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
        $userViewPath = '../views/user/';
        $guestViewPath = '../views/guest/';
        switch ($page) {
            case 'home':
                $pageToLoad = $userViewPath . 'dashboard.php';
                break;
            case 'profile':
                $pageToLoad = $userViewPath . 'profile.php';
                break;
            case 'my-ratings':
                $pageToLoad = $userViewPath . 'my-ratings.php';
                break;
            case 'restaurants':
                $pageToLoad = $guestViewPath . 'restaurants.php';
                break;
            case 'foods':
                $pageToLoad = $guestViewPath . 'foods.php';
                break;
            case 'restaurant-detail':
                $pageToLoad = $guestViewPath . 'restaurant-detail.php';
                break;
            case 'food-detail':
                $pageToLoad = $guestViewPath . 'food-detail.php';
                break;
            default:
                $pageToLoad = $userViewPath . 'dashboard.php'; // Fallback ke dashboard user
        }
    }
} else {
    // --- Guest Routing ---
    $guestViewPath = '../views/guest/';
    switch ($page) {
        case 'home':
            $pageToLoad = $guestViewPath . 'index.php';
            break;
        case 'restaurants':
            $pageToLoad = $guestViewPath . 'restaurants.php';
            break;
        case 'foods':
            $pageToLoad = $guestViewPath . 'foods.php';
            break;
        case 'restaurant-detail':
            $pageToLoad = $guestViewPath . 'restaurant-detail.php';
            break;
        case 'food-detail':
            $pageToLoad = $guestViewPath . 'food-detail.php';
            break;
        default:
            $pageToLoad = $guestViewPath . 'index.php'; // Fallback ke guest home
    }
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