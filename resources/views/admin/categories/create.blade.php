@extends('layouts.app')

@section('content')
<div class="py-5 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 fw-bold">Add New Category</h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('admin.categories.store') }}" method="POST">
                            @csrf
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Category Name</label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Contoh: Artificial Intelligence" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
    <label class="form-label fw-bold">Description <span class="text-danger">*</span></label>
    
    <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3" placeholder="Jelaskan kategori ini..." required></textarea>
    
    @error('description')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror
</div>

                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ route('admin.categories.index') }}" class="btn btn-light text-muted">Cancel</a>
                                <button type="submit" class="btn btn-primary fw-bold px-4">Save Category</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection