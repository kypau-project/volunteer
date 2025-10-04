@section('title', 'Register')
<div>
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('showAlert', () => {
                Swal.fire({
                    icon: 'success',
                    title: 'Registrasi Berhasil!',
                    text: 'Akun Anda telah berhasil dibuat. Silakan login untuk melanjutkan.',
                    confirmButtonText: 'Login',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    allowEnterKey: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '/auth/start-session';
                    }
                });
            });
        });
    </script>
    <div class="row h-100">
        <div class="col-lg-5 col-12">
            <div id="auth-left">
                <div class="auth-logo mb-4">
                    <a href="#">
                        <img src="{{ asset('images/adu.png') }}" alt="Logo" class="img-fluid" style="width: 160px; height: auto;">
                    </a>
                </div>

                <h1 class="auth-title">Sign Up</h1>
                <p class="auth-subtitle mb-5">Masukkan semua data untuk mendaftar.</p>

                <form wire:submit.prevent="register">
                    <div class="form-group position-relative has-icon-left mb-4">
                        <input type="text" wire:model.blur="name" class="form-control form-control-xl @error('name') is-invalid @enderror" placeholder="Nama Lengkap">
                        <div class="form-control-icon">
                            <i class="bi bi-person"></i>
                        </div>
                        @error('name')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group position-relative has-icon-left mb-4">
                        <input type="text" wire:model.blur="username" class="form-control form-control-xl @error('username') is-invalid @enderror" placeholder="Username">
                        <div class="form-control-icon">
                            <i class="bi bi-person-badge"></i>
                        </div>
                        @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group position-relative has-icon-left mb-4">
                        <input type="email" wire:model.blur="email" class="form-control form-control-xl @error('email') is-invalid @enderror" placeholder="Email">
                        <div class="form-control-icon">
                            <i class="bi bi-envelope"></i>
                        </div>
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group position-relative has-icon-left mb-4">
                        <input type="text" wire:model.blur="whatsapp" class="form-control form-control-xl @error('whatsapp') is-invalid @enderror" placeholder="Nomor WhatsApp">
                        <div class="form-control-icon">
                            <i class="bi bi-whatsapp"></i>
                        </div>
                        @error('whatsapp') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group position-relative has-icon-left mb-4">
                        <input type="password" wire:model.blur="password" class="form-control form-control-xl @error('password') is-invalid @enderror" placeholder="Password">
                        <div class="form-control-icon">
                            <i class="bi bi-shield-lock"></i>
                        </div>
                        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group position-relative has-icon-left mb-4">
                        <input type="password" wire:model.blur="password_confirmation" class="form-control form-control-xl @error('password_confirmation') is-invalid @enderror" placeholder="Konfirmasi Password">
                        <div class="form-control-icon">
                            <i class="bi bi-shield-lock"></i>
                        </div>
                        @error('password_confirmation') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <button type="submit" class="btn btn-primary btn-block btn-lg shadow-lg mt-5">
                        <span wire:loading.remove wire:target="register">Sign Up</span>
                        <span wire:loading wire:target="register">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            Loading...
                        </span>
                    </button>
                </form>
                <div class="text-center mt-5 text-lg fs-4">
                    <p class='text-gray-600'>Already have an account? <a href="{{ route('login') }}" class="font-bold">Log
                            in</a>.</p>
                </div>
            </div>
        </div>
        <div class="col-lg-7 d-none d-lg-block">
            <div id="auth-right">

            </div>
        </div>
    </div>
</div>