<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FoodieVote - Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
</head>
<body>
    <?php require_once '../views/partials/navbar_guest.php'; ?>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <h1 class="text-center mb-4">Selamat Datang di FoodieVote</h1>
                <p class="text-center">Platform rating restoran dan makanan terpercaya</p>

                <div class="row mt-5">
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <h5 class="card-title">Temukan Restoran Terbaik</h5>
                                <p class="card-text">Jelajahi berbagai restoran dengan rating dan ulasan dari pengguna lain</p>
                                <a href="index.php?page=restaurants" class="btn btn-primary">Lihat Restoran</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <h5 class="card-title">Temukan Makanan Favorit</h5>
                                <p class="card-text">Temukan makanan lezat dengan rating dan ulasan dari pengguna lain</p>
                                <a href="index.php?page=foods" class="btn btn-primary">Lihat Makanan</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <h5 class="card-title">Beri Penilaian</h5>
                                <p class="card-text">Bagikan pengalaman kuliner Anda dan bantu orang lain menemukan tempat terbaik</p>
                                <a href="login.php" class="btn btn-primary">Login untuk Memberi Rating</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php require_once '../views/partials/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>