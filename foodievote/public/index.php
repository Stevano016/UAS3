<?php
require_once '../config/config.php';

// Cek apakah user sudah login
if (isLoggedIn()) {
    if (isAdmin()) {
        redirect('views/admin/dashboard.php');
    } else {
        redirect('views/user/dashboard.php');
    }
} else {
    // Jika belum login, arahkan ke halaman guest
    redirect('views/guest/index.php');
}
?>