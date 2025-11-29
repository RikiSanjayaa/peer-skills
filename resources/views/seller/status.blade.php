@extends('layouts.app')

@section('content')
    <div class="py-5 bg-light min-vh-100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    {{-- Status Card --}}
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-5 text-center">
                            @if ($seller->isPending())
                                {{-- Pending Status --}}
                                <div class="mb-4">
                                    <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center"
                                        style="width: 100px; height: 100px;">
                                        <i class="bi bi-hourglass-split text-warning" style="font-size: 3rem;"></i>
                                    </div>
                                </div>
                                <h3 class="fw-bold mb-3">Pengajuan Sedang Diproses</h3>
                                <p class="text-muted mb-4">
                                    Pengajuan seller Anda sedang ditinjau oleh tim kami.
                                    Proses verifikasi biasanya memakan waktu 1-3 hari kerja.
                                </p>

                                <div class="bg-light rounded-3 p-4 text-start mb-4">
                                    <h6 class="fw-bold mb-3">Detail Pengajuan</h6>
                                    <div class="row g-3">
                                        <div class="col-sm-6">
                                            <small class="text-muted d-block">Nama Bisnis</small>
                                            <span class="fw-medium">{{ $seller->business_name }}</span>
                                        </div>
                                        <div class="col-sm-6">
                                            <small class="text-muted d-block">Tanggal Daftar</small>
                                            <span>{{ $seller->created_at->format('d M Y, H:i') }}</span>
                                        </div>
                                        <div class="col-12">
                                            <small class="text-muted d-block">Keahlian</small>
                                            <div class="mt-1">
                                                @foreach ($seller->skills ?? [] as $skill)
                                                    <span class="badge bg-primary me-1">{{ $skill }}</span>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <a href="{{ route('home') }}" class="btn btn-outline-primary">
                                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Beranda
                                </a>
                            @elseif($seller->isRejected())
                                {{-- Rejected Status --}}
                                <div class="mb-4">
                                    <div class="bg-danger bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center"
                                        style="width: 100px; height: 100px;">
                                        <i class="bi bi-x-circle text-danger" style="font-size: 3rem;"></i>
                                    </div>
                                </div>
                                <h3 class="fw-bold mb-3">Pengajuan Ditolak</h3>
                                <p class="text-muted mb-4">
                                    Maaf, pengajuan seller Anda tidak dapat disetujui.
                                    Silakan baca alasan penolakan di bawah ini.
                                </p>

                                {{-- Rejection Reason --}}
                                <div class="bg-danger bg-opacity-10 border border-danger rounded-3 p-4 text-start mb-4">
                                    <h6 class="fw-bold text-danger mb-2">
                                        <i class="bi bi-exclamation-circle me-1"></i> Alasan Penolakan
                                    </h6>
                                    <p class="mb-0">{{ $seller->rejection_reason }}</p>
                                    @if ($seller->rejected_at)
                                        <small class="text-muted d-block mt-2">
                                            Ditolak pada {{ $seller->rejected_at->format('d M Y, H:i') }}
                                        </small>
                                    @endif
                                </div>

                                {{-- Previous Application Details --}}
                                <div class="bg-light rounded-3 p-4 text-start mb-4">
                                    <h6 class="fw-bold mb-3">Detail Pengajuan Sebelumnya</h6>
                                    <div class="row g-3">
                                        <div class="col-sm-6">
                                            <small class="text-muted d-block">Nama Bisnis</small>
                                            <span class="fw-medium">{{ $seller->business_name }}</span>
                                        </div>
                                        <div class="col-sm-6">
                                            <small class="text-muted d-block">Universitas</small>
                                            <span>{{ $seller->university }}</span>
                                        </div>
                                        <div class="col-12">
                                            <small class="text-muted d-block">Deskripsi</small>
                                            <span>{{ Str::limit($seller->description, 100) }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex gap-2 justify-content-center">
                                    <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                                        <i class="bi bi-arrow-left me-1"></i> Kembali
                                    </a>
                                    <a href="{{ route('seller.register') }}" class="btn btn-primary">
                                        <i class="bi bi-arrow-repeat me-1"></i> Ajukan Ulang
                                    </a>
                                </div>
                            @elseif($seller->isApproved())
                                {{-- Approved - redirect to dashboard --}}
                                <script>
                                    window.location.href = "{{ route('seller.dashboard') }}";
                                </script>
                                <div class="mb-4">
                                    <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center"
                                        style="width: 100px; height: 100px;">
                                        <i class="bi bi-check-circle text-success" style="font-size: 3rem;"></i>
                                    </div>
                                </div>
                                <h3 class="fw-bold mb-3">Pengajuan Disetujui!</h3>
                                <p class="text-muted mb-4">Selamat! Anda sekarang adalah seller di PeerSkill.</p>
                                <a href="{{ route('seller.dashboard') }}" class="btn btn-success">
                                    <i class="bi bi-speedometer2 me-1"></i> Ke Dashboard Seller
                                </a>
                            @endif
                        </div>
                    </div>

                    {{-- Help Card --}}
                    <div class="card border-0 shadow-sm mt-4">
                        <div class="card-body p-4">
                            <h6 class="fw-bold mb-3">
                                <i class="bi bi-question-circle me-1"></i> Butuh Bantuan?
                            </h6>
                            <p class="text-muted small mb-0">
                                Jika Anda memiliki pertanyaan tentang pengajuan seller, silakan hubungi tim support kami
                                melalui email di <a href="mailto:support@peerskill.com">support@peerskill.com</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
