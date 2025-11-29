@extends('layouts.admin')

@section('content')
    <div class="py-5 bg-light">
        <div class="container">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold text-dark">Laporan Masalah</h2>
                    <p class="text-muted">Daftar laporan dari Buyer dan Seller terkait order</p>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">Pelapor (Reporter)</th>
                                    <th>Terlapor</th>
                                    <th>Masalah</th>
                                    <th>Status</th>
                                    <th class="text-end pe-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reports as $report)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="fw-bold text-primary">{{ $report->reporter->name }}</div>
                                            <div class="small text-muted">Order #{{ $report->order_id }}</div>
                                        </td>

                                        <td>
                                            <div class="fw-bold">{{ $report->reportedUser->name }}</div>
                                            <div class="small text-muted">{{ $report->reportedUser->email }}</div>
                                        </td>

                                        <td style="max-width: 300px;">
                                            <span
                                                class="badge bg-warning text-dark mb-1">{{ str_replace('_', ' ', ucfirst($report->reason)) }}</span>
                                            <div class="small text-muted text-truncate">{{ $report->description }}</div>
                                        </td>

                                        <td>
                                            @if ($report->status == 'pending')
                                                <span class="badge bg-danger">Pending</span>
                                            @elseif($report->status == 'resolved')
                                                <span class="badge bg-success">Resolved</span>
                                            @else
                                                <span class="badge bg-secondary">Dismissed</span>
                                            @endif
                                        </td>

                                        <td class="text-end pe-4">
                                            @if ($report->status == 'pending')
                                                <div class="d-flex justify-content-end gap-2">
                                                    <form action="{{ route('admin.reports.resolve', $report->id) }}"
                                                        method="POST">
                                                        @csrf @method('PATCH')
                                                        <button type="submit" class="btn btn-sm btn-outline-success"
                                                            title="Tandai Selesai Tanpa Hukuman">
                                                            <i class="bi bi-check-lg"></i>
                                                        </button>
                                                    </form>

                                                    <button type="button" class="btn btn-sm btn-danger fw-bold"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#suspendModal-{{ $report->id }}">
                                                        <i class="bi bi-slash-circle me-1"></i> Suspend
                                                    </button>

                                                    <form action="{{ route('admin.reports.dismiss', $report->id) }}"
                                                        method="POST">
                                                        @csrf @method('PATCH')
                                                        <button type="submit" class="btn btn-sm btn-outline-secondary"
                                                            title="Abaikan">
                                                            <i class="bi bi-x-lg"></i>
                                                        </button>
                                                    </form>
                                                </div>

                                                <div class="modal fade text-start" id="suspendModal-{{ $report->id }}"
                                                    tabindex="-1">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <form
                                                                action="{{ route('admin.reports.suspend', $report->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                <div class="modal-header bg-danger text-white">
                                                                    <h5 class="modal-title"><i
                                                                            class="bi bi-exclamation-triangle-fill me-2"></i>Suspend
                                                                        User</h5>
                                                                    <button type="button" class="btn-close btn-close-white"
                                                                        data-bs-dismiss="modal"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <p>Anda akan menghukum user:
                                                                        <strong>{{ $report->reportedUser->name }}</strong>
                                                                    </p>
                                                                    <p class="small text-muted">User tidak akan bisa login
                                                                        selama masa hukuman.</p>

                                                                    <div class="mb-3">
                                                                        <label class="form-label fw-bold">Pilih Durasi
                                                                            Hukuman:</label>
                                                                        <select name="days" class="form-select" required>
                                                                            <option value="" selected disabled>Pilih
                                                                                durasi...</option>
                                                                            <option value="1">1 Hari (Peringatan
                                                                                Ringan)</option>
                                                                            <option value="3">3 Hari</option>
                                                                            <option value="7">7 Hari (1 Minggu)
                                                                            </option>
                                                                            <option value="30">30 Hari (1 Bulan)
                                                                            </option>
                                                                            <option value="permanent"
                                                                                class="text-danger fw-bold">⛔ BANNED
                                                                                PERMANEN</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-light"
                                                                        data-bs-dismiss="modal">Batal</button>
                                                                    <button type="submit"
                                                                        class="btn btn-danger fw-bold">Eksekusi
                                                                        Hukuman</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-muted small fst-italic">No actions</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5 text-muted">Belum ada laporan masuk.
                                            Aman! 🛡️</td>
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
