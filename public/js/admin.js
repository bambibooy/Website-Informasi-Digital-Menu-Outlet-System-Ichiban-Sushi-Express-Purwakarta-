(function(){
  "use strict";

  /* ==========================================================
     STATE
     ========================================================== */
  const DEFAULT_CATEGORIES = ["Ramen","Donburi","Fushion Roll","Beverages"];
  const CATEGORY_ICON = {"Ramen":"🍜","Donburi":"🍚","Fushion Roll":"🍣","Beverages":"🥤"};
  const DAYS = ["Senin","Selasa","Rabu","Kamis","Jumat","Sabtu","Minggu"];

  let categories = [];
  let menuItems = [];
  let schedule = {};
  let activeCategory = "Semua";
  let searchTerm = "";
  let editingId = null;
  let selectedFotoFile = null;   
  let removeFotoFlag = false;   

  let editingCatId = null;
  let selectedCatFotoFile = null;
  let removeCatFotoFlag = false;

  function getCategoryNames(){
    if(Array.isArray(categories) && categories.length > 0){
      return categories.map(c => c.name);
    }
    return DEFAULT_CATEGORIES;
  }   

  const defaultSchedule = () => {
    const s = {};
    DAYS.forEach(d => { s[d] = {open:"11:00", close:"21:00", closed:false}; });
    return s;
  };

  /* ==========================================================
     API (Laravel backend)
     ========================================================== */
  function getCsrfToken() {
    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
    return csrfMeta ? csrfMeta.content : null;
  }

  function getBaseUrl() {
    const meta = document.querySelector('meta[name="base-url"]');
    if (meta && meta.content) return meta.content.replace(/\/+$/, '');
    if (typeof window !== 'undefined' && window.__APP_URL__) {
      return String(window.__APP_URL__).replace(/\/+$/, '');
    }
    return '';
  }

  function getApiUrl(path) {
    const base = getBaseUrl();
    const cleanPath = path.startsWith('/') ? path : '/' + path;
    return base ? `${base}${cleanPath}` : cleanPath;
  }

  function formatNetworkErrorMessage(err) {
    if (!err) return 'Gagal terhubung ke server.';
    const msg = String(err.message || err);
    if (err.name === 'TypeError' || /failed to fetch|networkerror|load failed|network request failed/i.test(msg)) {
      return 'Gagal terhubung ke server. Pastikan server web (Laragon/PHP) aktif dan koneksi internet stabil.';
    }
    return msg;
  }

  async function apiFetch(url, options = {}) {
    const targetUrl = getApiUrl(url);
    const token = getCsrfToken();
    let res;

    try {
      res = await fetch(targetUrl, {
        ...options,
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest',
          ...(token ? { 'X-CSRF-TOKEN': token } : {}),
          ...(options.headers || {}),
        },
      });
    } catch (networkErr) {
      console.error('API Fetch Network Error:', networkErr);
      const customErr = new Error(formatNetworkErrorMessage(networkErr));
      customErr.originalError = networkErr;
      throw customErr;
    }

    return handleApiResponse(res);
  }

  async function apiFetchForm(url, formData) {
    const targetUrl = getApiUrl(url);
    const token = getCsrfToken();
    let res;

    try {
      res = await fetch(targetUrl, {
        method: 'POST', 
        body: formData,
        headers: {
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest',
          ...(token ? { 'X-CSRF-TOKEN': token } : {}),
        },
      });
    } catch (networkErr) {
      console.error('API FetchForm Network Error:', networkErr);
      const customErr = new Error(formatNetworkErrorMessage(networkErr));
      customErr.originalError = networkErr;
      throw customErr;
    }

    return handleApiResponse(res);
  }

  async function handleApiResponse(res) {
    if (!res.ok) {
      let message = 'Request gagal (' + res.status + ')';
      let errors = null;

      if (res.status === 419) {
        message = 'Sesi keamanan (CSRF Token) telah kedaluwarsa. Silakan refresh halaman browser.';
      } else if (res.status === 401) {
        message = 'Sesi login telah berakhir. Silakan login kembali.';
      } else if (res.status === 403) {
        message = 'Akses ditolak. Anda tidak memiliki hak akses.';
      } else if (res.status === 404) {
        message = 'Endpoint atau data tidak ditemukan (404).';
      } else if (res.status === 413) {
        message = 'Ukuran file foto melebihi batas upload server (Maks 2MB).';
      } else if (res.status === 422) {
        message = 'Data yang dimasukkan tidak valid.';
      } else if (res.status >= 500) {
        message = 'Terjadi kesalahan internal pada server (' + res.status + ').';
      }

      try {
        const contentType = res.headers.get('content-type') || '';
        if (contentType.includes('application/json')) {
          const errBody = await res.json();
          if (errBody.message) message = errBody.message;
          if (errBody.errors) {
            errors = errBody.errors;
            const firstKey = Object.keys(errors)[0];
            if (firstKey && errors[firstKey] && errors[firstKey][0]) {
              message = errors[firstKey][0];
            }
          }
        }
      } catch (e) { /* ignore json parse error */ }

      const err = new Error(message);
      err.status = res.status;
      err.errors = errors;
      throw err;
    }
    if (res.status === 204) return null;
    return res.json();
  }

  async function fetchMenus() {
    return apiFetch('/api/menus');
  }
  async function createMenuOnServer(formData) {
    return apiFetchForm('/api/menus', formData);
  }
  async function updateMenuOnServer(id, formData) {
    formData.append('_method', 'PUT');
    return apiFetchForm(`/api/menus/${id}`, formData);
  }
  async function toggleMenuOnServer(id, aktif) {
    return apiFetch(`/api/menus/${id}/toggle`, { method: 'PATCH', body: JSON.stringify({ aktif }) });
  }
  async function deleteMenuOnServer(id) {
    return apiFetch(`/api/menus/${id}`, { method: 'DELETE' });
  }

  // Kategori Carousel — ke backend Laravel (tabel categories)
  async function fetchCategories() {
    return apiFetch('/api/categories');
  }
  async function createCategoryOnServer(formData) {
    return apiFetchForm('/api/categories', formData);
  }
  async function updateCategoryOnServer(id, formData) {
    formData.append('_method', 'PUT');
    return apiFetchForm(`/api/categories/${id}`, formData);
  }
  async function deleteCategoryOnServer(id) {
    return apiFetch(`/api/categories/${id}`, { method: 'DELETE' });
  }

  // Jam Operasional — ke backend Laravel (tabel operating_hours),
  async function fetchOperatingHours() {
    return apiFetch('/api/operating-hours');
  }
  async function saveOperatingHoursOnServer(hours) {
    return apiFetch('/api/operating-hours', { method: 'PUT', body: JSON.stringify({ hours }) });
  }

  // Auth: ke backend Laravel (Auth::attempt + remember token),
  async function loginRequest(payload) {
    return apiFetch('/login', { method: 'POST', body: JSON.stringify(payload) });
  }
  async function logoutRequest() {
    return apiFetch('/logout', { method: 'POST' });
  }

  /* ==========================================================
     JAM OPERASIONAL
     ========================================================== */
  function parseTimeRange(timeStr){
    if(typeof timeStr === 'string'){
      const m = timeStr.split(/\s*-\s*/);
      if(m.length === 2 && m[0] && m[1]) return { open: m[0], close: m[1] };
    }
    return { open: "11:00", close: "21:00" };
  }
  function formatTimeRange(open, close){
    return `${open} - ${close}`;
  }

  function updateCategorySelectOptions(){
    const select = document.getElementById('input-kategori');
    if(!select) return;
    const currentVal = select.value;
    const names = getCategoryNames();
    select.innerHTML = names.map(n => `<option value="${escapeHtml(n)}">${escapeHtml(n)}</option>`).join('');
    if(names.includes(currentVal)){
      select.value = currentVal;
    } else if(names.length > 0){
      select.value = names[0];
    }
  }

  async function loadAppData(){
    try{
      categories = await fetchCategories();
    }catch(e){
      console.error('Gagal memuat kategori dari server:', e);
      categories = [];
    }
    updateCategorySelectOptions();

    try{
      menuItems = await fetchMenus();
    }catch(e){
      console.error('Gagal memuat menu dari server:', e);
      showToast('Gagal memuat menu: ' + (e.message || 'Koneksi bermasalah'), 3500);
      menuItems = [];
    }

    try{
      const rows = await fetchOperatingHours();
      const s = {};
      (rows || []).forEach(row => {
        const { open, close } = parseTimeRange(row.time);
        s[row.day] = { id: row.id, open, close, closed: !!row.is_closed };
      });
      DAYS.forEach(d => { if(!s[d]) s[d] = { id: null, open:"11:00", close:"21:00", closed:false }; });
      schedule = s;
    }catch(e){
      console.error('Gagal memuat jam operasional dari server:', e);
      showToast('Gagal memuat jam operasional: ' + (e.message || 'Koneksi bermasalah'), 3500);
      schedule = defaultSchedule();
    }
  }

  async function saveSchedule(){
    const hours = DAYS.map((day, idx) => {
      const d = schedule[day] || { open:"11:00", close:"21:00", closed:false };
      return {
        id: d.id || null,
        day,
        sort_order: idx,
        time: formatTimeRange(d.open, d.close),
        is_closed: d.closed ? 1 : 0,
      };
    });

    const result = await saveOperatingHoursOnServer(hours);

    // simpan id yang baru dibuat server (kalau sebelumnya baris hari itu belum ada)
    // supaya penyimpanan berikutnya tidak perlu bikin baris baru lagi.
    if (result && Array.isArray(result.data)) {
      result.data.forEach(row => {
        if (schedule[row.day]) schedule[row.day].id = row.id;
      });
    }
  }

  /* ==========================================================
     HELPERS
     ========================================================== */
  function formatRupiah(n){
    return "Rp " + Number(n).toLocaleString('id-ID');
  }
  function showToast(msg, duration = 2500){
    const t = document.getElementById('toast');
    if (!t) return;
    t.textContent = msg;
    t.classList.add('show');
    clearTimeout(showToast._t);
    showToast._t = setTimeout(() => t.classList.remove('show'), duration);
  }
  function escapeHtml(str){
    return String(str).replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
  }

  /* ==========================================================
     AUTH VIEWS (Login / App)
     ========================================================== */
  const loginView = document.getElementById('login-view');
  const appView = document.getElementById('app-view');

  function showAuthView(name){
    if(loginView) loginView.style.display = name === 'login' ? 'flex' : 'none';
    if(appView) appView.style.display = name === 'app' ? 'block' : 'none';
  }

  const togglePassBtn = document.getElementById('toggle-pass');
  if(togglePassBtn){
    togglePassBtn.addEventListener('click', () => {
      const pw = document.getElementById('login-password');
      pw.type = pw.type === 'password' ? 'text' : 'password';
    });
  }

  /* ---------------- LOGIN (JIKA DI DALAM APP) ---------------- */
  const adminLoginForm = document.getElementById('login-form');
  if(adminLoginForm){
    adminLoginForm.addEventListener('submit', async (e) => {
      e.preventDefault();
      const username = document.getElementById('login-username').value.trim();
      const email = document.getElementById('login-email').value.trim().toLowerCase();
      const password = document.getElementById('login-password').value;
      const remember = true;
      const errorEl = document.getElementById('login-error');

      if(!username || !email || !password){
        errorEl.textContent = "Semua kolom wajib diisi.";
        errorEl.style.display = 'block';
        return;
      }

      errorEl.textContent = "";
      errorEl.style.display = 'none';
      const submitBtn = e.target.querySelector('button[type="submit"]');
      submitBtn.disabled = true;

      try{
        const result = await loginRequest({ username, email, password, remember });
        window.location.href = result.redirect || getApiUrl('/admin/dashboard');
      }catch(err){
        errorEl.textContent = err.message || "Username, email, atau password salah.";
        errorEl.style.display = 'block';
      }finally{
        submitBtn.disabled = false;
      }
    });
  }

  /* ---------------- LOGOUT 2 LANGKAH (DENGAN KONFIRMASI) ---------------- */
  const logoutModal = document.getElementById('logout-modal-overlay');
  const logoutBtn = document.getElementById('logout-btn');
  const cancelLogoutBtn = document.getElementById('btn-cancel-logout');
  const confirmLogoutBtn = document.getElementById('btn-confirm-logout');

  if(logoutBtn && logoutModal){
    logoutBtn.addEventListener('click', (e) => {
      e.preventDefault();
      logoutModal.classList.add('show');
    });
  }

  if(cancelLogoutBtn && logoutModal){
    cancelLogoutBtn.addEventListener('click', () => {
      logoutModal.classList.remove('show');
    });
  }

  if(logoutModal){
    logoutModal.addEventListener('click', (e) => {
      if(e.target === logoutModal){
        logoutModal.classList.remove('show');
      }
    });
  }

  if(confirmLogoutBtn){
    confirmLogoutBtn.addEventListener('click', async () => {
      confirmLogoutBtn.disabled = true;
      confirmLogoutBtn.textContent = 'Memproses...';
      try{
        await logoutRequest();
      }catch(err){
        console.error('Logout error:', err);
      }
      window.location.href = getApiUrl('/admin/login');
    });
  }

  /* ==========================================================
     APP NAVIGATION
     ========================================================== */
  const navButtons = document.querySelectorAll('.nav-item');
  const views = {
    dashboard: document.getElementById('view-dashboard'),
    menu: document.getElementById('view-menu'),
    kategori: document.getElementById('view-kategori'),
    jam: document.getElementById('view-jam'),
  };
  function switchView(name){
    Object.keys(views).forEach(k => {
      if(views[k]) views[k].style.display = (k===name ? '' : 'none');
    });
    navButtons.forEach(b => b.classList.toggle('active', b.dataset.view===name));
    if(name==='dashboard') renderDashboard();
    if(name==='menu') renderMenuList();
    if(name==='kategori') renderCategoryList();
    if(name==='jam') renderSchedule();
    closeSidebar();
  }
  navButtons.forEach(btn => btn.addEventListener('click', () => switchView(btn.dataset.view)));

  document.getElementById('global-search').addEventListener('input', (e) => {
    searchTerm = e.target.value.toLowerCase();
    if(views.menu && views.menu.style.display !== 'none') renderMenuList();
    if(views.kategori && views.kategori.style.display !== 'none') renderCategoryList();
  });

  /* ==========================================================
     SIDEBAR MOBILE (buka/tutup off-canvas)
     ========================================================== */
  const sidebarEl = document.getElementById('sidebar');
  const sidebarOverlay = document.getElementById('sidebar-overlay');
  const sidebarToggleBtn = document.getElementById('sidebar-toggle');
  const sidebarCloseBtn = document.getElementById('sidebar-close');

  function openSidebar(){
    if(sidebarEl) sidebarEl.classList.add('open');
    if(sidebarOverlay) sidebarOverlay.classList.add('show');
    document.body.style.overflow = 'hidden';
  }
  function closeSidebar(){
    if(sidebarEl) sidebarEl.classList.remove('open');
    if(sidebarOverlay) sidebarOverlay.classList.remove('show');
    document.body.style.overflow = '';
  }
  if(sidebarToggleBtn) sidebarToggleBtn.addEventListener('click', openSidebar);
  if(sidebarCloseBtn) sidebarCloseBtn.addEventListener('click', closeSidebar);
  if(sidebarOverlay) sidebarOverlay.addEventListener('click', closeSidebar);

  /* ==========================================================
     DASHBOARD
     ========================================================== */
  function renderDashboard(){
    const total = menuItems.length;
    const active = menuItems.filter(m => m.aktif).length;
    const catNames = getCategoryNames();

    document.getElementById('stat-total').textContent = total;
    document.getElementById('stat-active').textContent = active;
    document.getElementById('stat-categories').textContent = catNames.length;

    const maxCount = Math.max(1, ...catNames.map(c => menuItems.filter(m=>m.kategori===c).length));
    const wrap = document.getElementById('cat-breakdown');
    wrap.innerHTML = catNames.map(cat => {
      const count = menuItems.filter(m => m.kategori === cat).length;
      const pct = Math.round((count / maxCount) * 100);
      return `
        <div class="cat-row">
          <div class="cat-name">${escapeHtml(cat)}</div>
          <div class="cat-bar-track"><div class="cat-bar-fill" style="width:${pct}%"></div></div>
          <div class="cat-count">${count}</div>
        </div>`;
    }).join('');
  }

  /* ==========================================================
     MENU LIST
     ========================================================== */
  function renderCategoryPills(){
    const wrap = document.getElementById('category-pills');
    if(!wrap) return;
    const all = ["Semua", ...getCategoryNames()];
    wrap.innerHTML = all.map(cat => `<button type="button" class="pill ${cat===activeCategory?'active':''}" data-cat="${escapeHtml(cat)}">${escapeHtml(cat)}</button>`).join('');
    wrap.querySelectorAll('.pill').forEach(p => {
      p.addEventListener('click', () => {
        activeCategory = p.dataset.cat;
        renderMenuList();
      });
    });
  }

  function renderMenuList(){
    renderCategoryPills();
    const tbody = document.getElementById('menu-tbody');
    let items = menuItems;
    if(activeCategory !== "Semua") items = items.filter(m => m.kategori === activeCategory);
    if(searchTerm) items = items.filter(m => m.nama.toLowerCase().includes(searchTerm));

    if(items.length === 0){
      tbody.innerHTML = `<tr class="empty-row"><td colspan="5">Tidak ada menu yang cocok. Coba ubah pencarian atau kategori, atau tambahkan menu baru.</td></tr>`;
      return;
    }

    tbody.innerHTML = items.map(item => `
      <tr data-id="${item.id}">
        <td>
          <div class="menu-cell">
            <div class="menu-icon">${item.gambar_url ? `<img src="${escapeHtml(item.gambar_url)}" alt="${escapeHtml(item.nama)}">` : (CATEGORY_ICON[item.kategori] || '🍽️')}</div>
            <div>
              <div class="menu-name">${escapeHtml(item.nama)} ${item.sold ? '<span class="sold-badge">SOLD</span>' : ''}</div>
              <div class="menu-desc">${escapeHtml(item.deskripsi || '')}</div>
            </div>
          </div>
        </td>
        <td><span class="kategori-pill">${escapeHtml(item.kategori)}</span></td>
        <td class="price">${formatRupiah(item.harga)}</td>
        <td>
          <label class="switch">
            <input type="checkbox" class="status-toggle" ${item.aktif ? 'checked' : ''}>
            <span class="slider"></span>
          </label>
        </td>
        <td>
          <div class="action-cell">
            <button class="icon-btn btn-edit" title="Ubah menu">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
            </button>
            <button class="icon-btn btn-delete" title="Hapus menu">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/></svg>
            </button>
          </div>
        </td>
      </tr>
    `).join('');

    tbody.querySelectorAll('tr[data-id]').forEach(row => {
      const id = Number(row.dataset.id);
      row.querySelector('.status-toggle').addEventListener('change', (e) => toggleStatus(id, e.target.checked, e.target));
      row.querySelector('.btn-edit').addEventListener('click', () => openMenuModal(id));
      row.querySelector('.btn-delete').addEventListener('click', () => deleteMenu(id));
    });
  }

  async function toggleStatus(id, val, checkboxEl){
    const item = menuItems.find(m => m.id === id);
    if(!item) return;
    try{
      await toggleMenuOnServer(id, val);
      item.aktif = val;
      showToast(val ? "Menu ditampilkan di menu publik" : "Menu disembunyikan dari menu publik");
      renderDashboard();
    }catch(e){
      console.error('Toggle status error:', e);
      showToast("Gagal mengubah status: " + e.message, 3500);
      if(checkboxEl) checkboxEl.checked = item.aktif;
    }
  }

  async function deleteMenu(id){
    const item = menuItems.find(m => m.id === id);
    if(!item) return;
    if(!confirm(`Hapus "${item.nama}" dari daftar menu?`)) return;
    try{
      await deleteMenuOnServer(id);
      menuItems = menuItems.filter(m => m.id !== id);
      renderMenuList();
      renderDashboard();
      showToast("Menu berhasil dihapus");
    }catch(e){
      console.error('Delete menu error:', e);
      showToast("Gagal menghapus menu: " + e.message, 3500);
    }
  }

  /* ==========================================================
     MENU MODAL (Add / Edit) — termasuk upload foto
     ========================================================== */
  const overlay = document.getElementById('menu-modal-overlay');
  const menuForm = document.getElementById('menu-form');
  const inputNama = document.getElementById('input-nama');
  const inputDeskripsi = document.getElementById('input-deskripsi');
  const inputKategori = document.getElementById('input-kategori');
  const inputHarga = document.getElementById('input-harga');
  const inputPublik = document.getElementById('input-publik');
  const inputSold = document.getElementById('input-sold');
  inputSold.addEventListener('change', () => {
    if (inputSold.checked) {
      // Menu yang ditandai SOLD harus tetap "Tampil di menu publik" (biar muncul sebagai kotak SOLD, bukan hilang)
      inputPublik.checked = true;
    }
  });
  const inputFoto = document.getElementById('input-foto');
  const fotoPreview = document.getElementById('foto-preview');
  const fotoPreviewPlaceholder = document.getElementById('foto-preview-placeholder');
  const fotoRemoveBtn = document.getElementById('foto-remove');
  const fgFoto = document.getElementById('fg-foto');

  const MAX_FOTO_BYTES = 2 * 1024 * 1024; // 2MB

  function resetFotoField(existingUrl){
    selectedFotoFile = null;
    removeFotoFlag = false;
    inputFoto.value = '';
    fgFoto.classList.remove('error');
    const photoMsg = document.getElementById('foto-error-msg');
    if(photoMsg) photoMsg.textContent = 'Ukuran atau format foto tidak valid (Maks 2MB).';
    if(existingUrl){
      fotoPreview.src = existingUrl;
      fotoPreview.style.display = 'block';
      fotoPreviewPlaceholder.style.display = 'none';
      fotoRemoveBtn.style.display = 'inline';
    }else{
      fotoPreview.src = '';
      fotoPreview.style.display = 'none';
      fotoPreviewPlaceholder.style.display = 'block';
      fotoRemoveBtn.style.display = 'none';
    }
  }

  inputFoto.addEventListener('change', () => {
    const file = inputFoto.files[0];
    fgFoto.classList.remove('error');
    if(!file) return;

    if(file.size > MAX_FOTO_BYTES){
      fgFoto.classList.add('error');
      const photoMsg = document.getElementById('foto-error-msg');
      if(photoMsg) photoMsg.textContent = 'Ukuran foto melebihi batas 2MB.';
      inputFoto.value = '';
      return;
    }

    selectedFotoFile = file;
    removeFotoFlag = false;
    const reader = new FileReader();
    reader.onload = (e) => {
      fotoPreview.src = e.target.result;
      fotoPreview.style.display = 'block';
      fotoPreviewPlaceholder.style.display = 'none';
      fotoRemoveBtn.style.display = 'inline';
    };
    reader.readAsDataURL(file);
  });

  fotoRemoveBtn.addEventListener('click', () => {
    selectedFotoFile = null;
    removeFotoFlag = true;
    inputFoto.value = '';
    fotoPreview.src = '';
    fotoPreview.style.display = 'none';
    fotoPreviewPlaceholder.style.display = 'block';
    fotoRemoveBtn.style.display = 'none';
  });

  function openMenuModal(id){
    editingId = id || null;
    updateCategorySelectOptions();
    document.getElementById('fg-nama').classList.remove('error');
    document.getElementById('fg-harga').classList.remove('error');
    fgFoto.classList.remove('error');

    if(editingId){
      const item = menuItems.find(m => m.id === editingId);
      if(!item) return;
      document.getElementById('menu-modal-title').textContent = "Edit Menu";
      document.getElementById('menu-modal-submit').textContent = "Simpan Perubahan";
      inputNama.value = item.nama;
      inputDeskripsi.value = item.deskripsi || '';
      inputKategori.value = item.kategori;
      inputHarga.value = item.harga;
      inputPublik.checked = item.aktif;
      inputSold.checked = !!item.sold;
      resetFotoField(item.gambar_url || null);
    }else{
      document.getElementById('menu-modal-title').textContent = "Tambah Menu";
      document.getElementById('menu-modal-submit').textContent = "Simpan";
      menuForm.reset();
      inputPublik.checked = true;
      inputSold.checked = false;
      const catNames = getCategoryNames();
      if(catNames.length > 0) inputKategori.value = catNames[0];
      resetFotoField(null);
    }
    overlay.classList.add('show');
    setTimeout(() => inputNama.focus(), 50);
  }
  function closeMenuModal(){
    overlay.classList.remove('show');
    editingId = null;
  }
  document.getElementById('btn-add-menu').addEventListener('click', () => openMenuModal(null));
  document.getElementById('menu-modal-close').addEventListener('click', closeMenuModal);
  document.getElementById('menu-modal-cancel').addEventListener('click', closeMenuModal);
  overlay.addEventListener('click', (e) => { if(e.target === overlay) closeMenuModal(); });

  menuForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    const nama = inputNama.value.trim();
    const harga = inputHarga.value;
    let ok = true;

    document.getElementById('fg-nama').classList.toggle('error', !nama);
    document.getElementById('fg-harga').classList.toggle('error', harga === '' || Number(harga) < 0 || isNaN(Number(harga)));
    if(!nama || harga === '' || Number(harga) < 0 || isNaN(Number(harga))) ok = false;
    if(!ok) return;

    const formData = new FormData();
    formData.append('nama', nama);
    formData.append('deskripsi', inputDeskripsi.value.trim());
    formData.append('kategori', inputKategori.value);
    formData.append('harga', Number(harga));
    formData.append('aktif', inputPublik.checked ? '1' : '0');
    formData.append('sold', inputSold.checked ? '1' : '0');
    if(selectedFotoFile) formData.append('gambar', selectedFotoFile);
    if(removeFotoFlag) formData.append('hapus_gambar', '1');

    const submitBtn = document.getElementById('menu-modal-submit');
    const originalLabel = submitBtn.textContent;
    submitBtn.disabled = true;
    submitBtn.textContent = "Menyimpan...";

    try{
      if(editingId){
        const updated = await updateMenuOnServer(editingId, formData);
        const idx = menuItems.findIndex(m => m.id === editingId);
        if(idx !== -1) {
          menuItems[idx] = updated;
        }
        showToast("Perubahan menu berhasil disimpan");
      }else{
        const created = await createMenuOnServer(formData);
        menuItems.unshift(created);
        showToast("Menu baru berhasil ditambahkan");
      }
      closeMenuModal();
      renderMenuList();
      renderDashboard();
    }catch(e){
      console.error('Menu save error:', e);
      if(e.errors){
        if(e.errors.nama) document.getElementById('fg-nama').classList.add('error');
        if(e.errors.harga) document.getElementById('fg-harga').classList.add('error');
        if(e.errors.gambar){
          fgFoto.classList.add('error');
          const photoMsg = document.getElementById('foto-error-msg');
          if(photoMsg) photoMsg.textContent = e.errors.gambar[0];
        }
      }
      showToast("Gagal menyimpan menu: " + e.message, 4000);
    }finally{
      submitBtn.disabled = false;
      submitBtn.textContent = originalLabel;
    }
  });

  /* ==========================================================
     KATEGORI CAROUSEL LIST
     ========================================================== */
  function renderCategoryList(){
    const tbody = document.getElementById('kategori-tbody');
    if(!tbody) return;
    let items = categories;
    if(searchTerm) {
      items = items.filter(c => c.name.toLowerCase().includes(searchTerm) || (c.slug && c.slug.toLowerCase().includes(searchTerm)));
    }

    if(items.length === 0){
      tbody.innerHTML = `<tr class="empty-row"><td colspan="6">Belum ada kategori carousel yang cocok. Klik "Tambah Kategori" untuk membuat baru.</td></tr>`;
      return;
    }

    tbody.innerHTML = items.map(cat => {
      const menuCount = cat.menus_count !== undefined ? cat.menus_count : menuItems.filter(m => m.kategori === cat.name).length;
      const imgUrl = cat.image_url || 'images/Emoji.png';
      return `
        <tr data-id="${cat.id}">
          <td>
            <div class="cat-icon-cell">
              <img src="${escapeHtml(imgUrl)}" alt="${escapeHtml(cat.name)}" onerror="this.onerror=null;this.src='images/Emoji.png';">
            </div>
          </td>
          <td><strong>${escapeHtml(cat.name)}</strong></td>
          <td><span class="kategori-pill" style="font-family:monospace;font-size:12px;">${escapeHtml(cat.slug || '')}</span></td>
          <td><span class="sort-badge">#${cat.sort_order ?? 0}</span></td>
          <td><span style="font-weight:700;color:var(--ink);">${menuCount} menu</span></td>
          <td>
            <div class="action-cell">
              <button type="button" class="icon-btn btn-edit-cat" title="Ubah kategori">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
              </button>
              <button type="button" class="icon-btn btn-delete-cat" title="Hapus kategori">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/></svg>
              </button>
            </div>
          </td>
        </tr>
      `;
    }).join('');

    tbody.querySelectorAll('tr[data-id]').forEach(row => {
      const id = Number(row.dataset.id);
      row.querySelector('.btn-edit-cat').addEventListener('click', () => openCategoryModal(id));
      row.querySelector('.btn-delete-cat').addEventListener('click', () => deleteCategory(id));
    });
  }

  /* ==========================================================
     KATEGORI MODAL (Add / Edit)
     ========================================================== */
  const catOverlay = document.getElementById('category-modal-overlay');
  const catForm = document.getElementById('category-form');
  const inputCatNama = document.getElementById('input-cat-nama');
  const inputCatSlug = document.getElementById('input-cat-slug');
  const inputCatSort = document.getElementById('input-cat-sort');
  const inputCatFoto = document.getElementById('input-cat-foto');
  const catFotoPreview = document.getElementById('cat-foto-preview');
  const catFotoPreviewPlaceholder = document.getElementById('cat-foto-preview-placeholder');
  const catFotoRemoveBtn = document.getElementById('cat-foto-remove');
  const fgCatFoto = document.getElementById('fg-cat-foto');

  function resetCatFotoField(existingUrl){
    selectedCatFotoFile = null;
    removeCatFotoFlag = false;
    if(inputCatFoto) inputCatFoto.value = '';
    if(fgCatFoto) fgCatFoto.classList.remove('error');
    const photoMsg = document.getElementById('cat-foto-error-msg');
    if(photoMsg) photoMsg.textContent = 'Ukuran atau format foto tidak valid (Maks 2MB).';
    if(existingUrl){
      if(catFotoPreview) {
        catFotoPreview.src = existingUrl;
        catFotoPreview.style.display = 'block';
      }
      if(catFotoPreviewPlaceholder) catFotoPreviewPlaceholder.style.display = 'none';
      if(catFotoRemoveBtn) catFotoRemoveBtn.style.display = 'inline';
    }else{
      if(catFotoPreview) {
        catFotoPreview.src = '';
        catFotoPreview.style.display = 'none';
      }
      if(catFotoPreviewPlaceholder) catFotoPreviewPlaceholder.style.display = 'block';
      if(catFotoRemoveBtn) catFotoRemoveBtn.style.display = 'none';
    }
  }

  if(inputCatFoto){
    inputCatFoto.addEventListener('change', () => {
      const file = inputCatFoto.files[0];
      if(fgCatFoto) fgCatFoto.classList.remove('error');
      if(!file) return;

      if(file.size > MAX_FOTO_BYTES){
        if(fgCatFoto) fgCatFoto.classList.add('error');
        const photoMsg = document.getElementById('cat-foto-error-msg');
        if(photoMsg) photoMsg.textContent = 'Ukuran foto melebihi batas 2MB.';
        inputCatFoto.value = '';
        return;
      }

      selectedCatFotoFile = file;
      removeCatFotoFlag = false;
      const reader = new FileReader();
      reader.onload = (e) => {
        if(catFotoPreview){
          catFotoPreview.src = e.target.result;
          catFotoPreview.style.display = 'block';
        }
        if(catFotoPreviewPlaceholder) catFotoPreviewPlaceholder.style.display = 'none';
        if(catFotoRemoveBtn) catFotoRemoveBtn.style.display = 'inline';
      };
      reader.readAsDataURL(file);
    });
  }

  if(catFotoRemoveBtn){
    catFotoRemoveBtn.addEventListener('click', () => {
      selectedCatFotoFile = null;
      removeCatFotoFlag = true;
      if(inputCatFoto) inputCatFoto.value = '';
      if(catFotoPreview){
        catFotoPreview.src = '';
        catFotoPreview.style.display = 'none';
      }
      if(catFotoPreviewPlaceholder) catFotoPreviewPlaceholder.style.display = 'block';
      catFotoRemoveBtn.style.display = 'none';
    });
  }

  function openCategoryModal(id){
    editingCatId = id || null;
    const fgNama = document.getElementById('fg-cat-nama');
    if(fgNama) fgNama.classList.remove('error');
    if(fgCatFoto) fgCatFoto.classList.remove('error');

    if(editingCatId){
      const cat = categories.find(c => c.id === editingCatId);
      if(!cat) return;
      document.getElementById('category-modal-title').textContent = "Edit Kategori Carousel";
      document.getElementById('category-modal-submit').textContent = "Simpan Perubahan";
      inputCatNama.value = cat.name;
      inputCatSlug.value = cat.slug || '';
      inputCatSort.value = cat.sort_order ?? 0;
      resetCatFotoField(cat.image_url || null);
    }else{
      document.getElementById('category-modal-title').textContent = "Tambah Kategori Carousel";
      document.getElementById('category-modal-submit').textContent = "Simpan";
      catForm.reset();
      const nextSort = categories.length > 0 ? Math.max(...categories.map(c => Number(c.sort_order) || 0)) + 1 : 1;
      inputCatSort.value = nextSort;
      resetCatFotoField(null);
    }
    if(catOverlay) catOverlay.classList.add('show');
    setTimeout(() => { if(inputCatNama) inputCatNama.focus(); }, 50);
  }

  function closeCategoryModal(){
    if(catOverlay) catOverlay.classList.remove('show');
    editingCatId = null;
  }

  const btnAddKategori = document.getElementById('btn-add-kategori');
  if(btnAddKategori) btnAddKategori.addEventListener('click', () => openCategoryModal(null));
  const catModalClose = document.getElementById('category-modal-close');
  if(catModalClose) catModalClose.addEventListener('click', closeCategoryModal);
  const catModalCancel = document.getElementById('category-modal-cancel');
  if(catModalCancel) catModalCancel.addEventListener('click', closeCategoryModal);
  if(catOverlay) catOverlay.addEventListener('click', (e) => { if(e.target === catOverlay) closeCategoryModal(); });

  if(catForm){
    catForm.addEventListener('submit', async (e) => {
      e.preventDefault();
      const nama = inputCatNama.value.trim();
      const fgNama = document.getElementById('fg-cat-nama');
      if(!nama){
        if(fgNama) fgNama.classList.add('error');
        return;
      }
      if(fgNama) fgNama.classList.remove('error');

      const formData = new FormData();
      formData.append('name', nama);
      formData.append('slug', inputCatSlug.value.trim());
      formData.append('sort_order', inputCatSort.value);
      if(selectedCatFotoFile) formData.append('image', selectedCatFotoFile);
      if(removeCatFotoFlag) formData.append('hapus_image', '1');

      const submitBtn = document.getElementById('category-modal-submit');
      const originalLabel = submitBtn.textContent;
      submitBtn.disabled = true;
      submitBtn.textContent = "Menyimpan...";

      try{
        if(editingCatId){
          const updated = await updateCategoryOnServer(editingCatId, formData);
          const idx = categories.findIndex(c => c.id === editingCatId);
          if(idx !== -1) categories[idx] = updated;
          showToast("Kategori berhasil diperbarui");
        }else{
          const created = await createCategoryOnServer(formData);
          categories.push(created);
          showToast("Kategori baru berhasil ditambahkan");
        }
        closeCategoryModal();
        updateCategorySelectOptions();
        renderCategoryList();
        renderCategoryPills();
        renderDashboard();
      }catch(err){
        console.error('Category save error:', err);
        if(err.errors){
          if(err.errors.name && fgNama) fgNama.classList.add('error');
          if(err.errors.image && fgCatFoto){
            fgCatFoto.classList.add('error');
            const photoMsg = document.getElementById('cat-foto-error-msg');
            if(photoMsg) photoMsg.textContent = err.errors.image[0];
          }
        }
        showToast("Gagal menyimpan kategori: " + err.message, 4000);
      }finally{
        submitBtn.disabled = false;
        submitBtn.textContent = originalLabel;
      }
    });
  }

  async function deleteCategory(id){
    const cat = categories.find(c => c.id === id);
    if(!cat) return;
    const menuCount = cat.menus_count !== undefined ? cat.menus_count : menuItems.filter(m => m.kategori === cat.name).length;
    const confirmMsg = menuCount > 0
      ? `Kategori "${cat.name}" memiliki ${menuCount} menu terkait. Yakin ingin menghapus kategori ini?`
      : `Hapus kategori "${cat.name}" dari carousel?`;
    if(!confirm(confirmMsg)) return;

    try{
      await deleteCategoryOnServer(id);
      categories = categories.filter(c => c.id !== id);
      updateCategorySelectOptions();
      renderCategoryList();
      renderCategoryPills();
      renderDashboard();
      showToast("Kategori berhasil dihapus");
    }catch(e){
      console.error('Delete category error:', e);
      showToast("Gagal menghapus kategori: " + e.message, 3500);
    }
  }

  /* ==========================================================
     JAM OPERASIONAL
     ========================================================== */
  function renderSchedule(){
    const wrap = document.getElementById('jadwal-list');
    wrap.innerHTML = DAYS.map(day => {
      const d = schedule[day] || {open:"11:00", close:"21:00", closed:false};
      return `
        <div class="day-row" data-day="${day}">
          <div class="day-label">${day}</div>
          <div class="day-time-wrap">
            <input type="time" class="time-field time-open" value="${d.open}" ${d.closed?'disabled':''}>
            <span class="time-sep">-</span>
            <input type="time" class="time-field time-close" value="${d.close}" ${d.closed?'disabled':''}>
          </div>
          <label class="closed-toggle">
            <input type="checkbox" class="closed-check" ${d.closed?'checked':''}>
            <span>Tutup hari ini</span>
          </label>
        </div>`;
    }).join('');

    wrap.querySelectorAll('.day-row').forEach(row => {
      const day = row.dataset.day;
      const openInput = row.querySelector('.time-open');
      const closeInput = row.querySelector('.time-close');
      const closedCheck = row.querySelector('.closed-check');

      closedCheck.addEventListener('change', () => {
        schedule[day].closed = closedCheck.checked;
        openInput.disabled = closedCheck.checked;
        closeInput.disabled = closedCheck.checked;
      });
      openInput.addEventListener('change', () => { schedule[day].open = openInput.value; });
      closeInput.addEventListener('change', () => { schedule[day].close = closeInput.value; });
    });
  }

  document.getElementById('btn-save-jadwal').addEventListener('click', async (e) => {
    const btn = e.currentTarget;
    const originalLabel = btn.textContent;
    btn.disabled = true;
    try{
      await saveSchedule();
      showToast("Jadwal jam operasional disimpan");
    }catch(err){
      console.error('Gagal menyimpan jadwal:', err);
      showToast('Gagal menyimpan jadwal: ' + (err.message || 'Koneksi bermasalah'), 4000);
    }finally{
      btn.disabled = false;
      btn.textContent = originalLabel;
    }
  });

  /* ==========================================================
     INIT
     Status login diambil dari window.__AUTH__ yang di-inject blade
     dari sesi Laravel (bukan dari storage sisi klien lagi).
     ========================================================== */
  (async function init(){
    const auth = window.__AUTH__ || { authenticated: false };
    if(auth.authenticated){
      const usernameLabel = document.getElementById('admin-username-label') || document.getElementById('admin-name-label');
      if (usernameLabel) {
        usernameLabel.textContent = auth.username || auth.name || 'Admin';
      }
      const emailLabel = document.getElementById('admin-email-label');
      if (emailLabel && auth.email) {
        emailLabel.textContent = auth.email;
      }
      await loadAppData();
      showAuthView('app');
      switchView('dashboard');
    }else{
      window.location.href = getApiUrl('/admin/login');
    }
  })();

})();