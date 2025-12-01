@extends('layouts.app')

@section('title', 'Order: ' . $gig->title . ' - PeerSkill')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Gig Summary Card -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                @php
                                    $images = $gig->images ?? [];
                                    $firstImage = count($images) > 0 ? $images[0] : null;
                                @endphp
                                @if ($firstImage)
                                    <img src="{{ asset('storage/' . $firstImage) }}" alt="{{ $gig->title }}" class="rounded"
                                        style="width: 120px; height: 80px; object-fit: cover;">
                                @else
                                    <div class="bg-secondary rounded d-flex align-items-center justify-content-center"
                                        style="width: 120px; height: 80px;">
                                        <i class="bi bi-image text-white fs-4"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="col">
                                <h5 class="mb-1">{{ $gig->title }}</h5>
                                <div class="d-flex align-items-center text-muted small">
                                    @if ($gig->seller->user->avatar)
                                        <img src="{{ $gig->seller->user->avatar_url }}" class="rounded-circle me-2"
                                            style="width: 24px; height: 24px; object-fit: cover;">
                                    @else
                                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2"
                                            style="width: 24px; height: 24px; font-size: 0.7rem;">
                                            {{ $gig->seller->user->initials }}
                                        </div>
                                    @endif
                                    <span>{{ $gig->seller->user->name }}</span>
                                    <span class="mx-2">•</span>
                                    <span>{{ $gig->category->name }}</span>
                                </div>
                                <div class="mt-2">
                                    <span class="text-success fw-bold">
                                        Rp {{ number_format($gig->min_price, 0, ',', '.') }}
                                        @if ($gig->max_price && $gig->max_price != $gig->min_price)
                                            - Rp {{ number_format($gig->max_price, 0, ',', '.') }}
                                        @endif
                                    </span>
                                    <span class="text-muted small ms-2">
                                        <i class="bi bi-clock me-1"></i>{{ $gig->delivery_days }} Durasi Pengerjaan (Hari)
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Form -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom">
                        <h4 class="mb-0">
                            <i class="bi bi-cart-plus me-2"></i>Buat Pesanan Anda
                        </h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('orders.store', $gig) }}" method="POST">
                            @csrf

                            <!-- Order Type -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Tipe Pesanan</label>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="form-check card h-100">
                                            <div class="card-body">
                                                <input class="form-check-input" type="radio" name="type"
                                                    id="typeStandard" value="standard" checked
                                                    onchange="toggleTutoringFields()">
                                                <label class="form-check-label w-100" for="typeStandard">
                                                    <div class="fw-semibold">
                                                        <i class="bi bi-file-earmark-text me-1"></i> Pesanan Standar
                                                    </div>
                                                    <small class="text-muted">
                                                        Menerima hasil kerja (file, dokumen, desain, dll.)
                                                    </small>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    @if ($gig->allows_tutoring)
                                        <div class="col-md-6">
                                            <div class="form-check card h-100">
                                                <div class="card-body">
                                                    <input class="form-check-input" type="radio" name="type"
                                                        id="typeTutoring" value="tutoring"
                                                        {{ old('type') === 'tutoring' ? 'checked' : '' }}
                                                        onchange="toggleTutoringFields()">
                                                    <label class="form-check-label w-100" for="typeTutoring">
                                                        <div class="fw-semibold">
                                                            <i class="bi bi-mortarboard me-1"></i> Sesi Bimbingan
                                                        </div>
                                                        <small class="text-muted">
                                                            Jadwalkan sesi bimbingan/mentoring secara langsung
                                                        </small>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                @error('type')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Requirements -->
                            <div class="mb-4">
                                <label for="requirements" class="form-label fw-semibold">
                                    Apa yang Anda butuhkan?
                                    <span class="text-danger">*</span>
                                </label>
                                <textarea class="form-control @error('requirements') is-invalid @enderror" id="requirements" name="requirements"
                                    rows="6"
                                    placeholder="Please describe in detail:
