<?php

namespace App\Livewire\Umum;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;

class Profile extends Component
{
    use WithFileUploads;

    public $name;
    public $username;
    public $email;
    public $whatsapp;
    public $alamat;
    public $foto;
    public $newFoto;
    public $password;

    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->username = $user->username;
        $this->email = $user->email;
        $this->whatsapp = $user->whatsapp;
        $this->alamat = $user->alamat;
        $this->foto = $user->foto;
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
            'newFoto'   => 'nullable|image|max:2048',
            'password'  => 'nullable|min:6'
        ]);

        if ($this->newFoto) {
            if ($user->foto && Storage::disk('public')->exists('users/' . $user->foto)) {
                Storage::disk('public')->delete('users/' . $user->foto);
            }
            $this->newFoto->storeAs('public/users', $this->newFoto->hashName());
            $user->foto = $this->newFoto->hashName();
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

        session()->flash('message', 'Profile berhasil diperbarui.');

        $this->dispatch('swal', [
            'title' => 'Berhasil!',
            'text' => 'Profil berhasil diperbarui.',
            'icon' => 'success'
        ]);
    }

    public function render()
    {
        return view('livewire.umum.profile');
    }
}
