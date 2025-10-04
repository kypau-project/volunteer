@section('title', 'Form Edit Laporan')
<div>
    <div class="page-heading">
        <h3>Edit Laporan</h3>
    </div>

    <div class="col-md-12 col-12">
        <div class="card">
            <div class="card-header text-center">
                <h4 class="card-title">Form Edit Laporan</h4>
            </div>
            <div class="card-content" style="margin-top: -10px;">
                <div class="card-body">
                    <form wire:submit.prevent="update" class="form form-vertical" enctype="multipart/form-data">
                        <div class="form-body">
                            <div class="row">

                                {{-- Judul --}}
                                <div class="col-12">
                                    <div class="form-group has-icon-left">
                                        <label for="judul">Judul Laporan</label>
                                        <div class="position-relative">
                                            <input type="text" id="judul" wire:model="judul" class="form-control @error('judul') is-invalid @enderror" placeholder="Masukkan judul laporan">
                                            <div class="form-control-icon">
                                                <i class="bi bi-book"></i>
                                            </div>
                                            @error('judul')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                {{-- Tanggal --}}
                                <div class="col-12">
                                    <div class="form-group has-icon-left">
                                        <label for="tanggal">Tanggal Laporan</label>
                                        <div class="position-relative">
                                            <input type="date" id="tanggal" wire:model="tanggal" class="form-control @error('tanggal') is-invalid @enderror">
                                            <div class="form-control-icon">
                                                <i class="bi bi-calendar-date"></i>
                                            </div>
                                            @error('tanggal')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                {{-- Deskripsi --}}
                                <div class="col-12">
                                    <div class="form-group has-icon-left">
                                        <label for="deskripsi">Deskripsi Laporan</label>
                                        <div class="position-relative">
                                            <textarea id="deskripsi" wire:model="deskripsi" rows="5" class="form-control @error('deskripsi') is-invalid @enderror" placeholder="Tuliskan deskripsi laporan"></textarea>
                                            <div class="form-control-icon">
                                                <i class="bi bi-info-circle"></i>
                                            </div>
                                            @error('deskripsi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                @if(auth()->user()->role === 'admin')
                                {{-- Status (hanya untuk admin) --}}
                                <div class="col-12">
                                    <div class="form-group has-icon-left">
                                        <label for="status">Status Laporan</label>
                                        <div class="position-relative">
                                            <select id="status" wire:model="status" class="form-control @error('status') is-invalid @enderror">
                                                <option value="pending">Pending</option>
                                                <option value="diproses">Diproses</option>
                                                <option value="selesai">Selesai</option>
                                            </select>
                                            <div class="form-control-icon">
                                                <i class="bi bi-flag"></i>
                                            </div>
                                            @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                {{-- Respon Admin --}}
                                <div class="col-12">
                                    <div class="form-group has-icon-left">
                                        <label for="respon">Respon Admin</label>
                                        <div class="position-relative">
                                            <textarea id="respon" wire:model="respon" rows="4"
                                                class="form-control @error('respon') is-invalid @enderror"
                                                placeholder="Berikan respon untuk pelapor..."></textarea>
                                            <div class="form-control-icon">
                                                <i class="bi bi-chat-dots"></i>
                                            </div>
                                            @error('respon')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                @endif

                                @if(auth()->user()->role !== 'admin' && $respon)
                                {{-- Tampilkan respon untuk user --}}
                                <div class="col-12">
                                    <div class="alert alert-info">
                                        <h4 class="alert-heading">Respon Admin:</h4>
                                        <p class="mb-0">{{ $respon }}</p>
                                    </div>
                                </div>
                                @endif

                                {{-- Gambar --}}
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="gambar">Gambar Laporan</label>
                                        <div class="input-group">
                                            <span class="input-group-text align-items-start">
                                                <i class="bi bi-card-image"></i>
                                            </span>
                                            <input type="file" id="gambar" accept="image/*" wire:model="gambar" class="form-control @error('gambar') is-invalid @enderror">
                                        </div>
                                        @error('gambar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    @if($gambar)
                                    {{-- preview gambar baru --}}
                                    <img src="{{ $gambar->temporaryUrl() }}" class="img-thumbnail mt-2" width="200">
                                    @elseif($gambarLama)
                                    {{-- tampilkan gambar lama --}}
                                    <img src="{{ $gambarLama }}" class="img-thumbnail mt-2" width="200">
                                    @endif
                                </div>

                                {{-- Tombol --}}
                                <div class="col-12 d-flex justify-content-end">
                                    <button
                                        wire:loading.attr="disabled"
                                        wire:target="update"
                                        type="submit"
                                        class="btn btn-primary me-1 mb-1">
                                        <span wire:loading.remove wire:target="update">Simpan</span>
                                        <span wire:loading wire:target="update" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                    </button>
                                    <button type="reset" class="btn btn-light-secondary me-1 mb-1">Reset</button>
                                </div>

                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush