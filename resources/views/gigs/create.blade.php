@extends('layouts.app')

@section('title', 'Create Gig - PeerSkill')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div class="card shadow-sm">
                    <div class="card-body p-5">
                        <h2 class="mb-4">Create a New Gig</h2>
                        <p class="text-muted mb-4">Fill out the details below to create your service offering.</p>

                        <form method="POST" action="{{ route('gigs.store') }}" enctype="multipart/form-data">
                            @csrf

                            <!-- Title -->
                            <div class="mb-3">
                                <label for="title" class="form-label">Gig Title <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror"
                                    id="title" name="title" value="{{ old('title') }}" placeholder="I will..."
                                    required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Category -->
                            <div class="mb-3">
                                <label for="category_id" class="form-label">Category <span
                                        class="text-danger">*</span></label>
                                <select class="form-select @error('category_id') is-invalid @enderror" id="category_id"
                                    name="category_id" required>
                                    <option value="">Select a category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="mb-3">
                                <label for="description" class="form-label">Description <span
                                        class="text-danger">*</span></label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                    rows="6" required>{{ old('description') }}</textarea>
                                <div class="form-text">Describe what you're offering and what buyers will receive</div>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Price Range -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="min_price" class="form-label">Minimum Price ($) <span
                                            class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('min_price') is-invalid @enderror"
                                        id="min_price" name="min_price" value="{{ old('min_price') }}" min="5"
                                        step="0.01" required>
                                    @error('min_price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="max_price" class="form-label">Maximum Price ($)</label>
                                    <input type="number" class="form-control @error('max_price') is-invalid @enderror"
                                        id="max_price" name="max_price" value="{{ old('max_price') }}" min="5"
                                        step="0.01">
                                    <div class="form-text">Leave empty if price is fixed</div>
                                    @error('max_price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Delivery Time -->
                            <div class="mb-3">
                                <label for="delivery_days" class="form-label">Delivery Time (Days) <span
                                        class="text-danger">*</span></label>
                                <select class="form-select @error('delivery_days') is-invalid @enderror" id="delivery_days"
                                    name="delivery_days" required>
                                    <option value="">Select delivery time</option>
                                    @foreach ($deliveryPresets as $days)
                                        <option value="{{ $days }}"
                                            {{ old('delivery_days') == $days ? 'selected' : '' }}>
                                            {{ $days }} {{ $days == 1 ? 'day' : 'days' }}
                                        </option>
                                    @endforeach
                                    <option value="custom"
                                        {{ old('delivery_days') && !in_array(old('delivery_days'), $deliveryPresets) ? 'selected' : '' }}>
                                        Custom</option>
                                </select>
                                <input type="number" class="form-control mt-2 d-none" id="custom_delivery_days"
                                    name="custom_delivery_days" min="1" max="365"
                                    placeholder="Enter custom days">
                                @error('delivery_days')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Tutoring Option -->
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="allows_tutoring"
                                        name="allows_tutoring" value="1"
                                        {{ old('allows_tutoring') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="allows_tutoring">
                                        I offer tutoring sessions for this service
                                    </label>
                                </div>
                            </div>

                            <!-- Images -->
                            <div class="mb-3">
                                <label for="images" class="form-label">Gig Images</label>
                                <input type="file" class="form-control @error('images.*') is-invalid @enderror"
                                    id="images" name="images[]" accept="image/*" multiple>
                                <div class="form-text">Upload up to 5 images (max 2MB each). Leave empty to use default
                                    placeholder.</div>
                                @error('images.*')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Attachments -->
                            <div class="mb-4">
                                <label for="attachments" class="form-label">Portfolio Attachments</label>
                                <input type="file" class="form-control @error('attachments.*') is-invalid @enderror"
                                    id="attachments" name="attachments[]" accept=".pdf,.jpg,.jpeg,.png" multiple>
                                <div class="form-text">Upload portfolio files (PDF or images, max 5MB each)</div>
                                @error('attachments.*')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex gap-3">
                                <button type="submit" class="btn btn-primary">Create Gig</button>
                                <a href="{{ route('seller.dashboard') }}" class="btn btn-outline-secondary">Cancel</a>
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
        // Handle custom delivery days
        const deliverySelect = document.getElementById('delivery_days');
        const customInput = document.getElementById('custom_delivery_days');

        deliverySelect.addEventListener('change', function() {
            if (this.value === 'custom') {
                customInput.classList.remove('d-none');
                customInput.required = true;
            } else {
                customInput.classList.add('d-none');
                customInput.required = false;
                customInput.value = '';
            }
        });

        // Set delivery_days value from custom input when submitting
        document.querySelector('form').addEventListener('submit', function(e) {
            if (deliverySelect.value === 'custom' && customInput.value) {
                deliverySelect.value = customInput.value;
            }
        });

        // Limit file uploads
        document.getElementById('images').addEventListener('change', function() {
            if (this.files.length > 5) {
                alert('You can only upload up to 5 images');
                this.value = '';
            }
        });
    </script>
@endpush
