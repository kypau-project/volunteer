<?php

namespace App\Livewire\Admin\Event;

use App\Models\Event;
use Livewire\Component;
use Livewire\WithPagination;

class EventIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';

    protected $queryString = ['search', 'statusFilter'];

    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function render()
    {
        $events = Event::query()
            ->when($this->search, function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%')
                      ->orWhere('location', 'like', '%' . $this->search . '%');
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->orderBy('start_date', 'desc')
            ->paginate(10);

        return view('livewire.admin.event.event-index', [
            'events' => $events,
        ]);
    }

    public function deleteEvent(Event $event)
    {
        $event->delete();
        session()->flash('message', 'Acara berhasil dihapus.');
    }
}
