<?php
// Inisialisasi session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Fungsi untuk mengatur session
function setSession($key, $value) {
    $_SESSION[$key] = $value;
}

// Fungsi untuk mendapatkan nilai session
function getSession($key) {
    return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
}

// Fungsi untuk menghapus session
function unsetSession($key) {
    if (isset($_SESSION[$key])) {
        unset($_SESSION[$key]);
    }
}

// Fungsi untuk menghancurkan semua session
function destroySession() {
    session_destroy();
}