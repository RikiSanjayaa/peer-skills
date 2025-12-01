@extends('layouts.app')

@section('title', 'PeerSkills - Platform Freelance Mahasiswa Terdepan')

@push('styles')
{{-- Import Font & Library Animasi --}}
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<style>
    /* --- 1. GLOBAL VARIABLES & RESET --- */
    :root {
        /* WARNA UTAMA (CYAN - Sesuai Identitas PeerSkills) */
        --primary: #00BCD4;       /* Cyan Cerah */
        --primary-dark: #0097A7;  /* Cyan Gelap untuk Hover */
        
        /* WARNA SEKUNDER (ROYAL BLUE - Untuk Gradasi Keren) */
        --secondary: #2962FF;     
        
        /* ACCENT (PINK - Untuk Pemanis Kecil) */
        --accent: #F50057;     
        
        --dark: #0F172A;       /* Slate 900 */
        --light: #F8FAFC;      /* Slate 50 */
    }

    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background-color: var(--light);
        color: #334155;
        overflow-x: hidden;
    }

    /* --- 2. UTILITY CLASSES --- */
    /* Gradasi Teks Keren (Cyan ke Biru) */
    .text-gradient {
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    
    .hover-scale { transition: transform 0.2s ease; }
    .hover-scale:active { transform: scale(0.95); }

    .hover-float { transition: transform 0.3s ease; }
    .hover-float:hover { transform: translateY(-10px); }

    .reveal {
        opacity: 0;
        transform: translateY(30px);
        transition: all 0.8s cubic-bezier(0.5, 0, 0, 1);
    }
    .reveal.active {
        opacity: 1;
        transform: translateY(0);
    }

    /* --- 3. HERO SECTION --- */
    .hero-section {
        position: relative;
        padding: 8rem 0 6rem;
        /* Latar belakang gradasi tipis cyan-biru */
        background: radial-gradient(circle at 0% 0%, rgba(0, 188, 212, 0.1) 0%, transparent 50%), 
                    radial-gradient(circle at 100% 100%, rgba(41, 98, 255, 0.1) 0%, transparent 50%);
        overflow: hidden;
    }

    .animated-blob {
        position: absolute;
        filter: blur(80px);
        z-index: 0;
        opacity: 0.4;
        animation: blob-bounce 10s infinite ease-in-out;
    }
    /* Blob warna Cyan & Biru */
    .blob-1 { top: -10%; left: -10%; width: 500px; height: 500px; background: #80DEEA; animation-delay: 0s; }
    .blob-2 { bottom: -10%; right: -10%; width: 400px; height: 400px; background: #82B1FF; animation-delay: 5s; }

    @keyframes blob-bounce {
        0%, 100% { transform: translate(0, 0) scale(1); }
        50% { transform: translate(30px, -30px) scale(1.1); }
    }

    /* Search Bar Glassmorphism */
    .search-glass {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.8);
        box-shadow: 0 8px 32px rgba(0, 188, 212, 0.1); /* Shadow Cyan Tipis */
        border-radius: 50px;
        padding: 8px;
        transition: all 0.3s ease;
    }
    .search-glass:focus-within {
        box-shadow: 0 15px 40px rgba(0, 188, 212, 0.2);
        border-color: var(--primary);
        transform: scale(1.01);
    }
    .search-input { border: none; background: transparent; font-weight: 600; padding-left: 20px; outline: none !important; box-shadow: none !important; }

    /* Custom Button Gradient */
    .btn-gradient {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        color: white;
        border: none;
    }
    .btn-gradient:hover {
        background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 100%);
        color: white;
    }

    /* --- 4. INFINITE MARQUEE --- */
    .marquee-container { overflow: hidden; white-space: nowrap; position: relative; }
    .marquee-content { display: inline-block; animation: marquee 25s linear infinite; }
    @keyframes marquee { 0% { transform: translateX(0); } 100% { transform: translateX(-50%); } }
    
    .skill-tag {
        display: inline-block;
        padding: 10px 25px;
        margin: 0 10px;
        background: white;
        border-radius: 30px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        font-weight: 700;
        color: var(--dark);
        border: 1px solid #E0F7FA; /* Border Cyan Tipis */
    }

    /* --- 5. GIG CARDS --- */
    .gig-card {
        background: white;
        border-radius: 24px;
        border: 1px solid #F1F5F9;
        overflow: hidden;
        height: 100%;
        transition: all 0.4s ease;
        display: flex;
        flex-direction: column;
    }
    .gig-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 30px -10px rgba(0, 188, 212, 0.15); /* Shadow Cyan */
        border-color: var(--primary);
    }
    
    .gig-image-wrapper {
        height: 220px;
        position: relative;
        overflow: hidden;
        background: #E0F7FA;
    }
    .gig-image-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s ease;
    }
    .gig-card:hover .gig-image-wrapper img { transform: scale(1.1); }

    .price-badge {
        background: var(--dark);
        color: white;
        padding: 6px 16px;
        border-radius: 20px;
        font-weight: 700;
        font-size: 0.9rem;
    }

    /* --- 6. FLOATING 3D ELEMENTS --- */
    .float-card {
        animation: float 6s ease-in-out infinite;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(5px);
        border-radius: 16px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        z-index: 2;
    }
    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-20px); }
    }
