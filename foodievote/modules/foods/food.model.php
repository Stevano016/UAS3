<?php
require_once __DIR__ . '/../../config/database.php';

class FoodModel {
    private $conn;
    
    public function __construct() {
        $this->conn = getConnection();
    }
    
    // Mendapatkan semua makanan
    public function getAllFoods() {
        $sql = "SELECT f.id, f.name, f.description, f.price, f.image_url, r.name as restaurant_name, AVG(rt.rating) as avg_rating, COUNT(rt.id) as total_ratings 
                FROM foods f 
                JOIN restaurants r ON f.restaurant_id = r.id 
                LEFT JOIN ratings rt ON f.id = rt.food_id 
                GROUP BY f.id 
                ORDER BY avg_rating DESC, f.name ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Mendapatkan makanan berdasarkan ID
    public function getFoodById($id) {
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
    }
    
    // Mendapatkan makanan berdasarkan nama
    public function getFoodsByName($name) {
        $sql = "SELECT f.id, f.name, f.description, f.price, f.image_url, r.name as restaurant_name, AVG(rt.rating) as avg_rating, COUNT(rt.id) as total_ratings 
                FROM foods f 
                JOIN restaurants r ON f.restaurant_id = r.id 
                LEFT JOIN ratings rt ON f.id = rt.food_id 
                WHERE f.name LIKE :name
                GROUP BY f.id 
                ORDER BY avg_rating DESC, f.name ASC";
        $stmt = $this->conn->prepare($sql);
        $name = "%{$name}%";
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Mendapatkan makanan berdasarkan restoran
    public function getFoodsByRestaurant($restaurantId) {
        $sql = "SELECT f.id, f.name, f.description, f.price, f.image_url, r.name as restaurant_name, AVG(rt.rating) as avg_rating, COUNT(rt.id) as total_ratings 
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
    }
    
    // Menambahkan makanan baru
    public function addFood($name, $description, $price, $restaurantId, $imageUrl) {
        $sql = "INSERT INTO foods (name, description, price, restaurant_id, image_url) VALUES (:name, :description, :price, :restaurant_id, :image_url)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':description', $description, PDO::PARAM_STR);
        $stmt->bindParam(':price', $price, PDO::PARAM_STR); // Using STR to preserve currency format
        $stmt->bindParam(':restaurant_id', $restaurantId, PDO::PARAM_INT);
        $stmt->bindParam(':image_url', $imageUrl, PDO::PARAM_STR);
        
        return $stmt->execute();
    }
    
    // Memperbarui data makanan
    public function updateFood($id, $name, $description, $price, $restaurantId, $imageUrl) {
        if ($imageUrl) {
            $sql = "UPDATE foods SET name = :name, description = :description, price = :price, restaurant_id = :restaurant_id, image_url = :image_url WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':description', $description, PDO::PARAM_STR);
            $stmt->bindParam(':price', $price, PDO::PARAM_STR);
            $stmt->bindParam(':restaurant_id', $restaurantId, PDO::PARAM_INT);
            $stmt->bindParam(':image_url', $imageUrl, PDO::PARAM_STR);
        } else {
            $sql = "UPDATE foods SET name = :name, description = :description, price = :price, restaurant_id = :restaurant_id WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':description', $description, PDO::PARAM_STR);
            $stmt->bindParam(':price', $price, PDO::PARAM_STR);
            $stmt->bindParam(':restaurant_id', $restaurantId, PDO::PARAM_INT);
        }
        
        return $stmt->execute();
    }
    
    // Menghapus makanan
    public function deleteFood($id) {
        // Hapus terlebih dahulu rating yang terkait
        $sql = "DELETE FROM ratings WHERE food_id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        // Baru hapus makanan
        $sql = "DELETE FROM foods WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        return $stmt->execute();
    }
}