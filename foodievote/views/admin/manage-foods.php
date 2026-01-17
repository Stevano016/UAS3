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

// Ambil pesan dari session jika ada
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    $messageType = $_SESSION['messageType'];
    unset($_SESSION['message']);
    unset($_SESSION['messageType']);
}

// Fungsi untuk upload gambar
function uploadImage($file) {
    // Path absolut ke folder uploads
    $uploadDir = __DIR__ . '/../../uploads/foods/';
    
    // Buat folder jika belum ada
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    
    $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
    $maxSize = 5 * 1024 * 1024; // 5MB
    
    // Validasi file
    if (!isset($file['error']) || is_array($file['error'])) {
        return ['success' => false, 'message' => 'Error upload file'];
    }
    
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'message' => 'Error saat upload file'];
    }
    
    if ($file['size'] > $maxSize) {
        return ['success' => false, 'message' => 'Ukuran file terlalu besar (maksimal 5MB)'];
    }
    
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mimeType = $finfo->file($file['tmp_name']);
    
    if (!in_array($mimeType, $allowedTypes)) {
        return ['success' => false, 'message' => 'Format file tidak didukung. Gunakan JPG, PNG, GIF, atau WEBP'];
    }
    
    // Generate nama file unik
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $fileName = uniqid('food_', true) . '.' . $extension;
    $filePath = $uploadDir . $fileName;
    
    // Pindahkan file
    if (!move_uploaded_file($file['tmp_name'], $filePath)) {
        return ['success' => false, 'message' => 'Gagal menyimpan file'];
    }
    
    // Return path relatif untuk disimpan di database
    return ['success' => true, 'path' => $filePath, 'url' => 'uploads/foods/' . $fileName];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_food'])) {
        $name = trim($_POST['name']);
        $description = trim($_POST['description']);
        $price = trim($_POST['price']);
        $restaurantId = $_POST['restaurant_id'];
        $imageUrl = '';
        
        // Cek apakah ada file yang diupload
        if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] !== UPLOAD_ERR_NO_FILE) {
            $uploadResult = uploadImage($_FILES['image_file']);
            if ($uploadResult['success']) {
                $imageUrl = $uploadResult['url'];
            } else {
                $message = $uploadResult['message'];
                $messageType = 'danger';
            }
        } elseif (!empty($_POST['image_url'])) {
            // Gunakan URL jika diisi
            $imageUrl = trim($_POST['image_url']);
        }
        
        // Validasi
        if (empty($name) || empty($description) || empty($price) || empty($restaurantId)) {
            $message = 'Semua field harus diisi';
            $messageType = 'danger';
        } elseif (!isset($uploadResult) || $uploadResult['success']) {
            $result = $foodController->addFood($name, $description, $price, $restaurantId, $imageUrl);
            
            // Refresh data jika berhasil - gunakan session dan JS redirect
            if ($result['success']) {
                $_SESSION['message'] = $result['message'];
                $_SESSION['messageType'] = 'success';
                echo "<script>window.location.href = 'index.php?page=manage-foods';</script>";
                exit();
            } else {
                $message = $result['message'];
                $messageType = 'danger';
            }
        }
    } elseif (isset($_POST['update_food'])) {
        $id = $_POST['food_id'];
        $name = trim($_POST['name']);
        $description = trim($_POST['description']);
        $price = trim($_POST['price']);
        $restaurantId = $_POST['restaurant_id'];
        $imageUrl = trim($_POST['current_image_url']); // Gunakan gambar lama sebagai default
        
        // Cek apakah ada file yang diupload
        if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] !== UPLOAD_ERR_NO_FILE) {
            $uploadResult = uploadImage($_FILES['image_file']);
            if ($uploadResult['success']) {
                // Hapus gambar lama jika ada dan bukan URL eksternal
                if (!empty($imageUrl) && strpos($imageUrl, 'http') === false) {
                    $oldImagePath = __DIR__ . '/../../' . $imageUrl;
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }
                $imageUrl = $uploadResult['url'];
            } else {
                $message = $uploadResult['message'];
                $messageType = 'danger';
            }
        } elseif (!empty($_POST['image_url'])) {
            // Gunakan URL baru jika diisi
            $imageUrl = trim($_POST['image_url']);
        }
        
        // Validasi
        if (empty($id) || empty($name) || empty($description) || empty($price) || empty($restaurantId)) {
            $message = 'Semua field harus diisi';
            $messageType = 'danger';
        } elseif (!isset($uploadResult) || $uploadResult['success']) {
            $result = $foodController->updateFood($id, $name, $description, $price, $restaurantId, $imageUrl);
            
            // Refresh data jika berhasil - gunakan session dan JS redirect
            if ($result['success']) {
                $_SESSION['message'] = $result['message'];
                $_SESSION['messageType'] = 'success';
                echo "<script>window.location.href = 'index.php?page=manage-foods';</script>";
                exit();
            } else {
                $message = $result['message'];
                $messageType = 'danger';
            }
        }
    } elseif (isset($_POST['delete_food'])) {
        $id = $_POST['food_id'];
        
        // Ambil data makanan untuk hapus gambar
        $food = $foodModel->getFoodById($id);
        
        $result = $foodController->deleteFood($id);
        
        // Hapus file gambar jika berhasil delete dan bukan URL eksternal
        if ($result['success'] && !empty($food['image_url']) && strpos($food['image_url'], 'http') === false) {
            $imagePath = __DIR__ . '/../../' . $food['image_url'];
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
        
        // Refresh data jika berhasil - gunakan session dan JS redirect
        if ($result['success']) {
            $_SESSION['message'] = $result['message'];
            $_SESSION['messageType'] = 'success';
            echo "<script>window.location.href = 'index.php?page=manage-foods';</script>";
            exit();
        } else {
            $message = $result['message'];
            $messageType = 'danger';
        }
    }
}

