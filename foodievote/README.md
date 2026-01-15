# Panduan Instalasi dan Penggunaan FoodieVote

## Prasyarat
- Web server (Apache/Nginx)
- PHP 7.4 atau lebih tinggi
- MySQL 5.7 atau lebih tinggi
- Laragon/XAMPP/WAMP (opsional, untuk lokal development)

## Langkah-langkah Instalasi

### 1. Konfigurasi Database
1. Buka aplikasi database Anda (misalnya phpMyAdmin di Laragon)
2. Buat database baru dengan nama `foodievote_db`
3. Impor file `database_schema.sql` yang ada di folder root aplikasi
   - Buka phpMyAdmin
   - Pilih database `foodievote_db`
   - Klik tab "Import"
   - Pilih file `database_schema.sql`
   - Klik "Go"

### 2. Konfigurasi Aplikasi
1. Sesuaikan konfigurasi database di file `config/database.php`
   ```php
   define('DB_HOST', 'localhost');      // Host database
   define('DB_USER', 'root');          // Username database
   define('DB_PASS', '');              // Password database (kosongkan jika pakai Laragon default)
   define('DB_NAME', 'foodievote_db'); // Nama database
   ```

### 3. Menjalankan Aplikasi
1. Tempatkan folder `foodievote` di direktori web server Anda
   - Jika menggunakan Laragon: `C:\laragon\www\Sasino\foodievote`
   - Jika menggunakan XAMPP: `C:\xampp\htdocs\foodievote`
   
2. Akses aplikasi melalui browser
   - Buka browser
   - Ketik URL: `http://localhost/Sasino/foodievote/public/` (untuk Laragon)
   - Atau: `http://localhost/foodievote/public/` (untuk XAMPP)

### 4. Login Awal
- Untuk pertama kali, Anda bisa login dengan akun admin default:
  - Username: `admin`
  - Password: `admin123`

## Struktur Aplikasi
```
foodievote/
├── config/           # File konfigurasi
│   ├── config.php
│   └── database.php
├── core/             # Komponen inti aplikasi
│   ├── session.php
│   ├── auth.php
│   └── middleware.php
├── modules/          # Modul aplikasi
│   ├── users/
│   ├── restaurants/
│   ├── foods/
│   └── ratings/
├── views/            # Tampilan aplikasi
│   ├── admin/
│   ├── user/
│   └── guest/
├── public/           # File publik
│   ├── index.php
│   ├── login.php
│   └── logout.php
├── assets/           # File statis
│   ├── css/
│   ├── js/
│   └── images/
└── database_schema.sql  # Skema database
```

## Fitur Aplikasi
- Guest: Dapat melihat daftar restoran dan makanan beserta rating
- User: Dapat login, memberi rating, mengedit profil
- Admin: Dapat mengelola user, restoran, makanan, dan rating

## Troubleshooting
- Jika muncul error "Connection failed", pastikan konfigurasi database benar
- Jika halaman tidak bisa diakses, pastikan mod_rewrite aktif di server Apache
- Jika tampilan rusak, pastikan file CSS dan JS bisa diakses