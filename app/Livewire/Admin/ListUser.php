<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Services\UserImportExport;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class ListUser extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $roleFilter = '';
    public $statusFilter = '';
    public $user_id;
    public $name;
    public $email;
    public $password;
    public $role;
    public $phone;
    public $photo;
    public $address;
    public $skills;
    public $bio;
    public $birth_date;
    public $gender;
    public $education;
    public $institution;
    public $experience;
    public $total_hours;
    public $created_at;
    public $last_login_at;
    public $showModal = false;
    public $isDelete = false;
    public $modalType = 'create'; // create, edit, view
    public $importFile;
    protected $paginationTheme = 'bootstrap';

    protected $rules = [
        'name' => 'required|min:3',
        'email' => 'required|email|unique:users,email',
        'role' => 'required|in:admin,coordinator,volunteer',
        'password' => 'required|min:6',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function create()
    {
        $this->resetValidation();
        $this->reset(['name', 'email', 'password', 'role', 'phone', 'photo', 'address', 'birth_date', 'gender', 'education', 'institution', 'skills', 'bio', 'experience', 'total_hours', 'created_at', 'last_login_at']);
        $this->modalType = 'create';
        $this->showModal = true;
    }

    public function updatingRoleFilter()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function store()
    {
        $this->validate();

        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'role' => $this->role
        ]);

        $this->showModal = false;
        session()->flash('message', 'User created successfully.');
    }

    public function edit($id)
    {
        $this->resetValidation();
        $this->user_id = $id;
        $user = User::find($id);
        if (!$user) {
            session()->flash('error', 'User not found.');
            return;
        }
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->role;
        // If the user has profile fields on the user model or related profile, populate them for possible display
        $this->phone = $user->phone ?? null;
        $this->photo = $user->photo ?? null;
        if ($user->profile) {
            $this->address = $user->profile->address;
            $this->skills = $user->profile->skills;
            $this->bio = $user->profile->bio;
            $this->total_hours = $user->profile->total_hours;
        }
        $this->modalType = 'edit';
        $this->showModal = true;
    }

    public function update()
    {
        $rules = [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email,' . $this->user_id,
            'role' => 'required|in:admin,coordinator,volunteer',
        ];

        if ($this->password) {
            $rules['password'] = 'min:6';
        }

        $this->validate($rules);

        $user = User::find($this->user_id);
        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role
        ];

        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }

        $user->update($data);
        $this->showModal = false;
        session()->flash('message', 'User updated successfully.');
    }

    public function confirmDelete($id)
    {
        $this->user_id = $id;
        $this->isDelete = true;
    }

    public function delete()
    {
        $user = User::find($this->user_id);
        if (!$user) {
            session()->flash('error', 'User not found.');
            $this->isDelete = false;
            return;
        }

        if ($user->id === auth()->id()) {
            session()->flash('error', 'You cannot delete your own account.');
            $this->isDelete = false;
            return;
        }

        $user->delete();
        session()->flash('message', 'User deleted successfully.');
        $this->isDelete = false;
    }

    public function view($id)
    {
        $this->user_id = $id;
        $user = User::with('profile')->find($id);
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->role;
        $this->phone = $user->phone ?? null;
        $this->photo = $user->photo ?? null;
        $this->created_at = $user->created_at ?? null;
        $this->last_login_at = $user->last_login_at ?? null;

        if ($user->profile) {
            $this->address = $user->profile->address;
            $this->birth_date = $user->profile->birth_date;
            $this->gender = $user->profile->gender;
            $this->education = $user->profile->education;
            $this->institution = $user->profile->institution;
            $this->skills = $user->profile->skills;
            $this->bio = $user->profile->bio;
            $this->experience = $user->profile->experience;
            $this->total_hours = $user->profile->total_hours;
        } else {
            $this->address = null;
            $this->birth_date = null;
            $this->gender = null;
            $this->education = null;
            $this->institution = null;
            $this->skills = null;
            $this->bio = null;
            $this->experience = null;
            $this->total_hours = null;
        }
        $this->modalType = 'view';
        $this->showModal = true;
    }

    public function toggleBlock($id)
    {
        $user = User::find($id);
        if (!$user) {
            session()->flash('error', 'User not found.');
            return;
        }

        if ($user->role !== 'admin') {
            $user->is_blocked = !$user->is_blocked;
            $user->save();
            $message = $user->is_blocked ? 'User blocked successfully.' : 'User unblocked successfully.';
            session()->flash('message', $message);
        } else {
            session()->flash('error', 'Cannot block admin users.');
        }
    }

    public function import()
    {
        $this->validate([
            'importFile' => 'required|file|mimes:xlsx,xls',
        ]);

        try {
            $service = new UserImportExport();
            $result = $service->import($this->importFile->path());

            // Display alert dengan detail hasil import
            if ($result['success'] > 0) {
                $message = $result['success'] . ' user berhasil diimport';
                if ($result['failed'] > 0) {
                    $message .= ' dan ' . $result['failed'] . ' user gagal diimport';
                }
                session()->flash('message', $message);
            } else {
                session()->flash('error', 'Tidak ada user yang berhasil diimport. ' . ($result['failed'] > 0 ? $result['failed'] . ' user gagal.' : ''));
            }

            // Log errors jika ada
            if (!empty($result['errors'])) {
                Log::warning('Import errors:', $result['errors']);
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Error importing users: ' . $e->getMessage());
        }

        $this->reset('importFile');
    }

    public function resetImportFile()
    {
        $this->reset('importFile');
    }

    public function export()
    {
        return response()->streamDownload(function () {
            $service = new UserImportExport();
            echo $service->export();
        }, 'users_' . now()->format('Y-m-d_H-i-s') . '.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    public function render()
    {
        $users = User::where(function ($query) {
            $query->where('name', 'like', '%' . $this->search . '%')
                ->orWhere('email', 'like', '%' . $this->search . '%');
        });

        // Apply role filter if selected
        if ($this->roleFilter) {
            $users->where('role', $this->roleFilter);
        }

        // Apply status filter if selected
        if ($this->statusFilter === 'blocked') {
            $users->where('is_blocked', true);
        } elseif ($this->statusFilter === 'active') {
            $users->where('is_blocked', false);
        }

        $users = $users->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.admin.list-user', [
            'users' => $users
        ]);
    }
}
