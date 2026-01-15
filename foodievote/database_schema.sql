-- Skema database untuk FoodieVote

-- Membuat database
CREATE DATABASE IF NOT EXISTS foodievote_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Menggunakan database
USE foodievote_db;

-- Tabel users
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel restaurants
CREATE TABLE restaurants (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    address TEXT NOT NULL,
    phone VARCHAR(20) NOT NULL,
    operating_hours VARCHAR(100) NOT NULL,
    image_url VARCHAR(500),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel foods
CREATE TABLE foods (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    restaurant_id INT NOT NULL,
    image_url VARCHAR(500),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (restaurant_id) REFERENCES restaurants(id) ON DELETE CASCADE
);

-- Tabel ratings
CREATE TABLE ratings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    restaurant_id INT,
    food_id INT,
    rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),
    review TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (restaurant_id) REFERENCES restaurants(id) ON DELETE CASCADE,
    FOREIGN KEY (food_id) REFERENCES foods(id) ON DELETE CASCADE,
    -- Pastikan satu user hanya bisa memberi rating sekali per item
    UNIQUE KEY unique_user_restaurant (user_id, restaurant_id),
    UNIQUE KEY unique_user_food (user_id, food_id)
);

-- Menambahkan user admin default (password: admin123)
INSERT INTO users (username, email, password, role) VALUES
('admin', 'admin@foodievote.com', '$2y$10$VlFMsY.x9H.W44.UhZ8.OOv9.a6hPs.ESQJnhBHdw.yvVJw0Xwz8.', 'admin');

-- Menambahkan contoh data restoran
INSERT INTO restaurants (name, description, address, phone, operating_hours, image_url) VALUES
('Warung Padang Mak Syakban', 'Restoran padang dengan masakan tradisional yang lezat', 'Jl. Diponegoro No. 123, Jakarta', '021-12345678', '08:00-22:00', 'https://placehold.co/600x400/orange/white?text=Warung+Padang'),
('Kopi Kenangan', 'Tempat nongkrong anak muda dengan kopi pilihan', 'Jl. Sudirman No. 45, Bandung', '022-87654321', '06:00-23:00', 'https://placehold.co/600x400/brown/white?text=Kopi+Kenangan'),
('Sate Khas Senayan', 'Sate dengan bumbu kacang khas yang gurih', 'Jl. Asia Afrika No. 78, Jakarta', '021-23456789', '10:00-21:00', 'https://placehold.co/600x400/red/white?text=Sate+Senayan');

-- Menambahkan contoh data makanan
INSERT INTO foods (name, description, price, restaurant_id, image_url) VALUES
('Nasi Rendang', 'Nasi putih dengan rendang sapi khas Padang', 25000.00, 1, 'https://placehold.co/300x200/green/white?text=Nasi+Rendang'),
('Nasi Gurameh', 'Nasi dengan gurameh bakar pedas', 35000.00, 1, 'https://placehold.co/300x200/green/white?text=Nasi+Gurameh'),
('Es Kopi Susu', 'Kopi susu dengan gula aren', 18000.00, 2, 'https://placehold.co/300x200/brown/white?text=Kopi+Susu'),
('Cappuccino', 'Minuman kopi dengan busa susu', 22000.00, 2, 'https://placehold.co/300x200/brown/white?text=Cappuccino'),
('Sate Ayam', 'Sate ayam dengan bumbu kacang', 20000.00, 3, 'https://placehold.co/300x200/red/white?text=Sate+Ayam'),
('Sate Kambing', 'Sate kambing dengan bumbu kacang', 25000.00, 3, 'https://placehold.co/300x200/red/white?text=Sate+Kambing');