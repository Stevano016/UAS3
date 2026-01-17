<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Restoran - FoodieVote Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
</head>
<body>
    <?php
    require_once '../core/middleware.php';
    require_once '../modules/restaurants/restaurant.model.php';
    require_once '../modules/restaurants/restaurant.controller.php';
    
    requireLogin();
    requireAdmin();
    
    $restaurantModel = new RestaurantModel();
    $restaurantController = new RestaurantController();
    
    $restaurants = $restaurantModel->getAllRestaurants();
    
    $message = '';
    $messageType = '';
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['add_restaurant'])) {
            $name = trim($_POST['name']);
            $description = trim($_POST['description']);
            $address = trim($_POST['address']);
            $phone = trim($_POST['phone']);
            $operatingHours = trim($_POST['operating_hours']);
            $imageUrl = trim($_POST['image_url']);
            
            // Validasi
            if (empty($name) || empty($description) || empty($address) || empty($phone) || empty($operatingHours)) {
                $message = 'Semua field harus diisi';
                $messageType = 'danger';
            } else {
                $result = $restaurantController->addRestaurant($name, $description, $address, $phone, $operatingHours, $imageUrl);
                $message = $result['message'];
                $messageType = $result['success'] ? 'success' : 'danger';
                
                // Refresh data jika berhasil
                if ($result['success']) {
                    $restaurants = $restaurantModel->getAllRestaurants();
                }
            }
        } elseif (isset($_POST['update_restaurant'])) {
            $id = $_POST['restaurant_id'];
            $name = trim($_POST['name']);
            $description = trim($_POST['description']);
            $address = trim($_POST['address']);
            $phone = trim($_POST['phone']);
            $operatingHours = trim($_POST['operating_hours']);
            $imageUrl = trim($_POST['image_url']);
            
            // Validasi
            if (empty($id) || empty($name) || empty($description) || empty($address) || empty($phone) || empty($operatingHours)) {
                $message = 'Semua field harus diisi';
                $messageType = 'danger';
            } else {
                $result = $restaurantController->updateRestaurant($id, $name, $description, $address, $phone, $operatingHours, $imageUrl);
                $message = $result['message'];
                $messageType = $result['success'] ? 'success' : 'danger';
                
                // Refresh data jika berhasil
                if ($result['success']) {
                    $restaurants = $restaurantModel->getAllRestaurants();
                }
            }
        } elseif (isset($_POST['delete_restaurant'])) {
            $id = $_POST['restaurant_id'];
            
            $result = $restaurantController->deleteRestaurant($id);
            $message = $result['message'];
            $messageType = $result['success'] ? 'success' : 'danger';
            
            // Refresh data jika berhasil
            if ($result['success']) {
                $restaurants = $restaurantModel->getAllRestaurants();
            }
        }
    }
    ?>
    
    <?php require_once '../views/partials/navbar_admin.php'; ?>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <h1>Kelola Restoran</h1>
                
                <?php if ($message): ?>
                    <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
                        <?php echo htmlspecialchars($message); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <!-- Form Tambah Restoran -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Tambah Restoran Baru</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Nama Restoran</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">Nomor Telepon</label>
                                    <input type="text" class="form-control" id="phone" name="phone" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Deskripsi</label>
                                <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Alamat</label>
                                <input type="text" class="form-control" id="address" name="address" required>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="operating_hours" class="form-label">Jam Operasional</label>
                                    <input type="text" class="form-control" id="operating_hours" name="operating_hours" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="image_url" class="form-label">URL Gambar</label>
                                    <input type="text" class="form-control" id="image_url" name="image_url">
                                </div>
                            </div>
                            <button type="submit" name="add_restaurant" class="btn btn-primary">Tambah Restoran</button>
                        </form>
                    </div>
                </div>
                
                <!-- Daftar Restoran -->
                <div class="card">
                    <div class="card-header">
                        <h5>Daftar Restoran</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nama</th>
                                        <th>Alamat</th>
                                        <th>Telepon</th>
                                        <th>Rating Rata-rata</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($restaurants as $restaurant): ?>
                                        <tr>
                                            <td><?php echo $restaurant['id']; ?></td>
                                            <td><?php echo htmlspecialchars($restaurant['name']); ?></td>
                                            <td><?php echo htmlspecialchars($restaurant['address']); ?></td>
                                            <td><?php echo htmlspecialchars($restaurant['phone']); ?></td>
                                            <td>
                                                <?php
                                                $avgRating = $restaurant['avg_rating'] ? round($restaurant['avg_rating'], 1) : 0;
                                                echo $avgRating . ' ★';
                                                ?>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $restaurant['id']; ?>">Edit</button>
                                                <form method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus restoran ini?')">
                                                    <input type="hidden" name="restaurant_id" value="<?php echo $restaurant['id']; ?>">
                                                    <button type="submit" name="delete_restaurant" class="btn btn-sm btn-outline-danger">Hapus</button>
                                                </form>
                                            </td>
                                        </tr>
                                        
                                        <!-- Modal Edit -->
                                        <div class="modal fade" id="editModal<?php echo $restaurant['id']; ?>" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Edit Restoran</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <form method="POST">
                                                        <div class="modal-body">
                                                            <input type="hidden" name="restaurant_id" value="<?php echo $restaurant['id']; ?>">
                                                            <div class="mb-3">
                                                                <label for="edit_name_<?php echo $restaurant['id']; ?>" class="form-label">Nama Restoran</label>
                                                                <input type="text" class="form-control" id="edit_name_<?php echo $restaurant['id']; ?>" name="name" value="<?php echo htmlspecialchars($restaurant['name']); ?>" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="edit_description_<?php echo $restaurant['id']; ?>" class="form-label">Deskripsi</label>
                                                                <textarea class="form-control" id="edit_description_<?php echo $restaurant['id']; ?>" name="description" rows="3" required><?php echo htmlspecialchars($restaurant['description']); ?></textarea>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="edit_address_<?php echo $restaurant['id']; ?>" class="form-label">Alamat</label>
                                                                <input type="text" class="form-control" id="edit_address_<?php echo $restaurant['id']; ?>" name="address" value="<?php echo htmlspecialchars($restaurant['address']); ?>" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="edit_phone_<?php echo $restaurant['id']; ?>" class="form-label">Nomor Telepon</label>
                                                                <input type="text" class="form-control" id="edit_phone_<?php echo $restaurant['id']; ?>" name="phone" value="<?php echo htmlspecialchars($restaurant['phone']); ?>" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="edit_operating_hours_<?php echo $restaurant['id']; ?>" class="form-label">Jam Operasional</label>
                                                                <input type="text" class="form-control" id="edit_operating_hours_<?php echo $restaurant['id']; ?>" name="operating_hours" value="<?php echo htmlspecialchars($restaurant['operating_hours']); ?>" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="edit_image_url_<?php echo $restaurant['id']; ?>" class="form-label">URL Gambar</label>
                                                                <input type="text" class="form-control" id="edit_image_url_<?php echo $restaurant['id']; ?>" name="image_url" value="<?php echo htmlspecialchars($restaurant['image_url']); ?>">
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                            <button type="submit" name="update_restaurant" class="btn btn-primary">Simpan</button>
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