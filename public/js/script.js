const DEFAULT_MENU_DATA = {
  ramen: {
    label: "Ramen",
    categoryImage: "images/Ramen_kategori.png",
    items: [
      {
        name: "Tori Teriyaki Ramen",
        price: 40700,
        image: "images/tori_teriyaki_ramen.png",
        desc: "Ramen gurih dengan topping daging ayam bumbu teriyaki, telur, nori dan sayuran.",
      },
      {
        name: "Ebi Furai Ramen",
        price: 42900,
        image: "images/ebi_furai_ramen.png",
        desc: "Ramen dengan topping udang goreng tepung crispy, telur, nori dan sayuran segar.",
      },
      {
        name: "Spicy Gyuniku Ramen",
        price: 46200,
        image: "images/spicy_gyuniku_ramen.png",
        desc: "Ramen pedas dengan topping daging sapi, telur setengah matang, nori dan sayuran.",
      },
      {
        name: "Gyukara Ramen with Tori Paithan",
        price: 46200,
        image: "images/gyukara_ramen_with_paithan.png",
        desc: "Ramen kuah kaldu ayam kental (paithan) dengan topping daging sapi pedas dan telur.",
      },
      {
        name: "Tori Abura Ramen",
        price: 40700,
        image: "images/tori_abura_ramen.png",
        desc: "Ramen tanpa kuah (abura soba) dengan topping ayam, telur, nori dan saus spesial.",
      },
      {
        name: "Curry Tori Paithan Ramen",
        price: 46200,
        image: "images/curry_tori_paithan_ramen.png",
        desc: "Ramen kuah kari kental khas Jepang dengan topping ayam, telur dan sayuran.",
      },
      {
        name: "Tori Katsu Ramen",
        price: 42700,
        image: "images/tori_katsu_ramen.png",
        desc: "Ramen dengan topping ayam katsu crispy, telur, nori dan sayuran segar.",
      },
      {
        name: "Spicy Beef Ramen",
        price: 46200,
        image: "images/spicy_gyuniku_ramen.png",
        desc: "Ramen kuah pedas dengan topping irisan daging sapi empuk, telur, dan nori segar.",
        sold: true,
      },
    ],
  },

  donburi: {
    label: "Donburi",
    categoryImage: "images/Donburi_kategori.png",
    items: [
      {
        name: "Tori Katsu Don",
        price: 37400,
        image: "images/tori_katsu_don.webp",
        desc: "Nasi dengan topping ayam katsu crispy disiram saus donburi manis gurih.",
      },
      {
        name: "Tori Teriyaki Don",
        price: 37400,
        image: "images/tori_teriyaki_don.webp",
        desc: "Nasi dengan topping ayam panggang saus teriyaki dan sayuran segar.",
      },
      {
        name: "Tori Katsu Curry Don",
        price: 37400,
        image: "images/tori_katsu_curry_don.webp",
        desc: "Nasi dengan topping ayam katsu crispy disiram saus kari khas Jepang.",
      },
      {
        name: "Gyu Katsu Curry Don",
        price: 46200,
        image: "images/gyu_katsu_curry_don.webp",
        desc: "Nasi dengan topping daging sapi katsu crispy disiram saus kari khas Jepang.",
      },
      {
        name: "Gyuniku Teriyaki Don",
        price: 46200,
        image: "images/gyuniku_teriyaki_don.webp",
        desc: "Nasi dengan topping daging sapi panggang saus teriyaki dan sayuran segar.",
      },
    ],
  },

  roll: {
    label: "Fushion Roll",
    categoryImage: "images/Fushion_Roll_kategori.png",
    items: [
      {
        name: "Chicken Mentai Roll",
        price: 30000,
        image: "images/chicken_mentai_roll.webp",
        desc: "Nasi sushi, saus mentai creamy dan irisan cabai diatasnya.",
      },
      {
        name: "Dory Mentai Roll",
        price: 30000,
        image: "images/dori_mentai_roll.webp",
        desc: "Nasi sushi dengan dori dipadukan chili sauce dan mayo mentai.",
      },
      {
        name: "Katsu Spicy Cheese Roll",
        price: 38000,
        image: "images/katsu_spicy_cheese_roll.webp",
        desc: "Chicken katsu, keju, bubuk cabai, telur ikan dan saus keju.",
      },
      {
        name: "Dory Spicy Cheese Roll",
        price: 38000,
        image: "images/dori_spicy_cheese_roll.webp",
        desc: "Dory katsu, keju, bubuk cabai dan saus keju.",
      },
      {
        name: "Top Gun Roll",
        price: 48000,
        image: "images/top_gun roll.webp",
        desc: "Crab stick, telur ikan, tempura garing, mentimun dan mayonnes",
      },
      {
        name: "Katsu Roll",
        price: 38000,
        image: "images/katsu_roll.webp",
        desc: "Chicken katsu, telur ikan dan mayonnes pedas.",
      },
      {
        name: "Fish Cake Roll",
        price: 38000,
        image: "images/fish_cake_roll.webp",
        desc: "16 Sushi; 8 salmon skin roll dengan unagi, 8 salmon skin roll dengan alpukat.",
      },
      {
        name: "Spicy Chicken Roll",
        price: 38000,
        image: "images/spicy_chicken_roll.webp",
        desc: "Ayam, cabai rawit, telur ikan dan mayonnes.",
      },
      {
        name: "California Roll",
        price: 52000,
        image: "images/california_roll.webp",
        desc: "Alpukat, crab stick, tamago, mentimun, telur ikan, wijen hitam dan mayonnes.",
      },
    ],
  },

  beverages: {
    label: "Beverages",
    categoryImage: "images/Beverages_kategori.png",
    items: [
      {
        name: "Ocha",
        price: 12100,
        image: "images/ocha.webp",
        desc: "Minuman segar terbuat dari teh hijau.",
      },
      {
        name: "Mineral Water 330 ml",
        price: 12100,
        image: "images/mineral_water.webp",
        desc: "Minuman air segar dalam kemasan botol",
      },
      {
        name: "Lemonade",
        price: 14300,
        image: "images/lemonade.webp",
        desc: "Minuman segar yang terbuat dari perasan lemon.",
      },
      {
        name: "Ice Lemon Tea",
        price: 14300,
        image: "images/ice_lemon_tea.webp",
        desc: "Minuman teh dan perasan lemon yang segar",
      },
      {
        name: "Blackcurrant Tea",
        price: 14300,
        image: "images/blackcurrant_tea.webp",
        desc: "Minuman segar rasa anggur",
      },
      {
        name: "Orange Juice",
        price: 14300,
        image: "images/orange_juice.webp",
        desc: "Minuman dari perasan jeruk yang segar, bercita rasa unik",
      },
      {
        name: "Tea Kotak Sosro",
        price: 14300,
        image: "images/tek_botol_sosro.webp",
        desc: "Minuman teh dalam kemasan kotak",
      },
    ],
  },
};

