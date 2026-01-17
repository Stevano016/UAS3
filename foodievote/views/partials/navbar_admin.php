 <div class="admin-container">
        <!-- Sidebar Navigation -->
        <aside class="admin-sidebar">
            <div class="d-flex align-items-center mb-4">
                <h4 class="mb-0">FoodieVote</h4>
            </div>

            <ul class="sidebar-nav">
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
                        <div class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                Halo, <?php echo htmlspecialchars(getSession('username')); ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>
