<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet" />
<title>Ichiban Sushi — Login Admin</title>

<style>
  /* ================= RESET & DASAR ================= */
  *, *::before, *::after {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
  }

  body {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #A8151A 0%, #C41E24 50%, #B8161B 100%);
    padding: 20px 16px;
    font-family: 'Poppins', -apple-system, BlinkMacSystemFont, sans-serif;
    color: #334155;
    overflow-x: hidden;
  }

  /* ================= TATA LETAK CARD ================= */
  .login-card-wrap {
    width: 100%;
    max-width: 480px; /* Lebih lebar secara horizontal sesuai permintaan */
    display: flex;
    justify-content: center;
    margin: auto;
  }

  .login-card {
    width: 100%;
    background: #ffffff;
    border-radius: 18px;
    padding: 34px 36px 30px;
    /* Bayangan tipis & minimalis, tidak menyebar terlalu tebal */
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
  }

  /* ================= LOGO & HEADER ================= */
  .login-brand {
    text-align: center;
    margin-bottom: 20px;
  }

  .login-logo-img {
    height: 42px; /* Diperkecil agar proporsional dan rapi */
    width: auto;
    max-width: 100%;
    margin: 0 auto;
    display: block;
    object-fit: contain;
  }

  .logo-fallback {
    font-size: 16px;
    font-weight: 600;
    color: #C41E24;
    letter-spacing: 2px;
    text-align: center;
  }

  .login-header-text {
    text-align: center;
    margin-bottom: 22px;
  }

  .login-title {
    font-size: 19px;
    font-weight: 700; /* Font sedang/ramping, tidak terlalu tebal */
    color: #1e293b;
    letter-spacing: -0.2px;
    margin-bottom: 4px;
  }

  .login-subtitle {
    font-size: 12.5px;
    font-weight: 400;
    color: #64748b;
  }

  /* ================= NOTIFIKASI ALERT ================= */
  .login-alert {
    padding: 10px 14px;
    border-radius: 10px;
    font-size: 12.5px;
    font-weight: 400;
    margin-bottom: 16px;
    line-height: 1.45;
    text-align: center;
  }

  .login-alert-error {
    background: #fef2f2;
    color: #b91c1c;
    border: 1px solid #fecaca;
  }

  .login-alert-success {
    background: #f0fdf4;
    color: #15803d;
    border: 1px solid #bbf7d0;
  }

  /* ================= FORM INPUT & GROUP ================= */
  .form-group {
    margin-bottom: 16px;
    text-align: left;
  }

  .input-label {
    display: block;
    font-size: 12.5px;
    font-weight: 500; /* Ketebalan sedang/ramping */
    color: #475569;
    margin-bottom: 6px;
  }

  .input-wrapper {
    position: relative;
    width: 100%;
    display: flex;
    align-items: center;
  }

  .input-icon {
    position: absolute;
    left: 13px;
    width: 17px;
    height: 17px;
    color: #94a3b8;
    pointer-events: none;
    transition: color 0.2s;
  }

  .field {
    width: 100%;
    height: 44px;
    padding: 10px 14px 10px 40px; /* Ruang untuk ikon di kiri */
    font-family: inherit;
    font-size: 13.5px;
    font-weight: 400;
    color: #1e293b;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    outline: none;
    transition: all 0.2s ease;
  }

  .field::placeholder {
    color: #94a3b8;
    font-weight: 300;
    font-size: 13px;
  }

  .field:focus {
    background: #ffffff;
    border-color: #C41E24;
    box-shadow: 0 0 0 3px rgba(196, 30, 36, 0.08);
  }

  .field:focus + .input-icon,
  .input-wrapper:focus-within .input-icon {
    color: #C41E24;
  }

  /* Password Toggle */
  .password-wrap .field {
    padding-right: 42px; /* Ruang untuk tombol mata */
  }

  .eye-btn {
    position: absolute;
    right: 10px;
    background: none;
    border: none;
    padding: 6px;
    cursor: pointer;
    color: #94a3b8;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 6px;
    transition: color 0.2s;
  }

  .eye-btn:hover {
    color: #475569;
  }

  /* Error Box di bawah input */
  .login-error {
    color: #dc2626;
    background: #fef2f2;
    border: 1px solid #fecaca;
    border-radius: 8px;
    padding: 9px 12px;
    font-size: 12px;
    font-weight: 400;
    margin-top: 4px;
    margin-bottom: 16px;
    text-align: left;
    display: none;
    line-height: 1.45;
  }

  /* ================= TOMBOL SUBMIT ================= */
  .btn-submit {
    width: 100%;
    height: 44px;
    background: #C41E24; /* Merah khas Ichiban Sushi */
    color: #ffffff;
    border: none;
    border-radius: 10px;
    font-family: inherit;
    font-size: 14px;
    font-weight: 500; /* Font sedang */
    letter-spacing: 0.3px;
    cursor: pointer;
    /* Bayangan tipis minimalis */
    box-shadow: 0 2px 6px rgba(196, 30, 36, 0.25);
    transition: all 0.2s ease;
    margin-top: 6px;
  }

  .btn-submit:hover {
    background: #A8151A;
    box-shadow: 0 3px 8px rgba(196, 30, 36, 0.35);
  }

  .btn-submit:disabled {
    background: #cbd5e1;
    color: #64748b;
    box-shadow: none;
    cursor: not-allowed;
  }

  /* ================= LINK KEMBALI ================= */
  .back-to-home {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    width: 100%;
    margin-top: 20px;
    font-size: 12.5px;
    color: #64748b;
    text-decoration: none;
    font-weight: 400;
    transition: color 0.2s;
  }

  .back-to-home:hover {
    color: #C41E24;
    text-decoration: underline;
  }

  /* ================= RESPONSIVE MOBILE (HP) ================= */
  @media (max-width: 520px) {
    body {
      padding: 14px 12px;
    }

    .login-card-wrap {
      max-width: 100%;
    }

    .login-card {
      padding: 26px 20px 22px;
      border-radius: 16px;
    }

    .login-logo-img {
      height: 36px;
    }

    .login-title {
      font-size: 17.5px;
    }

    .field {
      height: 42px;
      font-size: 13px;
      padding-left: 38px;
    }

    .btn-submit {
      height: 42px;
      font-size: 13.5px;
    }
  }
