@extends('layouts.app')

@section('title', $user->name . ' - PeerSkill')

@section('content')
    <!-- Banner Section -->
    <div class="position-relative" style="height: 250px; background: linear-gradient(135deg, #00BCD4, #00ACC1);">
        @if ($user->banner)
            <img src="{{ $user->banner_url }}" alt="Profile Banner" class="w-100 h-100" style="object-fit: cover;">
        @endif
    </div>

    <div class="container">
        <!-- Profile Header -->
        <div class="row">
            <div class="col-12">
                <div class="d-flex flex-column flex-md-row align-items-start" style="margin-top: -75px;">
                    <!-- Avatar -->
                    <div class="position-relative mb-3 mb-md-0">
                        @if ($user->avatar)
                            <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}"
                                class="rounded-circle border-4 border-white shadow"
                                style="width: 150px; height: 150px; object-fit: cover; background: white;">
                        @else
                            <div class="rounded-circle border-4 border-white shadow d-flex align-items-center justify-content-center text-white"
                                style="width: 150px; height: 150px; background: linear-gradient(135deg, #00BCD4, #00ACC1); font-size: 3rem; font-weight: bold;">
                                {{ $user->initials }}
                            </div>
                        @endif
                        @if ($user->is_seller)
                            <span class="position-absolute bottom-0 end-0 badge bg-success rounded-pill px-2 py-1"
                                style="font-size: 0.7rem;">
                                <i class="bi bi-patch-check-fill"></i> Seller
                            </span>
                        @endif
                    </div>

                    <!-- User Info -->
                    <div class="ms-md-4 mt-md-5 pt-md-4">
                        <h2 class="mb-1">{{ $user->name }}</h2>
                        @if ($user->is_seller && $user->seller)
                            <p class="text-muted mb-2">
                                <i class="bi bi-briefcase"></i> {{ $user->seller->business_name }}
                            </p>
                        @endif
                        <div class="d-flex flex-wrap gap-3 text-muted small">
                            <span><i class="bi bi-calendar3"></i> Member since {{ $user->created_at->format('M Y') }}</span>
                            <span><i class="bi bi-cart-check"></i> {{ $orderCount }} orders completed</span>
                            @if ($user->is_seller && $user->seller)
                                <span><i class="bi bi-grid"></i> {{ $user->seller->gigs->count() }} gigs</span>
                            @endif
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="ms-md-auto mt-3 mt-md-5 pt-md-4">
                        @auth
                            @if (Auth::id() === $user->id)
                                <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary">
                                    <i class="bi bi-pencil"></i> Edit Profile
                                </a>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
        </div>

        <hr class="my-4">

        <div class="row">
            <!-- Left Column - About & Details -->
            <div class="col-lg-4 mb-4">
                <!-- About Section -->
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-3"><i class="bi bi-person"></i> About</h5>
                        @if ($user->bio)
                            <p class="text-muted">{{ $user->bio }}</p>
                        @else
                            <p class="text-muted fst-italic">No bio yet.</p>
                        @endif
                    </div>
                </div>

                <!-- Seller Details -->
                @if ($user->is_seller && $user->seller)
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <h5 class="card-title mb-3"><i class="bi bi-mortarboard"></i> Education</h5>
                            <p class="mb-1"><strong>{{ $user->seller->major }}</strong></p>
                            <p class="text-muted small">{{ $user->seller->university }}</p>
                        </div>
                    </div>

                    <!-- Skills -->
                    @if ($user->seller->skills && count($user->seller->skills) > 0)
                        <div class="card shadow-sm mb-4">
                            <div class="card-body">
                                <h5 class="card-title mb-3"><i class="bi bi-tools"></i> Skills</h5>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach ($user->seller->skills as $skill)
                                        <span class="badge bg-light text-dark border">{{ $skill }}</span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Portfolio -->
                    @if ($user->seller->portfolio_url)
                        <div class="card shadow-sm mb-4">
                            <div class="card-body">
                                <h5 class="card-title mb-3"><i class="bi bi-folder"></i> Portfolio</h5>
                                <a href="{{ $user->seller->portfolio_url }}" target="_blank"
                                    class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-box-arrow-up-right"></i> View Portfolio
                                </a>
                            </div>
                        </div>
                    @endif
                @endif

                <!-- Social Links -->
                @if ($user->social_links && count($user->social_links) > 0)
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <h5 class="card-title mb-3"><i class="bi bi-share"></i> Social Links</h5>
                            <div class="d-flex flex-column gap-2">
                                @foreach ($user->social_links as $link)
                                    <a href="{{ $link['url'] }}" target="_blank" class="text-decoration-none">
                                        <i class="bi bi-link-45deg"></i> {{ $link['platform'] }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Right Column - Gigs (for sellers) -->
            <div class="col-lg-8">
                @if ($user->is_seller && $user->seller && $user->seller->gigs->count() > 0)
                    <h4 class="mb-4">{{ $user->name }}'s Gigs</h4>
                    <div class="row g-4">
                        @foreach ($user->seller->gigs as $gig)
                            <div class="col-md-6">
                                <div class="card gig-card h-100">
                                    <a href="{{ route('gigs.show', $gig) }}" class="text-decoration-none text-dark">
                                        @if ($gig->images && count($gig->images) > 0)
                                            <img src="{{ asset('storage/' . $gig->images[0]) }}" class="card-img-top"
                                                alt="{{ $gig->title }}" style="height: 180px; object-fit: cover;">
                                        @else
                                            <div class="card-img-top d-flex align-items-center justify-content-center text-white"
                                                style="height: 180px; background: linear-gradient(135deg, #00BCD4, #00ACC1);">
                                                <h6 class="text-center px-3">{{ $gig->title }}</h6>
                                            </div>
                                        @endif
                                        <div class="card-body">
                                            <span class="badge bg-primary mb-2">{{ $gig->category->name }}</span>
                                            @if ($gig->allows_tutoring)
                                                <span class="badge bg-success mb-2">Tutoring</span>
                                            @endif
                                            <h6 class="card-title">{{ Str::limit($gig->title, 50) }}</h6>
                                            <p class="card-text text-muted small">{{ Str::limit($gig->description, 80) }}
                                            </p>
                                        </div>
                                        <div class="card-footer bg-white border-top">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <small class="text-muted">
                                                    <i class="bi bi-clock"></i> {{ $gig->delivery_days }} days
                                                </small>
                                                <strong class="text-primary">
                                                    @if ($gig->max_price)
                                                        Rp {{ number_format($gig->min_price, 0, ',', '.') }}+
                                                    @else
                                                        Rp {{ number_format($gig->min_price, 0, ',', '.') }}
                                                    @endif
                                                </strong>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @elseif(!$user->is_seller)
                    <div class="card shadow-sm">
                        <div class="card-body text-center py-5">
                            <i class="bi bi-person-circle text-muted" style="font-size: 4rem;"></i>
                            <h5 class="mt-3">Buyer Profile</h5>
                            <p class="text-muted">{{ $user->name }} has completed {{ $orderCount }} orders on
                                PeerSkill.</p>
                        </div>
                    </div>
                @else
                    <div class="card shadow-sm">
                        <div class="card-body text-center py-5">
                            <i class="bi bi-grid text-muted" style="font-size: 4rem;"></i>
                            <h5 class="mt-3">No Gigs Yet</h5>
                            <p class="text-muted">This seller hasn't created any gigs yet.</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
