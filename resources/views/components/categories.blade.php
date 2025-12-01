<section class="categories-section py-5 bg-light">
    <div class="container">
        <!-- Penjelasan singkat platform -->
        <div class="text-center mb-5">
            <h2 class="fw-semibold gradient-title">Jelajahi Kategori yang Paling Banyak Disukai</h2>
            <p class="text-muted">
                <span class="highlight">PeerSkill</span> adalah marketplace internal Universitas Bumigora yang mempermudah mahasiswa dan dosen menemukan jasa berbasis keterampilan. <br>
                Temukan <span class="highlight">kategori layanan</span> yang sesuai kebutuhanmu!
            </p>
        </div>
        <div class="row g-4">

            <!-- Desain grafis & multimedia -->
            <div class="col-lg-3 col-md-4 col-sm-6">
                <a href="{{ route('gigs.index', ['category' => 1]) }}" class="text-decoration-none text-dark">
                    <div class="category-card card h-100">
                        <div class="card-body text-center p-4">
                            <div class="category-icon mb-3">
                                <i class="bi bi-palette"></i>
                            </div>
                            <h6 class="card-title mb-1 fw-semibold">Desain Grafis & Multimedia</h6>
                            <p class="card-text text-muted small">Logo, Poster, Infografis, Editing Video</p>
                            <div class="hover-info mt-2 text-start text-muted small">
                                Dukung kreativitas, dokumentasikan ide, dan buat karya nyata di kampus.
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Teknologi & pemrograman -->
            <div class="col-lg-3 col-md-4 col-sm-6">
                <a href="{{ route('gigs.index', ['category' => 2]) }}" class="text-decoration-none text-dark">
                    <div class="category-card card h-100">
                        <div class="card-body text-center p-4">
                            <div class="category-icon mb-3">
                                <i class="bi bi-code-slash"></i>
                            </div>
                            <h6 class="card-title mb-1 fw-semibold">Teknologi & Pemrograman</h6>
                            <p class="card-text text-muted small">Website, Skrip Python, Aplikasi Kecil</p>
                            <div class="hover-info mt-2 text-start text-muted small">
                                Kembangkan kemampuan teknis dan buat proyek nyata yang bisa menjadi pengalaman kerja.
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Tutor akademik -->
            <div class="col-lg-3 col-md-4 col-sm-6">
                <a href="{{ route('gigs.index', ['category' => 3]) }}" class="text-decoration-none text-dark">
                    <div class="category-card card h-100">
                        <div class="card-body text-center p-4">
                            <div class="category-icon mb-3">
                                <i class="bi bi-journal-text"></i>
                            </div>
                            <h6 class="card-title mb-1 fw-semibold">Tutor Akademik</h6>
                            <p class="card-text text-muted small">Bimbingan Belajar, Proofreading, Terjemahan</p>
                            <div class="hover-info mt-2 text-start text-muted small">
                                Tingkatkan kemampuan komunikasi sambil membantu mahasiswa dan dosen lain.
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Fotografi & videografi -->
            <div class="col-lg-3 col-md-4 col-sm-6">
                <a href="{{ route('gigs.index', ['category' => 4]) }}" class="text-decoration-none text-dark">
                    <div class="category-card card h-100">
                        <div class="card-body text-center p-4">
                            <div class="category-icon mb-3">
                                <i class="bi bi-camera"></i>
                            </div>
                            <h6 class="card-title mb-1 fw-semibold">Fotografi & Videografi</h6>
                            <p class="card-text text-muted small">Dokumentasi Acara, Wisuda, Kegiatan UKM</p>
                            <div class="hover-info mt-2 text-start text-muted small">
                                Tangkap momen penting kampus dan tunjukkan kreativitasmu melalui dokumentasi nyata.
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Kreatif lainnya -->
            <div class="col-lg-3 col-md-4 col-sm-6">
                <a href="{{ route('gigs.index', ['category' => 5]) }}" class="text-decoration-none text-dark">
                    <div class="category-card card h-100">
                        <div class="card-body text-center p-4">
                            <div class="category-icon mb-3">
                                <i class="bi bi-music-note-beamed"></i>
                            </div>
                            <h6 class="card-title mb-1 fw-semibold">Kreatif Lainnya</h6>
                            <p class="card-text text-muted small">Voice Over, Musik, Konten Media Sosial</p>
                            <div class="hover-info mt-2 text-start text-muted small">
                                Kembangkan kreativitas di berbagai bidang kreatif dan tunjukkan karya unikmu.
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Pemasaran Digital -->
            <div class="col-lg-3 col-md-4 col-sm-6">
                <a href="{{ route('gigs.index', ['category' => 6]) }}" class="text-decoration-none text-dark">
                    <div class="category-card card h-100">
                        <div class="card-body text-center p-4">
                            <div class="category-icon mb-3">
                                <i class="bi bi-megaphone"></i>
                            </div>
                            <h6 class="card-title mb-1 fw-semibold">Pemasaran Digital</h6>
                            <p class="card-text text-muted small">Optimasi SEO, Media Sosial, Email Marketing</p>
                            <div class="hover-info mt-2 text-start text-muted small">
                                Tingkatkan visibilitas layananmu dan jangkau lebih banyak pengguna di kampus.
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Bisnis -->
            <div class="col-lg-3 col-md-4 col-sm-6">
                <a href="{{ route('gigs.index', ['category' => 7]) }}" class="text-decoration-none text-dark">
                    <div class="category-card card h-100">
                        <div class="card-body text-center p-4">
                            <div class="category-icon mb-3">
                                <i class="bi bi-bar-chart"></i>
                            </div>
                            <h6 class="card-title mb-1 fw-semibold">Bisnis</h6>
                            <p class="card-text text-muted small">Konsultasi, Keuangan, Layanan Hukum</p>
                            <div class="hover-info mt-2 text-start text-muted small">
                                Tingkatkan kemampuan analisis dan strategi melalui layanan bisnis internal kampus.
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Gaya Hidup -->
            <div class="col-lg-3 col-md-4 col-sm-6">
                <a href="{{ route('gigs.index', ['search' => 'lifestyle']) }}" class="text-decoration-none text-dark">
                    <div class="category-card card h-100">
                        <div class="card-body text-center p-4">
                            <div class="category-icon mb-3">
                                <i class="bi bi-gift"></i>
                            </div>
                            <h6 class="card-title mb-1 fw-semibold">Gaya Hidup</h6>
                            <p class="card-text text-muted small">Permainan, Perjalanan, Kesehatan & Kebugaran</p>
                            <div class="hover-info mt-2 text-start text-muted small">
                                Temukan layanan yang mendukung keseharian, hobi, dan gaya hidup sehat di kampus.
                            </div>
                        </div>
                    </div>
                </a>
            </div>

        </div>
    </div>
