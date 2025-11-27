<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Dashboard - PeerSkill</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    @include('components.navbar')

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
                        <h2 class="mb-0">0</h2>
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
                            <button class="btn btn-primary" disabled>Create New Gig</button>
                            <button class="btn btn-outline-secondary" disabled>View Orders</button>
                            <button class="btn btn-outline-secondary" disabled>Messages</button>
                            <button class="btn btn-outline-secondary" disabled>Analytics</button>
                        </div>
                        <hr class="my-3">
                        <small class="text-muted">These features are coming soon!</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Recent Activity</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted text-center py-4">No recent activity to display.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('components.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
