# -*- coding: utf-8 -*-
import os
from scratch_pdf_engine import PDFEngine

def build_ukk_guide():
    pdf = PDFEngine("PANDUAN_UKK_ICHIBAN_SUSHI_LARAVEL10.pdf")
    
    # ================= COVER BANNER =================
    pdf.add_title_header(
        "PANDUAN LENGKAP UJI KOMPETENSI KEAHLIAN (UKK)",
        "Bedah Arsitektur & Source Code: Website Restoran Ichiban Sushi (Laravel 10)",
        "DOKUMEN BELAJAR SISWA SMK RPL"
    )
    
    pdf.add_callout(
        "PENGANTAR UNTUK SISWA",
        "Dokumen ini disusun khusus sebagai bahan belajar persiapan tanya jawab lisan dan uji teknis pada Uji Kompetensi Keahlian (UKK) Rekayasa Perangkat Lunak. Seluruh penjelasan mengacu langsung pada kode asli project Ichiban Sushi tanpa rekayasa.",
        "tip"
    )
    
    # ================= BAGIAN 1: STRUKTUR FOLDER =================
    pdf.add_section_h1("Ringkasan Struktur Folder Project", "1")
    pdf.add_paragraph("Project ini menggunakan arsitektur Hybrid SPA (Single Page Application): backend Laravel 10 menyediakan RESTful API dan satu shell Blade utama, sedangkan interaksi dinamis ditangani oleh Vanilla JavaScript (Fetch API). Berikut struktur folder inti yang wajib dipahami:")
    
    pdf.add_bullet("app/Http/Controllers/AuthController.php :", "Menangani logika autentikasi login bertahap (validasi username, email, password), regenerasi sesi, dan proses logout.")
    pdf.add_bullet("app/Http/Controllers/Auth/LoginController.php :", "Menampilkan form login jika user adalah guest; mengalihkan langsung ke dashboard jika sudah login.")
    pdf.add_bullet("app/Http/Controllers/MenuController.php :", "Mengelola CRUD menu restoran, validasi harga/kategori, dan upload/penghapusan gambar di storage.")
    pdf.add_bullet("app/Http/Controllers/Admin/CategoryController.php :", "Mengelola CRUD kategori carousel, pembuatan slug otomatis, dan sinkronisasi nama kategori pada tabel menu.")
    pdf.add_bullet("app/Http/Controllers/Admin/OperatingHourController.php :", "Membaca jadwal dan melakukan bulk update jadwal 7 hari operasional menggunakan updateOrCreate().")
    pdf.add_bullet("app/Http/Middleware/AdminMiddleware.php :", "Satpam keamanan rute admin: memeriksa Auth::check() dan verifikasi peran isAdmin().")
    pdf.add_bullet("app/Models/ :", "Berisi User.php, Menu.php, Category.php, dan OperatingHour.php sebagai representasi tabel Eloquent.")
    pdf.add_bullet("routes/web.php :", "Sentral seluruh rute web publik, autentikasi, view dashboard admin, serta rute API menu, kategori, dan jam operasional.")
    pdf.add_bullet("resources/views/ :", "Berisi auth/login.blade.php (form login admin), admin/app.blade.php (shell dashboard admin), dan publik/home.blade.php (landing page pelanggan).")
    pdf.add_bullet("public/js/admin.js :", "Otak interaktif dashboard admin yang menjalankan pemanggilan Fetch API ke server secara asynchronous.")
    pdf.add_bullet("storage/app/public/ :", "Folder penyimpanan fisik file foto yang diunggah pengguna (subfolder menus/ dan categories/).")

    # ================= BAGIAN 2: LOGIN =================
    pdf.add_section_h1("Sistem Autentikasi (Login & Logout)", "2")
    
    pdf.add_section_h2("1. Alur Lengkap Login dari Awal sampai Akhir")
    pdf.add_paragraph("Alur kerja proses login admin berjalan melalui 8 tahapan terstruktur:")
    pdf.add_bullet("Tahap 1 (Pintu Rahasia):", "Di halaman depan (home.blade.php), tidak terdapat tombol login. Admin masuk dengan melakukan double-click (desktop) atau double-tap di bawah 350ms (HP) pada logo restoran di header.")
    pdf.add_bullet("Tahap 2 (Request Halaman):", "Browser mengirimkan GET /admin/login. Controller LoginController memeriksa status sesi via middleware 'guest'. Jika belum login, form login.blade.php ditampilkan.")
    pdf.add_bullet("Tahap 3 (Pengisian Kredensial):", "Admin mengisi tiga kolom input: Username, Alamat Email, dan Password.")
    pdf.add_bullet("Tahap 4 (Validasi Frontend):", "JavaScript di login.blade.php mengecek kekosongan input dan memastikan nilai teks Username tidak sama persis dengan Alamat Email.")
    pdf.add_bullet("Tahap 5 (Pengiriman via Fetch):", "Data dikirim via POST /login berformat JSON disertai header X-CSRF-TOKEN untuk keamanan anti pemalsuan request.")
    pdf.add_bullet("Tahap 6 (Validasi Backend):", "AuthController memvalidasi request, mencari username di database, mencocokkan email, memverifikasi hash password via Hash::check(), dan memastikan role adalah 'admin'.")
    pdf.add_bullet("Tahap 7 (Pembuatan Sesi):", "Sistem menjalankan Auth::login($user, true) dan $request->session()->regenerate() guna mencegah serangan Session Fixation.")
    pdf.add_bullet("Tahap 8 (Redirect Dashboard):", "Controller mengembalikan respons JSON berisi URL redirect ke /admin/dashboard. Browser langsung mengarahkan tampilan ke dashboard.")

    pdf.add_section_h2("2. File & Folder Terhubung dengan Login")
    pdf.add_bullet("routes/web.php (baris 20-28):", "Mendaftarkan rute GET /admin/login, POST /login, dan GET/POST /logout.")
    pdf.add_bullet("app/Http/Controllers/Auth/LoginController.php (baris 11-20):", "Method showLoginForm() mengarahkan guest ke view login atau me-redirect user yang sudah aktif.")
    pdf.add_bullet("app/Http/Controllers/AuthController.php (baris 17-111):", "Eksekutor inti verifikasi kredensial, regenerasi ID sesi, dan logout.")
    pdf.add_bullet("app/Http/Middleware/AdminMiddleware.php (baris 19-48):", "Pelindung rute panel admin: memastikan Auth::check() == true dan user->isAdmin() == true.")
    pdf.add_bullet("app/Models/User.php (baris 10-53):", "Model Eloquent tabel users dengan fungsi isAdmin() serta proteksi hidden password.")
    pdf.add_bullet("resources/views/auth/login.blade.php :", "Tampilan form login responsif kartu putih berlatar merah khas restoran dengan fitur toggle show/hide password.")
    pdf.add_bullet("database/migrations/ :", "File migrasi pembentuk skema tabel users beserta kolom username, email, password, role, dan remember_token.")
    pdf.add_bullet("config/auth.php (baris 16-72):", "Konfigurasi autentikasi guard 'web' berbasis driver session dan user provider Eloquent.")

    pdf.add_section_h2("3. Bedah Kode Login (AuthController.php)")
    pdf.add_code_block("app/Http/Controllers/AuthController.php (baris 19-63)", [
        "// 1. Validasi format data request",
        "$request->validate([",
        "    'username' => ['required', 'string'],",
        "    'email'    => ['required', 'string', 'email'],",
        "    'password' => ['required', 'string'],",
        "]);",
        "",
        "// 2. Validasi bisnis: Username dan Email tidak boleh sama persis",
        "if (strcasecmp($usernameInput, $emailInput) === 0) {",
        "    return $this->respondError($request, 'email', 'Username dan Email tidak boleh sama.');",
        "}",
        "",
        "// 3. Cari user berdasarkan username (aman dari SQL Injection)",
        "$user = User::where('username', $usernameInput)->first();",
        "if (!$user) return $this->respondError($request, 'username', 'Username tidak ditemukan.');",
        "",
        "// 4. Verifikasi kecocokan email akun",
        "if (strcasecmp($user->email, $emailInput) !== 0) {",
        "    return $this->respondError($request, 'email', 'Email tidak cocok dengan akun tersebut.');",
        "}",
        "",
        "// 5. Verifikasi password terenkripsi Bcrypt",
        "if (!Hash::check($request->input('password'), $user->password)) {",
        "    return $this->respondError($request, 'password', 'Password salah. Silakan coba lagi.');",
        "}",
        "",
        "// 6. Verifikasi hak akses admin",
        "if (!$user->isAdmin()) {",
        "    return $this->respondError($request, 'role', 'Akses ditolak. Bukan Administrator.', 403);",
        "}",
        "",
        "// 7. Login sukses: Buat sesi baru dan regenerasi Session ID",
        "Auth::login($user, $request->boolean('remember', true));",
        "$request->session()->regenerate();",
        "return response()->json(['success' => true, 'redirect' => route('admin.dashboard')]);"
    ])

    pdf.add_callout(
        "POIN PENTING PENGUJI: MENGAPA TIDAK MEMAKAI Auth::attempt() STANDAR?",
        "Jelaskan bahwa verifikasi manual bertahap sengaja dipilih agar sistem dapat memberikan pesan error yang SANGAT SPESIFIK kepada pengguna: membedakan saat username tidak ada, saat email tidak cocok, atau saat password keliru. Auth::attempt() bawaan hanya mengembalikan nilai boolean umum tanpa rincian letak kesalahan.",
        "exam"
    )

    pdf.add_section_h2("4. Cara Kerja Sisi Localhost & Database")
    pdf.add_paragraph("Saat halaman dibuka di browser lokal (Laragon):")
    pdf.add_bullet("1. Cookie Session :", "Browser mengirim request GET. Laravel membaca cookie laravel_session. Jika belum ada, server membuat file sesi baru dan mengirimkannya via header Set-Cookie.")
    pdf.add_bullet("2. Proteksi CSRF :", "Laravel menyisipkan meta token CSRF ke form HTML. Saat POST /login dikirim, token dicocokkan dengan sesi server untuk mencegah pemalsuan request.")
    pdf.add_bullet("3. Query SQL :", "Controller mengeksekusi query PDO terproteksi: SELECT * FROM users WHERE username = ? LIMIT 1.")
    pdf.add_bullet("4. Remember Me :", "Parameter remember bernilai true menghasilkan token acak di kolom remember_token tabel users serta cookie remember_web_* pada browser.")

    # ================= BAGIAN 3: CRUD UMUM =================
    pdf.add_section_h1("Arsitektur CRUD Secara Umum", "3")
    pdf.add_paragraph("Sistem CRUD pada website Ichiban Sushi dibangun dengan spesifikasi teknis berikut:")
    pdf.add_bullet("Framework & Bahasa :", "Laravel Framework versi 10.10 berjalan di atas runtime PHP ^8.1 (composer.json baris 8-13).")
    pdf.add_bullet("Paket Pendukung :", "laravel/sanctum (^3.3), guzzlehttp/guzzle (^7.2), dan spatie/laravel-ignition (^2.0).")
    pdf.add_bullet("Arsitektur SPA Hybrid :", "Tidak menggunakan full page reload klasik. Satu file view Blade utama (admin/app.blade.php) memuat shell tampilan. Aksi CRUD diproses oleh Vanilla JS (admin.js) via Fetch API asinkron.")
    pdf.add_bullet("Storage & Simbolik Link :", "File foto disimpan di storage/app/public/ dan diakses publik via symlink public/storage yang dibuat melalui perintah php artisan storage:link.")

    pdf.add_section_h2("Siklus Aliran Data CRUD")
    pdf.add_paragraph("1. Request (Route) : Request HTTP (POST/GET/PUT/DELETE) masuk ke routes/web.php dengan proteksi middleware ['auth', 'admin'].")
    pdf.add_paragraph("2. Controller : Menerima data request, mengeksekusi $request->validate(), serta mengelola upload/penghapusan file gambar via Storage::disk('public').")
    pdf.add_paragraph("3. Model Eloquent : Menerapkan proteksi Mass Assignment menggunakan properti $fillable, lalu menjalankan query SQL ke MySQL.")
    pdf.add_paragraph("4. Respons JSON : Controller mengembalikan data berformat JSON (status 200, 201 Created, atau 204 No Content).")
    pdf.add_paragraph("5. Update DOM : JavaScript di browser menangkap JSON, menutup modal form, memperbarui tabel HTML secara instan, dan memunculkan notifikasi Toast.")

    # ================= BAGIAN 4: CRUD PER FITUR =================
    pdf.add_section_h1("Pembahasan CRUD per Fitur", "4")

    # FITUR 1: MENU
    pdf.add_section_h2("A. Fitur Menu Restoran (Makanan & Minuman)")
    pdf.add_paragraph("Fitur menu mendukung pengelolaan penuh daftar kuliner yang tampil di katalog publik pelanggan.")
    pdf.add_bullet("Alur Tambah Data :", "Klik '+ Tambah Menu' -> Modal terbuka -> Isi nama, deskripsi, kategori, harga, pilih foto menu (opsional, maks 2MB), atur switch tampil/sold -> Klik 'Simpan' -> admin.js mengirim FormData POST /api/menus -> Controller memvalidasi dan menyimpan gambar -> Data disimpan ke tabel menus -> Respons JSON 201 dikembalikan.")
    pdf.add_bullet("Alur Edit Data :", "Klik tombol 'Edit' pada baris menu -> Form modal terisi data lama -> Dikirim via FormData POST dengan parameter _method=PUT -> MenuController::update() memvalidasi data -> Jika ada foto baru, foto lama dihapus via Storage::disk('public')->delete() -> Data diupdate -> Respons JSON 200.")
    pdf.add_bullet("Alur Hapus Data :", "Klik tombol 'Hapus' -> Konfirmasi dialog -> Request DELETE /api/menus/{id} -> Controller menghapus file fisik foto dari storage lalu mengeksekusi $menu->delete() -> Respons JSON 204.")

    pdf.add_code_block("app/Http/Controllers/MenuController.php (baris 37-64)", [
        "public function store(Request $request)",
        "{",
        "    $data = $request->validate([",
        "        'nama'      => ['required', 'string', 'max:255'],",
        "        'deskripsi' => ['nullable', 'string'],",
        "        'kategori'  => ['required', 'string', 'max:100'],",
        "        'harga'     => ['required', 'numeric', 'min:0'],",
        "        'aktif'     => ['nullable'],",
        "        'sold'      => ['nullable'],",
        "        'gambar'    => ['nullable', 'image', 'max:2048'], // Maks 2MB",
        "    ]);",
        "",
        "    $menu = new Menu();",
        "    $menu->nama = $data['nama'];",
        "    $menu->deskripsi = $data['deskripsi'] ?? null;",
        "    $menu->kategori = $data['kategori'];",
        "    $menu->harga = (int) $data['harga'];",
        "    $menu->aktif = $request->boolean('aktif');",
        "    $menu->sold = $request->boolean('sold');",
        "",
        "    if ($request->hasFile('gambar')) {",
        "        $menu->gambar = $request->file('gambar')->store('menus', 'public');",
        "    }",
        "    $menu->save();",
        "    return response()->json($menu, 201);",
        "}"
    ])

    pdf.add_paragraph("Lokasi File & Basis Data Menu :", "F2", 9.0)
    pdf.add_bullet("Route :", "routes/web.php (baris 39-44)")
    pdf.add_bullet("Controller :", "app/Http/Controllers/MenuController.php (baris 10-136)")
    pdf.add_bullet("Model :", "app/Models/Menu.php (baris 9-46, memiliki accessor getGambarUrlAttribute)")
    pdf.add_bullet("Tabel Database :", "menus (kolom: id, nama, deskripsi, kategori, harga, aktif, sold, gambar, created_at, updated_at)")
    pdf.add_bullet("Folder Penyimpanan Foto :", "storage/app/public/menus/ (diakses publik via /storage/menus/)")

    # FITUR 2: KATEGORI
    pdf.add_section_h2("B. Fitur Kategori Carousel")
    pdf.add_paragraph("Fitur ini mengatur kategori menu yang muncul pada slider carousel di halaman depan pelanggan.")
    pdf.add_bullet("Alur Tambah Data :", "Klik '+ Tambah Kategori' -> Form modal terbuka -> Isi nama kategori, nomor urutan, dan file gambar -> POST /api/categories -> CategoryController membuat slug unik otomatis via Str::slug() -> Gambar disimpan ke storage/app/public/categories/ -> Data disimpan ke tabel categories.")
    pdf.add_bullet("Fitur Istimewa (Sinkronisasi Relasi) :", "Saat nama kategori diedit (misal dari 'Roll' menjadi 'Fushion Roll'), CategoryController::update() otomatis memperbarui kolom kategori di seluruh menu terkait pada tabel menus via query: Menu::where('kategori', $oldName)->update(['kategori' => $newName]).")
    pdf.add_bullet("Catatan Migrasi :", "File migrasi fisik untuk tabel categories tidak ada di folder database/migrations/ (dibuat langsung di database MySQL / phpMyAdmin), namun data awalnya dikelola oleh seeder database/seeders/CategorySeeder.php.")

    pdf.add_paragraph("Lokasi File & Basis Data Kategori :", "F2", 9.0)
    pdf.add_bullet("Route :", "routes/web.php (baris 46-50)")
    pdf.add_bullet("Controller :", "app/Http/Controllers/Admin/CategoryController.php (baris 12-150)")
    pdf.add_bullet("Model :", "app/Models/Category.php (baris 9-53, memiliki relasi hasMany ke Menu)")
    pdf.add_bullet("Tabel Database :", "categories (kolom: id, slug, name, image, sort_order, created_at, updated_at)")
    pdf.add_bullet("Folder Penyimpanan Gambar :", "storage/app/public/categories/")

    # FITUR 3: JAM OPERASIONAL
    pdf.add_section_h2("C. Fitur Jam Operasional Restoran")
    pdf.add_callout(
        "KARAKTERISTIK KHUSUS: MEKANISME BULK UPDATE",
        "Fitur Jam Operasional TIDAK MEMILIKI tombol tambah baris baru maupun tombol hapus data. Hal ini karena hari dalam seminggu sudah pasti berjumlah tetap 7 hari (Senin s/d Minggu). Fitur ini bekerja dengan mekanisme pembacaan jadwal (Read) dan penyimpanan massal sekaligus (Bulk Update).",
        "exam"
    )
    pdf.add_bullet("Alur Kerja :", "Admin membuka tab 'Jam Operasional' -> admin.js memanggil GET /api/operating-hours -> Tampil form 7 hari -> Admin mengubah jam buka/tutup atau mencentang checkbox 'Tutup hari ini' -> Klik 'Simpan Perubahan Jadwal' -> Request PUT /api/operating-hours dikirim dengan body array berisi data 7 hari -> Controller melakukan updateOrCreate().")

    pdf.add_code_block("app/Http/Controllers/Admin/OperatingHourController.php (baris 39-50)", [
        "// Looping seluruh data 7 hari yang dikirim form",
        "foreach ($request->hours as $item) {",
        "    OperatingHour::updateOrCreate(",
        "        ['day' => $item['day']], // Kunci pencarian berdasarkan nama hari",
        "        [",
        "            'time'       => $item['time'],",
        "            'is_closed'  => isset($item['is_closed']) && $item['is_closed'] == 1,",
        "            'sort_order' => $item['sort_order'] ?? 0,",
        "        ]",
        "    );",
        "}"
    ])

    pdf.add_paragraph("Lokasi File & Basis Data Jam Operasional :", "F2", 9.0)
    pdf.add_bullet("Route :", "routes/web.php (baris 53-54)")
    pdf.add_bullet("Controller :", "app/Http/Controllers/Admin/OperatingHourController.php (baris 9-61)")
    pdf.add_bullet("Model :", "app/Models/OperatingHour.php (baris 8-23)")
    pdf.add_bullet("Migration :", "database/migrations/2024_01_01_000003_create_operating_hours_table.php")
    pdf.add_bullet("Tabel Database :", "operating_hours (kolom: id, day, time, is_closed, sort_order, created_at, updated_at)")

    # ================= BAGIAN 5: TABEL MATRIKS =================
    pdf.add_section_h1("Tabel Matriks Seluruh Fitur", "5")
    pdf.add_paragraph("Berikut ringkasan keterhubungan file setiap fitur dalam project:")
    
    headers = ["Fitur", "File Route", "Controller", "Model", "View (Blade)", "Tabel DB"]
    rows = [
        ["Login Admin", "routes/web.php", "AuthController.php &\nLoginController.php", "User.php", "resources/views/\nauth/login.blade.php", "users"],
        ["Menu Resto", "routes/web.php", "MenuController.php", "Menu.php", "resources/views/\nadmin/app.blade.php", "menus"],
        ["Kategori", "routes/web.php", "Admin/\nCategoryController.php", "Category.php", "resources/views/\nadmin/app.blade.php", "categories"],
        ["Jam Operasional", "routes/web.php", "Admin/\nOperatingHourController.php", "OperatingHour.php", "resources/views/\nadmin/app.blade.php", "operating_hours"],
        ["Katalog Publik", "routes/web.php", "PublicController.php", "Menu, Category,\nOperatingHour", "resources/views/\npublik/home.blade.php", "(Semua tabel)"]
    ]
    col_widths = [75.0, 85.0, 110.0, 75.0, 95.0, 70.0]
    pdf.add_table(headers, rows, col_widths)

    # ================= BAGIAN 6: 10 TANYA JAWAB PENGUJI =================
    pdf.add_section_h1("10 Tanya Jawab Penguji UKK Terpopuler", "6")
    pdf.add_paragraph("Gunakan panduan jawaban lisan berikut saat penguji mengajukan pertanyaan:")

    pdf.add_qa_item(
        1,
        "Apa fungsi token CSRF (@csrf atau X-CSRF-TOKEN) pada formulir aplikasi ini?",
        "CSRF (Cross-Site Request Forgery) adalah token keamanan acak buatan Laravel untuk memastikan bahwa request formulir yang masuk benar-benar berasal dari website aplikasi kita sendiri, bukan dikirim oleh peretas dari situs pihak ketiga yang mencurigakan."
    )
    
    pdf.add_qa_item(
        2,
        "Bagaimana cara aplikasi mengamankan password admin di dalam database?",
        "Password tidak disimpan dalam bentuk teks biasa, melainkan dienkripsi menjadi hash satu arah menggunakan algoritma Bcrypt. Saat login, pencocokan dilakukan dengan fungsi Hash::check(), sehingga jika database bocor, password asli admin tetap aman dan tidak dapat dibaca."
    )

    pdf.add_qa_item(
        3,
        "Mengapa kamu tidak menggunakan Auth::attempt() standar pada AuthController?",
        "Karena sistem kami memiliki 3 kolom input (Username, Email, Password) dan membutuhkan pesan error yang sangat spesifik untuk kemudahan pengguna. Dengan validasi bertahap, sistem dapat membedakan secara rinci apakah yang salah adalah username tidak terdaftar, email tidak cocok dengan akun, atau password yang keliru."
    )

    pdf.add_qa_item(
        4,
        "Bagaimana cara sistem memblokir pengguna yang mencoba membuka halaman admin tanpa login?",
        "Rute /admin/* dan /api/* dilindungi oleh middleware 'auth' dan 'admin' (AdminMiddleware). Middleware akan memeriksa apakah pengunjung telah terdaftar di sesi (Auth::check()) dan apakah memiliki role administrator ($user->isAdmin()). Jika tidak valid, pengunjung langsung dialihkan kembali ke halaman /admin/login."
    )

    pdf.add_qa_item(
        5,
        "Apa fungsi pemanggilan $request->session()->regenerate() setelah login berhasil?",
        "Fungsi ini digunakan untuk mencegah serangan Session Fixation. Begitu login terbukti valid, Laravel akan memperbarui dan mengganti ID sesi yang lama dengan ID sesi yang baru, sehingga penyerang yang mengetahui ID sesi sebelum login tidak bisa membajak hak akses admin."
    )

    pdf.add_qa_item(
        6,
        "Apa kegunaan properti $fillable di dalam file Model Eloquent?",
        "Sebagai proteksi dari celah keamanan Mass Assignment Vulnerability. Properti $fillable bertindak sebagai daftar putih (whitelist) nama-nama kolom tabel yang diizinkan untuk diisi data secara massal saat memanggil fungsi seperti Model::create() atau Model::update()."
    )

    pdf.add_qa_item(
        7,
        "Mengapa rute API admin diletakkan di routes/web.php bukan di routes/api.php?",
        "Karena rute di routes/api.php secara default bersifat stateless dan tidak memuat cookie sesi web. Sementara panel admin kami menggunakan autentikasi sesi browser (web session). Dengan menaruh rute API di routes/web.php, controller dapat membaca sesi login dan token CSRF secara otomatis."
    )

    pdf.add_qa_item(
        8,
        "Apa yang dimaksud dengan method spoofing (_method: PUT) saat mengedit menu?",
        "Standar form HTML aslinya hanya mendukung method GET dan POST. Browser tidak bisa mengirimkan file (multipart/form-data) secara langsung menggunakan method PUT murni. Solusinya adalah mengirim request POST dengan menyisipkan parameter _method = PUT agar router Laravel mengenalinya sebagai proses Update."
    )

    pdf.add_qa_item(
        9,
        "Bagaimana cara kerja pintu rahasia menuju halaman login admin pada website ini?",
        "Pada halaman depan publik tidak ditampilkan tombol login biasa. Di file public/js/script.js, terdapat event listener pada logo restoran: event 'dblclick' untuk pengguna komputer (PC) dan event 'touchend' dengan selang ketukan cepat di bawah 350ms untuk pengguna layar sentuh (HP). Saat diketuk dua kali, pengunjung langsung diarahkan ke /admin/login."
    )

    pdf.add_qa_item(
        10,
        "Mengapa file foto yang diunggah disimpan di storage/app/public bukan langsung di folder public?",
        "Untuk keamanan dan kerapian arsitektur Laravel. Folder storage memisahkan file dinamis yang diunggah pengguna dari kode aset statis aplikasi di folder public. Agar file di storage dapat diakses oleh browser, kita membuat tautan simbolik (symlink) menggunakan perintah 'php artisan storage:link'."
    )

    # Build and write PDF file
    output_path = pdf.build()
    return output_path

if __name__ == "__main__":
    build_ukk_guide()
