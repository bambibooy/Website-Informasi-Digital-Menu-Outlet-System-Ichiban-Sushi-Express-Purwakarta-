<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="base-url" content="{{ url('/') }}">
<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
<title>Ichiban Sushi — Admin</title>
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>
  
<script>
  window.__APP_URL__ = {!! json_encode(url('/')) !!};
  window.__AUTH__ = {!! json_encode([
      'authenticated' => auth()->check(),
      'name' => optional(auth()->user())->username ?? optional(auth()->user())->name ?? 'Admin',
      'username' => optional(auth()->user())->username ?? optional(auth()->user())->name ?? 'Admin',
      'email' => optional(auth()->user())->email ?? '',
      'role' => optional(auth()->user())->role ?? 'Admin',
  ]) !!};
</script>

<!-- ================= APP VIEW ================= -->
<div id="app-view">
  <div class="app-shell">

    <!-- Topbar khusus mobile: tombol hamburger & logo saja (tanpa teks) -->
    <header class="mobile-topbar">
      <button class="hamburger-btn" id="sidebar-toggle" type="button" aria-label="Buka menu navigasi">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
      </button>
      <div class="mobile-topbar-brand">
        <img src="{{ asset('images/logo_putih.png') }}" alt="Logo Ichiban Sushi" class="mobile-brand-logo">
      </div>
    </header>

    <div class="sidebar-overlay" id="sidebar-overlay"></div>

    <aside class="sidebar" id="sidebar">
      <button class="sidebar-close" id="sidebar-close" aria-label="Tutup menu">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></svg>
      </button>
      <div class="avatar-circle">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.5-6 8-6s8 2 8 6"/></svg>
      </div>
      <div class="sidebar-profile">
        <div class="admin-username" id="admin-username-label">{{ optional(auth()->user())->username ?? optional(auth()->user())->name ?? 'Admin' }}</div>
        <span class="role-badge">Admin</span>
        <div class="admin-email" id="admin-email-label">{{ optional(auth()->user())->email ?? '' }}</div>
      </div>
      <nav class="nav">
        <button class="nav-item active" data-view="dashboard">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/></svg>
          Dashboard
        </button>
        <button class="nav-item" data-view="menu">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16v4H4z"/><path d="M4 12h16v8H4z"/></svg>
          Menu
        </button>
        <button class="nav-item" data-view="kategori">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><circle cx="17.5" cy="17.5" r="3.5"/></svg>
          Kategori Carousel
        </button>
        <button class="nav-item" data-view="jam">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3"/></svg>
          Jam Operasional
        </button>
      </nav>
      <div class="sidebar-footer">
        <button class="logout-btn" id="logout-btn">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="M16 17l5-5-5-5"/><path d="M21 12H9"/></svg>
          Keluar
        </button>
      </div>
    </aside>

    <main class="main">
      <div class="search-bar">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
        <input type="text" id="global-search" placeholder="Cari menu...">
      </div>

      <!-- Dashboard -->
      <section id="view-dashboard" class="view-section">
        <p class="page-eyebrow">Selamat Datang Kembali</p>
        <h1 class="page-title">DASHBOARD</h1>
        <div class="stat-row">
          <div class="stat-card">
            <div class="label">Total Menu</div>
            <div class="value" id="stat-total">0</div>
            <div class="sub">Item terdaftar</div>
          </div>
          <div class="stat-card">
            <div class="label">Menu Aktif</div>
            <div class="value" id="stat-active">0</div>
            <div class="sub">Tampil di menu publik</div>
          </div>
          <div class="stat-card">
            <div class="label">Kategori</div>
            <div class="value" id="stat-categories">0</div>
            <div class="sub">Kategori digunakan</div>
          </div>
        </div>
        <div class="panel">
          <h2 style="margin:0 0 2px;font-size:19px;">Sebaran Kategori</h2>
          <p style="margin:0 0 8px;color:#8a867e;font-size:13.5px;">Jumlah menu sesuai kategori</p>
          <div id="cat-breakdown"></div>
        </div>
      </section>

      <!-- Menu -->
      <section id="view-menu" class="view-section" style="display:none;">
        <p class="page-eyebrow">Kelola Konten</p>
        <h1 class="page-title">MENU</h1>
        <div class="panel">
          <div class="panel-head">
            <div>
              <h2>Daftar Menu</h2>
              <p>Tambah, ubah, atau hapus menu, tersimpan otomatis.</p>
            </div>
            <button class="btn-add" id="btn-add-menu">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
              Tambah Menu
            </button>
          </div>
          <div class="pill-row" id="category-pills"></div>
          <div class="table-responsive">
            <table>
              <thead>
                <tr>
                  <th>Menu</th><th>Kategori</th><th>Harga</th><th>Status</th><th>Aksi</th>
                </tr>
              </thead>
              <tbody id="menu-tbody"></tbody>
            </table>
          </div>
        </div>
      </section>

      <!-- Kategori Carousel -->
      <section id="view-kategori" class="view-section" style="display:none;">
        <p class="page-eyebrow">Kelola Carousel</p>
        <h1 class="page-title">KATEGORI CAROUSEL</h1>
        <div class="panel">
          <div class="panel-head">
            <div>
              <h2>Daftar Kategori Carousel</h2>
              <p>Kelola icon dan kategori yang tampil pada carousel menu di halaman utama.</p>
            </div>
            <button class="btn-add" id="btn-add-kategori">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
              Tambah Kategori
            </button>
          </div>
          <div class="table-responsive">
            <table>
              <thead>
                <tr>
                  <th>Foto Icon</th><th>Nama Kategori</th><th>Slug</th><th>Urutan</th><th>Jumlah Menu</th><th>Aksi</th>
                </tr>
              </thead>
              <tbody id="kategori-tbody"></tbody>
            </table>
          </div>
        </div>
      </section>

      <!-- Jam Operasional -->
      <section id="view-jam" class="view-section" style="display:none;">
        <p class="page-eyebrow">Atur Jadwal</p>
        <h1 class="page-title">JAM OPERASIONAL</h1>
        <div class="panel">
          <div class="jam-head">
            <div>
              <h2>Jadwal Mingguan</h2>
              <p>Atur jam buka-tutup tiap hari.</p>
            </div>
            <button class="btn-save" id="btn-save-jadwal">Simpan Jadwal</button>
          </div>
          <div id="jadwal-list"></div>
        </div>
      </section>
    </main>
  </div>
