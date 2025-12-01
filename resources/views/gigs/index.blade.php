@extends('layouts.app')

@section('title', 'Telusuri Layanan - PeerSkill')

@section('content')
<div class="container py-5">
    <div class="row g-4">

        <!-- Sidebar Filter -->
        <div class="col-lg-3">
            <div class="card shadow-sm filter-card">
                <div class="card-header text-white">
                    <h5 class="mb-0"><i class="bi bi-funnel me-2"></i>Filter</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('gigs.index') }}" method="GET">
                        <div class="mb-3 position-relative">
                            <label for="search" class="form-label fw-bold">Cari</label>
                            <input type="text" class="form-control rounded-pill" name="search"
                                   placeholder="Cari layanan..." value="{{ request('search') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Kategori</label>
                            <select class="form-select rounded-pill" name="category">
                                <option value="">Semua Kategori</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected':'' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <label class="form-label fw-bold">Harga</label>
                        <div class="row g-2">
                            <div class="col-6">
                                <input type="number" class="form-control rounded" name="min_price" placeholder="Min"
                                       value="{{ request('min_price') }}">
                            </div>
                            <div class="col-6">
                                <input type="number" class="form-control rounded" name="max_price" placeholder="Max"
                                       value="{{ request('max_price') }}">
                            </div>
                        </div>

                        <div class="form-check form-switch my-3">
                            <input class="form-check-input" type="checkbox" name="tutoring" value="1"
                                   {{ request('tutoring')=='1'?'checked':'' }}>
                            <label class="form-check-label fw-semibold">Dengan Bimbingan</label>
                        </div>

                        <div class="d-grid gap-2">
                            <button class="btn btn-primary rounded-pill">Terapkan Filter</button>
                            <a href="{{ route('gigs.index') }}" class="btn btn-outline-secondary rounded-pill">Reset</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-9">
            <h2 class="text-primary fw-bold mb-3">Telusuri Layanan</h2>

            @if($gigs->count() > 0)
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                    @foreach($gigs as $gig)
                    <div class="col">
                        <div class="gig-card shadow-sm">
                            <a href="{{ route('gigs.show', $gig) }}" class="text-dark text-decoration-none">
                                @if($gig->images && count($gig->images) > 0)
                                    <img src="{{ asset('storage/'.$gig->images[0]) }}" class="gig-img">
                                @else
                                    <div class="gig-img d-flex align-items-center justify-content-center text-white"
                                         style="background: linear-gradient(135deg, var(--peerskill-primary), var(--peerskill-primary-dark));">
                                        <span class="px-2 text-center">{{ Str::limit($gig->title, 25) }}</span>
                                    </div>
                                @endif
                                <div class="p-2 text-center">
                                    <span class="badge bg-primary mb-1">{{ $gig->category->name }}</span>
                                    @if($gig->allows_tutoring)
                                        <span class="badge bg-success mb-1">Bimbingan</span>
                                    @endif
                                    <h6 class="gig-title mt-1">{{ Str::limit($gig->title, 40) }}</h6>
                                </div>
                            </a>

                            <div class="gig-footer px-3 py-2 d-flex justify-content-between align-items-center">
                                <small class="text-muted d-flex align-items-center">
                                    @if($gig->seller->user->avatar)
                                        <img src="{{ $gig->seller->user->avatar_url }}" class="rounded-circle me-2"
                                             style="width:32px;height:32px;object-fit:cover;">
                                    @else
                                        <div class="avatar-placeholder">
                                            {{ strtoupper(substr($gig->seller->user->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    {{ $gig->seller->business_name }}
                                </small>

                                <strong class="text-primary">
                                    Rp {{ number_format($gig->min_price, 0, ',', '.') }}
                                </strong>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="mt-4">{{ $gigs->links() }}</div>

            @else
                <div class="text-center py-5">
                    <h6 class="fw-semibold text-muted">Tidak ada layanan ditemukan.</h6>
                </div>
            @endif
        </div>

    </div>
</div>
@endsection

@push('styles')
<style>
/* ==== GLOBAL BRAND COLORS ==== */
:root {
    --peerskill-primary: #00BCD4;
    --peerskill-primary-dark: #00ACC1;
    --peerskill-grey: #6c757d;
    --peerskill-bg-light: #f8f9fa;
}

/* ==== CARD SERVICE STYLE ==== */
.gig-card {
    border: none;
    border-radius: 16px;
    overflow: hidden;
    background: #fff;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    cursor: pointer;
}

/* Hover: naik + shadow halus */
.gig-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 12px 24px rgba(0,0,0,0.12);
}

/* Gambar layanan */
.gig-img {
    height: 180px;
    width: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

/* Zoom lembut saat hover */
.gig-card:hover .gig-img {
    transform: scale(1.05);
}

/* Title */
.gig-title {
    font-size: 15px;
    font-weight: 600;
    min-height: 42px;
    margin-top: 6px;
    transition: color 0.25s ease;
}

/* Hover warna title */
.gig-card:hover .gig-title {
    color: var(--peerskill-primary);
}

/* Footer */
.gig-footer {
    background: #fff;
    font-size: 14px;
    border-top: 1px solid #eee;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

/* Avatar fallback */
.avatar-placeholder {
    width: 32px;
    height: 32px;
    background: var(--peerskill-primary);
    border-radius: 50%;
    font-size: 12px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    margin-right: 6px;
}

/* Badge */
.badge {
    font-size: 0.72rem;
    padding: 4px 8px;
    border-radius: 12px;
    margin-right: 4px;
    transition: transform 0.25s ease;
}

/* Hover badge */
.gig-card:hover .badge {
    transform: scale(1.1);
}

/* Sidebar filter */
.filter-card {
    border: none;
    border-radius: 16px;
}

.filter-card .card-header {
    background: var(--peerskill-primary);
    color: #fff;
    border-radius: 16px 16px 0 0;
}

.rounded-pill {
    border-radius: 50px !important;
}

/* Button primary hover */
.btn-primary {
    background: var(--peerskill-primary);
    border-color: var(--peerskill-primary);
    transition: all 0.25s ease;
}
.btn-primary:hover {
    background: var(--peerskill-primary-dark);
    border-color: var(--peerskill-primary-dark);
}

/* Responsive tweaks */
@media (max-width: 576px) {
    .gig-img {
        height: 150px;
    }
    .gig-title {
        font-size: 14px;
    }
    .badge {
        font-size: 0.7rem;
        padding: 3px 6px;
    }
}
</style>
@endpush

