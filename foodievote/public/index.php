<?php
require_once '../config/config.php';
require_once '../core/session.php';

// Inisialisasi session jika belum ada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Default page
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

// Tentukan file view yang akan dimuat
$pageToLoad = '';

if (isLoggedIn()) {
    if (isAdmin()) {
        // --- Admin Routing ---
        $adminViewPath = '../views/admin/';
        switch ($page) {
            case 'home':
                $pageToLoad = $adminViewPath . 'dashboard.php';
                break;
            case 'manage-users':
                $pageToLoad = $adminViewPath . 'manage-users.php';
                break;
            case 'manage-restaurants':
                $pageToLoad = $adminViewPath . 'manage-restaurants.php';
                break;
            case 'manage-foods':
                $pageToLoad = $adminViewPath . 'manage-foods.php';
                break;
            case 'manage-ratings':
                $pageToLoad = $adminViewPath . 'manage-ratings.php';
                break;
            default:
                $pageToLoad = $adminViewPath . 'dashboard.php'; // Fallback ke dashboard admin
        }
    } else {
        // --- User Routing ---
        // Saat ini, user biasa selalu diarahkan ke dashboard mereka.
        // Bisa diperluas dengan logika 'page' jika diperlukan.
        $pageToLoad = '../views/user/dashboard.php';
    }
} else {
    // --- Guest Routing ---
    // Arahkan ke halaman guest index. Bisa diperluas juga.
    $pageToLoad = '../views/guest/index.php';
}

// Sertakan file yang sesuai
if (file_exists($pageToLoad)) {
    require_once $pageToLoad;
} else {
    // Tampilkan halaman 404 jika file tidak ditemukan
    http_response_code(404);
    // Bisa juga include file 404 kustom: require_once '../views/errors/404.php';
    echo "<h1>404 Not Found</h1><p>The page <code>" . htmlspecialchars($page) . "</code> was not found.</p>";
    echo "<a href='index.php'>Go to Homepage</a>";
}
?>