@php
    $currentRoute = request()->route()->getName();
    // Generate consistent avatar color based on user name
    $avatarColors = ['#00bcd4', '#0097a7', '#00838f', '#006064', '#0288d1', '#039be5'];
    $colorIndex = Auth::check() ? ord(Auth::user()->name[0]) % count($avatarColors) : 0;
    $avatarColor = $avatarColors[$colorIndex];
@endphp

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="/">
            <img src="{{ asset('images/logo.png') }}" alt="PeerSkill" height="50" class="me-2">
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                @auth
                    <li class="nav-item">
                        <a class="nav-link {{ $currentRoute === 'home' ? 'active' : '' }}" href="/">Home</a>
                    </li>
                    @if (Auth::user()->is_seller)
                        <li class="nav-item">
                            <a class="nav-link {{ $currentRoute === 'seller.dashboard' ? 'active' : '' }}"
                                href="{{ route('seller.dashboard') }}">Seller Dashboard</a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link {{ $currentRoute === 'seller.register' ? 'active' : '' }}"
                                href="{{ route('seller.register') }}">Become a Seller</a>
                        </li>
                    @endif
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button"
                            data-bs-toggle="dropdown">
                            <div class="profile-avatar me-2" style="background-color: {{ $avatarColor }}">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">Logout</button>
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
