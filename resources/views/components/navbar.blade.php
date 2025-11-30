@php
    $currentRoute = request()->route()->getName();
    // Generate consistent avatar color based on user name
    $avatarColors = ['#00bcd4', '#0097a7', '#00838f', '#006064', '#0288d1', '#039be5'];
    $colorIndex = Auth::check() ? ord(Auth::user()->name[0]) % count($avatarColors) : 0;
    $avatarColor = $avatarColors[$colorIndex];
@endphp

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
    <div class="container">
        <a href="{{ url('/') }}" class="navbar-brand">
    {{-- BARIS INI MENAMPILKAN GAMBAR LOGO --}}
    <img src="{{ asset('images/logo.jpeg') }}" alt="PeerSkill Logo" height="50" class="me-2">
    
    {{-- BARIS INI MENAMPILKAN TEKS PEERSKILL --}}
    PeerSkill
</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                @auth
                    @if (Auth::check() && Auth::user()->role === 'admin')
                        <li class="nav-item">
                            <a class="nav-link {{ $currentRoute === 'admin.dashboard' ? 'active' : '' }}"
                                href="{{ route('admin.dashboard') }}">
                                Dashboard Admin
                            </a>
                        </li>
                    @endif
                    <li class="nav-item">
                        <a class="nav-link {{ $currentRoute === 'home' ? 'active' : '' }}" href="/">Home</a>
                    </li>
                    @if (Auth::user()->role !== 'admin')
                        @if (Auth::user()->is_seller)
                            <li class="nav-item">
                                <a class="nav-link {{ $currentRoute === 'seller.dashboard' ? 'active' : '' }}"
                                    href="{{ route('seller.dashboard') }}">Dashboard Penjual</a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link {{ $currentRoute === 'seller.register' ? 'active' : '' }}"
                                    href="{{ route('seller.register') }}">Become a Seller</a>
                            </li>
                        @endif
                    @endif
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button"
                            data-bs-toggle="dropdown">
                            @if (Auth::user()->avatar)
                                <img src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->name }}"
                                    class="rounded-circle me-2" style="width: 32px; height: 32px; object-fit: cover;">
                            @else
                                <div class="profile-avatar me-2" style="background-color: {{ $avatarColor }}">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                            @endif
                            <span class="d-none d-lg-inline">{{ Auth::user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="{{ route('profile.show', Auth::user()) }}">
                                    <i class="bi bi-person me-2"></i>My Profile
                                </a>
                            </li>
                            @if (Auth::user()->role !== 'admin')
                                <li>
                                    <a class="dropdown-item" href="{{ route('orders.index') }}">
                                        <i class="bi bi-bag me-2"></i>My Orders
                                    </a>
                                </li>
                            @endif
                            <li>
                                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                    <i class="bi bi-gear me-2"></i>Edit Profile
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="bi bi-box-arrow-right me-2"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Sign In</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-primary ms-2" href="{{ route('register') }}" style="color: white;">Sign Up</a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
