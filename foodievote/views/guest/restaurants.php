<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Restoran - FoodieVote</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="../guest/index.php">FoodieVote</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="../guest/index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="restaurants.php">Restoran</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="foods.php">Makanan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../../public/login.php">Login</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <h1>Daftar Restoran</h1>
                
                <!-- Form pencarian -->
                <form class="mb-4" method="GET">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Cari restoran..." name="search" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                        <button class="btn btn-outline-secondary" type="submit">Cari</button>
                    </div>
                </form>
                
                <!-- Daftar restoran -->
                <div class="row">
                    <?php
                    require_once '../../modules/restaurants/restaurant.model.php';
                    
                    $restaurantModel = new RestaurantModel();
                    
                    if (isset($_GET['search']) && !empty($_GET['search'])) {
                        $searchTerm = $_GET['search'];
                        $restaurants = $restaurantModel->getRestaurantsByName($searchTerm);
                    } else {
                        $restaurants = $restaurantModel->getAllRestaurants();
                    }
                    
                    foreach ($restaurants as $restaurant) {
                        $avgRating = $restaurant['avg_rating'] ? round($restaurant['avg_rating'], 1) : 0;
                        $totalRatings = $restaurant['total_ratings'] ?? 0;
                        
                        echo '<div class="col-md-4 mb-4">';
                        echo '<div class="card h-100">';
                        
                        if ($restaurant['image_url']) {
                            echo '<img src="' . $restaurant['image_url'] . '" class="card-img-top" alt="' . htmlspecialchars($restaurant['name']) . '" style="height: 200px; object-fit: cover;">';
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
                        echo '<a href="restaurant-detail.php?id=' . $restaurant['id'] . '" class="btn btn-primary">Lihat Detail</a>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                    }
                    
                    if (empty($restaurants)) {
                        echo '<div class="col-12"><p class="text-center">Tidak ada restoran ditemukan.</p></div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-light mt-5 py-4">
        <div class="container text-center">
            <p>&copy; 2023 FoodieVote. Semua hak dilindungi.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>