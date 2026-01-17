<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - FoodieVote</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
</head>
<body>
    <?php
    require_once '../core/middleware.php';
    require_once '../modules/users/user.model.php';
    
    requireLogin();
    requireAdmin();
    
    $userModel = new UserModel();
    $currentUser = $userModel->getUserById(getSession('user_id'));
    ?>
    
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">FoodieVote Admin</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php?page=home">Dashboard</a>
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
                            Halo, <?php echo htmlspecialchars($currentUser['username']); ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <h1>Dashboard Administrator</h1>
                <p>Selamat datang, <strong><?php echo htmlspecialchars($currentUser['username']); ?></strong>!</p>
                
                <div class="row mt-4">
                    <div class="col-md-3 mb-4">
                        <div class="card text-white bg-primary">
                            <div class="card-body text-center">
                                <h5 class="card-title">Kelola User</h5>
                                <p class="card-text">Tambah, edit, atau hapus akun pengguna</p>
                                <a href="index.php?page=manage-users" class="btn btn-light">Lihat</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card text-white bg-success">
                            <div class="card-body text-center">
                                <h5 class="card-title">Kelola Restoran</h5>
                                <p class="card-text">Tambah, edit, atau hapus data restoran</p>
                                <a href="index.php?page=manage-restaurants" class="btn btn-light">Lihat</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card text-white bg-info">
                            <div class="card-body text-center">
                                <h5 class="card-title">Kelola Makanan</h5>
                                <p class="card-text">Tambah, edit, atau hapus data makanan</p>
                                <a href="index.php?page=manage-foods" class="btn btn-light">Lihat</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card text-white bg-warning">
                            <div class="card-body text-center">
                                <h5 class="card-title">Kelola Rating</h5>
                                <p class="card-text">Moderasi dan tinjau rating pengguna</p>
                                <a href="index.php?page=manage-ratings" class="btn btn-light text-dark">Lihat</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-light mt-5 py-4">
        <div class="container text-center">
            <p>&copy; 2023 FoodieVote Admin Panel. Semua hak dilindungi.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>