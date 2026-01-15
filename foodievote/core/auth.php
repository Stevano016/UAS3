<?php
require_once '../config/config.php';
require_once 'session.php';

// Fungsi untuk memeriksa status login
function checkLogin($username, $password) {
    // Include model user untuk memverifikasi login
    require_once '../modules/users/user.model.php';
    
    $userModel = new UserModel();
    $user = $userModel->getUserByUsername($username);
    
    if ($user && password_verify($password, $user['password'])) {
        // Set session untuk user
        setSession('user_id', $user['id']);
        setSession('username', $user['username']);
        setSession('role', $user['role']);
        setSession('email', $user['email']);
        
        return true;
    }
    
    return false;
}

// Fungsi untuk logout
function doLogout() {
    unsetSession('user_id');
    unsetSession('username');
    unsetSession('role');
    unsetSession('email');
    destroySession();
}

// Fungsi untuk memeriksa apakah user sudah login
function isLogged() {
    return getSession('user_id') !== null;
}

// Fungsi untuk memeriksa apakah user adalah admin
function isAdminUser() {
    return getSession('role') === 'admin';
}

// Fungsi untuk memeriksa apakah user adalah user biasa
function isRegularUser() {
    return getSession('role') === 'user';
}