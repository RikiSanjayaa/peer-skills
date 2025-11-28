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
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="display-6 text-primary mb-2">{{ $gigs->count() }}</div>
                        <div class="text-muted small">Active Gigs</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="display-6 text-warning mb-2">{{ $pendingOrders }}</div>
                        <div class="text-muted small">Pending Orders</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="display-6 text-info mb-2">{{ $activeOrders }}</div>
                        <div class="text-muted small">In Progress</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="display-6 text-success mb-2">{{ $completedOrders }}</div>
                        <div class="text-muted small">Completed</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <!-- Left Column -->
            <div class="col-lg-8">
                <!-- Recent Orders -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="bi bi-bag me-2"></i>Recent Orders</h5>
                        <a href="{{ route('orders.index', ['role' => 'seller']) }}" class="btn btn-sm btn-outline-primary">
                            View All Orders
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
                                                <small class="text-muted">Order #{{ $order->id }}</small>
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
                                            <span class="text-muted small">Awaiting Quote</span>
                                        @endif
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="bi bi-inbox display-4 text-muted"></i>
                                <p class="text-muted mt-2 mb-0">No orders yet. Share your gigs to get started!</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- My Gigs -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="bi bi-grid me-2"></i>My Gigs</h5>
                        <a href="{{ route('gigs.create') }}" class="btn btn-sm btn-primary">
                            <i class="bi bi-plus-lg me-1"></i>Create Gig
                        </a>
                    </div>
                    <div class="card-body">
                        @if ($gigs->count() > 0)
                            <div class="row g-3">
                                @foreach ($gigs as $gig)
                                    <div class="col-md-6">
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
                                                    <span
                                                        class="badge bg-primary mb-1 small">{{ $gig->category->name }}</span>
                                                    <h6 class="card-title mb-1 small">{{ Str::limit($gig->title, 35) }}
                                                    </h6>
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <span class="text-success fw-bold small">
                                                            Rp {{ number_format($gig->min_price, 0, ',', '.') }}
                                                            @if ($gig->max_price)
                                                                +
                                                            @endif
                                                        </span>
                                                        <small class="text-muted">{{ $gig->delivery_days }}d</small>
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
                                                        onsubmit="return confirm('Are you sure you want to delete this gig?');">
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
                                <p class="text-muted mt-2 mb-3">You haven't created any gigs yet.</p>
                                <a href="{{ route('gigs.create') }}" class="btn btn-primary">
                                    <i class="bi bi-plus-lg me-1"></i>Create Your First Gig
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Column - Sidebar -->
            <div class="col-lg-4">
                <!-- Quick Actions -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="bi bi-lightning me-2"></i>Quick Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('gigs.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-lg me-2"></i>Create New Gig
                            </a>
                            <a href="{{ route('orders.index', ['role' => 'seller']) }}" class="btn btn-outline-primary">
                                <i class="bi bi-bag me-2"></i>View All Orders
                            </a>
                            <a href="{{ route('orders.index', ['role' => 'seller', 'filter' => 'pending']) }}"
                                class="btn btn-outline-warning">
                                <i class="bi bi-clock me-2"></i>Pending Quotes
                                @if ($pendingOrders > 0)
                                    <span class="badge bg-warning text-dark ms-1">{{ $pendingOrders }}</span>
                                @endif
                            </a>
                            <a href="{{ route('gigs.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-search me-2"></i>Browse Marketplace
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Earnings Summary -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="bi bi-wallet2 me-2"></i>Earnings</h5>
                    </div>
                    <div class="card-body text-center">
                        <div class="display-5 text-success mb-2">
                            Rp {{ number_format($totalEarnings, 0, ',', '.') }}
                        </div>
                        <p class="text-muted small mb-0">Total earnings from {{ $completedOrders }} completed orders</p>
                    </div>
                </div>

                <!-- Seller Info Card -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>About Your Business</h5>
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

                        <div class="mb-0">
                            <small class="text-muted d-block mb-2">Skills</small>
                            <div class="d-flex flex-wrap gap-1">
                                @foreach ($seller->skills as $skill)
                                    <span class="badge bg-light text-dark border">{{ $skill }}</span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Help Card -->
                <div class="card border-0 shadow-sm bg-light">
                    <div class="card-body text-center">
                        <i class="bi bi-question-circle display-6 text-muted mb-2"></i>
                        <h6>Need Help?</h6>
                        <p class="text-muted small mb-2">
                            Tips to get more orders: Complete your profile, add quality gig images, and respond quickly
                            to inquiries.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mb-5"></div>
@endsection
