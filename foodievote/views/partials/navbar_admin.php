<div class="admin-container">
        <!-- Sidebar Navigation -->
        <aside class="admin-sidebar" style="display: flex; flex-direction: column; height: 100vh;">
            <div class="d-flex align-items-center mb-4">
                <h4 class="mb-0">FoodieVote</h4>
            </div>

            <ul class="sidebar-nav" style="flex: 1; list-style: none; padding: 0; margin: 0;">
                <li class="nav-item">
                    <a class="nav-link <?php echo (isset($_GET['page']) && $_GET['page'] === 'home') || !isset($_GET['page']) ? 'active' : ''; ?>" href="index.php?page=home">
                        <i></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo isset($_GET['page']) && $_GET['page'] === 'manage-users' ? 'active' : ''; ?>" href="index.php?page=manage-users">
                        <i></i> Kelola User
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo isset($_GET['page']) && $_GET['page'] === 'manage-restaurants' ? 'active' : ''; ?>" href="index.php?page=manage-restaurants">
                        <i></i> Kelola Restoran
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo isset($_GET['page']) && $_GET['page'] === 'manage-foods' ? 'active' : ''; ?>" href="index.php?page=manage-foods">
                        <i></i> Kelola Makanan
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo isset($_GET['page']) && $_GET['page'] === 'manage-ratings' ? 'active' : ''; ?>" href="index.php?page=manage-ratings">
                        <i></i> Kelola Rating
                    </a>
                </li>
            </ul>
            
            <!-- Logout Button -->
            <div style="margin-top: auto; padding-top: 1rem; border-top: 1px solid rgba(255,255,255,0.1);">
                <a class="nav-link text-danger" href="#" onclick="showLogoutConfirm(event)" style="display: block;">
                    <i></i> Logout
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="admin-main">
            <!-- Top Navigation Bar -->
            <nav class="navbar navbar-expand-lg navbar-light navbar-admin">
                <div class="container-fluid">
                    <div class="d-flex align-items-center">
                        <h4 class="mb-0"><?php echo $pageTitle ?? 'Dashboard'; ?></h4>
                    </div>

                    <div class="navbar-nav ms-auto">
                        <div class="d-flex align-items-center gap-2">
                            <span class="nav-link mb-0">Halo, <?php echo htmlspecialchars(getSession('username')); ?></span>
                            <a class="btn btn-outline-danger btn-sm" href="#" onclick="showLogoutConfirm(event)">Logout</a>
                        </div>
                    </div>
                </div>
            </nav>

        
