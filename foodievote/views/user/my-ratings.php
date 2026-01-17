<?php
require_once '../core/middleware.php';
require_once '../modules/ratings/rating.model.php';
require_once '../modules/ratings/rating.controller.php';

requireLogin();
requireUser();

$ratingModel = new RatingModel();
$ratingController = new RatingController();

$userId = getSession('user_id');
$userRatings = $ratingModel->getRatingsByUser($userId);

$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_rating'])) {
        $ratingId = $_POST['rating_id'];
        $ratingValue = $_POST['rating_value'];
        $review = $_POST['review'];
        
        $result = $ratingController->updateRating($ratingId, $ratingValue, $review);
        $message = $result['message'];
        $messageType = $result['success'] ? 'success' : 'danger';
        
        // Refresh data setelah update
        $userRatings = $ratingModel->getRatingsByUser($userId);
    } elseif (isset($_POST['delete_rating'])) {
        $ratingId = $_POST['rating_id'];
        
        $result = $ratingController->deleteRating($ratingId);
        $message = $result['message'];
        $messageType = $result['success'] ? 'success' : 'danger';
        
        // Refresh data setelah delete
        $userRatings = $ratingModel->getRatingsByUser($userId);
    }
}
?>

<h1>Rating Saya</h1>

<?php if ($message): ?>
    <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
        <?php echo htmlspecialchars($message); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (!empty($userRatings)): ?>
    <?php foreach ($userRatings as $rating): ?>
        <div class="card mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <h6 class="card-subtitle mb-2">
                        <?php if (!empty($rating['food_name'])): ?>
                            <a href="index.php?page=food-detail&id=<?php echo $rating['food_id']; ?>"><?php echo htmlspecialchars($rating['food_name']); ?></a>
                            <span class="text-muted">di</span>
                            <a href="index.php?page=restaurant-detail&id=<?php echo $rating['restaurant_id']; ?>"><?php echo htmlspecialchars($rating['restaurant_name'] ?? 'Restoran Tidak Diketahui'); ?></a>
                        <?php else: ?>
                            <a href="index.php?page=restaurant-detail&id=<?php echo $rating['restaurant_id']; ?>"><?php echo htmlspecialchars($rating['restaurant_name'] ?? 'Restoran Tidak Diketahui'); ?></a>
                        <?php endif; ?>
                    </h6>
                    <small class="text-muted"><?php echo date('d M Y', strtotime($rating['created_at'])); ?></small>
                </div>
                
                <div class="mb-2">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <span class="text-warning"><?php echo $i <= $rating['rating'] ? '★' : '☆'; ?></span>
                    <?php endfor; ?>
                </div>
                
                <p class="card-text"><?php echo htmlspecialchars($rating['review'] ?? ''); ?></p>
                
                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $rating['id']; ?>">Edit</button>
                <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal<?php echo $rating['id']; ?>">Hapus</button>
            </div>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p>Anda belum memberikan rating apapun.</p>
<?php endif; ?>

<!-- Modals - Dipindahkan keluar dari loop card -->
<?php foreach ($userRatings as $rating): ?>
    <!-- Modal Edit -->
    <div class="modal fade" id="editModal<?php echo $rating['id']; ?>" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Rating</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="rating_id" value="<?php echo $rating['id']; ?>">
                        <div class="mb-3">
                            <label class="form-label">Rating</label>
                            <select class="form-select" name="rating_value" required>
                                <option value="1" <?php echo $rating['rating'] == 1 ? 'selected' : ''; ?>>1 Bintang</option>
                                <option value="2" <?php echo $rating['rating'] == 2 ? 'selected' : ''; ?>>2 Bintang</option>
                                <option value="3" <?php echo $rating['rating'] == 3 ? 'selected' : ''; ?>>3 Bintang</option>
                                <option value="4" <?php echo $rating['rating'] == 4 ? 'selected' : ''; ?>>4 Bintang</option>
                                <option value="5" <?php echo $rating['rating'] == 5 ? 'selected' : ''; ?>>5 Bintang</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Ulasan</label>
                            <textarea class="form-control" name="review" rows="3" required><?php echo htmlspecialchars($rating['review'] ?? ''); ?></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" name="update_rating" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Modal Hapus -->
    <div class="modal fade" id="deleteModal<?php echo $rating['id']; ?>" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="rating_id" value="<?php echo $rating['id']; ?>">
                        <p>Apakah Anda yakin ingin menghapus rating ini?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" name="delete_rating" class="btn btn-danger">Hapus</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endforeach; ?>