<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin Panel - PeerSkill')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            background-color: #f8f9fa;
        }
        .admin-content {
            min-height: calc(100vh - 56px);
        }
    </style>

    @stack('styles')
</head>

<body>

    <div id="app">
        
        @include('components.admin-sidebar')

        {{-- 
           Trik: Kita bungkus navbar dalam div agar bisa menyelipkan tombol hamburger 
           di sebelah kirinya, ATAU kita edit file components.navbar sedikit saja.
           
           Untuk cara paling aman tanpa merusak kode temanmu, kita pakai Navbar bawaan,
           tapi kita inject tombol Hamburger di file components/navbar.blade.php (lihat langkah 2 di bawah).
        --}}
        @include('components.navbar')

        <div class="admin-content">
            <div class="container-fluid px-4 py-3">
                
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('info'))
                    <div class="alert alert-info alert-dismissible fade show shadow-sm" role="alert">
                        <i class="bi bi-info-circle-fill me-2"></i> {{ session('info') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
            </div>

            @yield('content')
        </div>

        {{-- Admin Footer --}}
        <footer class="bg-dark text-light py-3 mt-auto">
            <div class="container-fluid px-4">
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted">
                        &copy; {{ date('Y') }} PeerSkill Admin Panel
                    </small>
                    <small class="text-muted">
                        <i class="bi bi-shield-check me-1"></i> Administrator Access Only
                    </small>
                </div>
            </div>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @stack('scripts')
</body>
</html>