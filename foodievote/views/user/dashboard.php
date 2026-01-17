<?php
require_once '../core/middleware.php';
require_once '../modules/users/user.model.php';
require_once '../modules/ratings/rating.model.php';
require_once '../modules/restaurants/restaurant.model.php';
require_once '../modules/foods/food.model.php';

requireLogin();
requireUser();

$userModel = new UserModel();
$ratingModel = new RatingModel();
$restaurantModel = new RestaurantModel();
$foodModel = new FoodModel();

$userId = getSession('user_id');
$user = $userModel->getUserById($userId);
$userRatings = $ratingModel->getRatingsByUser($userId);

// Calculate user statistics
$totalRatings = count($userRatings);
$avgRating = 0;
if ($totalRatings > 0) {
    $sumRatings = array_sum(array_column($userRatings, 'rating'));
    $avgRating = round($sumRatings / $totalRatings, 1);
}

// Count food vs restaurant ratings
$foodRatings = array_filter($userRatings, function($r) { return !empty($r['food_id']); });
$restaurantRatings = array_filter($userRatings, function($r) { return empty($r['food_id']); });

// Get recent ratings
$recentRatings = array_slice($userRatings, 0, 3);

// Get total items available
$totalRestaurants = count($restaurantModel->getAllRestaurants());
$totalFoods = count($foodModel->getAllFoods());
?>

<style>
.user-dashboard-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 2.5rem;
    border-radius: 20px;
    margin-bottom: 2rem;
    box-shadow: 0 10px 40px rgba(102, 126, 234, 0.3);
    position: relative;
    overflow: hidden;
}

.user-dashboard-header::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -10%;
    width: 300px;
    height: 300px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
}

.welcome-badge {
    display: inline-block;
    background: rgba(255, 255, 255, 0.2);
    padding: 0.5rem 1.5rem;
    border-radius: 50px;
    font-size: 0.9rem;
    margin-bottom: 1rem;
    backdrop-filter: blur(10px);
}

.user-greeting {
    font-size: 2.2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.user-subtitle {
    font-size: 1.1rem;
    opacity: 0.95;
}

.stat-card-user {
    background: white;
    border-radius: 20px;
    padding: 1.8rem;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    border: none;
    height: 100%;
    position: relative;
    overflow: hidden;
}

.stat-card-user::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
    background: linear-gradient(180deg, #667eea, #764ba2);
}

.stat-card-user:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
}

.stat-icon-user {
    font-size: 2.5rem;
    margin-bottom: 1rem;
}

.stat-number-user {
    font-size: 2.8rem;
    font-weight: 700;
    color: #667eea;
    margin-bottom: 0.3rem;
}

.stat-label-user {
    color: #6c757d;
    font-size: 0.95rem;
    font-weight: 500;
}

.action-card {
    background: white;
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    border: none;
    height: 100%;
    text-decoration: none;
    display: block;
    position: relative;
    overflow: hidden;
}

.action-card::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 5px;
    background: linear-gradient(90deg, #667eea, #764ba2);
    transform: scaleX(0);
    transition: transform 0.3s ease;
}

.action-card:hover::after {
    transform: scaleX(1);
}

.action-card:hover {
    transform: translateY(-10px) scale(1.02);
    box-shadow: 0 15px 40px rgba(102, 126, 234, 0.2);
}

.action-icon {
    font-size: 3rem;
    margin-bottom: 1.5rem;
    display: block;
}

.action-title {
    font-size: 1.4rem;
    font-weight: 700;
    margin-bottom: 1rem;
    color: #2d3748;
}

.action-description {
    color: #718096;
    margin-bottom: 1.5rem;
    line-height: 1.6;
}

.action-button {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.7rem 1.5rem;
    border-radius: 50px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.recent-activity {
    background: white;
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
}

.activity-header {
    display: flex;
    justify-content: between;
    align-items: center;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid #f0f0f0;
}

.activity-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #2d3748;
    margin: 0;
}

.rating-item {
    padding: 1.2rem;
    border-radius: 12px;
    background: #f8f9fa;
    margin-bottom: 1rem;
    transition: all 0.3s ease;
}

.rating-item:hover {
    background: #e9ecef;
    transform: translateX(5px);
}

.rating-stars {
    color: #ffc107;
    font-size: 1.2rem;
    margin-bottom: 0.5rem;
}

.rating-review {
    color: #495057;
    font-style: italic;
    margin-top: 0.5rem;
}

.empty-state {
    text-align: center;
    padding: 3rem 1rem;
}

