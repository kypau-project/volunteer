<?php

namespace App\Livewire\Admin;

use App\Models\Event;
use App\Models\User;
use App\Models\Registration;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\VolunteersExport;
use App\Exports\AttendanceExport;

#[\Livewire\Attributes\Layout('layouts.app')]
class ReportGenerator extends Component
{
    public $selectedEventId;
    public $events;

    public function mount()
    {
        $this->events = Event::orderBy('start_date', 'desc')->get();
    }

    public function exportVolunteers()
    {
        return Excel::download(new VolunteersExport, 'relawan_data_' . now()->format('Ymd_His') . '.xlsx');
    }

    public function exportAttendance()
    {
        if (!$this->selectedEventId) {
            session()->flash('error', 'Pilih acara terlebih dahulu untuk mengunduh laporan kehadiran.');
            return;
        }

        $event = Event::find($this->selectedEventId);
        if (!$event) {
            session()->flash('error', 'Acara tidak ditemukan.');
            return;
        }

        return Excel::download(new AttendanceExport($this->selectedEventId), 'kehadiran_' . \Str::slug($event->title) . '_' . now()->format('Ymd_His') . '.xlsx');
    }

    public function render()
    {
        // Simple statistics for display
        $totalVolunteers = User::where('role', 'volunteer')->count();
        $totalEvents = Event::count();
        $totalHours = User::where('role', 'volunteer')->with('profile')->get()->sum(fn($user) => $user->profile->total_hours ?? 0);

        // Get feedback count if table exists, otherwise return 0
        try {
            $totalFeedbacks = \App\Models\Feedback::count();
        } catch (\Exception $e) {
            $totalFeedbacks = 0;
        }

        return view('livewire.admin.report-generator', [
            'totalVolunteers' => $totalVolunteers,
            'totalEvents' => $totalEvents,
            'totalHours' => $totalHours,
            'totalFeedbacks' => $totalFeedbacks,
        ]);
    }
}
