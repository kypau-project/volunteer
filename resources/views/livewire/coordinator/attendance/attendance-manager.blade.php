<div>
    <div class="page-heading">
        <h3>Manajemen Kehadiran: {{ $event->title }}</h3>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div class="d-flex gap-3 flex-grow-1">
                    <input wire:model.live="search" type="text" class="form-control" placeholder="Cari relawan...">
                    <select wire:model.live="statusFilter" class="form-select w-25">
                        <option value="">Semua Status</option>
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                    </select>
                </div>
                @if($event->isFinished())
                <button wire:click="generateAllCertificates" class="btn btn-success ms-3" title="Generate sertifikat untuk semua relawan yang memenuhi syarat">
                    <i class="bi-file-earmark-check"></i> Buat Semua Sertifikat
                </button>
                @endif
            </div>

            <div wire:loading.delay wire:target="search, statusFilter">
                <div class="d-flex justify-content-center p-4">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>

            @if (session()->has('message'))
            <div class="alert alert-success mx-4 mt-4">
                {{ session('message') }}
            </div>
            @endif

            @if (session()->has('error'))
            <div class="alert alert-danger mx-4 mt-4">
                {{ session('error') }}
            </div>
            @endif

            <div class="card-body">
                <!-- Attendance Code Management Section -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4 class="mb-0">Kode Absensi Event</h4>
                                @if(!$showGenerateCode && !$event->check_in_code)
                                <button wire:click="generateAttendanceCodes" class="btn btn-primary">
                                    <i class="bi-upc-scan"></i> Generate Kode Baru
                                </button>
                                @endif
                            </div>
                            <div class="card-body">
                                @if($showGenerateCode || $event->check_in_code)
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Kode Check-in</label>
                                        <input wire:model="customCheckInCode" type="text" class="form-control"
                                            placeholder="5 karakter" maxlength="5"
                                            value="{{ $event->check_in_code }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Kode Check-out</label>
                                        <input wire:model="customCheckOutCode" type="text" class="form-control"
                                            placeholder="5 karakter" maxlength="5"
                                            value="{{ $event->check_out_code }}">
                                    </div>
                                    <div class="col-12">
                                        <button wire:click="saveAttendanceCodes" class="btn btn-primary">
                                            <i class="bi-save"></i> Simpan Kode
                                        </button>
                                    </div>
                                </div>
                                @endif

                                @if($event->check_in_code)
                                <div class="alert alert-info mt-3">
                                    <h6 class="alert-heading">Kode Aktif:</h6>
                                    <p class="mb-0">
                                        Check-in: <strong>{{ $event->check_in_code }}</strong> |
                                        Check-out: <strong>{{ $event->check_out_code }}</strong>
                                        <button class="btn btn-sm btn-outline-info ms-2"
                                            onclick="navigator.clipboard.writeText('Check-in: {{ $event->check_in_code }}\nCheck-out: {{ $event->check_out_code }}')">
                                            <i class="bi-clipboard"></i> Salin
                                        </button>
                                    </p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <div wire:loading.remove wire:target="search, statusFilter">
                            <table class="table table-striped" id="table1">
                                <thead>
                                    <tr>
                                        <th>Relawan</th>
                                        <th>Status</th>
                                        <th>Check In</th>
                                        <th>Check Out</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($registrations as $registration)
                                    <tr>
                                        <td>
                                            <p class="mb-0">{{ $registration->user->name }}</p>
                                            <small class="text-muted">{{ $registration->user->email }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $registration->status == 'approved' ? 'success' : 'warning' }}">
                                                {{ ucfirst($registration->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $registration->check_in ? $registration->check_in->format("H:i:s, d M") : "-" }}</td>
                                        <td>
                                            {{ $registration->check_out ? $registration->check_out->format("H:i:s, d M") : "-" }}
                                            @if($registration->hours_contributed > 0)
                                            <small class="text-primary">({{ number_format($registration->hours_contributed, 2) }} jam)</small>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                @if(!$registration->check_in)
                                                <button wire:click="checkIn({{ $registration->id }})" class="btn btn-success btn-sm">
                                                    <i class="bi-box-arrow-in-right"></i> Check In Manual
                                                </button>
                                                @elseif(!$registration->check_out)
                                                <button wire:click="checkOut({{ $registration->id }})" class="btn btn-danger btn-sm">
                                                    <i class="bi-box-arrow-right"></i> Check Out Manual
                                                </button>
                                                @else
                                                <span class="badge bg-secondary">Selesai</span>
                                                @if($registration->attended && $registration->check_out)
                                                @if($registration->certificate)
                                                <a href="{{ route('certificates.download', $registration->certificate->id) }}" target="_blank" class="btn btn-primary btn-sm">
                                                    <i class="bi-download"></i> Sertifikat
                                                </a>
                                                @elseif($event->isFinished())
                                                <button wire:click="generateCertificate({{ $registration->id }})" class="btn btn-info btn-sm" title="Buat sertifikat untuk relawan ini">
                                                    <i class="bi-file-earmark-text"></i> Buat Sertifikat
                                                </button>
                                                @endif
                                                @endif
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">
                                            Tidak ada relawan yang terdaftar.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center">
                            {{ $registrations->links() }}
                        </div>
                    </div>
                </div>
            </div>
    </section>
</div>