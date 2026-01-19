<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/navUser.css">

<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom py-3 shadow-sm">
    <div class="container-fluid px-5">
             <a class="navbar-brand d-flex align-items-center fw-bold" href="index.php" style="color: white !important;">
            <img src="<?php echo BASE_URL; ?>/assets/images/foodievote-logo1.png" 
            width="50" height="50" class="me-2">
            <span style="font-size: 1.5rem; color: #1e3a8a;">FoodieVote</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link px-4 py-2 rounded-pill nav-hover-light" href="index.php?page=home">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-4 py-2 rounded-pill nav-hover-light" href="index.php?page=restaurants">Restoran</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-4 py-2 rounded-pill nav-hover-light" href="index.php?page=foods">Makanan</a>
                </li>
            </ul>
            <div class="d-flex align-items-center gap-2">
                <div class="dropdown">
                    <a class="btn btn-outline-primary rounded-pill px-4 py-2 dropdown-toggle btn-user" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                        Halo, <?php echo htmlspecialchars(getSession('username')); ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end custom-dropdown">
                        <li><a class="dropdown-item" href="index.php?page=profile">
                            <i class="bi bi-person-circle me-2"></i>Profil Saya
                        </a></li>
                        <li><a class="dropdown-item" href="index.php?page=my-ratings">
                            <i class="bi bi-star-fill me-2"></i>Rating Saya
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="#" onclick="showLogoutConfirm(event)">
                            <i class="bi bi-box-arrow-right me-2"></i>Logout
                        </a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>




