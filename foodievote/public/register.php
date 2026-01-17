<?php
require_once '../config/config.php';
require_once '../modules/users/user.controller.php';

// Cek apakah user sudah login
if (isLoggedIn()) {
    redirect('/');
}

$userController = new UserController();
$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    $result = $userController->register($username, $email, $password, $confirmPassword);
    $message = $result['message'];
    $messageType = $result['success'] ? 'success' : 'danger';

    if ($result['success']) {
        // Redirect ke halaman login setelah registrasi berhasil
        redirect('login.php');
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - FoodieVote</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/dashboard.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center p-4">
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-4 text-center border-0">
                    <h3 class="fw-bold mb-0">Create Account</h3>
                    <p class="text-muted mb-0">Join us today</p>
                </div>
                <div class="card-body p-4">
                    <?php if ($message): ?>
                        <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
                            <?php echo htmlspecialchars($message); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <form method="POST">
    <div class="mb-3">
        <label for="username" class="form-label fw-medium">Username</label>
        <input type="text" class="form-control" id="username" name="username" required>
    </div>

    <div class="mb-3">
        <label for="email" class="form-label fw-medium">Email</label>
        <input type="email" class="form-control" id="email" name="email" required>
    </div>

    <div class="mb-3">
        <label for="password" class="form-label fw-medium">Password</label>
        <input type="password" class="form-control" id="password" name="password" required>
    </div>

    <div class="mb-4">
        <label for="confirm_password" class="form-label fw-medium">Confirm Password</label>
        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
    </div>

    <button type="submit" class="btn btn-primary w-100 py-2">
        Register
    </button>
</form>


                    <div class="text-center mt-4">
                        <p class="mb-0">Already have an account? <a href="<?php echo BASE_URL; ?>/public/login.php" class="text-decoration-none">Sign in here</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>