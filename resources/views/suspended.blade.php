<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akun Ditangguhkan - PeerSkill</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #f8f9fa; }
        .suspend-card { max-width: 600px; margin: 80px auto; }
    </style>
</head>
<body>

    <div class="container">
        <div class="card border-danger shadow-lg suspend-card">
            <div class="card-header bg-danger text-white fw-bold">
                <i class="bi bi-exclamation-octagon-fill me-2"></i> AKUN DIBEKUKAN
            </div>
            <div class="card-body p-5 text-center">
                <h3 class="text-danger mb-3">Akses Anda Ditangguhkan</h3>
                
                <p class="lead text-muted">
                    Halo <strong>{{ auth()->user()->name }}</strong>, akun Anda telah disuspend karena pelanggaran aturan komunitas.
                </p>
    
                <div class="alert alert-warning py-4">
                    <h5 class="alert-heading fw-bold"><i class="bi bi-clock-history"></i> Hukuman Berakhir:</h5>
                    <p class="display-6 mb-0">{{ auth()->user()->suspended_until->format('d M Y') }}</p>
                    <small class="text-muted">({{ auth()->user()->suspended_until->diffForHumans() }})</small>
                </div>
    
                <hr class="my-4">
    
                <h5 class="fw-bold">Ajukan Banding / Klarifikasi</h5>
                <p class="text-muted small mb-3">Jika Anda merasa ini kesalahan, silakan jelaskan kepada kami.</p>
    
                {{-- Cek apakah sudah pernah banding --}}
                @if(App\Models\BanAppeal::where('user_id', auth()->id())->where('status', 'pending')->exists())
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle-fill me-2"></i> Banding Anda sedang ditinjau oleh Admin. Mohon tunggu.
                    </div>
                @else
                    <form action="{{ route('suspended.appeal') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <textarea name="message" class="form-control" rows="4" placeholder="Tulis alasan kenapa akun Anda harus dipulihkan..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-dark w-100 fw-bold">Kirim Klarifikasi</button>
                    </form>
                @endif
                
                <form method="POST" action="{{ route('logout') }}" class="mt-4">
                    @csrf
                    <button type="submit" class="btn btn-link text-muted text-decoration-none">
                        <i class="bi bi-box-arrow-left"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>