<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    // 1. Tampilkan Daftar Laporan
    public function index()
    {
        // Ambil semua laporan + data pelapor & terlapor biar tidak query berulang (N+1 problem)
        $reports = Report::with(['reporter', 'reportedUser', 'order'])
            ->latest()
            ->get();

        return view('admin.reports.index', compact('reports'));
    }

    // 2. Tandai Selesai (Resolved) - Jika admin sudah menengahi
    public function resolve(Report $report)
    {
        $report->update(['status' => 'resolved']);
        return redirect()->back()->with('success', 'Laporan ditandai selesai (Resolved).');
    }

    // 3. Abaikan (Dismissed) - Jika laporan tidak valid
    public function dismiss(Report $report)
    {
        $report->update(['status' => 'dismissed']);
        return redirect()->back()->with('success', 'Laporan diabaikan.');
    }
    // 4. SUSPEND USER (Hukuman)
    public function suspend(Request $request, $reportId)
    {
        $report = \App\Models\Report::findOrFail($reportId);
        $userToSuspend = $report->reportedUser; // User yang dilaporkan

        // Validasi input hari
        $days = $request->input('days'); // 1, 3, 7, 30, atau 'permanent'

        if ($days === 'permanent') {
            $suspensionEnd = now()->addYears(100); // 100 Tahun = Permanen
            $msg = 'dibanned PERMANEN';
        } else {
            $suspensionEnd = now()->addDays((int)$days);
            $msg = "disuspend selama $days hari";
        }

        // Terapkan Hukuman
        $userToSuspend->update(['suspended_until' => $suspensionEnd]);

        // Otomatis tandai laporan sebagai "Resolved" (Selesai)
        $report->update(['status' => 'resolved']);

        return redirect()->back()->with('success', "User berhasil $msg.");
    }
}
