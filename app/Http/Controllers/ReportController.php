<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function store(Request $request, Order $order)
    {
        $request->validate([
            'reason' => 'required|string',
            'description' => 'required|string|min:10',
        ]);

        // Logika Otomatis: Menentukan Siapa yang Dilaporkan
        // Jika yang lapor Buyer -> Terlapor adalah Seller
        // Jika yang lapor Seller -> Terlapor adalah Buyer
        $isBuyer = Auth::id() === $order->buyer_id;
        $reportedUserId = $isBuyer ? $order->seller_id : $order->buyer_id;

        Report::create([
            'order_id' => $order->id,
            'reporter_id' => Auth::id(),
            'reported_user_id' => $reportedUserId,
            'reason' => $request->reason,
            'description' => $request->description,
            'status' => 'pending'
        ]);

        return redirect()->back()->with('success', 'Laporan berhasil dikirim. Tim Admin akan segera meninjau masalah ini.');
    }
}