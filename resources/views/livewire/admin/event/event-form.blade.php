<div>
    <div class="page-heading">
        <h3>{{ $event->exists ? 'Edit' : 'Buat' }} Acara</h3>
    </div>

    <div class="col-md-12 col-12">
        <div class="card">
            <div class="card-header text-center">
                <h4 class="card-title">Form {{ $event->exists ? 'Edit' : 'Buat' }} Acara</h4>
            </div>
            <div class="card-content">
                <div class="card-body">
                    <form wire:submit.prevent="save" class="form form-vertical">
                        <div class="form-body">
                            <div class="row">
                                {{-- Judul --}}
                                <div class="col-12">
                                    <div class="form-group has-icon-left">
                                        <label for="title">Judul Acara</label>
                                        <div class="position-relative">
                                            <input type="text" id="title" wire:model="title" class="form-control @error('title') is-invalid @enderror" placeholder="Masukkan judul acara">
                                            <div class="form-control-icon">
                                                <i class="bi-card-heading"></i>
                                            </div>
                                            @error('title')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                {{-- Category --}}
                                <div class="col-12">
                                    <div class="form-group has-icon-left">
                                        <label for="category">Kategori</label>
                                        <div class="position-relative">
                                            <input type="text" id="category" wire:model="category" class="form-control @error('category') is-invalid @enderror" placeholder="Kategori acara">
                                            <div class="form-control-icon">
                                                <i class="bi-tag"></i>
                                            </div>
                                            @error('category')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                {{-- Banner Image --}}
                                <div class="col-12 mb-3">
                                    <div class="form-group">
                                        <label for="banner">Banner Acara</label>
                                        <div class="position-relative">
                                            @if ($banner)
                                            <img src="{{ $banner->temporaryUrl() }}" class="img-fluid mb-2 rounded" style="max-height: 200px">
                                            @elseif($event->banner)
                                            <img src="{{ Storage::url($event->banner) }}" class="img-fluid mb-2 rounded" style="max-height: 200px">
                                            @endif
                                            <input type="file" wire:model="banner" id="banner" class="form-control @error('banner') is-invalid @enderror" accept="image/*">
                                            @error('banner')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="text-muted">Format: JPG, PNG, JPEG. Maks: 2MB</small>
                                        </div>
                                    </div>
                                </div>

                                {{-- Description --}}
                                <div class="col-12">
                                    <div class="form-group has-icon-left">
                                        <label for="description">Deskripsi Acara</label>
                                        <div class="position-relative">
                                            <textarea id="description" wire:model="description" rows="4" class="form-control @error('description') is-invalid @enderror" placeholder="Tuliskan deskripsi acara"></textarea>
                                            <div class="form-control-icon">
                                                <i class="bi-text-paragraph"></i>
                                            </div>
                                            @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                {{-- Start Date --}}
                                <div class="col-md-6">
                                    <div class="form-group has-icon-left">
                                        <label for="start_date">Tanggal & Waktu Mulai</label>
                                        <div class="position-relative">
                                            <input type="datetime-local" id="start_date" wire:model="start_date" class="form-control @error('start_date') is-invalid @enderror">
                                            <div class="form-control-icon">
                                                <i class="bi-calendar-event"></i>
                                            </div>
                                            @error('start_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                {{-- End Date --}}
                                <div class="col-md-6">
                                    <div class="form-group has-icon-left">
                                        <label for="end_date">Tanggal & Waktu Selesai</label>
                                        <div class="position-relative">
                                            <input type="datetime-local" id="end_date" wire:model="end_date" class="form-control @error('end_date') is-invalid @enderror">
                                            <div class="form-control-icon">
                                                <i class="bi-calendar-check"></i>
                                            </div>
                                            @error('end_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                {{-- Location --}}
                                <div class="col-12">
                                    <div class="form-group has-icon-left">
                                        <label for="location">Lokasi</label>
                                        <div class="position-relative">
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="bi-geo-alt"></i></span>
                                                <input type="text" id="location" wire:model="location" class="form-control @error('location') is-invalid @enderror" placeholder="Lokasi acara">
                                                @php
                                                $mapsHref = $maps_url ?: $this->getGoogleMapsUrl();
                                                @endphp
                                                @if(!empty($mapsHref))
                                                <a href="{{ $mapsHref }}" target="_blank" class="btn btn-outline-primary" title="Buka di Google Maps">
                                                    <i class="bi-map"></i>
                                                </a>
                                                @else
                                                <button type="button" class="btn btn-outline-secondary" disabled title="Masukkan lokasi atau URL Maps">
                                                    <i class="bi-map"></i>
                                                </button>
                                                @endif
                                            </div>
                                            <div class="form-control-icon" style="display:none;">
                                                <i class="bi-geo-alt"></i>
                                            </div>
                                            @error('location')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                {{-- Maps URL --}}
                                <div class="col-12">
                                    <div class="form-group has-icon-left">
                                        <label for="maps_url">URL Google Maps</label>
                                        <div class="position-relative">
                                            <input type="url" id="maps_url" wire:model="maps_url" class="form-control @error('maps_url') is-invalid @enderror" placeholder="https://maps.google.com/...">
                                            <div class="form-control-icon">
                                                <i class="bi-map"></i>
                                            </div>
                                            @error('maps_url')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="text-muted">Masukkan URL Google Maps untuk memudahkan volunteer menemukan lokasi</small>
                                        </div>
                                    </div>
                                </div>

                                {{-- Quota --}}
                                <div class="col-md-6">
                                    <div class="form-group has-icon-left">
                                        <label for="quota">Kuota Relawan</label>
                                        <div class="position-relative">
                                            <input type="number" id="quota" wire:model="quota" min="1" class="form-control @error('quota') is-invalid @enderror">
                                            <div class="form-control-icon">
                                                <i class="bi-people"></i>
                                            </div>
                                            @error('quota')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                {{-- Status --}}
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="status">Status Acara</label>
                                        <div class="position-relative">
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="bi-flag"></i></span>
                                                <select id="status" wire:model="status" class="form-select @error('status') is-invalid @enderror">
                                                    <option value="draft">Draft</option>
                                                    <option value="published">Published</option>
                                                    <option value="cancelled">Cancelled</option>
                                                </select>
                                            </div>
                                            @error('status')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                {{-- Required Skills --}}
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="required_skills">Kebutuhan Keterampilan</label>
                                        <div class="position-relative">
                                            <div class="input-group mb-2">
                                                <span class="input-group-text"><i class="bi-briefcase"></i></span>
                                                <input type="text"
                                                    wire:model.defer="custom_required_skill"
                                                    wire:keydown.enter.prevent="addRequiredSkill"
                                                    class="form-control @error('required_skills_list') is-invalid @enderror"
                                                    placeholder="Ketik role dan tekan Enter atau klik Tambah">
                                                <button type="button" wire:click="addRequiredSkill" class="btn btn-secondary">
                                                    Tambah
                                                </button>
                                            </div>
                                            <div class="mt-2">
                                                @foreach($required_skills_list as $skill)
                                                <span class="badge bg-light-primary me-1 mb-1">
                                                    {{ $skill }}
                                                    <i class="bi-x-circle cursor-pointer" wire:click="removeRequiredSkill('{{ $skill }}')"></i>
                                                </span>
                                                @endforeach
                                            </div>
                                            @error('required_skills')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                {{-- Tombol --}}
                                <div class="col-12 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary me-1 mb-1" wire:loading.attr="disabled" wire:target="save">
                                        <span wire:loading.remove wire:target="save">
                                            <i class="bi-save"></i> Simpan
                                        </span>
                                        <span wire:loading wire:target="save" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                    </button>
                                    <a href="{{ route('admin.events.index') }}" class="btn btn-light-secondary me-1 mb-1">
                                        <i class="bi-x"></i> Batal
                                    </a>
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