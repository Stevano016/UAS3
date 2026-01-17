<?php
require_once '../core/middleware.php';
require_once '../modules/users/user.model.php';
require_once '../modules/restaurants/restaurant.model.php';
require_once '../modules/foods/food.model.php';
require_once '../modules/ratings/rating.model.php';

requireLogin();
requireAdmin();

$userModel = new UserModel();
$restaurantModel = new RestaurantModel();
$foodModel = new FoodModel();
$ratingModel = new RatingModel();

$currentUser = $userModel->getUserById(getSession('user_id'));

// Get statistics
$totalUsers = count($userModel->getAllUsers());
$totalRestaurants = count($restaurantModel->getAllRestaurants());
$totalFoods = count($foodModel->getAllFoods());
$totalRatings = count($ratingModel->getAllRatings());

// Get recent activities (you can customize this based on your needs)
$recentRatings = array_slice($ratingModel->getAllRatings(), 0, 5);
?>

<!-- Dashboard Header -->
<div class="dashboard-header fade-in-up">
    <div class="greeting-text">👋 Selamat Datang Kembali!</div>
    <div class="welcome-subtitle">
        Halo, <strong><?php echo htmlspecialchars($currentUser['username']); ?></strong> - Kelola sistem FoodieVote dengan mudah
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-4 fade-in-up" style="animation-delay: 0.1s">
        <div class="stat-card bg-primary text-white">
            <div class="card-body">
                <div class="stat-icon"></div>
                <div class="stat-number"><?php echo $totalUsers; ?></div>
                <div class="stat-label">Total Pengguna</div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-4 fade-in-up" style="animation-delay: 0.2s">
        <div class="stat-card bg-success text-white">
            <div class="card-body">
                <div class="stat-icon"></div>
                <div class="stat-number"><?php echo $totalRestaurants; ?></div>
                <div class="stat-label">Total Restoran</div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-4 fade-in-up" style="animation-delay: 0.3s">
        <div class="stat-card bg-info text-white">
            <div class="card-body">
                <div class="stat-icon"></div>
                <div class="stat-number"><?php echo $totalFoods; ?></div>
                <div class="stat-label">Total Makanan</div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-4 fade-in-up" style="animation-delay: 0.4s">
        <div class="stat-card bg-warning text-white">
            <div class="card-body">
                <div class="stat-icon"></div>
                <div class="stat-number"><?php echo $totalRatings; ?></div>
                <div class="stat-label">Total Rating</div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mb-4">
    <div class="col-12 mb-3">
        <h4> Aksi Cepat</h4>
    </div>
    <div class="col-lg-3 col-md-6 mb-4 fade-in-up" style="animation-delay: 0.5s">
        <a href="index.php?page=manage-users" class="text-decoration-none">
            <div class="quick-action-card">
                <div class="card-body text-center">
                    <div class="quick-action-icon"></div>
                    <h5 class="card-title text-primary">Kelola User</h5>
                    <p class="card-text text-muted">Tambah, edit, atau hapus akun pengguna</p>
                    <span class="btn btn-sm btn-outline-primary">Kelola →</span>
                </div>
            </div>
        </a>
    </div>
    <div class="col-lg-3 col-md-6 mb-4 fade-in-up" style="animation-delay: 0.6s">
        <a href="index.php?page=manage-restaurants" class="text-decoration-none">
            <div class="quick-action-card">
                <div class="card-body text-center">
                    <div class="quick-action-icon"></div>
                    <h5 class="card-title text-success">Kelola Restoran</h5>
                    <p class="card-text text-muted">Tambah, edit, atau hapus data restoran</p>
                    <span class="btn btn-sm btn-outline-success">Kelola →</span>
                </div>
            </div>
        </a>
    </div>
    <div class="col-lg-3 col-md-6 mb-4 fade-in-up" style="animation-delay: 0.7s">
        <a href="index.php?page=manage-foods" class="text-decoration-none">
            <div class="quick-action-card">
                <div class="card-body text-center">
                    <div class="quick-action-icon"></div>
                    <h5 class="card-title text-info">Kelola Makanan</h5>
                    <p class="card-text text-muted">Tambah, edit, atau hapus data makanan</p>
                    <span class="btn btn-sm btn-outline-info">Kelola →</span>
                </div>
            </div>
        </a>
    </div>
    <div class="col-lg-3 col-md-6 mb-4 fade-in-up" style="animation-delay: 0.8s">
        <a href="index.php?page=manage-ratings" class="text-decoration-none">
            <div class="quick-action-card">
                <div class="card-body text-center">
                    <div class="quick-action-icon"></div>
                    <h5 class="card-title text-warning">Kelola Rating</h5>
                    <p class="card-text text-muted">Moderasi dan tinjau rating pengguna</p>
                    <span class="btn btn-sm btn-outline-warning">Kelola →</span>
                </div>
            </div>
        </a>
    </div>
</div>

<!-- Recent Activity -->
<div class="row">
    <div class="col-12 mb-3">
        <h4> Aktivitas Terbaru</h4>
    </div>
    <div class="col-12 fade-in-up" style="animation-delay: 0.9s">
        <div class="recent-activity-card">
            <div class="card-body">
                <?php if (!empty($recentRatings)): ?>
                    <?php foreach ($recentRatings as $rating): ?>
                        <div class="activity-item">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <strong><?php echo htmlspecialchars($rating['username'] ?? 'Unknown'); ?></strong>
                                    memberikan rating
                                    <span class="badge-custom bg-warning text-dark">
                                        <?php echo $rating['rating']; ?> ⭐
                                    </span>
                                    untuk
                                    <strong>
                                        <?php 
                                        if (!empty($rating['food_name'])) {
                                            echo htmlspecialchars($rating['food_name']);
                                        } else {
                                            echo htmlspecialchars($rating['restaurant_name'] ?? 'Item');
                                        }
                                        ?>
                                    </strong>
                                    <div class="text-muted small mt-1">
                                        "<?php echo htmlspecialchars(substr($rating['review'] ?? '', 0, 80)); ?><?php echo strlen($rating['review'] ?? '') > 80 ? '...' : ''; ?>"
                                    </div>
                                </div>
                                <small class="text-muted">
                                    <?php echo isset($rating['created_at']) ? date('d/m/Y', strtotime($rating['created_at'])) : '-'; ?>
                                </small>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <div class="text-center mt-3">
                        <a href="index.php?page=manage-ratings" class="btn btn-sm btn-outline-primary">
                            Lihat Semua Rating →
                        </a>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <div class="mb-3" style="font-size: 3rem;">📝</div>
                        <h5 class="text-muted">Belum Ada Aktivitas</h5>
                        <p class="text-muted">Aktivitas rating akan muncul di sini</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>