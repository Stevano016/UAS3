<?php
require_once __DIR__ . '/../../config/config.php';
require_once 'food.model.php';

class FoodController {
    private $foodModel;
    
    public function __construct() {
        $this->foodModel = new FoodModel();
    }
    
    // Fungsi untuk mendapatkan semua makanan
    public function getAllFoods() {
        return $this->foodModel->getAllFoods();
    }
    
    // Fungsi untuk mendapatkan makanan berdasarkan ID
    public function getFoodById($id) {
        return $this->foodModel->getFoodById($id);
    }
    
    // Fungsi untuk mendapatkan makanan berdasarkan nama
    public function getFoodsByName($name) {
        return $this->foodModel->getFoodsByName($name);
    }
    
    // Fungsi untuk mendapatkan makanan berdasarkan restoran
    public function getFoodsByRestaurant($restaurantId) {
        return $this->foodModel->getFoodsByRestaurant($restaurantId);
    }
    
    // Fungsi untuk menambahkan makanan baru
    public function addFood($name, $description, $price, $restaurantId, $imageUrl) {
        // Validasi input
        if (empty($name) || empty($description) || empty($price) || empty($restaurantId)) {
            return [
                'success' => false,
                'message' => 'Semua field harus diisi'
            ];
        }
        
        if (!is_numeric($price) || $price < 0) {
            return [
                'success' => false,
                'message' => 'Harga harus berupa angka positif'
            ];
        }
        
        if ($this->foodModel->addFood($name, $description, $price, $restaurantId, $imageUrl)) {
            return [
                'success' => true,
                'message' => 'Makanan berhasil ditambahkan'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Gagal menambahkan makanan'
            ];
        }
    }
    
    // Fungsi untuk memperbarui data makanan
    public function updateFood($id, $name, $description, $price, $restaurantId, $imageUrl) {
        // Validasi input
        if (empty($id) || empty($name) || empty($description) || empty($price) || empty($restaurantId)) {
            return [
                'success' => false,
                'message' => 'Semua field harus diisi'
            ];
        }
        
        if (!is_numeric($price) || $price < 0) {
            return [
                'success' => false,
                'message' => 'Harga harus berupa angka positif'
            ];
        }
        
        if ($this->foodModel->updateFood($id, $name, $description, $price, $restaurantId, $imageUrl)) {
            return [
                'success' => true,
                'message' => 'Data makanan berhasil diperbarui'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Gagal memperbarui data makanan'
            ];
        }
    }
    
    // Fungsi untuk menghapus makanan
    public function deleteFood($id) {
        if ($this->foodModel->deleteFood($id)) {
            return [
                'success' => true,
                'message' => 'Makanan berhasil dihapus'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Gagal menghapus makanan'
            ];
        }
    }
}