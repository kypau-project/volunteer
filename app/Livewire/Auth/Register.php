<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Js;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;

#[Layout('components.layouts.auth')]
class Register extends Component
{
    #[Rule('required|string|min:3|max:255')]
    public $name = '';

    #[Rule('required|string|min:3|max:255|unique:users,username')]
    public $username = '';

    #[Rule('required|email|unique:users,email')]
    public $email = '';

    #[Rule('required|string|min:10|max:15|unique:users,whatsapp')]
    public $whatsapp = '';

    #[Rule('required|string|min:6')]
    public $password = '';

    #[Rule('required|string|same:password')]

    public $password_confirmation = '';

    public function register()
    {
        $validated = $this->validate([
            'name' => 'required|string|min:3|max:255',
            'username' => 'required|string|min:3|max:255|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'whatsapp' => 'required|string|min:10|max:15|unique:users,whatsapp',
            'password' => 'required|string|min:6',
            'password_confirmation' => 'required|string|same:password'
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'name.min' => 'Nama lengkap minimal 3 karakter.',
            'name.max' => 'Nama lengkap maksimal 255 karakter.',

            'username.required' => 'Username wajib diisi.',
            'username.min' => 'Username minimal 3 karakter.',
            'username.max' => 'Username maksimal 255 karakter.',
            'username.unique' => 'Username sudah digunakan.',

            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',

            'whatsapp.required' => 'Nomor WhatsApp wajib diisi.',
            'whatsapp.min' => 'Nomor WhatsApp minimal 10 digit.',
            'whatsapp.max' => 'Nomor WhatsApp maksimal 15 digit.',
            'whatsapp.unique' => 'Nomor WhatsApp sudah terdaftar.',

            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',

            'password_confirmation.required' => 'Konfirmasi password wajib diisi.',
            'password_confirmation.same' => 'Konfirmasi password harus sama dengan password.'
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'whatsapp' => $validated['whatsapp'],
            'password' => Hash::make($validated['password']),
            'role' => 'user'
        ]);

        $this->reset();

        $this->dispatch('showAlert');
        // return $this->redirect('/auth/start-session', navigate: true);

        // Auth::login($user);

        // session()->flash('message', 'Registrasi berhasil! Selamat datang!');

        // return $this->redirect(route('user.dashboard'), navigate: true);
    }

    public function render()
    {
        return view('livewire.auth.register');
    }
}
