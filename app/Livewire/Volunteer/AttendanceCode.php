<?php

namespace App\Livewire\Volunteer;

use App\Models\Registration;
use Carbon\Carbon;
use Livewire\Component;

#[\Livewire\Attributes\Layout('components.layouts.app')]
class AttendanceCode extends Component
{
    public $registration;
    public $attendanceCode = '';

    public function mount(Registration $registration)
    {
        $this->registration = $registration;

        // Redirect if event is not today
        if (!$this->registration->event->start_date->isToday()) {
            session()->flash('error', 'Verifikasi kehadiran hanya dapat dilakukan pada hari event berlangsung.');
            return redirect()->route('volunteer.dashboard');
        }
    }

    public function verifyAttendance()
    {
        $this->validate([
            'attendanceCode' => 'required|string|size:5'
        ], [
            'attendanceCode.required' => 'Kode absensi harus diisi.',
            'attendanceCode.size' => 'Kode absensi harus 5 karakter.'
        ]);

        $code = strtoupper($this->attendanceCode);

        if (!$this->registration->check_in && $code === $this->registration->event->check_in_code) {
            $this->registration->update([
                'check_in' => now(),
                'status' => 'approved'
            ]);

            session()->flash('message', 'Check-in berhasil! Selamat mengikuti event.');
            $this->reset('attendanceCode');
            return;
        }

        if (
            $this->registration->check_in && !$this->registration->check_out &&
            $code === $this->registration->event->check_out_code
        ) {
            $checkOutTime = now();
            $checkInTime = Carbon::parse($this->registration->check_in);
            $hoursContributed = $checkOutTime->diffInMinutes($checkInTime) / 60;

            $this->registration->update([
                'check_out' => $checkOutTime,
                'hours_contributed' => $hoursContributed,
                'attended' => true
            ]);

            // Update total_hours in volunteer_profiles
            $profile = $this->registration->user->profile;
            if ($profile) {
                $profile->total_hours += $hoursContributed;
                $profile->save();
            }

            session()->flash('message', 'Check-out berhasil! Terima kasih atas kontribusi Anda. Total jam: ' .
                number_format($hoursContributed, 2) . ' jam.');
            $this->reset('attendanceCode');
            return;
        }

        session()->flash('error', 'Kode absensi tidak valid.');
    }

    public function render()
    {
        return view('livewire.volunteer.attendance-code');
    }
}
