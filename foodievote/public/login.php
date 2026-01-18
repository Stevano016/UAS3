<?php
require_once '../config/config.php';
require_once '../modules/users/user.controller.php';

// Cek apakah user sudah login, jika ya, arahkan ke halaman utama
if (isLoggedIn()) {
    redirect('/');
}

$userController = new UserController();
$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $result = $userController->login($username, $password);
    $message = $result['message'];
    $messageType = $result['success'] ? 'success' : 'danger';

    if ($result['success']) {
        // Arahkan ke halaman utama, front controller akan menangani view yang benar
        redirect('/');
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - FoodieVote</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/dashboard.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center p-4">
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-4 text-center border-0">
                    <h3 class="fw-bold mb-0">Welcome Back</h3>
                    <p class="text-muted mb-0">Sign in to your account</p>
                </div>
                <div class="card-body p-4">
                    <?php if ($message) { ?>
                        <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
                            <?php echo htmlspecialchars($message); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php } ?>

                    <form method="POST" id="loginForm">
                        <!-- Username -->
                        <div class="mb-3">
                            <label for="username" class="form-label fw-medium">Username</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="username" 
                                   name="username" 
                                   autocomplete="username"
                                   required>
                        </div>

                        <!-- Password -->
                        <div class="mb-4">
                            <label for="password" class="form-label fw-medium">Password</label>
                            <div class="input-group">
                                <input type="password" 
                                       class="form-control" 
                                       id="password" 
                                       name="password" 
                                       autocomplete="current-password"
                                       required>
                                <button class="btn btn-outline-secondary" 
                                        type="button" 
                                        id="togglePassword" 
                                        tabindex="-1"
                                        aria-label="Toggle password visibility">
                                    <i class="bi bi-eye" id="togglePasswordIcon"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary w-100 py-2">
                            Login
                        </button>
                    </form>

                    <!-- Register Link -->
                    <div class="text-center mt-4">
                        <p class="mb-0">Don't have an account? 
                            <a href="<?php echo BASE_URL; ?>/public/register.php" class="text-decoration-none">Sign up here</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
     <script src="<?php echo BASE_URL; ?>/assets/js/Login.js"></script>
</body>
</html>