• What you need done
• Expected outcome/deliverables
• Any specific requirements or preferences
• Deadline preferences (if any)
• Relevant background information"
                                    required>{{ old('requirements') }}</textarea>
                                @error('requirements')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Berikan detail selengkap mungkin. Penjual akan menentukan harga berdasarkan kebutuhan Anda.
                                </div>
                            </div>

                            <!-- Tutoring Fields (hidden by default) -->
                            <div id="tutoringFields" style="display: none;">
                                <hr class="my-4">
                                <h5 class="mb-3">
                                    <i class="bi bi-calendar-event me-2"></i>Jadwal Bimbingan
                                </h5>

                                <!-- Topic -->
                                <div class="mb-4">
                                    <label for="topic" class="form-label fw-semibold">Topik/Subjek</label>
                                    <input type="text" class="form-control @error('topic') is-invalid @enderror"
                                        id="topic" name="topic"
                                        placeholder="e.g., Data Structures, Calculus, JavaScript basics..."
                                        value="{{ old('topic') }}">
                                    @error('topic')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Proposed Time Slots --> 
                                <div class="mb-4">
                                    <label class="form-label fw-semibold">
                                        Slot Waktu Pilihan
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div id="slotsContainer">
                                        <div class="input-group mb-2 slot-row">
                                            <input type="datetime-local" class="form-control" name="proposed_slots[]"
                                                min="{{ now()->addHours(24)->format('Y-m-d\TH:i') }}">
                                            <button type="button" class="btn btn-outline-danger"
                                                onclick="removeSlot(this)" style="display: none;">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="addSlot()">
                                        <i class="bi bi-plus me-1"></i>Tambahkan Slot Lain
                                    </button>
                                    @error('proposed_slots')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        Sarankan beberapa slot waktu agar Penjual dapat memilih salah satu yang sesuai.
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <!-- Pricing Note -->
                            <div class="alert alert-info d-flex align-items-start">
                                <i class="bi bi-info-circle-fill me-2 mt-1"></i>
                                <div>
                                    <strong>Cara kerja penentuan harga:</strong>
                                    <p class="mb-0 small">
                                        Setelah Anda mengirimkan pesanan ini, Penjual akan meninjau kebutuhan Anda dan mengirimkan penawaran harga.
                                        Anda kemudian dapat menerima atau menolak penawaran harga tersebut. Pembayaran tidak diperlukan sampai Anda menerima.
                                    </p>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="bi bi-send me-2"></i>Kirim Permintaan Pesanan
                                </button>
                                <a href="{{ route('gigs.show', $gig) }}" class="btn btn-outline-secondary">
                                    Batal
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function toggleTutoringFields() {
            const isTutoring = document.getElementById('typeTutoring')?.checked ?? false;
            const tutoringFields = document.getElementById('tutoringFields');

            if (tutoringFields) {
                tutoringFields.style.display = isTutoring ? 'block' : 'none';

                // Toggle required attribute on tutoring-specific inputs
                const topicInput = document.getElementById('topic');
                const slotInputs = document.querySelectorAll('input[name="proposed_slots[]"]');

                slotInputs.forEach(input => {
                    if (isTutoring) {
                        input.setAttribute('required', 'required');
                    } else {
                        input.removeAttribute('required');
                    }
                });
            }
        }

        function addSlot() {
            const container = document.getElementById('slotsContainer');
            const rows = container.querySelectorAll('.slot-row');

            // Show remove buttons on all rows if we have more than one
            if (rows.length >= 1) {
                rows.forEach(row => {
                    row.querySelector('button').style.display = 'block';
                });
            }

            const minDate = new Date();
            minDate.setHours(minDate.getHours() + 24);
            const minDateStr = minDate.toISOString().slice(0, 16);

            const newRow = document.createElement('div');
            newRow.className = 'input-group mb-2 slot-row';
            newRow.innerHTML = `
            <input type="datetime-local"
                   class="form-control"
                   name="proposed_slots[]"
                   min="${minDateStr}"
                   required>
            <button type="button" class="btn btn-outline-danger" onclick="removeSlot(this)">
                <i class="bi bi-trash"></i>
            </button>
        `;
            container.appendChild(newRow);
        }

        function removeSlot(btn) {
            const container = document.getElementById('slotsContainer');
            btn.closest('.slot-row').remove();

            const rows = container.querySelectorAll('.slot-row');
            // Hide remove button if only one slot left
            if (rows.length === 1) {
                rows[0].querySelector('button').style.display = 'none';
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            toggleTutoringFields();
        });
    </script>
@endpush
