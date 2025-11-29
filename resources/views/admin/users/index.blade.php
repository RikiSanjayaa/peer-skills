@extends('layouts.admin')

@section('content')
    <div class="py-4">
        <div class="container-fluid px-4">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold text-dark">Manage Users</h2>
                    <p class="text-muted">Kelola pengguna platform (non-admin)</p>
                </div>
            </div>

            {{-- Info Card --}}
            <div class="alert alert-info d-flex align-items-center mb-4">
                <i class="bi bi-info-circle fs-4 me-3"></i>
                <div>
                    <strong>Catatan:</strong> Halaman ini hanya menampilkan user biasa (buyer/seller).
                    Akun admin tidak dapat dibuat dari sini dan hanya dapat dikelola melalui database.
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">User</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Bergabung</th>
                                    <th class="text-end pe-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                    <tr>
                                        <td class="ps-4 fw-bold">
                                            <div class="d-flex align-items-center">
                                                @if ($user->avatar)
                                                    <img src="{{ $user->avatar_url }}" class="rounded-circle me-2"
                                                        style="width: 35px; height: 35px; object-fit: cover;">
                                                @else
                                                    <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center me-2"
                                                        style="width: 35px; height: 35px;">
                                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                                    </div>
                                                @endif
                                                {{ $user->name }}
                                            </div>
                                        </td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            @if ($user->suspended_until && $user->suspended_until->isFuture())
                                                <span class="badge bg-danger">SUSPENDED</span>
                                                <div class="small text-danger mt-1" style="font-size: 0.75rem;">
                                                    s.d {{ $user->suspended_until->format('d M Y') }}
                                                </div>
                                            @elseif ($user->is_seller)
                                                <span class="badge bg-success">SELLER</span>
                                            @else
                                                <span class="badge bg-secondary">BUYER</span>
                                            @endif
                                        </td>
                                        <td>{{ $user->created_at->format('d M Y') }}</td>
                                        <td class="text-end pe-4">
                                            <div class="d-flex justify-content-end gap-2">

                                                {{-- LOGIKA TOMBOL UNBAN (Hanya muncul kalau kena suspend) --}}
                                                @if ($user->suspended_until && $user->suspended_until->isFuture())
                                                    <form action="{{ route('admin.users.unban', $user->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit"
                                                            class="btn btn-sm btn-info text-white fw-bold shadow-sm"
                                                            title="Cabut Hukuman (Unban)">
                                                            <i class="bi bi-unlock-fill me-1"></i> Unban
                                                        </button>
                                                    </form>
                                                @else
                                                    
                                                    <a href="{{ route('profile.show', $user) }}"
                                                        class="btn btn-sm btn-outline-primary" target="_blank"
                                                        title="Lihat Profil">
                                                        <i class="bi bi-eye"></i>
                                                    </a>

                                                    <form id="delete-user-{{ $user->id }}"
                                                        action="{{ route('admin.users.destroy', $user->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn btn-sm btn-outline-danger"
                                                            onclick="confirmDeleteUser({{ $user->id }}, '{{ $user->name }}')">
                                                            <i class="bi bi-trash"></i>
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
                                                <i class="bi bi-people fs-1 d-block mb-3"></i>
                                                <h5 class="fw-bold">Tidak ada user</h5>
                                                <p class="mb-0">Belum ada user yang terdaftar.</p>
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
        function confirmDeleteUser(id, name) {
            Swal.fire({
                title: 'Hapus User?',
                html: `Anda akan menghapus user <strong>${name}</strong>.<br>Semua data terkait (gig, order) akan ikut terhapus.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-user-' + id).submit();
                }
            })
        }
    </script>
@endsection
