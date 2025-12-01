<section class="hero-section">
    <div class="container">
        <div class="row align-items-center min-vh-70">
            <div class="col-lg-8 mx-auto text-center">
                <!-- Hero Title -->
                <h1 class="hero-title mb-4">
                    Selamat Datang di <span class="highlight">PeerSkill</span> – Marketplace Jasa Internal Universitas Bumigora
                </h1>

                <!-- Subtitle -->
                <p class="hero-subtitle mb-3">
                    Menghubungkan mahasiswa dan dosen melalui kolaborasi, kreativitas, dan technopreneurship
                </p>

                <!-- Highlight kecil manfaat -->
                <small class="text-white d-block mb-3">
                    Bantu mahasiswa membangun portofolio, pengalaman nyata, dan penghasilan tambahan
                </small>

                <!-- Tagline / slogan -->
                <small class="text-white d-block mb-4" style="font-weight:500;">
                    Cari jasa? Temukan <span style="color: #FFFF00;">skill</span> di <span style="color: #000000;">PeerSkill</span>!
                </small>

                <!-- Search Box -->
                <div class="search-box mx-auto position-relative">
                    <form action="{{ route('gigs.index') }}" method="GET">
                        <div class="input-group input-group-lg shadow-lg hero-search-group">
                            <input type="text" name="search" id="heroSearch" class="form-control"
                                placeholder="Coba Desain Logo, WordPress, Edit Video, atau SEO..." 
                                aria-label="Search services" value="{{ request('search') }}" autocomplete="off">
                            <button class="btn btn-dark px-4" type="submit">
                                <i class="bi bi-search me-2"></i>Cari
                            </button>
                        </div>
                        <div id="heroSearchSuggestions" class="search-suggestions"></div>
                    </form>
                </div>

                <!-- Popular Badges -->
                <div class="mt-4 hero-popular">
                    <small class="text-white-50">Populer:</small>
                    <a href="{{ route('gigs.index', ['search' => 'logo design']) }}" class="badge badge-popular">Desain Logo</a>
                    <a href="{{ route('gigs.index', ['search' => 'wordpress']) }}" class="badge badge-popular">WordPress</a>
                    <a href="{{ route('gigs.index', ['search' => 'video editing']) }}" class="badge badge-popular">Edit Video</a>
                    <a href="{{ route('gigs.index', ['search' => 'seo']) }}" class="badge badge-popular">SEO</a>
                </div>
            </div>
        </div>
    </div>
</section>

@push('styles')
<style>
/* Hero Section */
.hero-section {
    background: linear-gradient(135deg, #00BCD4 0%, #00ACC1 100%);
    padding: 5rem 0 6rem 0;
    position: relative;
    text-align: center;
    color: white;
    overflow: hidden;
}

/* Hero Title & Subtitle */
.hero-title {
    font-size: 3rem;
    font-weight: 700;
    line-height: 1.2;
}

.hero-title .highlight {
    color: #1a1a1a;
}

.hero-subtitle {
    font-size: 1.25rem;
    font-weight: 400;
    line-height: 1.5;
    opacity: 0.9;
}

/* Search Box */
.search-box {
    max-width: 600px;
    margin: 0 auto;
    position: relative;
}

.hero-search-group .form-control {
    border-radius: 0.5rem 0 0 0.5rem;
    padding: 0.75rem 1rem;
    box-shadow: 0 4px 10px rgba(0,0,0,0.08);
    transition: box-shadow 0.3s ease, transform 0.3s ease;
}

.hero-search-group .form-control:focus {
    box-shadow: 0 6px 18px rgba(0,0,0,0.12);
    transform: translateY(-2px);
}

/* Search Button */
.hero-search-group .btn {
    border-radius: 0 0.5rem 0.5rem 0;
    transition: transform 0.3s ease, background-color 0.3s ease;
}

.hero-search-group .btn:hover {
    transform: scale(1.05);
    background-color: #111;
}

/* Search Suggestions Dropdown */
.search-suggestions {
    display: none;
    position: absolute;
    width: 100%;
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 0.5rem;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.12);
    margin-top: 0.25rem;
    z-index: 1000;
    max-height: 400px;
    overflow-y: auto;
}

/* Popular Badges */
.hero-popular {
    margin-top: 2rem;
}

.hero-popular .badge-popular {
    background-color: #fff;
    color: #1a1a1a;
    text-decoration: none;
    margin-left: 0.5rem;
    margin-bottom: 0.25rem;
    display: inline-block;
    padding: 0.4rem 0.8rem;
    border-radius: 1rem;
    font-size: 0.8rem;
    font-weight: 500;
    transition: transform 0.2s ease, background-color 0.2s ease, color 0.2s ease;
}

.hero-popular .badge-popular:hover {
    transform: scale(1.08);
    background-color: rgba(255,255,255,0.9);
    color: var(--peerskill-primary-dark);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .hero-title {
        font-size: 2.2rem;
    }
    .hero-subtitle {
        font-size: 1.1rem;
    }
    .hero-search-group .form-control {
        padding: 0.65rem 0.8rem;
    }
    .hero-search-group .btn {
        padding: 0.65rem 1rem;
    }
    ul.text-white-75 li {
        font-size: 0.85rem;
    }
}
</style>
@endpush
