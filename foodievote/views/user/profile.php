<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya - FoodieVote</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
</head>
<body>
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
    
    <?php require_once '../views/partials/navbar_user.php'; ?>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <h1>Profil Saya</h1>
                
                <?php if ($message): ?>
                    <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
                        <?php echo htmlspecialchars($message); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <div class="card">
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
                
                <div class="card mt-4">
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
        </div>
    </div>

    <?php require_once '../views/partials/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>