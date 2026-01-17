<?php
require_once '../core/middleware.php';
require_once '../modules/users/user.model.php';
require_once '../modules/users/user.controller.php';

requireLogin();
requireUser();

$userModel = new UserModel();
$userController = new UserController();

$userId = getSession('user_id');
$user = $userModel->getUserById($userId);

$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_profile'])) {
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        
        $result = $userController->updateUser($userId, $username, $email);
        $message = $result['message'];
        $messageType = $result['success'] ? 'success' : 'danger';
        
        // Jika berhasil, update session
        if ($result['success']) {
            setSession('username', $username);
            setSession('email', $email);
            $user = $userModel->getUserById($userId); // Refresh data user
        }
    } elseif (isset($_POST['update_password'])) {
        $currentPassword = $_POST['current_password'];
        $newPassword = $_POST['new_password'];
        $confirmNewPassword = $_POST['confirm_new_password'];
        
        $result = $userController->updatePassword($userId, $currentPassword, $newPassword, $confirmNewPassword);
        $message = $result['message'];
        $messageType = $result['success'] ? 'success' : 'danger';
    }
}
?>

<div class="col-12 col-lg-8 mx-auto">
    <div class="dashboard-header">
        <h1>Profil Saya</h1>
    </div>

    <?php if ($message): ?>
        <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($message); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card mb-4 fade-in-section">
        <div class="card-header">
            <h5>Informasi Akun</h5>
        </div>
        <div class="card-body">
            <form method="POST">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                </div>
                <button type="submit" name="update_profile" class="btn btn-primary">Perbarui Profil</button>
            </form>
        </div>
    </div>

    <div class="card fade-in-section">
        <div class="card-header">
            <h5>Ganti Password</h5>
        </div>
        <div class="card-body">
            <form method="POST">
                <div class="mb-3">
                    <label for="current_password" class="form-label">Password Saat Ini</label>
                    <input type="password" class="form-control" id="current_password" name="current_password" required>
                </div>
                <div class="mb-3">
                    <label for="new_password" class="form-label">Password Baru</label>
                    <input type="password" class="form-control" id="new_password" name="new_password" required>
                </div>
                <div class="mb-3">
                    <label for="confirm_new_password" class="form-label">Konfirmasi Password Baru</label>
                    <input type="password" class="form-control" id="confirm_new_password" name="confirm_new_password" required>
                </div>
                <button type="submit" name="update_password" class="btn btn-primary">Ganti Password</button>
            </form>
        </div>
    </div>
</div>
