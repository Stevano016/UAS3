<?php
require_once __DIR__ . '/../../config/database.php';

class RestaurantModel {
    private $conn;
    
    public function __construct() {
        $this->conn = getConnection();
    }
    
    // Mendapatkan semua restoran
    public function getAllRestaurants() {
        $sql = "SELECT r.id, r.name, r.description, r.address, r.phone, r.operating_hours, r.image_url, AVG(rt.rating) as avg_rating, COUNT(rt.id) as total_ratings 
                FROM restaurants r 
                LEFT JOIN ratings rt ON r.id = rt.restaurant_id 
                GROUP BY r.id 
                ORDER BY avg_rating DESC, r.name ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Mendapatkan restoran berdasarkan ID
    public function getRestaurantById($id) {
        $sql = "SELECT r.*, AVG(rt.rating) as avg_rating, COUNT(rt.id) as total_ratings 
                FROM restaurants r 
                LEFT JOIN ratings rt ON r.id = rt.restaurant_id 
                WHERE r.id = :id 
                GROUP BY r.id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Mendapatkan restoran berdasarkan nama
    public function getRestaurantsByName($name) {
        $sql = "SELECT r.id, r.name, r.description, r.address, r.phone, r.operating_hours, r.image_url, AVG(rt.rating) as avg_rating, COUNT(rt.id) as total_ratings 
                FROM restaurants r 
                LEFT JOIN ratings rt ON r.id = rt.restaurant_id 
                WHERE r.name LIKE :name
                GROUP BY r.id 
                ORDER BY avg_rating DESC, r.name ASC";
        $stmt = $this->conn->prepare($sql);
        $name = "%{$name}%";
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Menambahkan restoran baru
    public function addRestaurant($name, $description, $address, $phone, $operatingHours, $imageUrl) {
        $sql = "INSERT INTO restaurants (name, description, address, phone, operating_hours, image_url) VALUES (:name, :description, :address, :phone, :operating_hours, :image_url)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':description', $description, PDO::PARAM_STR);
        $stmt->bindParam(':address', $address, PDO::PARAM_STR);
        $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
        $stmt->bindParam(':operating_hours', $operatingHours, PDO::PARAM_STR);
        $stmt->bindParam(':image_url', $imageUrl, PDO::PARAM_STR);
        
        return $stmt->execute();
    }
    
    // Memperbarui data restoran
    public function updateRestaurant($id, $name, $description, $address, $phone, $operatingHours, $imageUrl) {
        if ($imageUrl) {
            $sql = "UPDATE restaurants SET name = :name, description = :description, address = :address, phone = :phone, operating_hours = :operating_hours, image_url = :image_url WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':description', $description, PDO::PARAM_STR);
            $stmt->bindParam(':address', $address, PDO::PARAM_STR);
            $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
            $stmt->bindParam(':operating_hours', $operatingHours, PDO::PARAM_STR);
            $stmt->bindParam(':image_url', $imageUrl, PDO::PARAM_STR);
        } else {
            $sql = "UPDATE restaurants SET name = :name, description = :description, address = :address, phone = :phone, operating_hours = :operating_hours WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':description', $description, PDO::PARAM_STR);
            $stmt->bindParam(':address', $address, PDO::PARAM_STR);
            $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
            $stmt->bindParam(':operating_hours', $operatingHours, PDO::PARAM_STR);
        }
        
        return $stmt->execute();
    }
    
    // Menghapus restoran
    public function deleteRestaurant($id) {
        // Hapus terlebih dahulu rating yang terkait
        $sql = "DELETE FROM ratings WHERE restaurant_id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        // Baru hapus restoran
        $sql = "DELETE FROM restaurants WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        return $stmt->execute();
    }
}