</div>

<!-- ================= MENU MODAL ================= -->
<div class="modal-overlay" id="menu-modal-overlay">
  <div class="modal">
    <div class="modal-head">
      <h3 id="menu-modal-title">Tambah Menu</h3>
      <button class="modal-close" id="menu-modal-close" type="button">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></svg>
      </button>
    </div>
    <form id="menu-form">
      <div class="form-group" id="fg-nama">
        <label>Nama Menu</label>
        <input type="text" id="input-nama" placeholder="Contoh: Tori Teriyaki Ramen">
        <div class="form-error-msg">Nama menu wajib diisi.</div>
      </div>
      <div class="form-group">
        <label>Deskripsi Singkat</label>
        <textarea id="input-deskripsi" rows="2" placeholder="Contoh: Ramen gurih dengan saus"></textarea>
      </div>
      <div class="form-row-2">
        <div class="form-group" id="fg-kategori">
          <label>Kategori</label>
          <select id="input-kategori">
            <option value="Ramen">Ramen</option>
            <option value="Donburi">Donburi</option>
            <option value="Fushion Roll">Fushion Roll</option>
            <option value="Beverages">Beverages</option>
          </select>
        </div>
        <div class="form-group" id="fg-harga">
          <label>Harga (Rp)</label>
          <input type="number" id="input-harga" min="0" placeholder="40700">
          <div class="form-error-msg">Masukkan harga yang valid.</div>
        </div>
      </div>

      <!-- Foto Menu -->
      <div class="form-group" id="fg-foto">
        <label>Foto Menu</label>
        <div class="photo-upload-row">
          <div class="photo-preview" id="foto-preview-wrap">
            <img id="foto-preview" alt="Preview foto menu" style="display:none;">
            <svg id="foto-preview-placeholder" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><rect x="3" y="5" width="18" height="14" rx="2"/><circle cx="9" cy="11" r="2"/><path d="m21 17-5-5-4 4-2-2-5 5"/></svg>
          </div>
          <div class="photo-upload-actions">
            <label for="input-foto" class="btn-outline btn-choose-file">Pilih Foto</label>
            <input type="file" id="input-foto" accept="image/png,image/jpeg,image/webp" hidden>
            <button type="button" class="link-btn" id="foto-remove" style="display:none;">Hapus foto</button>
            <div class="form-hint">JPG, PNG, atau WEBP. Maks 2MB.</div>
          </div>
        </div>
        <div class="form-error-msg" id="foto-error-msg">Ukuran atau format foto tidak valid.</div>
      </div>

      <div class="toggle-row">
        <span>Tampil di menu publik</span>
        <label class="switch">
          <input type="checkbox" id="input-publik" checked>
          <span class="slider"></span>
        </label>
      </div>
      <div class="toggle-row">
        <span>Tandai SOLD (habis)</span>
        <label class="switch">
          <input type="checkbox" id="input-sold">
          <span class="slider"></span>
        </label>
      </div>
      <div class="modal-actions">
        <button type="button" class="btn-outline" id="menu-modal-cancel">Batal</button>
        <button type="submit" class="btn-solid" id="menu-modal-submit">Simpan</button>
      </div>
    </form>
  </div>
