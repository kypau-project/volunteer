<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CertificateController extends Controller
{
    /**
     * Stream the certificate PDF for download.
     */
    public function download(Certificate $certificate)
    {
        $certificate->load(['registration.user', 'registration.event']);
        $registration = $certificate->registration;

        if (! $registration) {
            abort(404);
        }

        // Authorization: user can download their own certificate or coordinator/admin of the event
        $user = Auth::user();
        if ($user->id !== $registration->user_id && $user->id !== $registration->event->created_by) {
            abort(403, 'Unauthorized');
        }

        $certificateNumber = 'CERT-' . $certificate->issued_at->format('Ymd') . '-' . $certificate->id;

        $data = [
            'name' => $registration->user->name,
            'eventTitle' => $registration->event->title,
            'hours' => number_format($registration->hours_contributed, 2),
            'issueDate' => $certificate->issued_at->format('d F Y'),
            'certificateNumber' => $certificateNumber,
        ];

        // If a stored file exists, serve it directly
        if ($certificate->file_path && Storage::exists($certificate->file_path)) {
            $storedName = basename($certificate->file_path);
            return Storage::download($certificate->file_path, $storedName);
        }

        // Otherwise generate on-the-fly and stream
        $pdf = Pdf::loadView('certificates.template', $data)->setPaper('a4', 'landscape');

        $filename = 'Sertifikat-' . Str::slug($registration->user->name) . '-' . Str::slug($registration->event->title) . '.pdf';

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, $filename);
    }
}
