@extends('layouts.app')

@section('title', 'PeerSkill - Find the Perfect Freelance Services')

@push('styles')
    <style>
        :root {
            --primary-color: #00BCD4;
            --primary-dark: #00ACC1;
            --secondary-color: #1a1a1a;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
        }

        .hover-lift {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
        }

        .hover-lift:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        }

        .category-card:hover .category-icon {
            transform: scale(1.1);
            transition: transform 0.3s ease;
        }

        .gig-card img {
            height: 200px;
            object-fit: cover;
        }

        .min-vh-50 {
            min-height: 50vh;
        }

        .navbar {
            transition: box-shadow 0.3s ease;
        }

        .navbar.scrolled {
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
    </style>
@endpush

@section('content')
    <!-- Hero Section -->
    @include('components.hero')

    <!-- Categories Section -->
    @include('components.categories')

    <!-- Featured Gigs Section -->
    @include('components.featured-gigs')
@endsection

@push('scripts')
    <script>
        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Hero search suggestions
        let heroSearchTimeout;
        const heroSearchInput = document.getElementById('heroSearch');
        const heroSuggestionsDiv = document.getElementById('heroSearchSuggestions');

        if (heroSearchInput) {
            heroSearchInput.addEventListener('input', function() {
                clearTimeout(heroSearchTimeout);
                const query = this.value.trim();

                if (query.length < 2) {
                    heroSuggestionsDiv.style.display = 'none';
                    heroSuggestionsDiv.innerHTML = '';
                    return;
                }

                heroSearchTimeout = setTimeout(() => {
                    fetch(`{{ route('gigs.search.suggestions') }}?q=${encodeURIComponent(query)}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.length === 0) {
                                heroSuggestionsDiv.style.display = 'none';
                                heroSuggestionsDiv.innerHTML = '';
                                return;
                            }

                            let html = '<div class="list-group list-group-flush">';
                            data.forEach(gig => {
                                html += `
                                    <a href="${gig.url}" class="list-group-item list-group-item-action">
                                        <div class="d-flex align-items-center">
                                            ${gig.image
                                                ? `<img src="${gig.image}" alt="${gig.title}" class="rounded me-3" style="width: 60px; height: 60px; object-fit: cover;">`
                                                : `<div class="rounded me-3 d-flex align-items-center justify-content-center text-white" style="width: 60px; height: 60px; background: linear-gradient(135deg, #00BCD4, #00ACC1); font-size: 12px; text-align: center; padding: 5px;">${gig.title.substring(0, 20)}</div>`
                                            }
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1 text-dark">${gig.title}</h6>
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

                            heroSuggestionsDiv.innerHTML = html;
                            heroSuggestionsDiv.style.display = 'block';
                        })
                        .catch(error => {
                            console.error('Search error:', error);
                            heroSuggestionsDiv.style.display = 'none';
                        });
                }, 300); // Debounce for 300ms
            });

            // Hide suggestions when clicking outside
            document.addEventListener('click', function(e) {
                if (!heroSearchInput.contains(e.target) && !heroSuggestionsDiv.contains(e.target)) {
                    heroSuggestionsDiv.style.display = 'none';
                }
            });

            // Show suggestions again when focusing on input
            heroSearchInput.addEventListener('focus', function() {
                if (this.value.trim().length >= 2 && heroSuggestionsDiv.innerHTML) {
                    heroSuggestionsDiv.style.display = 'block';
                }
            });
        }
    </script>
@endpush
