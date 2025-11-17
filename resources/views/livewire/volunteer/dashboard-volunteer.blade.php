@section('title', 'Dashboard Relawan')

<div>
    <div class="page-heading">
        <h3>Dashboard Relawan</h3>
    </div>
    <div class="page-content">
        <section class="row">
            <div class="col-12">
                <div class="row">
                    <div class="col-6 col-lg-4 col-md-6">
                        <div class="card">
                            <div class="card-body px-4 py-4-5">
                                <div class="row">
                                    <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                        <div class="stats-icon blue mb-2">
                                            <i class="bi-clock"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                        <h6 class="text-muted font-semibold">Total Jam Kontribusi</h6>
                                        <h6 class="font-extrabold mb-0">{{ number_format($totalHours, 2) }} Jam</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-4 col-md-6">
                        <div class="card">
                            <div class="card-body px-4 py-4-5">
                                <div class="row">
                                    <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                        <div class="stats-icon green mb-2">
                                            <i class="bi-calendar-check"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                        <h6 class="text-muted font-semibold">Acara Diikuti</h6>
                                        <h6 class="font-extrabold mb-0">{{ $registrations->total() }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-4 col-md-6">
                        <div class="card">
                            <div class="card-body px-4 py-4-5">
                                <div class="row">
                                    <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                        <div class="stats-icon purple mb-2">
                                            <i class="bi-calendar-event"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                        <h6 class="text-muted font-semibold">Acara Mendatang</h6>
                                        <h6 class="font-extrabold mb-0">{{ $registrations->where("event.start_date", ">", now())->count() }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Riwayat Partisipasi Acara</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Nama Acara</th>
                                        <th>Tanggal</th>
                                        <th>Status</th>
                                        <th>Kehadiran</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($registrations as $registration)
                                    <tr>
                                        <td>{{ $registration->event->title }}</td>
                                        <td>{{ $registration->event->start_date->format('d M Y') }}</td>
                                        <td>
                                            @if($registration->check_out || $registration->event->end_date < now())
                                                <span class="badge bg-info">Selesai</span>
                                                @else
                                                @switch($registration->status)
                                                @case('pending')
                                                <span class="badge bg-warning">Menunggu</span>
                                                @break
                                                @case('approved')
                                                <span class="badge bg-success">Disetujui</span>
                                                @break
                                                @case('rejected')
                                                <span class="badge bg-danger">Ditolak</span>
                                                @break
                                                @case('cancelled')
                                                <span class="badge bg-secondary">Dibatalkan</span>
                                                @break
                                                @endswitch
                                                @endif
                                        </td>
                                        <td>
                                            @if($registration->attended)
                                            <span class="badge bg-success">Hadir</span>
                                            @else
                                            <span class="badge bg-secondary">Belum Hadir</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($registration->event->start_date->isToday() && !$registration->check_out)
                                            <a href="{{ route('volunteer.registrations.attendance', $registration) }}" class="btn btn-sm btn-primary">
                                                <i class="bi-qr-code"></i> Absensi
                                            </a>
                                            @elseif($registration->attended && $registration->check_out && !$registration->feedback)
                                            <a href="{{ route('volunteer.feedback.create', $registration) }}" class="btn btn-sm btn-primary">
                                                <i class="bi-chat-dots"></i> Beri Feedback
                                            </a>
                                            @elseif($registration->feedback)
                                            <span class="badge bg-success">
                                                <i class="bi-check-circle"></i> Feedback Diberikan
                                            </span>
                                            @else
                                            <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Anda belum pernah mendaftar di acara manapun.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-center mt-3">
                            {{ $registrations->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>