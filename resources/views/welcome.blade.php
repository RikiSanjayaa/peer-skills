<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PeerSkill - Find the Perfect Freelance Services</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <!-- Custom Styles -->
    @vite(['resources/css/app.css'])
    
    <style>
        :root {
            --primary-color: #00BCD4;
            --primary-dark: #00ACC1;
            --secondary-color: #1a1a1a;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
        }
        
        .hover-lift {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
        }
        
        .hover-lift:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        }
        
        .category-card:hover .category-icon {
            transform: scale(1.1);
            transition: transform 0.3s ease;
        }
        
        .gig-card img {
            height: 200px;
            object-fit: cover;
        }
        
        .min-vh-50 {
            min-height: 50vh;
        }
        
        .navbar {
            transition: box-shadow 0.3s ease;
        }
        
        .navbar.scrolled {
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div id="app">
        <!-- Navigation -->
        @include('components.navbar')
        
        <!-- Hero Section -->
        @include('components.hero')
        
        <!-- Categories Section -->
        @include('components.categories')
        
        <!-- Featured Gigs Section -->
        @include('components.featured-gigs')
        
        <!-- Footer -->
        @include('components.footer')
    </div>
    
    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Vue.js -->
    @vite(['resources/js/app.js'])
    
    <script>
        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
    </script>
</body>
</html>
