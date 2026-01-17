<?php
require_once __DIR__ . '/../../config/database.php';

class UserModel {
    private $conn;
    
    public function __construct() {
        $this->conn = getConnection();
    }
    
    // Validasi input untuk mencegah data tidak valid
    private function validateId($id) {
        if (!is_numeric($id) || $id <= 0) {
            throw new InvalidArgumentException("Invalid ID");
        }
        return (int)$id;
    }
    
    private function sanitizeString($string) {
        return trim(strip_tags($string));
    }
    
    private function validateEmail($email) {
        $email = $this->sanitizeString($email);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException("Invalid email format");
        }
        return $email;
    }
    
    private function validateUsername($username) {
        $username = $this->sanitizeString($username);
        if (strlen($username) < 3 || strlen($username) > 50) {
            throw new InvalidArgumentException("Username must be between 3 and 50 characters");
        }
        // Hanya izinkan alphanumeric, underscore, dan dash
        if (!preg_match('/^[a-zA-Z0-9_-]+$/', $username)) {
            throw new InvalidArgumentException("Username contains invalid characters");
        }
        return $username;
    }
    
    private function validateRole($role) {
        $allowedRoles = ['user', 'admin', 'moderator'];
        if (!in_array($role, $allowedRoles, true)) {
            throw new InvalidArgumentException("Invalid role");
        }
        return $role;
    }
    
    // Mendapatkan semua user
    public function getAllUsers() {
        try {
            $sql = "SELECT id, username, email, role, created_at FROM users ORDER BY created_at DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getAllUsers: " . $e->getMessage());
            return false;
        }
    }
    
    // Mendapatkan user berdasarkan ID
    public function getUserById($id) {
        try {
            $id = $this->validateId($id);
            
            $sql = "SELECT id, username, email, role, created_at FROM users WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error in getUserById: " . $e->getMessage());
            return false;
        }
    }
    
    // Mendapatkan user berdasarkan username
    public function getUserByUsername($username) {
        try {
            $username = $this->sanitizeString($username);
            
            $sql = "SELECT id, username, email, password, role, created_at FROM users WHERE username = :username";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error in getUserByUsername: " . $e->getMessage());
            return false;
        }
    }
    
    // Mengecek apakah username sudah ada
    public function checkUsernameExists($username) {
        try {
            $username = $this->sanitizeString($username);
            
            $sql = "SELECT COUNT(*) FROM users WHERE username = :username";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchColumn() > 0;
        } catch (Exception $e) {
            error_log("Error in checkUsernameExists: " . $e->getMessage());
            return false;
        }
    }
    
    // Mengecek apakah email sudah ada
    public function checkEmailExists($email) {
        try {
            $email = $this->sanitizeString($email);
            
            $sql = "SELECT COUNT(*) FROM users WHERE email = :email";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchColumn() > 0;
        } catch (Exception $e) {
            error_log("Error in checkEmailExists: " . $e->getMessage());
            return false;
        }
    }
    
    // Menambahkan user baru
    public function addUser($username, $email, $password, $role = 'user') {
        try {
            // Validasi input
            $username = $this->validateUsername($username);
            $email = $this->validateEmail($email);
            $role = $this->validateRole($role);
            
            // Validasi password
            if (strlen($password) < 8) {
                throw new InvalidArgumentException("Password must be at least 8 characters");
            }
            
            // Cek duplikasi
            if ($this->checkUsernameExists($username)) {
                throw new Exception("Username already exists");
            }
            
            if ($this->checkEmailExists($email)) {
                throw new Exception("Email already exists");
            }
            
            $hashedPassword = password_hash($password, PASSWORD_ARGON2ID);
            
            $sql = "INSERT INTO users (username, email, password, role) VALUES (:username, :email, :password, :role)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
            $stmt->bindParam(':role', $role, PDO::PARAM_STR);
            
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error in addUser: " . $e->getMessage());
            throw $e;
        }
    }
    
    // Memperbarui data user
    public function updateUser($id, $username, $email) {
        try {
            $id = $this->validateId($id);
            $username = $this->validateUsername($username);
            $email = $this->validateEmail($email);
            
            // Cek apakah username sudah digunakan oleh user lain
            $existingUser = $this->getUserByUsername($username);
            if ($existingUser && $existingUser['id'] != $id) {
                throw new Exception("Username already exists");
            }
            
            // Cek apakah email sudah digunakan oleh user lain
            $existingEmail = $this->getUserByEmail($email);
            if ($existingEmail && $existingEmail['id'] != $id) {
                throw new Exception("Email already exists");
            }
            
            $sql = "UPDATE users SET username = :username, email = :email WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error in updateUser: " . $e->getMessage());
            throw $e;
        }
    }
    
    // Memperbarui password user
    public function updatePassword($id, $password) {
        try {
            $id = $this->validateId($id);
            
            if (strlen($password) < 8) {
                throw new InvalidArgumentException("Password must be at least 8 characters");
            }
            
            $hashedPassword = password_hash($password, PASSWORD_ARGON2ID);
            
            $sql = "UPDATE users SET password = :password WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
            
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error in updatePassword: " . $e->getMessage());
            throw $e;
        }
    }
    
    // Menghapus user
    public function deleteUser($id) {
        try {
            $id = $this->validateId($id);
            
            $sql = "DELETE FROM users WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error in deleteUser: " . $e->getMessage());
            return false;
        }
    }

    // Mendapatkan user berdasarkan email
    public function getUserByEmail($email) {
        try {
            $email = $this->sanitizeString($email);
            
            $sql = "SELECT id, username, email, password, role, created_at FROM users WHERE email = :email";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error in getUserByEmail: " . $e->getMessage());
            return false;
        }
    }
}