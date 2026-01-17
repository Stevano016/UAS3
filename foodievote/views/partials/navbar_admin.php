    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">FoodieVote Admin</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?page=home">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?page=manage-users">Kelola User</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?page=manage-restaurants">Kelola Restoran</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?page=manage-foods">Kelola Makanan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?page=manage-ratings">Kelola Rating</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            Halo, <?php echo htmlspecialchars(getSession('username')); ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>