const MENU_DATA = window.MENU_DATA && Object.keys(window.MENU_DATA).length > 0 ? window.MENU_DATA : DEFAULT_MENU_DATA;

// Urutan kategori yang ditampilkan di carousel
const CATEGORY_ORDER = window.CATEGORY_ORDER && window.CATEGORY_ORDER.length > 0
  ? window.CATEGORY_ORDER
  : Object.keys(MENU_DATA);

// Data jam buka
const HOURS_DATA = window.HOURS_DATA && window.HOURS_DATA.length > 0
  ? window.HOURS_DATA
  : [
    { day: "SENIN", time: "10:00 - 21:45" },
    { day: "SELASA", time: "10:00 - 21:45" },
    { day: "RABU", time: "10:00 - 21:45" },
    { day: "KAMIS", time: "10:00 - 21:45" },
    { day: "JUMAT", time: "10:00 - 21:45" },
    { day: "SABTU", time: "10:00 - 21:45" },
    { day: "MINGGU", time: "10:00 - 21:45" },
  ];

/* ============================ UTIL ============================ */

function formatPrice(num) {
  return "Rp " + num.toLocaleString("id-ID");
}

/* ============================ NAVBAR ============================ */

const header = document.getElementById("site-header");
const navLinks = document.querySelectorAll(".nav-link");
const menuToggle = document.getElementById("menu-toggle");
const mobileMenu = document.getElementById("mobile-menu");
const iconBurger = document.getElementById("icon-burger");
const iconClose = document.getElementById("icon-close");

function scrollToSection(targetSelector) {
  const target = document.querySelector(targetSelector);
  if (!target) return;
  const headerHeight = header ? header.offsetHeight : 72;
  const targetPosition = target.getBoundingClientRect().top + window.pageYOffset - headerHeight;
  window.scrollTo({
    top: targetPosition,
    behavior: "smooth"
  });
}

