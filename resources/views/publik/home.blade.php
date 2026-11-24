<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Ichiban Sushi</title>

<!--FONTS-->
<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet" />

<!--CSS-->
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
<noscript>
  <style>
    .reveal-fade-up, .reveal-fade-down, .reveal-slide-left, .reveal-slide-right, .reveal-zoom-in,
    #about-left, #about-right, #about-mascot { opacity: 1 !important; transform: none !important; }
  </style>
</noscript>
</head>

<body>

<!--NAVBAR-->
<header id="site-header" class="site-header">
  <div class="header-inner">
    <a href="#tentang-kami" class="logo nav-link" data-target="#tentang-kami">
      <img src="images/logo ichiban.png" alt="Logo Ichiban Sushi"/>
      <span class="logo-text">
        <span class="logo-text-top"></span>
        <span class="logo-text-main"></span>
      </span>
    </a>

    <!-- NAV DESKTOP -->
    <nav class="nav-desktop">
      <button class="nav-link nav-pill" data-target="#tentang-kami">Tentang Kami</button>
      <button class="nav-link nav-pill" data-target="#menu-kami">Menu Kami</button>
      <button class="nav-link nav-pill" data-target="#jam-operasional">Jam Operasional</button>
    </nav>

    <!-- USER PROFILE / LOGOUT DESKTOP -->
    <div style="display: flex; align-items: center; gap: 0.5rem; justify-self: end;" class="desktop-user-actions">
      @auth
        @if(auth()->user()->isAdmin())
          <a href="{{ route('admin.dashboard') }}" class="admin-icon-btn" title="Panel Admin" aria-label="Panel Admin">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon-sm" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 10-8 0v4h8z" />
            </svg>
          </a>
        @endif

        <form method="POST" action="{{ route('logout') }}" style="display: inline;">
          @csrf
          <button type="submit" class="admin-icon-btn" title="Keluar" aria-label="Keluar">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon-sm" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
            </svg>
          </button>
        </form>
      @endauth
    </div>

    <!-- HAMBURGER MOBILE -->
    <button id="menu-toggle" aria-label="Buka menu" class="hamburger-btn">
      <svg id="icon-burger" xmlns="http://www.w3.org/2000/svg" class="icon-svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
      </svg>
      <svg id="icon-close" xmlns="http://www.w3.org/2000/svg" class="icon-svg hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
      </svg>
    </button>
  </div>

  <!-- NAV MOBILE PANEL -->
  <nav id="mobile-menu" class="mobile-nav">
    <button class="nav-link nav-pill-mobile" data-target="#tentang-kami">Tentang Kami</button>
    <button class="nav-link nav-pill-mobile" data-target="#menu-kami">Menu Kami</button>
    <button class="nav-link nav-pill-mobile" data-target="#jam-operasional">Jam Operasional</button>

    @auth
      <div style="border-top: 1px solid rgba(0,0,0,0.08); margin-top: 0.5rem; padding-top: 0.75rem;">
        <div style="font-size: 0.85rem; font-weight: 700; color: var(--ink); margin-bottom: 0.5rem; padding: 0 0.5rem;">
          {{ auth()->user()->name }} ({{ auth()->user()->role }})
        </div>
        @if(auth()->user()->isAdmin())
          <a href="{{ route('admin.dashboard') }}" class="nav-pill-mobile" style="display: block; background: var(--primary-light); color: var(--primary); margin-bottom: 0.5rem;">
            Buka Panel Admin
          </a>
        @endif
        <form method="POST" action="{{ route('logout') }}" style="display: block;">
          @csrf
          <button type="submit" class="nav-pill-mobile" style="width: 100%; text-align: left; color: var(--primary);">
            Keluar (Logout)
          </button>
        </form>
      </div>
    @endauth
  </nav>
</header>

