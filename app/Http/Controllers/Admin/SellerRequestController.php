<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SellerRequestController extends Controller
{
    /**
     * Tampilkan daftar request seller dengan filter status
     */
    public function index(Request $request)
    {
        $status = $request->get('status', 'pending');

        $query = Seller::with('user', 'reviewer');

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $sellers = $query->latest()->get();

        // Count untuk tabs
        $counts = [
            'pending' => Seller::pending()->count(),
            'approved' => Seller::approved()->count(),
            'rejected' => Seller::rejected()->count(),
        ];

        return view('admin.sellers.index', compact('sellers', 'status', 'counts'));
    }

    /**
     * Tampilkan detail seller untuk review
     */
    public function show($id)
    {
        $seller = Seller::with('user', 'reviewer', 'gigs')->findOrFail($id);

        // Cek apakah user pernah ditolak sebelumnya (riwayat)
        $previousRejections = Seller::where('user_id', $seller->user_id)
            ->where('id', '!=', $seller->id)
            ->where('status', 'rejected')
            ->get();

        return view('admin.sellers.show', compact('seller', 'previousRejections'));
    }

    /**
     * Approve seller request
     */
    public function approve($id)
    {
        $seller = Seller::findOrFail($id);

        if (!$seller->isPending()) {
            return redirect()->back()->with('error', 'Pengajuan ini sudah diproses sebelumnya.');
        }

        $seller->update([
            'status' => Seller::STATUS_APPROVED,
            'is_active' => true,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        // Update user menjadi seller
        $seller->user->update(['is_seller' => true]);

        return redirect()->back()->with('success', "Seller '{$seller->business_name}' berhasil disetujui!");
    }

    /**
     * Reject seller request dengan alasan
     */
    public function reject(Request $request, $id)
    {
        $seller = Seller::findOrFail($id);

        if (!$seller->isPending()) {
            return redirect()->back()->with('error', 'Pengajuan ini sudah diproses sebelumnya.');
        }

        $validated = $request->validate([
            'rejection_reason_type' => 'required|string',
            'rejection_reason_custom' => 'required_if:rejection_reason_type,other|nullable|string|max:500',
        ]);

        // Tentukan alasan penolakan
        $reasonType = $validated['rejection_reason_type'];
        if ($reasonType === 'other') {
            $reason = $validated['rejection_reason_custom'];
        } else {
            $reason = Seller::REJECTION_REASONS[$reasonType] ?? $reasonType;
        }

        $seller->update([
            'status' => Seller::STATUS_REJECTED,
            'is_active' => false,
            'rejection_reason' => $reason,
            'rejected_at' => now(),
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        return redirect()->back()->with('success', "Pengajuan seller '{$seller->business_name}' telah ditolak.");
    }

    /**
     * Suspend seller yang sudah approved
     */
    public function suspend(Request $request, $id)
    {
        $seller = Seller::findOrFail($id);

        if (!$seller->isApproved()) {
            return redirect()->back()->with('error', 'Hanya seller yang sudah disetujui yang dapat ditangguhkan.');
        }

        $validated = $request->validate([
            'suspension_reason' => 'required|string|max:500',
        ]);

        $seller->update([
            'status' => Seller::STATUS_SUSPENDED,
            'is_active' => false,
            'rejection_reason' => $validated['suspension_reason'],
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        // Update user
        $seller->user->update(['is_seller' => false]);

        return redirect()->back()->with('success', "Seller '{$seller->business_name}' telah ditangguhkan.");
    }

    /**
     * Reactivate suspended seller
     */
    public function reactivate($id)
    {
        $seller = Seller::findOrFail($id);

        if ($seller->status !== Seller::STATUS_SUSPENDED) {
            return redirect()->back()->with('error', 'Seller ini tidak dalam status ditangguhkan.');
        }

        $seller->update([
            'status' => Seller::STATUS_APPROVED,
            'is_active' => true,
            'rejection_reason' => null,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        $seller->user->update(['is_seller' => true]);

        return redirect()->back()->with('success', "Seller '{$seller->business_name}' telah diaktifkan kembali.");
    }
}