function openMobileMenu() {
  if (!mobileMenu) return;
  mobileMenu.classList.add("is-open");
  if (menuToggle) menuToggle.setAttribute("aria-expanded", "true");
  if (iconBurger) iconBurger.classList.add("hidden");
  if (iconClose) iconClose.classList.remove("hidden");
}

function closeMobileMenu() {
  if (!mobileMenu) return;
  mobileMenu.classList.remove("is-open");
  if (menuToggle) menuToggle.setAttribute("aria-expanded", "false");
  if (iconBurger) iconBurger.classList.remove("hidden");
  if (iconClose) iconClose.classList.add("hidden");
}

function toggleMobileMenu() {
  if (!mobileMenu) return;
  if (mobileMenu.classList.contains("is-open")) {
    closeMobileMenu();
  } else {
    openMobileMenu();
  }
}

navLinks.forEach((link) => {
  link.addEventListener("click", (e) => {
    e.preventDefault();
    const targetSelector = link.getAttribute("data-target");
    scrollToSection(targetSelector);
    if (targetSelector === "#tentang-kami") {
      if (typeof resetAboutAnimation === "function" && typeof triggerAboutAnimation === "function") {
        resetAboutAnimation();
        setTimeout(triggerAboutAnimation, 120);
      } else {
        playTypingAnimation();
      }
    }
    closeMobileMenu();
  });
});

if (menuToggle) {
  menuToggle.addEventListener("click", (e) => {
    e.stopPropagation();
    toggleMobileMenu();
  });
}

// Tutup menu mobile ketika klik di luar header
document.addEventListener("click", (e) => {
  if (mobileMenu && mobileMenu.classList.contains("is-open")) {
    if (!header || !header.contains(e.target)) {
      closeMobileMenu();
    }
  }
});

// Scrollspy: highlight nav sesuai section yang sedang terlihat
const sections = ["#tentang-kami", "#menu-kami", "#jam-operasional"]
  .map((sel) => document.querySelector(sel))
  .filter((el) => el !== null);

function updateActiveNav() {
  if (!header || sections.length === 0) return;
  const headerHeight = header.offsetHeight + 10;
  let currentId = sections[0].id;

  sections.forEach((section) => {
    const rect = section.getBoundingClientRect();
    if (rect.top <= headerHeight) {
      currentId = section.id;
    }
  });

  navLinks.forEach((link) => {
    const isActive = link.getAttribute("data-target") === "#" + currentId;
    link.classList.toggle("active", isActive);
  });
}

let navScrollTicking = false;
function onNavScroll() {
  if (!navScrollTicking) {
    window.requestAnimationFrame(() => {
      updateActiveNav();
      navScrollTicking = false;
    });
    navScrollTicking = true;
  }
}

window.addEventListener("scroll", onNavScroll, { passive: true });
window.addEventListener("load", updateActiveNav);

/* ============================ ABOUT ANIMATION ============================ */

const aboutSection = document.getElementById("tentang-kami");
let typingTimeout = null;

// Reset semua animasi di section Tentang Kami
function resetAboutAnimation() {
  if (!aboutSection) return;
  aboutSection.classList.remove("in-view");
  const aboutElements = aboutSection.querySelectorAll(
    ".reveal-slide-left, .reveal-slide-right, .reveal-zoom-in, .reveal-fade-up, .about-text, #about-left, #about-right, #about-mascot"
  );
  aboutElements.forEach((el) => {
    el.classList.remove("is-revealed");
  });
}

// Trigger ulang semua animasi di section Tentang Kami
function triggerAboutAnimation() {
  if (!aboutSection) return;
  aboutSection.classList.add("in-view");
  playTypingAnimation();
  const aboutElements = aboutSection.querySelectorAll(
    ".reveal-slide-left, .reveal-slide-right, .reveal-zoom-in, .reveal-fade-up, .about-text, #about-left, #about-right, #about-mascot"
  );
  aboutElements.forEach((el) => {
    el.classList.add("is-revealed");
  });
}

