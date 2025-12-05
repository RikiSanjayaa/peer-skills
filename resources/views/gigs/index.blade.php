@extends('layouts.app')

@section('title', 'Telusuri Layanan - PeerSkill')

@section('content')
    <div class="container py-5">
        <div class="row g-4">

            <!-- Sidebar Filter -->
            <div class="col-lg-3">
                <div class="card shadow-sm filter-card">
                    <div class="card-header text-white">
                        <h5 class="mb-0"><i class="bi bi-funnel me-2"></i>Filter</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('gigs.index') }}" method="GET">
                            <div class="mb-3 position-relative">
                                <label for="search" class="form-label fw-bold">Cari</label>
                                <input type="text" class="form-control rounded-pill" name="search"
                                    placeholder="Cari layanan..." value="{{ request('search') }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Kategori</label>
                                <select class="form-select rounded-pill" name="category">
                                    <option value="">Semua Kategori</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ request('category') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <label class="form-label fw-bold">Harga</label>
                            <div class="row g-2">
                                <div class="col-6">
                                    <input type="number" class="form-control rounded" name="min_price" placeholder="Min"
                                        value="{{ request('min_price') }}">
                                </div>
                                <div class="col-6">
                                    <input type="number" class="form-control rounded" name="max_price" placeholder="Max"
                                        value="{{ request('max_price') }}">
                                </div>
                            </div>

                            <div class="form-check form-switch my-3">
                                <input class="form-check-input" type="checkbox" name="tutoring" value="1"
                                    {{ request('tutoring') == '1' ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold">Dengan Bimbingan</label>
                            </div>

                            <div class="d-grid gap-2">
                                <button class="btn btn-primary rounded-pill">Terapkan Filter</button>
                                <a href="{{ route('gigs.index') }}" class="btn btn-outline-secondary rounded-pill">Reset</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-lg-9">
                <h2 class="text-primary fw-bold mb-3">Telusuri Layanan</h2>

                @if ($gigs->count() > 0)
                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                        @foreach ($gigs as $gig)
                            <div class="col">
                                <x-gig-card :gig="$gig" />
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-4">{{ $gigs->links() }}</div>
                @else
                    <div class="text-center py-5">
                        <h6 class="fw-semibold text-muted">Tidak ada layanan ditemukan.</h6>
                    </div>
                @endif
            </div>

        </div>
    </div>
@endsection
