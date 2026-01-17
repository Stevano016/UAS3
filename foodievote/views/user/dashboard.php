<?php
require_once '../core/middleware.php';
require_once '../modules/users/user.model.php';
require_once '../modules/ratings/rating.model.php';

requireLogin();
requireUser();

$userModel = new UserModel();
$ratingModel = new RatingModel();

$userId = getSession('user_id');
$user = $userModel->getUserById($userId);
$userRatings = $ratingModel->getRatingsByUser($userId);
?>

<h1>Dashboard User</h1>
<p>Selamat datang, <strong><?php echo htmlspecialchars($user['username']); ?></strong>!</p>

<div class="row mt-4">
    <div class="col-md-4 mb-4">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <h5 class="card-title">Profil Saya</h5>
                <p class="card-text">Lihat dan kelola informasi akun Anda</p>
                <a href="index.php?page=profile" class="btn btn-light">Lihat Profil</a>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card text-white bg-success">
            <div class="card-body">
                <h5 class="card-title">Rating Saya</h5>
                <p class="card-text">Lihat semua rating dan ulasan yang telah Anda berikan</p>
                <a href="index.php?page=my-ratings" class="btn btn-light">Lihat Rating</a>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card text-white bg-info">
            <div class="card-body">
                <h5 class="card-title">Berikan Rating</h5>
                <p class="card-text">Berikan penilaian untuk restoran atau makanan yang pernah Anda coba</p>
                <a href="index.php?page=restaurants" class="btn btn-light">Cari Item untuk Dirating</a>
            </div>
        </div>
    </div>
</div>
