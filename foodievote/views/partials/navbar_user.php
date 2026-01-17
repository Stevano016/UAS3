    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.php">FoodieVote</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?page=home">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?page=restaurants">Restoran</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?page=foods">Makanan</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle active" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            Halo, <?php echo htmlspecialchars(getSession('username')); ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="index.php?page=profile">Profil Saya</a></li>
                            <li><a class="dropdown-item" href="index.php?page=my-ratings">Rating Saya</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>