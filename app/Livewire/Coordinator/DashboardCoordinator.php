<?php

namespace App\Livewire\Coordinator;

use App\Models\Registration;
use App\Models\User;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

#[\Livewire\Attributes\Layout('components.layouts.app')]
class DashboardCoordinator extends Component
{
    public $chartData;

    public function mount()
    {
        $this->generateChartData();
    }

    public function generateChartData()
    {
        // Data for registrations per month (Bar Chart) - Only for volunteers
        $registrations = User::where('role', 'volunteer')
            ->select(
                DB::raw('count(id) as count'),
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month')
            )
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $labels = $registrations->pluck('month')->map(fn($m) => Carbon::parse($m . '-01')->format('M Y'))->toArray();
        $data = $registrations->pluck('count')->toArray();

        $this->chartData = [
            'labels' => $labels,
            'data' => $data,
        ];
    }

    public function render()
    {
        $totalVolunteers = User::where('role', 'volunteer')->count();
        $activeVolunteers = User::where('role', 'volunteer')
            ->whereDate('last_login_at', '>=', Carbon::now()->subDays(30))
            ->count();

        $newVolunteersThisMonth = User::where('role', 'volunteer')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();

        return view('livewire.coordinator.dashboard-coordinator', [
            'totalVolunteers' => $totalVolunteers,
            'activeVolunteers' => $activeVolunteers,
            'newVolunteersThisMonth' => $newVolunteersThisMonth,
            'chartData' => $this->chartData,
        ]);
    }
}
