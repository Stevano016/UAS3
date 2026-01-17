<?php
require_once '../core/middleware.php';
require_once '../modules/users/user.model.php';

requireLogin();
requireAdmin();

$userModel = new UserModel();
$currentUser = $userModel->getUserById(getSession('user_id'));
?>

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
