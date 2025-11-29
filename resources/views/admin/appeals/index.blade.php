@extends('layouts.admin')

@section('content')
<div class="py-5 bg-light">
    <div class="container">
        
        <h3 class="fw-bold mb-4">Permohonan Banding (Unban Requests)</h3>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">User</th>
                                <th>Pesan Klarifikasi</th>
                                <th>Waktu Request</th>
                                <th class="text-end pe-4">Keputusan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($appeals as $appeal)
                            <tr>
                                <td class="ps-4 fw-bold">
                                    {{ $appeal->user->name }} <br>
                                    <small class="text-muted">{{ $appeal->user->email }}</small>
                                </td>
                                <td style="max-width: 400px;">
                                    <div class="p-2 bg-light rounded border">
                                        "{{ $appeal->message }}"
                                    </div>
                                </td>
                                <td>{{ $appeal->created_at->diffForHumans() }}</td>
                                <td class="text-end pe-4">
                                    <div class="d-flex justify-content-end gap-2">
                                        <form action="{{ route('admin.appeals.approve', $appeal->id) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <button class="btn btn-success btn-sm fw-bold">
                                                <i class="bi bi-unlock-fill"></i> Terima & Unban
                                            </button>
                                        </form>

                                        <form action="{{ route('admin.appeals.reject', $appeal->id) }}" method="POST" onsubmit="return confirm('Tolak banding ini?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-outline-danger btn-sm">
                                                Tolak
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted">
                                    Tidak ada permohonan banding baru.
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
@endsection