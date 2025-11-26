# DigiTamu - Sistem Buku Tamu Digital

**DigiTamu** adalah aplikasi berbasis web yang dirancang sebagai solusi modern untuk menggantikan buku tamu konvensional. Aplikasi ini memungkinkan penyelenggara acara (seperti pernikahan, seminar, atau rapat organisasi) untuk membuat buku tamu digital, mengelola daftar kehadiran, dan mendapatkan laporan tamu secara *real-time*.

Dikembangkan menggunakan PHP Native dan MySQL dengan antarmuka yang bersih dan responsif.

---

## 📋 Daftar Isi

- [Tentang Aplikasi](#-tentang-aplikasi)
- [Fitur Utama](#-fitur-utama)
- [Teknologi yang Digunakan](#-teknologi-yang-digunakan)
- [Struktur Database](#-struktur-database)
- [Panduan Instalasi](#-panduan-instalasi)
- [Cara Penggunaan](#-cara-penggunaan)
- [Lisensi](#-lisensi)

---

## 📖 Tentang Aplikasi

DigiTamu memfasilitasi pencatatan kehadiran tamu dengan menyediakan tautan (URL) unik untuk setiap acara. Tamu dapat mengisi data diri mereka melalui perangkat masing-masing (ponsel/tablet) atau melalui perangkat yang disediakan di meja registrasi. Data yang masuk akan tersimpan otomatis ke dalam database dan dapat dipantau oleh penyelenggara melalui dashboard admin.

---

## ✨ Fitur Utama

### 1. Manajemen Akun Penyelenggara
- **Registrasi & Login Aman:** Sistem autentikasi menggunakan enkripsi `password_hash` (BCRYPT) untuk keamanan data pengguna.
- **Profil & Pengaturan:** Penyelenggara dapat memperbarui nama lengkap dan kata sandi akun.

### 2. Manajemen Acara (Event)
- **Pembuatan Acara Dinamis:** Penyelenggara dapat membuat acara baru dengan tanggal dan nama acara yang dapat disesuaikan.
- **Slug URL Unik:** Setiap acara memiliki URL khusus (contoh: `?event=pernikahan-a-b`) untuk akses buku tamu publik.
- **Template Buku Tamu:** Tersedia berbagai jenis template formulir sesuai tipe acara:
    - Pernikahan (Nama, Alamat, Ucapan).
    - Organisasi/Seminar (Nama, Instansi, Pesan).
    - Umum (Nama, Info Tambahan, Pesan).

### 3. Buku Tamu (Guest Interface)
- **Antarmuka Publik:** Halaman khusus bagi tamu untuk mengisi daftar hadir yang responsif di perangkat mobile.
- **Feedback Langsung:** Konfirmasi visual (Berhasil/Gagal) setelah tamu mengisi data.

### 4. Pelaporan & Dashboard
- **Dashboard Informatif:** Ringkasan daftar acara yang telah dibuat beserta tautan aksesnya.
- **Detail Data Tamu:** Melihat daftar lengkap tamu yang hadir pada acara tertentu.
- **Cetak Laporan:** Fitur `Print` bawaan untuk mencetak rekapitulasi kehadiran tamu ke format fisik atau PDF.

---

## 🛠 Teknologi yang Digunakan

- **Backend:** PHP (Native/Procedural).
- **Database:** MySQL / MariaDB via `mysqli` driver.
- **Frontend:** HTML5, CSS3 (Custom Styling + Google Fonts 'Poppins'), FontAwesome Icons.
- **Server:** Apache (XAMPP/WAMP/LAMP).

---

## 🗄 Struktur Database

Aplikasi ini menggunakan skema relasional dengan tabel utama sebagai berikut:

1.  **`penyelenggara`**: Menyimpan data akun admin/user (ID, Username, Password, Nama Lengkap).
2.  **`jenis_acara`**: Menyimpan template formulir (Pernikahan, Seminar, dll).
3.  **`acara`**: Menyimpan data event yang terhubung ke penyelenggara dan jenis template.
4.  **`tamu`**: Menyimpan data tamu yang hadir, terhubung ke tabel acara.

---

## ⚙️ Panduan Instalasi

Ikuti langkah-langkah berikut untuk menjalankan proyek ini di lingkungan lokal (Localhost):

### 1. Persiapan Database
1.  Buka aplikasi manajemen database (seperti phpMyAdmin).
2.  Buat database baru dengan nama `digitamu`.
3.  Import file `Query.sql` yang disertakan dalam repositori ini ke dalam database `digitamu`. Pastikan tabel `jenis_acara` telah terisi data awal (seeding).

### 2. Konfigurasi Koneksi
1.  Buka file `config.php`.
2.  Sesuaikan konfigurasi database dengan lingkungan server Anda:
    ```php
    define('DB_HOST', '127.0.0.1'); // atau localhost
    define('DB_USER', 'root');      // User database Anda
    define('DB_PASS', '');          // Password database Anda
    define('DB_NAME', 'digitamu');
    define('DB_PORT', 3306);        // Sesuaikan port (Default MySQL: 3306)
    ```

### 3. Menjalankan Aplikasi
1.  Pindahkan folder proyek ke dalam direktori server web (contoh: `htdocs` untuk XAMPP).
2.  Buka browser dan akses: `http://localhost/nama-folder-proyek/`.
3.  Anda akan diarahkan ke halaman *Landing Page*.

---

## 🚀 Cara Penggunaan

1.  **Registrasi:** Klik tombol "Daftar" untuk membuat akun penyelenggara baru.
2.  **Login:** Masuk menggunakan username dan password yang telah didaftarkan.
3.  **Buat Acara:** Pergi ke menu "Buat Acara", isi detail acara, pilih tipe template, dan tentukan Slug URL unik.
4.  **Bagikan Link:** Di Dashboard, salin tautan pada kolom "Link Publik" atau klik tombol "Buka Form". Bagikan link ini kepada tamu undangan.
5.  **Monitoring:** Pantau tamu yang masuk melalui menu "Detail" pada setiap acara di Dashboard.

---

## 📝 Lisensi

Proyek ini dibuat untuk tujuan pembelajaran dan pengembangan sistem basis data lanjut. Bebas untuk dimodifikasi dan dikembangkan lebih lanjut.
