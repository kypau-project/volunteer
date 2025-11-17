<?php

namespace App\Livewire\Coordinator\Event;

use App\Models\Event;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EventForm extends Component
{
    use WithFileUploads;

    public $event;
    public $title;
    public $description;
    public $image;
    public $banner;
    public $category;
    public $start_date;
    public $end_date;
    public $location;
    public $maps_url;
    public $quota;
    public $required_skills; // legacy comma-separated string
    public $required_skills_list = [];
    public $custom_required_skill = '';
    public $status = 'published';

    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'banner' => 'nullable|image|max:2048',
        'category' => 'nullable|string|max:255',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after:start_date',
        'location' => 'required|string|max:255',
        'maps_url' => 'nullable|url',
        'quota' => 'required|integer|min:1',
        'required_skills' => 'nullable|string',
        'required_skills_list' => 'nullable|array',
        'status' => 'required|in:draft,published,cancelled',
    ];

    public function mount($event = null)
    {
        // If $event is provided, find the event by ID, otherwise create a new Event
        $this->event = $event ? Event::findOrFail($event) : new Event();

        if ($this->event->exists) {
            $this->title = $this->event->title;
            $this->description = $this->event->description;
            $this->category = $this->event->category;
            $this->start_date = $this->event->start_date->format('Y-m-d\TH:i');
            $this->end_date = $this->event->end_date->format('Y-m-d\TH:i');
            $this->location = $this->event->location;
            $this->maps_url = $this->event->maps_url;
            $this->quota = $this->event->quota;
            // Handle array to string conversion for display and interactive list
            $this->required_skills = is_array($this->event->required_skills) ? implode(', ', $this->event->required_skills) : $this->event->required_skills;
            $this->required_skills_list = is_array($this->event->required_skills) ? $this->event->required_skills : (is_string($this->required_skills) && strlen(trim($this->required_skills)) ? array_map('trim', explode(',', $this->required_skills)) : []);
            $this->status = $this->event->status;
        }
    }

    public function getGoogleMapsUrl()
    {
        if (empty($this->location)) return null;
        return 'https://www.google.com/maps/search/?api=1&query=' . urlencode($this->location);
    }

    public function save()
    {
        $this->validate();

        // Buat array data dulu
        $data = [
            'title' => $this->title,
            'description' => $this->description,
            'category' => $this->category,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'location' => $this->location,
            'maps_url' => $this->maps_url,
            'quota' => $this->quota,
            'status' => $this->status,
            'created_by' => Auth::id(),
        ];

        // Upload banner dan tambahkan ke $data jika ada
        if ($this->banner) {
            if ($this->event->exists && $this->event->banner) {
                Storage::disk('public')->delete($this->event->banner);
            }
            $bannerPath = $this->banner->store('events/banners', 'public');
            $data['banner'] = $bannerPath;  // Sekarang banner path masuk ke array $data
        }

        // Tambahkan required skills ke $data
        $skillsArray = !empty($this->required_skills_list)
            ? $this->required_skills_list
            : array_filter(array_map('trim', explode(',', $this->required_skills)));
        $data['required_skills'] = $skillsArray;

        if ($this->event->exists) {
            $this->event->update($data);
            session()->flash('message', 'Acara berhasil diperbarui.');
        } else {
            Event::create($data);
            session()->flash('message', 'Acara berhasil dibuat.');
        }

        return redirect()->route('coordinator.events.index');
    }

    // Interactive skill/tag methods (similar to profile)
    public function addRequiredSkill()
    {
        if (!empty($this->custom_required_skill)) {
            $skill = trim($this->custom_required_skill);
            if ($skill && !in_array($skill, $this->required_skills_list)) {
                $this->required_skills_list[] = $skill;
            }
            $this->custom_required_skill = '';
        }
    }

    public function removeRequiredSkill($skill)
    {
        $this->required_skills_list = array_values(array_filter($this->required_skills_list, function ($s) use ($skill) {
            return $s !== $skill;
        }));
    }

    public function render()
    {
        return view('livewire.coordinator.event.event-form');
    }
}
