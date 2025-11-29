@extends('layouts.admin')

@php
    $avatarColors = ['#00bcd4', '#0097a7', '#00838f', '#006064', '#0288d1', '#039be5'];
    $colorIndex = ord($seller->user->name[0]) % count($avatarColors);
    $avatarColor = $avatarColors[$colorIndex];
@endphp

@section('content')
    <div class="py-4">
        <div class="container">
            {{-- Header --}}
            <div class="mb-4">
                <a href="{{ route('admin.sellers.index') }}" class="text-decoration-none text-muted mb-2 d-inline-block">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
                </a>
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h2 class="fw-bold text-dark mb-1">Detail Pengajuan Seller</h2>
                        <p class="text-muted">Review lengkap pengajuan dari {{ $seller->user->name }}</p>
                    </div>
                    <span class="badge {{ $seller->status_badge_class }} fs-6 py-2 px-3">
                        {{ $seller->status_label }}
                    </span>
                </div>
            </div>

            <div class="row g-4">
                {{-- Main Content --}}
                <div class="col-lg-8">
                    {{-- User Profile Card --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white border-0 py-3">
                            <h5 class="mb-0 fw-bold">Informasi Mahasiswa</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-4">
                                @if ($seller->user->avatar)
                                    <img src="{{ $seller->user->avatar_url }}" alt="{{ $seller->user->name }}"
                                        class="rounded-circle me-4" style="width: 80px; height: 80px; object-fit: cover;">
                                @else
                                    <div class="profile-avatar me-4"
                                        style="background-color: {{ $avatarColor }}; width: 80px; height: 80px; font-size: 2rem;">
                                        {{ strtoupper(substr($seller->user->name, 0, 1)) }}
                                    </div>
                                @endif

                                <div>
                                    <h4 class="mb-1 fw-bold">{{ $seller->user->name }}</h4>
                                    <div class="text-muted">{{ $seller->user->email }}</div>
                                    <div class="text-muted small">
                                        Bergabung {{ $seller->user->created_at->format('d M Y') }}
                                    </div>
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="text-muted small fw-bold d-block">UNIVERSITAS</label>
                                    <span>{{ $seller->university }}</span>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-muted small fw-bold d-block">JURUSAN</label>
                                    <span>{{ $seller->major }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Business Info Card --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white border-0 py-3">
                            <h5 class="mb-0 fw-bold">Informasi Bisnis</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-4">
                                <label class="text-muted small fw-bold d-block mb-2">NAMA BISNIS</label>
                                <h5 class="text-primary">{{ $seller->business_name }}</h5>
                            </div>

                            <div class="mb-4">
                                <label class="text-muted small fw-bold d-block mb-2">DESKRIPSI LAYANAN</label>
                                <div class="p-3 bg-light rounded">
                                    {{ $seller->description }}
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="text-muted small fw-bold d-block mb-2">KEAHLIAN</label>
                                @if (is_array($seller->skills) && count($seller->skills) > 0)
                                    @foreach ($seller->skills as $skill)
                                        <span class="badge bg-primary me-1 mb-1">{{ $skill }}</span>
                                    @endforeach
                                @else
                                    <span class="text-muted">Tidak ada keahlian yang didaftarkan</span>
                                @endif
                            </div>

                            @if ($seller->portfolio_url)
                                <div>
                                    <label class="text-muted small fw-bold d-block mb-2">PORTFOLIO</label>
                                    <a href="{{ $seller->portfolio_url }}" target="_blank" class="btn btn-outline-primary">
                                        <i class="bi bi-link-45deg me-1"></i> Lihat Portfolio
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Gigs (if approved) --}}
                    @if ($seller->isApproved() && $seller->gigs->count() > 0)
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white border-0 py-3">
                                <h5 class="mb-0 fw-bold">Layanan yang Dibuat ({{ $seller->gigs->count() }})</h5>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table mb-0">
                                        <thead class="bg-light">
                                            <tr>
                                                <th class="ps-4">Judul</th>
                                                <th>Harga</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($seller->gigs as $gig)
                                                <tr>
                                                    <td class="ps-4">{{ $gig->title }}</td>
                                                    <td>Rp {{ number_format($gig->price, 0, ',', '.') }}</td>
                                                    <td>
                                                        <span
                                                            class="badge {{ $gig->is_active ? 'bg-success' : 'bg-secondary' }}">
                                                            {{ $gig->is_active ? 'Aktif' : 'Nonaktif' }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Sidebar --}}
                <div class="col-lg-4">
                    {{-- Status Card --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white border-0 py-3">
                            <h5 class="mb-0 fw-bold">Status Pengajuan</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="text-muted small fw-bold d-block">STATUS</label>
                                <span class="badge {{ $seller->status_badge_class }} fs-6">
                                    {{ $seller->status_label }}
                                </span>
                            </div>

                            <div class="mb-3">
                                <label class="text-muted small fw-bold d-block">TANGGAL DAFTAR</label>
                                <span>{{ $seller->created_at->format('d M Y H:i') }}</span>
                            </div>

                            @if ($seller->reviewed_at)
                                <div class="mb-3">
                                    <label class="text-muted small fw-bold d-block">TANGGAL REVIEW</label>
                                    <span>{{ $seller->reviewed_at->format('d M Y H:i') }}</span>
                                </div>

                                @if ($seller->reviewer)
                                    <div class="mb-3">
                                        <label class="text-muted small fw-bold d-block">DIREVIEW OLEH</label>
                                        <span>{{ $seller->reviewer->name }}</span>
                                    </div>
                                @endif
                            @endif

                            @if ($seller->isRejected() && $seller->rejection_reason)
                                <div class="p-3 bg-danger bg-opacity-10 rounded border border-danger">
                                    <label class="text-danger small fw-bold d-block mb-2">
                                        <i class="bi bi-x-circle me-1"></i> ALASAN PENOLAKAN
                                    </label>
                                    <span>{{ $seller->rejection_reason }}</span>
                                </div>
                            @endif

                            @if ($seller->status === 'suspended' && $seller->rejection_reason)
                                <div class="p-3 bg-warning bg-opacity-10 rounded border border-warning">
                                    <label class="text-warning small fw-bold d-block mb-2">
                                        <i class="bi bi-pause-circle me-1"></i> ALASAN PENANGGUHAN
                                    </label>
                                    <span>{{ $seller->rejection_reason }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Actions Card --}}
                    @if ($seller->isPending())
                        <div class="card border-0 shadow-sm" x-data="{ showRejectForm: false }">
                            <div class="card-header bg-white border-0 py-3">
                                <h5 class="mb-0 fw-bold">Tindakan</h5>
                            </div>
                            <div class="card-body">
                                <div x-show="!showRejectForm">
                                    <form action="{{ route('admin.sellers.approve', $seller->id) }}" method="POST"
                                        class="mb-2">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn btn-success w-100">
                                            <i class="bi bi-check-lg me-1"></i> Setujui Pengajuan
                                        </button>
                                    </form>
                                    <button type="button" class="btn btn-outline-danger w-100"
                                        @click="showRejectForm = true">
                                        <i class="bi bi-x-lg me-1"></i> Tolak Pengajuan
                                    </button>
                                </div>

                                <div x-show="showRejectForm" x-transition>
                                    <form action="{{ route('admin.sellers.reject', $seller->id) }}" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="form-label fw-medium">Alasan Penolakan <span
                                                    class="text-danger">*</span></label>
                                            <select name="rejection_reason_type" class="form-select" required
                                                x-model="rejectionType">
                                                <option value="">-- Pilih Alasan --</option>
                                                @foreach (\App\Models\Seller::REJECTION_REASONS as $key => $label)
                                                    <option value="{{ $key }}">{{ $label }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="mb-3" x-show="rejectionType === 'other'" x-transition
                                            x-data="{ rejectionType: '' }">
                                            <label class="form-label fw-medium">Alasan Lainnya</label>
                                            <textarea name="rejection_reason_custom" class="form-control" rows="3"></textarea>
                                        </div>

                                        <div class="d-flex gap-2">
                                            <button type="button" class="btn btn-outline-secondary flex-fill"
                                                @click="showRejectForm = false">
                                                Batal
                                            </button>
                                            <button type="submit" class="btn btn-danger flex-fill">
                                                Tolak
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @elseif($seller->isApproved())
                        <div class="card border-0 shadow-sm" x-data="{ showSuspendForm: false }">
                            <div class="card-header bg-white border-0 py-3">
                                <h5 class="mb-0 fw-bold">Tindakan</h5>
                            </div>
                            <div class="card-body">
                                <div x-show="!showSuspendForm">
                                    <button type="button" class="btn btn-warning w-100" @click="showSuspendForm = true">
                                        <i class="bi bi-pause-circle me-1"></i> Tangguhkan Seller
                                    </button>
                                </div>

                                <div x-show="showSuspendForm" x-transition>
                                    <form action="{{ route('admin.sellers.suspend', $seller->id) }}" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="form-label fw-medium">Alasan Penangguhan <span
                                                    class="text-danger">*</span></label>
                                            <textarea name="suspension_reason" class="form-control" rows="3" required></textarea>
                                        </div>

                                        <div class="d-flex gap-2">
                                            <button type="button" class="btn btn-outline-secondary flex-fill"
                                                @click="showSuspendForm = false">
                                                Batal
                                            </button>
                                            <button type="submit" class="btn btn-warning flex-fill">
                                                Tangguhkan
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @elseif($seller->status === 'suspended')
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white border-0 py-3">
                                <h5 class="mb-0 fw-bold">Tindakan</h5>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('admin.sellers.reactivate', $seller->id) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn btn-info w-100">
                                        <i class="bi bi-play-circle me-1"></i> Aktifkan Kembali
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif

                    {{-- Previous Rejections --}}
                    @if ($previousRejections->count() > 0)
                        <div class="card border-0 shadow-sm mt-4">
                            <div class="card-header bg-white border-0 py-3">
                                <h5 class="mb-0 fw-bold text-warning">
                                    <i class="bi bi-exclamation-triangle me-1"></i> Riwayat Penolakan
                                </h5>
                            </div>
                            <div class="card-body">
                                <p class="text-muted small mb-3">User ini pernah ditolak
                                    {{ $previousRejections->count() }}x sebelumnya:</p>
                                @foreach ($previousRejections as $rejected)
                                    <div class="p-2 bg-light rounded mb-2">
                                        <div class="small text-muted">{{ $rejected->rejected_at?->format('d M Y') }}</div>
                                        <div class="small">{{ $rejected->rejection_reason ?: 'Tidak ada alasan' }}</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
