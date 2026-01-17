<?php
require_once __DIR__ . '/../../config/database.php';

class RatingModel {
    private $conn;
    
    public function __construct() {
        $this->conn = getConnection();
    }
    
    // Mendapatkan semua rating
    public function getAllRatings() {
        $sql = "SELECT r.id, r.user_id, r.restaurant_id, r.food_id, r.rating, r.review, r.created_at, 
                u.username, 
                COALESCE(res.name, res2.name) as restaurant_name, 
                f.name as food_name 
                FROM ratings r 
                JOIN users u ON r.user_id = u.id 
                LEFT JOIN restaurants res ON r.restaurant_id = res.id 
                LEFT JOIN foods f ON r.food_id = f.id 
                LEFT JOIN restaurants res2 ON f.restaurant_id = res2.id
                ORDER BY r.created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Mendapatkan rating berdasarkan ID
    public function getRatingById($id) {
        $sql = "SELECT r.*, 
                u.username, 
                COALESCE(res.name, res2.name) as restaurant_name, 
                f.name as food_name 
                FROM ratings r 
                JOIN users u ON r.user_id = u.id 
                LEFT JOIN restaurants res ON r.restaurant_id = res.id 
                LEFT JOIN foods f ON r.food_id = f.id 
                LEFT JOIN restaurants res2 ON f.restaurant_id = res2.id
                WHERE r.id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Mendapatkan rating berdasarkan user
    public function getRatingsByUser($userId) {
        $sql = "SELECT r.id, r.user_id, r.restaurant_id, r.food_id, r.rating, r.review, r.created_at, 
                u.username, 
                COALESCE(res.name, res2.name) as restaurant_name, 
                f.name as food_name 
                FROM ratings r 
                JOIN users u ON r.user_id = u.id 
                LEFT JOIN restaurants res ON r.restaurant_id = res.id 
                LEFT JOIN foods f ON r.food_id = f.id 
                LEFT JOIN restaurants res2 ON f.restaurant_id = res2.id
                WHERE r.user_id = :user_id
                ORDER BY r.created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Mendapatkan rating untuk restoran tertentu
    public function getRatingsByRestaurant($restaurantId) {
        $sql = "SELECT r.id, r.user_id, r.restaurant_id, r.food_id, r.rating, r.review, r.created_at, 
                u.username, 
                res.name as restaurant_name 
                FROM ratings r 
                JOIN users u ON r.user_id = u.id 
                JOIN restaurants res ON r.restaurant_id = res.id 
                WHERE r.restaurant_id = :restaurant_id
                ORDER BY r.created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':restaurant_id', $restaurantId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Mendapatkan rating untuk makanan tertentu
    public function getRatingsByFood($foodId) {
        $sql = "SELECT r.id, r.user_id, r.restaurant_id, r.food_id, r.rating, r.review, r.created_at, 
                u.username, 
                f.name as food_name,
                res.name as restaurant_name
                FROM ratings r 
                JOIN users u ON r.user_id = u.id 
                JOIN foods f ON r.food_id = f.id 
                LEFT JOIN restaurants res ON f.restaurant_id = res.id
                WHERE r.food_id = :food_id
                ORDER BY r.created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':food_id', $foodId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Menambahkan rating baru
    public function addRating($userId, $restaurantId, $foodId, $rating, $review) {
        // Cek apakah user sudah memberi rating sebelumnya untuk item yang sama
        if ($foodId) {
            $sql = "SELECT COUNT(*) FROM ratings WHERE user_id = :user_id AND food_id = :food_id";
        } else {
            $sql = "SELECT COUNT(*) FROM ratings WHERE user_id = :user_id AND restaurant_id = :restaurant_id AND food_id IS NULL";
        }
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        if ($foodId) {
            $stmt->bindParam(':food_id', $foodId, PDO::PARAM_INT);
        } else {
            $stmt->bindParam(':restaurant_id', $restaurantId, PDO::PARAM_INT);
        }
        $stmt->execute();
        
        if ($stmt->fetchColumn() > 0) {
            // User sudah memberi rating sebelumnya untuk item ini
            return false;
        }
        
        // Insert rating baru
        $sql = "INSERT INTO ratings (user_id, restaurant_id, food_id, rating, review) VALUES (:user_id, :restaurant_id, :food_id, :rating, :review)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':restaurant_id', $restaurantId, PDO::PARAM_INT);
        $stmt->bindParam(':food_id', $foodId, PDO::PARAM_INT);
        $stmt->bindParam(':rating', $rating, PDO::PARAM_INT);
        $stmt->bindParam(':review', $review, PDO::PARAM_STR);
        
        return $stmt->execute();
    }
    
    // Memperbarui rating
    public function updateRating($id, $rating, $review) {
        $sql = "UPDATE ratings SET rating = :rating, review = :review WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':rating', $rating, PDO::PARAM_INT);
        $stmt->bindParam(':review', $review, PDO::PARAM_STR);
        
        return $stmt->execute();
    }
    
    // Menghapus rating
    public function deleteRating($id) {
        $sql = "DELETE FROM ratings WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        return $stmt->execute();
    }

    // Menambah atau memperbarui rating dalam satu query
    public function addOrUpdateRating($userId, $itemId, $isFoodItem, $rating, $review) {
        $restaurantId = $isFoodItem ? null : $itemId;
        $foodId = $isFoodItem ? $itemId : null;

        $sql = "INSERT INTO ratings (user_id, restaurant_id, food_id, rating, review) 
                VALUES (:user_id, :restaurant_id, :food_id, :rating, :review)
                ON DUPLICATE KEY UPDATE rating = VALUES(rating), review = VALUES(review)";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':restaurant_id', $restaurantId, PDO::PARAM_INT);
        $stmt->bindParam(':food_id', $foodId, PDO::PARAM_INT);
        $stmt->bindParam(':rating', $rating, PDO::PARAM_INT);
        $stmt->bindParam(':review', $review, PDO::PARAM_STR);
        
        return $stmt->execute();
    }
    
    // Mendapatkan rata-rata rating untuk restoran
    public function getAverageRatingForRestaurant($restaurantId) {
        $sql = "SELECT AVG(rating) as avg_rating, COUNT(id) as total_ratings FROM ratings WHERE restaurant_id = :restaurant_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':restaurant_id', $restaurantId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Mendapatkan rata-rata rating untuk makanan
    public function getAverageRatingForFood($foodId) {
        $sql = "SELECT AVG(rating) as avg_rating, COUNT(id) as total_ratings FROM ratings WHERE food_id = :food_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':food_id', $foodId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}