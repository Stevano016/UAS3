<?php
require_once '../config/config.php';
require_once '../core/auth.php';

// Lakukan logout
doLogout();

// Redirect ke halaman utama setelah logout
redirect('index.php');
?>