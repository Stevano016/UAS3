<nav class="navbar navbar-expand-lg navbar-dark py-3" style="background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);">
    <div class="container-fluid px-6">
        <a class="navbar-brand d-flex align-items-center fw-bold" href="index.php" style="color: white !important;">
            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2" style="color: #fbbf24;">
                <path d="M18 8h1a4 4 0 0 1 0 8h-1"></path>
                <path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"></path>
                <line x1="6" y1="1" x2="6" y2="4"></line>
                <line x1="10" y1="1" x2="10" y2="4"></line>
                <line x1="14" y1="1" x2="14" y2="4"></line>
            </svg>
            <span style="font-size: 1.2rem;">FoodieVote</span>
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link px-3 rounded-pill nav-hover-bordered" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3 rounded-pill nav-hover-bordered" href="index.php?page=restaurants">Restoran</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3 rounded-pill nav-hover-bordered" href="index.php?page=about">Tentang Kami</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3 rounded-pill nav-hover-bordered" href="index.php?page=foods">Makanan</a>
                </li>
            </ul>
            
            <div class="d-flex gap-2">
                <a href="login.php" class="btn btn-outline-light rounded-pill px-4 btn-login">Login</a>
                <a href="register.php" class="btn btn-warning text-dark fw-bold rounded-pill px-4 btn-signup">Sign Up</a>
            </div>
        </div>
    </div>
</nav>

<style>
    /* Override Bootstrap default dengan specificity tinggi */
    .navbar-dark .navbar-nav .nav-link,
    .navbar-dark .navbar-nav .nav-link:focus,
    .nav-hover,
    .nav-hover-bordered {
        color: white !important;
    }
    
    /* Hover effect - BIRU TUA */
    .navbar-dark .navbar-nav .nav-link.nav-hover:hover {
        color: #1e40af !important;
        transform: translateY(-2px);
    }
    
    .navbar-brand {
        color: white !important;
    }
    
    .navbar-brand:hover {
        color: white !important;
    }
    
    /* Hover effect untuk menu navigasi - BIRU TUA saat hover */
    .nav-hover {
        transition: all 0.3s ease;
    }
    
    /* Hover untuk menu dengan border - Background BIRU TUA saat hover */
    .nav-hover-bordered {
        transition: all 0.3s ease;
    }
    
    .navbar-dark .navbar-nav .nav-link.nav-hover-bordered:hover {
        background-color: #1e40af !important;
        color: white !important;
        border-color: #1e40af !important;
        transform: translateY(-2px);
    }
    
    /* Hover untuk tombol Login */
    .btn-login {
        transition: all 0.3s ease;
        border-color: white !important;
        color: white !important;
    }
    
    .btn-login:hover {
        background-color: white !important;
        color: #1e3a8a !important;
        border-color: white !important;
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    
    /* Hover untuk tombol Sign Up */
    .btn-signup {
        transition: all 0.3s ease;
        background-color: #fbbf24 !important;
        color: #1e3a8a !important;
    }
    
    .btn-signup:hover {
        background-color: #f59e0b !important;
        color: #1e3a8a !important;
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
    }
    
    /* Logo animation */
    .navbar-brand svg {
        transition: transform 0.3s ease;
    }
    
    .navbar-brand:hover svg {
        transform: rotate(5deg) scale(1.1);
    }
</style>