<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('components.layouts.auth')]
class Login extends Component
{
    #[Validate('required', message: 'ID User wajib diisi.')]
    public string $idUser = '';

    #[Validate('required', message: 'Password wajib diisi.')]
    // #[Validate('min:6', message: 'Password minimal 6 karakter.')]
    public string $password = '';

    public bool $remember = false;

    public function login()
    {
        $this->validate();

        $user = User::where('email', $this->idUser)
            ->orWhere('username', $this->idUser)
            ->orWhere('whatsapp', $this->idUser)
            ->first();

        if (! $user) {
            $this->addError('idUser', 'Akun tidak ditemukan.');
            return;
        }

        if ($user->is_blocked) {
            $this->js(<<<JS
                Swal.fire({
                    icon: 'error',
                    title: 'Akun Diblokir!',
                    text: 'Akun Anda telah diblokir. Silakan hubungi administrator untuk informasi lebih lanjut.',
                    confirmButtonText: 'OK'
                });
            JS);
            return;
        }

        if (Auth::attempt([$this->getLoginField($user) => $this->idUser, 'password' => $this->password], $this->remember)) {
            session()->regenerate();
            // Update last login
            $user->updateLastLogin();
            return $this->redirectIntended(route('admin.dashboard'), navigate: true);
        }

        $this->addError('password', 'Password salah.');
    }

    private function getLoginField(User $user): string
    {
        return match (true) {
            $user->email === $this->idUser    => 'email',
            $user->username === $this->idUser => 'username',
            $user->whatsapp === $this->idUser => 'whatsapp',
            default => 'email',
        };
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
