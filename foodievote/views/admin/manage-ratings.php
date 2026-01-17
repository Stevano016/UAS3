<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Rating - FoodieVote Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
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
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['update_rating'])) {
            $id = $_POST['rating_id'];
            $ratingValue = $_POST['rating_value'];
            $review = $_POST['review'];
            
            // Validasi
            if (empty($id) || empty($ratingValue)) {
                $message = 'ID dan rating harus diisi';
                $messageType = 'danger';
            } else {
                $result = $ratingController->updateRating($id, $ratingValue, $review);
                $message = $result['message'];
                $messageType = $result['success'] ? 'success' : 'danger';
                
                // Refresh data jika berhasil
                if ($result['success']) {
                    $ratings = $ratingModel->getAllRatings();
                }
            }
        } elseif (isset($_POST['delete_rating'])) {
            $id = $_POST['rating_id'];
            
            $result = $ratingController->deleteRating($id);
            $message = $result['message'];
            $messageType = $result['success'] ? 'success' : 'danger';
            
            // Refresh data jika berhasil
            if ($result['success']) {
                $ratings = $ratingModel->getAllRatings();
            }
        }
    }
    ?>
    
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">FoodieVote Admin</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?page=home">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?page=manage-users">Kelola User</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?page=manage-restaurants">Kelola Restoran</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?page=manage-foods">Kelola Makanan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php?page=manage-ratings">Kelola Rating</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            Halo, <?php echo htmlspecialchars(getSession('username')); ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <h1>Kelola Rating</h1>
                
                <?php if ($message): ?>
                    <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
                        <?php echo htmlspecialchars($message); ?>
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
                                        <th>Aksi</th>
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
                                            <td><?php echo date('d M Y', strtotime($rating['created_at'])); ?></td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $rating['id']; ?>">Edit</button>
                                                <form method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus rating ini?')">
                                                    <input type="hidden" name="rating_id" value="<?php echo $rating['id']; ?>">
                                                    <button type="submit" name="delete_rating" class="btn btn-sm btn-outline-danger">Hapus</button>
                                                </form>
                                            </td>
                                        </tr>
                                        
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
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-light mt-5 py-4">
        <div class="container text-center">
            <p>&copy; 2023 FoodieVote Admin Panel. Semua hak dilindungi.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>