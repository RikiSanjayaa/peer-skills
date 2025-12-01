<section class="py-5 bg-light">
    <div class="container">

        <!-- HEADER -->
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
            <div class="me-3">
                <h2 class="featured-title">
                    Layanan <span class="text-primary">Unggulan</span> PeerSkill
                </h2>
                <p class="featured-subtitle">
                    PeerSkill mempermudah 
                    <span class="text-highlight fw-semibold">mahasiswa</span> dan 
                    <span class="text-highlight fw-semibold">dosen</span> menemukan 
                    <span class="text-highlight">jasa berbasis keterampilan</span>.<br>
                    Jelajahi 
                    <span class="text-highlight fw-semibold">layanan populer</span> 
                    dan pilih yang sesuai kebutuhanmu!
                </p>
            </div>
            <a href="{{ route('gigs.index') }}" class="btn btn-outline-primary mt-3 mt-md-0">
                Lihat Semua Layanan
            </a>
        </div>

        @if ($featuredGigs->count() > 0)
            <div class="row g-4">

                @foreach ($featuredGigs as $gig)
                <div class="col-md-4">
                    <div class="card gig-card h-100 d-flex flex-column">

                        <!-- IMAGE (klikable) -->
                        <a href="{{ route('gigs.show', $gig) }}">
                            @if ($gig->images && count($gig->images) > 0)
                                <img src="{{ asset('storage/' . $gig->images[0]) }}"
                                     class="card-img-top"
                                     alt="{{ $gig->title }}"
                                     style="height: 200px; object-fit: cover;">
                            @else
                                <div class="card-img-top d-flex align-items-center justify-content-center text-white"
                                     style="height: 200px; background: linear-gradient(135deg, var(--peerskill-primary), var(--peerskill-primary-dark)); border-radius: 16px 16px 0 0;">
                                     <h5 class="text-center px-3">{{ $gig->title }}</h5>
                                </div>
                            @endif
                        </a>

                        <!-- CARD BODY (tidak dibungkus <a>, biar footer tidak ketarik) -->
                        <div class="card-body">
                            <span class="badge mb-2">{{ $gig->category->name }}</span>
                            <h5 class="card-title">{{ Str::limit($gig->title, 50) }}</h5>
                            <p class="card-text small">{{ Str::limit($gig->description, 100) }}</p>
                        </div>

                        <!-- FOOTER -->
                        <div class="card-footer mt-auto">
                            <div class="d-flex justify-content-between align-items-center">

                                <!-- SELLER -->
                                <a href="{{ route('profile.show', $gig->seller->user) }}" 
                                   class="seller-info text-decoration-none">

                                    @if ($gig->seller->user->avatar)
                                        <img src="{{ $gig->seller->user->avatar_url }}"
                                             alt="{{ $gig->seller->user->name }}"
                                             class="rounded-circle"
                                             style="width: 32px; height: 32px; object-fit: cover;">
                                    @else
                                        <div class="profile-avatar">
                                            {{ strtoupper(substr($gig->seller->user->name, 0, 1)) }}
                                        </div>
                                    @endif

                                    <small class="text-muted seller-name">
                                        {{ $gig->seller->business_name }}
                                    </small>
                                </a>

                                <!-- PRICE -->
                                <strong class="gig-price">
                                    @if ($gig->max_price)
                                        Rp {{ number_format($gig->min_price, 0, ',', '.') }}+
                                    @else
                                        Rp {{ number_format($gig->min_price, 0, ',', '.') }}
                                    @endif
                                </strong>

                            </div>
                        </div>

                    </div>
                </div>
                @endforeach

            </div>
        @else

            <!-- EMPTY -->
            <div class="text-center py-5 empty-state">
                <h4 class="text-muted">Belum ada layanan yang tersedia</h4>
                <p class="text-muted">Jadilah yang pertama membuat layanan dan mulai menawarkan jasa Anda!</p>

                @auth
                    @if (Auth::user()->is_seller)
                        <a href="{{ route('gigs.create') }}" class="btn btn-primary mt-3">Buat Layanan Pertamamu</a>
                    @else
                        <a href="{{ route('seller.register') }}" class="btn btn-primary mt-3">Jadilah Penjual</a>
                    @endif
                @else
                    <a href="{{ route('register') }}" class="btn btn-primary mt-3">Mulai</a>
                @endauth
            </div>

        @endif

    </div>
</section>



@push('styles')
<style>
    :root {
        --peerskill-blue: #2196f3;
        --subtitle-gray: #555;
    }

    /* TEXT */
    .text-highlight {
        color: var(--peerskill-blue);
        font-weight: 600;
    }

    /* HEADER */
    .featured-title { font-size: 1.95rem; font-weight: 800; color: #111; }
    .featured-subtitle { font-size: 0.95rem; color: var(--subtitle-gray); line-height: 1.45; }

    /* CARD */
    .gig-card {
        border: none;
        border-radius: 20px;
        background: #fff;
        transition: .3s;
        box-shadow: 0 4px 18px rgba(0,0,0,0.06);
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }

    /* IMAGE */
    .card-img-top { border-radius: 20px 20px 0 0; transition: .35s; }
    .gig-card:hover .card-img-top { transform: scale(1.05); }

    /* BADGE */
    .gig-card .badge {
        background: var(--peerskill-blue);
        color: #fff;
        border-radius: 20px;
        padding: 6px 12px;
        font-size: .7rem;
    }

    /* CONTENT */
    .card-body { padding: 14px 16px; }
    .card-title { font-size: 1rem; font-weight: 700; }
    .card-text { font-size: .84rem; color: #666; }

    /* FOOTER */
    .card-footer {
        background: transparent;
        border: none;
        padding: 12px 16px 18px;
        margin-top: auto;
    }

    /* SELLER FIX */
    .seller-info {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-grow: 1;            /* <-- dorong harga ke kanan */
    }

    .seller-name {
        font-size: .85rem;
        color: #555 !important;
    }

    /* AVATAR */
    .profile-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: var(--peerskill-blue);
        color: #fff;
        font-weight: 700;
        font-size: 13px;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    /* PRICE */
    .gig-price {
        font-size: 1.05rem;
        font-weight: 800;
        color: var(--peerskill-blue);
    }

    /* RESPONSIVE */
    @media (max-width: 768px) {
        .card-title { font-size: .95rem; }
    }
</style>
@endpush
