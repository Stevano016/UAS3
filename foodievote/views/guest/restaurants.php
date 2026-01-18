<?php
require_once '../modules/restaurants/restaurant.model.php';                    
$restaurantModel = new RestaurantModel();

if (isset($_GET['search']) && !empty($_GET['search'])) {
    $searchTerm = $_GET['search'];
    $restaurants = $restaurantModel->getRestaurantsByName($searchTerm);
} else {
    $restaurants = $restaurantModel->getAllRestaurants();
}
?>
<div class="container mt-5">
        <div class="row">
<h1>Daftar Restoran</h1>

<!-- Form pencarian -->
<form class="mb-4" method="GET">
    <input type="hidden" name="page" value="restaurants">
    <div class="input-group">
        <input type="text" class="form-control" placeholder="Cari restoran..." name="search" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
        <button class="btn btn-outline-secondary" type="submit">Cari</button>
    </div>
</form>

<!-- Daftar restoran -->
<div class="row">
    <?php
    if (empty($restaurants)) {
        echo '<div class="col-12"><p class="text-center">Tidak ada restoran ditemukan.</p></div>';
    } else {
        foreach ($restaurants as $restaurant) {
            $avgRating = $restaurant['avg_rating'] ? round($restaurant['avg_rating'], 1) : 0;
            $totalRatings = $restaurant['total_ratings'] ?? 0;
            
            echo '<div class="col-md-4 mb-4">';
            echo '<div class="card h-100">';
            
            if ($restaurant['image_url']) {
                // Check if image_url is a relative path and prepend BASE_URL if needed
                $imageSrc = $restaurant['image_url'];
                if (strpos($restaurant['image_url'], 'http') !== 0) {
                    // If it doesn't start with http, treat as relative path
                    if (strpos($restaurant['image_url'], '/') === 0) {
                        // If it starts with '/', it's relative to root
                        $imageSrc = BASE_URL . $restaurant['image_url'];
                    } else {
                        // If it doesn't start with '/', prepend BASE_URL
                        $imageSrc = BASE_URL . '/' . $restaurant['image_url'];
                    }
                }
                echo '<img src="' . $imageSrc . '" class="card-img-top" alt="' . htmlspecialchars($restaurant['name']) . '" style="height: 200px; object-fit: cover;">';
            }
            
            echo '<div class="card-body">';
            echo '<h5 class="card-title">' . htmlspecialchars($restaurant['name']) . '</h5>';
            echo '<p class="card-text">' . htmlspecialchars(substr($restaurant['description'], 0, 100)) . '...</p>';
            echo '<p class="card-text"><small class="text-muted">Alamat: ' . htmlspecialchars($restaurant['address']) . '</small></p>';
            echo '<div class="d-flex justify-content-between align-items-center">';
            echo '<div>';
            echo '<span class="badge bg-warning text-dark">' . $avgRating . ' ★</span>';
            echo '<small>(' . $totalRatings . ' rating)</small>';
            echo '</div>';
            echo '<a href="index.php?page=restaurant-detail&id=' . $restaurant['id'] . '" class="btn btn-primary">Lihat Detail</a>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
    }
    ?>
</div>