</style>
</head>
<body>

<div class="login-card-wrap">
  <div class="login-card">

    <!-- 1. LOGO DI BAGIAN ATAS DI DALAM KOTAK PUTIH -->
    <div class="login-brand">
      <img src="{{ asset('images/logo ichiban.png') }}" alt="Logo Ichiban Sushi" class="login-logo-img" onerror="this.style.display='none';document.getElementById('login-logo-fallback').style.display='block';">
      <div class="logo-fallback" id="login-logo-fallback" style="display:none;">ICHIBAN SUSHI</div>
    </div>

    <div class="login-header-text">
      <h1 class="login-title">Login Admin</h1>
      <p class="login-subtitle">Masuk untuk mengelola menu & jam operasional</p>
    </div>

    <!-- Notifikasi Flash Session & Error Validasi -->
    @if(session('error'))
      <div class="login-alert login-alert-error">
        {{ session('error') }}
      </div>
    @endif
    @if(session('success'))
      <div class="login-alert login-alert-success">
        {{ session('success') }}
      </div>
    @endif
    @if($errors->any())
      <div class="login-alert login-alert-error">
        {{ $errors->first() }}
      </div>
    @endif

    <form id="login-form" method="POST" action="{{ url('/login') }}">
      @csrf

      <!-- 2. FIELD 1: USERNAME (Teks Biasa) -->
      <div class="form-group">
        <label class="input-label" for="login-username">Username</label>
        <div class="input-wrapper">
          <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
            <circle cx="12" cy="7" r="4"/>
          </svg>
          <input class="field" id="login-username" name="username" type="text" placeholder="Masukkan username" autocomplete="username" value="{{ old('username') }}" required autofocus>
        </div>
      </div>

      <!-- 2. FIELD 2: EMAIL (Format Email) -->
      <div class="form-group">
        <label class="input-label" for="login-email">Alamat Email</label>
        <div class="input-wrapper">
          <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
            <rect width="20" height="16" x="2" y="4" rx="2"/>
            <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
          </svg>
          <input class="field" id="login-email" name="email" type="email" placeholder="nama@email.com" autocomplete="email" value="{{ old('email') }}" required>
        </div>
      </div>

      <!-- 2. FIELD 3: PASSWORD (Dengan Toggle Show/Hide) -->
      <div class="form-group">
        <label class="input-label" for="login-password">Password</label>
        <div class="input-wrapper password-wrap">
          <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
            <rect width="18" height="11" x="3" y="11" rx="2" ry="2"/>
            <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
          </svg>
          <input class="field" id="login-password" name="password" type="password" placeholder="Masukkan password" autocomplete="current-password" required>
          <button type="button" class="eye-btn" id="toggle-pass" aria-label="Tampilkan password">
            <!-- Ikon Mata Terbuka -->
            <svg id="eye-open-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" width="18" height="18">
              <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7Z"/>
              <circle cx="12" cy="12" r="3"/>
            </svg>
            <!-- Ikon Mata Tertutup (Tersembunyi secara default) -->
            <svg id="eye-closed-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" width="18" height="18" style="display: none;">
              <path d="m2 2 20 20M9.88 9.88a3 3 0 1 0 4.24 4.24M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/>
            </svg>
          </button>
        </div>
      </div>

      <!-- Pesan Error Validasi di Bawah Form -->
      <div class="login-error" id="login-error"></div>

      <button type="submit" class="btn-submit" id="btn-submit-login">Masuk</button>
    </form>

    <a href="{{ route('home') }}" class="back-to-home">
    Kembali ke Beranda Restoran
    </a>
  </div>