</style>
@endpush

@section('content')

{{-- 1. HERO SECTION --}}
<div class="hero-section">
    <div class="animated-blob blob-1"></div>
    <div class="animated-blob blob-2"></div>

    <div class="container position-relative z-1">
        <div class="row align-items-center">
            {{-- Kiri: Teks Utama --}}
            <div class="col-lg-6 mb-5 mb-lg-0">
                <div class="animate__animated animate__fadeInDown">
                    <span class="badge bg-white text-primary px-3 py-2 rounded-pill fw-bold border shadow-sm mb-3">
                        🚀 Marketplace Mahasiswa No. #1
                    </span>
                </div>
                
                <h1 class="display-3 fw-bolder text-dark mb-4 lh-sm animate__animated animate__fadeInUp">
                    Jasa Profesional, <br>
                    <span class="text-gradient">Harga Teman Kampus.</span>
                </h1>
                
                <p class="lead text-muted mb-5 animate__animated animate__fadeInUp animate__delay-1s" style="max-width: 90%;">
                    Platform aman untuk mahasiswa mencari cuan atau bantuan tugas. Terverifikasi KTM, bebas penipuan, dan diskusi langsung.
                </p>

                {{-- FORM PENCARIAN --}}
                <div class="position-relative animate__animated animate__fadeInUp animate__delay-1s" style="max-width: 550px; z-index: 5;">
                    <form action="{{ route('gigs.index') }}" method="GET" class="w-100">
                        <div class="search-glass d-flex align-items-center">
                            <i class="bi bi-search text-muted fs-5 ms-3"></i>
                            <input type="text" id="heroSearch" name="query" class="form-control search-input w-100" 
                                   placeholder="Coba cari: 'Desain Logo', 'Joki Coding'..." autocomplete="off">
                            <button type="submit" class="btn btn-gradient rounded-pill px-4 py-2 fw-bold shadow-lg ms-2 hover-scale">
                                Cari
                            </button>
                        </div>
                    </form>
                    {{-- Dropdown Suggestion --}}
                    <div id="heroSearchSuggestions" class="position-absolute w-100 bg-white shadow-xl rounded-4 mt-2 overflow-hidden border-0" style="display: none;"></div>
                </div>

                {{-- Social Proof --}}
                <div class="mt-5 d-flex align-items-center gap-3 animate__animated animate__fadeInUp animate__delay-2s">
                    <div class="d-flex">
                        @for($i=0; $i<4; $i++)
                        <div class="rounded-circle border border-2 border-white bg-secondary" style="width:45px; height:45px; margin-left: -15px; background-image: url('https://i.pravatar.cc/150?img={{ $i+10 }}'); background-size: cover;"></div>
                        @endfor
                        <div class="rounded-circle border border-2 border-white bg-dark text-white d-flex align-items-center justify-content-center fw-bold" style="width:45px; height:45px; margin-left: -15px; font-size: 12px;">1k+</div>
                    </div>
                    <div>
                        <p class="mb-0 fw-bold text-dark lh-1">1,200+ Mahasiswa</p>
                        <small class="text-muted">Telah bergabung bulan ini</small>
                    </div>
                </div>
            </div>

            {{-- Kanan: Ilustrasi Floating --}}
            <div class="col-lg-6 position-relative text-center d-none d-lg-block">
                <div class="position-relative" style="height: 600px;">
                    <img src="{{ asset('images/foto.png') }}"  
                         class="img-fluid position-absolute top-50 start-50 translate-middle z-1 animate__animated animate__zoomIn" 
                         style="width: 100%; max-width: 650px;">

                    {{-- Floating Card Verified --}}
                    <div class="float-card position-absolute p-3 text-start animate__animated animate__fadeInRight animate__delay-1s" style="top: 15%; right: 5%; animation-delay: 0s;">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-success bg-opacity-10 text-success p-2 rounded-circle">
                                <i class="bi bi-shield-check fs-4"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0">Verified</h6>
                                <small class="text-muted">Mahasiswa Aktif</small>
                            </div>
                        </div>
                    </div>

                    {{-- Floating Card Rating --}}
                    <div class="float-card position-absolute p-3 text-start animate__animated animate__fadeInLeft animate__delay-2s" style="bottom: 20%; left: 0%; animation-delay: 2s;">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-warning bg-opacity-10 text-warning p-2 rounded-circle">
                                <i class="bi bi-star-fill fs-4"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0">4.9/5.0</h6>
                                <small class="text-muted">Rating Sempurna</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- 2. INFINITE MARQUEE --}}
