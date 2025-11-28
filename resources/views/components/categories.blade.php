<section class="categories-section py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-5">Browse Popular Categories</h2>

        <div class="row g-4">
            <div class="col-lg-3 col-md-4 col-sm-6">
                <a href="{{ route('gigs.index', ['category' => 1]) }}" class="text-decoration-none text-dark">
                    <div class="category-card card h-100 border-0 shadow-sm hover-lift">
                        <div class="card-body text-center">
                            <div class="category-icon mb-3" style="font-size: 3rem; color: #00BCD4;">
                                <i class="bi bi-palette"></i>
                            </div>
                            <h5 class="card-title">Graphics & Design</h5>
                            <p class="card-text text-muted small">Logo, Brand Identity, Illustrations</p>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-lg-3 col-md-4 col-sm-6">
                <a href="{{ route('gigs.index', ['category' => 2]) }}" class="text-decoration-none text-dark">
                    <div class="category-card card h-100 border-0 shadow-sm hover-lift">
                        <div class="card-body text-center">
                            <div class="category-icon mb-3" style="font-size: 3rem; color: #00BCD4;">
                                <i class="bi bi-code-slash"></i>
                            </div>
                            <h5 class="card-title">Programming & Tech</h5>
                            <p class="card-text text-muted small">Web Development, Mobile Apps, APIs</p>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-lg-3 col-md-4 col-sm-6">
                <a href="{{ route('gigs.index', ['category' => 3]) }}" class="text-decoration-none text-dark">
                    <div class="category-card card h-100 border-0 shadow-sm hover-lift">
                        <div class="card-body text-center">
                            <div class="category-icon mb-3" style="font-size: 3rem; color: #00BCD4;">
                                <i class="bi bi-pencil-square"></i>
                            </div>
                            <h5 class="card-title">Writing & Translation</h5>
                            <p class="card-text text-muted small">Content Writing, Copywriting, Translation</p>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-lg-3 col-md-4 col-sm-6">
                <a href="{{ route('gigs.index', ['category' => 4]) }}" class="text-decoration-none text-dark">
                    <div class="category-card card h-100 border-0 shadow-sm hover-lift">
                        <div class="card-body text-center">
                            <div class="category-icon mb-3" style="font-size: 3rem; color: #00BCD4;">
                                <i class="bi bi-camera-video"></i>
                            </div>
                            <h5 class="card-title">Video & Animation</h5>
                            <p class="card-text text-muted small">Video Editing, Motion Graphics, 3D</p>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-lg-3 col-md-4 col-sm-6">
                <a href="{{ route('gigs.index', ['category' => 5]) }}" class="text-decoration-none text-dark">
                    <div class="category-card card h-100 border-0 shadow-sm hover-lift">
                        <div class="card-body text-center">
                            <div class="category-icon mb-3" style="font-size: 3rem; color: #00BCD4;">
                                <i class="bi bi-megaphone"></i>
                            </div>
                            <h5 class="card-title">Digital Marketing</h5>
                            <p class="card-text text-muted small">SEO, Social Media, Email Marketing</p>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-lg-3 col-md-4 col-sm-6">
                <a href="{{ route('gigs.index', ['category' => 6]) }}" class="text-decoration-none text-dark">
                    <div class="category-card card h-100 border-0 shadow-sm hover-lift">
                        <div class="card-body text-center">
                            <div class="category-icon mb-3" style="font-size: 3rem; color: #00BCD4;">
                                <i class="bi bi-music-note-beamed"></i>
                            </div>
                            <h5 class="card-title">Music & Audio</h5>
                            <p class="card-text text-muted small">Voice Over, Mixing, Sound Design</p>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-lg-3 col-md-4 col-sm-6">
                <a href="{{ route('gigs.index', ['category' => 7]) }}" class="text-decoration-none text-dark">
                    <div class="category-card card h-100 border-0 shadow-sm hover-lift">
                        <div class="card-body text-center">
                            <div class="category-icon mb-3" style="font-size: 3rem; color: #00BCD4;">
                                <i class="bi bi-bar-chart"></i>
                            </div>
                            <h5 class="card-title">Business</h5>
                            <p class="card-text text-muted small">Consulting, Finance, Legal Services</p>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-lg-3 col-md-4 col-sm-6">
                <a href="{{ route('gigs.index', ['search' => 'lifestyle']) }}" class="text-decoration-none text-dark">
                    <div class="category-card card h-100 border-0 shadow-sm hover-lift">
                        <div class="card-body text-center">
                            <div class="category-icon mb-3" style="font-size: 3rem; color: #00BCD4;">
                                <i class="bi bi-gift"></i>
                            </div>
                            <h5 class="card-title">Lifestyle</h5>
                            <p class="card-text text-muted small">Gaming, Travel, Health & Fitness</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</section>
