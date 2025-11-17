<?php

namespace App\Livewire\Coordinator;

use App\Models\Registration;
use App\Models\Certificate;
use Livewire\Component;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;

#[\Livewire\Attributes\Layout('components.layouts.app')]
class CertificateGenerator extends Component
{
    public Registration $registration;

    public function mount(Registration $registration)
    {
        // Check if the volunteer attended and checked out
        if (!$registration->attended || !$registration->check_out) {
            session()->flash('error', 'Sertifikat hanya dapat dibuat untuk relawan yang sudah check-out dan hadir.');
            return redirect()->route('admin.events.attendance', $registration->event);
        }

        $this->registration = $registration;
    }

    public function generateCertificate()
    {
        // Check if certificate already exists
        if ($this->registration->certificate) {
            session()->flash('error', 'Sertifikat sudah pernah dibuat.');
            return;
        }

        // 1. Create a unique certificate number
        $certificateNumber = 'CERT-' . now()->format('Ymd') . '-' . $this->registration->id;

        // 2. Save the certificate record (table has file_path and issued_at)
        // file_path is non-nullable in the DB, so create with an empty string and update after generating the file
        $certificate = Certificate::create([
            'registration_id' => $this->registration->id,
            'file_path' => '',
            'issued_at' => now(),
        ]);

        session()->flash('message', 'Sertifikat berhasil dibuat!');

        // Generate PDF immediately and persist to storage
        $data = [
            'name' => $this->registration->user->name,
            'eventTitle' => $this->registration->event->title,
            'hours' => number_format($this->registration->hours_contributed, 2),
            'issueDate' => $certificate->issued_at->format('d F Y'),
            'certificateNumber' => 'CERT-' . $certificate->issued_at->format('Ymd') . '-' . $certificate->id,
        ];

        $pdf = Pdf::loadView('certificates.template', $data)->setPaper('a4', 'landscape');

        $safeName = Str::slug($this->registration->user->name);
        $safeEvent = Str::slug($this->registration->event->title);
        $filename = "Sertifikat-{$safeName}-{$safeEvent}-{$certificate->id}.pdf";
        $path = "certificates/{$filename}";

        // Store PDF to disk (storage/app/certificates/...)
        Storage::put($path, $pdf->output());

        // Update certificate record with file path
        $certificate->file_path = $path;
        $certificate->save();

        // Redirect to download route which will serve the stored file
        return redirect()->route('certificates.download', $certificate->id);
    }

    public function downloadCertificate(Certificate $certificate = null)
    {
        // For Livewire, redirect to download route instead of streaming directly from an action.
        $certificate = $certificate ?? $this->registration->certificate;

        if (!$certificate) {
            session()->flash('error', 'Sertifikat belum dibuat.');
            return;
        }

        return redirect()->route('certificates.download', $certificate->id);
    }

    public function render()
    {
        return view('livewire.coordinator.certificate-generator');
    }
}
