<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ListUser extends Component
{
    use WithPagination, WithFileUploads;

    protected function onError($message)
    {
        $this->js(<<<JS
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '$message',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        JS);
    }

    public $search = '';
    public $filterRole = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    // For create/edit modal
    public $showModal = false;
    public $isEdit = false;
    public $userId;
    public $name;
    public $email;
    public $username;
    public $password;
    public $whatsapp;
    public $alamat;
    public $role;
    public $photo;
    public $tempPhoto;

    protected $listeners = [
        'edit-user' => 'editUser',
        'confirmDelete' => 'deleteUser',
        'confirmToggleBlock' => 'toggleBlock'
    ];

    protected $swal = true;

    public function deleteUser($userId)
    {
        try {
            $user = User::findOrFail($userId);

            // Don't allow deleting your own account
            if ($user->id === Auth::id()) {
                $this->js(<<<JS
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Anda tidak dapat menghapus akun Anda sendiri.',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true
                    });
                JS);
                return;
            }

            // Delete user's photo if exists
            if ($user->photo && Storage::disk('public')->exists('users/' . $user->photo)) {
                Storage::disk('public')->delete('users/' . $user->photo);
            }

            $user->delete();

            $this->js(<<<JS
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'User berhasil dihapus',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
            JS);
        } catch (\Exception $e) {
            logger()->error('Error deleting user: ' . $e->getMessage());
            $this->js(<<<JS
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Gagal menghapus user',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
            JS);
        }
    }

    public function confirmDelete($userId)
    {
        $this->js(<<<JS
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data user akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('confirmDelete', { userId: $userId });
                }
            })
        JS);
    }

    public function toggleBlock($userId)
    {
        try {
            $user = User::findOrFail($userId);

            // Don't allow blocking your own account
            if ($user->id === Auth::id()) {
                $this->js(<<<JS
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Anda tidak dapat memblokir akun Anda sendiri.',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true
                    });
                JS);
                return;
            }

            $user->is_blocked = !$user->is_blocked;
            $user->save();

            $status = $user->is_blocked ? 'diblokir' : 'diaktifkan';
            $this->js(<<<JS
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'User berhasil $status',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
            JS);
        } catch (\Exception $e) {
            logger()->error('Error toggling user block status: ' . $e->getMessage());
            $this->js(<<<JS
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Gagal mengubah status user',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
            JS);
        }
    }

    public function confirmToggleBlock($userId)
    {
        $user = User::findOrFail($userId);
        $action = $user->is_blocked ? 'mengaktifkan' : 'memblokir';
        $this->js(<<<JS
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Anda akan $action user ini!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, lanjutkan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('confirmToggleBlock', { userId: $userId });
                }
            })
        JS);
    }

    public function showUserDetail($userId)
    {
        $user = User::withCount('laporans')->findOrFail($userId);
        $roleBadge = $user->role === 'admin' ? '<span class="badge bg-primary">admin</span>' : '<span class="badge bg-info">user</span>';
        $lastLogin = $user->last_login ? \Carbon\Carbon::parse($user->last_login)->setTimezone('Asia/Jakarta')->translatedFormat('d M Y H:i') . ' WIB' : 'Belum pernah login';
        $createdAt = $user->created_at->setTimezone('Asia/Jakarta')->translatedFormat('d M Y H:i') . ' WIB';
        $photoUrl = $user->getAvatarUrl();

        if ($this->swal) {
            $html = "<div class='text-start'>
                <div class='text-center mb-3'>
                    <img src='{$photoUrl}' class='rounded-circle' style='width: 150px; height: 150px; object-fit: cover;'>
                </div>
                <h5 class='mb-3'>Informasi Pribadi</h5>
                <p><strong>Nama:</strong> {$user->name}</p>
                <p><strong>Username:</strong> {$user->username}</p>
                <p><strong>Email:</strong> {$user->email}</p>
                <p><strong>WhatsApp:</strong> {$user->whatsapp}</p>
                <p><strong>Alamat:</strong> {$user->alamat}</p>
                <p><strong>Role:</strong> {$roleBadge}</p>
                <hr>
                <h5 class='mb-3'>Info Akun</h5>
                <p><strong>Dibuat:</strong> {$createdAt}</p>
                <p><strong>Terakhir Login:</strong> {$lastLogin}</p>
                <hr>
                <h5 class='mb-3'>Statistik Laporan</h5>
                <p><strong>Total Laporan:</strong> {$user->laporans_count}</p>
                <div class='text-end mt-3'>
                    <button class='btn btn-primary btn-sm' onclick='Swal.close(); Livewire.dispatch(\"edit-user\", { userId: {$user->id} })'>
                        <i class=\"bi bi-pencil\"></i> Edit User
                    </button>
                </div>
            </div>";
            $this->js(<<<JS
                Swal.fire({
                    title: 'Detail User',
                    html: `$html`,
                    width: '600px',
                    confirmButtonText: 'Tutup',
                    allowOutsideClick: false
                });
            JS);
        }
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function createUser()
    {
        $this->swal = false;
        $this->reset(['userId', 'name', 'email', 'username', 'password', 'whatsapp', 'alamat', 'role', 'photo']);
        $this->isEdit = false;
        $this->showModal = true;

        $this->js(<<<JS
            Swal.close();
        JS);
    }

    protected function cleanupPhoto()
    {
        if ($this->photo && !$this->photo instanceof \Illuminate\Http\UploadedFile) {
            $this->photo = null;
        }
    }

    public function editUser($userId)
    {
        $this->swal = false; // Prevent detail modal from showing
        $user = User::findOrFail($userId);
        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->username = $user->username;
        $this->whatsapp = $user->whatsapp;
        $this->alamat = $user->alamat;
        $this->role = $user->role;
        $this->isEdit = true;
        $this->showModal = true;
        $this->cleanupPhoto(); // Reset photo state

        $this->js(<<<JS
            Swal.close();
        JS);
    }

    public function saveUser()
    {
        try {
            $this->validate([
                'name' => 'required|min:3',
                'email' => 'required|email|unique:users,email,' . ($this->userId ?? ''),
                'username' => 'required|unique:users,username,' . ($this->userId ?? ''),
                'whatsapp' => 'required',
                'alamat' => 'required',
                'role' => 'required|in:admin,user',
                'password' => $this->isEdit ? 'nullable|min:6' : 'required|min:6',
                'photo' => $this->isEdit ? 'nullable|image|max:2024' : 'nullable|image|max:2024',
            ]);

            if ($this->isEdit) {
                $user = User::findOrFail($this->userId);
            } else {
                $user = new User();
            }

            try {
                $user->name = $this->name;
                $user->email = $this->email;
                $user->username = $this->username;
                if ($this->password) {
                    $user->password = Hash::make($this->password);
                }
                $user->whatsapp = $this->whatsapp;
                $user->alamat = $this->alamat;
                $user->role = $this->role;

                if ($this->photo) {
                    // Ensure users directory exists
                    if (!Storage::disk('public')->exists('users')) {
                        Storage::disk('public')->makeDirectory('users');
                    }

                    // Delete old photo if exists
                    if ($user->photo && Storage::disk('public')->exists('users/' . $user->photo)) {
                        try {
                            Storage::disk('public')->delete('users/' . $user->photo);
                        } catch (\Exception $e) {
                            logger()->warning('Failed to delete old photo: ' . $e->getMessage());
                        }
                    }

                    // Store new photo
                    $fileName = $this->photo->hashName();
                    try {
                        $this->photo->storeAs('users', $fileName, 'public');
                        $user->photo = $fileName;
                    } catch (\Exception $e) {
                        logger()->error('Failed to store new photo: ' . $e->getMessage());
                        throw new \Exception('Gagal menyimpan foto profil. Silakan coba lagi.');
                    }
                }

                $user->save();
            } catch (\Exception $e) {
                logger()->error('Error saving user: ' . $e->getMessage());
                throw $e;
            }
            $message = $this->isEdit ? 'User berhasil diperbarui' : 'User berhasil ditambahkan';
            $this->showModal = false;
            $this->reset(['userId', 'name', 'email', 'username', 'password', 'whatsapp', 'alamat', 'role', 'photo']);
            $this->swal = true;

            $this->js(<<<JS
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '$message',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        JS);
        } catch (\Exception $e) {
            logger()->error('Error in ListUser saveUser: ' . $e->getMessage());
            $errorMessage = config('app.debug') ? $e->getMessage() : 'Terjadi kesalahan saat menyimpan data.';

            $this->js(<<<JS
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: '$errorMessage',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
            JS);
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset(['userId', 'name', 'email', 'username', 'password', 'whatsapp', 'alamat', 'role', 'photo']);
        $this->cleanupPhoto(); // Clean up photo state
    }

    public function render()
    {
        $users = User::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%')
                        ->orWhere('username', 'like', '%' . $this->search . '%')
                        ->orWhere('whatsapp', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filterRole, function ($query) {
                $query->where('role', $this->filterRole);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        $userStats = [
            'total' => User::count(),
            'admin' => User::where('role', 'admin')->count(),
            'user' => User::where('role', 'user')->count(),
            'active_today' => User::whereNotNull('last_login')->whereDate('last_login', now())->count(),
            'new_this_month' => User::whereMonth('created_at', now()->month)->count(),
        ];

        return view('livewire.admin.list-user', [
            'users' => $users,
            'userStats' => $userStats
        ]);
    }
}
