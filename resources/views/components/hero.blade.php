<section class="hero-section py-5" style="background: linear-gradient(135deg, #00BCD4 0%, #00ACC1 100%);">
    <div class="container">
        <div class="row align-items-center min-vh-50">
            <div class="col-lg-8 mx-auto text-center text-white">
                <h1 class="display-3 fw-bold mb-4">
                    Find the perfect <span style="color: #1a1a1a;">freelance</span> services for your business
                </h1>
                <p class="lead mb-4">
                    Connect with skilled freelancers ready to bring your projects to life
                </p>

                <div class="search-box mx-auto position-relative" style="max-width: 600px;">
                    <form action="{{ route('gigs.index') }}" method="GET">
                        <div class="input-group input-group-lg shadow-lg">
                            <input type="text" name="search" id="heroSearch" class="form-control"
                                placeholder="Try 'logo design' or 'web development'..." aria-label="Search services"
                                value="{{ request('search') }}" autocomplete="off">
                            <button class="btn btn-dark px-4" type="submit">
                                <i class="bi bi-search me-2"></i>Search
                            </button>
                        </div>
                        <div id="heroSearchSuggestions"
                            class="position-absolute w-100 bg-white border rounded shadow-lg mt-1"
                            style="display: none; z-index: 1000; max-height: 400px; overflow-y: auto;"></div>
                    </form>
                </div>

                <div class="mt-4">
                    <small class="text-white-50">Popular:</small>
                    <a href="{{ route('gigs.index', ['search' => 'logo design']) }}"
                        class="badge bg-white text-dark text-decoration-none ms-2">Logo Design</a>
                    <a href="{{ route('gigs.index', ['search' => 'wordpress']) }}"
                        class="badge bg-white text-dark text-decoration-none ms-2">WordPress</a>
                    <a href="{{ route('gigs.index', ['search' => 'video editing']) }}"
                        class="badge bg-white text-dark text-decoration-none ms-2">Video Editing</a>
                    <a href="{{ route('gigs.index', ['search' => 'seo']) }}"
                        class="badge bg-white text-dark text-decoration-none ms-2">SEO</a>
                </div>
            </div>
        </div>
    </div>
</section>
