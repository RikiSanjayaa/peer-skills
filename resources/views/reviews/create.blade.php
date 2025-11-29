@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-5 text-center">
                    
                    <h3 class="fw-bold mb-3">Bagaimana pesanan Anda?</h3>
                    <p class="text-muted mb-4">
                        Berikan ulasan untuk Gig: <br>
                        <strong>{{ $order->gig->title }}</strong>
                    </p>

                    <form action="{{ route('reviews.store', $order->id) }}" method="POST">
                        @csrf
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold d-block">Rating</label>
                            <div class="btn-group" role="group" aria-label="Rating">
                                @for($i = 1; $i <= 5; $i++)
                                    <input type="radio" class="btn-check" name="rating" id="star{{ $i }}" value="{{ $i }}" required>
                                    <label class="btn btn-outline-warning" for="star{{ $i }}">{{ $i }} <i class="bi bi-star-fill"></i></label>
                                @endfor
                            </div>
                            @error('rating')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4 text-start">
                            <label class="form-label fw-bold">Ulasan Anda</label>
                            <textarea name="comment" class="form-control" rows="4" placeholder="Ceritakan pengalaman Anda bekerja dengan seller ini..."></textarea>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary fw-bold">Kirim Ulasan</button>
                            <a href="{{ route('orders.show', $order->id) }}" class="btn btn-link text-muted mt-2">Batal</a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection