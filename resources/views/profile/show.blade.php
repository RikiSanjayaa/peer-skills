@extends('layouts.app')

@section('title', $user->name . ' - PeerSkill')

@section('content')

    <style>
        /* Banner */
        .profile-banner {
            width: 100%;
            min-height: 180px;
            position: relative;
            overflow: hidden;
            border-radius: 0 0 18px 18px;
            background: #f7f7f7 !important;
        }

        .profile-banner img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-banner.no-banner {
            min-height: 80px;
            background: transparent !important;
        }

        /* Avatar */
        .profile-avatar-img {
            width: 95px;
            height: 95px;
            border: 3px solid #ffffff;
            border-radius: 50%;
            object-fit: cover;
            background: #ffffff;
            margin-top: -40px;
            box-shadow: 0px 6px 18px rgba(0, 0, 0, 0.12);
        }

        .profile-avatar-text {
            width: 95px;
            height: 95px;
            border-radius: 50%;
            margin-top: -40px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 1.9rem;
            font-weight: bold;
            background: linear-gradient(135deg, #00BCD4, #00ACC1);
            box-shadow: 0px 6px 18px rgba(0, 0, 0, 0.12);
        }



        /* Edit button inside banner (now always visible) */
        .edit-btn-fixed {
            position: absolute;
            top: 12px;
            right: 18px;
            z-index: 20;
        }

        /* Cards */
        .premium-card {
            border-radius: 18px;
            border: 0;
            transition: .25s ease-in-out;
        }

        .premium-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 22px rgba(0, 0, 0, 0.08);
        }

        .gig-card {
            border-radius: 16px;
            overflow: hidden;
            transition: .25s;
        }

        .gig-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 22px rgba(0, 0, 0, .1);
        }

        /* Typography */
        .profile-name {
            font-size: 1.7rem;
            font-weight: 700;
            color: #222;
        }

        .profile-meta {
            color: #666;
            font-size: .9rem;
        }

        /* Blue only on category label */
        .badge.bg-primary {
            background-color: #0077e6 !important;
        }

        .gig-image {
            width: 100%;
            height: 140px;
            object-fit: cover;
            border-radius: 10px 10px 0 0;
        }

        .gig-placeholder {
            background: linear-gradient(135deg, #00BCD4, #00ACC1);
            color: #fff;
        }

        .card.gig-card {
            border: none;
            border-radius: 12px;
            overflow: hidden;
            transition: .25s;
        }

        .card.gig-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 22px rgba(0, 0, 0, .1);
        }

        .icon-large {
            font-size: 4rem;
        }

        .avatar-circle {
            width: 95px;
            height: 95px;
            border-radius: 50%;
            background: #00ACC1;
            color: #fff;
            font-size: 34px;
            font-weight: 700;
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
        }

        .btn-edit-profile {
            border: 1.8px solid #007BFF;
            background: #fff;
            padding: 6px 18px;
            font-size: 14px;
            border-radius: 30px;
            color: #007BFF;
            transition: 0.25s;
            text-decoration: none;
            white-space: nowrap;
        }

        .btn-edit-profile:hover {
            background: #007BFF;
            color: #fff;
        }
    </style>

    {{-- ===================== BANNER ===================== --}}
    {{-- TODO: design banner dengan tinggi yang fix --}}
    <div class="profile-banner {{ $user->banner ? '' : 'no-banner' }}">
        @if ($user->banner)
            <img src="{{ $user->banner_url }}">
        @endif
    </div>

    <div class="container">

        {{-- ===================== PROFILE HEADER ===================== --}}
        <div class="d-flex align-items-start gap-3 mb-3">

            {{-- Avatar --}}
            <div class="avatar-circle">
                {{ strtoupper(substr($user->name, 0, 2)) }}
            </div>

            <div class="flex-grow-1">
                <h4 class="fw-bold m-0 d-flex align-items-center gap-2">
                    {{ $user->name }}
                    @if ($user->is_seller)
                        <span class="badge bg-success">Penjual</span>
                    @endif
                </h4>

                <div class="text-muted small d-flex flex-wrap gap-3 mt-1">
                    <span><i class="bi bi-briefcase"></i> SellerPro</span>
                    <span><i class="bi bi-calendar-check"></i> Bergabung: Nov 2025</span>
                    <span><i class="bi bi-basket"></i> {{ $orderCount }} pesanan selesai</span>
                    @if ($user->is_seller)
                        <span><i class="bi bi-grid"></i> {{ $user->seller->gigs->count() }} Layanan</span>
                    @endif
                </div>

                @auth
                    @if (Auth::id() === $user->id)
                        {{-- Move Edit Button Here --}}
                        <div class="mt-3">
                            <a href="{{ route('profile.edit') }}" class="btn-edit-profile">
                                <i class="bi bi-pencil"></i> Edit Profil
                            </a>
                        </div>
                    @endif
                @endauth
            </div>
        </div>

        <hr class="mb-4">


        {{-- ===================== MAIN CONTENT ===================== --}}
        <div class="row">

            {{-- LEFT --}}
            <div class="col-lg-4 mb-4">

                <div class="card premium-card mb-4">
                    <div class="card-body">
                        <h5 class="fw-semibold mb-3"><i class="bi bi-person"></i> Tentang</h5>
                        <p class="text-muted">{{ $user->bio ?? 'Belum ada bio.' }}</p>
                    </div>
                </div>

                @if ($user->is_seller && $user->seller)
                    <div class="card premium-card mb-4">
                        <div class="card-body">
                            <h5 class="fw-semibold mb-3"><i class="bi bi-mortarboard"></i> Pendidikan</h5>
                            <p class="mb-1 fw-semibold">{{ $user->seller->major }}</p>
                            <p class="text-muted small">{{ $user->seller->university }}</p>
                        </div>
                    </div>

                    @if ($user->seller->skills)
                        <div class="card premium-card mb-4">
                            <div class="card-body">
                                <h5 class="fw-semibold mb-3"><i class="bi bi-tools"></i> Keterampilan</h5>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach ($user->seller->skills as $skill)
                                        <span class="badge bg-light text-dark px-3 py-2 rounded-pill border">
                                            {{ $skill }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($user->seller->portfolio_url)
                        <div class="card premium-card mb-4">
                            <div class="card-body">
                                <h5 class="fw-semibold mb-3"><i class="bi bi-folder"></i> Portofolio</h5>
                                <a href="{{ $user->seller->portfolio_url }}" target="_blank"
                                    class="btn btn-outline-primary btn-sm rounded-pill px-3">
                                    <i class="bi bi-box-arrow-up-right"></i> Lihat
                                </a>
                            </div>
                        </div>
                    @endif
                @endif

                @if ($user->social_links)
                    <div class="card premium-card mb-4">
                        <div class="card-body">
                            <h5 class="fw-semibold mb-3"><i class="bi bi-share"></i> Tautan Sosial</h5>
                            <div class="d-flex flex-column gap-2">
                                @foreach ($user->social_links as $link)
                                    <a href="{{ $link['url'] }}" target="_blank" class="text-decoration-none">
                                        <i class="bi bi-link-45deg"></i>
                                        {{ $link['platform'] }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            {{-- RIGHT --}}
            <div class="col-lg-8">

                @if ($user->is_seller && $user->seller && $user->seller->gigs->count())
                    <h4 class="fw-semibold mb-4">{{ $user->name }}'s Gigs</h4>

                    <div class="row g-3">
                        @foreach ($user->seller->gigs as $gig)
                            <div class="col-6 col-md-4"> {{-- ⬅ 3 kolom saat layar besar --}}
                                <a href="{{ route('gigs.show', $gig) }}" class="text-decoration-none text-dark">
                                    <div class="card gig-card h-100">

                                        @if ($gig->images)
                                            <img src="{{ asset('storage/' . $gig->images[0]) }}" class="gig-image">
                                        @else
                                            <div
                                                class="gig-image gig-placeholder d-flex align-items-center justify-content-center">
                                                <small class="px-2 fw-semibold">{{ Str::limit($gig->title, 25) }}</small>
                                            </div>
                                        @endif

                                        <div class="p-2">
                                            <span class="badge bg-primary mb-2 small">{{ $gig->category->name }}</span>

                                            <h6 class="fw-semibold small text-truncate">
                                                {{ Str::limit($gig->title, 35) }}
                                            </h6>

                                            <div class="d-flex justify-content-between align-items-center mt-1">
                                                <small class="text-muted">
                                                    <i class="bi bi-clock"></i> {{ $gig->delivery_days }}h
                                                </small>
                                                <strong class="text-primary small">
                                                    Rp {{ number_format($gig->min_price, 0, ',', '.') }}
                                                </strong>
                                            </div>
                                        </div>

                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                @elseif (!$user->is_seller)
                    <div class="card premium-card text-center py-5">
                        <i class="bi bi-person-circle text-muted icon-large"></i>
                        <h5 class="mt-3">Profil Pembeli</h5>
                        <p class="text-muted">{{ $user->name }} telah menyelesaikan {{ $orderCount }} pesanan.</p>
                    </div>
                @else
                    <div class="card premium-card text-center py-5">
                        <i class="bi bi-grid text-muted icon-large"></i>
                        <h5 class="mt-3">Belum Ada Layanan</h5>
                        <p class="text-muted">Penjual ini belum membuat layanan apapun.</p>
                    </div>
                @endif
            </div>

        </div>

    </div>

@endsection
