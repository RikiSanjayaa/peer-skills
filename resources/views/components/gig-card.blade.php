@props(['gig', 'showTutoringBadge' => true])

<a href="{{ route('gigs.show', $gig) }}" class="text-decoration-none h-100 d-block">
    <div class="gig-card position-relative">
        {{-- Badge Kategori --}}
        <div class="position-absolute top-0 start-0 m-3 z-2">
            <span class="badge bg-white text-dark shadow-sm px-3 py-2 rounded-pill fw-bold">
                {{ $gig->category->name ?? 'Umum' }}
            </span>
            @if ($showTutoringBadge && $gig->allows_tutoring)
                <span class="badge bg-success text-white shadow-sm px-3 py-2 rounded-pill fw-bold">
                    <i class="bi bi-mortarboard-fill me-1"></i>Bimbingan
                </span>
            @endif
        </div>

        <div class="gig-image-wrapper">
            @php
                $image = !empty($gig->images) && is_array($gig->images) ? $gig->images[0] : null;
            @endphp

            @if ($image)
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
                @if ($gig->seller->user->avatar)
                    <img src="{{ $gig->seller->user->avatar_url }}" class="rounded-circle me-2 shadow-sm"
                        style="width: 35px; height: 35px; object-fit: cover;">
                @else
                    <div
                        class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2 fw-bold shadow-sm seller-avatar">
                        {{ strtoupper(substr($gig->seller->user->name, 0, 1)) }}
                    </div>
                @endif
                <div>
                    <span class="text-dark fw-bold d-block lh-1" style="font-size: 0.95rem;">
                        {{ $gig->seller->user->name }}
                    </span>
                    <small class="text-muted" style="font-size: 0.75rem;">
                        <i class="bi bi-patch-check-fill text-primary"></i> Mahasiswa
                    </small>
                </div>
            </div>

            <h5 class="fw-bold text-dark mb-3 lh-sm flex-grow-1 gig-title-clamp">
                {{ $gig->title }}
            </h5>

            <div class="mt-auto pt-3 border-top d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center text-warning small gap-1">
                    <i class="bi bi-star-fill"></i>
                    {{-- TODO: tambah rating beneran --}}
                    <span class="fw-bold text-dark">5.0</span>
                </div>
                <span class="price-badge">
                    Mulai Rp {{ number_format($gig->min_price, 0, ',', '.') }}
                </span>
            </div>
        </div>
    </div>
</a>