// Typing animation untuk teks "TAHUKAH ANDA?" (berulang setiap kali user kembali ke section Tentang Kami)
function playTypingAnimation() {
  const titleEl = document.getElementById("about-title");
  if (!titleEl) return;
  const fullText = titleEl.getAttribute("data-text") || "TAHUKAH ANDA?";

  if (typingTimeout) {
    clearTimeout(typingTimeout);
    typingTimeout = null;
  }

  titleEl.textContent = "";

  const cursor = document.createElement("span");
  cursor.className = "typing-cursor";
  cursor.textContent = "|";
  titleEl.appendChild(cursor);

  let charIndex = 0;
  const typingSpeed = 80;

  function typeChar() {
    if (charIndex < fullText.length) {
      cursor.before(document.createTextNode(fullText.charAt(charIndex)));
      charIndex++;
      typingTimeout = setTimeout(typeChar, typingSpeed);
    } else {
      typingTimeout = setTimeout(() => {
        cursor.classList.add("fade-out");
        setTimeout(() => {
          if (cursor.parentNode) cursor.remove();
        }, 400);
      }, 1000);
    }
  }

  typingTimeout = setTimeout(typeChar, 250);
}

// IntersectionObserver: section "Tentang Kami" otomatis animasi MASUK saat terlihat di viewport
if (aboutSection) {
  let aboutAnimated = false;
  const aboutObserver = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting && !aboutAnimated) {
          triggerAboutAnimation();
          aboutAnimated = true;
          aboutObserver.unobserve(aboutSection);
        }
      });
    },
    { threshold: 0.15 }
  );
  aboutObserver.observe(aboutSection);
}

/* ============================ MENU: KATEGORI ============================ */

const categoryTrack = document.getElementById("category-track");
const categoryTitle = document.getElementById("category-title");
const menuGrid = document.getElementById("menu-grid");
const catPrevBtn = document.getElementById("cat-prev");
const catNextBtn = document.getElementById("cat-next");

let activeCategory = CATEGORY_ORDER[0];

function renderCategories() {
  if (!categoryTrack) return;
  categoryTrack.innerHTML = "";
  CATEGORY_ORDER.forEach((key) => {
    const cat = MENU_DATA[key];
    const card = document.createElement("button");
    card.type = "button";
    card.className = "category-card" + (key === activeCategory ? " active" : "");
    card.setAttribute("data-category", key);
    card.innerHTML = `
      <img src="${cat.categoryImage}" alt="${cat.label}" />
      <span class="category-label">${cat.label}</span>
    `;
    card.addEventListener("click", () => setActiveCategory(key));
    categoryTrack.appendChild(card);
  });
}

function setActiveCategory(key) {
  activeCategory = key;
  document.querySelectorAll(".category-card").forEach((card) => {
    card.classList.toggle("active", card.getAttribute("data-category") === key);
  });
  if (categoryTitle) categoryTitle.textContent = MENU_DATA[key].label;
  renderMenuItems(key);
}

function goToCategory(direction) {
  const currentIndex = CATEGORY_ORDER.indexOf(activeCategory);
  let newIndex = currentIndex + direction;
  if (newIndex < 0) newIndex = CATEGORY_ORDER.length - 1;
  if (newIndex >= CATEGORY_ORDER.length) newIndex = 0;

  const newKey = CATEGORY_ORDER[newIndex];
  setActiveCategory(newKey);

  const activeCard = categoryTrack.querySelector(`[data-category="${newKey}"]`);
  if (activeCard) {
    activeCard.scrollIntoView({ behavior: "smooth", inline: "center", block: "nearest" });
  }
}

if (catPrevBtn && catNextBtn && categoryTrack) {
  catPrevBtn.addEventListener("click", () => goToCategory(-1));
  catNextBtn.addEventListener("click", () => goToCategory(1));
}

/* ============================ MENU: GRID ITEM ============================ */

