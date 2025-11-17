<?php

namespace App\Livewire\Volunteer;

use App\Models\Event;
use App\Models\Registration;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

#[\Livewire\Attributes\Layout('components.layouts.app')]
class EventList extends Component
{
    use WithPagination;

    public $search = '';
    public $categoryFilter = '';

    protected $queryString = ['search', 'categoryFilter'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function register(Event $event)
    {
        $user = Auth::user();

        // If the user is a volunteer we require a complete profile before allowing registration
        if ($user && method_exists($user, 'isVolunteer') && $user->isVolunteer() && method_exists($user, 'hasCompleteProfile') && !$user->hasCompleteProfile()) {
            $payload = [
                'type' => 'error',
                'text' => 'Lengkapi profil Anda terlebih dahulu sebelum mendaftar ke acara.',
            ];

            // Prefer dispatchBrowserEvent (newer Livewire). If unavailable, fallback to dispatch/emit or session flash.
            if (method_exists($this, 'dispatchBrowserEvent')) {
                $this->dispatchBrowserEvent('swal', $payload);
            } elseif (method_exists($this, 'dispatch')) {
                // Older Livewire exposes dispatch which triggers component events client-side
                $this->dispatch('swal', $payload);
            } elseif (method_exists($this, 'emit')) {
                // emit may be available in some versions
                $this->emit('swal', $payload);
            } else {
                // Last-resort: session flash so user still sees message after full refresh
                session()->flash('error', 'Lengkapi profil Anda terlebih dahulu sebelum mendaftar ke acara.');
            }

            return;
        }

        // Check if already registered
        if (Registration::where('user_id', Auth::id())->where('event_id', $event->id)->exists()) {
            $payload = [
                'type' => 'error',
                'text' => 'Anda sudah terdaftar untuk acara ini.',
            ];

            if (method_exists($this, 'dispatchBrowserEvent')) {
                $this->dispatchBrowserEvent('swal', $payload);
            } elseif (method_exists($this, 'dispatch')) {
                $this->dispatch('swal', $payload);
            } elseif (method_exists($this, 'emit')) {
                $this->emit('swal', $payload);
            } else {
                session()->flash('error', $payload['text']);
            }

            return;
        }

        // Cek apakah kuota penuh
        if ($event->registrations()->whereIn('status', ['approved', 'pending'])->count() >= $event->quota) {
            $payload = [
                'type' => 'error',
                'text' => 'Kuota acara ini sudah penuh.',
            ];

            if (method_exists($this, 'dispatchBrowserEvent')) {
                $this->dispatchBrowserEvent('swal', $payload);
            } elseif (method_exists($this, 'dispatch')) {
                $this->dispatch('swal', $payload);
            } elseif (method_exists($this, 'emit')) {
                $this->emit('swal', $payload);
            } else {
                session()->flash('error', $payload['text']);
            }

            return;
        }

        // Buat pendaftaran baru
        Registration::create([
            'user_id' => Auth::id(),
            'event_id' => $event->id,
            'status' => 'pending', // default
        ]);

        $payload = [
            'type' => 'success',
            'text' => 'Pendaftaran berhasil!',
        ];

        if (method_exists($this, 'dispatchBrowserEvent')) {
            $this->dispatchBrowserEvent('swal', $payload);
        } elseif (method_exists($this, 'dispatch')) {
            $this->dispatch('swal', $payload);
        } elseif (method_exists($this, 'emit')) {
            $this->emit('swal', $payload);
        } else {
            session()->flash('message', $payload['text']);
        }
    }

    public function showEventDetail($eventId)
    {
        $this->dispatch('show-event-detail', eventId: $eventId);
    }

    public function render()
    {
        $events = Event::query()
            ->where('status', 'published') // Only show published events
            ->when($this->search, function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%')
                    ->orWhere('location', 'like', '%' . $this->search . '%');
            })
            ->when($this->categoryFilter, function ($query) {
                $query->where('category', $this->categoryFilter);
            })
            ->orderBy('start_date', 'asc')
            ->paginate(9);

        $registeredEventIds = Auth::user() ? Auth::user()->registrations->pluck('event_id')->toArray() : [];

        return view('livewire.volunteer.event-list', [
            'events' => $events,
            'registeredEventIds' => $registeredEventIds,
            'categories' => Event::where('status', 'published')->where('start_date', '>', now())->distinct()->pluck('category')->filter(),
        ]);
    }
}
