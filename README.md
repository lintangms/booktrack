# 📚 BookTrack - Sistem Informasi Manajemen Perpustakaan

Aplikasi manajemen perpustakaan modern berbasis **Laravel** dan **MySQL**, dirancang untuk mempermudah pengelolaan katalog buku, anggota perpustakaan, transaksi peminjaman, serta pengembalian buku dan denda keterlambatan secara otomatis.

---

## 👥 Tim Pengembang & Pembagian Modul CRUD

| No | Peran / Anggota | Tanggung Jawab Modul | Branch Fitur |
|:---|:---|:---|:---|
| 1 | **Ketua Tim** | **CRUD 1: Data Buku & Katalog** (`/data-buku`) | `feat/data-buku` |
| 2 | **Anggota 1** | **CRUD 2: Data Anggota / Mahasiswa** (`/data-anggota`) | `feature/crud-anggota` |
| 3 | **Anggota 2** | **CRUD 3: Transaksi Peminjaman** (`/peminjaman`) | `feature/crud-peminjaman` |
| 4 | **Anggota 3** | **CRUD 4: Transaksi Pengembalian & Denda** (`/pengembalian`) | `feature/crud-pengembalian` |

---

## 🚀 Fitur Unggulan

- **Autentikasi & Otorisasi:** Sistem login, registrasi akun anggota baru, proteksi session, dan manajemen peran (Administrator & Anggota).
- **Dashboard Ringkasan:** Statistik realtime total koleksi, jumlah anggota, buku dipinjam, buku terlambat, serta grafik tren peminjaman.
- **CRUD 1 (Katalog Buku):**
  - **Create:** Tambah judul buku baru dengan ISBN, kategori, stok, dan tema sampul.
  - **Read:** Daftar buku dengan pencarian cepat (judul/penulis/ISBN) dan filter kategori.
  - **Update:** Edit informasi buku serta pembaruan kuota stok buku.
  - **Delete:** Penghapusan data buku dengan konfirmasi.
- **CRUD 2 (Manajemen Anggota):** Kelola data mahasiswa, kontak, program studi, dan status keaktifan.
- **CRUD 3 (Peminjaman Buku):** Pencatatan transaksi peminjaman, otomatisasi pengurangan stok buku, dan deteksi jatuh tempo.
- **CRUD 4 (Pengembalian & Denda):** Kalkulasi denda keterlambatan otomatis (Rp 1.000/hari), inspeksi kondisi fisik buku (Baik/Rusak/Hilang), dan pemulihan stok buku.

---

## ⚙️ Persyaratan Sistem

- **PHP:** >= 8.2 (dengan ekstensi `pdo_mysql`, `mbstring`, `openssl`)
- **Web Server:** Apache (XAMPP) atau PHP CLI Server
- **Database:** MySQL / MariaDB (XAMPP port 3306)
- **Dependency Manager:** Composer

---

## 🛠️ Panduan Instalasi Lokal

1. **Clone Repositori:**
   ```bash
   git clone https://github.com/lintangms/booktrack.git
   cd booktrack
   ```

2. **Instal Dependensi:**
   ```bash
   composer install
   ```

3. **Konfigurasi Environment:**
   Duplikat file `.env.example` menjadi `.env`:
   ```bash
   copy .env.example .env
   ```
   Pastikan konfigurasi database di `.env` sesuai dengan MySQL XAMPP Anda:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=book_track
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. **Generate Application Key:**
   ```bash
   php artisan key:generate
   ```

5. **Migrasi Database & Seeder Awal:**
   ```bash
   php artisan migrate:fresh --seed
   ```

6. **Jalankan Aplikasi:**
   ```bash
   php artisan serve
   ```
   Akses aplikasi di browser melalui: **[http://localhost:8000](http://localhost:8000)**

---

## 🔑 Akun Default untuk Pengujian

| Peran | Email | Kata Sandi |
|:---|:---|:---|
| **Administrator** | `alya@booktrack.id` | `password123` |
| **Anggota (Mahasiswa)** | `lintang@kampus.ac.id` | `password123` |
| **Anggota (Mahasiswa)** | `lintangmuhammadshiddiq@gmail.com` | `password` |

---

## 🧪 Pengujian Otomatis (Unit & Feature Test)

Aplikasi telah dilengkapi unit test dan feature test otomatis menggunakan PHPUnit:
```bash
php artisan test
```