</div>

<!-- ================= CATEGORY MODAL ================= -->
<div class="modal-overlay" id="category-modal-overlay">
  <div class="modal">
    <div class="modal-head">
      <h3 id="category-modal-title">Tambah Kategori</h3>
      <button class="modal-close" id="category-modal-close" type="button">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></svg>
      </button>
    </div>
    <form id="category-form">
      <div class="form-group" id="fg-cat-nama">
        <label>Nama Kategori</label>
        <input type="text" id="input-cat-nama" placeholder="Contoh: Ramen, Bento Set, atau Dessert" required>
        <div class="form-error-msg">Nama kategori wajib diisi.</div>
      </div>
      <div class="form-row-2">
        <div class="form-group" id="fg-cat-slug">
          <label>Slug (URL Identifier)</label>
          <input type="text" id="input-cat-slug" placeholder="Otomatis terisi jika kosong">
        </div>
        <div class="form-group" id="fg-cat-sort">
          <label>Urutan Tampil (Sort Order)</label>
          <input type="number" id="input-cat-sort" min="0" placeholder="1">
        </div>
      </div>

      <!-- Foto Icon Kategori (Tampil di Carousel) -->
      <div class="form-group" id="fg-cat-foto">
        <label>Foto Icon Kategori (Carousel)</label>
        <div class="photo-upload-row">
          <div class="photo-preview" id="cat-foto-preview-wrap">
            <img id="cat-foto-preview" alt="Preview foto kategori" style="display:none;">
            <svg id="cat-foto-preview-placeholder" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><rect x="3" y="5" width="18" height="14" rx="2"/><circle cx="9" cy="11" r="2"/><path d="m21 17-5-5-4 4-2-2-5 5"/></svg>
          </div>
          <div class="photo-upload-actions">
            <label for="input-cat-foto" class="btn-outline btn-choose-file">Pilih Foto</label>
            <input type="file" id="input-cat-foto" accept="image/png,image/jpeg,image/webp,image/svg+xml" hidden>
            <button type="button" class="link-btn" id="cat-foto-remove" style="display:none;">Hapus foto</button>
            <div class="form-hint">PNG, JPG, WEBP, atau SVG. Maks 2MB.</div>
          </div>
        </div>
        <div class="form-error-msg" id="cat-foto-error-msg">Ukuran atau format foto tidak valid.</div>
      </div>

      <div class="modal-actions">
        <button type="button" class="btn-outline" id="category-modal-cancel">Batal</button>
        <button type="submit" class="btn-solid" id="category-modal-submit">Simpan</button>
      </div>
    </form>
  </div>
</div>

<!-- ================= MODAL KONFIRMASI LOGOUT ================= -->
<div class="modal-overlay" id="logout-modal-overlay">
  <div class="modal" style="max-width: 400px; text-align: center;">
    <div style="width: 52px; height: 52px; border-radius: 50%; background: #fee2e2; color: #dc2626; display: flex; align-items: center; justify-content: center; margin: 0 auto 14px;">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="26" height="26">
        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="M16 17l5-5-5-5"/><path d="M21 12H9"/>
      </svg>
    </div>
    <h3 style="margin: 0 0 8px; font-size: 19px; font-weight: 700; color: #1e293b;">Konfirmasi Keluar</h3>
    <p style="margin: 0 0 20px; color: #64748b; font-size: 13.5px; line-height: 1.5;">Apakah Anda yakin ingin keluar dari panel admin?</p>
    <div class="modal-actions" style="justify-content: center; gap: 10px;">
      <button type="button" class="btn-outline" id="btn-cancel-logout" style="flex: 1; padding: 10px;">Batal</button>
      <button type="button" class="btn-solid" id="btn-confirm-logout" style="flex: 1; padding: 10px; background: #dc2626; border-color: #dc2626;">Ya, Keluar</button>
    </div>
  </div>
</div>

<div class="toast" id="toast"></div>

<script src="{{ asset('js/admin.js') }}"></script>
</body>
</html>