# 🏗 ARCHITECTURE.md — Ichiban Sushi Web Application

Dokumen ini menjelaskan arsitektur teknis, pola desain, dan keputusan teknis yang diterapkan dalam project **Ichiban Sushi**.

---

## Daftar Isi

- [Ringkasan Arsitektur](#ringkasan-arsitektur)
- [Diagram Arsitektur](#diagram-arsitektur)
- [Layer Aplikasi](#layer-aplikasi)
  - [Presentation Layer](#1-presentation-layer)
  - [Application Layer (Controllers)](#2-application-layer-controllers)
  - [Domain Layer (Models)](#3-domain-layer-models)
  - [Data Layer (Database)](#4-data-layer-database)
- [Routing Strategy](#routing-strategy)
- [Autentikasi & Autorisasi](#autentikasi--autorisasi)
- [Frontend Architecture](#frontend-architecture)
  - [Halaman Publik](#halaman-publik-homebladephp--scriptjs)
  - [Admin Panel (SPA)](#admin-panel-spa-appbladephp--adminjs)
- [File Storage Strategy](#file-storage-strategy)
- [Data Flow Diagrams](#data-flow-diagrams)
- [Pola Desain yang Digunakan](#pola-desain-yang-digunakan)
- [Keputusan Teknis](#keputusan-teknis)
- [Dependency Map](#dependency-map)

---

## Ringkasan Arsitektur

Aplikasi ini mengikuti arsitektur **MVC (Model-View-Controller)** standar Laravel dengan modifikasi berikut:

- **Dual Frontend**: Dua interface terpisah — halaman publik (SSR Blade) dan admin panel (SPA vanilla JS)
- **API-in-Web Routes**: Endpoint JSON API didefinisikan di `routes/web.php` (bukan `api.php`) untuk memanfaatkan session authentication
- **Hybrid Rendering**: Landing page menggunakan Server-Side Rendering via Blade, sementara admin panel menggunakan Client-Side Rendering via JavaScript SPA
- **Flat Model Architecture**: Model `Menu` menyimpan kategori sebagai string (denormalisasi), bukan relasi foreign key

```
┌─────────────────────────────────────────────────────────────┐
│                        BROWSER                              │
│                                                             │
│  ┌──────────────────────┐  ┌─────────────────────────────┐  │
│  │   Halaman Publik     │  │      Admin Panel (SPA)      │  │
│  │   (Blade SSR)        │  │    (Vanilla JS + CSS)       │  │
│  │   style.css          │  │    admin.css + admin.js      │  │
│  │   script.js          │  │                             │  │
│  └──────────┬───────────┘  └──────────────┬──────────────┘  │
│             │                             │                  │
└─────────────┼─────────────────────────────┼──────────────────┘
              │ HTTP GET (SSR)              │ fetch() JSON API
              │                             │
┌─────────────┴─────────────────────────────┴──────────────────┐
│                    LARAVEL 10 BACKEND                        │
│                                                              │
│  ┌─────────────────────────────────────────────────────────┐ │
│  │                   routes/web.php                        │ │
│  │  ┌──────────┐  ┌──────────────┐  ┌───────────────────┐ │ │
│  │  │  Guest   │  │ Authenticated │  │ Admin (auth+admin)│ │ │
│  │  │  Routes  │  │   API Routes  │  │   Panel Routes    │ │ │
│  │  └────┬─────┘  └──────┬───────┘  └────────┬──────────┘ │ │
│  └───────┼───────────────┼────────────────────┼────────────┘ │
│          │               │                    │              │
│  ┌───────┴───────────────┴────────────────────┴────────────┐ │
│  │                    CONTROLLERS                          │ │
│  │  PublicController   MenuController   OperatingHourCtrl  │ │
│  │  AuthController     LoginController  CategoryController │ │
│  └───────────────────────┬─────────────────────────────────┘ │
│                          │                                   │
│  ┌───────────────────────┴─────────────────────────────────┐ │
│  │                      MODELS                             │ │
│  │  User    Menu    Category    MenuItem    OperatingHour   │ │
│  └───────────────────────┬─────────────────────────────────┘ │
│                          │                                   │
│  ┌───────────────────────┴─────────────────────────────────┐ │
│  │                  MySQL Database                         │ │
│  │  users  |  menus  |  operating_hours  |  categories     │ │
│  └─────────────────────────────────────────────────────────┘ │
└──────────────────────────────────────────────────────────────┘
```

---

## Diagram Arsitektur

### Request Flow

```
Browser Request
     │
     ▼
┌──────────────┐
│  Middleware   │──── Global: TrustProxies, CORS, CSRF, Session
│    Stack      │──── Route:  auth, admin, guest
└──────┬───────┘
       │
       ▼
┌──────────────┐     ┌──────────────┐     ┌──────────────┐
│   Route      │────▶│  Controller  │────▶│    Model     │
│   Resolver   │     │              │     │  (Eloquent)  │
└──────────────┘     └──────┬───────┘     └──────┬───────┘
                            │                     │
                     ┌──────┴───────┐       ┌─────┴──────┐
                     │   Response   │       │   MySQL    │
                     │ (Blade/JSON) │       │  Database  │
                     └──────────────┘       └────────────┘
```

---

## Layer Aplikasi

### 1. Presentation Layer

Terdiri dari dua frontend terpisah yang melayani pengguna berbeda:

#### Halaman Publik

| File | Peran |
|------|-------|
| `resources/views/publik/home.blade.php` | Template utama landing page, menerima data server-rendered |
| `public/css/style.css` | Stylesheet khusus halaman publik (22KB) |
| `public/js/script.js` | Interaktivitas: smooth scroll, animasi, menu navigation |

#### Admin Panel

| File | Peran |
|------|-------|
| `resources/views/admin/app.blade.php` | Shell SPA admin, berisi HTML semua view (Dashboard, Menu, Jam) |
| `resources/views/auth/login.blade.php` | Halaman login standalone |
| `public/css/admin.css` | Stylesheet admin panel (21KB) |
| `public/js/admin.js` | Seluruh logika SPA: routing, CRUD, form handling, API calls |

---

### 2. Application Layer (Controllers)

Aplikasi memiliki **7 controller** yang diorganisir berdasarkan tanggung jawab:

```
Controllers/
├── PublicController.php          ─── Render landing page + format data menu
├── AuthController.php            ─── Login/logout via JSON (dipakai admin SPA)
├── MenuController.php            ─── REST API menu (CRUD, dipakai admin.js)
├── Auth/
│   └── LoginController.php       ─── Login/logout via Blade redirect
└── Admin/
    ├── DashboardController.php   ─── Statistik dashboard
    ├── CategoryController.php    ─── CRUD kategori (resource route)
    ├── MenuItemController.php    ─── CRUD menu item (legacy)
    └── OperatingHourController.php ── API jam operasional (read + bulk update)
```

#### Controller Responsibility Matrix

| Controller | Tipe Response | Auth Required | Konsumer |
|---|---|---|---|
| `PublicController` | Blade View | ❌ | Browser (customer) |
| `AuthController` | JSON | ❌ (login), ✅ (logout) | admin.js |
| `LoginController` | Blade Redirect | ❌ | Browser (login page) |
| `MenuController` | JSON | ✅ (kecuali publicIndex) | admin.js, script.js |
| `OperatingHourController` | JSON / Blade | ✅ | admin.js |
| `CategoryController` | Blade View | ✅ + Admin | Browser (admin) |
| `DashboardController` | Blade View | ✅ + Admin | Browser (admin) |

---

### 3. Domain Layer (Models)

#### Model: `User`

```php
// Atribut: name, username, email, password, role
// Method: isAdmin() → bool (role === 'admin')
// Traits: HasApiTokens, HasFactory, Notifiable
```

- Mendukung dua role: `admin` dan `user`
- Password otomatis di-hash via cast `hashed`
- Mendukung Sanctum token (tersedia tapi belum dipakai)

#### Model: `Menu` (Tabel Utama)

```php
// Atribut: nama, deskripsi, kategori, harga, aktif, sold, gambar
// Accessor: gambar_url (auto-resolve path gambar)
// Appends: ['gambar_url'] → otomatis masuk JSON response
```

- Kategori disimpan sebagai **string** (denormalisasi), bukan foreign key
- `gambar_url` accessor menangani dua skenario:
  - Gambar lama dari seed: path dimulai `images/` → `asset()`
  - Gambar upload admin: path di storage → `Storage::url()`
- Tabel ini yang aktif dipakai oleh admin SPA dan halaman publik

#### Model: `Category`

```php
// Atribut: slug, name, image, sort_order
// Relasi: hasMany(MenuItem::class)
// Accessor: image_url
```

- Digunakan bersamaan dengan model `MenuItem` (arsitektur legacy)

#### Model: `MenuItem` (Legacy)

```php
// Atribut: category_id, name, price, image, description, is_sold, sort_order
// Relasi: belongsTo(Category::class)
// Accessor: image_url, formatted_price
```

- Model lama yang menggunakan relasi foreign key ke `categories`
- Masih tersedia tapi fungsi utama sudah digantikan oleh model `Menu`

#### Model: `OperatingHour`

```php
// Atribut: day, time, is_closed, sort_order
// Contoh: day='SENIN', time='10:00 - 21:45', is_closed=false
```

---

### 4. Data Layer (Database)

#### Migration Timeline

```
2014_10_12  ── create_users_table (+ role column)
2014_10_12  ── create_password_reset_tokens_table
2019_08_19  ── create_failed_jobs_table
2019_12_14  ── create_personal_access_tokens_table (Sanctum)
2024_01_01  ── create_operating_hours_table
2026_08_28  ── add_username_to_users_table
2026_08_28  ── create_menus_table
2026_08_31  ── add_gambar_to_menus_table
2026_09_01  ── add_sold_to_menus_table
```

#### ER Diagram

```
┌────────────────────┐
│       users        │
├────────────────────┤
│ id (PK)            │
│ name               │
│ username (UNIQUE)  │
│ email (UNIQUE)     │
│ password           │
│ role               │     ┌────────────────────┐
│ remember_token     │     │       menus        │
│ timestamps         │     ├────────────────────┤
└────────────────────┘     │ id (PK)            │
                           │ nama               │
                           │ deskripsi          │
┌────────────────────┐     │ kategori (STRING)  │──── denormalisasi
│  operating_hours   │     │ harga              │
├────────────────────┤     │ aktif              │
│ id (PK)            │     │ sold               │
│ day                │     │ gambar             │
│ time               │     │ timestamps         │
│ is_closed          │     └────────────────────┘
│ sort_order         │
│ timestamps         │     ┌────────────────────┐     ┌────────────────────┐
└────────────────────┘     │    categories      │     │    menu_items      │
                           ├────────────────────┤     ├────────────────────┤
                           │ id (PK)            │◄────│ category_id (FK)   │
                           │ slug (UNIQUE)      │     │ id (PK)            │
                           │ name               │     │ name               │
                           │ image              │     │ price              │
                           │ sort_order         │     │ image              │
                           │ timestamps         │     │ description        │
                           └────────────────────┘     │ is_sold            │
                                                      │ sort_order         │
                                                      │ timestamps         │
                                                      └────────────────────┘
                                                            (legacy)
```

---

## Routing Strategy

### Mengapa API di `web.php`?

Semua endpoint JSON API (menu CRUD, jam operasional) didefinisikan di `routes/web.php`, bukan `routes/api.php`.

**Alasan:**
- `api.php` menggunakan middleware group `api` yang **tidak memuat session**
- Admin SPA login lewat session web biasa (cookie `laravel_session`)
- Jika API di `api.php`, middleware `auth` tidak mengenali admin yang sudah login
- Solusi: semua route di `web.php` → session tersedia → auth berfungsi

### Route Groups

```php
// 1. Guest Only — login form
Route::middleware('guest')->group(fn () => ...);

// 2. Public — landing page, public menu API
Route::get('/', ...);
Route::get('/api/menus/public', ...);

// 3. Authenticated — menu CRUD API, operating hours API
Route::middleware('auth')->group(fn () => ...);

// 4. Admin Only — admin panel views
Route::middleware(['auth', 'admin'])->prefix('admin')->group(fn () => ...);
```

---

## Autentikasi & Autorisasi

### Flow Login

```
                    ┌────────────────┐
                    │  /login (GET)  │
                    │  Login Form    │
                    └───────┬────────┘
                            │ POST (username + email + password)
                            ▼
                    ┌────────────────┐
                    │ AuthController │
                    │   ::login()   │
                    └───────┬────────┘
                            │
                 ┌──────────┼──────────┐
                 │          │          │
                 ▼          ▼          ▼
           Auth::attempt  Username   Session
           (email+pass)   Match?     Regenerate
                 │          │          │
                 └──────────┼──────────┘
                            │
                            ▼
                    ┌────────────────┐
                    │  JSON Response │
                    │  { name: ... } │
                    └────────────────┘
```

### Middleware Chain

| Middleware | Alias | Fungsi |
|---|---|---|
| `Authenticate` | `auth` | Redirect ke login jika belum authenticated |
| `AdminMiddleware` | `admin` | Cek `isAdmin()`, logout + redirect jika bukan admin |
| `RedirectIfAuthenticated` | `guest` | Redirect ke dashboard/home jika sudah login |

### Triple Verification Login

Admin login memerlukan **3 field**:
1. **Username** — diverifikasi setelah `Auth::attempt` berhasil
2. **Email** — digunakan oleh `Auth::attempt`
3. **Password** — digunakan oleh `Auth::attempt`

Jika email + password cocok tapi username tidak match → logout paksa + error.

---

## Frontend Architecture

### Halaman Publik (`home.blade.php` + `script.js`)

#### Server-Side Data Injection

```php
// PublicController::index()
return view('publik.home', compact(
    'menus',          // Collection Menu (Eloquent)
    'operatingHours', // Collection OperatingHour
    'menuData',       // Array terformat per kategori
    'categoryOrder',  // Urutan slug kategori
    'hoursData',      // Array jam operasional [{day, time}]
));
```

Data menu diformat di server menjadi struktur nested:

```json
{
  "ramen": {
    "label": "Ramen",
    "categoryImage": "images/Ramen_kategori.png",
    "items": [
      { "id": 1, "name": "...", "price": 40700, "image": "...", "desc": "...", "sold": false }
    ]
  }
}
```

#### Client-Side Behavior (`script.js`)

- **Smooth Scroll Navigation** — klik nav button, scroll ke section target
- **Sticky Header** — header berubah style saat scroll
- **Intersection Observer** — animasi fade-in saat section masuk viewport
- **Menu Category Tabs** — switch antar kategori menu
- **Responsive Mobile Menu** — hamburger toggle

### Admin Panel SPA (`app.blade.php` + `admin.js`)

#### SPA Architecture

Admin panel adalah **full SPA** yang dijalankan di satu file Blade (`app.blade.php`):

```
app.blade.php (Shell)
├── #login-view    ─── Form login (hidden jika sudah auth)
└── #app-view      ─── Aplikasi utama
    ├── .sidebar    ─── Navigasi: Dashboard, Menu, Jam Operasional
    └── .main
        ├── #view-dashboard   ─── Stat cards + category breakdown
        ├── #view-menu        ─── Tabel menu + modal CRUD
        └── #view-jam         ─── Form jam operasional
```

#### View Switching

```javascript
// admin.js — navigasi tanpa page reload
document.querySelectorAll('[data-view]').forEach(btn => {
    btn.addEventListener('click', () => switchView(btn.dataset.view));
});

function switchView(viewName) {
    // Hide semua #view-*, show yang dipilih
    // Update URL (/admin/dashboard, /admin/menu, /admin/jam-operasional)
    // Update sidebar active state
}
```

#### API Communication Pattern

```javascript
// Semua API call menggunakan native fetch()
// CSRF token diambil dari <meta name="csrf-token">

async function fetchMenus() {
    const res = await fetch('/api/menus', {
        headers: { 'X-CSRF-TOKEN': csrfToken }
    });
    return res.json();
}

// Upload gambar via FormData (bukan JSON)
async function saveMenu(data, file) {
    const fd = new FormData();
    fd.append('nama', data.nama);
    fd.append('gambar', file);
    // PUT method spoofing: fd.append('_method', 'PUT')
    await fetch(`/api/menus/${id}`, { method: 'POST', body: fd });
}
```

#### State Management

Admin SPA menggunakan **simple module-level variables** (bukan framework state):

```javascript
let allMenus = [];       // Cache daftar menu dari API
let currentFilter = '';   // Filter kategori aktif
let searchQuery = '';     // Query pencarian global
```

---

## File Storage Strategy

### Dua Skenario Path Gambar

Model `Menu` menangani dua format path gambar yang berbeda:

```
┌─────────────────────────────────────────────────────────┐
│                    Menu.gambar                           │
│                                                         │
│  Skenario 1: Gambar Seeder (Legacy)                    │
│  ┌─────────────────────────────────────────────┐       │
│  │ gambar = "images/tori_teriyaki_ramen.png"   │       │
│  │ → Cek str_starts_with('images/')            │       │
│  │ → Return asset('images/tori_teriyaki...')    │       │
│  │ → URL: /images/tori_teriyaki_ramen.png      │       │
│  └─────────────────────────────────────────────┘       │
│                                                         │
│  Skenario 2: Upload Admin                              │
│  ┌─────────────────────────────────────────────┐       │
│  │ gambar = "menus/abc123.jpg"                 │       │
│  │ → Storage::disk('public')->url(...)         │       │
│  │ → URL: /storage/menus/abc123.jpg            │       │
│  └─────────────────────────────────────────────┘       │
│                                                         │
│  Skenario 3: Tidak Ada Gambar                          │
│  ┌─────────────────────────────────────────────┐       │
│  │ gambar = null                               │       │
│  │ → Return null (fallback di view/JS)         │       │
│  └─────────────────────────────────────────────┘       │
└─────────────────────────────────────────────────────────┘
```

### Storage Directories

```
storage/app/public/
├── menus/          ← Upload menu via admin panel
└── categories/     ← Upload kategori via admin panel

public/
├── images/         ← Aset statis (logo, gambar seeder, kategori)
└── storage → ../storage/app/public  (symlink)
```

---

## Data Flow Diagrams

### Landing Page Load

```
Browser GET /
     │
     ▼
PublicController::index()
     │
     ├─→ Menu::where('aktif', true)->get()
     │        → Ambil semua menu aktif
     │
     ├─→ OperatingHour::orderBy('sort_order')->get()
     │        → Ambil jadwal operasional
     │
     ├─→ Format data per kategori (categoryConfigs)
     │        → Kelompokkan: ramen, donburi, roll, beverages
     │        → Handle kategori custom/baru
     │
     └─→ return view('publik.home', $data)
              → Render Blade template
              → Inject JSON via @json() untuk script.js
```

### Admin Menu CRUD

```
admin.js                     Laravel Backend                  Database
  │                              │                              │
  │──── GET /api/menus ─────────▶│                              │
  │                              │── Menu::orderByDesc('id') ──▶│
  │                              │◀───── Collection ────────────│
  │◀──── JSON [...menus] ───────│                              │
  │                              │                              │
  │──── POST /api/menus ────────▶│                              │
  │     (FormData + gambar)      │── validate()                 │
  │                              │── Store gambar to storage    │
  │                              │── Menu::create() ───────────▶│
  │◀──── JSON {menu} ──────────│                              │
  │                              │                              │
  │──── PATCH /api/menus/{id}/ ─▶│                              │
  │     toggle {aktif: bool}     │── Menu::find()->save() ─────▶│
  │◀──── JSON {menu} ──────────│                              │
  │                              │                              │
  │──── DELETE /api/menus/{id} ─▶│                              │
  │                              │── Delete gambar from storage │
  │                              │── Menu::delete() ───────────▶│
  │◀──── 204 No Content ───────│                              │
```

---

## Pola Desain yang Digunakan

### 1. Accessor Pattern (Eloquent)
Model `Menu` menggunakan accessor `getGambarUrlAttribute()` untuk menstandarisasi URL gambar, terlepas dari sumber penyimpanan.

### 2. UpdateOrCreate Pattern
Seeder menggunakan `updateOrCreate()` dan `firstOrCreate()` agar idempoten — bisa dijalankan berulang tanpa duplikasi data.

### 3. Method Spoofing
Karena HTML form dan `FormData` hanya mendukung GET/POST, update menu menggunakan `_method=PUT` yang diterjemahkan Laravel menjadi PUT request.

### 4. Content Negotiation
`OperatingHourController` menggunakan `$request->wantsJson()` untuk menentukan apakah response dikembalikan sebagai JSON (SPA) atau Blade redirect (traditional form).

### 5. Middleware Chaining
Route admin menggunakan chain `['auth', 'admin']` — `auth` memastikan user login, `admin` memverifikasi role.

### 6. SPA Shell Pattern
Admin panel menggunakan satu file Blade sebagai "shell" yang memuat semua view HTML. JavaScript mengontrol visibility view mana yang ditampilkan, menggantikan kebutuhan multi-page routing.

---

## Keputusan Teknis

### Mengapa Denormalisasi Kategori di Tabel `menus`?

**Keputusan:** Kategori disimpan sebagai string (`kategori = 'Ramen'`) bukan foreign key ke tabel `categories`.

**Alasan:**
- Menyederhanakan CRUD di admin SPA (tidak perlu manage relasi)
- Jumlah kategori terbatas dan jarang berubah (4 kategori utama)
- Mendukung kategori custom tanpa perlu menambah record di tabel lain
- Trade-off: inkonsistensi naming dimungkinkan, tapi diterima karena hanya admin yang mengelola

### Mengapa Vanilla JS, Bukan Framework?

**Keputusan:** Admin panel dan halaman publik menggunakan vanilla JavaScript tanpa React/Vue/etc.

**Alasan:**
- Kompleksitas UI relatif rendah (3 view + modal)
- Tidak perlu build step tambahan (Vite hanya untuk optional compilation)
- Ukuran bundle minimal (29KB admin.js vs 100KB+ framework)
- Lebih mudah di-maintain tanpa dependency frontend framework

### Mengapa Dua Model Menu?

**Situasi:** Terdapat `Menu` dan `MenuItem` + `Category`.

**Penjelasan:**
- `Category` + `MenuItem` adalah arsitektur awal (normalized, relasi FK)
- `Menu` adalah evolusi yang menyederhanakan pengelolaan via admin SPA
- Keduanya masih tersedia untuk backward compatibility
- `Menu` adalah yang **aktif dipakai** oleh halaman publik dan admin SPA

---

## Dependency Map

### PHP Dependencies (composer.json)

| Package | Versi | Fungsi |
|---|---|---|
| `laravel/framework` | ^10.10 | Core framework |
| `laravel/sanctum` | ^3.3 | API token authentication (tersedia) |
| `laravel/tinker` | ^2.8 | REPL untuk debugging |
| `guzzlehttp/guzzle` | ^7.2 | HTTP client |

### Dev Dependencies

| Package | Versi | Fungsi |
|---|---|---|
| `phpunit/phpunit` | ^10.1 | Unit testing |
| `fakerphp/faker` | ^1.9.1 | Data factory |
| `laravel/pint` | ^1.0 | Code formatter |
| `laravel/sail` | ^1.18 | Docker dev environment |
| `spatie/laravel-ignition` | ^2.0 | Error page |

### Frontend Dependencies

| Asset | Sumber | Fungsi |
|---|---|---|
| Poppins Font | Google Fonts (CDN) | Typography utama |
| Inter Font | Google Fonts (CDN) | Typography sekunder (publik) |
| SVG Icons | Inline | Ikon UI (tidak pakai icon library) |

---

## Keamanan

### Proteksi yang Diterapkan

| Aspek | Implementasi |
|---|---|
| CSRF | Token di `<meta>` tag, dikirim via header `X-CSRF-TOKEN` |
| Password Hashing | `bcrypt` via Laravel `Hash` facade |
| Session Regeneration | `$request->session()->regenerate()` setelah login |
| Session Invalidation | `$request->session()->invalidate()` saat logout |
| Input Validation | Server-side validation di setiap controller |
| File Upload | Validasi tipe (`image`), ukuran max (2MB), ekstensi |
| Role Check | Middleware `admin` dengan `isAdmin()` check |
| SQL Injection | Eloquent ORM (parameterized queries) |

---

<p align="center"><em>Dokumentasi ini di-generate berdasarkan analisis source code project Ichiban Sushi.</em></p>
