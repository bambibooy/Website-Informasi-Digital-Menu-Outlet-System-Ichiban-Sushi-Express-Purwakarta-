<p align="center">
  <img src="public/images/logo ichiban.png" alt="Ichiban Sushi Logo" width="80" />
</p>

<h1 align="center">Ichiban Sushi — Web Restaurant</h1>

<p align="center">
  Website restoran Jepang modern dengan <strong>halaman publik</strong> untuk pelanggan dan <strong>admin panel SPA</strong> untuk manajemen menu &amp; jam operasional.
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-10.x-FF2D20?logo=laravel&logoColor=white" alt="Laravel 10" />
  <img src="https://img.shields.io/badge/PHP-%3E%3D8.1-777BB4?logo=php&logoColor=white" alt="PHP 8.1+" />
  <img src="https://img.shields.io/badge/MySQL-Database-4479A1?logo=mysql&logoColor=white" alt="MySQL" />
  <img src="https://img.shields.io/badge/License-MIT-green" alt="License MIT" />
</p>

---

## 📋 Daftar Isi

- [Tentang Project](#-tentang-project)
- [Fitur Utama](#-fitur-utama)
- [Tech Stack](#-tech-stack)
- [Prasyarat](#-prasyarat)
- [Instalasi](#-instalasi)
- [Konfigurasi](#-konfigurasi)
- [Menjalankan Aplikasi](#-menjalankan-aplikasi)
- [Akun Default](#-akun-default)
- [Struktur Project](#-struktur-project)
- [API Endpoints](#-api-endpoints)
- [Database Schema](#-database-schema)
- [Seeder Data](#-seeder-data)
- [Screenshot](#-screenshot)
- [Lisensi](#-lisensi)

---

## Tentang Project

**Ichiban Sushi** adalah aplikasi web restoran Jepang yang dibangun dengan Laravel 10. Aplikasi ini terdiri dari dua bagian utama:

1. **Halaman Publik** — Landing page interaktif untuk pelanggan yang menampilkan menu restoran (Ramen, Donburi, Fushion Roll, Beverages), jam operasional, dan informasi tentang restoran.
2. **Admin Panel** — Single Page Application (SPA) berbasis vanilla JavaScript untuk admin mengelola menu, mengatur status sold/aktif, dan mengelola jam operasional restoran.

---

## Fitur Utama

### Halaman Publik (Customer-Facing)
- Landing page responsif dengan desain modern bertema Jepang
- Navigasi smooth-scroll ke section: *Tentang Kami*, *Menu Kami*, *Jam Operasional*
- Tampilan menu dikelompokkan per kategori dengan gambar, harga, dan deskripsi
- Indikator **SOLD** otomatis untuk item yang sedang tidak tersedia
- Jam operasional dinamis (diambil dari database)
- Dukungan desktop & mobile (responsive design)
- Tombol akses cepat ke admin panel untuk user yang sudah login

### Autentikasi
- Login admin dengan **username + email + password** (triple verification)
- Fitur **"Ingat Saya"** (remember me) menggunakan Laravel session + cookie
- Role-based access: hanya user dengan `role = admin` yang bisa masuk admin panel
- Proteksi route dengan middleware `auth` + `admin`

### Admin Panel (SPA)
- **Dashboard** — Statistik ringkasan: total menu, menu aktif, jumlah kategori, sebaran per kategori
- **Manajemen Menu** — CRUD lengkap: tambah, edit, hapus menu dengan upload gambar
- **Toggle Status** — Aktifkan/nonaktifkan menu dan tandai sebagai SOLD dari tabel langsung
- **Jam Operasional** — Edit jadwal buka/tutup per hari (Senin–Minggu) secara bulk
- **Pencarian Global** — Filter menu berdasarkan nama dari search bar
- **Filter Kategori** — Pill button untuk filter menu per kategori
- **Responsive Sidebar** — Sidebar navigasi yang bisa di-toggle di mobile

---

## 🛠 Tech Stack

| Layer        | Teknologi                                      |
| ------------ | ---------------------------------------------- |
| **Backend**  | PHP 8.1+, Laravel 10.x                        |
| **Database** | MySQL                                          |
| **Frontend** | Blade Templates, Vanilla JavaScript, CSS       |
| **Font**     | Google Fonts (Poppins, Inter)                  |
| **Auth**     | Laravel Session + Sanctum (token capability)   |
| **Storage**  | Laravel Filesystem (local `storage/app/public`)|
| **Server**   | Laragon (development) / Apache / Nginx         |

---

## Prasyarat

Pastikan sistem kamu sudah memiliki:

- **PHP** ≥ 8.1 dengan ekstensi: `pdo_mysql`, `mbstring`, `openssl`, `fileinfo`, `gd`
- **Composer** ≥ 2.x
- **MySQL** ≥ 5.7 atau **MariaDB** ≥ 10.3
- **Node.js** ≥ 16 (opsional, hanya jika ingin compile assets via Vite)
- **Git**

> **Rekomendasi:** Gunakan [Laragon](https://laragon.org/) untuk setup cepat di Windows — PHP, MySQL, dan Apache sudah termasuk.

---

## Instalasi

### 1. Clone Repository

```bash
git clone https://github.com/username/web-ichiban-sushi.git
cd web-ichiban-sushi
```

### 2. Install Dependensi PHP

```bash
composer install
```

### 3. Salin File Environment

```bash
cp .env.example .env
```

### 4. Generate Application Key

```bash
php artisan key:generate
```

### 5. Buat Database

Buat database MySQL baru:

```sql
CREATE DATABASE db_restoran CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 6. Konfigurasi Database

Edit file `.env` sesuai kredensial MySQL kamu:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_restoran
DB_USERNAME=root
DB_PASSWORD=
```

### 7. Jalankan Migrasi & Seeder

```bash
php artisan migrate --seed
```

Perintah ini akan:
- Membuat semua tabel (users, menus, operating_hours, dll.)
- Meng-seed akun admin default
- Meng-seed 4 kategori menu (Ramen, Donburi, Fushion Roll, Beverages)
- Meng-seed 27 item menu lengkap dengan gambar dan deskripsi
- Meng-seed jadwal operasional 7 hari (Senin–Minggu)

### 8. Buat Symlink Storage

```bash
php artisan storage:link
```

> Ini membuat symlink `public/storage` → `storage/app/public` agar file yang di-upload via admin bisa diakses dari browser.

---

## ⚙ Konfigurasi

### Environment Variables Penting

| Variable          | Deskripsi                            | Default              |
| ----------------- | ------------------------------------ | -------------------- |
| `APP_NAME`        | Nama aplikasi                        | `Laravel`            |
| `APP_ENV`         | Environment (local/production)       | `local`              |
| `APP_DEBUG`       | Mode debug                           | `true`               |
| `APP_URL`         | Base URL aplikasi                    | `http://127.0.0.1:8000` |
| `DB_DATABASE`     | Nama database MySQL                  | `db_restoran`        |
| `DB_USERNAME`     | Username database                    | `root`               |
| `DB_PASSWORD`     | Password database                    | (kosong)             |
| `SESSION_DRIVER`  | Driver sesi                          | `file`               |
| `FILESYSTEM_DISK` | Disk filesystem default              | `local`              |

---

## ▶ Menjalankan Aplikasi

### Development Server

```bash
php artisan serve
```

Akses di browser: [http://127.0.0.1:8000](http://127.0.0.1:8000)

### Dengan Laragon

Jika menggunakan Laragon, cukup letakkan project di folder `C:\laragon\www\`, lalu akses melalui:

```
http://web-ichiban-sushi.test
```

---

## Akun Default

Setelah menjalankan seeder, akun admin yang tersedia:

| Field      | Nilai                  |
| ---------- | ---------------------- |
| **Name**   | Admin Ichiban Sushi    |
| **Username** | `admin`              |
| **Email**  | `admin@ichiban.com`    |
| **Password** | `password`           |
| **Role**   | `admin`                |

> **Penting:** Segera ganti password default pada environment production!

---

## Struktur Project

```
web-ichiban-sushi/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/
│   │   │   │   ├── CategoryController.php    # CRUD kategori
│   │   │   │   ├── DashboardController.php   # Dashboard admin
│   │   │   │   ├── MenuItemController.php    # CRUD menu item (legacy)
│   │   │   │   └── OperatingHourController.php # API jam operasional
│   │   │   ├── Auth/
│   │   │   │   └── LoginController.php       # Form & proses login (Blade)
│   │   │   ├── AuthController.php            # Login/logout API (JSON)
│   │   │   ├── MenuController.php            # API CRUD menu (SPA admin)
│   │   │   └── PublicController.php          # Landing page controller
│   │   ├── Middleware/
│   │   │   ├── AdminMiddleware.php           # Cek role admin
│   │   │   └── ...                           # Middleware bawaan Laravel
│   │   └── Kernel.php                        # Registrasi middleware
│   ├── Models/
│   │   ├── Category.php                      # Model kategori
│   │   ├── Menu.php                          # Model menu (tabel utama)
│   │   ├── MenuItem.php                      # Model menu item (legacy)
│   │   ├── OperatingHour.php                 # Model jam operasional
│   │   └── User.php                          # Model user + isAdmin()
│   └── Providers/
├── database/
│   ├── migrations/
│   │   ├── create_users_table                # Tabel users + role
│   │   ├── add_username_to_users_table       # Kolom username
│   │   ├── create_menus_table                # Tabel menu utama
│   │   ├── add_gambar_to_menus_table         # Kolom gambar
│   │   ├── add_sold_to_menus_table           # Kolom sold
│   │   └── create_operating_hours_table      # Tabel jam operasional
│   └── seeders/
│       ├── DatabaseSeeder.php                # Orchestrator seeder
│       ├── AdminSeeder.php                   # Akun admin default
│       ├── CategorySeeder.php                # 4 kategori menu
│       ├── MenuSeeder.php                    # 27 item menu
│       ├── MenuItemSeeder.php                # Menu items (legacy)
│       └── OperatingHourSeeder.php           # Jadwal 7 hari
├── public/
│   ├── css/
│   │   ├── style.css                         # Stylesheet halaman publik
│   │   └── admin.css                         # Stylesheet admin panel
│   ├── js/
│   │   ├── script.js                         # JavaScript halaman publik
│   │   └── admin.js                          # JavaScript admin panel (SPA)
│   └── images/                               # Aset gambar menu & logo
├── resources/views/
│   ├── admin/
│   │   └── app.blade.php                     # Layout admin SPA (all-in-one)
│   ├── auth/
│   │   └── login.blade.php                   # Halaman login standalone
│   └── publik/
│       └── home.blade.php                    # Landing page publik
├── routes/
│   ├── web.php                               # Semua route (web + API-like)
│   └── api.php                               # (Kosong, API via web.php)
├── composer.json
├── .env.example
└── README.md
```

---

## API Endpoints

Semua API endpoint didefinisikan di `routes/web.php` (bukan `api.php`) agar session/auth Laravel berfungsi.

### Public (Tanpa Login)

| Method | Endpoint              | Deskripsi                              |
| ------ | --------------------- | -------------------------------------- |
| `GET`  | `/`                   | Halaman utama (landing page)           |
| `GET`  | `/api/menus/public`   | Daftar menu aktif (JSON)               |
| `GET`  | `/login`              | Halaman form login                     |
| `POST` | `/login`              | Proses login (username + email + password) |

### Authenticated (Butuh Login)

| Method   | Endpoint                     | Deskripsi                              |
| -------- | ---------------------------- | -------------------------------------- |
| `GET`    | `/api/menus`                 | Semua menu (aktif & nonaktif)          |
| `POST`   | `/api/menus`                 | Tambah menu baru (FormData)            |
| `PUT`    | `/api/menus/{id}`            | Update menu (FormData + `_method=PUT`) |
| `PATCH`  | `/api/menus/{id}/toggle`     | Toggle status aktif/nonaktif           |
| `DELETE` | `/api/menus/{id}`            | Hapus menu                             |
| `GET`    | `/api/operating-hours`       | Ambil semua jam operasional            |
| `PUT`    | `/api/operating-hours`       | Update jam operasional (bulk)          |
| `POST`   | `/logout`                    | Logout                                 |

### Admin Only (Auth + Admin Role)

| Method | Endpoint                   | Deskripsi                         |
| ------ | -------------------------- | --------------------------------- |
| `GET`  | `/admin/dashboard`         | Admin panel — Dashboard           |
| `GET`  | `/admin/menu`              | Admin panel — Menu management     |
| `GET`  | `/admin/jam-operasional`   | Admin panel — Jam operasional     |
| `*`    | `/admin/categories/*`      | CRUD kategori (resource route)    |

---

## 🗄 Database Schema

### Tabel `users`

| Kolom               | Tipe        | Keterangan              |
| -------------------- | ----------- | ----------------------- |
| `id`                 | BIGINT (PK) | Auto-increment          |
| `name`               | VARCHAR     | Nama lengkap            |
| `username`           | VARCHAR(50) | Username unik           |
| `email`              | VARCHAR     | Email unik              |
| `email_verified_at`  | TIMESTAMP   | Nullable                |
| `password`           | VARCHAR     | Hashed password         |
| `role`               | VARCHAR     | `admin` / `user`        |
| `remember_token`     | VARCHAR     | Token "ingat saya"      |
| `created_at`         | TIMESTAMP   |                         |
| `updated_at`         | TIMESTAMP   |                         |

### Tabel `menus`

| Kolom        | Tipe            | Keterangan                                |
| ------------ | --------------- | ----------------------------------------- |
| `id`         | BIGINT (PK)     | Auto-increment                            |
| `nama`       | VARCHAR         | Nama menu                                 |
| `deskripsi`  | TEXT            | Deskripsi (nullable)                      |
| `kategori`   | VARCHAR         | Nama kategori (Ramen/Donburi/dll)         |
| `harga`      | UNSIGNED INT    | Harga dalam Rupiah                        |
| `aktif`      | BOOLEAN         | Tampil di halaman publik (default: true)  |
| `sold`       | BOOLEAN         | Status habis/sold out (default: false)    |
| `gambar`     | VARCHAR         | Path gambar (nullable)                    |
| `created_at` | TIMESTAMP       |                                           |
| `updated_at` | TIMESTAMP       |                                           |

### Tabel `operating_hours`

| Kolom        | Tipe        | Keterangan                        |
| ------------ | ----------- | --------------------------------- |
| `id`         | BIGINT (PK) | Auto-increment                    |
| `day`        | VARCHAR     | Nama hari (SENIN, SELASA, dll)    |
| `time`       | VARCHAR     | Jam buka (misal: `10:00 - 21:45`) |
| `is_closed`  | BOOLEAN     | Apakah tutup pada hari itu        |
| `sort_order` | INTEGER     | Urutan tampil                     |
| `created_at` | TIMESTAMP   |                                   |
| `updated_at` | TIMESTAMP   |                                   |

---

## Seeder Data

Seeder menyediakan data awal yang siap pakai:

### Menu (27 item)

| Kategori       | Jumlah | Rentang Harga          |
| -------------- | ------ | ---------------------- |
| Ramen          | 7      | Rp 40.700 – Rp 46.200 |
| Donburi        | 5      | Rp 37.400 – Rp 46.200 |
| Fushion Roll   | 9      | Rp 30.000 – Rp 52.000 |
| Beverages      | 7      | Rp 12.100 – Rp 14.300 |

### Jam Operasional

| Hari    | Jam                |
| ------- | ------------------ |
| SENIN   | 10:00 – 21:45      |
| SELASA  | 10:00 – 21:45      |
| RABU    | 10:00 – 21:45      |
| KAMIS   | 10:00 – 21:45      |
| JUMAT   | 10:00 – 21:45      |
| SABTU   | 10:00 – 21:45      |
| MINGGU  | 10:00 – 21:45      |

---

## Screenshot

> Tambahkan screenshot halaman publik dan admin panel di sini.

```
screenshots/
├── public-landing.png
├── public-menu.png
├── admin-dashboard.png
├── admin-menu.png
└── admin-jam-operasional.png
```

---

## erintah Artisan Berguna

```bash
# Jalankan migrasi
php artisan migrate

# Reset dan seed ulang database
php artisan migrate:fresh --seed

# Buat symlink storage
php artisan storage:link

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Lihat semua route
php artisan route:list
```

---

## Kontribusi

1. Fork repository ini
2. Buat branch fitur (`git checkout -b fitur/fitur-baru`)
3. Commit perubahan (`git commit -m 'Tambah fitur baru'`)
4. Push ke branch (`git push origin fitur/fitur-baru`)
5. Buat Pull Request

---

## Lisensi

Project ini dilisensikan di bawah [MIT License](LICENSE).

---

<p align="center">
  Dibuat dengan ❤️ untuk <strong>Ichiban Sushi</strong>
</p>
