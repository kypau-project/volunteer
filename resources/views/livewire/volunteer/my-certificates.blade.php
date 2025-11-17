<div>
    <div class="page-heading">
        <h3>Sertifikat Saya</h3>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-header">
                <input wire:model.live="search" type="text" class="form-control" placeholder="Cari sertifikat berdasarkan nama acara...">
            </div>

            <div wire:loading.delay wire:target="search">
                <div class="d-flex justify-content-center p-4">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>

            <div class="card-body">
                @if ($certificates->isEmpty())
                <div class="text-center py-5">
                    <i class="bi bi-file-earmark-x" style="font-size: 3rem; color: #ccc;"></i>
                    <p class="text-muted mt-3">Anda belum memiliki sertifikat</p>
                </div>
                @else
                <div class="row g-4">
                    @foreach ($certificates as $registration)
                    <div class="col-md-6 col-lg-4">
                        <div class="card certificate-card h-100" style="border: 2px solid #3950A2; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                            <div class="card-body">
                                <div class="mb-3">
                                    <i class="bi bi-patch-check" style="font-size: 2.5rem; color: #3950A2;"></i>
                                </div>
                                <h5 class="card-title">{{ $registration->event->title }}</h5>
                                <p class="card-text text-muted mb-2">
                                    <small>
                                        <i class="bi bi-calendar-event"></i>
                                        {{ $registration->event->start_date->format('d M Y') }} - {{ $registration->event->end_date->format('d M Y') }}
                                    </small>
                                </p>
                                <p class="card-text text-muted mb-2">
                                    <small>
                                        <i class="bi bi-clock"></i>
                                        Kontribusi: {{ number_format($registration->hours_contributed, 2) }} jam
                                    </small>
                                </p>
                                <p class="card-text text-muted mb-3">
                                    <small>
                                        <i class="bi bi-check-circle"></i>
                                        Dikeluarkan: {{ $registration->certificate->issued_at->format('d M Y') }}
                                    </small>
                                </p>
                                <a href="{{ route('certificates.download', $registration->certificate->id) }}" target="_blank" class="btn btn-primary btn-sm w-100">
                                    <i class="bi bi-download"></i> Download Sertifikat
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="d-flex justify-content-center mt-4">
                    {{ $certificates->links() }}
                </div>
                @endif
            </div>
        </div>
    </section>
</div>