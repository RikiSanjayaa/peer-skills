@extends('layouts.admin')

@section('content')
    <div class="py-4">
        <div class="container-fluid px-4">
            <div class="row justify-content-center">
                <div class="col-md-6">

                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0 fw-bold">Edit Category: {{ $category->name }}</h5>
                        </div>
                        <div class="card-body p-4">
                            <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
                                @csrf
                                @method('PUT') <div class="mb-3">
                                    <label class="form-label fw-bold">Category Name</label>
                                    <input type="text" name="name" class="form-control"
                                        value="{{ old('name', $category->name) }}" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Description <span class="text-danger">*</span></label>
                                    <textarea name="description" class="form-control" rows="3" required>{{ old('description', $category->description) }}</textarea>
                                </div>

                                <div class="d-flex justify-content-between mt-4">
                                    <a href="{{ route('admin.categories.index') }}"
                                        class="btn btn-light text-muted">Cancel</a>
                                    <button type="submit" class="btn btn-warning fw-bold px-4">Update Category</button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