function renderMenuItems(key) {
  if (!menuGrid) return;
  const catData = MENU_DATA[key];
  const items = catData && Array.isArray(catData.items) ? catData.items : [];
  menuGrid.innerHTML = "";

  if (items.length === 0) {
    menuGrid.innerHTML = `
      <div style="grid-column: 1 / -1; text-align: center; padding: 3rem 1.5rem; color: var(--gray-500); background: #ffffff; border-radius: 1rem; border: 1.5px dashed var(--gray-300); margin: 1rem 0;">
        <h4 style="font-family: var(--font-poppins); font-size: 1.1rem; color: var(--ink); margin-bottom: 0.35rem;">Menu Segera Hadir</h4>
        <p style="font-size: 0.875rem; margin: 0; color: var(--gray-500);">Kategori ini sedang dalam tahap persiapan.</p>
      </div>
    `;
    return;
  }

  items.forEach((item, index) => {
    const delay = (index % 8) * 0.06;
    const isSold = Boolean(item.sold);

    const card = document.createElement("button");
    card.type = "button";
    card.className = `menu-card reveal-fade-up ${isSold ? "is-sold" : ""}`;
    card.style.transitionDelay = `${delay}s`;

    const imgSrc = item.image || "images/logo_putih.png";
    const itemName = item.name || "Menu Hidangan";
    const itemPrice = item.price ? formatPrice(item.price) : "Habis";

    card.innerHTML = `
      <div class="menu-img-wrap">
        <img src="${imgSrc}" alt="${itemName}" />
      </div>
      <div class="menu-name">${itemName}</div>
      <div class="menu-price">${itemPrice}</div>
      ${isSold ? `<span class="sold-badge">SOLD</span>` : ""}
    `;

    card.addEventListener("click", () => openMenuModal(item));
    menuGrid.appendChild(card);
  });

  if (typeof initScrollReveal === "function") {
    initScrollReveal();
  }
}

/* ============================ MENU: MODAL ============================ */

const menuModal = document.getElementById("menu-modal");
const menuModalBackdrop = document.getElementById("menu-modal-backdrop");
const menuModalClose = document.getElementById("menu-modal-close");
const menuModalImg = document.getElementById("menu-modal-img");
const menuModalName = document.getElementById("menu-modal-name");
const menuModalPrice = document.getElementById("menu-modal-price");
const menuModalDesc = document.getElementById("menu-modal-desc");

function openMenuModal(item) {
  if (!menuModal) return;
  menuModalImg.src = item.image;
  menuModalImg.alt = item.name;
  menuModalName.textContent = item.name;
  menuModalPrice.textContent = formatPrice(item.price);
  menuModalDesc.textContent = item.desc || "Menu pilihan lezat dari Ichiban Sushi.";

  /* Tampilkan / sembunyikan badge SOLD di dalam modal */
  const modalSoldBadge = document.getElementById("menu-modal-sold-badge");
  if (modalSoldBadge) {
    modalSoldBadge.style.display = item.sold ? "inline-flex" : "none";
  }

  menuModal.classList.remove("hidden");
  menuModal.classList.add("flex");
  document.body.style.overflow = "hidden";
}

function closeMenuModal() {
  if (!menuModal) return;
  menuModal.classList.add("hidden");
  menuModal.classList.remove("flex");
  document.body.style.overflow = "";
}

if (menuModalClose) menuModalClose.addEventListener("click", closeMenuModal);
if (menuModalBackdrop) menuModalBackdrop.addEventListener("click", closeMenuModal);

/* ============================ MODAL PETUNJUK PENGGUNA & PRIVACY POLICY ============================ */

const guideModal = document.getElementById("guide-modal");
const guideModalBackdrop = document.getElementById("guide-modal-backdrop");
const guideModalClose = document.getElementById("guide-modal-close");
const guideModalBtn = document.getElementById("guide-modal-btn");
const openUserGuideModalBtn = document.getElementById("openUserGuideModal");

function openGuideModal(e) {
  if (e) e.preventDefault();
  if (!guideModal) return;
  guideModal.classList.remove("hidden");
  guideModal.classList.add("flex");
  document.body.style.overflow = "hidden";
}

function closeGuideModal() {
  if (!guideModal) return;
  guideModal.classList.add("hidden");
  guideModal.classList.remove("flex");
  document.body.style.overflow = "";
}

if (openUserGuideModalBtn) openUserGuideModalBtn.addEventListener("click", openGuideModal);
if (guideModalClose) guideModalClose.addEventListener("click", closeGuideModal);
if (guideModalBtn) guideModalBtn.addEventListener("click", closeGuideModal);
if (guideModalBackdrop) guideModalBackdrop.addEventListener("click", closeGuideModal);

const privacyModal = document.getElementById("privacy-modal");
const privacyModalBackdrop = document.getElementById("privacy-modal-backdrop");
const privacyModalClose = document.getElementById("privacy-modal-close");
const privacyModalBtn = document.getElementById("privacy-modal-btn");
const openPrivacyModalBtn = document.getElementById("openPrivacyModal");

function openPrivacyModal(e) {
  if (e) e.preventDefault();
  if (!privacyModal) return;
  privacyModal.classList.remove("hidden");
  privacyModal.classList.add("flex");
  document.body.style.overflow = "hidden";
}

