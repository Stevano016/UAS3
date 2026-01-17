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

// Ambil pesan dari session jika ada
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    $messageType = $_SESSION['messageType'];
    unset($_SESSION['message']);
    unset($_SESSION['messageType']);
}

// Fungsi untuk upload gambar
function uploadRestaurantImage($file) {
    // Path absolut ke folder uploads
    $uploadDir = __DIR__ . '/../../uploads/restaurants/';
    
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
    $fileName = uniqid('restaurant_', true) . '.' . $extension;
    $filePath = $uploadDir . $fileName;
    
    // Pindahkan file
    if (!move_uploaded_file($file['tmp_name'], $filePath)) {
        return ['success' => false, 'message' => 'Gagal menyimpan file'];
    }
    
    // Return path relatif untuk disimpan di database
    return ['success' => true, 'path' => $filePath, 'url' => 'uploads/restaurants/' . $fileName];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_restaurant'])) {
        $name = trim($_POST['name']);
        $description = trim($_POST['description']);
        $address = trim($_POST['address']);
        $phone = trim($_POST['phone']);
        $operatingHours = trim($_POST['operating_hours']);
        $imageUrl = '';
        
        // Cek apakah ada file yang diupload
        if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] !== UPLOAD_ERR_NO_FILE) {
            $uploadResult = uploadRestaurantImage($_FILES['image_file']);
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
        if (empty($name) || empty($description) || empty($address) || empty($phone) || empty($operatingHours)) {
            $message = 'Semua field harus diisi';
            $messageType = 'danger';
        } elseif (!isset($uploadResult) || $uploadResult['success']) {
            $result = $restaurantController->addRestaurant($name, $description, $address, $phone, $operatingHours, $imageUrl);
            
            // Refresh data jika berhasil
            if ($result['success']) {
                $_SESSION['message'] = $result['message'];
                $_SESSION['messageType'] = 'success';
                echo "<script>window.location.href = 'index.php?page=manage-restaurants';</script>";
                exit();
            } else {
                $message = $result['message'];
                $messageType = 'danger';
            }
        }
    } elseif (isset($_POST['update_restaurant'])) {
        $id = $_POST['restaurant_id'];
        $name = trim($_POST['name']);
        $description = trim($_POST['description']);
        $address = trim($_POST['address']);
        $phone = trim($_POST['phone']);
        $operatingHours = trim($_POST['operating_hours']);
        $imageUrl = trim($_POST['current_image_url']); // Gunakan gambar lama sebagai default
        
        // Cek apakah ada file yang diupload
        if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] !== UPLOAD_ERR_NO_FILE) {
            $uploadResult = uploadRestaurantImage($_FILES['image_file']);
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
        if (empty($id) || empty($name) || empty($description) || empty($address) || empty($phone) || empty($operatingHours)) {
            $message = 'Semua field harus diisi';
            $messageType = 'danger';
        } elseif (!isset($uploadResult) || $uploadResult['success']) {
            $result = $restaurantController->updateRestaurant($id, $name, $description, $address, $phone, $operatingHours, $imageUrl);
            
            // Refresh data jika berhasil
            if ($result['success']) {
                $_SESSION['message'] = $result['message'];
                $_SESSION['messageType'] = 'success';
                echo "<script>window.location.href = 'index.php?page=manage-restaurants';</script>";
                exit();
            } else {
                $message = $result['message'];
                $messageType = 'danger';
            }
        }
    } elseif (isset($_POST['delete_restaurant'])) {
        $id = $_POST['restaurant_id'];
        
        // Ambil data restoran untuk hapus gambar
        $restaurant = $restaurantModel->getRestaurantById($id);
        
        $result = $restaurantController->deleteRestaurant($id);
        
        // Hapus file gambar jika berhasil delete dan bukan URL eksternal
        if ($result['success'] && !empty($restaurant['image_url']) && strpos($restaurant['image_url'], 'http') === false) {
            $imagePath = __DIR__ . '/../../' . $restaurant['image_url'];
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
        
        // Refresh data jika berhasil
        if ($result['success']) {
            $_SESSION['message'] = $result['message'];
            $_SESSION['messageType'] = 'success';
            echo "<scrip>window.location.href = 'index.php?page=manage-restaurants';</script>";
            exit();
        } else {
            $message = $result['message'];
            $messageType = 'danger';
        }
    }
}

// Refresh data setelah operasi
$restaurants = $restaurantModel->getAllRestaurants();
?>

<div class="dashboard-header">
    <h1>Kelola Restoran</h1>
</div>

<?php if ($message): ?>
    <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
        <?php echo htmlspecialchars($message); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- Form Tambah Restoran -->
<div class="card mb-4 fade-in-section">
    <div class="card-header">
        <h5>Tambah Restoran Baru</h5>
    </div>
    <div class="card-body">
        <form method="POST" enctype="multipart/form-data">
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
                    <input type="text" class="form-control" id="operating_hours" name="operating_hours" placeholder="Contoh: Senin-Jumat 08:00-22:00" required>
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
            <button type="submit" name="add_restaurant" class="btn btn-primary pulse-animation">Tambah Restoran</button>
        </form>
    </div>
</div>

