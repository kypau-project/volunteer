@section('title', 'Daftar Acara')

@push('js')
<script>
    document.addEventListener('livewire:initialized', () => {
        @this.on('show-event-detail', (event) => {
            let modal = new bootstrap.Modal(document.getElementById('eventDetailModal-' + event.eventId));
            modal.show();
        });
    });
</script>
@endpush

<div>
    <div class="page-heading">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Daftar Acara Tersedia</h3>
            </div>
        </div>
    </div>

    <div class="page-content">
        <!-- Flash Messages -->
        @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible show fade">
            <i class="bi-check-circle"></i> {{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
        @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible show fade">
            <i class="bi-exclamation-circle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <section class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-md-6 mb-3">
                                <div class="form-group">
                                    <label>Cari Acara</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi-search"></i></span>
                                        <input wire:model.lazy="search" type="text" class="form-control" placeholder="Cari berdasarkan judul atau lokasi...">
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-4 mb-3">
                                <div class="form-group">
                                    <label>Kategori</label>
                                    <select wire:model.lazy="categoryFilter" class="form-select">
                                        <option value="">Semua Kategori</option>
                                        @foreach($categories as $category)
                                        <option value="{{ $category }}">{{ $category }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Event Cards -->
            @forelse ($events as $event)
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card">
                    <div class="card-content">
                        @if($event->banner)
                        <img src="{{ Storage::url($event->banner) }}" class="card-img-top" alt="{{ $event->title }}" style="height: 200px; object-fit: cover;">
                        @else
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                            <i class="bi-calendar-event display-4 text-muted"></i>
                        </div>
                        @endif
                        <div class="card-body">
                            <h4 class="card-title">{{ $event->title }}</h4>
                            <div class="mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi-geo-alt text-primary me-2"></i>
                                    <span>{{ $event->location }}</span>
                                    @if($event->maps_url)
                                    <a href="{{ $event->maps_url }}" target="_blank" class="btn btn-sm" title="Buka di Google Maps">
                                        <i class="bi-arrow-up-right text-primary"></i>
                                    </a>
                                    @endif
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi-calendar-event text-primary me-2"></i>
                                    <span>{{ $event->start_date->format('d M Y') }} - {{ $event->end_date->format('d M Y') }}</span>
                                </div>
                            </div>
                            <p class="card-text">{{ Str::limit($event->description, 100) }}</p>
                            @php
                            $registered = $event->registrations->whereIn('status', ['approved', 'pending'])->count();
                            $percentage = ($registered / $event->quota) * 100;
                            @endphp
                            <div class="progress progress-primary mb-3" style="height: 10px">
                                <div class="progress-bar" role="progressbar" style="width: {{ $percentage }}%"
                                    aria-valuenow="{{ $registered }}" aria-valuemin="0" aria-valuemax="{{ $event->quota }}">
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="text-muted small">
                                    <i class="bi-people me-1"></i>
                                    Terdaftar: {{ $registered }}/{{ $event->quota }}
                                </span>
                                <span class="text-muted small">
                                    <i class="bi-person-plus me-1"></i>
                                    Tersisa: {{ $event->quota - $registered }}
                                </span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <button wire:click="showEventDetail({{ $event->id }})" class="btn btn-outline-primary btn-sm">
                                    <i class="bi-eye me-1"></i>
                                    Detail
                                </button>
                                @if(in_array($event->id, $registeredEventIds))
                                <span class="badge bg-success">
                                    <i class="bi-check-circle me-1"></i>Terdaftar
                                </span>
                                @else
                                <button wire:click="register({{ $event->id }})" wire:loading.attr="disabled"
                                    class="btn btn-primary">
                                    <i class="bi-person-plus me-1"></i>
                                    Daftar Sekarang
                                </button>
                                @endif
                            </div>

                            <!-- Modal Detail Event -->
                            <div class="modal fade" id="eventDetailModal-{{ $event->id }}" tabindex="-1"
                                aria-labelledby="eventDetailModalLabel-{{ $event->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="eventDetailModalLabel-{{ $event->id }}">Detail Event</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            @if($event->banner)
                                            <div class="text-center mb-4">
                                                <img src="{{ Storage::url($event->banner) }}" class="img-fluid rounded" alt="{{ $event->title }}" style="max-height: 300px;">
                                            </div>
                                            @endif

                                            <div class="row mb-4">
                                                <div class="col-12">
                                                    <h4>{{ $event->title }}</h4>
                                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                                        <div class="d-flex align-items-center">
                                                            <i class="bi-geo-alt text-primary me-2"></i>
                                                            <span>{{ $event->location }}</span>
                                                        </div>
                                                    </div>
                                                    @if(!empty($event->maps_url))
                                                    <div class="d-flex align-items-center mb-2">
                                                        <div class="d-flex align-items-center">
                                                            <i class="bi-map text-primary me-2"></i>
                                                            <a href="{{ $event->maps_url }}" target="_blank" class="text-primary text-decoration-underline">
                                                                {{ $event->maps_url }}
                                                            </a>
                                                        </div>
                                                    </div>
                                                    @endif
                                                    <div class="d-flex align-items-center mb-3">
                                                        <i class="bi-calendar-event text-primary me-2"></i>
                                                        <span>{{ $event->start_date->format('d M Y') }} ({{ $event->start_date->format('H:i') }}) - {{ $event->end_date->format('d M Y') }} ({{ $event->end_date->format('H:i') }})</span>
                                                    </div>
                                                    <div class="d-flex align-items-center mb-2">
                                                        <span class="fw-bold text-primary me-2">Deskripsi:</span>
                                                        <span>{{ $event->description }}</span>
                                                    </div>
                                                </div>
                                            </div>

                                            @if($event->required_skills)
                                            <div class="row mb-4">
                                                <div class="col-12">
                                                    <h5>Skill yang Dibutuhkan</h5>
                                                    <div class="mt-2">
                                                        @foreach($event->required_skills as $skill)
                                                        <span class="badge bg-light-primary me-1 mb-1">{{ $skill }}</span>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                            @endif

                                            <div class="row">
                                                <div class="col-12">
                                                    <h5 class="mb-3">Peserta Terdaftar</h5>
                                                    @if($event->registrations->where('status', 'approved')->count() > 0)
                                                    <div class="table-responsive">
                                                        <table class="table table-hover">
                                                            <thead>
                                                                <tr>
                                                                    <th>Nama</th>
                                                                    <th>Tanggal Daftar</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($event->registrations->where('status', 'approved') as $registration)
                                                                <tr>
                                                                    <td>
                                                                        {{ $registration->user->name }}
                                                                        @if($registration->user->id === auth()->id())
                                                                        <span class="badge bg-primary ms-1">You</span>
                                                                        @endif
                                                                    </td>
                                                                    <td>{{ $registration->created_at->format('d M Y') }}</td>
                                                                </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    @else
                                                    <p class="text-muted">Belum ada peserta yang terdaftar</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="bi-calendar-x fs-1 text-muted mb-3"></i>
                        <h4 class="text-muted">Tidak ada acara yang tersedia saat ini.</h4>
                    </div>
                </div>
            </div>
            @endforelse

            <!-- Pagination -->
            <div class="col-12">
                <div class="d-flex justify-content-center">
                    {{ $events->links() }}
                </div>
            </div>
        </section>
    </div>
</div>