// Refresh data setelah operasi
$foods = $foodModel->getAllFoods();
?>

<div class="dashboard-header">
    <h1>Kelola Makanan</h1>
</div>

<?php if ($message): ?>
    <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
        <?php echo htmlspecialchars($message); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- Form Tambah Makanan -->
<div class="card mb-4 fade-in-section">
    <div class="card-header">
        <h5>Tambah Makanan Baru</h5>
    </div>
    <div class="card-body">
        <form method="POST" enctype="multipart/form-data">
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
                        <option value="">Pilih Restoran</option>
                        <?php foreach ($restaurants as $restaurant): ?>
                            <option value="<?= $restaurant['id']; ?>">
                                <?= htmlspecialchars($restaurant['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="image_file" class="form-label">Upload Gambar</label>
                    <input type="file" class="form-control" id="image_file" name="image_file" accept="image/*" onchange="previewImage(this, 'preview_add')">
                    <small class="text-muted">Maksimal 5MB (JPG, PNG, GIF, WEBP)</small>
                </div>
            </div>
            <div class="mb-3">
                <label for="image_url" class="form-label">Atau Masukkan URL Gambar</label>
                <input type="text" class="form-control" id="image_url" name="image_url" placeholder="https://example.com/image.jpg">
            </div>
            <div class="mb-3" id="preview_add" style="display: none;">
                <label class="form-label">Preview Gambar</label>
                <div>
                    <img id="preview_add_img" src="" alt="Preview" style="max-width: 300px; max-height: 300px; border-radius: 8px;">
                </div>
            </div>
            <button type="submit" name="add_food" class="btn btn-primary pulse-animation">Tambah Makanan</button>
        </form>
    </div>
</div>

<!-- Daftar Makanan -->
<div class="card fade-in-section">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5>Daftar Makanan</h5>
        <span class="text-muted">Total: <?php echo count($foods); ?> item</span>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Gambar</th>
                        <th>Nama</th>
                        <th>Restoran</th>
                        <th>Harga</th>
                        <th>Rating Rata-rata</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($foods as $food): ?>
                        <tr>
                            <td><?php echo $food['id']; ?></td>
                            <td>
                                <?php if (!empty($food['image_url'])): ?>
                                    <?php 
                                    // Cek apakah URL eksternal atau lokal
                                    $imageSrc = (strpos($food['image_url'], 'http') === 0) 
                                        ? htmlspecialchars($food['image_url']) 
                                        : '../' . htmlspecialchars($food['image_url']); 
                                    ?>
                                    <img src="<?php echo $imageSrc; ?>" 
                                         alt="<?php echo htmlspecialchars($food['name']); ?>" 
                                         style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;"
                                         onerror="this.parentElement.innerHTML='<div style=\'width: 50px; height: 50px; background: #f0f0f0; border-radius: 8px; display: flex; align-items: center; justify-content: center;\'>🍽️</div>'">
                                <?php else: ?>
                                    <div style="width: 50px; height: 50px; background: #f0f0f0; border-radius: 8px; display: flex; align-items: center; justify-content: center;">🍽️</div>
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($food['name']); ?></td>
                            <td><?php echo htmlspecialchars($food['restaurant_name']); ?></td>
                            <td>Rp <?php echo number_format($food['price'], 0, ',', '.'); ?></td>
                            <td>
                                <?php
                                $avgRating = $food['avg_rating'] ? round($food['avg_rating'], 1) : 0;
                                echo '<span class="rating-stars">';
                                for ($i = 1; $i <= 5; $i++) {
                                    if ($i <= $avgRating) {
                                        echo '★';
                                    } else {
                                        echo '☆';
                                    }
                                }
                                echo '</span> (' . $avgRating . ')';
                                ?>
                            </td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-primary me-2" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $food['id']; ?>">Edit</button>
                                <form method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus makanan ini?')">
                                    <input type="hidden" name="food_id" value="<?php echo $food['id']; ?>">
                                    <button type="submit" name="delete_food" class="btn btn-sm btn-outline-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <?php if (empty($foods)): ?>
            <div class="text-center py-5">
                <div class="mb-3">🍽️</div>
                <h5 class="text-muted">Belum ada makanan</h5>
                <p class="text-muted">Tambahkan makanan pertama Anda dengan formulir di atas</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal Edit -->
<?php foreach ($foods as $food): ?>
    <div class="modal fade" id="editModal<?php echo $food['id']; ?>" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Makanan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" name="food_id" value="<?php echo $food['id']; ?>">
                        <input type="hidden" name="current_image_url" value="<?php echo htmlspecialchars($food['image_url']); ?>">
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
                        
                        <?php if (!empty($food['image_url'])): ?>
                        <div class="mb-3">
                            <label class="form-label">Gambar Saat Ini</label>
                            <div>
                                <?php 
                                $imageSrc = (strpos($food['image_url'], 'http') === 0) 
                                    ? htmlspecialchars($food['image_url']) 
                                    : '../' . htmlspecialchars($food['image_url']); 
                                ?>
                                <img src="<?php echo $imageSrc; ?>" alt="Current" style="max-width: 200px; max-height: 200px; border-radius: 8px;">
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <div class="mb-3">
                            <label for="edit_image_file_<?php echo $food['id']; ?>" class="form-label">Upload Gambar Baru</label>
                            <input type="file" class="form-control" id="edit_image_file_<?php echo $food['id']; ?>" name="image_file" accept="image/*" onchange="previewImage(this, 'preview_edit_<?php echo $food['id']; ?>')">
                            <small class="text-muted">Maksimal 5MB (JPG, PNG, GIF, WEBP)</small>
                        </div>
                        <div class="mb-3">
                            <label for="edit_image_url_<?php echo $food['id']; ?>" class="form-label">Atau Masukkan URL Gambar Baru</label>
                            <input type="text" class="form-control" id="edit_image_url_<?php echo $food['id']; ?>" name="image_url" placeholder="https://example.com/image.jpg">
                        </div>
                        <div class="mb-3" id="preview_edit_<?php echo $food['id']; ?>" style="display: none;">
                            <label class="form-label">Preview Gambar Baru</label>
                            <div>
                                <img id="preview_edit_<?php echo $food['id']; ?>_img" src="" alt="Preview" style="max-width: 200px; max-height: 200px; border-radius: 8px;">
                            </div>
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