</section>


@push('styles')
<style>

/* Gradient untuk judul */
.gradient-title {
    background: linear-gradient(90deg, #2196f3, #0d47a1);
    background-clip: text;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

/* Highlight kata kunci */
.highlight {
    color: #1565c0; /* biru gelap sama seperti ikon */
    font-weight: 600;
}

/* Card styling tetap seperti awal */
.category-card {
    border-radius: 16px;
    background: #fff;
    border: none;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    padding-top: 12px;
    cursor: pointer;
    box-shadow: 0 2px 6px rgba(0,0,0,0.06);
}
.category-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 12px 24px rgba(0,0,0,0.14);
}
.category-card .card-body { padding: 1.5rem 1rem; text-align: center; }

/* Icon styling */
.category-card .category-icon i {
    font-size: 2.8rem;
    color: #1565c0; /* biru lebih gelap */
    transition: transform 0.25s ease, color 0.25s ease;
}

/* Hover icon */
.category-card:hover .category-icon i {
    transform: scale(1.2) rotate(-5deg);
    color: #0d47a1; /* lebih gelap saat hover */
}

.category-card .card-title { 
    font-size: 1.05rem; 
    font-weight: 600; 
    letter-spacing: 0.3px; 
    margin-bottom: 0.35rem; 
    transition: color 0.25s ease; 
}
.category-card:hover .card-title { color: #2196f3; }

.category-card .card-text { 
    font-size: 0.78rem; 
    margin-top: 4px; 
    opacity: 0.85; 
    transition: opacity 0.25s ease; 
}
.category-card:hover .card-text { opacity: 1; }

/* Badges */
.badge { font-size: 0.65rem; margin-right: 4px; padding: 0.35em 0.5em; }

/* Hover info */
.hover-info { display: none; }
.category-card:hover .hover-info { display: block; }

/* Responsive */
@media (max-width: 576px) {
    .category-card .category-icon i {
        font-size: 2.4rem;
        color: #1565c0;
        transition: transform 0.25s ease, color 0.25s ease;
    }
    .category-card .card-title { font-size: 1rem; }
    .category-card .card-text { font-size: 0.75rem; }
}
</style>
@endpush
