<div class="offcanvas offcanvas-start" tabindex="-1" id="adminSidebar" aria-labelledby="adminSidebarLabel">
    
    <div class="offcanvas-header bg-info text-white">
        <h5 class="offcanvas-title fw-bold" id="adminSidebarLabel">
            <i class="bi bi-speedometer2 me-2"></i> Admin Panel
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>

    <div class="offcanvas-body p-0 d-flex flex-column h-100">
        
        <div class="p-3 bg-light border-bottom text-center">
            <div class="mb-2">
                @if(Auth::user()->avatar)
                    <img src="{{ Auth::user()->avatar_url }}" class="rounded-circle border border-3 border-white shadow-sm" style="width: 80px; height: 80px; object-fit: cover;">
                @else
                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto shadow-sm" style="width: 80px; height: 80px; font-size: 2rem;">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                @endif
            </div>
            <h6 class="fw-bold mb-0">{{ Auth::user()->name }}</h6>
            <small class="text-muted">{{ Auth::user()->email }}</small>
            
            <div class="mt-3 d-flex justify-content-center gap-2">
                <a href="{{ route('profile.show', Auth::user()) }}" class="btn btn-sm btn-outline-primary" title="Lihat Profil">
                    <i class="bi bi-person-circle"></i>
                </a>
                <a href="{{ route('profile.edit') }}" class="btn btn-sm btn-outline-secondary" title="Edit Profil">
                    <i class="bi bi-gear-fill"></i>
                </a>
            </div>
        </div>

        <div class="list-group list-group-flush flex-grow-1">
            
            <div class="list-group-item bg-white fw-bold text-muted small text-uppercase mt-2 border-0">
                Main
            </div>
            <a href="{{ route('admin.dashboard') }}" class="list-group-item list-group-item-action border-0 px-4 py-3 {{ request()->routeIs('admin.dashboard') ? 'text-primary bg-light fw-bold border-start border-4 border-primary' : '' }}">
                <i class="bi bi-grid-fill me-3"></i> Dashboard
            </a>
            <a href="{{ route('home') }}" class="list-group-item list-group-item-action border-0 px-4 py-3 {{ request()->routeIs('home') ? 'text-primary bg-light fw-bold border-start border-4 border-primary' : '' }}">
                <i class="bi bi-house-door-fill me-3"></i> Home Page
            </a>

            <div class="list-group-item bg-white fw-bold text-muted small text-uppercase mt-2 border-0">
                Data Master
            </div>
            <a href="{{ route('admin.categories.index') }}" class="list-group-item list-group-item-action border-0 px-4 py-3 {{ request()->routeIs('admin.categories.*') ? 'text-primary bg-light fw-bold border-start border-4 border-primary' : '' }}">
                <i class="bi bi-tags-fill me-3"></i> Manage Categories
            </a>
            <a href="{{ route('admin.users.index') }}" class="list-group-item list-group-item-action border-0 px-4 py-3 {{ request()->routeIs('admin.users.*') ? 'text-primary bg-light fw-bold border-start border-4 border-primary' : '' }}">
                <i class="bi bi-people-fill me-3"></i> Manage Users
            </a>

            <div class="list-group-item bg-white fw-bold text-muted small text-uppercase mt-2 border-0">
                Moderasi
            </div>
            <a href="{{ route('admin.sellers.index') }}" class="list-group-item list-group-item-action border-0 px-4 py-3 {{ request()->routeIs('admin.sellers.*') ? 'text-primary bg-light fw-bold border-start border-4 border-primary' : '' }}">
                <i class="bi bi-person-badge-fill me-3"></i> Verifikasi Seller
            </a>
            <a href="{{ route('admin.reports.index') }}" class="list-group-item list-group-item-action border-0 px-4 py-3 {{ request()->routeIs('admin.reports.*') ? 'text-primary bg-light fw-bold border-start border-4 border-primary' : '' }}">
                <i class="bi bi-exclamation-triangle-fill me-3"></i> Laporan Masalah
            </a>
            <a href="{{ route('admin.appeals.index') }}" class="list-group-item list-group-item-action border-0 px-4 py-3 {{ request()->routeIs('admin.appeals.*') ? 'text-primary bg-light fw-bold border-start border-4 border-primary' : '' }}">
                <i class="bi bi-shield-lock-fill me-3"></i> Banding User
            </a>
        </div>

        <div class="p-3 border-top bg-light">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-danger w-100 fw-bold">
                    <i class="bi bi-box-arrow-left me-2"></i> Logout
                </button>
            </form>
        </div>

    </div>
</div>