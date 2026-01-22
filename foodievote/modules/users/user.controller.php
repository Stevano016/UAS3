<?php

require_once __DIR__.'/../../config/config.php';
require_once __DIR__.'/../../core/session.php';
require_once 'user.model.php';

class UserController
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    // Fungsi untuk login
    public function login($username, $password)
    {
        // Validasi input
        $trimmedUsername = trim($username);
        $trimmedPassword = trim($password);

        if (empty($trimmedUsername) || empty($trimmedPassword)) {
            return [
                'success' => false,
                'message' => 'Username dan password harus diisi',
            ];
        }

        // Cek apakah user ada dan password benar
        $user = $this->userModel->getUserByUsername($trimmedUsername);

        if ($user && password_verify($trimmedPassword, $user['password'])) {
            // Set session
            setSession('user_id', $user['id']);
            setSession('username', $user['username']);
            setSession('role', $user['role']);
            setSession('email', $user['email']);

            return [
                'success' => true,
                'message' => 'Login berhasil',
                'user' => $user,
            ];
        }

        return [
            'success' => false,
            'message' => 'Username atau password salah',
        ];
    }

    // Fungsi untuk logout
    public function logout()
    {
        doLogout();

        return [
            'success' => true,
            'message' => 'Logout berhasil',
        ];
    }

    // Fungsi untuk registrasi
    public function register($username, $email, $password, $confirmPassword)
    {
        // Validasi input
        if (empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {
            return [
                'success' => false,
                'message' => 'Semua field harus diisi',
            ];
        }

        if ($password !== $confirmPassword) {
            return [
                'success' => false,
                'message' => 'Password dan konfirmasi password tidak cocok',
            ];
        }

        if (strlen($password) < 6) {
            return [
                'success' => false,
                'message' => 'Password minimal 6 karakter',
            ];
        }

        // Cek apakah username atau email sudah ada
        if ($this->userModel->checkUsernameExists($username)) {
            return [
                'success' => false,
                'message' => 'Username sudah digunakan',
            ];
        }

        if ($this->userModel->checkEmailExists($email)) {
            return [
                'success' => false,
                'message' => 'Email sudah digunakan',
            ];
        }

        // Tambahkan user baru
        if ($this->userModel->addUser($username, $email, $password)) {
            return [
                'success' => true,
                'message' => 'Registrasi berhasil, silakan login',
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Gagal menyimpan data user',
            ];
        }
    }

    // Fungsi untuk mendapatkan semua user (hanya untuk admin)
    public function getAllUsers()
    {
        return $this->userModel->getAllUsers();
    }

    // Fungsi untuk mendapatkan user berdasarkan ID
    public function getUserById($id)
    {
        return $this->userModel->getUserById($id);
    }

    // Fungsi untuk memperbarui data user
    public function updateUser($id, $username, $email)
    {
        // Validasi input
        if (empty($username) || empty($email)) {
            return [
                'success' => false,
                'message' => 'Username dan email harus diisi',
            ];
        }

        // Cek apakah username atau email sudah digunakan oleh user lain
        $existingUser = $this->userModel->getUserByUsername($username);
        if ($existingUser && $existingUser['id'] != $id) {
            return [
                'success' => false,
                'message' => 'Username sudah digunakan',
            ];
        }

        $existingEmailUser = $this->userModel->getUserByEmail($email);
        if ($existingEmailUser && $existingEmailUser['id'] != $id) {
            return [
                'success' => false,
                'message' => 'Email sudah digunakan',
            ];
        }

        if ($this->userModel->updateUser($id, $username, $email)) {
            return [
                'success' => true,
                'message' => 'Data user berhasil diperbarui',
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Gagal memperbarui data user',
            ];
        }
    }

    // Fungsi untuk memperbarui password
    public function updatePassword($id, $currentPassword, $newPassword, $confirmNewPassword)
    {
        // Validasi input
        if (empty($currentPassword) || empty($newPassword) || empty($confirmNewPassword)) {
            return [
                'success' => false,
                'message' => 'Semua field password harus diisi',
            ];
        }

        if ($newPassword !== $confirmNewPassword) {
            return [
                'success' => false,
                'message' => 'Password baru dan konfirmasi password tidak cocok',
            ];
        }

        if (strlen($newPassword) < 6) {
            return [
                'success' => false,
                'message' => 'Password minimal 6 karakter',
            ];
        }

        // Dapatkan user saat ini
        $user = $this->userModel->getUserById($id);

        // Cek apakah user ditemukan dan memiliki password
        if (!$user) {
            return [
                'success' => false,
                'message' => 'User tidak ditemukan',
            ];
        }

        if (!isset($user['password']) || empty($user['password'])) {
            return [
                'success' => false,
                'message' => 'Data password tidak valid',
            ];
        }

        if (!password_verify($currentPassword, $user['password'])) {
            return [
                'success' => false,
                'message' => 'Password saat ini salah',
            ];
        }

        if ($this->userModel->updatePassword($id, $newPassword)) {
            return [
                'success' => true,
                'message' => 'Password berhasil diperbarui',
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Gagal memperbarui password',
            ];
        }
    }

    // Fungsi untuk menambah user (hanya untuk admin)
    public function addUser($username, $email, $password, $role = 'user')
    {
        // Validasi input
        if (empty($username) || empty($email) || empty($password)) {
            return [
                'success' => false,
                'message' => 'Semua field harus diisi',
            ];
        }

        if (strlen($password) < 6) {
            return [
                'success' => false,
                'message' => 'Password minimal 6 karakter',
            ];
        }

        // Cek apakah username atau email sudah ada
        if ($this->userModel->checkUsernameExists($username)) {
            return [
                'success' => false,
                'message' => 'Username sudah digunakan',
            ];
        }

        if ($this->userModel->checkEmailExists($email)) {
            return [
                'success' => false,
                'message' => 'Email sudah digunakan',
            ];
        }

        // Tambahkan user baru
        if ($this->userModel->addUser($username, $email, $password, $role)) {
            return [
                'success' => true,
                'message' => 'User berhasil ditambahkan',
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Gagal menyimpan data user',
            ];
        }
    }

    // Fungsi untuk menghapus user (hanya untuk admin)
    public function deleteUser($id)
    {
        if ($this->userModel->deleteUser($id)) {
            return [
                'success' => true,
                'message' => 'User berhasil dihapus',
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Gagal menghapus user',
            ];
        }
    }

    // Fungsi tambahan untuk mendapatkan user berdasarkan email
    public function getUserByEmail($email)
    {
        return $this->userModel->getUserByEmail($email);
    }
}
