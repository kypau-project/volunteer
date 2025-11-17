<?php

namespace App\Livewire\Volunteer;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\Registration;

#[\Livewire\Attributes\Layout('components.layouts.app')]
class DashboardVolunteer extends Component
{
    use WithPagination;

    public function render()
    {
        $user = Auth::user();
        $registrations = $user->registrations()
            ->with("event")
            ->orderBy("created_at", "desc")
            ->paginate(10);

        $totalHours = $user->profile ? $user->profile->total_hours : 0;

        return view("livewire.volunteer.dashboard-volunteer", [
            "registrations" => $registrations,
            "totalHours" => $totalHours,
        ]);
    }
}
