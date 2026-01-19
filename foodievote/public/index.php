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
$layoutPath = '../views/layout/'; // Define layout path once

if (isLoggedIn()) {
    if (isAdmin()) {
        // --- Admin Routing ---
        $adminViewPath = '../views/admin/'; // This remains for content views

        $contentViewPath = ''; // This will hold the path to the actual content view
        $pageTitle = 'FoodieVote Admin'; // Default title

        switch ($page) {
            case 'home':
                $contentViewPath = $adminViewPath.'dashboard.php';
                $pageTitle = 'Dashboard Admin - FoodieVote';
                break;
            case 'manage-users':
                $contentViewPath = $adminViewPath.'manage-users.php';
                $pageTitle = 'Kelola User - FoodieVote Admin';
                break;
            case 'manage-restaurants':
                $contentViewPath = $adminViewPath.'manage-restaurants.php';
                $pageTitle = 'Kelola Restoran - FoodieVote Admin';
                break;
            case 'manage-foods':
                $contentViewPath = $adminViewPath.'manage-foods.php';
                $pageTitle = 'Kelola Makanan - FoodieVote Admin';
                break;
            case 'manage-ratings':
                $contentViewPath = $adminViewPath.'manage-ratings.php';
                $pageTitle = 'Kelola Rating - FoodieVote Admin';
                break;
            default:
                $contentViewPath = $adminViewPath.'dashboard.php'; // Fallback ke dashboard admin
                $pageTitle = 'Dashboard Admin - FoodieVote';
        }

        // Load the admin layout, which will then include the contentViewPath
        $pageToLoad = $layoutPath.'layout_admin.php';
    } else {
        // --- User Routing ---
        $userViewPath = '../views/user/'; // This remains for content views
        $guestViewPath = '../views/guest/'; // This remains for content views

        $contentViewPath = ''; // This will hold the path to the actual content view
        $pageTitle = 'FoodieVote User'; // Default title

        switch ($page) {
            case 'home':
                $contentViewPath = $userViewPath.'dashboard.php';
                $pageTitle = 'Dashboard User - FoodieVote';
                break;
            case 'profile':
                $contentViewPath = $userViewPath.'profile.php';
                $pageTitle = 'Profil Saya - FoodieVote';
                break;
            case 'my-ratings':
                $contentViewPath = $userViewPath.'my-ratings.php';
                $pageTitle = 'Rating Saya - FoodieVote';
                break;
            case 'restaurants':
                $contentViewPath = $guestViewPath.'restaurants.php';
                $pageTitle = 'Daftar Restoran - FoodieVote';
                break;
            case 'foods':
                $contentViewPath = $guestViewPath.'foods.php';
                $pageTitle = 'Daftar Makanan - FoodieVote';
                break;
            case 'restaurant-detail':
                $contentViewPath = $guestViewPath.'restaurant-detail.php';
                $pageTitle = 'Detail Restoran - FoodieVote';
                break;
            case 'food-detail':
                $contentViewPath = $guestViewPath.'food-detail.php';
                $pageTitle = 'Detail Makanan - FoodieVote';
                break;
            default:
                $contentViewPath = $userViewPath.'dashboard.php'; // Fallback ke dashboard user
                $pageTitle = 'Dashboard User - FoodieVote';
        }

        // Load the user layout, which will then include the contentViewPath
        $pageToLoad = $layoutPath.'layout_user.php';
    }
} else { // Not logged in (Guest)
    // --- Guest Routing ---
    $guestViewPath = '../views/guest/'; // This remains for content views

    $contentViewPath = ''; // This will hold the path to the actual content view
    $pageTitle = 'FoodieVote Guest'; // Default title

    switch ($page) {
        case 'home':
            $contentViewPath = $guestViewPath.'index.php';
            $pageTitle = 'FoodieVote - Home';
            break;
        case 'restaurants':
            $contentViewPath = $guestViewPath.'restaurants.php';
            $pageTitle = 'Daftar Restoran - FoodieVote';
            break;
        case 'kontak':
            $contentViewPath = $guestViewPath.'kontak.php';
            $pageTitle = 'Kontak Kami - FoodieVote';
            break;
        case 'foods':
            $contentViewPath = $guestViewPath.'foods.php';
            $pageTitle = 'Daftar Makanan - FoodieVote';
            break;
        case 'restaurant-detail':
            $contentViewPath = $guestViewPath.'restaurant-detail.php';
            $pageTitle = 'Detail Restoran - FoodieVote';
            break;
        case 'food-detail':
            $contentViewPath = $guestViewPath.'food-detail.php';
            $pageTitle = 'Detail Makanan - FoodieVote';
            break;
    }

    // Validasi jika halaman tidak ditemukan untuk Guest
    if (empty($contentViewPath)) {
        http_response_code(404);
        require_once '../views/error/error.php';
        exit;
    }

    // Load the guest layout, which will then include the contentViewPath
    $pageToLoad = $layoutPath.'layout_guest.php';
}

// Sertakan file yang sesuai
if (file_exists($pageToLoad)) {
    require_once $pageToLoad;
} else {
    // Tampilkan halaman 404 jika file tidak ditemukan
    http_response_code(404);
    require_once '../views/error/error.php';
    exit;
}
