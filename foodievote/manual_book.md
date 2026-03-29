# Manual Book Sistem FoodieVote

## Daftar Isi
1. [Pengenalan Sistem](#pengenalan-sistem)
2. [Instalasi Sistem](#instalasi-sistem)
3. [Struktur Sistem](#struktur-sistem)
4. [Panduan Penggunaan](#panduan-penggunaan)
5. [Fitur dan Fungsionalitas](#fitur-dan-fungsionalitas)
6. [Administrator](#administrator)
7. [User Biasa](#user-biasa)
8. [Guest (Pengunjung)](#guest-pengunjung)
9. [Troubleshooting](#troubleshooting)

## Pengenalan Sistem

FoodieVote adalah sistem informasi berbasis web yang dirancang untuk membantu pengguna menemukan, mengevaluasi, dan merekomendasikan restoran dan makanan terbaik di sekitar mereka. Sistem ini menyediakan platform bagi para pecinta kuliner untuk memberikan penilaian dan ulasan jujur tentang pengalaman mereka terhadap restoran dan makanan.

### Tujuan Sistem
- Memberikan rekomendasi kuliner berdasarkan penilaian dan ulasan pengguna
- Menyediakan platform bagi komunitas foodie untuk berbagi pengalaman kuliner
- Membantu pemilik restoran dalam memahami feedback pelanggan
- Mempermudah pencarian restoran dan makanan terbaik di suatu wilayah

### Jenis Pengguna
1. **Administrator**: Mengelola sistem secara keseluruhan
2. **User Biasa**: Memberikan penilaian dan ulasan
3. **Guest**: Melihat informasi tanpa login

## Instalasi Sistem

### Prasyarat
- Web server (Apache/Nginx)
- PHP 7.4 atau lebih tinggi
- MySQL 5.7 atau lebih tinggi
- Laragon/XAMPP/WAMP (opsional, untuk lokal development)

### Langkah-langkah Instalasi

#### 1. Konfigurasi Database
1. Buka aplikasi database Anda (misalnya phpMyAdmin di Laragon)
2. Buat database baru dengan nama `foodievote_db`
3. Impor file `database_schema.sql` yang ada di folder root aplikasi
   - Buka phpMyAdmin
   - Pilih database `foodievote_db`
   - Klik tab "Import"
   - Pilih file `database_schema.sql`
   - Klik "Go"

#### 2. Konfigurasi Aplikasi
1. Sesuaikan konfigurasi database di file `config/database.php`
   ```php
   define('DB_HOST', 'localhost');      // Host database
   define('DB_USER', 'root');          // Username database
   define('DB_PASS', '');              // Password database (kosongkan jika pakai Laragon default)
   define('DB_NAME', 'foodievote_db'); // Nama database
   ```

#### 3. Menjalankan Aplikasi
1. Tempatkan folder `foodievote` di direktori web server Anda
   - Jika menggunakan Laragon: `C:\laragon\www\Sasino\foodievote`
   - Jika menggunakan XAMPP: `C:\xampp\htdocs\foodievote`

2. Akses aplikasi melalui browser
   - Buka browser
   - Ketik URL: `http://localhost/Sasino/foodievote/public/` (untuk Laragon)
   - Atau: `http://localhost/foodievote/public/` (untuk XAMPP)

#### 4. Login Awal
- Untuk pertama kali, Anda bisa login dengan akun admin default:
  - Username: `admin`
  - Password: `admin123`

## Struktur Sistem

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
│   │   ├── user.model.php
│   │   └── user.controller.php
│   ├── restaurants/
│   │   ├── restaurant.model.php
│   │   └── restaurant.controller.php
│   ├── foods/
│   │   ├── food.model.php
│   │   └── food.controller.php
│   └── ratings/
│       ├── rating.model.php
│       └── rating.controller.php
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

## Panduan Penggunaan

### Guest (Pengunjung)
Pengguna yang belum login dapat:
- Melihat halaman depan (home)
- Melihat daftar restoran
- Melihat daftar makanan
- Melihat detail restoran
- Melihat detail makanan
- Melihat kontak sistem

### User Biasa
Setelah login, user biasa dapat:
- Melihat dashboard pribadi
- Melihat dan mengedit profil
- Memberikan rating dan ulasan untuk restoran dan makanan
- Melihat daftar rating yang telah diberikan
- Menjelajahi restoran dan makanan

### Administrator
Administrator memiliki hak akses penuh:
- Melihat dashboard admin
- Mengelola user (tambah, edit, hapus)
- Mengelola restoran (tambah, edit, hapus)
- Mengelola makanan (tambah, edit, hapus)
- Mengelola rating (melihat dan memoderasi)

## Fitur dan Fungsionalitas

### 1. Manajemen User
- Registrasi akun baru
- Login/logout
- Pengelolaan profil
- Reset password

### 2. Manajemen Restoran
- Menambah restoran baru
- Mengedit informasi restoran
- Menghapus restoran
- Melihat detail restoran
- Menampilkan rating rata-rata

### 3. Manajemen Makanan
- Menambah makanan baru
- Mengedit informasi makanan
- Menghapus makanan
- Melihat detail makanan
- Menampilkan rating rata-rata

### 4. Sistem Rating
- Memberikan rating (skala 1-5 bintang)
- Memberikan ulasan teks
- Melihat statistik rating
- Menampilkan rating terbaru

### 5. Pencarian dan Filter
- Pencarian restoran dan makanan
- Filter berdasarkan rating
- Sorting hasil pencarian

## Administrator

### Login sebagai Administrator
1. Buka halaman login: `http://localhost/Sasino/foodievote/public/login.php`
2. Masukkan username: `adm`
3. Masukkan password: `123456`
4. Klik tombol "Login"

### Dashboard Admin
Setelah login, administrator akan diarahkan ke dashboard admin yang menampilkan:
- Statistik jumlah user, restoran, makanan, dan rating
- Aksi cepat untuk mengelola berbagai komponen
- Aktivitas rating terbaru

### Mengelola User
1. Klik menu "Kelola User" dari dashboard
2. Di halaman ini, administrator dapat:
   - Melihat daftar semua user
   - Menambah user baru
   - Mengedit informasi user
   - Menghapus user

### Mengelola Restoran
1. Klik menu "Kelola Restoran" dari dashboard
2. Di halaman ini, administrator dapat:
   - Melihat daftar semua restoran
   - Menambah restoran baru
   - Mengedit informasi restoran
   - Menghapus restoran

### Mengelola Makanan
1. Klik menu "Kelola Makanan" dari dashboard
2. Di halaman ini, administrator dapat:
   - Melihat daftar semua makanan
   - Menambah makanan baru
   - Mengedit informasi makanan
   - Menghapus makanan

### Mengelola Rating
1. Klik menu "Kelola Rating" dari dashboard
2. Di halaman ini, administrator dapat:
   - Melihat semua rating yang diberikan
   - Memoderasi rating jika diperlukan
   - Melihat detail rating

## User Biasa

### Registrasi Akun
1. Buka halaman registrasi: `http://localhost/Sasino/foodievote/public/register.php`
2. Isi formulir registrasi:
   - Username (minimal 3 karakter, hanya huruf, angka, dan underscore)
   - Email (harus valid)
   - Password (minimal 8 karakter)
   - Konfirmasi password (harus sama dengan password)
3. Klik tombol "Register"

### Login sebagai User
1. Buka halaman login: `http://localhost/Sasino/foodievote/public/login.php`
2. Masukkan username dan password yang telah didaftarkan
3. Klik tombol "Login"

### Dashboard User
Setelah login, user akan diarahkan ke dashboard user yang menampilkan:
- Statistik rating pribadi (jumlah rating, rating rata-rata, dll.)
- Aksi cepat untuk fitur-fitur utama
- Aktivitas rating terbaru dari user tersebut
- Jumlah item yang belum dirating

### Profil User
1. Klik menu "Profil Saya" dari dashboard
2. Di halaman ini, user dapat:
   - Melihat informasi akun (username, email)
   - Mengedit informasi pribadi
   - Mengganti password

### Menjelajahi Restoran dan Makanan
1. Gunakan menu navigasi untuk mengakses halaman "Restoran" atau "Makanan"
2. Lihat daftar item yang tersedia dengan informasi seperti:
   - Nama item
   - Deskripsi singkat
   - Gambar pendukung
   - Rating rata-rata
   - Jumlah penilaian
3. Klik pada item untuk melihat detail lengkap

### Memberikan Rating dan Ulasan
1. Pilih restoran atau makanan yang ingin dirating
2. Klik pada tombol "Berikan Rating" atau ikon bintang
3. Pilih jumlah bintang (1-5) sesuai penilaian Anda
4. Tambahkan ulasan teks jika diinginkan
5. Klik tombol "Simpan Rating"
6. Catatan: Anda hanya dapat memberikan satu rating per item

### Melihat Rating Saya
1. Klik menu "Rating Saya" dari dashboard
2. Di halaman ini, user dapat melihat:
   - Semua rating yang telah diberikan
   - Ulasan yang telah dibuat
   - Tanggal dan waktu pemberian rating
   - Item yang dirating (restoran atau makanan)
   - Kemampuan untuk mengedit atau menghapus rating (jika diperlukan)

### Fitur-fitur Tambahan untuk User
- **Pencarian**: Gunakan fitur pencarian untuk menemukan restoran atau makanan tertentu
- **Filter dan Sortir**: Urutkan hasil berdasarkan rating, nama, atau kategori
- **Bookmark/Favorit**: Simpan item favorit untuk referensi di masa depan (jika fitur tersedia)
- **Notifikasi**: Dapatkan pemberitahuan tentang aktivitas terkait akun Anda
- **Statistik Pribadi**: Lihat ringkasan aktivitas rating Anda

### Panduan Memberikan Rating yang Baik
- Berikan penilaian jujur berdasarkan pengalaman nyata
- Tulis ulasan yang informatif dan konstruktif
- Hindari bahasa yang kasar atau tidak pantas
- Fokus pada kualitas makanan, layanan, suasana, atau faktor lain yang relevan
- Gunakan sistem bintang secara proporsional dengan pengalaman Anda

## Guest (Pengunjung)

### Halaman Depan
Pengunjung dapat mengakses halaman depan tanpa login yang menampilkan:
- Informasi tentang sistem FoodieVote
- Fitur-fitur utama sistem
- Informasi tentang tim pengembang
- Tombol untuk login atau registrasi

### Menjelajahi Restoran
1. Klik menu "Restoran" di navbar
2. Lihat daftar restoran yang tersedia
3. Klik pada restoran untuk melihat detail

### Menjelajahi Makanan
1. Klik menu "Makanan" di navbar
2. Lihat daftar makanan yang tersedia
3. Klik pada makanan untuk melihat detail

### Detail Restoran/Makanan
- Informasi lengkap tentang item
- Gambar pendukung
- Rating rata-rata
- Jumlah penilaian
- Ulasan dari pengguna

### Panduan untuk Guest
Sebagai pengunjung (guest), Anda memiliki akses terbatas ke sistem FoodieVote. Berikut adalah panduan lengkap untuk penggunaan sistem sebagai guest:

#### Fitur yang Tersedia untuk Guest
- **Melihat halaman depan**: Informasi umum tentang sistem dan tim pengembang
- **Menjelajahi restoran**: Melihat daftar restoran lengkap dengan informasi dasar
- **Menjelajahi makanan**: Melihat daftar makanan lengkap dengan informasi dasar
- **Melihat detail restoran**: Informasi lengkap tentang restoran termasuk alamat, jam operasional, deskripsi, dan gambar
- **Melihat detail makanan**: Informasi lengkap tentang makanan termasuk deskripsi, harga, dan gambar
- **Melihat rating dan ulasan**: Melihat rating rata-rata dan ulasan dari pengguna lain
- **Mengakses halaman kontak**: Informasi kontak sistem

#### Batasan untuk Guest
- Tidak dapat memberikan rating atau ulasan
- Tidak dapat mengedit atau menghapus informasi
- Tidak dapat mengakses fitur pribadi
- Tidak dapat menyimpan favorit atau bookmark
- Tidak dapat melihat statistik pribadi

#### Keuntungan Menjadi Guest
- Dapat melihat informasi tanpa perlu mendaftar akun
- Dapat membaca rating dan ulasan dari pengguna lain
- Dapat menjelajahi berbagai restoran dan makanan
- Dapat menemukan rekomendasi berdasarkan rating

#### Panduan Penggunaan Sebagai Guest
1. Gunakan menu navigasi untuk menjelajahi sistem
2. Gunakan fitur pencarian untuk menemukan restoran atau makanan tertentu
3. Baca rating dan ulasan dari pengguna lain untuk membantu pengambilan keputusan
4. Gunakan informasi yang tersedia untuk merencanakan kunjungan ke restoran
5. Klik tombol "Daftar" atau "Masuk" jika ingin memberikan rating atau ulasan

#### Mendaftar Sebagai User
Jika Anda ingin memberikan rating atau ulasan, Anda perlu mendaftar sebagai user:
1. Klik tombol "Sign Up" di navbar
2. Isi formulir pendaftaran dengan informasi yang valid
3. Verifikasi akun jika diperlukan
4. Setelah mendaftar, Anda dapat memberikan rating dan ulasan

## Troubleshooting

### Error "Connection failed"
- Pastikan konfigurasi database di `config/database.php` benar
- Periksa apakah server database (MySQL) sedang berjalan
- Pastikan nama database, username, dan password sesuai

### Halaman tidak bisa diakses
- Pastikan mod_rewrite aktif di server Apache
- Periksa apakah file `.htaccess` ada dan berfungsi
- Pastikan URL diakses dengan benar
- Pastikan direktori `public/` digunakan sebagai root URL

### Tampilan rusak
- Pastikan file CSS dan JS bisa diakses
- Periksa apakah path ke file asset benar
- Pastikan file-file di folder `assets/` tidak hilang
- Bersihkan cache browser jika diperlukan

### Tidak bisa login
- Pastikan username dan password benar
- Untuk login pertama kali, gunakan akun admin default
- Pastikan session PHP aktif
- Coba bersihkan cookie dan cache browser
- Pastikan tidak ada karakter spasi tambahan saat mengetik username/password

### Error saat registrasi
- Pastikan username dan email belum digunakan
- Pastikan password memenuhi syarat minimal 8 karakter
- Pastikan konfirmasi password sesuai dengan password
- Pastikan format email valid
- Pastikan tidak ada karakter ilegal dalam username

### Tidak bisa memberikan rating
- Pastikan sudah login
- Pastikan belum pernah memberikan rating untuk item yang sama sebelumnya
- Pastikan rating dalam rentang 1-5
- Pastikan tidak mencoba memberikan rating dua kali untuk item yang sama
- Refresh halaman jika tombol rating tidak muncul

### File upload gambar tidak muncul
- Pastikan folder `uploads/` memiliki izin tulis
- Pastikan ukuran file tidak melebihi batas maksimum
- Pastikan format file didukung (biasanya JPEG, PNG)
- Periksa apakah direktori upload benar-benar ada

### Session tidak berfungsi
- Pastikan session_start() dipanggil sebelum output apapun
- Cek apakah ada whitespace sebelum tag <?php
- Pastikan tidak ada error PHP sebelum session dimulai
- Bersihkan cookie dan cache browser

### Database tidak terkoneksi
- Pastikan service MySQL/MariaDB berjalan
- Cek kembali konfigurasi host, username, password, dan nama database
- Pastikan port database benar (biasanya 3306)
- Pastikan database telah dibuat dan skema telah diimpor

### Error 404 pada halaman tertentu
- Pastikan URL diakses dengan benar
- Cek apakah file view yang dimaksud ada
- Pastikan routing di `public/index.php` benar
- Periksa apakah file `.htaccess` aktif dan berfungsi

### Tidak bisa menyimpan data
- Pastikan form telah diisi dengan benar
- Cek validasi input di sisi server
- Pastikan tidak ada karakter ilegal dalam input
- Periksa apakah database dalam mode read-only

### Waktu dan tanggal tidak sesuai
- Pastikan zona waktu di server telah disetel dengan benar
- Cek konfigurasi PHP date.timezone
- Pastikan database menggunakan zona waktu yang sesuai

### Error saat mengakses halaman admin
- Pastikan Anda login sebagai admin
- Cek apakah role user benar-benar admin
- Pastikan middleware otentikasi berfungsi dengan baik
- Coba logout dan login kembali

### Tabel tidak muncul dengan data
- Pastikan query database tidak ada error
- Cek apakah data benar-benar ada di database
- Pastikan model mengembalikan data dengan format yang benar
- Periksa apakah ada error PHP dalam proses pengambilan data

### Fitur pencarian tidak berfungsi
- Pastikan parameter pencarian dikirim dengan benar
- Cek apakah query pencarian dalam model benar
- Pastikan tidak ada karakter khusus yang tidak difilter
- Periksa apakah fitur pencarian diaktifkan untuk guest/user

Jika mengalami masalah lain yang tidak tercantum di atas, silakan hubungi tim pengembang melalui halaman kontak sistem.