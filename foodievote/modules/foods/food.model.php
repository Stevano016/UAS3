<?php
require_once __DIR__ . '/../../config/database.php';

class FoodModel {
    private $conn;
    
    public function __construct() {
        $this->conn = getConnection();
    }
    
    // Sanitasi input untuk mencegah XSS dan SQL Injection
    private function sanitizeInput($input) {
        if (is_null($input)) {
            return null;
        }
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
    
    // Validasi harga
    private function validatePrice($price) {
        // Hapus karakter non-numerik kecuali titik dan koma
        $price = preg_replace('/[^0-9.,]/', '', $price);
        
        // Konversi koma ke titik untuk konsistensi
        $price = str_replace(',', '.', $price);
        
        if (!is_numeric($price) || $price < 0) {
            throw new InvalidArgumentException("Harga harus berupa angka positif");
        }
        
        return number_format((float)$price, 2, '.', '');
    }
    
    // Mendapatkan semua makanan
    public function getAllFoods() {
        try {
            $sql = "SELECT f.id, f.name, f.description, f.price, f.image_url, r.name as restaurant_name, 
                    AVG(rt.rating) as avg_rating, COUNT(rt.id) as total_ratings 
                    FROM foods f 
                    JOIN restaurants r ON f.restaurant_id = r.id 
                    LEFT JOIN ratings rt ON f.id = rt.food_id 
                    GROUP BY f.id 
                    ORDER BY avg_rating DESC, f.name ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getAllFoods: " . $e->getMessage());
            return [];
        }
    }
    
    // Mendapatkan makanan berdasarkan ID
    public function getFoodById($id) {
        try {
            $id = $this->validateId($id);
            
            $sql = "SELECT f.*, r.name as restaurant_name, AVG(rt.rating) as avg_rating, COUNT(rt.id) as total_ratings 
                    FROM foods f 
                    JOIN restaurants r ON f.restaurant_id = r.id 
                    LEFT JOIN ratings rt ON f.id = rt.food_id 
                    WHERE f.id = :id 
                    GROUP BY f.id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error in getFoodById: " . $e->getMessage());
            return false;
        }
    }
    
    // Mendapatkan makanan berdasarkan nama
    public function getFoodsByName($name) {
        try {
            $name = $this->sanitizeInput($name);
            
            if (empty($name)) {
                return [];
            }
            
            $sql = "SELECT f.id, f.name, f.description, f.price, f.image_url, r.name as restaurant_name, 
                    AVG(rt.rating) as avg_rating, COUNT(rt.id) as total_ratings 
                    FROM foods f 
                    JOIN restaurants r ON f.restaurant_id = r.id 
                    LEFT JOIN ratings rt ON f.id = rt.food_id 
                    WHERE f.name LIKE :name
                    GROUP BY f.id 
                    ORDER BY avg_rating DESC, f.name ASC";
            $stmt = $this->conn->prepare($sql);
            $searchTerm = "%{$name}%";
            $stmt->bindParam(':name', $searchTerm, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getFoodsByName: " . $e->getMessage());
            return [];
        }
    }
    
    // Mendapatkan makanan berdasarkan restoran
    public function getFoodsByRestaurant($restaurantId) {
        try {
            $restaurantId = $this->validateId($restaurantId);
            
            $sql = "SELECT f.id, f.name, f.description, f.price, f.image_url, r.name as restaurant_name, 
                    AVG(rt.rating) as avg_rating, COUNT(rt.id) as total_ratings 
                    FROM foods f 
                    JOIN restaurants r ON f.restaurant_id = r.id 
                    LEFT JOIN ratings rt ON f.id = rt.food_id 
                    WHERE f.restaurant_id = :restaurant_id
                    GROUP BY f.id 
                    ORDER BY avg_rating DESC, f.name ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':restaurant_id', $restaurantId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error in getFoodsByRestaurant: " . $e->getMessage());
            return [];
        }
    }
    
    // Menambahkan makanan baru
    public function addFood($name, $description, $price, $restaurantId, $imageUrl) {
        try {
            // Sanitasi dan validasi input
            $name = $this->sanitizeInput($name);
            $description = $this->sanitizeInput($description);
            $price = $this->validatePrice($price);
            $restaurantId = $this->validateId($restaurantId);
            $imageUrl = $this->sanitizeInput($imageUrl);
            
            // Validasi input wajib
            if (empty($name)) {
                throw new InvalidArgumentException("Nama makanan wajib diisi");
            }
            
            // Validasi panjang input
            if (strlen($name) > 255) {
                throw new InvalidArgumentException("Nama makanan terlalu panjang (maksimal 255 karakter)");
            }
            
            if (!empty($description) && strlen($description) > 1000) {
                throw new InvalidArgumentException("Deskripsi terlalu panjang (maksimal 1000 karakter)");
            }
            
            // Cek apakah restaurant_id valid (restaurant exists)
            $checkSql = "SELECT COUNT(*) FROM restaurants WHERE id = :restaurant_id";
            $checkStmt = $this->conn->prepare($checkSql);
            $checkStmt->bindParam(':restaurant_id', $restaurantId, PDO::PARAM_INT);
            $checkStmt->execute();
            
            if ($checkStmt->fetchColumn() == 0) {
                throw new InvalidArgumentException("Restoran tidak ditemukan");
            }
            
            $sql = "INSERT INTO foods (name, description, price, restaurant_id, image_url) 
                    VALUES (:name, :description, :price, :restaurant_id, :image_url)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':description', $description, PDO::PARAM_STR);
            $stmt->bindParam(':price', $price, PDO::PARAM_STR);
            $stmt->bindParam(':restaurant_id', $restaurantId, PDO::PARAM_INT);
            $stmt->bindParam(':image_url', $imageUrl, PDO::PARAM_STR);
            
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error in addFood: " . $e->getMessage());
            throw $e;
        }
    }
    
    // Memperbarui data makanan
    public function updateFood($id, $name, $description, $price, $restaurantId, $imageUrl) {
        try {
            // Validasi dan sanitasi input
            $id = $this->validateId($id);
            $name = $this->sanitizeInput($name);
            $description = $this->sanitizeInput($description);
            $price = $this->validatePrice($price);
            $restaurantId = $this->validateId($restaurantId);
            $imageUrl = $this->sanitizeInput($imageUrl);
            
            // Validasi input wajib
            if (empty($name)) {
                throw new InvalidArgumentException("Nama makanan wajib diisi");
            }
            
            // Validasi panjang input
            if (strlen($name) > 255) {
                throw new InvalidArgumentException("Nama makanan terlalu panjang (maksimal 255 karakter)");
            }
            
            if (!empty($description) && strlen($description) > 1000) {
                throw new InvalidArgumentException("Deskripsi terlalu panjang (maksimal 1000 karakter)");
            }
            
            // Cek apakah restaurant_id valid
            $checkSql = "SELECT COUNT(*) FROM restaurants WHERE id = :restaurant_id";
            $checkStmt = $this->conn->prepare($checkSql);
            $checkStmt->bindParam(':restaurant_id', $restaurantId, PDO::PARAM_INT);
            $checkStmt->execute();
            
            if ($checkStmt->fetchColumn() == 0) {
                throw new InvalidArgumentException("Restoran tidak ditemukan");
            }
            
            if ($imageUrl) {
                $sql = "UPDATE foods 
                        SET name = :name, description = :description, price = :price, 
                            restaurant_id = :restaurant_id, image_url = :image_url 
                        WHERE id = :id";
                $stmt = $this->conn->prepare($sql);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                $stmt->bindParam(':description', $description, PDO::PARAM_STR);
                $stmt->bindParam(':price', $price, PDO::PARAM_STR);
                $stmt->bindParam(':restaurant_id', $restaurantId, PDO::PARAM_INT);
                $stmt->bindParam(':image_url', $imageUrl, PDO::PARAM_STR);
            } else {
                $sql = "UPDATE foods 
                        SET name = :name, description = :description, price = :price, 
                            restaurant_id = :restaurant_id 
                        WHERE id = :id";
                $stmt = $this->conn->prepare($sql);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                $stmt->bindParam(':description', $description, PDO::PARAM_STR);
                $stmt->bindParam(':price', $price, PDO::PARAM_STR);
                $stmt->bindParam(':restaurant_id', $restaurantId, PDO::PARAM_INT);
            }
            
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error in updateFood: " . $e->getMessage());
            throw $e;
        }
    }
    
    // Menghapus makanan
    public function deleteFood($id) {
        try {
            $id = $this->validateId($id);
            
            // Mulai transaksi untuk memastikan konsistensi data
            $this->conn->beginTransaction();
            
            // Hapus terlebih dahulu rating yang terkait
            $sql = "DELETE FROM ratings WHERE food_id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            // Baru hapus makanan
            $sql = "DELETE FROM foods WHERE id = :id";
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
            error_log("Error in deleteFood: " . $e->getMessage());
            throw $e;
        }
    }
}