<div class="py-4 bg-white border-bottom border-top">
    <div class="marquee-container">
        <div class="marquee-content">
            <span class="skill-tag">🔥 Website Development</span> 
            <span class="skill-tag">🎨 UI/UX Design</span> 
            <span class="skill-tag">📝 Content Writing</span>
            <span class="skill-tag">📊 Data Analysis</span> 
            <span class="skill-tag">🎥 Video Editing</span> 
            <span class="skill-tag">🐍 Python Programming</span>
            <span class="skill-tag">📱 Mobile App Flutter</span>
            <span class="skill-tag">🎓 Joki Tugas Kuliah</span>
            <span class="skill-tag">🔥 Website Development</span> 
            <span class="skill-tag">🎨 UI/UX Design</span> 
            <span class="skill-tag">📝 Content Writing</span>
            <span class="skill-tag">📊 Data Analysis</span>
        </div>
    </div>
</div>

{{-- 3. STATS STRIP --}}
<div class="py-5" style="background-color: #fff;">
    <div class="container">
        <div class="row text-center justify-content-center g-4 reveal">
            <div class="col-4 col-md-3 border-end">
                <h2 class="fw-bold text-dark mb-0">1.5k+</h2>
                <small class="text-muted fw-bold">USER AKTIF</small>
            </div>
            <div class="col-4 col-md-3 border-end">
                <h2 class="fw-bold text-dark mb-0">500+</h2>
                <small class="text-muted fw-bold">GIG SELESAI</small>
            </div>
            <div class="col-4 col-md-3">
                <h2 class="fw-bold text-dark mb-0">98%</h2>
                <small class="text-muted fw-bold">KEPUASAN</small>
            </div>
        </div>
    </div>
</div>

