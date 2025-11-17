<![CDATA[<div>
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
            <div class="col-md-6">
                <div class="card">
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
                                       placeholder="{{ !$registration->check_in ? 'Masukkan kode check-in' : 'Masukkan kode check-out' }}">
                                <button wire:click="verifyAttendance" class="btn btn-primary">
                                    {{ !$registration->check_in ? 'Check-in' : 'Check-out' }}
                                </button>
                            </div>
                        </div>

                        <div class="mt-4">
                            <h6>Status Kehadiran:</h6>
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <i class="{{ $registration->check_in ? 'bi-check-circle-fill text-success' : 'bi-circle text-muted' }}"></i>
                                    Check-in: 
                                    @if($registration->check_in)
                                        {{ $registration->check_in->format('H:i') }}
                                        @if($registration->manual_attendance)
                                            <small>(Manual oleh coordinator)</small>
                                        @endif
                                    @else
                                        Belum check-in
                                    @endif
                                </li>
                                <li>
                                    <i class="{{ $registration->check_out ? 'bi-check-circle-fill text-success' : 'bi-circle text-muted' }}"></i>
                                    Check-out:
                                    @if($registration->check_out)
                                        {{ $registration->check_out->format('H:i') }}
                                        @if($registration->manual_attendance)
                                            <small>(Manual oleh coordinator)</small>
                                        @endif
                                    @else
                                        Belum check-out
                                    @endif
                                </li>
                                @if($registration->hours_contributed > 0)
                                    <li class="mt-2">
                                        <strong>Total Kontribusi:</strong> 
                                        {{ number_format($registration->hours_contributed, 2) }} jam
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Informasi Event</h4>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <strong>Nama Event:</strong><br>
                                {{ $registration->event->title }}
                            </li>
                            <li class="mb-2">
                                <strong>Tanggal:</strong><br>
                                {{ $registration->event->start_date->format('d F Y') }}
                            </li>
                            <li class="mb-2">
                                <strong>Waktu:</strong><br>
                                {{ $registration->event->start_date->format('H:i') }} - 
                                {{ $registration->event->end_date->format('H:i') }}
                            </li>
                            <li>
                                <strong>Lokasi:</strong><br>
                                {{ $registration->event->location }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>]]>