<!-- Daftar Restoran -->
<div class="card fade-in-section">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5>Daftar Restoran</h5>
        <span class="text-muted">Total: <?php echo count($restaurants); ?> restoran</span>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Gambar</th>
                        <th>Nama</th>
                        <th>Alamat</th>
                        <th>Telepon</th>
                        <th>Rating Rata-rata</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($restaurants as $restaurant): ?>
                        <tr>
                            <td><?php echo $restaurant['id']; ?></td>
                            <td>
                                <?php if (!empty($restaurant['image_url'])): ?>
                                    <?php
                                    // Check if image_url is a relative path and prepend BASE_URL if needed
                                    $imageSrc = $restaurant['image_url'];
                                    if (strpos($restaurant['image_url'], 'http') !== 0) {
                                        // If it doesn't start with http, treat as relative path
                                        if (strpos($restaurant['image_url'], '/') === 0) {
                                            // If it starts with '/', it's relative to root
                                            $imageSrc = BASE_URL . $restaurant['image_url'];
                                        } else {
                                            // If it doesn't start with '/', prepend BASE_URL
                                            $imageSrc = BASE_URL . '/' . $restaurant['image_url'];
                                        }
                                    }
                                    ?>
                                    <img src="<?php echo htmlspecialchars($imageSrc); ?>"
                                         alt="<?php echo htmlspecialchars($restaurant['name']); ?>"
                                         style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;"
                                         onerror="this.parentElement.innerHTML='<div style=\'width: 50px; height: 50px; background: #f0f0f0; border-radius: 8px; display: flex; align-items: center; justify-content: center;\'>🏪</div>'">
                                <?php else: ?>
                                    <div style="width: 50px; height: 50px; background: #f0f0f0; border-radius: 8px; display: flex; align-items: center; justify-content: center;">🏪</div>
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($restaurant['name']); ?></td>
                            <td><?php echo htmlspecialchars($restaurant['address']); ?></td>
                            <td><?php echo htmlspecialchars($restaurant['phone']); ?></td>
                            <td>
                                <?php
                                $avgRating = $restaurant['avg_rating'] ? round($restaurant['avg_rating'], 1) : 0;
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
                                <button class="btn btn-sm btn-outline-primary me-2" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $restaurant['id']; ?>">Edit</button>
                                <form method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus restoran ini?')">
                                    <input type="hidden" name="restaurant_id" value="<?php echo $restaurant['id']; ?>">
                                    <button type="submit" name="delete_restaurant" class="btn btn-sm btn-outline-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <?php if (empty($restaurants)): ?>
            <div class="text-center py-5">
                <div class="mb-3">🏪</div>
                <h5 class="text-muted">Belum ada restoran</h5>
                <p class="text-muted">Tambahkan restoran pertama Anda dengan formulir di atas</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal Edit -->
<?php foreach ($restaurants as $restaurant): ?>
    <div class="modal fade" id="editModal<?php echo $restaurant['id']; ?>" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Restoran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" name="restaurant_id" value="<?php echo $restaurant['id']; ?>">
                        <input type="hidden" name="current_image_url" value="<?php echo htmlspecialchars($restaurant['image_url']); ?>">
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
                        
                        <?php if (!empty($restaurant['image_url'])): ?>
                        <div class="mb-3">
                            <label class="form-label">Gambar Saat Ini</label>
                            <div>
                                <?php
                                // Check if image_url is a relative path and prepend BASE_URL if needed
                                $imageSrc = $restaurant['image_url'];
                                if (strpos($restaurant['image_url'], 'http') !== 0) {
                                    // If it doesn't start with http, treat as relative path
                                    if (strpos($restaurant['image_url'], '/') === 0) {
                                        // If it starts with '/', it's relative to root
                                        $imageSrc = BASE_URL . $restaurant['image_url'];
                                    } else {
                                        // If it doesn't start with '/', prepend BASE_URL
                                        $imageSrc = BASE_URL . '/' . $restaurant['image_url'];
                                    }
                                }
                                ?>
                                <img src="<?php echo htmlspecialchars($imageSrc); ?>" alt="Current" style="max-width: 200px; max-height: 200px; border-radius: 8px;">
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <div class="mb-3">
                            <label for="edit_image_file_<?php echo $restaurant['id']; ?>" class="form-label">Upload Gambar Baru</label>
                            <input type="file" class="form-control" id="edit_image_file_<?php echo $restaurant['id']; ?>" name="image_file" accept="image/*" onchange="previewImage(this, 'preview_edit_<?php echo $restaurant['id']; ?>')">
                            <small class="text-muted">Maksimal 5MB (JPG, PNG, GIF, WEBP)</small>
                        </div>
                        <div class="mb-3">
                            <label for="edit_image_url_<?php echo $restaurant['id']; ?>" class="form-label">Atau Masukkan URL Gambar Baru</label>
                            <input type="text" class="form-control" id="edit_image_url_<?php echo $restaurant['id']; ?>" name="image_url" placeholder="https://example.com/image.jpg">
                        </div>
                        <div class="mb-3" id="preview_edit_<?php echo $restaurant['id']; ?>" style="display: none;">
                            <label class="form-label">Preview Gambar Baru</label>
                            <div>
                                <img id="preview_edit_<?php echo $restaurant['id']; ?>_img" src="" alt="Preview" style="max-width: 200px; max-height: 200px; border-radius: 8px;">
                            </div>
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