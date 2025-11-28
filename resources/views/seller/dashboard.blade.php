@extends('layouts.app')

@section('title', 'Seller Dashboard - PeerSkill')

@section('content')
    <div class="container py-5">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row mb-4">
            <div class="col">
                <h2>Seller Dashboard</h2>
                <p class="text-muted">Welcome back, {{ Auth::user()->name }}!</p>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title text-muted">Active Gigs</h5>
                        <h2 class="mb-0">{{ $gigs->count() }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title text-muted">Orders in Progress</h5>
                        <h2 class="mb-0">0</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title text-muted">Completed Orders</h5>
                        <h2 class="mb-0">0</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title text-muted">Total Earnings</h5>
                        <h2 class="mb-0">$0</h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Seller Profile Card -->
        <div class="row mb-4">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Your Seller Profile</h5>
                        <span class="badge {{ $seller->is_active ? 'bg-success' : 'bg-secondary' }}">
                            {{ $seller->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <h6 class="text-muted mb-1">Business Name</h6>
                            <p class="mb-0">{{ $seller->business_name }}</p>
                        </div>
                        <div class="mb-3">
                            <h6 class="text-muted mb-1">Description</h6>
                            <p class="mb-0">{{ $seller->description }}</p>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <h6 class="text-muted mb-1">Major</h6>
                                <p class="mb-0">{{ $seller->major }}</p>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-muted mb-1">University</h6>
                                <p class="mb-0">{{ $seller->university }}</p>
                            </div>
                        </div>
                        @if ($seller->portfolio_url)
                            <div class="mb-3">
                                <h6 class="text-muted mb-1">Portfolio</h6>
                                <a href="{{ $seller->portfolio_url }}" target="_blank" class="text-decoration-none">
                                    {{ $seller->portfolio_url }}
                                </a>
                            </div>
                        @endif
                        <div class="mb-3">
                            <h6 class="text-muted mb-1">Skills</h6>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach ($seller->skills as $skill)
                                    <span class="badge bg-primary">{{ $skill }}</span>
                                @endforeach
                            </div>
                        </div>
                        <div class="mt-4">
                            <button class="btn btn-outline-primary" disabled>Edit Profile (Coming Soon)</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Quick Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('gigs.create') }}" class="btn btn-primary">Create New Gig</a>
                            <a href="{{ route('gigs.index') }}" class="btn btn-outline-secondary">Browse All
                                Gigs</a>
                            <button class="btn btn-outline-secondary" disabled>View Orders (Coming Soon)</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- My Gigs -->
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">My Gigs</h5>
                        <a href="{{ route('gigs.create') }}" class="btn btn-sm btn-primary">Create Gig</a>
                    </div>
                    <div class="card-body">
                        @if ($gigs->count() > 0)
                            <div class="row g-3">
                                @foreach ($gigs as $gig)
                                    <div class="col-md-4">
                                        <div class="card h-100">
                                            <a href="{{ route('gigs.show', $gig) }}"
                                                class="text-decoration-none text-dark">
                                                @if ($gig->images && count($gig->images) > 0)
                                                    <img src="{{ asset('storage/' . $gig->images[0]) }}"
                                                        class="card-img-top" alt="{{ $gig->title }}"
                                                        style="height: 150px; object-fit: cover;">
                                                @else
                                                    <div class="card-img-top d-flex align-items-center justify-content-center text-white"
                                                        style="height: 150px; background: linear-gradient(135deg, var(--peerskill-primary), var(--peerskill-primary-dark));">
                                                        <h6 class="text-center px-2">
                                                            {{ Str::limit($gig->title, 30) }}
                                                        </h6>
                                                    </div>
                                                @endif
                                                <div class="card-body">
                                                    <span class="badge bg-primary mb-2">{{ $gig->category->name }}</span>
                                                    <h6 class="card-title">{{ Str::limit($gig->title, 40) }}</h6>
                                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                                        <strong class="text-primary">
                                                            @if ($gig->max_price)
                                                                ${{ number_format($gig->min_price, 0) }}+
                                                            @else
                                                                ${{ number_format($gig->min_price, 0) }}
                                                            @endif
                                                        </strong>
                                                        <small class="text-muted">{{ $gig->delivery_days }}d
                                                            delivery</small>
                                                    </div>
                                                </div>
                                            </a>
                                            <div class="card-footer bg-white border-top">
                                                <div class="d-flex gap-2">
                                                    <a href="{{ route('gigs.edit', $gig) }}"
                                                        class="btn btn-sm btn-outline-primary flex-fill">Edit</a>
                                                    <form method="POST" action="{{ route('gigs.destroy', $gig) }}"
                                                        class="flex-fill" onsubmit="return confirm('Delete this gig?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="btn btn-sm btn-outline-danger w-100">Delete</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted text-center py-4">
                                You haven't created any gigs yet. <a href="{{ route('gigs.create') }}">Create your
                                    first gig</a> to start selling!
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