<div class="header-spacer"></div>
<!--TENTANG KAMI-->
<section id="tentang-kami" class="about-section">
  <div class="container">

    <div class="section-title-wrap reveal-fade-up">
      <h2 id="about-title" class="section-title" data-text="TAHUKAH ANDA?">TAHUKAH ANDA?</h2>
    </div>

    <div class="about-grid">

      <!-- KOLOM KIRI -->
      <div id="about-left" class="about-col about-col-left reveal-slide-left">
        <h3 class="about-heading">
          <span id="about-left-title-1">YUK, KENALAN DENGAN</span>
          <span id="about-left-title-2" class="about-heading-accent">ICHIKUMA!</span>
        </h3>
        <p id="about-left-text" class="about-text">
          Maskot Ichiban Sushi yang digambarkan sebagai <strong>beruang kutub yang ramah dan lucu</strong>. <span class="text-ichikuma">Ichikuma</span>, suka makan makanan Jepang terutama yang terbuat dari salmon dan tuna, seperti salmon roll dan tuna roll. Karakter <span class="text-ichikuma">Ichikuma</span> yang menggemaskan diharapkan dapat membawa kebahagiaan dan <strong>suasana yang hangat dan ceria</strong> di Ichiban Sushi.
        </p>
        <div class="emoji-row">
          <img src="images/Emoji.png" alt="Emoji Smile">
        </div>
      </div>

      <!-- MASKOT TENGAH -->
      <div id="about-mascot" class="mascot-wrap reveal-zoom-in">
        <div class="mascot-circle"></div>
        <img src="images/maskot.png" alt="Maskot Ichikuma Memasak" class="mascot-img mascot-img-1" />
        <img src="images/ichiban_makan.webp" alt="Maskot Ichikuma Makan" class="mascot-img mascot-img-2" />
      </div>

      <!-- KOLOM KANAN -->
      <div id="about-right" class="about-col about-col-right reveal-slide-right">
        <h3 class="about-heading">
          <span id="about-right-title" class="about-accent-inline">ICHIBAN (一番)</span>
        </h3>
        <p id="about-right-text" class="about-text">
          Dalam bahasa Jepang artinya &ldquo;<strong>Nomor Satu</strong>&rdquo;. Makna inilah yang senantiasa menjadi inspirasi kami untuk menjadi jaringan restoran sushi terbaik dan terbesar di Indonesia.
        </p>
        <div class="emoji-row">
          <img src="images/Emoji_mie.png" alt="Emoji Mie">
        </div>
      </div>
    </div>
  </div>
</section>

<!--MENU KAMI-->
<section id="menu-kami" class="menu-section">
  <div class="container">

    <!-- KATEGORI CAROUSEL -->
    <div class="menu-carousel reveal-fade-up">
      <button id="cat-prev" aria-label="Kategori sebelumnya" class="arrow-btn">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon-sm" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
      </button>

      <div id="category-track" class="category-track">
        <!-- category cards akan dirender otomatis oleh script.js dari MENU_DATA -->
      </div>

      <button id="cat-next" aria-label="Kategori berikutnya" class="arrow-btn">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon-sm" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
      </button>
    </div>

    <!-- LABEL KATEGORI AKTIF -->
    <div class="category-label-wrap reveal-zoom-in">
      <span id="category-title" class="category-badge"></span>
    </div>

    <!-- GRID MENU -->
    <div id="menu-grid" class="menu-grid">
      <!-- item menu dirender otomatis oleh script.js -->
    </div>
  </div>
</section>

<!-- MODAL DETAIL MENU -->
<div id="menu-modal" class="modal hidden">
  <div id="menu-modal-backdrop" class="modal-backdrop"></div>
  <div class="modal-box modal-pop">
    <button id="menu-modal-close" aria-label="Tutup" class="modal-close">
      <svg xmlns="http://www.w3.org/2000/svg" class="icon-xs" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
    </button>
    <img id="menu-modal-img" src="" alt="" class="modal-img" />
    <span id="menu-modal-sold-badge" class="modal-sold-badge" style="display:none;">SOLD</span>
    <h3 id="menu-modal-name" class="modal-name"></h3>
    <p id="menu-modal-price" class="modal-price"></p>
    <p id="menu-modal-desc" class="modal-desc"></p>
  </div>
</div>

<!--JAM OPERASIONAL -->
<section id="jam-operasional" class="hours-section">
  <div class="hours-card reveal-fade-up">

    <!-- JAM BUKA -->
    <div class="hours-col reveal-slide-left">
      <h3 class="hours-title">JAM BUKA</h3>
      <ul id="hours-list" class="hours-list">
        <!-- daftar hari dirender otomatis oleh script.js dari HOURS_DATA -->
      </ul>
    </div>

    <!-- MAP -->
<div class="map-col reveal-slide-right">
  <iframe 
    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d990.9638781492061!2d107.4429118!3d-6.5504654!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e690f006f2b9ab5%3A0x428cc862f74fb79f!2sIchiban%20Sushi%20Express!5e0!3m2!1sid!2sid!4v1724280000000!5m2!1sid!2sid" 
    allowfullscreen="" 
    loading="lazy" 
    referrerpolicy="no-referrer-when-downgrade">
  </iframe>
  <a href="https://maps.google.com/?q=Ichiban+Sushi+Express+Yogya+Purwakarta" target="_blank" class="map-link">Lihat di Map</a>