function closePrivacyModal() {
  if (!privacyModal) return;
  privacyModal.classList.add("hidden");
  privacyModal.classList.remove("flex");
  document.body.style.overflow = "";
}

if (openPrivacyModalBtn) openPrivacyModalBtn.addEventListener("click", openPrivacyModal);
if (privacyModalClose) privacyModalClose.addEventListener("click", closePrivacyModal);
if (privacyModalBtn) privacyModalBtn.addEventListener("click", closePrivacyModal);
if (privacyModalBackdrop) privacyModalBackdrop.addEventListener("click", closePrivacyModal);

// Listener tombol Escape untuk semua modal
document.addEventListener("keydown", (e) => {
  if (e.key === "Escape") {
    closeMenuModal();
    closeGuideModal();
    closePrivacyModal();
  }
});

/* ============================ JAM OPERASIONAL ============================ */

const hoursList = document.getElementById("hours-list");

function renderHours() {
  if (!hoursList) return;
  const jsDay = new Date().getDay();
  const todayIndex = jsDay === 0 ? 6 : jsDay - 1;

  hoursList.innerHTML = "";
  HOURS_DATA.forEach((item, index) => {
    const li = document.createElement("li");
    if (index === todayIndex) li.classList.add("today");
    li.innerHTML = `
      <span class="hours-day">${item.day}</span>
      <span class="hours-time">${item.time}</span>
    `;
    hoursList.appendChild(li);
  });
}

/* ============================ LINK MAP ============================ */

const mapLink = document.getElementById("map-link");
if (mapLink) {
  mapLink.href = "https://www.google.com/maps/place/Ichiban+Sushi+Express/@-6.5505829,107.4434456,17z/data=!3m1!4b1!4m6!3m5!1s0x2e690f006f2b9ab5:0x428cc862f74fb79f!8m2!3d-6.5505829!4d107.4434456!16s%2Fg%2F11xnc1qrhc?entry=ttu&g_ep=EgoyMDI2MDgxNy4wIKXMDSoASAFQAw%3D%3D";
}

/* ============================ SCROLL REVEAL ENGINE ============================ */

let revealObserver = null;

function initScrollReveal() {
  const revealElements = document.querySelectorAll(".reveal-fade-up, .reveal-fade-down, .reveal-slide-left, .reveal-slide-right, .reveal-zoom-in");

  if ("IntersectionObserver" in window) {
    if (!revealObserver) {
      revealObserver = new IntersectionObserver(
        (entries, observer) => {
          entries.forEach((entry) => {
            if (entry.isIntersecting) {
              entry.target.classList.add("is-revealed");
              // Unobserve elemen yang sudah tampil agar hemat komputasi dan scroll responsif instan
              observer.unobserve(entry.target);
            }
          });
        },
        {
          threshold: 0.05,
          rootMargin: "0px 0px 40px 0px"
        }
      );
    }

    revealElements.forEach((el) => {
      revealObserver.observe(el);
    });
  } else {
    revealElements.forEach((el) => el.classList.add("is-revealed"));
  }
}

/* ============================ INIT ============================ */

renderCategories();
setActiveCategory(activeCategory);
renderHours();
initScrollReveal();
playTypingAnimation();

/* ============================ PINTU RAHASIA ADMIN (DOUBLE CLICK / TAP LOGO) ============================ */
(function setupSecretAdminDoor() {
  const logoLinks = document.querySelectorAll(".site-header .logo");
  let lastLogoTapTime = 0;

  const redirectToAdminLogin = (e) => {
    if (e) {
      e.preventDefault();
      e.stopPropagation();
    }
    const loginUrl = window.__LOGIN_URL__ || "/admin/login";
    window.location.href = loginUrl;
  };

  logoLinks.forEach((logo) => {
    // Desktop: Double click pada logo
    logo.addEventListener("dblclick", redirectToAdminLogin);

    // Mobile / Touchscreen: Double tap (2 kali ketukan cepat di bawah 350ms)
    logo.addEventListener("touchend", (e) => {
      const currentTime = new Date().getTime();
      const tapInterval = currentTime - lastLogoTapTime;

      if (tapInterval > 0 && tapInterval < 350) {
        redirectToAdminLogin(e);
      }
      lastLogoTapTime = currentTime;
    });
  });
})();