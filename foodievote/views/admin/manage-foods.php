<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Makanan - FoodieVote Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
</head>
<body>
    <?php
    require_once '../core/middleware.php';
    require_once '../modules/foods/food.model.php';
    require_once '../modules/foods/food.controller.php';
    require_once '../modules/restaurants/restaurant.model.php';
    
    requireLogin();
    requireAdmin();
    
    $foodModel = new FoodModel();
    $foodController = new FoodController();
    $restaurantModel = new RestaurantModel();
    
    $foods = $foodModel->getAllFoods();
    $restaurants = $restaurantModel->getAllRestaurants();
    
    $message = '';
    $messageType = '';
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['add_food'])) {
            $name = trim($_POST['name']);
            $description = trim($_POST['description']);
            $price = trim($_POST['price']);
            $restaurantId = $_POST['restaurant_id'];
            $imageUrl = trim($_POST['image_url']);
            
            // Validasi
            if (empty($name) || empty($description) || empty($price) || empty($restaurantId)) {
                $message = 'Semua field harus diisi';
                $messageType = 'danger';
            } else {
                $result = $foodController->addFood($name, $description, $price, $restaurantId, $imageUrl);
                $message = $result['message'];
                $messageType = $result['success'] ? 'success' : 'danger';
                
                // Refresh data jika berhasil
                if ($result['success']) {
                    $foods = $foodModel->getAllFoods();
                }
            }
        } elseif (isset($_POST['update_food'])) {
            $id = $_POST['food_id'];
            $name = trim($_POST['name']);
            $description = trim($_POST['description']);
            $price = trim($_POST['price']);
            $restaurantId = $_POST['restaurant_id'];
            $imageUrl = trim($_POST['image_url']);
            
            // Validasi
            if (empty($id) || empty($name) || empty($description) || empty($price) || empty($restaurantId)) {
                $message = 'Semua field harus diisi';
                $messageType = 'danger';
            } else {
                $result = $foodController->updateFood($id, $name, $description, $price, $restaurantId, $imageUrl);
                $message = $result['message'];
                $messageType = $result['success'] ? 'success' : 'danger';
                
                // Refresh data jika berhasil
                if ($result['success']) {
                    $foods = $foodModel->getAllFoods();
                }
            }
        } elseif (isset($_POST['delete_food'])) {
            $id = $_POST['food_id'];
            
            $result = $foodController->deleteFood($id);
            $message = $result['message'];
            $messageType = $result['success'] ? 'success' : 'danger';
            
            // Refresh data jika berhasil
            if ($result['success']) {
                $foods = $foodModel->getAllFoods();
            }
        }
    }
    ?>
    
    <?php require_once '../views/partials/navbar_admin.php'; ?>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <h1>Kelola Makanan</h1>
                
                <?php if ($message): ?>
                    <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
                        <?php echo htmlspecialchars($message); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <!-- Form Tambah Makanan -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Tambah Makanan Baru</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Nama Makanan</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="price" class="form-label">Harga</label>
                                    <input type="text" class="form-control" id="price" name="price" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Deskripsi</label>
                                <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="restaurant_id" class="form-label">Restoran</label>
                                    <select class="form-select" id="restaurant_id" name="restaurant_id" required>
                                        <?php foreach ($restaurants as $restaurant): ?>
                                            <option value="<?php echo $restaurant['id']; ?>"><?php echo htmlspecialchars($restaurant['name']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="image_url" class="form-label">URL Gambar</label>
                                    <input type="text" class="form-control" id="image_url" name="image_url">
                                </div>
                            </div>
                            <button type="submit" name="add_food" class="btn btn-primary">Tambah Makanan</button>
                        </form>
                    </div>
                </div>
                
                <!-- Daftar Makanan -->
                <div class="card">
                    <div class="card-header">
                        <h5>Daftar Makanan</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nama</th>
                                        <th>Restoran</th>
                                        <th>Harga</th>
                                        <th>Rating Rata-rata</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($foods as $food): ?>
                                        <tr>
                                            <td><?php echo $food['id']; ?></td>
                                            <td><?php echo htmlspecialchars($food['name']); ?></td>
                                            <td><?php echo htmlspecialchars($food['restaurant_name']); ?></td>
                                            <td>Rp <?php echo number_format($food['price'], 0, ',', '.'); ?></td>
                                            <td>
                                                <?php
                                                $avgRating = $food['avg_rating'] ? round($food['avg_rating'], 1) : 0;
                                                echo $avgRating . ' ★';
                                                ?>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $food['id']; ?>">Edit</button>
                                                <form method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus makanan ini?')">
                                                    <input type="hidden" name="food_id" value="<?php echo $food['id']; ?>">
                                                    <button type="submit" name="delete_food" class="btn btn-sm btn-outline-danger">Hapus</button>
                                                </form>
                                            </td>
                                        </tr>
                                        
                                        <!-- Modal Edit -->
                                        <div class="modal fade" id="editModal<?php echo $food['id']; ?>" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Edit Makanan</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <form method="POST">
                                                        <div class="modal-body">
                                                            <input type="hidden" name="food_id" value="<?php echo $food['id']; ?>">
                                                            <div class="mb-3">
                                                                <label for="edit_name_<?php echo $food['id']; ?>" class="form-label">Nama Makanan</label>
                                                                <input type="text" class="form-control" id="edit_name_<?php echo $food['id']; ?>" name="name" value="<?php echo htmlspecialchars($food['name']); ?>" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="edit_description_<?php echo $food['id']; ?>" class="form-label">Deskripsi</label>
                                                                <textarea class="form-control" id="edit_description_<?php echo $food['id']; ?>" name="description" rows="3" required><?php echo htmlspecialchars($food['description']); ?></textarea>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="edit_price_<?php echo $food['id']; ?>" class="form-label">Harga</label>
                                                                <input type="text" class="form-control" id="edit_price_<?php echo $food['id']; ?>" name="price" value="<?php echo $food['price']; ?>" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="edit_restaurant_id_<?php echo $food['id']; ?>" class="form-label">Restoran</label>
                                                                <select class="form-select" id="edit_restaurant_id_<?php echo $food['id']; ?>" name="restaurant_id" required>
                                                                    <?php foreach ($restaurants as $restaurant): ?>
                                                                        <option value="<?php echo $restaurant['id']; ?>" <?php echo $food['restaurant_id'] == $restaurant['id'] ? 'selected' : ''; ?>>
                                                                            <?php echo htmlspecialchars($restaurant['name']); ?>
                                                                        </option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="edit_image_url_<?php echo $food['id']; ?>" class="form-label">URL Gambar</label>
                                                                <input type="text" class="form-control" id="edit_image_url_<?php echo $food['id']; ?>" name="image_url" value="<?php echo htmlspecialchars($food['image_url']); ?>">
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                            <button type="submit" name="update_food" class="btn btn-primary">Simpan</button>
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

    <?php require_once '../views/partials/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>