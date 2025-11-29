@extends('layouts.app')

@section('title', 'Order #' . $order->id . ' - PeerSkill')

@php
    $isBuyer = $order->isBuyer(auth()->user());
    $isSeller = $order->isSeller(auth()->user());
    $otherParty = $isBuyer ? $order->seller : $order->buyer;
@endphp

@section('content')
    {{-- STYLE BINTANG REVIEW --}}
    @push('styles')
        <style>
            /* Styling untuk Rating Bintang "Wah" */
            .rate {
                float: left;
                height: 46px;
                padding: 0 10px;
            }

            .rate:not(:checked)>input {
                position: absolute;
                top: -9999px;
            }

            .rate:not(:checked)>label {
                float: right;
                width: 1em;
                overflow: hidden;
                white-space: nowrap;
                cursor: pointer;
                font-size: 30px;
                color: #ccc;
            }

            .rate:not(:checked)>label:before {
                content: '★ ';
            }

            .rate>input:checked~label {
                color: #ffc700;
            }

            .rate:not(:checked)>label:hover,
            .rate:not(:checked)>label:hover~label {
                color: #deb217;
            }

            .rate>input:checked+label:hover,
            .rate>input:checked+label:hover~label,
            .rate>input:checked~label:hover,
            .rate>input:checked~label:hover~label,
            .rate>label:hover~input:checked~label {
                color: #c59b08;
            }
        </style>
    @endpush

    <div class="container py-5">
        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- Order Header -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                            <div>
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <span class="badge {{ $order->status_badge_class }} fs-6">
                                        {{ $order->status_label }}
                                    </span>
                                    @if ($order->isTutoring())
                                        <span class="badge bg-info text-dark">
                                            <i class="bi bi-mortarboard me-1"></i>Tutoring
                                        </span>
                                    @endif
                                </div>
                                <h4 class="mb-1">Order #{{ $order->id }}</h4>
                                <p class="text-muted mb-0">
                                    Placed {{ $order->created_at->format('M j, Y \a\t g:i A') }}
                                </p>
                            </div>
                            <div class="text-end">
                                @if ($order->price)
                                    <div class="h3 text-success mb-0">
                                        Rp {{ number_format($order->price, 0, ',', '.') }}
                                    </div>
                                    @if ($order->delivery_days)
                                        <small class="text-muted">
                                            <i class="bi bi-clock me-1"></i>{{ $order->delivery_days }} days delivery
                                        </small>
                                    @endif
                                @else
                                    <span class="text-muted">Awaiting Quote</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                @if ($order->status === 'completed' && !$order->review)
                    <div class="card border-0 shadow-sm mb-4 bg-success bg-opacity-10">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="fw-bold text-success mb-1"><i class="bi bi-check-circle-fill me-2"></i>Pesanan
                                    Selesai!</h5>
                                <p class="mb-0 text-muted small">Pekerjaan telah diterima. Yuk, beri rating untuk Seller!
                                </p>
                            </div>
                            <button type="button" class="btn btn-warning fw-bold text-dark shadow-sm"
                                data-bs-toggle="modal" data-bs-target="#reviewModal">
                                <i class="bi bi-star-fill me-1"></i> Beri Ulasan
                            </button>
                        </div>
                    </div>
                @endif

                @if ($order->review)
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body">
                            <div class="d-flex align-items-start">

                                {{-- LOGIKA AVATAR: Selalu tampilkan foto SI PENULIS REVIEW (Buyer), bukan user yang login --}}
                                @php
                                    $reviewer = $order->buyer; // Karena yang review pasti buyer
                                @endphp

                                @if ($reviewer->avatar)
                                    <img src="{{ $reviewer->avatar_url }}" class="rounded-circle me-3" width="50"
                                        height="50" style="object-fit: cover;">
                                @else
                                    <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center me-3"
                                        style="width: 50px; height: 50px;">
                                        {{ substr($reviewer->name, 0, 1) }}
                                    </div>
                                @endif

                                <div>
                                    {{-- LOGIKA JUDUL --}}
                                    @if ($isBuyer)
                                        <h6 class="fw-bold mb-1">Ulasan Anda</h6>
                                    @else
                                        <h6 class="fw-bold mb-1">Ulasan dari {{ $reviewer->name }}</h6>
                                    @endif

                                    <div class="text-warning mb-2">
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($i <= $order->review->rating)
                                                <i class="bi bi-star-fill"></i>
                                            @else
                                                <i class="bi bi-star"></i>
                                            @endif
                                        @endfor
                                        <span class="text-dark fw-bold ms-2 small">{{ $order->review->rating }}.0</span>
                                    </div>
                                    <p class="text-muted mb-0">"{{ $order->review->comment }}"</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Gig Info -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="bi bi-file-earmark me-2"></i>Gig Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                @php
                                    $images = $order->gig->images ?? [];
                                    $firstImage = count($images) > 0 ? $images[0] : null;
                                @endphp
                                @if ($firstImage)
                                    <img src="{{ asset('storage/' . $firstImage) }}" alt="{{ $order->gig->title }}"
                                        class="rounded" style="width: 100px; height: 70px; object-fit: cover;">
                                @else
                                    <div class="bg-secondary rounded d-flex align-items-center justify-content-center"
                                        style="width: 100px; height: 70px;">
                                        <i class="bi bi-image text-white"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="col">
                                <h6 class="mb-1">
                                    <a href="{{ route('gigs.show', $order->gig) }}" class="text-decoration-none">
                                        {{ $order->gig->title }}
                                    </a>
                                </h6>
                                <small class="text-muted">
                                    {{ $order->gig->category->name }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Requirements -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="bi bi-list-check me-2"></i>Requirements</h5>
                    </div>
                    <div class="card-body">
                        <div class="bg-light p-3 rounded">
                            {!! nl2br(e($order->requirements)) !!}
                        </div>
                    </div>
                </div>

                <!-- Tutoring Schedule (if applicable) -->
                @if ($order->isTutoring() && $order->tutoringSchedule)
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white">
                            <h5 class="mb-0"><i class="bi bi-calendar-event me-2"></i>Tutoring Schedule</h5>
                        </div>
                        <div class="card-body">
                            @if ($order->tutoringSchedule->topic)
                                <p class="mb-3">
                                    <strong>Topic:</strong> {{ $order->tutoringSchedule->topic }}
                                </p>
                            @endif

                            @if ($order->tutoringSchedule->confirmed_slot)
                                <div class="alert alert-success d-flex align-items-center">
                                    <i class="bi bi-check-circle-fill me-2"></i>
                                    <div>
                                        <strong>Confirmed Session:</strong><br>
                                        {{ $order->tutoringSchedule->formatted_confirmed_slot }}
                                    </div>
                                </div>
                                @if ($order->tutoringSchedule->external_link)
                                    <a href="{{ $order->tutoringSchedule->external_link }}" target="_blank"
                                        class="btn btn-primary">
                                        <i class="bi bi-camera-video me-1"></i>Join Session
                                    </a>
                                @endif
                            @else
                                <p class="mb-2"><strong>Proposed Time Slots:</strong></p>
                                <ul class="list-group">
                                    @foreach ($order->tutoringSchedule->proposed_slots ?? [] as $slot)
                                        <li class="list-group-item">
                                            <i class="bi bi-clock me-2"></i>
                                            {{ \Carbon\Carbon::parse($slot)->format('l, F j, Y \a\t g:i A') }}
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Seller Notes (with quote) -->
                @if ($order->seller_notes)
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white">
                            <h5 class="mb-0"><i class="bi bi-chat-quote me-2"></i>Seller's Notes</h5>
                        </div>
                        <div class="card-body">
                            <div class="bg-light p-3 rounded">
                                {!! nl2br(e($order->seller_notes)) !!}
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Deliveries -->
                @if ($order->deliveries->count() > 0)
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white">
                            <h5 class="mb-0"><i class="bi bi-box-seam me-2"></i>Deliveries</h5>
                        </div>
                        <div class="card-body">
                            @foreach ($order->deliveries as $delivery)
                                <div class="border rounded p-3 mb-3 {{ $delivery->is_final ? 'border-success' : '' }}">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <small class="text-muted">
                                            {{ $delivery->created_at->format('M j, Y \a\t g:i A') }}
                                        </small>
                                        @if ($delivery->is_final)
                                            <span class="badge bg-success">Final Delivery</span>
                                        @endif
                                    </div>
                                    <p class="mb-2">{!! nl2br(e($delivery->message)) !!}</p>
                                    @if ($delivery->hasFile())
                                        <a href="{{ $delivery->file_url }}" download="{{ $delivery->file_name }}"
                                            class="btn btn-outline-primary btn-sm">
                                            <i class="bi bi-download me-1"></i>
                                            {{ $delivery->file_name ?? 'Download File' }}
                                        </a>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Chat Button -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body text-center py-4">
                        <i class="bi bi-chat-dots display-6 text-muted mb-3 d-block"></i>
                        <h5 class="mb-2">Need to discuss something?</h5>
                        <p class="text-muted small mb-3">
                            Chat directly with {{ $isBuyer ? 'the seller' : 'the buyer' }} about this order
                        </p>
                        <button class="btn btn-outline-primary" disabled>
                            <i class="bi bi-chat-left-text me-2"></i>
                            Chat with {{ $isBuyer ? 'Seller' : 'Buyer' }} (Coming Soon)
                        </button>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Other Party Info -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">{{ $isBuyer ? 'Seller' : 'Buyer' }}</h5>
                    </div>
                    <div class="card-body text-center">
                        <a href="{{ route('profile.show', $otherParty) }}">
                            @if ($otherParty->avatar)
                                <img src="{{ $otherParty->avatar_url }}" alt="{{ $otherParty->name }}"
                                    class="rounded-circle mb-3" style="width: 80px; height: 80px; object-fit: cover;">
                            @else
                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto mb-3"
                                    style="width: 80px; height: 80px; font-size: 1.5rem;">
                                    {{ $otherParty->initials }}
                                </div>
                            @endif
                        </a>
                        <h6 class="mb-1">{{ $otherParty->name }}</h6>
                        @if (!$isBuyer && $otherParty->seller)
                            <small class="text-muted">{{ $otherParty->seller->business_name }}</small>
                        @endif
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Actions</h5>
                    </div>
                    <div class="card-body">
                        @if ($isSeller)
                            {{-- Seller Actions --}}
                            @if ($order->canBeQuoted())
                                <button class="btn btn-primary w-100 mb-2" data-bs-toggle="modal"
                                    data-bs-target="#quoteModal">
                                    <i class="bi bi-currency-dollar me-1"></i>Send Quote
                                </button>
                            @endif

                            @if ($order->canBeDelivered())
                                <button class="btn btn-success w-100 mb-2" data-bs-toggle="modal"
                                    data-bs-target="#deliverModal">
                                    @if ($order->isTutoring())
                                        <i class="bi bi-check-circle me-1"></i>Mark Session Complete
                                    @else
                                        <i class="bi bi-box-seam me-1"></i>Submit Delivery
                                    @endif
                                </button>
                            @endif
                        @else
                            {{-- Buyer Actions --}}
                            @if ($order->canBeAccepted())
                                <form action="{{ route('orders.accept', $order) }}" method="POST" class="mb-2">
                                    @csrf
                                    <button type="submit" class="btn btn-success w-100">
                                        <i class="bi bi-check-lg me-1"></i>Accept Quote
                                    </button>
                                </form>
                                <form action="{{ route('orders.decline', $order) }}" method="POST" class="mb-2">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-danger w-100">
                                        <i class="bi bi-x-lg me-1"></i>Decline Quote
                                    </button>
                                </form>
                            @endif

                            @if ($order->canRequestRevision())
                                <button class="btn btn-warning w-100 mb-2" data-bs-toggle="modal"
                                    data-bs-target="#revisionModal">
                                    <i class="bi bi-arrow-repeat me-1"></i>Request Revision
                                </button>
                            @endif

                            @if ($order->canBeCompleted())
                                <form action="{{ route('orders.complete', $order) }}" method="POST" class="mb-2">
                                    @csrf
                                    <button type="submit" class="btn btn-success w-100">
                                        <i class="bi bi-check-circle me-1"></i>Mark as Complete
                                    </button>
                                </form>
                            @endif
                        @endif

                        {{-- Cancel (both) --}}
                        @if ($order->canBeCancelled())
                            <button class="btn btn-outline-danger w-100" data-bs-toggle="modal"
                                data-bs-target="#cancelModal">
                                <i class="bi bi-x-circle me-1"></i>Cancel Order
                            </button>
                        @endif

                        {{-- Back to orders --}}
                        <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary w-100 mt-3">
                            <i class="bi bi-arrow-left me-1"></i>Back to Orders
                        </a>

                        <div class="mt-4 pt-3 border-top text-center">
                            <button type="button" class="btn btn-link text-danger text-decoration-none btn-sm"
                                data-bs-toggle="modal" data-bs-target="#reportModal">
                                <i class="bi bi-flag-fill me-1"></i> Laporkan Masalah
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Order Timeline -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i>Timeline</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0">
                            <li class="mb-3">
                                <div class="d-flex">
                                    <div class="me-3">
                                        <span class="badge rounded-pill bg-success"><i class="bi bi-check"></i></span>
                                    </div>
                                    <div>
                                        <strong>Order Placed</strong>
                                        <br><small
                                            class="text-muted">{{ $order->created_at->format('M j, Y g:i A') }}</small>
                                    </div>
                                </div>
                            </li>
                            @if ($order->quoted_at)
                                <li class="mb-3">
                                    <div class="d-flex">
                                        <div class="me-3">
                                            <span class="badge rounded-pill bg-success"><i class="bi bi-check"></i></span>
                                        </div>
                                        <div>
                                            <strong>Quote Sent</strong>
                                            <br><small
                                                class="text-muted">{{ $order->quoted_at->format('M j, Y g:i A') }}</small>
                                        </div>
                                    </div>
                                </li>
                            @endif
                            @if ($order->accepted_at)
                                <li class="mb-3">
                                    <div class="d-flex">
                                        <div class="me-3">
                                            <span class="badge rounded-pill bg-success"><i class="bi bi-check"></i></span>
                                        </div>
                                        <div>
                                            <strong>Quote Accepted</strong>
                                            <br><small
                                                class="text-muted">{{ $order->accepted_at->format('M j, Y g:i A') }}</small>
                                        </div>
                                    </div>
                                </li>
                            @endif
                            @if ($order->delivered_at)
                                <li class="mb-3">
                                    <div class="d-flex">
                                        <div class="me-3">
                                            <span class="badge rounded-pill bg-success"><i class="bi bi-check"></i></span>
                                        </div>
                                        <div>
                                            <strong>Delivered</strong>
                                            <br><small
                                                class="text-muted">{{ $order->delivered_at->format('M j, Y g:i A') }}</small>
                                        </div>
                                    </div>
                                </li>
                            @endif
                            @if ($order->completed_at)
                                <li class="mb-0">
                                    <div class="d-flex">
                                        <div class="me-3">
                                            <span class="badge rounded-pill bg-success"><i class="bi bi-check"></i></span>
                                        </div>
                                        <div>
                                            <strong>Completed</strong>
                                            <br><small
                                                class="text-muted">{{ $order->completed_at->format('M j, Y g:i A') }}</small>
                                        </div>
                                    </div>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Quote Modal (Seller) --}}
    @if ($isSeller && $order->canBeQuoted())
        <div class="modal fade" id="quoteModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('orders.quote', $order) }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Send Quote</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Price (Rp)</label>
                                <input type="number" class="form-control" name="price" min="1"
                                    value="{{ $order->gig->min_price }}" required>
                                <div class="form-text">
                                    Gig range: Rp {{ number_format($order->gig->min_price, 0, ',', '.') }}
                                    @if ($order->gig->max_price)
                                        - Rp {{ number_format($order->gig->max_price, 0, ',', '.') }}
                                    @endif
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Delivery Time (days)</label>
                                <input type="number" class="form-control" name="delivery_days" min="1"
                                    value="{{ $order->gig->delivery_days }}" required>
                            </div>
                            @if ($order->isTutoring())
                                <div class="mb-3">
                                    <label class="form-label">Confirm Time Slot</label>
                                    <select class="form-select" name="confirmed_slot" required>
                                        <option value="">Select a slot...</option>
                                        @foreach ($order->tutoringSchedule->proposed_slots ?? [] as $slot)
                                            <option value="{{ $slot }}">
                                                {{ \Carbon\Carbon::parse($slot)->format('l, F j, Y \a\t g:i A') }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Meeting Link (optional)</label>
                                    <input type="url" class="form-control" name="external_link"
                                        placeholder="https://zoom.us/j/... or https://meet.google.com/...">
                                </div>
                            @endif
                            <div class="mb-3">
                                <label class="form-label">Notes for Buyer (optional)</label>
                                <textarea class="form-control" name="seller_notes" rows="3"
                                    placeholder="Add any notes about pricing, timeline, or questions..."></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Send Quote</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- Deliver Modal (Seller) --}}
    @if ($isSeller && $order->canBeDelivered())
        <div class="modal fade" id="deliverModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    @if ($order->isTutoring())
                        <form action="{{ route('orders.complete-tutoring', $order) }}" method="POST">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title">Mark Session Complete</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <p>Confirm that the tutoring session has been completed?</p>
                                <p class="text-muted small">
                                    The buyer will be notified and can mark the order as complete or request follow-up.
                                </p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-success">Confirm Complete</button>
                            </div>
                        </form>
                    @else
                        <form action="{{ route('orders.deliver', $order) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title">Submit Delivery</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Delivery Message</label>
                                    <textarea class="form-control" name="message" rows="4" placeholder="Describe what you're delivering..."
                                        required></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Upload File (optional)</label>
                                    <input type="file" class="form-control" name="file">
                                    <div class="form-text">Max 50MB</div>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="is_final" value="1"
                                        id="isFinal">
                                    <label class="form-check-label" for="isFinal">
                                        This is the final delivery
                                    </label>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-success">Submit Delivery</button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    @endif

    {{-- Revision Modal (Buyer) --}}
    @if ($isBuyer && $order->canRequestRevision())
        <div class="modal fade" id="revisionModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('orders.revision', $order) }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Request Revision</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">What needs to be changed?</label>
                                <textarea class="form-control" name="message" rows="4" placeholder="Describe the changes you need..."
                                    required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-warning">Request Revision</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- Cancel Modal --}}
    @if ($order->canBeCancelled())
        <div class="modal fade" id="cancelModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('orders.cancel', $order) }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Cancel Order</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p class="text-danger">Are you sure you want to cancel this order?</p>
                            <div class="mb-3">
                                <label class="form-label">Reason (optional)</label>
                                <textarea class="form-control" name="reason" rows="3" placeholder="Why are you cancelling?"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Keep Order</button>
                            <button type="submit" class="btn btn-danger">Cancel Order</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <div class="modal fade" id="reviewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-0 bg-primary text-white">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-award me-2"></i>Beri Ulasan
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <form action="{{ route('reviews.store', $order->id) }}" method="POST">
                    @csrf
                    <div class="modal-body p-4 text-center">
                        <h5 class="mb-3">Bagaimana hasil kerja Seller?</h5>
                        <p class="text-muted small mb-4">Klik bintang di bawah untuk memberikan rating</p>

                        <div class="d-flex justify-content-center mb-4">
                            <div class="rate">
                                <input type="radio" id="star5" name="rating" value="5" required />
                                <label for="star5" title="Sempurna">5 stars</label>
                                <input type="radio" id="star4" name="rating" value="4" />
                                <label for="star4" title="Sangat Bagus">4 stars</label>
                                <input type="radio" id="star3" name="rating" value="3" />
                                <label for="star3" title="Bagus">3 stars</label>
                                <input type="radio" id="star2" name="rating" value="2" />
                                <label for="star2" title="Buruk">2 stars</label>
                                <input type="radio" id="star1" name="rating" value="1" />
                                <label for="star1" title="Sangat Buruk">1 star</label>
                            </div>
                        </div>

                        <div class="form-floating">
                            <textarea class="form-control" name="comment" placeholder="Tulis pengalamanmu di sini" id="floatingTextarea2"
                                style="height: 100px"></textarea>
                            <label for="floatingTextarea2">Ceritakan pengalamanmu (Opsional)</label>
                        </div>
                    </div>

                    <div class="modal-footer border-0 pt-0 justify-content-center pb-4">
                        <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary fw-bold px-5">Kirim Ulasan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="reportModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('orders.report.store', $order->id) }}" method="POST">
                    @csrf
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold text-danger">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>Laporkan Pesanan
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="alert alert-secondary small mb-3">
                            Laporkan masalah yang Anda alami dengan pesanan ini. Tim kami akan meninjau laporan Anda dan
                            mengambil tindakan yang sesuai.
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Alasan Pelaporan</label>
                            <select class="form-select" name="reason" required>
                                <option value="">Pilih alasan...</option>

                                @if ($isBuyer)
                                    {{-- Opsi untuk BUYER (Melaporkan Seller) --}}
                                    <option value="seller_not_responsive">Seller Tidak Responsif</option>
                                    <option value="rude_behavior">Perilaku Kasar/Tidak Sopan</option>
                                    <option value="scam_attempt">Indikasi Penipuan</option>
                                    <option value="incomplete_delivery">Hasil Kerja Tidak Sesuai</option>
                                @else
                                    {{-- Opsi untuk SELLER (Melaporkan Buyer) --}}
                                    <option value="buyer_unresponsive">Buyer Tidak Responsif</option>
                                    <option value="rude_behavior">Perilaku Kasar/Tidak Sopan</option>
                                    <option value="unreasonable_demands">Permintaan Tidak Masuk Akal</option>
                                    <option value="spam_scam">Spam / Percobaan Penipuan</option>
                                @endif

                                {{-- Opsi Umum --}}
                                <option value="other">Lainnya</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Detail Masalah</label>
                            <textarea class="form-control" name="description" rows="4" placeholder="Ceritakan kronologi masalahnya..."
                                required></textarea>
                        </div>
                    </div>

                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger fw-bold">Kirim Laporan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