</div>
  </div>
</section>

<!--FOOTER-->
<footer class="site-footer">
  <div class="footer-grid">

    <!-- KONTAK -->
    <div class="footer-col footer-col-left reveal-slide-left">
      <span class="footer-halal-badge">
        <img src="images/logo_halal.png" alt="Logo Halal" class="footer-logo-sm" />
      </span>
      <a href="mailto:marketing@eatwell.co.id" class="footer-email">marketing@eatwell.co.id</a>
      <p class="footer-address">
        Yogya Purwakarta (Ichiban Sushi Express)<br />
        Lantai GF, Jl. Jendral Sudirman No.5, Nagri Tengah, Kec. Purwakarta, Kabupaten Purwakarta, Jawa Barat 41114
      </p>
    </div>

    <!-- LOGO TENGAH -->
    <div class="footer-col footer-col-center reveal-fade-up">
      <img src="images/logo_putih.png" alt="Logo Ichiban Sushi" class="footer-logo-big" />
      <p class="footer-copyright footer-copyright-desktop">
        Copyrights &copy; 2026 Ichiban Sushi (PT Panca Boga Paramita), All Rights Reserved
      </p>
    </div>

    <!-- KONTAK KANAN -->
    <div class="footer-col footer-col-right reveal-slide-right">
      <a href="tel:+628118112680" class="footer-phone">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon-xs" fill="currentColor" viewBox="0 0 24 24"><path d="M6.6 10.8c1.4 2.8 3.8 5.1 6.6 6.6l2.2-2.2c.3-.3.7-.4 1-.2 1.1.4 2.3.6 3.6.6.6 0 1 .4 1 1v3.6c0 .6-.4 1-1 1C10.5 21.2 2.8 13.5 2.8 4c0-.6.4-1 1-1H7.4c.6 0 1 .4 1 1 0 1.2.2 2.4.6 3.5.1.4 0 .8-.3 1.1L6.6 10.8z"/></svg>
        (+62) 811 8112 6801
      </a>
      <span class="footer-tagline">Hubungi Kami</span>
      <div class="footer-social">
        <a href="https://www.instagram.com/ichibansushi_id" target="_blank" rel="noopener noreferrer" aria-label="Instagram" class="social-icon">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon-xs" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1"/></svg>
        </a>
        <a href="https://www.tiktok.com/@ichibansushi_id" target="_blank" rel="noopener noreferrer" aria-label="TikTok" class="social-icon">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon-xs" fill="currentColor" viewBox="0 0 24 24"><path d="M14 3c.4 2 1.9 3.6 4 3.9v2.6c-1.4 0-2.8-.4-4-1.2v6c0 3-2.4 5.3-5.3 5.3S3.4 17.3 3.4 14.4 5.8 9.1 8.7 9.1c.3 0 .6 0 .9.1v2.7c-.3-.1-.6-.2-.9-.2-1.5 0-2.7 1.2-2.7 2.7s1.2 2.7 2.7 2.7 2.7-1.2 2.7-2.7V3H14z"/></svg>
        </a>
      </div>
      <div style="display: flex; gap: 0.75rem; align-items: center; justify-content: center; flex-wrap: wrap;">
        <a href="#" class="footer-privacy" id="openPrivacyModal">Privacy Policy</a>
        <span style="color: rgba(255, 255, 255, 0.4); font-size: 0.7rem;">&bull;</span>
        <a href="#" class="footer-privacy" id="openUserGuideModal">Petunjuk Pengguna</a>
      </div>
    </div>
  </div>

  <p class="footer-copyright footer-copyright-mobile reveal-fade-up">
    Copyrights &copy; 2026 Ichiban Sushi, All Rights Reserved
  </p>
</footer>

