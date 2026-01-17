<?php
require_once __DIR__ . '/../../config/database.php';

class RestaurantModel {
    private $conn;
    
    public function __construct() {
        $this->conn = getConnection();
    }
    
    // Sanitasi input untuk mencegah XSS dan SQL Injection
    private function sanitizeInput($input) {
        if (is_null($input)) {
            return null;
        }
        // Hapus tag HTML dan karakter khusus berbahaya
        $input = strip_tags($input);
        $input = trim($input);
        return $input;
    }
    
    // Validasi ID harus berupa integer positif
    private function validateId($id) {
        if (!is_numeric($id) || $id <= 0) {
            throw new InvalidArgumentException("ID harus berupa angka positif");
        }
        return (int)$id;
    }
    
    // Mendapatkan semua restoran
    public function getAllRestaurants() {
        try {
            $sql = "SELECT r.id, r.name, r.description, r.address, r.phone, r.operating_hours, r.image_url, 
                    AVG(rt.rating) as avg_rating, COUNT(rt.id) as total_ratings 
                    FROM restaurants r 
                    LEFT JOIN ratings rt ON r.id = rt.restaurant_id 
                    GROUP BY r.id 
                    ORDER BY avg_rating DESC, r.name ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getAllRestaurants: " . $e->getMessage());
            return [];
        }
    }
    
    // Mendapatkan restoran berdasarkan ID
    public function getRestaurantById($id) {
        try {
            $id = $this->validateId($id);
            
            $sql = "SELECT r.*, AVG(rt.rating) as avg_rating, COUNT(rt.id) as total_ratings 
                    FROM restaurants r 
                    LEFT JOIN ratings rt ON r.id = rt.restaurant_id 
                    WHERE r.id = :id 
                    GROUP BY r.id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error in getRestaurantById: " . $e->getMessage());
            return false;
        }
    }
    
    // Mendapatkan restoran berdasarkan nama
    public function getRestaurantsByName($name) {
        try {
            $name = $this->sanitizeInput($name);
            
            if (empty($name)) {
                return [];
            }
            
            $sql = "SELECT r.id, r.name, r.description, r.address, r.phone, r.operating_hours, r.image_url, 
                    AVG(rt.rating) as avg_rating, COUNT(rt.id) as total_ratings 
                    FROM restaurants r 
                    LEFT JOIN ratings rt ON r.id = rt.restaurant_id 
                    WHERE r.name LIKE :name
                    GROUP BY r.id 
                    ORDER BY avg_rating DESC, r.name ASC";
            $stmt = $this->conn->prepare($sql);
            $searchTerm = "%{$name}%";
            $stmt->bindParam(':name', $searchTerm, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getRestaurantsByName: " . $e->getMessage());
            return [];
        }
    }
    
    // Menambahkan restoran baru
    public function addRestaurant($name, $description, $address, $phone, $operatingHours, $imageUrl) {
        try {
            // Sanitasi semua input
            $name = $this->sanitizeInput($name);
            $description = $this->sanitizeInput($description);
            $address = $this->sanitizeInput($address);
            $phone = $this->sanitizeInput($phone);
            $operatingHours = $this->sanitizeInput($operatingHours);
            $imageUrl = $this->sanitizeInput($imageUrl);
            
            // Validasi input wajib
            if (empty($name) || empty($address)) {
                throw new InvalidArgumentException("Nama dan alamat restoran wajib diisi");
            }
            
            // Validasi panjang input
            if (strlen($name) > 255) {
                throw new InvalidArgumentException("Nama restoran terlalu panjang (maksimal 255 karakter)");
            }
            
            // Validasi format nomor telepon (opsional, sesuaikan dengan kebutuhan)
            if (!empty($phone) && !preg_match('/^[0-9\s\-\+\(\)]+$/', $phone)) {
                throw new InvalidArgumentException("Format nomor telepon tidak valid");
            }
            
            $sql = "INSERT INTO restaurants (name, description, address, phone, operating_hours, image_url) 
                    VALUES (:name, :description, :address, :phone, :operating_hours, :image_url)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':description', $description, PDO::PARAM_STR);
            $stmt->bindParam(':address', $address, PDO::PARAM_STR);
            $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
            $stmt->bindParam(':operating_hours', $operatingHours, PDO::PARAM_STR);
            $stmt->bindParam(':image_url', $imageUrl, PDO::PARAM_STR);
            
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error in addRestaurant: " . $e->getMessage());
            throw $e;
        }
    }
    
    // Memperbarui data restoran
    public function updateRestaurant($id, $name, $description, $address, $phone, $operatingHours, $imageUrl) {
        try {
            // Validasi ID
            $id = $this->validateId($id);
            
            // Sanitasi semua input
            $name = $this->sanitizeInput($name);
            $description = $this->sanitizeInput($description);
            $address = $this->sanitizeInput($address);
            $phone = $this->sanitizeInput($phone);
            $operatingHours = $this->sanitizeInput($operatingHours);
            $imageUrl = $this->sanitizeInput($imageUrl);
            
            // Validasi input wajib
            if (empty($name) || empty($address)) {
                throw new InvalidArgumentException("Nama dan alamat restoran wajib diisi");
            }
            
            // Validasi panjang input
            if (strlen($name) > 255) {
                throw new InvalidArgumentException("Nama restoran terlalu panjang (maksimal 255 karakter)");
            }
            
            // Validasi format nomor telepon
            if (!empty($phone) && !preg_match('/^[0-9\s\-\+\(\)]+$/', $phone)) {
                throw new InvalidArgumentException("Format nomor telepon tidak valid");
            }
            
            if ($imageUrl) {
                $sql = "UPDATE restaurants 
                        SET name = :name, description = :description, address = :address, 
                            phone = :phone, operating_hours = :operating_hours, image_url = :image_url 
                        WHERE id = :id";
                $stmt = $this->conn->prepare($sql);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                $stmt->bindParam(':description', $description, PDO::PARAM_STR);
                $stmt->bindParam(':address', $address, PDO::PARAM_STR);
                $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
                $stmt->bindParam(':operating_hours', $operatingHours, PDO::PARAM_STR);
                $stmt->bindParam(':image_url', $imageUrl, PDO::PARAM_STR);
            } else {
                $sql = "UPDATE restaurants 
                        SET name = :name, description = :description, address = :address, 
                            phone = :phone, operating_hours = :operating_hours 
                        WHERE id = :id";
                $stmt = $this->conn->prepare($sql);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                $stmt->bindParam(':description', $description, PDO::PARAM_STR);
                $stmt->bindParam(':address', $address, PDO::PARAM_STR);
                $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
                $stmt->bindParam(':operating_hours', $operatingHours, PDO::PARAM_STR);
            }
            
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error in updateRestaurant: " . $e->getMessage());
            throw $e;
        }
    }
    
    // Menghapus restoran
    public function deleteRestaurant($id) {
        try {
            // Validasi ID
            $id = $this->validateId($id);
            
            // Mulai transaksi untuk memastikan konsistensi data
            $this->conn->beginTransaction();
            
            // Hapus terlebih dahulu rating yang terkait
            $sql = "DELETE FROM ratings WHERE restaurant_id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            // Baru hapus restoran
            $sql = "DELETE FROM restaurants WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $result = $stmt->execute();
            
            // Commit transaksi jika berhasil
            $this->conn->commit();
            
            return $result;
        } catch (Exception $e) {
            // Rollback jika terjadi error
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }
            error_log("Error in deleteRestaurant: " . $e->getMessage());
            throw $e;
        }
    }
}