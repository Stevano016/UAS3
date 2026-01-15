<?php
// Konfigurasi aplikasi FoodieVote

define('APP_NAME', 'FoodieVote');
define('BASE_URL', 'http://localhost/Sasino/foodievote');

// Konfigurasi database
require_once 'database.php';

// Fungsi bantuan umum
function redirect($page) {
    header('Location: ' . BASE_URL . '/' . ltrim($page, '/'));
    exit();
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function isUser() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'user';
}