<?php

namespace App\Livewire\Coordinator;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

#[\Livewire\Attributes\Layout('components.layouts.app')]
class ListVolunteer extends Component
{
    use WithPagination;

    public $search = '';
    protected $paginationTheme = 'bootstrap';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $users = User::where('role', 'volunteer')
            ->where(function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->paginate(10);

        return view('livewire.coordinator.list-volunteer', [
            'users' => $users
        ]);
    }
}
