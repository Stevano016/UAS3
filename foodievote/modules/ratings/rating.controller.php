<?php
require_once __DIR__ . '/../../config/config.php';
require_once 'rating.model.php';

class RatingController {
    private $ratingModel;
    
    public function __construct() {
        $this->ratingModel = new RatingModel();
    }
    
    // Fungsi untuk mendapatkan semua rating
    public function getAllRatings() {
        return $this->ratingModel->getAllRatings();
    }
    
    // Fungsi untuk mendapatkan rating berdasarkan ID
    public function getRatingById($id) {
        return $this->ratingModel->getRatingById($id);
    }
    
    // Fungsi untuk mendapatkan rating berdasarkan user
    public function getRatingsByUser($userId) {
        return $this->ratingModel->getRatingsByUser($userId);
    }
    
    // Fungsi untuk mendapatkan rating untuk restoran tertentu
    public function getRatingsByRestaurant($restaurantId) {
        return $this->ratingModel->getRatingsByRestaurant($restaurantId);
    }
    
    // Fungsi untuk mendapatkan rating untuk makanan tertentu
    public function getRatingsByFood($foodId) {
        return $this->ratingModel->getRatingsByFood($foodId);
    }
    
    // Fungsi untuk menambahkan rating baru
    public function addRating($userId, $restaurantId, $foodId, $rating, $review) {
        // Validasi input
        if (empty($userId) || (empty($restaurantId) && empty($foodId)) || empty($rating)) {
            return [
                'success' => false,
                'message' => 'User ID, item (restoran atau makanan), dan rating harus diisi'
            ];
        }
        
        if ($rating < 1 || $rating > 5) {
            return [
                'success' => false,
                'message' => 'Rating harus antara 1 dan 5'
            ];
        }
        
        if ($this->ratingModel->addRating($userId, $restaurantId, $foodId, $rating, $review)) {
            return [
                'success' => true,
                'message' => 'Rating berhasil ditambahkan'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Gagal menambahkan rating. Anda mungkin sudah memberi rating untuk item ini.'
            ];
        }
    }
    
    // Fungsi untuk memperbarui rating
    public function updateRating($id, $rating, $review) {
        // Validasi input
        if (empty($id) || empty($rating)) {
            return [
                'success' => false,
                'message' => 'ID dan rating harus diisi'
            ];
        }
        
        if ($rating < 1 || $rating > 5) {
            return [
                'success' => false,
                'message' => 'Rating harus antara 1 dan 5'
            ];
        }
        
        if ($this->ratingModel->updateRating($id, $rating, $review)) {
            return [
                'success' => true,
                'message' => 'Rating berhasil diperbarui'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Gagal memperbarui rating'
            ];
        }
    }
    
    // Fungsi untuk menghapus rating
    public function deleteRating($id) {
        if ($this->ratingModel->deleteRating($id)) {
            return [
                'success' => true,
                'message' => 'Rating berhasil dihapus'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Gagal menghapus rating'
            ];
        }
    }

    // Fungsi untuk menambah atau memperbarui rating
    public function addOrUpdateRating($userId, $itemId, $isFoodItem, $rating, $review) {
        // Validasi input
        if (empty($userId) || empty($itemId) || empty($rating)) {
            return [
                'success' => false,
                'message' => 'User ID, Item ID, dan Rating harus diisi'
            ];
        }
        
        if ($rating < 1 || $rating > 5) {
            return [
                'success' => false,
                'message' => 'Rating harus antara 1 dan 5'
            ];
        }

        if ($this->ratingModel->addOrUpdateRating($userId, $itemId, $isFoodItem, $rating, $review)) {
            return [
                'success' => true,
                'message' => 'Rating berhasil disimpan'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Gagal menyimpan rating'
            ];
        }
    }
    
    // Fungsi untuk mendapatkan rata-rata rating untuk restoran
    public function getAverageRatingForRestaurant($restaurantId) {
        return $this->ratingModel->getAverageRatingForRestaurant($restaurantId);
    }
    
    // Fungsi untuk mendapatkan rata-rata rating untuk makanan
    public function getAverageRatingForFood($foodId) {
        return $this->ratingModel->getAverageRatingForFood($foodId);
    }
}