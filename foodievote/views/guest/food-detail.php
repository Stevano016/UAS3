<?php
require_once '../modules/foods/food.model.php';
require_once '../modules/ratings/rating.model.php';
require_once '../modules/ratings/rating.controller.php';

$foodModel = new FoodModel();
$ratingModel = new RatingModel();
$ratingController = new RatingController(); // Initialize controller

// Initialize message variables
$message = '';
$messageType = '';

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $foodId = $_GET['id'];
    $food = $foodModel->getFoodById($foodId);
    
    if ($food) {
        $avgRating = $food['avg_rating'] ? round($food['avg_rating'], 1) : 0;
        $totalRatings = $food['total_ratings'] ?? 0;
        
        // --- Handle Rating Submission ---
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_food_rating'])) {
            if (isLoggedIn() && isUser()) { // Ensure only logged-in users can submit
                $userId = getSession('user_id');
                $ratingValue = $_POST['rating_value'];
                $reviewText = trim($_POST['review_text']);

                $result = $ratingController->addOrUpdateRating($userId, $foodId, true, $ratingValue, $reviewText); // true for food item
                $message = $result['message'];
                $messageType = $result['success'] ? 'success' : 'danger';
                
                // Refresh ratings after submission
                $ratings = $ratingModel->getRatingsByFood($foodId);
            } else {
                $message = "Anda harus login sebagai user untuk memberikan rating.";
                $messageType = "danger";
            }
        } else {
            // Load initial ratings if not a rating submission
            $ratings = $ratingModel->getRatingsByFood($foodId);
        }
        // --- End Handle Rating Submission ---
?>
        <div class="col-md-8">
            <h1><?php echo htmlspecialchars($food['name']); ?></h1>
            
            <?php if ($food['image_url']): ?>
                <img src="<?php echo $food['image_url']; ?>" class="img-fluid rounded mb-3" alt="<?php echo htmlspecialchars($food['name']); ?>" style="max-height: 400px; object-fit: cover;">
            <?php endif; ?>
            
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Deskripsi</h5>
                    <p class="card-text"><?php echo htmlspecialchars($food['description']); ?></p>
                    
                    <h5 class="card-title">Informasi</h5>
                    <p class="card-text">
                        <strong>Restoran:</strong> <a href="index.php?page=restaurant-detail&id=<?php echo $food['restaurant_id']; ?>"><?php echo htmlspecialchars($food['restaurant_name']); ?></a><br>
                        <strong>Harga:</strong> Rp <?php echo number_format($food['price'], 0, ',', '.'); ?>
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
                        <input type="hidden" name="food_id" value="<?php echo $foodId; ?>">
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
                        <button type="submit" name="submit_food_rating" class="btn btn-primary">Kirim Rating</button>
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
                <p>Belum ada ulasan untuk makanan ini.</p>
            <?php endif; ?>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>Info Restoran</h5>
                </div>
                <div class="card-body">
                    <?php
                    require_once '../modules/restaurants/restaurant.model.php';
                    $restaurantModel = new RestaurantModel();
                    $restaurant = $restaurantModel->getRestaurantById($food['restaurant_id']);
                    
                    if ($restaurant) {
                        $avgRestaurantRating = $restaurant['avg_rating'] ? round($restaurant['avg_rating'], 1) : 0;
                        $totalRestaurantRatings = $restaurant['total_ratings'] ?? 0;
                        
                        echo '<p class="mb-1">Alamat: ' . htmlspecialchars($restaurant['address']) . '</p>';
                        echo '<p class="mb-1">Telepon: ' . htmlspecialchars($restaurant['phone']) . '</p>';
                        echo '<div class="mt-2">';
                        echo '<span class="text-warning">' . $avgRestaurantRating . ' ★</span>';
                        echo '<small> (' . $totalRestaurantRatings . ' rating)</small>';
                        echo '</div>';
                        echo '<a href="index.php?page=restaurant-detail&id=' . $restaurant['id'] . '" class="btn btn-sm btn-outline-primary mt-2">Lihat Restoran</a>';
                    }
                    ?>
                </p>
            </div>
        </div>
<?php
    } else {
        echo '<div class="col-12"><p class="text-center">Makanan tidak ditemukan.</p></div>';
    }
} else {
    echo '<div class="col-12"><p class="text-center">ID makanan tidak valid.</p></div>';
}
?>
