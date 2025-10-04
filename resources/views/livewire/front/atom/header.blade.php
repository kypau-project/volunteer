<div>
    <div class="header-top">
        <div class="container">
            <div class="logo">
                <a href="{{ url('/') }}"><img src="{{asset('images/adu.png')}}" alt="Logo" style="width: 130px; height: auto;"></a>
            </div>
            <div class="header-top-right">

                <div class="dropdown">
                    @auth
                    <a href="#" id="topbarUserDropdown" class="user-dropdown d-flex align-items-center dropend dropdown-toggle " data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="avatar avatar-md2">
                            @if(auth()->user()->foto)
                            <img src="{{ asset('storage/users/' . auth()->user()->foto) }}" alt="{{ auth()->user()->name }}">
                            @else
                            <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name }}" alt="{{ auth()->user()->name }}">
                            @endif
                        </div>
                        <div class="text">
                            <h6 class="user-dropdown-name">{{ auth()->user()->name }}</h6>
                            <p class="user-dropdown-status text-sm text-muted">{{ auth()->user()->role }}</p>
                        </div>
                    </a>
                    @if(auth()->user()->role === 'admin')
                        <ul class="dropdown-menu dropdown-menu-end shadow-lg" aria-labelledby="topbarUserDropdown">
                            <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.laporan') }}">Laporan</a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.list-user') }}">Users</a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.profile') }}">My Profile</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">Logout</button>
                                </form>
                            </li>
                        </ul>
                    @else
                        <ul class="dropdown-menu dropdown-menu-end shadow-lg" aria-labelledby="topbarUserDropdown">
                            <li><a class="dropdown-item" href="{{ route('user.dashboard') }}">Dashboard</a></li>
                            <li><a class="dropdown-item" href="{{ route('user.laporan') }}">Laporan</a></li>
                            <li><a class="dropdown-item" href="{{ route('user.profile') }}">My Profile</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">Logout</button>
                                </form>
                            </li>
                        </ul>
                    @endif

                    <ul class="dropdown-menu dropdown-menu-end shadow-lg" aria-labelledby="topbarUserDropdown">
                        <li><a class="dropdown-item" href="#">My Account</a></li>
                        <li><a class="dropdown-item" href="#">Settings</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">Logout</button>
                            </form>
                        </li>
                    </ul>
                    @else
                    <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
                    @endauth
                </div>

                <!-- Burger button responsive -->
                <a href="#" class="burger-btn d-block d-xl-none">
                    <i class="bi bi-justify fs-3"></i>
                </a>
            </div>
        </div>
    </div>
</div>