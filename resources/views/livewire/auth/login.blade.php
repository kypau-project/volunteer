<div>
    <div class="row h-100">
        <div class="col-lg-5 col-12">
            <div id="auth-left">
                <div class="auth-logo mb-4">
                    <a href="#">
                        <img src="{{ asset('images/adu.png') }}" alt="Logo" class="img-fluid" style="width: 160px; height: auto;">
                    </a>
                </div>

                <h1 class="auth-title">Log in</h1>
                <p class="auth-subtitle mb-5">Masukkan email serta password.</p>

                <form wire:submit.prevent="login" novalidate>
                    {{-- ID User field --}}
                    <div class="form-group position-relative has-icon-left mb-4">
                        <label for="idUser" class="form-label visually-hidden">Email</label>
                        <input
                            wire:model.defer="idUser"
                            type="text"
                            id="idUser"
                            class="form-control form-control-xl @error('idUser') is-invalid @enderror"
                            placeholder="Email"
                            autocomplete="username">
                        <div class="form-control-icon">
                            <i class="bi-person"></i>
                        </div>
                        @error('idUser')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Password field --}}
                    <div class="form-group position-relative has-icon-left mb-4">
                        <label for="password" class="form-label visually-hidden">Password</label>
                        <input
                            wire:model.defer="password"
                            type="password"
                            id="password"
                            class="form-control form-control-xl @error('password') is-invalid @enderror"
                            placeholder="Password"
                            autocomplete="current-password">
                        <div class="form-control-icon">
                            <i class="bi-shield-lock"></i>
                        </div>
                        @error('password')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Remember --}}
                    <div class="form-check form-check-lg d-flex align-items-end mb-3">
                        <input wire:model="remember" class="form-check-input me-2" type="checkbox" id="remember">
                        <label class="form-check-label text-gray-600" for="remember">Keep me logged in</label>
                    </div>

                    {{-- Submit button --}}
                    <button
                        type="submit"
                        class="btn btn-primary btn-block btn-lg shadow-lg mt-3"
                        wire:loading.attr="disabled"
                        wire:target="login">
                        <span wire:loading.remove wire:target="login">Log in</span>
                        <span wire:loading wire:target="login">Loading...</span>
                    </button>
                </form>

                <div class="text-center mt-5 text-lg fs-4">
                    <p class="text-gray-600">Don't have an account? <a href="{{ route('register') }}" class="font-bold">Sign up</a>.</p>
                    <p><a class="font-bold" href="#">Forgot password?</a>.</p>
                </div>
            </div>
        </div>

        <div class="col-lg-7 d-none d-lg-block">
            <div id="auth-right">
                {{-- ilustrasi atau background --}}
            </div>
        </div>
    </div>
</div>