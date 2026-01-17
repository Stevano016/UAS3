<?php
require_once '../core/middleware.php';
require_once '../modules/ratings/rating.model.php';
require_once '../modules/ratings/rating.controller.php';
require_once '../modules/users/user.model.php';
require_once '../modules/foods/food.model.php';
require_once '../modules/restaurants/restaurant.model.php';

requireLogin();
requireAdmin();

$ratingModel = new RatingModel();
$ratingController = new RatingController();
$userModel = new UserModel();
$foodModel = new FoodModel();
$restaurantModel = new RestaurantModel();

$ratings = $ratingModel->getAllRatings();

$message = '';
$messageType = '';

// Check for messages from redirects
if (isset($_GET['message']) && isset($_GET['messageType'])) {
    $message = htmlspecialchars($_GET['message']);
    $messageType = htmlspecialchars($_GET['messageType']);
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_rating'])) {
        $id = $_POST['rating_id'];
        $ratingValue = $_POST['rating_value'];
        $review = trim($_POST['review']);
        
        if (empty($id) || empty($ratingValue) || empty($review)) {
            $message = 'Semua field harus diisi';
            $messageType = 'danger';
        } else {
            $result = $ratingController->updateRating($id, $ratingValue, $review);
            $message = $result['message'];
            $messageType = $result['success'] ? 'success' : 'danger';
            
            if ($result['success']) {
                header('Location: index.php?page=manage-ratings&message=' . urlencode($message) . '&messageType=' . urlencode($messageType));
                exit();
            }
        }
    } elseif (isset($_POST['delete_rating'])) {
        $id = $_POST['rating_id'];
        
        $result = $ratingController->deleteRating($id);
        $message = $result['message'];
        $messageType = $result['success'] ? 'success' : 'danger';
        
        if ($result['success']) {
            header('Location: index.php?page=manage-ratings&message=' . urlencode($message) . '&messageType=' . urlencode($messageType));
            exit();
        }
    }
}
?>

<h1>Kelola Rating</h1>

<?php if ($message): ?>
    <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
        <?php echo $message; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- Daftar Rating -->
<div class="card">
    <div class="card-header">
        <h5>Daftar Rating dan Ulasan</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Item</th>
                        <th>Rating</th>
                        <th>Ulasan</th>
                        <th>Tanggal</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($ratings as $rating): ?>
                        <tr>
                            <td><?php echo $rating['id']; ?></td>
                            <td><?php echo htmlspecialchars($rating['username']); ?></td>
                            <td>
                                <?php if ($rating['food_name']): ?>
                                    <?php echo htmlspecialchars($rating['food_name']); ?> (Makanan)<br>
                                    <small class="text-muted">di <?php echo htmlspecialchars($rating['restaurant_name']); ?></small>
                                <?php else: ?>
                                    <?php echo htmlspecialchars($rating['restaurant_name']); ?> (Restoran)
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <span class="text-warning"><?php echo $i <= $rating['rating'] ? '★' : '☆'; ?></span>
                                <?php endfor; ?>
                            </td>
                            <td><?php echo htmlspecialchars(substr($rating['review'], 0, 50)); ?><?php echo strlen($rating['review']) > 50 ? '...' : ''; ?></td>
                            <td><?php echo isset($rating['created_at']) ? date('d/m/Y H:i', strtotime($rating['created_at'])) : '-'; ?></td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $rating['id']; ?>">Edit</button>
                                <form method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus rating ini?')">
                                    <input type="hidden" name="rating_id" value="<?php echo $rating['id']; ?>">
                                    <button type="submit" name="delete_rating" class="btn btn-sm btn-outline-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Edit - Dipindahkan keluar dari tabel -->
<?php foreach ($ratings as $rating): ?>
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
                            <label class="form-label">User: <?php echo htmlspecialchars($rating['username']); ?></label>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">
                                Item: 
                                <?php if ($rating['food_name']): ?>
                                    <?php echo htmlspecialchars($rating['food_name']); ?> (Makanan) di <?php echo htmlspecialchars($rating['restaurant_name']); ?>
                                <?php else: ?>
                                    <?php echo htmlspecialchars($rating['restaurant_name']); ?> (Restoran)
                                <?php endif; ?>
                            </label>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Rating Saat Ini: <?php echo $rating['rating']; ?> ★</label>
                        </div>
                        <div class="mb-3">
                            <label for="edit_rating_<?php echo $rating['id']; ?>" class="form-label">Rating Baru</label>
                            <select class="form-select" id="edit_rating_<?php echo $rating['id']; ?>" name="rating_value" required>
                                <option value="1" <?php echo $rating['rating'] == 1 ? 'selected' : ''; ?>>1 Bintang</option>
                                <option value="2" <?php echo $rating['rating'] == 2 ? 'selected' : ''; ?>>2 Bintang</option>
                                <option value="3" <?php echo $rating['rating'] == 3 ? 'selected' : ''; ?>>3 Bintang</option>
                                <option value="4" <?php echo $rating['rating'] == 4 ? 'selected' : ''; ?>>4 Bintang</option>
                                <option value="5" <?php echo $rating['rating'] == 5 ? 'selected' : ''; ?>>5 Bintang</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit_review_<?php echo $rating['id']; ?>" class="form-label">Ulasan</label>
                            <textarea class="form-control" id="edit_review_<?php echo $rating['id']; ?>" name="review" rows="3" required><?php echo htmlspecialchars($rating['review']); ?></textarea>
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
<?php endforeach; ?>