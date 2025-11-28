<?php

namespace App\Http\Controllers;

use App\Models\Gig;
use App\Models\Order;
use App\Models\OrderDelivery;
use App\Models\TutoringSchedule;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    /**
     * Get the authenticated user
     */
    private function user(): User
    {
        /** @var User */
        return Auth::user();
    }

    /**
     * Display a listing of the user's orders
     */
    public function index(Request $request)
    {
        $user = $this->user();
        $filter = $request->get('filter', 'all');
        $role = $request->get('role', 'buyer'); // 'buyer' or 'seller'

        $query = $role === 'seller'
            ? Order::forSeller($user->id)
            : Order::forBuyer($user->id);

        // Apply status filter
        if ($filter === 'active') {
            $query->active();
        } elseif ($filter === 'completed') {
            $query->completed();
        } elseif ($filter !== 'all') {
            $query->where('status', $filter);
        }

        $orders = $query->with(['gig', 'buyer', 'seller'])
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders', 'filter', 'role'));
    }

    /**
     * Show the form for creating a new order
     */
    public function create(Gig $gig)
    {
        // Prevent ordering your own gig
        if ($this->user()->id === $gig->seller->user_id) {
            return redirect()->route('gigs.show', $gig)
                ->with('error', 'You cannot order your own gig.');
        }

        $gig->load('seller.user', 'category');

        return view('orders.create', compact('gig'));
    }

    /**
     * Store a newly created order
     */
    public function store(Request $request, Gig $gig)
    {
        $user = $this->user();

        // Prevent ordering your own gig
        if ($user->id === $gig->seller->user_id) {
            return redirect()->route('gigs.show', $gig)
                ->with('error', 'You cannot order your own gig.');
        }

        // Base validation rules
        $rules = [
            'requirements' => 'required|string|min:20',
            'type' => 'required|in:standard,tutoring',
        ];

        // Add tutoring-specific validation only if type is tutoring
        if ($request->input('type') === 'tutoring') {
            $rules['proposed_slots'] = 'required|array|min:1';
            $rules['proposed_slots.*'] = 'required|date|after:now';
            $rules['topic'] = 'nullable|string';
        }

        $validated = $request->validate($rules);

        // Check if gig allows tutoring
        if ($validated['type'] === 'tutoring' && !$gig->allows_tutoring) {
            return back()->with('error', 'This gig does not offer tutoring services.');
        }

        $order = Order::create([
            'buyer_id' => $user->id,
            'seller_id' => $gig->seller->user_id,
            'gig_id' => $gig->id,
            'type' => $validated['type'],
            'requirements' => $validated['requirements'],
            'status' => Order::STATUS_PENDING,
        ]);

        // Create tutoring schedule if it's a tutoring order
        if ($validated['type'] === 'tutoring') {
            TutoringSchedule::create([
                'order_id' => $order->id,
                'proposed_slots' => $validated['proposed_slots'],
                'topic' => $validated['topic'] ?? null,
            ]);
        }

        return redirect()->route('orders.show', $order)
            ->with('success', 'Order placed successfully! The seller will review your requirements and send a quote.');
    }

    /**
     * Display the specified order
     */
    public function show(Order $order)
    {
        // Ensure user is a participant
        if (!$order->isParticipant($this->user())) {
            abort(403, 'You are not authorized to view this order.');
        }

        $order->load([
            'gig.category',
            'buyer',
            'seller',
            'deliveries',
            'tutoringSchedule',
        ]);

        return view('orders.show', compact('order'));
    }

    /**
     * Seller sends a quote for the order
     */
    public function quote(Request $request, Order $order)
    {
        // Only seller can quote
        if (!$order->isSeller($this->user())) {
            abort(403, 'Only the seller can send a quote.');
        }

        if (!$order->canBeQuoted()) {
            return back()->with('error', 'This order cannot be quoted at this time.');
        }

        $validated = $request->validate([
            'price' => 'required|numeric|min:1',
            'delivery_days' => 'required|integer|min:1',
            'seller_notes' => 'nullable|string|max:1000',
            // For tutoring, seller confirms a time slot
            'confirmed_slot' => 'required_if:type,tutoring|date|after:now',
            'external_link' => 'nullable|url',
        ]);

        $order->update([
            'price' => $validated['price'],
            'delivery_days' => $validated['delivery_days'],
            'seller_notes' => $validated['seller_notes'] ?? null,
            'status' => Order::STATUS_QUOTED,
            'quoted_at' => now(),
        ]);

        // Update tutoring schedule if applicable
        if ($order->isTutoring() && isset($validated['confirmed_slot'])) {
            $order->tutoringSchedule->update([
                'confirmed_slot' => $validated['confirmed_slot'],
                'external_link' => $validated['external_link'] ?? null,
            ]);
        }

        return redirect()->route('orders.show', $order)
            ->with('success', 'Quote sent successfully! Waiting for buyer to accept.');
    }

    /**
     * Buyer accepts the quote
     */
    public function acceptQuote(Order $order)
    {
        if (!$order->isBuyer($this->user())) {
            abort(403, 'Only the buyer can accept the quote.');
        }

        if (!$order->canBeAccepted()) {
            return back()->with('error', 'This quote cannot be accepted.');
        }

        $order->update([
            'status' => Order::STATUS_ACCEPTED,
            'accepted_at' => now(),
        ]);

        return redirect()->route('orders.show', $order)
            ->with('success', 'Quote accepted! The seller will now start working on your order.');
    }

    /**
     * Buyer declines the quote
     */
    public function declineQuote(Order $order)
    {
        if (!$order->isBuyer($this->user())) {
            abort(403, 'Only the buyer can decline the quote.');
        }

        if (!$order->canBeDeclined()) {
            return back()->with('error', 'This quote cannot be declined.');
        }

        $order->update([
            'status' => Order::STATUS_DECLINED,
        ]);

        return redirect()->route('orders.show', $order)
            ->with('info', 'Quote declined. You can place a new order if needed.');
    }

    /**
     * Seller delivers the order
     */
    public function deliver(Request $request, Order $order)
    {
        if (!$order->isSeller($this->user())) {
            abort(403, 'Only the seller can deliver.');
        }

        if (!$order->canBeDelivered()) {
            return back()->with('error', 'This order cannot be delivered at this time.');
        }

        $validated = $request->validate([
            'message' => 'required|string|min:10',
            'file' => 'nullable|file|max:51200', // 50MB max
            'is_final' => 'boolean',
        ]);

        $deliveryData = [
            'order_id' => $order->id,
            'message' => $validated['message'],
            'is_final' => $validated['is_final'] ?? false,
        ];

        // Handle file upload
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $path = $file->store('deliveries/' . $order->id, 'public');
            $deliveryData['file_path'] = $path;
            $deliveryData['file_name'] = $file->getClientOriginalName();
        }

        OrderDelivery::create($deliveryData);

        $order->update([
            'status' => Order::STATUS_DELIVERED,
            'delivered_at' => now(),
        ]);

        return redirect()->route('orders.show', $order)
            ->with('success', 'Delivery submitted! Waiting for buyer review.');
    }

    /**
     * Buyer requests a revision
     */
    public function requestRevision(Request $request, Order $order)
    {
        $user = $this->user();

        if (!$order->isBuyer($user)) {
            abort(403, 'Only the buyer can request revisions.');
        }

        if (!$order->canRequestRevision()) {
            return back()->with('error', 'Cannot request revision at this time.');
        }

        $validated = $request->validate([
            'message' => 'required|string|min:20',
        ]);

        $order->update([
            'status' => Order::STATUS_REVISION_REQUESTED,
        ]);

        return redirect()->route('orders.show', $order)
            ->with('info', 'Revision request sent to the seller.');
    }

    /**
     * Buyer marks order as complete
     */
    public function complete(Order $order)
    {
        if (!$order->isBuyer($this->user())) {
            abort(403, 'Only the buyer can mark as complete.');
        }

        if (!$order->canBeCompleted()) {
            return back()->with('error', 'This order cannot be completed yet.');
        }

        $order->update([
            'status' => Order::STATUS_COMPLETED,
            'completed_at' => now(),
        ]);

        return redirect()->route('orders.show', $order)
            ->with('success', 'Order completed! Thank you for using PeerSkill.');
    }

    /**
     * Cancel the order (before acceptance)
     */
    public function cancel(Request $request, Order $order)
    {
        $user = $this->user();

        if (!$order->isParticipant($user)) {
            abort(403, 'You cannot cancel this order.');
        }

        if (!$order->canBeCancelled()) {
            return back()->with('error', 'This order cannot be cancelled.');
        }

        $order->update([
            'status' => Order::STATUS_CANCELLED,
        ]);

        return redirect()->route('orders.index')
            ->with('info', 'Order has been cancelled.');
    }

    /**
     * Seller marks tutoring session as complete
     */
    public function completeTutoring(Order $order)
    {
        if (!$order->isSeller($this->user())) {
            abort(403, 'Only the seller can mark the session as complete.');
        }

        if (!$order->isTutoring()) {
            return back()->with('error', 'This is not a tutoring order.');
        }

        if (!in_array($order->status, [Order::STATUS_ACCEPTED])) {
            return back()->with('error', 'Session cannot be marked complete at this time.');
        }

        // Create a delivery record marking completion
        OrderDelivery::create([
            'order_id' => $order->id,
            'message' => 'Tutoring session completed.',
            'is_final' => true,
        ]);

        $order->update([
            'status' => Order::STATUS_DELIVERED,
            'delivered_at' => now(),
        ]);

        return redirect()->route('orders.show', $order)
            ->with('success', 'Session marked as complete! Waiting for buyer confirmation.');
    }
}
