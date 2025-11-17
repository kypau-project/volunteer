<div>
    <div class="page-heading">
        <h3>Manajemen Acara</h3>
    </div>

    <div class="d-flex justify-content-end align-items-center mb-3">
        <a href="{{ route('admin.events.create') }}" wire:navigate class="btn btn-primary">
            <i class="bi-plus"></i> Buat Acara Baru
        </a>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div class="d-flex gap-3 align-items-center">
                    <select wire:model.live="statusFilter" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="draft">Draft</option>
                        <option value="published">Published</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
                <input type="text" wire:model.live="search" class="form-control w-25" placeholder="Cari acara...">
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

            <div class="card-body">
                <div class="table-responsive">
                    <div wire:loading.remove wire:target="search, statusFilter">
                        <table class="table table-striped" id="table1">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Judul Acara</th>
                                    <th>Lokasi</th>
                                    <th>Tanggal Mulai</th>
                                    <th>Kuota</th>
                                    <th>Status</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($events as $event)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $event->title }}</td>
                                    <td>{{ $event->location }}</td>
                                    <td>{{ $event->start_date->format('d M Y, H:i') }}</td>
                                    <td>{{ $event->quota }}</td>
                                    <td>
                                        <span class="badge bg-{{ $event->status == 'published' ? 'success' : ($event->status == 'draft' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($event->status) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.events.edit', $event) }}" wire:navigate class="btn btn-warning btn-sm">
                                            <i class="bi-pencil"></i> Edit
                                        </a>
                                        <button wire:click="deleteEvent({{ $event->id }})" class="btn btn-danger btn-sm"
                                            wire:loading.attr="disabled" wire:target="deleteEvent">
                                            <i class="bi-trash"></i> Hapus
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">
                                        Tidak ada acara yang ditemukan.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center">
                        {{ $events->links() }}
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush