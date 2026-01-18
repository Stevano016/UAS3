<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom py-3 shadow-sm">
    <div class="container-fluid px-5">
        <a class="navbar-brand d-flex align-items-center fw-bold" href="index.php">
            <svg xmlns="http://www.w3.org/2000/svg" width="45" height="45" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2" style="color: #3b82f6;">
                <path d="M18 8h1a4 4 0 0 1 0 8h-1"></path>
                <path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"></path>
                <line x1="6" y1="1" x2="6" y2="4"></line>
                <line x1="10" y1="1" x2="10" y2="4"></line>
                <line x1="14" y1="1" x2="14" y2="4"></line>
            </svg>
            <span style="font-size: 1.3rem; color: #1e3a8a;">FoodieVote</span>
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

<style>
    /* Navbar styling */
    .navbar {
        min-height: 80px;
    }
    
    /* Logo styling */
    .navbar-brand {
        color: #1e3a8a !important;
        transition: all 0.3s ease;
    }
    
    .navbar-brand:hover svg {
        transform: rotate(5deg) scale(1.1);
    }
    
    .navbar-brand svg {
        transition: transform 0.3s ease;
    }
    
    /* Nav link hover effect - light version */
    .nav-hover-light {
        color: #1e3a8a !important;
        transition: all 0.3s ease;
        font-size: 1rem;
    }
    
    .nav-hover-light:hover {
        background-color: #e0f2fe !important;
        color: #1e40af !important;
        transform: translateY(-2px);
    }
    
    /* User dropdown button */
    .btn-user {
        transition: all 0.3s ease;
        border-color: #3b82f6 !important;
        color: #3b82f6 !important;
        font-size: 1rem;
    }
    
    .btn-user:hover {
        background-color: #3b82f6 !important;
        color: white !important;
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(59, 130, 246, 0.3);
    }
    
    /* Custom dropdown menu */
    .custom-dropdown {
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        border: none;
        margin-top: 0.5rem;
        padding: 0.5rem 0;
    }
    
    .custom-dropdown .dropdown-item {
        padding: 0.7rem 1.5rem;
        transition: all 0.2s ease;
        font-size: 0.95rem;
    }
    
    .custom-dropdown .dropdown-item:hover {
        background-color: #e0f2fe;
        color: #1e40af;
        padding-left: 1.8rem;
    }
    
    .custom-dropdown .dropdown-item.text-danger:hover {
        background-color: #fee2e2;
        color: #dc2626 !important;
    }
</style>

<!-- Custom Alert Modal -->
<div id="customAlert" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; backdrop-filter: blur(5px);">
    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 10px 40px rgba(0,0,0,0.3); min-width: 350px; animation: slideIn 0.3s ease;">
        <div style="text-align: center; margin-bottom: 1.5rem;">
            <div style="width: 60px; height: 60px; border-radius: 50%; background: #fee2e2; margin: 0 auto 1rem; display: flex; align-items: center; justify-content: center; font-size: 2rem;">
                ⚠️
            </div>
            <h5 style="margin: 0 0 0.5rem 0; color: #333; font-weight: 600;">Konfirmasi Logout</h5>
            <p style="margin: 0; color: #666; font-size: 0.95rem;">Apakah Anda yakin ingin keluar dari sistem?</p>
        </div>
        <div style="display: flex; gap: 0.75rem; justify-content: center;">
            <button onclick="closeAlert()" style="padding: 0.6rem 1.5rem; border: 2px solid #6c757d; background: white; color: #6c757d; border-radius: 8px; cursor: pointer; font-weight: 500; transition: all 0.3s;">
                Batal
            </button>
            <button onclick="confirmLogout()" style="padding: 0.6rem 1.5rem; border: none; background: #dc3545; color: white; border-radius: 8px; cursor: pointer; font-weight: 500; transition: all 0.3s; box-shadow: 0 2px 8px rgba(220,53,69,0.3);">
                Ya, Logout
            </button>
        </div>
    </div>
</div>

<style>
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translate(-50%, -60%);
        }
        to {
            opacity: 1;
            transform: translate(-50%, -50%);
        }
    }
    
    #customAlert button:hover {
        transform: translateY(-2px);
    }
    
    #customAlert button:first-child:hover {
        background-color: #6c757d;
        color: white;
    }
    
    #customAlert button:last-child:hover {
        background-color: #c82333;
    }
</style>