@section('title', 'My Profile')

<div>
    <div class="page-heading">
        <h3>My Profile</h3>
    </div>
    <section class="section">
        <div class="row">
            <!-- Sidebar Avatar -->
            <div class="col-12 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-center align-items-center flex-column">
                            <div class="avatar avatar-2xl">
                                @if($photo)
                                @php
                                $photoPath = storage_path('app/public/users/'.$photo);
                                $cacheBust = file_exists($photoPath) ? filemtime($photoPath) : time();
                                @endphp
                                <img src="{{ asset('storage/users/'.$photo) }}?v={{ $cacheBust }}" alt="Avatar" class="rounded-circle" style="width:120px;height:120px;object-fit:cover;">
                                @else
                                <img src="https://ui-avatars.com/api/?background=random&name={{ urlencode($name) }}" alt="Avatar" class="rounded-circle" style="width:120px;height:120px;object-fit:cover;">
                                @endif
                            </div>

                            <h3 class="mt-3">{{ $name }}</h3>
                            <p class="text-small">{{ $email }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profile Form -->
            <div class="col-12 col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <form wire:submit.prevent="updateProfile">
                            <div class="form-group mb-3">
                                <label for="name" class="form-label">Nama</label>
                                <input type="text" wire:model.defer="name" id="name" class="form-control">
                                @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="text" wire:model.defer="email" id="email" class="form-control">
                                @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="phone" class="form-label">Nomor Telepon</label>
                                <input type="text" wire:model.defer="phone" id="phone" class="form-control">
                                @error('phone') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="address" class="form-label">Alamat</label>
                                <textarea wire:model.defer="address" id="address" class="form-control"></textarea>
                                @error('address') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="birth_date" class="form-label">Tanggal Lahir</label>
                                <input type="date" wire:model.defer="birth_date" id="birth_date" class="form-control">
                                @error('birth_date') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="gender" class="form-label">Jenis Kelamin</label>
                                <select wire:model.defer="gender" id="gender" class="form-control">
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="male">Laki-laki</option>
                                    <option value="female">Perempuan</option>
                                </select>
                                @error('gender') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="education" class="form-label">Pendidikan Terakhir</label>
                                <select wire:model.defer="education" id="education" class="form-control">
                                    <option value="">Pilih Pendidikan Terakhir</option>
                                    <option value="SD">SD</option>
                                    <option value="SMP">SMP</option>
                                    <option value="SMA">SMA</option>
                                    <option value="D1">D1</option>
                                    <option value="D2">D2</option>
                                    <option value="D3">D3</option>
                                    <option value="D4">D4</option>
                                    <option value="S1">S1</option>
                                    <option value="S2">S2</option>
                                    <option value="S3">S3</option>
                                </select>
                                @error('education') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="institution" class="form-label">Institusi</label>
                                <input type="text" wire:model.defer="institution" id="institution" class="form-control">
                                @error('institution') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="experience" class="form-label">Pengalaman</label>
                                <textarea wire:model.defer="experience" id="experience" class="form-control"></textarea>
                                @error('experience') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            @if(auth()->user() && auth()->user()->isVolunteer())
                            <div class="form-group mb-3">
                                <label for="skills" class="form-label">Skills</label>
                                <div class="input-group mb-2">
                                    <input type="text"
                                        wire:model.defer="customSkill"
                                        wire:keydown.enter.prevent="addCustomSkill"
                                        class="form-control"
                                        placeholder="Ketik skill dan tekan Enter atau klik Tambah">
                                    <button type="button" wire:click="addCustomSkill" class="btn btn-secondary">
                                        Tambah
                                    </button>
                                </div>
                                <div class="mt-2">
                                    @foreach($skills as $skill)
                                    <span class="badge bg-light-primary me-1 mb-1">
                                        {{ $skill }}
                                        <i class="bi-x-circle cursor-pointer" wire:click="removeSkill('{{ $skill }}')"></i>
                                    </span>
                                    @endforeach
                                </div>
                                @error('skills') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            @endif

                            <div class="form-group mb-3">
                                <label for="newPhoto" class="form-label">Foto Profil</label>
                                <input type="file" wire:model="newPhoto" id="newPhoto" class="form-control">
                                <div class="mt-2">
                                    @if ($newPhoto)
                                    <div class="mb-2">
                                        <strong>Preview:</strong>
                                        <div>
                                            <img src="{{ $newPhoto->temporaryUrl() }}" alt="Preview" class="rounded-circle" style="width:120px;height:120px;object-fit:cover;">
                                        </div>
                                    </div>
                                    @endif
                                    <div wire:loading wire:target="newPhoto">Uploading...</div>
                                </div>
                                @error('newPhoto') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="password" class="form-label">Password (Opsional)</label>
                                <input type="password" wire:model.defer="password" id="password" class="form-control">
                                @error('password') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>