<?php

namespace App\Livewire\Coordinator\Attendance;

use App\Models\Event;
use App\Models\Registration;
use App\Models\Certificate;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

#[\Livewire\Attributes\Layout('components.layouts.app')]
class AttendanceManager extends Component
{
    use WithPagination;

    public $event;
    public $search = '';
    public $statusFilter = '';
    public $showGenerateCode = false;
    public $customCheckInCode = '';
    public $customCheckOutCode = '';

    protected $queryString = ['search', 'statusFilter'];

    public function mount(Event $event)
    {
        $this->event = $event;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function generateAttendanceCodes()
    {
        // Generate random 5-digit codes
        $this->customCheckInCode = strtoupper(Str::random(5));
        $this->customCheckOutCode = strtoupper(Str::random(5));
        $this->showGenerateCode = true;
    }

    public function saveAttendanceCodes()
    {
        // Validate 5-digit codes
        $this->validate([
            'customCheckInCode' => 'required|string|size:5',
            'customCheckOutCode' => 'required|string|size:5|different:customCheckInCode',
        ], [
            'customCheckInCode.required' => 'Kode check-in harus diisi.',
            'customCheckInCode.size' => 'Kode check-in harus 5 karakter.',
            'customCheckOutCode.required' => 'Kode check-out harus diisi.',
            'customCheckOutCode.size' => 'Kode check-out harus 5 karakter.',
            'customCheckOutCode.different' => 'Kode check-out harus berbeda dengan kode check-in.',
        ]);

        $this->event->update([
            'check_in_code' => strtoupper($this->customCheckInCode),
            'check_out_code' => strtoupper($this->customCheckOutCode),
        ]);

        session()->flash('message', "Kode absensi berhasil disimpan. Check-in: {$this->customCheckInCode}, Check-out: {$this->customCheckOutCode}");
        $this->showGenerateCode = false;
    }

    public function checkIn(Registration $registration)
    {
        if ($registration->check_in) {
            session()->flash('error', 'Relawan ini sudah check-in.');
            return;
        }

        $registration->update([
            'check_in' => now(),
            'status' => 'approved',
            'manual_attendance' => true
        ]);

        session()->flash('message', 'Check-in manual berhasil untuk ' . $registration->user->name);
    }

    public function checkOut(Registration $registration)
    {
        if (!$registration->check_in) {
            session()->flash('error', 'Relawan ini belum check-in.');
            return;
        }

        if ($registration->check_out) {
            session()->flash('error', 'Relawan ini sudah check-out.');
            return;
        }

        $checkOutTime = now();
        $checkInTime = Carbon::parse($registration->check_in);
        $hoursContributed = $checkOutTime->diffInMinutes($checkInTime) / 60;

        $registration->update([
            'check_out' => $checkOutTime,
            'hours_contributed' => $hoursContributed,
            'attended' => true,
            'manual_attendance' => true
        ]);

        // Update total_hours in volunteer_profiles
        $profile = $registration->user->profile;
        if ($profile) {
            $profile->total_hours += $hoursContributed;
            $profile->save();
        }

        session()->flash('message', 'Check-out manual berhasil untuk ' . $registration->user->name . '. Kontribusi: ' . number_format($hoursContributed, 2) . ' jam.');
    }

    /**
     * Generate certificate for a single registration
     */
    public function generateCertificate(Registration $registration)
    {
        // Check if event has finished
        if (!$this->event->isFinished()) {
            session()->flash('error', 'Sertifikat hanya dapat dibuat setelah acara selesai.');
            return;
        }

        // Check if volunteer attended and checked out
        if (!$registration->attended || !$registration->check_out) {
            session()->flash('error', 'Sertifikat hanya dapat dibuat untuk relawan yang sudah hadir dan check-out.');
            return;
        }

        // Check if certificate already exists
        if ($registration->certificate) {
            session()->flash('error', 'Sertifikat untuk relawan ini sudah pernah dibuat.');
            return;
        }

        try {
            $this->createCertificate($registration);
            session()->flash('message', 'Sertifikat berhasil dibuat untuk ' . $registration->user->name . '.');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal membuat sertifikat: ' . $e->getMessage());
        }
    }

    /**
     * Generate certificates for all eligible registrations
     */
    public function generateAllCertificates()
    {
        // Check if event has finished
        if (!$this->event->isFinished()) {
            session()->flash('error', 'Sertifikat hanya dapat dibuat setelah acara selesai.');
            return;
        }

        // Get all registrations that attended, checked out, and don't have certificate yet
        $registrations = $this->event->registrations()
            ->where('attended', true)
            ->where('check_out', '!=', null)
            ->whereDoesntHave('certificate')
            ->get();

        if ($registrations->isEmpty()) {
            session()->flash('error', 'Tidak ada relawan yang memenuhi syarat untuk pembuatan sertifikat atau semua sertifikat sudah dibuat.');
            return;
        }

        $successCount = 0;
        $failedCount = 0;

        foreach ($registrations as $registration) {
            try {
                $this->createCertificate($registration);
                $successCount++;
            } catch (\Exception $e) {
                $failedCount++;
            }
        }

        $message = "Sertifikat berhasil dibuat: {$successCount}";
        if ($failedCount > 0) {
            $message .= ", Gagal: {$failedCount}";
        }
        session()->flash('message', $message . '.');
    }

    /**
     * Helper method to create a certificate
     */
    private function createCertificate(Registration $registration)
    {
        // Create certificate record
        $certificate = Certificate::create([
            'registration_id' => $registration->id,
            'file_path' => '',
            'issued_at' => now(),
        ]);

        // Prepare data for PDF
        $data = [
            'name' => $registration->user->name,
            'eventTitle' => $this->event->title,
            'hours' => number_format($registration->hours_contributed, 2),
            'issueDate' => $certificate->issued_at->format('d F Y'),
            'certificateNumber' => 'CERT-' . $certificate->issued_at->format('Ymd') . '-' . $certificate->id,
        ];

        // Generate PDF
        $pdf = Pdf::loadView('certificates.template', $data)->setPaper('a4', 'landscape');

        // Store PDF
        $safeName = Str::slug($registration->user->name);
        $safeEvent = Str::slug($this->event->title);
        $filename = "Sertifikat-{$safeName}-{$safeEvent}-{$certificate->id}.pdf";
        $path = "certificates/{$filename}";

        Storage::put($path, $pdf->output());

        // Update certificate with file path
        $certificate->update([
            'file_path' => $path,
        ]);
    }

    public function render()
    {
        $registrations = $this->event->registrations()
            ->whereIn('status', ['pending', 'approved'])
            ->with('user')
            ->when($this->search, function ($query) {
                $query->whereHas('user', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->orderBy('created_at', 'asc')
            ->paginate(10);

        return view('livewire.coordinator.attendance.attendance-manager', [
            'registrations' => $registrations,
        ]);
    }
}
