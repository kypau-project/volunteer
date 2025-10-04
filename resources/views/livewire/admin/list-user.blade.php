<div>
    <div class="page-heading mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <h3>Daftar User</h3>
            <button class="btn btn-primary" wire:click="createUser">
                <i class="bi bi-plus-circle"></i> Tambah User
            </button>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <div class="row mb-2">
                <div class="col-md-4 mb-2">
                    <input type="text" wire:model.live="search" class="form-control" placeholder="Cari nama/email/username/whatsapp...">
                </div>

                <div class="col-md-3 mb-2">
                    <select wire:model.live="filterRole" class="form-select">
                        <option value="">Semua Role</option>
                        <option value="admin">Admin</option>
                        <option value="user">User</option>
                    </select>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th wire:click="sortBy('name')" style="cursor:pointer">Nama <i class="bi bi-sort-alpha-down"></i></th>
                            <th wire:click="sortBy('username')" style="cursor:pointer">Username <i class="bi bi-sort-alpha-down"></i></th>
                            <th>Email</th>
                            <th>WhatsApp</th>
                            <th>Role</th>
                            <th>Dibuat</th>
                            <th>Terakhir Login</th>
                            <th>Laporan</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td>{{ ($users->currentPage()-1)*$users->perPage() + $loop->iteration }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->username }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->whatsapp }}</td>
                            <td>
                                <span class="badge bg-{{ $user->role === 'admin' ? 'primary' : 'info' }}">{{ $user->role }}</span>
                            </td>
                            <td>{{ $user->created_at->setTimezone('Asia/Jakarta')->translatedFormat('d M Y H:i') }}</td>
                            <td>{{ $user->last_login ? \Carbon\Carbon::parse($user->last_login)->setTimezone('Asia/Jakarta')->translatedFormat('d M Y H:i') : '-' }}</td>
                            <td>{{ $user->laporans()->count() }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button class="btn btn-outline-info btn-sm" wire:click="showUserDetail({{ $user->id }})" title="Detail">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    @if($user->id !== Auth::id())
                                    <button class="btn btn-outline-{{ $user->is_blocked ? 'success' : 'warning' }} btn-sm"
                                        wire:click="confirmToggleBlock({{ $user->id }})"
                                        title="{{ $user->is_blocked ? 'Aktifkan' : 'Blokir' }}">
                                        <i class="bi bi-{{ $user->is_blocked ? 'unlock' : 'lock' }}"></i>
                                    </button>
                                    <button class="btn btn-outline-danger btn-sm" wire:click="confirmDelete({{ $user->id }})" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-2">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Create/Edit User -->
    <div class="modal {{ $showModal ? 'show' : '' }}" tabindex="-1" role="dialog" @if($showModal) style="display: block;" @else style="display: none;" @endif>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $isEdit ? 'Edit User' : 'Tambah User Baru' }}</h5>
                    <button type="button" class="btn-close" wire:click="closeModal"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit="saveUser">
                        <div class="mb-3">
                            <label class="form-label">Foto Profil</label>
                            <input type="file" class="form-control" wire:model="photo" accept="image/*">
                            @if($photo && $photo instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile)
                            <img src="{{ $photo->temporaryUrl() }}" class="mt-2 rounded" style="max-width: 200px">
                            @elseif($isEdit && $userId)
                            @php
                            $user = \App\Models\User::find($userId);
                            @endphp
                            <img src="{{ $user ? $user->getAvatarUrl() : '' }}" class="mt-2 rounded" style="max-width: 200px">
                            @endif
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nama</label>
                            <input type="text" class="form-control" wire:model="name" required>
                            @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" wire:model="email" required>
                            @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" class="form-control" wire:model="username" required>
                            @error('username') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ $isEdit ? 'Password (kosongkan jika tidak ingin mengubah)' : 'Password' }}</label>
                            <input type="password" class="form-control" wire:model="password" {{ $isEdit ? '' : 'required' }}>
                            @error('password') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">WhatsApp</label>
                            <input type="text" class="form-control" wire:model="whatsapp" required>
                            @error('whatsapp') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Alamat</label>
                            <textarea class="form-control" wire:model="alamat" required></textarea>
                            @error('alamat') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Role</label>
                            <select class="form-select" wire:model="role" required>
                                <option value="">Pilih Role</option>
                                <option value="admin">Admin</option>
                                <option value="user">User</option>
                            </select>
                            @error('role') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="closeModal">Tutup</button>
                    <button type="button" class="btn btn-primary" wire:click="saveUser">
                        {{ $isEdit ? 'Update' : 'Simpan' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
    @if($showModal)
    <div class="modal-backdrop fade show"></div>
    @endif
</div>