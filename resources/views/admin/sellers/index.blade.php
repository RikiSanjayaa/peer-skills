@extends('layouts.app')

@php
    $avatarColors = ['#00bcd4', '#0097a7', '#00838f', '#006064', '#0288d1', '#039be5'];
@endphp

@section('content')
    <div class="py-5 bg-light" x-data="sellerManagement()">
        <div class="container">

            {{-- Header --}}
            <div class="mb-4">
                <h2 class="fw-bold text-dark">Manajemen Seller</h2>
                <p class="text-muted">Tinjau dan kelola pendaftaran seller mahasiswa</p>
            </div>

            {{-- Status Tabs --}}
            <div class="mb-4">
                <ul class="nav nav-pills gap-2">
                    <li class="nav-item">
                        <a href="{{ route('admin.sellers.index', ['status' => 'pending']) }}"
                            class="nav-link {{ $status === 'pending' ? 'active' : 'text-dark' }}">
                            <i class="bi bi-clock me-1"></i> Menunggu
                            @if ($counts['pending'] > 0)
                                <span class="badge bg-warning text-dark ms-1">{{ $counts['pending'] }}</span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.sellers.index', ['status' => 'approved']) }}"
                            class="nav-link {{ $status === 'approved' ? 'active' : 'text-dark' }}">
                            <i class="bi bi-check-circle me-1"></i> Disetujui
                            <span class="badge bg-success ms-1">{{ $counts['approved'] }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.sellers.index', ['status' => 'rejected']) }}"
                            class="nav-link {{ $status === 'rejected' ? 'active' : 'text-dark' }}">
                            <i class="bi bi-x-circle me-1"></i> Ditolak
                            <span class="badge bg-danger ms-1">{{ $counts['rejected'] }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.sellers.index', ['status' => 'all']) }}"
                            class="nav-link {{ $status === 'all' ? 'active' : 'text-dark' }}">
                            <i class="bi bi-list me-1"></i> Semua
                        </a>
                    </li>
                </ul>
            </div>

            {{-- Alert Messages --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Sellers Table --}}
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">Mahasiswa</th>
                                    <th>Info Bisnis</th>
                                    <th>Keahlian</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-end pe-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sellers as $seller)
                                    @php
                                        $colorIndex = ord($seller->user->name[0]) % count($avatarColors);
                                        $avatarColor = $avatarColors[$colorIndex];
                                    @endphp
                                    <tr>
                                        {{-- User Info --}}
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                @if ($seller->user->avatar)
                                                    <img src="{{ $seller->user->avatar_url }}"
                                                        alt="{{ $seller->user->name }}" class="rounded-circle me-2"
                                                        style="width: 32px; height: 32px; object-fit: cover;">
                                                @else
                                                    <div class="profile-avatar me-2"
                                                        style="background-color: {{ $avatarColor }}">
                                                        {{ strtoupper(substr($seller->user->name, 0, 1)) }}
                                                    </div>
                                                @endif
                                                <div>
                                                    <div class="fw-bold">{{ $seller->user->name }}</div>
                                                    <div class="small text-muted">{{ $seller->university }}</div>
                                                    <div class="small text-muted">{{ $seller->major }}</div>
                                                </div>
                                            </div>
                                        </td>

                                        {{-- Business Info --}}
                                        <td>
                                            <div class="fw-bold text-primary">{{ $seller->business_name }}</div>
                                            <div class="small text-muted text-truncate" style="max-width: 200px;">
                                                {{ Str::limit($seller->description, 60) }}
                                            </div>
                                            @if ($seller->portfolio_url)
                                                <a href="{{ $seller->portfolio_url }}" target="_blank"
                                                    class="small text-decoration-none">
                                                    <i class="bi bi-link-45deg"></i> Portfolio
                                                </a>
                                            @endif
                                        </td>

                                        {{-- Skills --}}
                                        <td>
                                            <div style="max-width: 180px;">
                                                @if (is_array($seller->skills))
                                                    @foreach (array_slice($seller->skills, 0, 3) as $skill)
                                                        <span
                                                            class="badge bg-info bg-opacity-10 text-info border border-info mb-1">{{ $skill }}</span>
                                                    @endforeach
                                                    @if (count($seller->skills) > 3)
                                                        <span
                                                            class="badge bg-secondary mb-1">+{{ count($seller->skills) - 3 }}</span>
                                                    @endif
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </div>
                                        </td>

                                        {{-- Status --}}
                                        <td class="text-center">
                                            <span class="badge {{ $seller->status_badge_class }}">
                                                {{ $seller->status_label }}
                                            </span>
                                            @if ($seller->reviewed_at)
                                                <div class="small text-muted mt-1">
                                                    {{ $seller->reviewed_at->format('d M Y') }}
                                                </div>
                                            @endif
                                        </td>

                                        {{-- Actions --}}
                                        <td class="text-end pe-4">
                                            <div class="d-flex justify-content-end gap-2">
                                                {{-- View Detail Button --}}
                                                <button type="button" class="btn btn-outline-primary btn-sm"
                                                    @click="showDetail({{ $seller->id }}, {{ json_encode([
                                                        'id' => $seller->id,
                                                        'business_name' => $seller->business_name,
                                                        'description' => $seller->description,
                                                        'skills' => $seller->skills,
                                                        'portfolio_url' => $seller->portfolio_url,
                                                        'status' => $seller->status,
                                                        'status_label' => $seller->status_label,
                                                        'status_badge_class' => $seller->status_badge_class,
                                                        'rejection_reason' => $seller->rejection_reason,
                                                        'rejected_at' => $seller->rejected_at?->format('d M Y H:i'),
                                                        'reviewed_at' => $seller->reviewed_at?->format('d M Y H:i'),
                                                        'reviewer_name' => $seller->reviewer?->name,
                                                        'user' => [
                                                            'name' => $seller->user->name,
                                                            'email' => $seller->user->email,
                                                            'avatar' => $seller->user->avatar,
                                                            'avatar_url' => $seller->user->avatar_url,
                                                            'avatar_color' => $avatarColor,
                                                            'initial' => strtoupper(substr($seller->user->name, 0, 1)),
                                                            'created_at' => $seller->user->created_at->format('d M Y'),
                                                        ],
                                                        'university' => $seller->university,
                                                        'major' => $seller->major,
                                                        'created_at' => $seller->created_at->format('d M Y H:i'),
                                                    ]) }})"
                                                    title="Lihat Detail">
                                                    <i class="bi bi-eye"></i>
                                                </button>

                                                @if ($seller->isPending())
                                                    {{-- Approve --}}
                                                    <form action="{{ route('admin.sellers.approve', $seller->id) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf @method('PATCH')
                                                        <button type="submit" class="btn btn-success btn-sm"
                                                            title="Setujui">
                                                            <i class="bi bi-check-lg"></i>
                                                        </button>
                                                    </form>

                                                    {{-- Reject --}}
                                                    <button type="button" class="btn btn-danger btn-sm"
                                                        @click="showRejectModal({{ $seller->id }}, '{{ $seller->business_name }}')"
                                                        title="Tolak">
                                                        <i class="bi bi-x-lg"></i>
                                                    </button>
                                                @elseif($seller->isApproved())
                                                    {{-- Suspend --}}
                                                    <button type="button" class="btn btn-warning btn-sm"
                                                        @click="showSuspendModal({{ $seller->id }}, '{{ $seller->business_name }}')"
                                                        title="Tangguhkan">
                                                        <i class="bi bi-pause-circle"></i>
                                                    </button>
                                                @elseif($seller->status === 'suspended')
                                                    {{-- Reactivate --}}
                                                    <form action="{{ route('admin.sellers.reactivate', $seller->id) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf @method('PATCH')
                                                        <button type="submit" class="btn btn-info btn-sm"
                                                            title="Aktifkan Kembali">
                                                            <i class="bi bi-play-circle"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5">
                                            <div class="text-muted">
                                                <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                                <h5 class="fw-bold">Tidak ada data</h5>
                                                <p class="mb-0">
                                                    @if ($status === 'pending')
                                                        Tidak ada pengajuan seller yang menunggu verifikasi.
                                                    @elseif($status === 'approved')
                                                        Belum ada seller yang disetujui.
                                                    @elseif($status === 'rejected')
                                                        Tidak ada pengajuan yang ditolak.
                                                    @else
                                                        Tidak ada data seller.
                                                    @endif
                                                </p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Detail Modal --}}
        <div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold">Detail Pengajuan Seller</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" x-show="selectedSeller">
                        <template x-if="selectedSeller">
                            <div>
                                {{-- User Profile --}}
                                <div class="d-flex align-items-center mb-4 p-3 bg-light rounded-3">
                                    <template x-if="selectedSeller.user.avatar">
                                        <img :src="selectedSeller.user.avatar_url" class="rounded-circle me-3"
                                            width="70" height="70" style="object-fit: cover;">
                                    </template>
                                    <template x-if="!selectedSeller.user.avatar">
                                        <div class="profile-avatar me-3"
                                            :style="`background-color: ${selectedSeller.user.avatar_color}; width: 70px; height: 70px; font-size: 1.75rem;`"
                                            x-text="selectedSeller.user.initial"></div>
                                    </template>
                                    <div class="flex-grow-1">
                                        <h5 class="mb-1 fw-bold" x-text="selectedSeller.user.name"></h5>
                                        <div class="text-muted small" x-text="selectedSeller.user.email"></div>
                                        <div class="text-muted small">
                                            <span x-text="selectedSeller.university"></span> •
                                            <span x-text="selectedSeller.major"></span>
                                        </div>
                                    </div>
                                    <span :class="selectedSeller.status_badge_class" class="badge"
                                        x-text="selectedSeller.status_label"></span>
                                </div>

                                {{-- Business Info --}}
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <label class="form-label text-muted small fw-bold">NAMA BISNIS</label>
                                        <div class="fw-medium" x-text="selectedSeller.business_name"></div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label text-muted small fw-bold">TANGGAL DAFTAR</label>
                                        <div x-text="selectedSeller.created_at"></div>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label text-muted small fw-bold">DESKRIPSI LAYANAN</label>
                                        <div class="p-3 bg-light rounded" x-text="selectedSeller.description"></div>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label text-muted small fw-bold">KEAHLIAN</label>
                                        <div>
                                            <template x-for="skill in selectedSeller.skills || []" :key="skill">
                                                <span class="badge bg-primary me-1 mb-1" x-text="skill"></span>
                                            </template>
                                        </div>
                                    </div>
                                    <div class="col-12" x-show="selectedSeller.portfolio_url">
                                        <label class="form-label text-muted small fw-bold">PORTFOLIO</label>
                                        <div>
                                            <a :href="selectedSeller.portfolio_url" target="_blank"
                                                class="btn btn-outline-primary btn-sm">
                                                <i class="bi bi-link-45deg me-1"></i> Lihat Portfolio
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                {{-- Rejection Info (if rejected) --}}
                                <template x-if="selectedSeller.status === 'rejected'">
                                    <div class="mt-4 p-3 bg-danger bg-opacity-10 rounded-3 border border-danger">
                                        <label class="form-label text-danger small fw-bold mb-2">
                                            <i class="bi bi-x-circle me-1"></i> ALASAN PENOLAKAN
                                        </label>
                                        <div x-text="selectedSeller.rejection_reason || '-'"></div>
                                        <div class="small text-muted mt-2">
                                            Ditolak pada: <span x-text="selectedSeller.rejected_at"></span>
                                            <template x-if="selectedSeller.reviewer_name">
                                                <span> oleh <span x-text="selectedSeller.reviewer_name"></span></span>
                                            </template>
                                        </div>
                                    </div>
                                </template>

                                {{-- Review Info (if approved) --}}
                                <template x-if="selectedSeller.status === 'approved' && selectedSeller.reviewed_at">
                                    <div class="mt-4 p-3 bg-success bg-opacity-10 rounded-3 border border-success">
                                        <div class="small text-success">
                                            <i class="bi bi-check-circle me-1"></i>
                                            Disetujui pada: <span x-text="selectedSeller.reviewed_at"></span>
                                            <template x-if="selectedSeller.reviewer_name">
                                                <span> oleh <span x-text="selectedSeller.reviewer_name"></span></span>
                                            </template>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </template>
                    </div>
                    <div class="modal-footer border-0" x-show="selectedSeller && selectedSeller.status === 'pending'">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="button" class="btn btn-danger"
                            @click="showRejectModal(selectedSeller.id, selectedSeller.business_name); bootstrap.Modal.getInstance(document.getElementById('detailModal')).hide();">
                            <i class="bi bi-x-lg me-1"></i> Tolak
                        </button>
                        <form :action="`{{ url('admin/seller-requests') }}/${selectedSeller?.id}/approve`" method="POST"
                            class="d-inline">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check-lg me-1"></i> Setujui
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Reject Modal --}}
        <div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header border-0">
                        <h5 class="modal-title fw-bold text-danger">
                            <i class="bi bi-x-circle me-2"></i>Tolak Pengajuan
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form :action="`{{ url('admin/seller-requests') }}/${rejectSellerId}/reject`" method="POST">
                        @csrf
                        <div class="modal-body">
                            <p class="text-muted">
                                Anda akan menolak pengajuan dari <strong x-text="rejectSellerName"></strong>.
                                Pilih alasan penolakan:
                            </p>

                            <div class="mb-3">
                                <label class="form-label fw-medium">Alasan Penolakan <span
                                        class="text-danger">*</span></label>
                                <select name="rejection_reason_type" class="form-select" x-model="rejectionType"
                                    required>
                                    <option value="">-- Pilih Alasan --</option>
                                    @foreach (\App\Models\Seller::REJECTION_REASONS as $key => $label)
                                        <option value="{{ $key }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3" x-show="rejectionType === 'other'" x-transition>
                                <label class="form-label fw-medium">Alasan Lainnya <span
                                        class="text-danger">*</span></label>
                                <textarea name="rejection_reason_custom" class="form-control" rows="3"
                                    placeholder="Jelaskan alasan penolakan..." x-bind:required="rejectionType === 'other'"></textarea>
                            </div>

                            <div class="alert alert-warning d-flex align-items-center mb-0">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                <small>Seller akan menerima notifikasi penolakan beserta alasannya dan dapat mendaftar
                                    ulang.</small>
                            </div>
                        </div>
                        <div class="modal-footer border-0">
                            <button type="button" class="btn btn-outline-secondary"
                                data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-danger">
                                <i class="bi bi-x-lg me-1"></i> Tolak Pengajuan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Suspend Modal --}}
        <div class="modal fade" id="suspendModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header border-0">
                        <h5 class="modal-title fw-bold text-warning">
                            <i class="bi bi-pause-circle me-2"></i>Tangguhkan Seller
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form :action="`{{ url('admin/seller-requests') }}/${suspendSellerId}/suspend`" method="POST">
                        @csrf
                        <div class="modal-body">
                            <p class="text-muted">
                                Anda akan menangguhkan seller <strong x-text="suspendSellerName"></strong>.
                                Seller tidak akan bisa menerima order baru.
                            </p>

                            <div class="mb-3">
                                <label class="form-label fw-medium">Alasan Penangguhan <span
                                        class="text-danger">*</span></label>
                                <textarea name="suspension_reason" class="form-control" rows="3" placeholder="Jelaskan alasan penangguhan..."
                                    required></textarea>
                            </div>

                            <div class="alert alert-info d-flex align-items-center mb-0">
                                <i class="bi bi-info-circle me-2"></i>
                                <small>Seller dapat diaktifkan kembali kapan saja oleh admin.</small>
                            </div>
                        </div>
                        <div class="modal-footer border-0">
                            <button type="button" class="btn btn-outline-secondary"
                                data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-warning">
                                <i class="bi bi-pause-circle me-1"></i> Tangguhkan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function sellerManagement() {
            return {
                selectedSeller: null,
                rejectSellerId: null,
                rejectSellerName: '',
                rejectionType: '',
                suspendSellerId: null,
                suspendSellerName: '',

                showDetail(id, seller) {
                    this.selectedSeller = seller;
                    new bootstrap.Modal(document.getElementById('detailModal')).show();
                },

                showRejectModal(id, name) {
                    this.rejectSellerId = id;
                    this.rejectSellerName = name;
                    this.rejectionType = '';
                    new bootstrap.Modal(document.getElementById('rejectModal')).show();
                },

                showSuspendModal(id, name) {
                    this.suspendSellerId = id;
                    this.suspendSellerName = name;
                    new bootstrap.Modal(document.getElementById('suspendModal')).show();
                }
            }
        }
    </script>
@endsection
