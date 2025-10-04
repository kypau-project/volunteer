<?php

namespace App\Livewire\Umum;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;
use Livewire\Attributes\Reactive;

class Profile extends Component
{
    use WithFileUploads;

    public $name;
    public $username;
    public $email;
    public $whatsapp;
    public $alamat;
    public $photo;
    public $newPhoto;
    public $password;

    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->username = $user->username;
        $this->email = $user->email;
        $this->whatsapp = $user->whatsapp;
        $this->alamat = $user->alamat;
        $this->photo = $user->photo;
    }

    public function updated($field)
    {
        $user = User::findOrFail(Auth::user()->id);

        // Validate only the updated field
        $this->validateOnly($field, [
            'name'      => 'required|string|max:255',
            'username'  => 'nullable|string|max:255|unique:users,username,' . $user->id,
            'email'     => 'required|email|unique:users,email,' . $user->id,
            'whatsapp'  => 'required|string|unique:users,whatsapp,' . $user->id,
            'alamat'    => 'nullable|string',
            'newPhoto'   => 'nullable|image|max:2048',
            'password'  => 'nullable|min:6'
        ]);

        // Update the field if it's a basic field
        if (in_array($field, ['name', 'username', 'email', 'whatsapp', 'alamat'])) {
            $user->{$field} = $this->{$field};
            $user->save();
            $this->dispatch('swal:toast', [
                'icon' => 'success',
                'title' => 'Berhasil diperbarui!',
                'timer' => 3000
            ]);
        }
    }

    public function updateProfile()
    {
        $user = User::findOrFail(Auth::user()->id);

        $this->validate([
            'name'      => 'required|string|max:255',
            'username'  => 'nullable|string|max:255|unique:users,username,' . $user->id,
            'email'     => 'required|email|unique:users,email,' . $user->id,
            'whatsapp'  => 'required|string|unique:users,whatsapp,' . $user->id,
            'alamat'    => 'nullable|string',
            'newPhoto'   => 'nullable|image|max:2048',
            'password'  => 'nullable|min:6'
        ]);

        if ($this->newPhoto) {
            if ($user->photo && Storage::disk('public')->exists('users/' . $user->photo)) {
                Storage::disk('public')->delete('users/' . $user->photo);
            }
            $this->newPhoto->storeAs('public/users', $this->newPhoto->hashName());
            $user->photo = $this->newPhoto->hashName();
            $this->photo = $user->photo;
        }

        $user->name = $this->name;
        $user->username = $this->username;
        $user->email = $this->email;
        $user->whatsapp = $this->whatsapp;
        $user->alamat = $this->alamat;

        if ($this->password) {
            $user->password = Hash::make($this->password);
        }

        $user->save();

        // $this->redirectIntended(route('user.profile'), navigate: true);

        // $this->dispatch('profileUpdated');


        // $this->dispatch('swal', [
        //     'title' => 'Berhasil!',
        //     'text' => 'Profil berhasil diperbarui.',
        //     'icon' => 'success'
        // ]);
    }

    public function render()
    {
        return view('livewire.umum.profile');
    }
}
