<?php

namespace App\Livewire\Umum;

use Livewire\Component;
use App\Models\User;
use App\Models\VolunteerProfile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;
use Livewire\Attributes\Reactive;

class Profile extends Component
{
    use WithFileUploads;

    public $name;
    public $email;
    public $phone;
    public $address;
    public $birth_date;
    public $gender;
    public $education;
    public $institution;
    public $skills = [];
    public $customSkill = '';
    public $experience;
    public $photo;
    public $newPhoto;
    public $password;

    // No predefined skills - users enter their own skills

    public function mount()
    {
        $user = Auth::user();
        $profile = $user->profile ?? VolunteerProfile::create(['user_id' => $user->id]);

        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone;
        $this->address = $profile->address;
        $this->birth_date = $profile->birth_date;
        $this->gender = $profile->gender;
        $this->education = $profile->education;
        $this->institution = $profile->institution;
        $this->skills = $profile->skills ? explode(',', $profile->skills) : [];
        $this->experience = $profile->experience;
        $this->photo = $user->photo;
    }

    public function updated($field)
    {
        // Intentionally left empty: we don't auto-save on each field change.
        // Inputs are bound with wire:model.defer so changes are sent only on submit.
    }

    public function updateProfile()
    {
        $user = User::findOrFail(Auth::user()->id);
        $profile = $user->profile;

        // Build validation rules. Skills required only for volunteers.
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => [
                'required',
                'regex:/^\+?[0-9]{10,15}$/',
                'unique:users,phone,' . $user->id,
            ],
            'address' => 'required|string',
            'birth_date' => 'required|date',
            'gender' => 'required|in:male,female',
            'education' => 'required|in:SD,SMP,SMA,D1,D2,D3,D4,S1,S2,S3',
            'institution' => 'required|string',
            'experience' => 'nullable|string',
            'newPhoto' => 'nullable|image|max:2048',
            'password' => 'nullable|min:6',
        ];

        if ($user->isVolunteer()) {
            $rules['skills'] = 'required|array|min:1';
        } else {
            $rules['skills'] = 'nullable|array';
        }

        $this->validate($rules);

        // Handle photo upload
        if ($this->newPhoto) {
            if ($user->photo && Storage::disk('public')->exists('users/' . $user->photo)) {
                Storage::disk('public')->delete('users/' . $user->photo);
            }
            $this->newPhoto->storeAs('public/users', $this->newPhoto->hashName());
            $user->photo = $this->newPhoto->hashName();
            $this->photo = $user->photo;
        }

        // Update user data
        $user->name = $this->name;
        $user->email = $this->email;
        $user->phone = $this->phone;

        if ($this->password) {
            $user->password = Hash::make($this->password);
        }

        $user->save();

        // Update volunteer profile
        $profile->address = $this->address;
        $profile->birth_date = $this->birth_date;
        $profile->gender = $this->gender;
        $profile->education = $this->education;
        $profile->institution = $this->institution;
        $profile->skills = implode(',', array_unique($this->skills));
        $profile->experience = $this->experience;
        $profile->save();

        // Dispatch a swal event to show the success message
        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => 'Berhasil!',
            'text' => 'Perubahan berhasil disimpan.',
            'confirmButtonColor' => '#3085d6',
            'confirmButtonText' => 'Ok'
        ]);

        // $this->dispatch('profileUpdated');


        // $this->dispatch('swal', [
        //     'title' => 'Berhasil!',
        //     'text' => 'Profil berhasil diperbarui.',
        //     'icon' => 'success'
        // ]);
    }

    public function addCustomSkill()
    {
        if (!empty($this->customSkill)) {
            $this->skills[] = $this->customSkill;
            $this->customSkill = '';

            // Do not persist immediately; save happens on "Simpan Perubahan"
            $this->js("
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true
                });
                Toast.fire({
                    icon: 'success',
                    title: 'Skill ditambahkan'
                });
            ");
        }
    }

    public function removeSkill($skill)
    {
        $this->skills = array_values(array_diff($this->skills, [$skill]));

        // Do not persist immediately; save happens on "Simpan Perubahan"
        $this->js("
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true
            });
            Toast.fire({
                icon: 'success',
                title: 'Skill dihapus'
            });
        ");
    }

    #[\Livewire\Attributes\On('set-skills')]
    public function setSkills($skills)
    {
        // Update property only. We do not persist immediately; commit on save.
        $this->skills = $skills;
        $this->js("
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 1500,
                timerProgressBar: true
            });
            Toast.fire({
                icon: 'success',
                title: 'Skills diperbarui'
            });
        ");
    }

    public function render()
    {
        return view('livewire.umum.profile');
    }
}
