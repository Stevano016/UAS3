Resume Proyek Sistem Informasi
Judul Sistem

FoodieVote – Sistem Informasi Rating Restoran dan Makanan Berbasis Web Menggunakan PHP Native

Deskripsi Umum Sistem

FoodieVote adalah aplikasi web berbasis PHP Native yang menyediakan layanan pemberian rating dan ulasan terhadap restoran serta makanan. Sistem ini memungkinkan pengguna untuk membagikan pengalaman kuliner mereka, sekaligus membantu pengunjung lain dalam mengambil keputusan berdasarkan penilaian yang telah diberikan.

Seluruh komponen sistem, termasuk halaman tampilan, dikembangkan menggunakan file PHP (.php) sehingga logika, validasi session, dan kontrol akses dapat diterapkan langsung pada setiap halaman tanpa ketergantungan pada file HTML statis.

Arsitektur Aplikasi

FoodieVote menerapkan arsitektur modular dengan pemisahan tanggung jawab yang jelas pada setiap lapisan sistem. Struktur folder utama aplikasi adalah sebagai berikut:

foodievote/
├── config/
├── core/
├── modules/
├── views/
├── public/
└── assets/


Arsitektur ini dirancang untuk menjaga keteraturan kode, mempermudah pemeliharaan, serta memungkinkan pengembangan fitur secara bertahap.

Penjelasan Struktur Direktori
1. Direktori config

Direktori config berfungsi sebagai pusat konfigurasi aplikasi.

database.php
Digunakan untuk mengatur koneksi ke database MySQL yang digunakan oleh seluruh modul sistem.

config.php
Berisi pengaturan umum dan konstanta global aplikasi seperti nama aplikasi dan konfigurasi dasar lainnya.

2. Direktori core

Direktori core berisi komponen inti yang menangani autentikasi, session, dan pengendalian akses.

session.php
Mengelola inisialisasi dan penggunaan session PHP secara terpusat.

auth.php
Menyediakan fungsi untuk memeriksa status login dan peran (role) pengguna.

middleware.php
Digunakan untuk membatasi akses halaman berdasarkan role pengguna, sehingga setiap halaman PHP hanya dapat diakses oleh pihak yang berwenang.

3. Direktori modules

Direktori modules berisi logika bisnis utama aplikasi yang dipisahkan berdasarkan fitur. Setiap modul memiliki file model dan controller.

a. Modul Users

user.model.php
Menangani operasi database terkait pengguna, seperti pengambilan data user, validasi akun, dan manajemen data user.

user.controller.php
Mengelola proses login, logout, pengelolaan data user oleh admin, serta pembaruan profil pengguna.

b. Modul Restaurants

restaurant.model.php
Mengelola operasi CRUD data restoran pada database.

restaurant.controller.php
Menangani logika pengelolaan restoran oleh admin.

c. Modul Foods

food.model.php
Mengelola operasi CRUD data makanan serta relasinya dengan restoran.

food.controller.php
Mengatur alur penambahan, pembaruan, dan penghapusan data makanan.

d. Modul Ratings

rating.model.php
Mengelola penyimpanan dan pengambilan data rating serta ulasan.

rating.controller.php
Menangani proses pemberian rating oleh user serta moderasi rating oleh admin.

4. Direktori views

Direktori views berisi halaman tampilan berbasis PHP (.php) yang memadukan logika ringan, validasi session, dan antarmuka pengguna.

views/admin/
Berisi halaman PHP untuk dashboard admin dan manajemen data pengguna, restoran, makanan, serta rating.

views/user/
Berisi halaman PHP untuk interaksi user, seperti pemberian rating, pengelolaan profil, dan melihat data restoran serta makanan.

views/guest/
Berisi halaman PHP yang dapat diakses tanpa login untuk menampilkan informasi umum, daftar restoran, makanan, dan rating.

Tidak terdapat file HTML statis pada sistem ini; seluruh tampilan diolah melalui file PHP.

5. Direktori public

Direktori public merupakan titik masuk utama aplikasi.

index.php
Halaman utama yang dapat diakses oleh guest.

login.php
Halaman autentikasi pengguna (admin dan user).

logout.php
Menangani proses logout dan penghancuran session.

6. Direktori assets

Direktori assets digunakan untuk menyimpan file pendukung tampilan seperti gambar, file CSS, dan JavaScript.

Role dan Hak Akses Pengguna
Admin

Login ke sistem

Mengelola data pengguna

Mengelola data restoran dan makanan

Mengelola serta memoderasi rating dan ulasan

Mengatur status konten

User

Login ke sistem

Memberikan rating dan ulasan restoran dan makanan

Mengelola profil pribadi

Mengedit dan menghapus rating milik sendiri

Guest

Mengakses halaman utama

Melihat data restoran, makanan, dan rating

Tidak dapat melakukan perubahan data

Keamanan Sistem

Password disimpan menggunakan metode hashing bcrypt

Autentikasi berbasis session PHP

Pembatasan akses halaman menggunakan middleware

Validasi hak akses berdasarkan role pengguna

Penggunaan foreign key dan unique constraint pada database untuk menjaga integritas data