</div>

<script>
  // Fitur Toggle Password Show / Hide
  const togglePassBtn = document.getElementById('toggle-pass');
  const passwordInput = document.getElementById('login-password');
  const eyeOpenIcon = document.getElementById('eye-open-icon');
  const eyeClosedIcon = document.getElementById('eye-closed-icon');

  if (togglePassBtn && passwordInput) {
    togglePassBtn.addEventListener('click', () => {
      const isPassword = passwordInput.type === 'password';
      passwordInput.type = isPassword ? 'text' : 'password';
      eyeOpenIcon.style.display = isPassword ? 'none' : 'block';
      eyeClosedIcon.style.display = isPassword ? 'block' : 'none';
      togglePassBtn.setAttribute('aria-label', isPassword ? 'Sembunyikan password' : 'Tampilkan password');
    });
  }

  // Pengiriman Form Login via Fetch
  const loginForm = document.getElementById('login-form');
  const errorEl = document.getElementById('login-error');
  const submitBtn = document.getElementById('btn-submit-login');

  loginForm.addEventListener('submit', async (e) => {
    e.preventDefault();

    const username = document.getElementById('login-username').value.trim();
    const email = document.getElementById('login-email').value.trim();
    const password = passwordInput.value;
    const token = document.querySelector('meta[name="csrf-token"]')?.content;

    if (!username || !email || !password) {
      errorEl.textContent = 'Semua kolom formulir wajib diisi.';
      errorEl.style.display = 'block';
      return;
    }

    // Validasi Frontend: Username dan Alamat Email tidak boleh diisi dengan nilai yang sama
    if (username.toLowerCase() === email.toLowerCase()) {
      errorEl.textContent = 'Username dan Alamat Email tidak boleh diisi dengan nilai yang sama.';
      errorEl.style.display = 'block';
      return;
    }

    errorEl.textContent = '';
    errorEl.style.display = 'none';
    submitBtn.disabled = true;
    submitBtn.textContent = 'Memverifikasi...';

    try {
      const res = await fetch("{{ url('/login') }}", {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-CSRF-TOKEN': token || ''
        },
        body: JSON.stringify({ username, email, password, remember: true })
      });

      // Penanganan Error Sesi/CSRF 419 yang ramah
      if (res.status === 419) {
        errorEl.textContent = 'Sesi Anda telah kedaluwarsa. Silakan muat ulang (refresh) halaman untuk mencoba login kembali.';
        errorEl.style.display = 'block';
        submitBtn.disabled = false;
        submitBtn.textContent = 'Masuk ke Panel';
        return;
      }

      const data = await res.json().catch(() => ({}));

      if (!res.ok) {
        const errorMsg = data.message || (data.errors ? Object.values(data.errors)[0][0] : 'Gagal login. Periksa username dan password Anda.');
        errorEl.textContent = errorMsg;
        errorEl.style.display = 'block';
        submitBtn.disabled = false;
        submitBtn.textContent = 'Masuk ke Panel';
        return;
      }

      // Berhasil: Langsung redirect ke Dashboard
      window.location.href = data.redirect || "{{ route('admin.dashboard') }}";
    } catch (err) {
      errorEl.textContent = 'Gagal menghubungi server. Periksa koneksi internet Anda.';
      errorEl.style.display = 'block';
      submitBtn.disabled = false;
      submitBtn.textContent = 'Masuk ke Panel';
    }
  });
</script>
</body>
</html>
