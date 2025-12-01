@extends('layouts.app')

@section('title', 'Seller Dashboard - PeerSkill')

@section('content')

<div class="container">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show mt-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="row g-3 mt-4">
        <div class="col-6 col-lg-3">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="display-6 text-primary mb-2">{{ $gigs->count() }}</div>
                    <div class="text-muted small">Layanan Aktif</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="display-6 text-warning mb-2">{{ $pendingOrders }}</div>
                    <div class="text-muted small">Pesanan Menunggu</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="display-6 text-info mb-2">{{ $activeOrders }}</div>
                    <div class="text-muted small">Dalam Proses</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="display-6 text-success mb-2">{{ $completedOrders }}</div>
                    <div class="text-muted small">Selesai</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <!-- Left Column -->
        <div class="col-lg-8">
            <!-- Recent Orders -->
            <div class="card mb-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-bag me-2"></i>Pesanan Terbaru</h5>
                    <a href="{{ route('orders.index', ['role' => 'seller']) }}" class="btn btn-sm btn-outline-primary">
                        Lihat Semua Pesanan
                    </a>
                </div>
                <div class="card-body">
                    @if ($recentOrders->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach ($recentOrders as $order)
                                <a href="{{ route('orders.show', $order) }}"
                                    class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3">
                                    <div>
                                        <div class="d-flex align-items-center gap-2 mb-1">
                                            <span class="badge {{ $order->status_badge_class }}">
                                                {{ $order->status_label }}
                                            </span>
                                            <small class="text-muted">Pesanan #{{ $order->id }}</small>
                                        </div>
                                        <div class="fw-medium">{{ Str::limit($order->gig->title, 40) }}</div>
                                        <small class="text-muted">
                                            <i class="bi bi-person me-1"></i>{{ $order->buyer->name }}
                                            <span class="mx-1">•</span>
                                            {{ $order->created_at->diffForHumans() }}
                                        </small>
                                    </div>
                                    @if ($order->price)
                                        <div class="text-success fw-bold">
                                            Rp {{ number_format($order->price, 0, ',', '.') }}
                                        </div>
                                    @else
                                        <span class="text-muted small">Menunggu Penawaran</span>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-inbox display-4 text-muted"></i>
                            <p class="text-muted mt-2 mb-0">Belum ada pesanan. Bagikan layanan Anda untuk memulai!</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- My Gigs -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-grid me-2"></i>Layanan Saya</h5>
                    <a href="{{ route('gigs.create') }}" class="btn btn-sm btn-primary">
                        <i class="bi bi-plus-lg me-1"></i>Buat Layanan
                    </a>
                </div>
                <div class="card-body">
                    @if ($gigs->count() > 0)
                        <div class="row g-3">
                            @foreach ($gigs as $gig)
                                <div class="col-6 col-md-4">
                                    <div class="card h-100 border">
                                        <a href="{{ route('gigs.show', $gig) }}"
                                            class="text-decoration-none text-dark">
                                            @if ($gig->images && count($gig->images) > 0)
                                                <img src="{{ asset('storage/' . $gig->images[0]) }}"
                                                    class="card-img-top" alt="{{ $gig->title }}"
                                                    style="height: 120px; object-fit: cover;">
                                            @else
                                                <div class="card-img-top d-flex align-items-center justify-content-center text-white"
                                                    style="height: 120px; background: linear-gradient(135deg, var(--peerskill-primary), var(--peerskill-primary-dark));">
                                                    <i class="bi bi-image fs-2"></i>
                                                </div>
                                            @endif
                                            <div class="card-body py-2">
                                                <span class="badge bg-primary mb-1 small">{{ $gig->category->name }}</span>
                                                <h6 class="card-title mb-1 small">{{ Str::limit($gig->title, 35) }}</h6>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="text-success fw-bold small">
                                                        Rp {{ number_format($gig->min_price, 0, ',', '.') }}
                                                        @if ($gig->max_price)
                                                            +
                                                        @endif
                                                    </span>
                                                    <small class="text-muted">{{ $gig->delivery_days }} Hari</small>
                                                </div>
                                            </div>
                                        </a>
                                        <div class="card-footer bg-white border-top py-2">
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('gigs.edit', $gig) }}"
                                                    class="btn btn-sm btn-outline-primary flex-fill py-1">
                                                    <i class="bi bi-pencil"></i> Edit
                                                </a>
                                                <form method="POST" action="{{ route('gigs.destroy', $gig) }}"
                                                    class="flex-fill"
                                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus layanan ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="btn btn-sm btn-outline-danger w-100 py-1">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-grid display-4 text-muted"></i>
                            <p class="text-muted mt-2 mb-3">Anda belum membuat layanan apa pun.</p>
                            <a href="{{ route('gigs.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-lg me-1"></i>Buat Layanan Pertama Anda
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-lg-4">
            <!-- Quick Actions -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-lightning me-2"></i>Tindakan Cepat</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('gigs.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-lg me-2"></i>Buat Layanan Baru
                        </a>
                        <a href="{{ route('orders.index', ['role' => 'seller']) }}" class="btn btn-outline-primary">
                            <i class="bi bi-bag me-2"></i>Lihat Semua Pesanan
                        </a>
                        <a href="{{ route('orders.index', ['role' => 'seller', 'filter' => 'pending']) }}"
                            class="btn btn-outline-warning">
                            <i class="bi bi-clock me-2"></i>Penawaran Menunggu
                            @if ($pendingOrders > 0)
                                <span class="badge bg-warning text-dark ms-1">{{ $pendingOrders }}</span>
                            @endif
                        </a>
                        <a href="{{ route('gigs.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-search me-2"></i>Jelajahi Marketplace
                        </a>
                    </div>
                </div>
            </div>

            <!-- Earnings Summary -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-wallet2 me-2"></i>Pendapatan</h5>
                </div>
                <div class="card-body text-center">
                    <div class="display-5 text-success mb-2">
                        Rp {{ number_format($totalEarnings, 0, ',', '.') }}
                    </div>
                    <p class="text-muted small mb-0">Total pendapatan dari {{ $completedOrders }} pesanan selesai</p>
                </div>
            </div>

            <!-- Seller Info Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Tentang Bisnis Anda</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted small mb-3">{{ $seller->description }}</p>

                    @if ($seller->portfolio_url)
                        <div class="mb-3">
                            <small class="text-muted d-block mb-1">Portfolio</small>
                            <a href="{{ $seller->portfolio_url }}" target="_blank"
                                class="text-decoration-none small">
                                <i class="bi bi-link-45deg"></i> {{ Str::limit($seller->portfolio_url, 30) }}
                            </a>
                        </div>
                    @endif

                    <div>
                        <small class="text-muted d-block mb-2">Keterampilan</small>
                        <div class="d-flex flex-wrap gap-1">
                            @foreach ($seller->skills as $skill)
                                <span class="badge bg-light text-dark border">{{ $skill }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Help Card -->
            <div class="card bg-light">
                <div class="card-body text-center">
                    <i class="bi bi-question-circle display-6 text-muted mb-2"></i>
                    <h6>Butuh Bantuan?</h6>
                    <p class="text-muted small mb-2">
                        Tips untuk mendapatkan lebih banyak pesanan: Lengkapi profil Anda, tambahkan gambar layanan berkualitas, dan tanggapi pertanyaan dengan cepat.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="mb-5"></div>
@endsection

@push('styles')
<style>
    :root {
        --blur-bg: rgba(255, 255, 255, 0.45);
        --blur-border: rgba(255, 255, 255, 0.35);
        --shadow-soft: 0 8px 25px rgba(0,0,0,0.06);
        --radius: 18px;
        --transition: 220ms ease;
    }

    .container {
        max-width: 1100px !important;
    }

    .card {
        border-radius: var(--radius) !important;
        background: rgba(255,255,255,0.52) !important;
        backdrop-filter: saturate(160%) blur(14px);
        border: 1px solid rgba(255,255,255,0.35) !important;
        box-shadow: var(--shadow-soft);
        transition: var(--transition);
        padding: 4px !important;
        transform: scale(0.96);
    }
    .card:hover {
        transform: translateY(-3px);
        box-shadow: 0 13px 35px rgba(0,0,0,0.09);
    }

    .card-header {
        border-bottom: 1px solid rgba(255,255,255,0.25) !important;
        background: rgba(255,255,255,0.15) !important;
        border-radius: var(--radius) var(--radius) 0 0 !important;
    }
    .card-header h5 {
        font-weight: 600;
        letter-spacing: -0.2px;
    }

    .card-body {
        padding: 10px !important;
    }

    .list-group-item {
        border: none !important;
        border-radius: 12px;
        padding: 18px 16px !important;
        background: rgba(255,255,255,0.65);
        backdrop-filter: blur(10px);
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        transition: 0.15s ease-in-out;
    }
    .list-group-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 3px 14px rgba(0,0,0,0.09);
    }

    .list-group {
        display: flex;
        flex-direction: column;
        gap: 14px;
    }

    .btn.btn-primary {
        background: linear-gradient(145deg, #0b7cff, #005dda) !important;
        border: none !important;
        color: white !important;
        font-weight: 600;
        box-shadow: 0 3px 14px rgba(0, 123, 255, 0.25);
        transition: 150ms ease;
    }
    .btn.btn-primary:hover {
        box-shadow: 0 5px 22px rgba(0, 123, 255, 0.33);
        transform: translateY(-2px);
    }

    .btn.btn-outline-primary {
        border: 1px solid rgba(0, 123, 255, 0.6) !important;
        color: #006ae6 !important;
        font-weight: 500;
        background: rgba(255,255,255,0.55) !important;
    }
    .btn.btn-outline-primary:hover {
        background: rgba(0,123,255,0.08) !important;
        transform: translateY(-2px);
    }

    .btn.btn-outline-warning {
        border: 1px solid rgba(255,193,7,0.7) !important;
        color: #b88600 !important;
        font-weight: 500;
        background: rgba(255,255,255,0.55) !important;
    }
    .btn.btn-outline-warning:hover {
        background: rgba(255,193,7,0.13) !important;
        transform: translateY(-2px);
    }

    .btn.btn-outline-secondary {
        border: 1px solid rgba(108,117,125,0.7) !important;
        color: #444 !important;
        font-weight: 500;
        background: rgba(255,255,255,0.55) !important;
    }
    .btn.btn-outline-secondary:hover {
        background: rgba(108,117,125,0.10) !important;
        transform: translateY(-2px);
    }

    .btn {
        border-radius: 10px;
        transition: var(--transition);
        padding: 12px 14px;
    }

    .btn .badge {
        border-radius: 7px !important;
        padding: 4px 6px !important;
        font-size: 0.7rem !important;
        box-shadow: 0 1px 4px rgba(0,0,0,0.08);
    }

    .card-body p.text-muted {
        font-size: 14px;
        line-height: 1.55;
        color: #6c757d;
        margin-bottom: 14px;
    }
    .card-body a {
        color: #0077ed;
        font-weight: 500;
    }
    .card-body a:hover {
        opacity: 0.7;
    }

    .badge.bg-light {
        background: rgba(255,255,255,0.60) !important;
        padding: 6px 10px;
        font-weight: 500;
        border-radius: 10px;
        border: 1px solid rgba(255,255,255,0.36) !important;
        transition: 0.18s ease;
        box-shadow: 0 1px 4px rgba(0,0,0,0.08) !important;
    }
    .badge.bg-light:hover {
        background: rgba(255,255,255,0.88) !important;
        transform: translateY(-1px);
        box-shadow: 0 2px 9px rgba(0,0,0,0.12) !important;
    }
</style>
@endpush