.empty-icon {
    font-size: 4rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.fade-in-up {
    animation: fadeInUp 0.6s ease;
}

.progress-circle {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: conic-gradient(#667eea 0deg, #764ba2 360deg);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
}

.progress-inner {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    background: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    font-weight: 700;
    color: #667eea;
}
</style>

<!-- Dashboard Header -->
<div class="user-dashboard-header fade-in-up">
    <div class="welcome-badge">👤 User Dashboard</div>
    <div class="user-greeting">Halo, <?php echo htmlspecialchars($user['username']); ?>! 👋</div>
    <div class="user-subtitle">Kelola rating dan review Anda dengan mudah</div>
</div>

<!-- Statistics Overview -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-4 fade-in-up" style="animation-delay: 0.1s">
        <div class="stat-card-user">
            <div class="stat-icon-user">📊</div>
            <div class="stat-number-user"><?php echo $totalRatings; ?></div>
            <div class="stat-label-user">Total Rating Saya</div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-4 fade-in-up" style="animation-delay: 0.2s">
        <div class="stat-card-user">
            <div class="stat-icon-user">⭐</div>
            <div class="stat-number-user"><?php echo $avgRating; ?></div>
            <div class="stat-label-user">Rating Rata-rata</div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-4 fade-in-up" style="animation-delay: 0.3s">
        <div class="stat-card-user">
            <div class="stat-icon-user">🍔</div>
            <div class="stat-number-user"><?php echo count($foodRatings); ?></div>
            <div class="stat-label-user">Rating Makanan</div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-4 fade-in-up" style="animation-delay: 0.4s">
        <div class="stat-card-user">
            <div class="stat-icon-user">🏪</div>
            <div class="stat-number-user"><?php echo count($restaurantRatings); ?></div>
            <div class="stat-label-user">Rating Restoran</div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mb-4">
    <div class="col-12 mb-3">
        <h3 style="font-weight: 700; color: #2d3748;">🚀 Aksi Cepat</h3>
    </div>
    <div class="col-lg-4 col-md-6 mb-4 fade-in-up" style="animation-delay: 0.5s">
        <a href="index.php?page=profile" class="action-card">
            <span class="action-icon">👤</span>
            <h5 class="action-title">Profil Saya</h5>
            <p class="action-description">Lihat dan kelola informasi akun pribadi Anda</p>
            <span class="action-button btn btn-outline-primary">
                Lihat Profil
                <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z"/>
                </svg>
            </span>
        </a>
    </div>
    <div class="col-lg-4 col-md-6 mb-4 fade-in-up" style="animation-delay: 0.6s">
        <a href="index.php?page=my-ratings" class="action-card">
            <span class="action-icon">⭐</span>
            <h5 class="action-title">Rating Saya</h5>
            <p class="action-description">Lihat semua rating dan ulasan yang telah Anda berikan</p>
            <span class="action-button btn btn-outline-success">
                Lihat Rating
                <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z"/>
                </svg>
            </span>
        </a>
    </div>
    <div class="col-lg-4 col-md-6 mb-4 fade-in-up" style="animation-delay: 0.7s">
        <a href="index.php?page=restaurants" class="action-card">
            <span class="action-icon">🔍</span>
            <h5 class="action-title">Berikan Rating</h5>
            <p class="action-description">Cari dan berikan penilaian untuk restoran atau makanan</p>
            <span class="action-button btn btn-outline-info">
                Mulai Rating
                <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z"/>
                </svg>
            </span>
        </a>
    </div>
</div>

<!-- Recent Activity -->
<div class="row">
    <div class="col-12 fade-in-up" style="animation-delay: 0.8s">
        <div class="recent-activity">
            <div class="activity-header">
                <h4 class="activity-title">📝 Aktivitas Terbaru</h4>
                <?php if ($totalRatings > 0): ?>
                    <a href="index.php?page=my-ratings" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                <?php endif; ?>
            </div>
            
            <?php if (!empty($recentRatings)): ?>
                <?php foreach ($recentRatings as $rating): ?>
                    <div class="rating-item">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <div class="rating-stars">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <?php echo $i <= $rating['rating'] ? '★' : '☆'; ?>
                                    <?php endfor; ?>
                                </div>
                                <strong>
                                    <?php 
                                    if (!empty($rating['food_name'])) {
                                        echo htmlspecialchars($rating['food_name']) . ' (Makanan)';
                                    } else {
                                        echo htmlspecialchars($rating['restaurant_name'] ?? 'Item') . ' (Restoran)';
                                    }
                                    ?>
                                </strong>
                                <?php if (!empty($rating['restaurant_name']) && !empty($rating['food_name'])): ?>
                                    <small class="text-muted d-block">di <?php echo htmlspecialchars($rating['restaurant_name']); ?></small>
                                <?php endif; ?>
                                <div class="rating-review">
                                    "<?php echo htmlspecialchars($rating['review'] ?? ''); ?>"
                                </div>
                            </div>
                            <small class="text-muted ms-3">
                                <?php echo isset($rating['created_at']) ? date('d M Y', strtotime($rating['created_at'])) : '-'; ?>
                            </small>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="empty-state">
                    <div class="empty-icon">📭</div>
                    <h5 class="text-muted mb-2">Belum Ada Rating</h5>
                    <p class="text-muted">Mulai berikan rating untuk restoran atau makanan favorit Anda!</p>
                    <a href="index.php?page=restaurants" class="btn btn-primary mt-3">
                        🔍 Cari Item untuk Dirating
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Explore More Section -->
<?php if ($totalRatings > 0): ?>
<div class="row mt-4">
    <div class="col-12 fade-in-up" style="animation-delay: 0.9s">
        <div class="recent-activity" style="background: linear-gradient(135deg, #667eea15 0%, #764ba215 100%);">
            <div class="text-center py-4">
                <h5 style="font-weight: 700; color: #2d3748; margin-bottom: 1rem;">
                    🎯 Masih Ada <?php echo ($totalRestaurants + $totalFoods) - $totalRatings; ?> Item yang Belum Dirating!
                </h5>
                <p class="text-muted mb-3">Bantu komunitas dengan memberikan review Anda</p>
                <a href="index.php?page=restaurants" class="btn btn-primary btn-lg">
                    Jelajahi Lebih Banyak →
                </a>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>