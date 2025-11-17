<?php

namespace App\Livewire\Admin;

use App\Models\Registration;
use App\Models\User;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

#[\Livewire\Attributes\Layout('components.layouts.app')]
class DashboardAdmin extends Component
{
    public $chartData;

    public function mount()
    {
        $this->generateChartData();
    }

    public function generateChartData()
    {
        // Data for registrations per month (Bar Chart)
        $registrations = Registration::select(
            DB::raw('count(id) as count'),
            DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month')
        )
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
        // Total All Users (not just volunteers)
        $totalUsers = User::count();

        // Total volunteer hours
        $totalHours = User::where('role', 'volunteer')->with('profile')->get()->sum(fn($user) => $user->profile->total_hours ?? 0);
        $totalEvents = \App\Models\Event::count();

        try {
            $activeToday = User::whereDate('last_login_at', Carbon::today())->count();
        } catch (\Exception $e) {
            $activeToday = 0;
        }

        $roleStats = [
            'volunteer' => User::where('role', 'volunteer')->count(),
            'coordinator' => User::where('role', 'coordinator')->count(),
            'admin' => User::where('role', 'admin')->count(),
        ];

        // Get all users created this month
        $newThisMonth = User::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();

        return view('livewire.admin.dashboard-admin', [
            'totalVolunteers' => $totalUsers, // Changed to show total users
            'totalHours' => $totalHours,
            'totalEvents' => $totalEvents,
            'activeToday' => $activeToday,
            'roleStats' => $roleStats,
            'newThisMonth' => $newThisMonth,
            'chartData' => $this->chartData,
        ]);
    }
}
