@extends('layouts.app')

@section('title', $gig->title . ' - PeerSkill')

@section('content')
    <div class="container py-5">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row">
            <!-- Gig Images/Carousel -->
            <div class="col-lg-8">
                @if ($gig->images && count($gig->images) > 0)
                    <div id="gigCarousel" class="carousel slide mb-4" data-bs-ride="carousel">
                        <div class="carousel-inner rounded">
                            @foreach ($gig->images as $index => $image)
                                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                    <img src="{{ asset('storage/' . $image) }}" class="d-block w-100"
                                        alt="{{ $gig->title }}" style="height: 400px; object-fit: cover;">
                                </div>
                            @endforeach
                        </div>
                        @if (count($gig->images) > 1)
                            <button class="carousel-control-prev" type="button" data-bs-target="#gigCarousel"
                                data-bs-slide="prev">
                                <span class="carousel-control-prev-icon"></span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#gigCarousel"
                                data-bs-slide="next">
                                <span class="carousel-control-next-icon"></span>
                            </button>
                        @endif
                    </div>
                @else
                    <div class="card mb-4"
                        style="height: 400px; background: linear-gradient(135deg, var(--peerskill-primary), var(--peerskill-primary-dark));">
                        <div class="card-body d-flex align-items-center justify-content-center">
                            <h2 class="text-white text-center">{{ $gig->title }}</h2>
                        </div>
                    </div>
                @endif

                <!-- Gig Details -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <span class="badge bg-primary mb-2">{{ $gig->category->name }}</span>
                                <h3 class="mb-0">{{ $gig->title }}</h3>
                            </div>
                            @auth
                                @if (Auth::user()->is_seller && $gig->seller_id === Auth::user()->seller->id)
                                    <div class="dropdown">
                                        <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button"
                                            data-bs-toggle="dropdown">
                                            Manage
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="{{ route('gigs.edit', $gig) }}">Edit Gig</a>
                                            </li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li>
                                                <form method="POST" action="{{ route('gigs.destroy', $gig) }}"
                                                    onsubmit="return confirm('Are you sure you want to delete this gig?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger">Delete
                                                        Gig</button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                @endif
                            @endauth
                        </div>

                        <div class="mb-4">
                            <h5>About This Gig</h5>
                            <p class="text-muted" style="white-space: pre-line;">{{ $gig->description }}</p>
                        </div>

                        @if ($gig->attachments && count($gig->attachments) > 0)
                            <div class="mb-4">
                                <h5>Portfolio Attachments</h5>
                                <div class="list-group">
                                    @foreach ($gig->attachments as $attachment)
                                        <a href="{{ asset('storage/' . $attachment['path']) }}" target="_blank"
                                            class="list-group-item list-group-item-action">
                                            <i class="bi bi-file-earmark-arrow-down"></i>
                                            {{ $attachment['original_name'] }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Seller Info -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="mb-3">About the Seller</h5>
                        <a href="{{ route('profile.show', $gig->seller->user) }}" class="text-decoration-none">
                            <div class="d-flex align-items-center mb-3">
                                @if ($gig->seller->user->avatar)
                                    <img src="{{ $gig->seller->user->avatar_url }}" alt="{{ $gig->seller->user->name }}"
                                        class="rounded-circle me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                @else
                                    <div class="profile-avatar me-3"
                                        style="background-color: var(--peerskill-primary); width: 50px; height: 50px; font-size: 18px;">
                                        {{ strtoupper(substr($gig->seller->user->name, 0, 1)) }}
                                    </div>
                                @endif
                                <div>
                                    <h6 class="mb-0 text-dark">{{ $gig->seller->business_name }}</h6>
                                    <small class="text-muted">{{ $gig->seller->user->name }}</small>
                                </div>
                            </div>
                        </a>
                        <p class="text-muted mb-2">{{ $gig->seller->description }}</p>
                        <div class="row text-muted small">
                            <div class="col-6">
                                <strong>Education:</strong> {{ $gig->seller->major }}<br>
                                <strong>University:</strong> {{ $gig->seller->university }}
                            </div>
                            <div class="col-6">
                                @if ($gig->seller->portfolio_url)
                                    <strong>Portfolio:</strong> <a href="{{ $gig->seller->portfolio_url }}"
                                        target="_blank">View</a>
                                @endif
                            </div>
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('profile.show', $gig->seller->user) }}"
                                class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-person"></i> View Profile
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Card -->
            <div class="col-lg-4">
                <div class="card sticky-top" style="top: 100px;">
                    <div class="card-body">
                        <div class="mb-3">
                            <h4 class="mb-2">
                                @if ($gig->max_price)
                                    Rp {{ number_format($gig->min_price, 0, ',', '.') }} -
                                    Rp {{ number_format($gig->max_price, 0, ',', '.') }}
                                @else
                                    Rp {{ number_format($gig->min_price, 0, ',', '.') }}
                                @endif
                            </h4>
                            <small class="text-muted">
                                @if ($gig->max_price)
                                    Price range (negotiable based on requirements)
                                @else
                                    Fixed price
                                @endif
                            </small>
                        </div>

                        <hr>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span><i class="bi bi-clock"></i> Delivery Time:</span>
                                <strong>{{ $gig->delivery_days }}
                                    {{ $gig->delivery_days == 1 ? 'day' : 'days' }}</strong>
                            </div>
                            @if ($gig->allows_tutoring)
                                <div class="d-flex justify-content-between">
                                    <span><i class="bi bi-mortarboard"></i> Tutoring:</span>
                                    <strong class="text-success">Available</strong>
                                </div>
                            @endif
                        </div>

                        <hr>

                        @auth
                            @if (Auth::user()->is_seller && $gig->seller_id === Auth::user()->seller->id)
                                <div class="alert alert-info small mb-0">
                                    This is your gig. Buyers will see the order button here.
                                </div>
                            @else
                                <a href="{{ route('orders.create', $gig) }}" class="btn btn-primary w-100 mb-2">
                                    <i class="bi bi-cart-plus me-1"></i> Order Now
                                </a>
                                @if ($gig->allows_tutoring)
                                    <a href="{{ route('orders.create', $gig) }}?type=tutoring"
                                        class="btn btn-outline-primary w-100">
                                        <i class="bi bi-mortarboard me-1"></i> Book Tutoring
                                    </a>
                                @endif
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary w-100 mb-2">
                                Login to Order
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
