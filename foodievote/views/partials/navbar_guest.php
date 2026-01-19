<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/navGuest.css">
<nav class="navbar navbar-expand-lg navbar-dark py-3"style="background: linear-gradient(135deg, #2D1B16 0%, #5D2E17 100%);">
    <div class="container-fluid px-5">
        <a class="navbar-brand d-flex align-items-center fw-bold" href="index.php" style="color: white !important;">
            <img src="<?php echo BASE_URL; ?>/assets/images/foodievote-logo1.png" 
            width="50" height="50" class="me-2">
            <span style="font-size: 1.5rem;">FoodieVote</span>
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
                    <a class="nav-link px-3 rounded-pill nav-hover-bordered" href="index.php?page=foods">Makanan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3 rounded-pill nav-hover-bordered" href="index.php?page=kontak">Kontak</a>
                </li>
            </ul>
            
            <div class="d-flex gap-2">
                <a href="login.php" class="btn btn-outline-light rounded-pill px-4 btn-login">Login</a>
                <a href="register.php" class="btn btn-warning text-dark fw-bold rounded-pill px-4 btn-signup">Sign Up</a>
            </div>
        </div>
    </div>
</nav>

