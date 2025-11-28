<section class="py-5 bg-light">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2>Featured Services</h2>
                <p class="text-muted">Discover popular gigs from talented sellers</p>
            </div>
            <a href="{{ route('gigs.index') }}" class="btn btn-outline-primary">Browse All</a>
        </div>

        @if ($featuredGigs->count() > 0)
            <div class="row g-4">
                @foreach ($featuredGigs as $gig)
                    <div class="col-md-4">
                        <div class="card gig-card h-100">
                            <a href="{{ route('gigs.show', $gig) }}" class="text-decoration-none text-dark">
                                @if ($gig->images && count($gig->images) > 0)
                                    <img src="{{ asset('storage/' . $gig->images[0]) }}" class="card-img-top"
                                        alt="{{ $gig->title }}" style="height: 200px; object-fit: cover;">
                                @else
                                    <div class="card-img-top d-flex align-items-center justify-content-center text-white"
                                        style="height: 200px; background: linear-gradient(135deg, var(--peerskill-primary), var(--peerskill-primary-dark));">
                                        <h5 class="text-center px-3">{{ $gig->title }}</h5>
                                    </div>
                                @endif
                                <div class="card-body">
                                    <span class="badge bg-primary mb-2">{{ $gig->category->name }}</span>
                                    <h5 class="card-title">{{ Str::limit($gig->title, 50) }}</h5>
                                    <p class="card-text text-muted small">{{ Str::limit($gig->description, 100) }}</p>
                                </div>
                                <div class="card-footer bg-white border-top">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <a href="{{ route('profile.show', $gig->seller->user) }}"
                                            class="d-flex align-items-center text-decoration-none"
                                            onclick="event.stopPropagation();">
                                            @if ($gig->seller->user->avatar)
                                                <img src="{{ $gig->seller->user->avatar_url }}"
                                                    alt="{{ $gig->seller->user->name }}" class="rounded-circle me-2"
                                                    style="width: 30px; height: 30px; object-fit: cover;">
                                            @else
                                                <div class="profile-avatar me-2"
                                                    style="background-color: var(--peerskill-primary); width: 30px; height: 30px; font-size: 12px;">
                                                    {{ strtoupper(substr($gig->seller->user->name, 0, 1)) }}
                                                </div>
                                            @endif
                                            <small class="text-muted">{{ $gig->seller->business_name }}</small>
                                        </a>
                                        <div>
                                            <strong class="text-primary">
                                                @if ($gig->max_price)
                                                    ${{ number_format($gig->min_price, 0) }}+
                                                @else
                                                    ${{ number_format($gig->min_price, 0) }}
                                                @endif
                                            </strong>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-5">
                <h4 class="text-muted">No gigs available yet</h4>
                <p class="text-muted">Be the first to create a gig and start offering your services!</p>
                @auth
                    @if (Auth::user()->is_seller)
                        <a href="{{ route('gigs.create') }}" class="btn btn-primary mt-3">Create Your First Gig</a>
                    @else
                        <a href="{{ route('seller.register') }}" class="btn btn-primary mt-3">Become a Seller</a>
                    @endif
                @else
                    <a href="{{ route('register') }}" class="btn btn-primary mt-3">Get Started</a>
                @endauth
            </div>
        @endif
    </div>
</section>
