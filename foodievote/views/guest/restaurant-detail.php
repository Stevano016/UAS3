<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Restoran - FoodieVote</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
</head>
<body>
    <?php require_once '../views/partials/navbar_guest.php'; ?>

    <div class="container mt-5">
        <div class="row">
            <?php
            require_once '../modules/restaurants/restaurant.model.php';
            require_once '../modules/ratings/rating.model.php';
            require_once '../modules/ratings/rating.controller.php';
            
            $restaurantModel = new RestaurantModel();
            $ratingModel = new RatingModel();
            
            if (isset($_GET['id']) && !empty($_GET['id'])) {
                $restaurantId = $_GET['id'];
                $restaurant = $restaurantModel->getRestaurantById($restaurantId);
                
                if ($restaurant) {
                    $avgRating = $restaurant['avg_rating'] ? round($restaurant['avg_rating'], 1) : 0;
                    $totalRatings = $restaurant['total_ratings'] ?? 0;
                    $ratings = $ratingModel->getRatingsByRestaurant($restaurantId);
            ?>
                    <div class="col-md-8">
                        <h1><?php echo htmlspecialchars($restaurant['name']); ?></h1>
                        
                        <?php if ($restaurant['image_url']): ?>
                            <img src="<?php echo $restaurant['image_url']; ?>" class="img-fluid rounded mb-3" alt="<?php echo htmlspecialchars($restaurant['name']); ?>" style="max-height: 400px; object-fit: cover;">
                        <?php endif; ?>
                        
                        <div class="card mb-4">
                            <div class="card-body">
                                <h5 class="card-title">Deskripsi</h5>
                                <p class="card-text"><?php echo htmlspecialchars($restaurant['description']); ?></p>
                                
                                <h5 class="card-title">Informasi Kontak</h5>
                                <p class="card-text">
                                    <strong>Alamat:</strong> <?php echo htmlspecialchars($restaurant['address']); ?><br>
                                    <strong>Telepon:</strong> <?php echo htmlspecialchars($restaurant['phone']); ?><br>
                                    <strong>Jam Operasional:</strong> <?php echo htmlspecialchars($restaurant['operating_hours']); ?>
                                </p>
                                
                                <div class="mt-3">
                                    <span class="fs-4 fw-bold text-warning"><?php echo $avgRating; ?> ★</span>
                                    <span class="text-muted">(<?php echo $totalRatings; ?> rating)</span>
                                </div>
                            </div>
                        </div>

                        <?php if ($message): // Display messages here ?>
                            <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
                                <?php echo htmlspecialchars($message); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (isLoggedIn() && isUser()): // Show form only if logged in as user ?>
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5>Berikan Rating dan Ulasan Anda</h5>
                            </div>
                            <div class="card-body">
                                <form method="POST">
                                    <input type="hidden" name="restaurant_id" value="<?php echo $restaurantId; ?>">
                                    <div class="mb-3">
                                        <label for="rating_value" class="form-label">Rating</label>
                                        <select class="form-select" id="rating_value" name="rating_value" required>
                                            <option value="">Pilih Rating</option>
                                            <option value="1">1 Bintang</option>
                                            <option value="2">2 Bintang</option>
                                            <option value="3">3 Bintang</option>
                                            <option value="4">4 Bintang</option>
                                            <option value="5">5 Bintang</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="review_text" class="form-label">Ulasan Anda</label>
                                        <textarea class="form-control" id="review_text" name="review_text" rows="3"></textarea>
                                    </div>
                                    <button type="submit" name="submit_restaurant_rating" class="btn btn-primary">Kirim Rating</button>
                                </form>
                            </div>
                        </div>
                        <?php elseif (isLoggedIn() && isAdmin()): // Admin can't rate ?>
                            <div class="alert alert-info">Admin tidak dapat memberikan rating.</div>
                        <?php else: // Not logged in ?>
                            <div class="alert alert-info">Silakan <a href="login.php">login</a> untuk memberikan rating.</div>
                        <?php endif; ?>

                        <h3>Ulasan Pelanggan</h3>
                        <?php if (!empty($ratings)): ?>
                            <?php foreach ($ratings as $rating): ?>
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <h6 class="card-subtitle mb-2 text-muted"><?php echo htmlspecialchars($rating['username']); ?></h6>
                                            <small class="text-muted"><?php echo date('d M Y', strtotime($rating['created_at'])); ?></small>
                                        </div>
                                        <div class="mb-1">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <span class="text-warning"><?php echo $i <= $rating['rating'] ? '★' : '☆'; ?></span>
                                            <?php endfor; ?>
                                        </div>
                                        <p class="card-text"><?php echo htmlspecialchars($rating['review']); ?></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>Belum ada ulasan untuk restoran ini.</p>
                        <?php endif; ?>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h5>Makanan di <?php echo htmlspecialchars($restaurant['name']); ?></h5>
                            </div>
                            <div class="card-body">
                                <?php
                                require_once '../modules/foods/food.model.php';
                                $foodModel = new FoodModel();
                                $foods = $foodModel->getFoodsByRestaurant($restaurantId);
                                
                                if (!empty($foods)):
                                    foreach ($foods as $food):
                                        $avgFoodRating = $food['avg_rating'] ? round($food['avg_rating'], 1) : 0;
                                        $totalFoodRatings = $food['total_ratings'] ?? 0;
                                ?>
                                        <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                                            <div>
                                                <h6 class="mb-0"><?php echo htmlspecialchars($food['name']); ?></h6>
                                                <small>Rp <?php echo number_format($food['price'], 0, ',', '.'); ?></small>
                                            </div>
                                            <div class="text-end">
                                                <div>
                                                    <span class="text-warning"><?php echo $avgFoodRating; ?> ★</span>
                                                </div>
                                                <small>(<?php echo $totalFoodRatings; ?> rating)</small>
                                            </div>
                                        </div>
                                <?php
                                    endforeach;
                                else:
                                    echo "<p>Belum ada makanan yang terdaftar di restoran ini.</p>";
                                endif;
                                ?>
                            </div>
                        </div>
                    </div>
            <?php
                } else {
                    echo '<div class="col-12"><p class="text-center">Restoran tidak ditemukan.</p></div>';
                }
            } else {
                echo '<div class="col-12"><p class="text-center">ID restoran tidak valid.</p></div>';
            }
            ?>
        </div>
    </div>

    <?php require_once '../views/partials/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>