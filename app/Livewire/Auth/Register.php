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

    #[Rule('required|regex:/^\+?[0-9]{10,15}$/|unique:users,phone')]
    public $phone = '';

    #[Rule('required|string|min:6')]
    public $password = '';

    #[Rule('required|string|same:password')]

    public $password_confirmation = '';

    public function register()
    {
        $validated = $this->validate([
            'name' => 'required|string|min:3|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => [
                'required',
                'regex:/^\+?[0-9]{10,15}$/',
                'unique:users,phone'
            ],
            'password' => 'required|string|min:6',
            'password_confirmation' => 'required|string|same:password'
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'name.min' => 'Nama lengkap minimal 3 karakter.',
            'name.max' => 'Nama lengkap maksimal 255 karakter.',

            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',

            'phone.required' => 'Nomor Telepon wajib diisi.',
            'phone.min' => 'Nomor Telepon minimal 10 digit.',
            'phone.max' => 'Nomor Telepon maksimal 15 digit.',
            'phone.unique' => 'Nomor Telepon sudah terdaftar.',
            'phone.regex' => 'Format Nomor Telepon tidak valid.',

            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',

            'password_confirmation.required' => 'Konfirmasi password wajib diisi.',
            'password_confirmation.same' => 'Konfirmasi password harus sama dengan password.'
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'role' => 'volunteer'
        ]);

        $this->reset();

        $this->dispatch('showAlert');
    }

    public function render()
    {
        return view('livewire.auth.register');
    }
}
