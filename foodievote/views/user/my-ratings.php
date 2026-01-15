<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rating Saya - FoodieVote</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <?php
    require_once '../../core/middleware.php';
    require_once '../../modules/ratings/rating.model.php';
    require_once '../../modules/ratings/rating.controller.php';
    
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
    
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="../guest/index.php">FoodieVote</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="profile.php">Profil Saya</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="my-ratings.php">Rating Saya</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../../../public/logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
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
                                        <?php if ($rating['food_name']): ?>
                                            <a href="../guest/food-detail.php?id=<?php echo $rating['food_id']; ?>"><?php echo htmlspecialchars($rating['food_name']); ?></a>
                                            <span class="text-muted">di</span>
                                            <a href="../guest/restaurant-detail.php?id=<?php echo $rating['restaurant_id']; ?>"><?php echo htmlspecialchars($rating['restaurant_name']); ?></a>
                                        <?php else: ?>
                                            <a href="../guest/restaurant-detail.php?id=<?php echo $rating['restaurant_id']; ?>"><?php echo htmlspecialchars($rating['restaurant_name']); ?></a>
                                        <?php endif; ?>
                                    </h6>
                                    <small class="text-muted"><?php echo date('d M Y', strtotime($rating['created_at'])); ?></small>
                                </div>
                                
                                <div class="mb-2">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <span class="text-warning"><?php echo $i <= $rating['rating'] ? '★' : '☆'; ?></span>
                                    <?php endfor; ?>
                                </div>
                                
                                <p class="card-text"><?php echo htmlspecialchars($rating['review']); ?></p>
                                
                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $rating['id']; ?>">Edit</button>
                                <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal<?php echo $rating['id']; ?>">Hapus</button>
                                
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
                                                        <textarea class="form-control" name="review" rows="3" required><?php echo htmlspecialchars($rating['review']); ?></textarea>
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
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Anda belum memberikan rating apapun.</p>
                <?php endif; ?>
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