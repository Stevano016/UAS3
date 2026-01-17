<?php
require_once '../modules/foods/food.model.php';                    
$foodModel = new FoodModel();

if (isset($_GET['search']) && !empty($_GET['search'])) {
    $searchTerm = $_GET['search'];
    $foods = $foodModel->getFoodsByName($searchTerm);
} else {
    $foods = $foodModel->getAllFoods();
}

// Check if $foods is not empty before iterating
if (!empty($foods)) {
    echo '<h1>Daftar Makanan</h1>';
    
    // Form pencarian
    echo '<form class="mb-4" method="GET">';
    echo '<div class="input-group">';
    echo '<input type="text" class="form-control" placeholder="Cari makanan..." name="search" value="' . (isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '') . '">';
    echo '<button class="btn btn-outline-secondary" type="submit">Cari</button>';
    echo '</div>';
    echo '</form>';
    
    echo '<div class="row">';
    foreach ($foods as $food) {
        $avgRating = $food['avg_rating'] ? round($food['avg_rating'], 1) : 0;
        $totalRatings = $food['total_ratings'] ?? 0;
        
        echo '<div class="col-md-4 mb-4">';
        echo '<div class="card h-100">';
        
        if ($food['image_url']) {
            echo '<img src="' . $food['image_url'] . '" class="card-img-top" alt="' . htmlspecialchars($food['name']) . '" style="height: 200px; object-fit: cover;">';
        }
        
        echo '<div class="card-body">';
        echo '<h5 class="card-title">' . htmlspecialchars($food['name']);
        echo '</h5>';
        echo '<p class="card-text">' . htmlspecialchars(substr($food['description'], 0, 100)) . '...</p>';
        echo '<p class="card-text">Restoran: ' . htmlspecialchars($food['restaurant_name']) . '</p>';
        echo '<p class="card-text">Harga: Rp ' . number_format($food['price'], 0, ',', '.') . '</p>';
        echo '<div class="d-flex justify-content-between align-items-center">';
        echo '<div>';
        echo '<span class="badge bg-warning text-dark">' . $avgRating . ' ★</span>';
        echo '<small>(' . $totalRatings . ' rating)</small>';
        echo '</div>';
        echo '<a href="index.php?page=food-detail&id=' . $food['id'] . '" class="btn btn-primary">Lihat Detail</a>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }
    echo '</div>';
} else {
    echo '<div class="col-12"><p class="text-center">Tidak ada makanan ditemukan.</p></div>';
}
?>