<?php
require_once __DIR__ . '/../../config/config.php';
require_once 'restaurant.model.php';

class RestaurantController {
    private $restaurantModel;
    
    public function __construct() {
        $this->restaurantModel = new RestaurantModel();
    }
    
    // Fungsi untuk mendapatkan semua restoran
    public function getAllRestaurants() {
        return $this->restaurantModel->getAllRestaurants();
    }
    
    // Fungsi untuk mendapatkan restoran berdasarkan ID
    public function getRestaurantById($id) {
        return $this->restaurantModel->getRestaurantById($id);
    }
    
    // Fungsi untuk mendapatkan restoran berdasarkan nama
    public function getRestaurantsByName($name) {
        return $this->restaurantModel->getRestaurantsByName($name);
    }
    
    // Fungsi untuk menambahkan restoran baru
    public function addRestaurant($name, $description, $address, $phone, $operatingHours, $imageUrl) {
        // Validasi input
        if (empty($name) || empty($description) || empty($address) || empty($phone) || empty($operatingHours)) {
            return [
                'success' => false,
                'message' => 'Semua field harus diisi'
            ];
        }
        
        if ($this->restaurantModel->addRestaurant($name, $description, $address, $phone, $operatingHours, $imageUrl)) {
            return [
                'success' => true,
                'message' => 'Restoran berhasil ditambahkan'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Gagal menambahkan restoran'
            ];
        }
    }
    
    // Fungsi untuk memperbarui data restoran
    public function updateRestaurant($id, $name, $description, $address, $phone, $operatingHours, $imageUrl) {
        // Validasi input
        if (empty($id) || empty($name) || empty($description) || empty($address) || empty($phone) || empty($operatingHours)) {
            return [
                'success' => false,
                'message' => 'Semua field harus diisi'
            ];
        }
        
        if ($this->restaurantModel->updateRestaurant($id, $name, $description, $address, $phone, $operatingHours, $imageUrl)) {
            return [
                'success' => true,
                'message' => 'Data restoran berhasil diperbarui'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Gagal memperbarui data restoran'
            ];
        }
    }
    
    // Fungsi untuk menghapus restoran
    public function deleteRestaurant($id) {
        if ($this->restaurantModel->deleteRestaurant($id)) {
            return [
                'success' => true,
                'message' => 'Restoran berhasil dihapus'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Gagal menghapus restoran'
            ];
        }
    }
}