<!-- MODAL PETUNJUK PENGGUNA -->
<div id="guide-modal" class="modal hidden">
  <div id="guide-modal-backdrop" class="modal-backdrop"></div>
  <div class="modal-box modal-guide-box modal-pop">
    <div class="modal-guide-header">
      <div class="modal-guide-title">
        <svg xmlns="http://www.w3.org/2000/svg" class="guide-title-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <circle cx="12" cy="12" r="10"/>
          <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/>
          <line x1="12" y1="17" x2="12.01" y2="17"/>
        </svg>
        <h3>Petunjuk Penggunaan Website</h3>
      </div>
      <button id="guide-modal-close" aria-label="Tutup" class="modal-close">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon-xs" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
          <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
    </div>

    <div class="modal-guide-body">
      <p class="guide-intro">Selamat datang di website resmi <strong>Ichiban Sushi Express Yogya Purwakarta</strong>. Berikut adalah petunjuk penggunaan fitur-fitur website untuk kenyamanan Anda:</p>

      <div class="guide-step">
        <div class="guide-step-icon">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
        </div>
        <div class="guide-step-content">
          <h4>1. Navigasi Cepat</h4>
          <p>Gunakan navigasi di bagian atas halaman untuk langsung menuju bagian <strong>Beranda</strong>, <strong>Tentang Kami</strong>, <strong>Menu Kami</strong>, atau <strong>Jam Operasional</strong>.</p>
        </div>
      </div>

      <div class="guide-step">
        <div class="guide-step-icon">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
        </div>
        <div class="guide-step-content">
          <h4>2. Memilih Kategori Menu</h4>
          <p>Pada bagian <strong>Menu Kami</strong>, klik tombol kategori seperti <em>Ramen</em>, <em>Donburi</em>, <em>Fushion Roll</em>, atau <em>Beverages</em> untuk menyaring menu yang ingin Anda lihat.</p>
        </div>
      </div>

      <div class="guide-step">
        <div class="guide-step-icon">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        </div>
        <div class="guide-step-content">
          <h4>3. Detail Menu & Ketersediaan</h4>
          <p>Klik pada salah satu item menu untuk membuka rincian harga, deskripsi, dan gambarnya. Menu yang habis akan ditandai dengan label <strong>SOLD OUT</strong>.</p>
        </div>
      </div>

      <div class="guide-step">
        <div class="guide-step-icon">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
        </div>
        <div class="guide-step-content">
          <h4>4. Jam Buka & Lokasi Outlet</h4>
          <p>Jadwal jam operasional harian diperbarui otomatis. Anda dapat menekan tombol <strong>Lihat di Map</strong> untuk membuka penunjuk rute Google Maps ke lokasi restoran.</p>
        </div>
      </div>

      <div class="guide-step">
        <div class="guide-step-icon">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
        </div>
        <div class="guide-step-content">
          <h4>5. Informasi Kontak & Sosial Media</h4>
          <p>Jika memerlukan bantuan, hubungi email/telepon di footer atau ikuti akun Instagram & TikTok resmi Ichiban Sushi.</p>
        </div>
      </div>
    </div>

    <div class="modal-guide-footer">
      <button id="guide-modal-btn" class="guide-close-btn">Saya Mengerti</button>
    </div>
  </div>
</div>

<!-- MODAL PRIVACY POLICY -->
<div id="privacy-modal" class="modal hidden">
  <div id="privacy-modal-backdrop" class="modal-backdrop"></div>
  <div class="modal-box modal-guide-box modal-pop">
    <div class="modal-guide-header">
      <div class="modal-guide-title">
        <svg xmlns="http://www.w3.org/2000/svg" class="guide-title-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
        </svg>
        <h3>Kebijakan Privasi (Privacy Policy)</h3>
      </div>
      <button id="privacy-modal-close" aria-label="Tutup" class="modal-close">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon-xs" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
          <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
    </div>

    <div class="modal-guide-body">
      <p class="guide-intro">Di <strong>Ichiban Sushi Express Purwakarta</strong>, kami berkomitmen untuk melindungi kenyamanan dan privasi setiap pengunjung website kami.</p>

      <div class="guide-step">
        <div class="guide-step-icon">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
        </div>
        <div class="guide-step-content">
          <h4>Penggunaan Informasi Publik</h4>
          <p>Website ini disediakan khusus untuk menyampaikan informasi katalog menu, harga, jam operasional, dan lokasi outlet kepada publik tanpa mengumpulkan data pribadi sensitif.</p>
        </div>
      </div>

      <div class="guide-step">
        <div class="guide-step-icon">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
        </div>
        <div class="guide-step-content">
          <h4>Keamanan Data Katalog</h4>
          <p>Data menu dan operasional bersumber langsung dari sistem manajemen terenkripsi untuk memastikan informasi yang disajikan selalu akurat dan terpercaya.</p>
        </div>
      </div>
    </div>

    <div class="modal-guide-footer">
      <button id="privacy-modal-btn" class="guide-close-btn">Tutup</button>
    </div>
  </div>
</div>

<!--DYNAMIC BACKEND DATA-->
<script>
  window.MENU_DATA = @json($menuData ?? []);
  window.CATEGORY_ORDER = @json($categoryOrder ?? []);
  window.HOURS_DATA = @json($hoursData ?? []);
</script>

<!--SCRIPT-->
<script src="{{ asset('js/script.js') }}" defer></script>
</body>
</html>