@extends('layouts.app')

@section('content')
<div class="py-5 bg-light">
    <div class="container">
        
        <div class="mb-4">
            <h2 class="fw-bold text-dark">Permintaan Verifikasi Seller</h2>
            <p class="text-muted">Tinjau mahasiswa yang ingin mendaftar sebagai seller</p>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Mahasiswa</th>
                                <th>Info Bisnis</th>
                                <th>Keahlian (Skills)</th>
                                <th class="text-end pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pendingSellers as $seller)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold">{{ $seller->user->name }}</div>
                                    <div class="small text-muted">{{ $seller->university }}</div>
                                    <div class="small text-muted">{{ $seller->major }}</div>
                                </td>

                                <td>
                                    <div class="fw-bold text-primary">{{ $seller->business_name }}</div>
                                    <div class="small text-muted text-truncate" style="max-width: 200px;">
                                        {{ $seller->description }}
                                    </div>
                                    @if($seller->portfolio_url)
                                        <a href="{{ $seller->portfolio_url }}" target="_blank" class="small text-decoration-none">Lihat Portfolio ↗</a>
                                    @endif
                                </td>

                                <td>
                                    @if(is_array($seller->skills))
                                        @foreach($seller->skills as $skill)
                                            <span class="badge bg-info bg-opacity-10 text-info border border-info mb-1">{{ $skill }}</span>
                                        @endforeach
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>

                                <td class="text-end pe-4">
                                    <div class="d-flex justify-content-end gap-2">
                                        <form action="{{ route('admin.sellers.approve', $seller->id) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="btn btn-success btn-sm fw-bold">
                                                ✓ Terima
                                            </button>
                                        </form>
                                        
                                        <form id="reject-seller-{{ $seller->id }}" action="{{ route('admin.sellers.reject', $seller->id) }}" method="POST">
                                            @csrf @method('DELETE')
                                            <button type="button" class="btn btn-outline-danger btn-sm" onclick="confirmReject({{ $seller->id }})">
                                                ✕ Tolak
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <div class="text-muted">
                                        <h5 class="fw-bold">Tidak ada permintaan baru</h5>
                                        <p>Semua pendaftaran seller sudah diproses.</p>
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
</div>

<script>
    function confirmReject(id) {
        Swal.fire({
            title: 'Tolak Pendaftaran?',
            text: "Data pengajuan akan dihapus permanen.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Tolak',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('reject-seller-' + id).submit();
            }
        })
    }
</script>
@endsection