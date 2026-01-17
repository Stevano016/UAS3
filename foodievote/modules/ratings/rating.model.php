<?php
require_once __DIR__ . '/../../config/database.php';

class RatingModel {
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
    
    // Validasi rating (1-5)
    private function validateRating($rating) {
        if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
            throw new InvalidArgumentException("Rating harus berupa angka antara 1-5");
        }
        return (int)$rating;
    }
    
    // Mendapatkan semua rating
    public function getAllRatings() {
        try {
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
        } catch (PDOException $e) {
            error_log("Error in getAllRatings: " . $e->getMessage());
            return [];
        }
    }
    
    // Mendapatkan rating berdasarkan ID
    public function getRatingById($id) {
        try {
            $id = $this->validateId($id);
            
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
        } catch (Exception $e) {
            error_log("Error in getRatingById: " . $e->getMessage());
            return false;
        }
    }
    
    // Mendapatkan rating berdasarkan user
    public function getRatingsByUser($userId) {
        try {
            $userId = $this->validateId($userId);
            
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
        } catch (Exception $e) {
            error_log("Error in getRatingsByUser: " . $e->getMessage());
            return [];
        }
    }
    
    // Mendapatkan rating untuk restoran tertentu
    public function getRatingsByRestaurant($restaurantId) {
        try {
            $restaurantId = $this->validateId($restaurantId);
            
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
        } catch (Exception $e) {
            error_log("Error in getRatingsByRestaurant: " . $e->getMessage());
            return [];
        }
    }
    
    // Mendapatkan rating untuk makanan tertentu
    public function getRatingsByFood($foodId) {
        try {
            $foodId = $this->validateId($foodId);
            
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
        } catch (Exception $e) {
            error_log("Error in getRatingsByFood: " . $e->getMessage());
            return [];
        }
    }
    
    // Menambahkan rating baru
    public function addRating($userId, $restaurantId, $foodId, $rating, $review) {
        try {
            // Validasi input
            $userId = $this->validateId($userId);
            $rating = $this->validateRating($rating);
            $review = $this->sanitizeInput($review);
            
            // Validasi restaurantId atau foodId
            if ($foodId !== null) {
                $foodId = $this->validateId($foodId);
                $restaurantId = null; // Set null jika rating untuk food
            } else if ($restaurantId !== null) {
                $restaurantId = $this->validateId($restaurantId);
            } else {
                throw new InvalidArgumentException("Restaurant ID atau Food ID harus diisi");
            }
            
            // Validasi panjang review
            if (!empty($review) && strlen($review) > 1000) {
                throw new InvalidArgumentException("Review terlalu panjang (maksimal 1000 karakter)");
            }
            
            // Cek apakah user sudah memberi rating sebelumnya untuk item yang sama
            if ($foodId) {
                $sql = "SELECT COUNT(*) FROM ratings WHERE user_id = :user_id AND food_id = :food_id";
                $stmt = $this->conn->prepare($sql);
                $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
                $stmt->bindParam(':food_id', $foodId, PDO::PARAM_INT);
            } else {
                $sql = "SELECT COUNT(*) FROM ratings WHERE user_id = :user_id AND restaurant_id = :restaurant_id AND food_id IS NULL";
                $stmt = $this->conn->prepare($sql);
                $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
                $stmt->bindParam(':restaurant_id', $restaurantId, PDO::PARAM_INT);
            }
            
            $stmt->execute();
            
            if ($stmt->fetchColumn() > 0) {
                // User sudah memberi rating sebelumnya untuk item ini
                throw new InvalidArgumentException("Anda sudah memberikan rating untuk item ini");
            }
            
            // Insert rating baru
            $sql = "INSERT INTO ratings (user_id, restaurant_id, food_id, rating, review) 
                    VALUES (:user_id, :restaurant_id, :food_id, :rating, :review)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            
            if ($restaurantId !== null) {
                $stmt->bindParam(':restaurant_id', $restaurantId, PDO::PARAM_INT);
            } else {
                $stmt->bindValue(':restaurant_id', null, PDO::PARAM_NULL);
            }
            
            if ($foodId !== null) {
                $stmt->bindParam(':food_id', $foodId, PDO::PARAM_INT);
            } else {
                $stmt->bindValue(':food_id', null, PDO::PARAM_NULL);
            }
            
            $stmt->bindParam(':rating', $rating, PDO::PARAM_INT);
            $stmt->bindParam(':review', $review, PDO::PARAM_STR);
            
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error in addRating: " . $e->getMessage());
            throw $e;
        }
    }
    
    // Memperbarui rating
    public function updateRating($id, $rating, $review) {
        try {
            // Validasi input
            $id = $this->validateId($id);
            $rating = $this->validateRating($rating);
            $review = $this->sanitizeInput($review);
            
            // Validasi panjang review
            if (!empty($review) && strlen($review) > 1000) {
                throw new InvalidArgumentException("Review terlalu panjang (maksimal 1000 karakter)");
            }
            
            $sql = "UPDATE ratings SET rating = :rating, review = :review WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':rating', $rating, PDO::PARAM_INT);
            $stmt->bindParam(':review', $review, PDO::PARAM_STR);
            
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error in updateRating: " . $e->getMessage());
            throw $e;
        }
    }
    
    // Menghapus rating
    public function deleteRating($id) {
        try {
            $id = $this->validateId($id);
            
            $sql = "DELETE FROM ratings WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error in deleteRating: " . $e->getMessage());
            throw $e;
        }
    }

    // Menambah atau memperbarui rating dalam satu query
    public function addOrUpdateRating($userId, $itemId, $isFoodItem, $rating, $review) {
        try {
            // Validasi input
            $userId = $this->validateId($userId);
            $itemId = $this->validateId($itemId);
            $rating = $this->validateRating($rating);
            $review = $this->sanitizeInput($review);
            $isFoodItem = (bool)$isFoodItem;
            
            // Validasi panjang review
            if (!empty($review) && strlen($review) > 1000) {
                throw new InvalidArgumentException("Review terlalu panjang (maksimal 1000 karakter)");
            }
            
            $restaurantId = $isFoodItem ? null : $itemId;
            $foodId = $isFoodItem ? $itemId : null;

            $sql = "INSERT INTO ratings (user_id, restaurant_id, food_id, rating, review) 
                    VALUES (:user_id, :restaurant_id, :food_id, :rating, :review)
                    ON DUPLICATE KEY UPDATE rating = VALUES(rating), review = VALUES(review)";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            
            if ($restaurantId !== null) {
                $stmt->bindParam(':restaurant_id', $restaurantId, PDO::PARAM_INT);
            } else {
                $stmt->bindValue(':restaurant_id', null, PDO::PARAM_NULL);
            }
            
            if ($foodId !== null) {
                $stmt->bindParam(':food_id', $foodId, PDO::PARAM_INT);
            } else {
                $stmt->bindValue(':food_id', null, PDO::PARAM_NULL);
            }
            
            $stmt->bindParam(':rating', $rating, PDO::PARAM_INT);
            $stmt->bindParam(':review', $review, PDO::PARAM_STR);
            
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error in addOrUpdateRating: " . $e->getMessage());
            throw $e;
        }
    }
    
    // Mendapatkan rata-rata rating untuk restoran
    public function getAverageRatingForRestaurant($restaurantId) {
        try {
            $restaurantId = $this->validateId($restaurantId);
            
            $sql = "SELECT AVG(rating) as avg_rating, COUNT(id) as total_ratings 
                    FROM ratings 
                    WHERE restaurant_id = :restaurant_id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':restaurant_id', $restaurantId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error in getAverageRatingForRestaurant: " . $e->getMessage());
            return ['avg_rating' => 0, 'total_ratings' => 0];
        }
    }
    
    // Mendapatkan rata-rata rating untuk makanan
    public function getAverageRatingForFood($foodId) {
        try {
            $foodId = $this->validateId($foodId);
            
            $sql = "SELECT AVG(rating) as avg_rating, COUNT(id) as total_ratings 
                    FROM ratings 
                    WHERE food_id = :food_id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':food_id', $foodId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error in getAverageRatingForFood: " . $e->getMessage());
            return ['avg_rating' => 0, 'total_ratings' => 0];
        }
    }
}