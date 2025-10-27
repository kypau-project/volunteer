<?php

namespace App\Livewire\Admin\Event;

use App\Models\Event;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class EventForm extends Component
{
    public $event;
    public $title;
    public $description;
    public $category;
    public $start_date;
    public $end_date;
    public $location;
    public $quota;
    public $required_skills;
    public $status = 'draft';

    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'category' => 'nullable|string|max:255',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after:start_date',
        'location' => 'required|string|max:255',
        'quota' => 'required|integer|min:1',
        'required_skills' => 'nullable|string',
        'status' => 'required|in:draft,published,cancelled',
    ];

    public function mount(Event $event = null)
    {
        // Initialize event as a new Event model if it's null
        $this->event = $event ?? new Event();

        if ($this->event->exists) {
            $this->title = $this->event->title;
            $this->description = $this->event->description;
            $this->category = $this->event->category;
            $this->start_date = $this->event->start_date->format('Y-m-d\TH:i');
            $this->end_date = $this->event->end_date->format('Y-m-d\TH:i');
            $this->location = $this->event->location;
            $this->quota = $this->event->quota;
            // Handle array to string conversion for display
            $this->required_skills = is_array($this->event->required_skills) ? implode(', ', $this->event->required_skills) : $this->event->required_skills;
            $this->status = $this->event->status;
        }
    }

    public function save()
    {
        $this->validate();

        // Convert comma-separated string back to array for storage
        $skillsArray = array_filter(array_map('trim', explode(',', $this->required_skills)));

        $data = [
            'title' => $this->title,
            'description' => $this->description,
            'category' => $this->category,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'location' => $this->location,
            'quota' => $this->quota,
            'required_skills' => $skillsArray,
            'status' => $this->status,
            'created_by' => Auth::id(),
        ];

        if ($this->event->exists) {
            $this->event->update($data);
            session()->flash('message', 'Acara berhasil diperbarui.');
        } else {
            Event::create($data);
            session()->flash('message', 'Acara berhasil dibuat.');
        }

        return redirect()->route('admin.events.index');
    }

    public function render()
    {
        return view('livewire.admin.event.event-form');
    }
}