{{-- 4. FEATURED GIGS --}}
<div class="py-5" style="background-color: #f8fafc;">
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-end mb-5 reveal">
            <div>
                <span class="text-primary fw-bold text-uppercase small ls-1">PILIHAN EDITOR</span>
                <h2 class="fw-bold mb-0 text-dark display-6">Gigs Paling Laris 🔥</h2>
            </div>
            <a href="{{ route('gigs.index') }}" class="btn btn-outline-dark rounded-pill px-4 fw-bold hover-float">Lihat Semua</a>
        </div>

        <div class="row g-4">
            {{-- Loop Data dari Controller --}}
            @forelse($featuredGigs as $gig)
            <div class="col-md-6 col-lg-4 reveal">
                <a href="{{ route('gigs.show', $gig) }}" class="text-decoration-none h-100 d-block">
                    <div class="gig-card position-relative">
                        {{-- Badge Kategori --}}
                        <div class="position-absolute top-0 start-0 m-3 z-2">
                            <span class="badge bg-white text-dark shadow-sm px-3 py-2 rounded-pill fw-bold">
                                {{ $gig->category->name ?? 'Umum' }}
                            </span>
                        </div>

                        <div class="gig-image-wrapper">
                            @php 
                                // Ambil gambar pertama dari array
                                $image = !empty($gig->images) && is_array($gig->images) ? $gig->images[0] : null; 
                            @endphp
                            
                            @if($image)
                                <img src="{{ Storage::url($image) }}" alt="{{ $gig->title }}">
                            @else
                                <div class="w-100 h-100 d-flex align-items-center justify-content-center text-white" 
                                     style="background: linear-gradient(45deg, #00BCD4, #2962FF);">
                                    <i class="bi bi-laptop fs-1 opacity-50"></i>
                                </div>
                            @endif
                        </div>
                        
                        <div class="p-4 d-flex flex-column flex-grow-1">
                            {{-- Info Seller --}}
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2 fw-bold shadow-sm" 
                                     style="width: 35px; height: 35px; font-size: 14px;">
                                    {{ substr($gig->seller->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <span class="text-dark fw-bold d-block lh-1" style="font-size: 0.95rem;">{{ $gig->seller->user->name }}</span>
                                    <small class="text-muted" style="font-size: 0.75rem;">
                                        <i class="bi bi-patch-check-fill text-primary"></i> Mahasiswa
                                    </small>
                                </div>
                            </div>

                            <h5 class="fw-bold text-dark mb-3 lh-sm flex-grow-1" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                {{ $gig->title }}
                            </h5>

                            <div class="mt-auto pt-3 border-top d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center text-warning small gap-1">
                                    <i class="bi bi-star-fill"></i>
                                    <span class="fw-bold text-dark">5.0</span>
                                </div>
                                <span class="price-badge">
                                    Mulai Rp {{ number_format($gig->min_price, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <div class="alert alert-light border shadow-sm d-inline-block px-5 py-3 rounded-4">
                    <i class="bi bi-emoji-frown me-2"></i> Belum ada gig yang tersedia.
                </div>
            </div>
            @endforelse
        </div>
    </div>
</div>

{{-- 5. FEATURES / WHY US --}}
<div class="py-5 bg-white">
    <div class="container py-5">
        <div class="row align-items-center g-5">
            <div class="col-lg-5 reveal">
                <span class="text-primary fw-bold text-uppercase small ls-1">KENAPA KAMI?</span>
                <h2 class="display-5 fw-bold mb-4 mt-2">Solusi Cerdas Kebutuhan Kampus.</h2>
                <p class="text-muted lead mb-4">
                    Kami menyediakan ekosistem yang aman (Escrow) bagi mahasiswa untuk bertransaksi skill. Fokus kuliah sambil cari cuan.
                </p>
                
                <ul class="list-unstyled d-flex flex-column gap-3">
                    <li class="d-flex align-items-center gap-3 p-3 bg-light rounded-3">
                        <i class="bi bi-shield-check fs-3 text-primary"></i>
                        <div>
                            <h6 class="fw-bold mb-0">Pembayaran Aman</h6>
                            <small class="text-muted">Uang ditahan sistem sampai pekerjaan selesai.</small>
                        </div>
                    </li>
                    <li class="d-flex align-items-center gap-3 p-3 bg-light rounded-3">
                        <i class="bi bi-mortarboard fs-3 text-info"></i>
                        <div>
                            <h6 class="fw-bold mb-0">Eksklusif Mahasiswa</h6>
                            <small class="text-muted">Semua pengguna terverifikasi status mahasiswa.</small>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="col-lg-7 reveal">
                <div class="row g-4">
                    <div class="col-md-6 mt-md-5">
                        <div class="p-4 rounded-4 bg-dark text-white shadow-lg hover-float h-100 d-flex flex-column justify-content-center">
                            <i class="bi bi-lightning-charge-fill display-3 text-warning mb-3"></i>
                            <h4>Kerja Cepat</h4>
                            <p class="text-white-50">Deadline mepet? Temukan freelancer yang siap kerja kilat (Express).</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex flex-column gap-4">
                            <div class="p-4 rounded-4 bg-white border shadow-sm hover-float">
                                <i class="bi bi-wallet2 fs-1 text-primary mb-2"></i>
                                <h5 class="fw-bold">Harga Mahasiswa</h5>
                                <p class="text-muted small mb-0">Standar harga teman, kualitas profesional.</p>
                            </div>
                            <div class="p-4 rounded-4 bg-white border shadow-sm hover-float">
                                <i class="bi bi-chat-dots fs-1 text-info mb-2"></i>
                                <h5 class="fw-bold">Chat Real-time</h5>
                                <p class="text-muted small mb-0">Diskusi langsung tanpa ribet.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- 6. TESTIMONIALS (Baru) --}}
<div class="py-5" style="background-color: #f8fafc;">
    <div class="container py-5 reveal">
        <div class="text-center mb-5">
            <h2 class="fw-bold display-6">Kata Mereka</h2>
            <p class="text-muted">Apa kata mahasiswa yang sudah menggunakan PeerSkills?</p>
        </div>
        <div class="row g-4 justify-content-center">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm p-4 h-100 rounded-4">
                    <div class="text-warning mb-3"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i></div>
                    <p class="text-muted fst-italic">"Sangat terbantu! Tugas coding selesai tepat waktu dan hasilnya rapi banget. Seller juga ramah."</p>
                    <div class="d-flex align-items-center mt-3">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold me-2" style="width: 40px; height: 40px;">A</div>
                        <div><h6 class="mb-0 fw-bold">Andi P.</h6><small class="text-muted">Teknik Informatika</small></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm p-4 h-100 rounded-4">
                    <div class="text-warning mb-3"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i></div>
                    <p class="text-muted fst-italic">"Lumayan banget buat nambah uang jajan. Sistemnya aman, jadi nggak takut ditipu buyer."</p>
                    <div class="d-flex align-items-center mt-3">
                        <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold me-2" style="width: 40px; height: 40px;">S</div>
                        <div><h6 class="mb-0 fw-bold">Siti M.</h6><small class="text-muted">Desain Komunikasi Visual</small></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- 7. CTA (Call to Action) --}}
<div class="container pb-5 mb-5 reveal">
    <div class="position-relative overflow-hidden rounded-5 p-5 text-center bg-primary text-white shadow-lg" 
         style="background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);">
        
        <div class="position-absolute top-0 end-0 p-5 opacity-25">
            <i class="bi bi-rocket-takeoff-fill display-1"></i>
        </div>
        <div class="position-absolute bottom-0 start-0 translate-middle rounded-circle bg-white opacity-10" style="width: 300px; height: 300px;"></div>

        <div class="position-relative z-1 py-4">
            <h2 class="display-4 fw-bold mb-3">Siap Tambah Uang Jajan? 💸</h2>
            <p class="lead mb-4 text-white-75 mx-auto" style="max-width: 600px;">
                Jangan biarkan skill kamu menganggur. Upload jasa kamu sekarang, dapatkan klien pertamamu besok.
            </p>
            <div class="d-flex justify-content-center gap-3">
                <a href="{{ route('seller.register') }}" class="btn btn-white bg-white text-primary btn-lg rounded-pill px-5 fw-bold shadow hover-float">
                    Mulai Jualan
                </a>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // --- 1. SEARCH SCRIPT ---
    const heroSearchInput = document.getElementById('heroSearch');
    const heroSuggestionsDiv = document.getElementById('heroSearchSuggestions');
    let heroSearchTimeout;

    if (heroSearchInput && heroSuggestionsDiv) {
        heroSearchInput.addEventListener('input', function() {
            clearTimeout(heroSearchTimeout);
            const query = this.value.trim();
            if (query.length < 2) { heroSuggestionsDiv.style.display = 'none'; return; }

            heroSearchTimeout = setTimeout(() => {
                fetch(`{{ route('gigs.search.suggestions') }}?q=${encodeURIComponent(query)}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.length === 0) { heroSuggestionsDiv.style.display = 'none'; return; }
                        let html = '<div class="list-group list-group-flush">';
                        data.forEach(gig => {
                            const img = gig.image ? `<img src="${gig.image}" class="rounded me-3" style="width:40px;height:40px;object-fit:cover;">` : '';
                            html += `<a href="${gig.url}" class="list-group-item list-group-item-action border-0 py-3 d-flex align-items-center">
                                        ${img} 
                                        <div><h6 class="mb-0 fw-bold text-dark">${gig.title}</h6><small class="text-muted">${gig.category}</small></div>
                                     </a>`;
                        });
                        html += '</div>';
                        heroSuggestionsDiv.innerHTML = html; heroSuggestionsDiv.style.display = 'block';
                    });
            }, 300);
        });
        document.addEventListener('click', e => { if (!heroSearchInput.contains(e.target)) heroSuggestionsDiv.style.display = 'none'; });
    }

    // --- 2. SCROLL REVEAL ANIMATION ---
    // Efek elemen muncul saat di-scroll
    window.addEventListener('scroll', reveal);
    function reveal() {
        var reveals = document.querySelectorAll('.reveal');
        for (var i = 0; i < reveals.length; i++) {
            var windowHeight = window.innerHeight;
            var elementTop = reveals[i].getBoundingClientRect().top;
            if (elementTop < windowHeight - 100) reveals[i].classList.add('active');
        }
    }
    reveal();
</script>
@endpush