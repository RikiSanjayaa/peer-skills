@extends('layouts.app')

@section('title', 'Browse Gigs - PeerSkill')

@section('content')
    <div class="container py-5">
        <!-- Search and Filters -->
        <div class="row mb-4">
            <div class="col-lg-3">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-funnel"></i> Filters</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('gigs.index') }}" method="GET">
                            <!-- Search -->
                            <div class="mb-3 position-relative">
                                <label for="search" class="form-label fw-bold">Search</label>
                                {{-- PERBAIKAN: Cek 'search' atau 'query' agar input terisi otomatis --}}
                                <input type="text" class="form-control" id="search" name="search"
                                    placeholder="Search gigs..." 
                                    value="{{ request('search') ?? request('query') }}" 
                                    autocomplete="off">
                                    
                                <div id="searchSuggestions"
                                    class="position-absolute w-100 bg-white border rounded shadow-sm"
                                    style="display: none; z-index: 1000; max-height: 400px; overflow-y: auto;"></div>
                            </div>

                            <!-- Category -->
                            <div class="mb-3">
                                <label for="category" class="form-label fw-bold">Category</label>
                                <select class="form-select" id="category" name="category">
                                    <option value="">All Categories</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ request('category') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Price Range -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">Price Range ($)</label>
                                <div class="row g-2">
                                    <div class="col-6">
                                        <input type="number" class="form-control" name="min_price" placeholder="Min"
                                            value="{{ request('min_price') }}" min="0">
                                    </div>
                                    <div class="col-6">
                                        <input type="number" class="form-control" name="max_price" placeholder="Max"
                                            value="{{ request('max_price') }}" min="0">
                                    </div>
                                </div>
                            </div>

                            <!-- Delivery Time -->
                            <div class="mb-3">
                                <label for="delivery_days" class="form-label fw-bold">Delivery Time</label>
                                <select class="form-select" id="delivery_days" name="delivery_days">
                                    <option value="">Any</option>
                                    <option value="1" {{ request('delivery_days') == '1' ? 'selected' : '' }}>1 day
                                    </option>
                                    <option value="3" {{ request('delivery_days') == '3' ? 'selected' : '' }}>Up to
                                        3 days</option>
                                    <option value="7" {{ request('delivery_days') == '7' ? 'selected' : '' }}>Up to
                                        7 days</option>
                                    <option value="14" {{ request('delivery_days') == '14' ? 'selected' : '' }}>Up
                                        to 14 days</option>
                                    <option value="30" {{ request('delivery_days') == '30' ? 'selected' : '' }}>Up
                                        to 30 days</option>
                                </select>
                            </div>

                            <!-- Tutoring -->
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="tutoring" name="tutoring"
                                        value="1" {{ request('tutoring') == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="tutoring">
                                        Offers Tutoring
                                    </label>
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Apply Filters</button>
                                <a href="{{ route('gigs.index') }}" class="btn btn-outline-secondary">Clear All</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-9">
                <!-- Sort and Results Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2>Browse Gigs</h2>
                        <p class="text-muted mb-0">{{ $gigs->total() }} gigs found</p>
                    </div>
                    <div>
                        <form action="{{ route('gigs.index') }}" method="GET" id="sortForm">
                            @foreach (request()->except('sort') as $key => $value)
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endforeach
                            <select class="form-select" name="sort"
                                onchange="document.getElementById('sortForm').submit()">
                                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest
                                </option>
                                <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price:
                                    Low to High</option>
                                <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>
                                    Price: High to Low</option>
                                <option value="delivery" {{ request('sort') == 'delivery' ? 'selected' : '' }}>Fastest
                                    Delivery</option>
                            </select>
                        </form>
                    </div>
                </div>

                @if ($gigs->count() > 0)
                    <div class="row g-4">
                        @foreach ($gigs as $gig)
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
                                            @if ($gig->allows_tutoring)
                                                <span class="badge bg-success mb-2">Tutoring</span>
                                            @endif
                                            <h5 class="card-title">{{ Str::limit($gig->title, 50) }}</h5>
                                            <p class="card-text text-muted small">
                                                {{ Str::limit($gig->description, 100) }}</p>
                                        </div>
                                    </a>
                                    <div class="card-footer bg-white border-top">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <a href="{{ route('profile.show', $gig->seller->user) }}"
                                                class="d-flex align-items-center text-decoration-none">
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
                                            <strong class="text-primary">
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

                    <!-- Pagination -->
                    <div class="mt-5">
                        {{ $gigs->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <h4 class="text-muted">No gigs found matching your criteria</h4>
                        <p class="text-muted">Try adjusting your filters or search terms.</p>
                        <a href="{{ route('gigs.index') }}" class="btn btn-primary mt-3">Clear Filters</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Live search suggestions
        let searchTimeout;
        const searchInput = document.getElementById('search');
        const suggestionsDiv = document.getElementById('searchSuggestions');

        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                const query = this.value.trim();

                if (query.length < 2) {
                    suggestionsDiv.style.display = 'none';
                    suggestionsDiv.innerHTML = '';
                    return;
                }

                searchTimeout = setTimeout(() => {
                    fetch(`{{ route('gigs.search.suggestions') }}?q=${encodeURIComponent(query)}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.length === 0) {
                                suggestionsDiv.style.display = 'none';
                                suggestionsDiv.innerHTML = '';
                                return;
                            }

                            let html = '<div class="list-group list-group-flush">';
                            data.forEach(gig => {
                                html += `
                                    <a href="${gig.url}" class="list-group-item list-group-item-action">
                                        <div class="d-flex align-items-center">
                                            ${gig.image
                                                ? `<img src="${gig.image}" alt="${gig.title}" class="rounded me-3" style="width: 60px; height: 60px; object-fit: cover;">`
                                                : `<div class="rounded me-3 d-flex align-items-center justify-content-center text-white" style="width: 60px; height: 60px; background: linear-gradient(135deg, #00BCD4, #00ACC1); font-size: 12px; text-align: center;">${gig.title.substring(0, 20)}</div>`
                                            }
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1">${gig.title}</h6>
                                                <small class="text-muted">
                                                    <span class="badge bg-primary me-1">${gig.category}</span>
                                                    ${gig.seller} • ${gig.price}
                                                </small>
                                            </div>
                                        </div>
                                    </a>
                                `;
                            });
                            html += '</div>';

                            suggestionsDiv.innerHTML = html;
                            suggestionsDiv.style.display = 'block';
                        })
                        .catch(error => {
                            console.error('Search error:', error);
                            suggestionsDiv.style.display = 'none';
                        });
                }, 300); // Debounce for 300ms
            });

            // Hide suggestions when clicking outside
            document.addEventListener('click', function(e) {
                if (!searchInput.contains(e.target) && !suggestionsDiv.contains(e.target)) {
                    suggestionsDiv.style.display = 'none';
                }
            });

            // Show suggestions again when focusing on input
            searchInput.addEventListener('focus', function() {
                if (this.value.trim().length >= 2 && suggestionsDiv.innerHTML) {
                    suggestionsDiv.style.display = 'block';
                }
            });
        }
    </script>
@endpush
