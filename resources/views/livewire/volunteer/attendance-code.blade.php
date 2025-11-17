<div>
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Verifikasi Kehadiran</h3>
                <p class="text-subtitle text-muted">{{ $registration->event->title }}</p>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="row">
            <!-- Bagian Kiri: Form Absensi -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Form Absensi</h5>
                    </div>
                    <div class="card-body">
                        @if (session()->has('message'))
                        <div class="alert alert-success">
                            {{ session('message') }}
                        </div>
                        @endif

                        @if (session()->has('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                        @endif

                        <div class="form-group">
                            <label for="attendanceCode">Kode Absensi</label>
                            <div class="input-group">
                                <input wire:model="attendanceCode" type="text" class="form-control"
                                    placeholder="{{ !$registration->check_in ? 'Masukkan kode check-in' : 'Masukkan kode check-out' }}"
                                    maxlength="5">
                                <button wire:click="verifyAttendance" class="btn btn-primary">
                                    <i class="bi-check-circle me-2"></i>
                                    {{ !$registration->check_in ? 'Check-in' : 'Check-out' }}
                                </button>
                            </div>
                            @error('attendanceCode')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="mt-4">
                            <h6 class="mb-3">Status Kehadiran:</h6>
                            <ul class="list-unstyled">
                                <li class="d-flex align-items-center mb-3">
                                    <i class="{{ $registration->check_in ? 'bi-check-circle-fill text-success' : 'bi-circle text-muted' }} me-2"></i>
                                    <div>
                                        <strong>Check-in:</strong><br>
                                        @if($registration->check_in)
                                        {{ $registration->check_in->format('H:i:s, d M Y') }}
                                        @else
                                        Belum check-in
                                        @endif
                                    </div>
                                </li>
                                <li class="d-flex align-items-center mb-3">
                                    <i class="{{ $registration->check_out ? 'bi-check-circle-fill text-success' : 'bi-circle text-muted' }} me-2"></i>
                                    <div>
                                        <strong>Check-out:</strong><br>
                                        @if($registration->check_out)
                                        {{ $registration->check_out->format('H:i:s, d M Y') }}
                                        @else
                                        Belum check-out
                                        @endif
                                    </div>
                                </li>
                                @if($registration->hours_contributed > 0)
                                <li class="d-flex align-items-center">
                                    <i class="bi-clock-history text-primary me-2"></i>
                                    <div>
                                        <strong>Total Kontribusi:</strong><br>
                                        {{ number_format($registration->hours_contributed, 2) }} jam
                                    </div>
                                </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bagian Kanan: Informasi Event -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Informasi Event</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled">
                            <li class="mb-3">
                                <strong>Nama Event:</strong><br>
                                {{ $registration->event->title }}
                            </li>
                            <li class="mb-3">
                                <strong>Tanggal:</strong><br>
                                {{ $registration->event->start_date->format('d F Y') }}
                            </li>
                            <li class="mb-3">
                                <strong>Waktu:</strong><br>
                                {{ $registration->event->start_date->format('H:i') }} -
                                {{ $registration->event->end_date->format('H:i') }}
                            </li>
                            <li class="mb-3">
                                <strong>Lokasi:</strong><br>
                                {{ $registration->event->location }}
                            </li>
                            <li>
                                <strong>Status Pendaftaran:</strong><br>
                                <span class="badge bg-{{ $registration->status === 'approved' ? 'success' : 'warning' }}">
                                    {{ ucfirst($registration->status) }}
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>