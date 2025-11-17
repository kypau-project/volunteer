<?php

namespace App\Livewire\Volunteer;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;

#[\Livewire\Attributes\Layout('components.layouts.app')]
class MyCertificates extends Component
{
    use WithPagination;

    public $search = '';

    protected $queryString = ['search'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $user = Auth::user();

        $certificates = $user->registrations()
            ->whereHas('certificate')
            ->with(['certificate', 'event', 'user'])
            ->when($this->search, function ($query) {
                $query->whereHas('event', function ($q) {
                    $q->where('title', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.volunteer.my-certificates', [
            'certificates' => $certificates,
        ]);
    }
}
