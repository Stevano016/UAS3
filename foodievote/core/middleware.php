<?php
require_once 'session.php';
require_once 'auth.php';

// Middleware untuk memastikan pengguna sudah login
function requireLogin() {
    if (!isLogged()) {
        redirect('../public/login.php');
        exit();
    }
}

// Middleware untuk membatasi akses hanya untuk admin
function requireAdmin() {
    if (!isAdminUser()) {
        redirect('../public/index.php');
        exit();
    }
}

// Middleware untuk membatasi akses hanya untuk user biasa
function requireUser() {
    if (!isRegularUser()) {
        redirect('../public/index.php');
        exit();
    }
}

// Middleware untuk membatasi akses tidak untuk user yang sudah login
function guestOnly() {
    if (isLogged()) {
        redirect('../public/index.php');
        exit();
    }
}