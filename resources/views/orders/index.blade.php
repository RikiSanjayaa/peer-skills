@extends('layouts.app')

@section('title', 'My Orders - PeerSkill')

@section('content')
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h2 mb-0">Pesanan Saya</h1>
        </div>

        <!-- Role Toggle -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex flex-wrap gap-3 align-items-center">
                    <span class="text-muted">View as:</span>
                    <div class="btn-group" role="group">
                        <a href="{{ route('orders.index', ['role' => 'buyer', 'filter' => $filter]) }}"
                            class="btn {{ $role === 'buyer' ? 'btn-primary' : 'btn-outline-primary' }}">
                            <i class="bi bi-cart me-1"></i> Pembeli
                        </a>
                        @if (auth()->user()->is_seller)
                            <a href="{{ route('orders.index', ['role' => 'seller', 'filter' => $filter]) }}"
                                class="btn {{ $role === 'seller' ? 'btn-primary' : 'btn-outline-primary' }}">
                                <i class="bi bi-shop me-1"></i> Penjual
                            </a>
                        @endif
                    </div>

                    <div class="vr d-none d-md-block"></div>

                    <span class="text-muted">Filter:</span>
                    <div class="btn-group" role="group">
                        <a href="{{ route('orders.index', ['role' => $role, 'filter' => 'all']) }}"
                            class="btn btn-sm {{ $filter === 'all' ? 'btn-secondary' : 'btn-outline-secondary' }}">
                            Semua
                        </a>
                        <a href="{{ route('orders.index', ['role' => $role, 'filter' => 'active']) }}"
                            class="btn btn-sm {{ $filter === 'active' ? 'btn-secondary' : 'btn-outline-secondary' }}">
                            Aktif
                        </a>
                        <a href="{{ route('orders.index', ['role' => $role, 'filter' => 'completed']) }}"
                            class="btn btn-sm {{ $filter === 'completed' ? 'btn-secondary' : 'btn-outline-secondary' }}">
                            Selesai
                        </a>
                    </div>
                </div>
            </div>
        </div>

        @if ($orders->isEmpty())
            <div class="text-center py-5">
                <i class="bi bi-inbox display-1 text-muted"></i>
                <h3 class="mt-3 text-muted">Pesanan Tidak Ditemukan</h3>
                <p class="text-muted">
                    @if ($role === 'buyer')
                        Anda belum menempatkan pesanan apa pun. Jelajahi Gig untuk memulai!
                    @else
                        Anda belum memiliki pesanan sebagai Penjual.
                    @endif
                </p>
                @if ($role === 'buyer')
                    <a href="{{ route('gigs.index') }}" class="btn btn-primary">
                        <i class="bi bi-search me-1"></i> Jelajahi Gig
                    </a>
                @endif
            </div>
        @else
            <div class="row g-4">
                @foreach ($orders as $order)
                    <div class="col-12">
                        <div class="card border-0 shadow-sm hover-shadow">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <!-- Gig Image -->
                                    <div class="col-auto d-none d-md-block">
                                        @php
                                            $images = $order->gig->images ?? [];
                                            $firstImage = count($images) > 0 ? $images[0] : null;
                                        @endphp
                                        @if ($firstImage)
                                            <img src="{{ asset('storage/' . $firstImage) }}"
                                                alt="{{ $order->gig->title }}" class="rounded"
                                                style="width: 100px; height: 70px; object-fit: cover;">
                                        @else
                                            <div class="bg-secondary rounded d-flex align-items-center justify-content-center"
                                                style="width: 100px; height: 70px;">
                                                <i class="bi bi-image text-white"></i>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Order Info -->
                                    <div class="col">
                                        <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                                            <span class="badge {{ $order->status_badge_class }}">
                                                {{ $order->status_label }}
                                            </span>
                                            @if ($order->type === 'tutoring')
                                                <span class="badge bg-info text-dark">
                                                    <i class="bi bi-mortarboard me-1"></i>Bimbingan
                                                </span>
                                            @endif
                                            <small class="text-muted">Pesanan #{{ $order->id }}</small>
                                        </div>

                                        <h5 class="mb-1">
                                            <a href="{{ route('orders.show', $order) }}"
                                                class="text-decoration-none text-dark">
                                                {{ Str::limit($order->gig->title, 60) }}
                                            </a>
                                        </h5>

                                        <div class="text-muted small">
                                            @if ($role === 'buyer')
                                                <span>Penjual: {{ $order->seller->name }}</span>
                                            @else
                                                <span>Pembeli: {{ $order->buyer->name }}</span>
                                            @endif
                                            <span class="mx-2">•</span>
                                            <span>{{ $order->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>

                                    <!-- Price & Actions -->
                                    <div class="col-auto text-end">
                                        @if ($order->price)
                                            <div class="h5 mb-0 text-success">
                                                Rp {{ number_format($order->price, 0, ',', '.') }}
                                            </div>
                                        @else
                                            <span class="text-muted">Menunggu Penawaran</span>
                                        @endif

                                        <a href="{{ route('orders.show', $order) }}"
                                            class="btn btn-sm btn-outline-primary mt-2">
                                            Lihat Detail
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $orders->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
@endsection

@push('styles')
    <style>
        .hover-shadow {
            transition: all 0.2s ease;
        }

        .hover-shadow:hover {
            transform: translateY(-2px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        }
    </style>
@endpush
