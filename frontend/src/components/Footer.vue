<script setup>
import { useI18n } from 'vue-i18n'
import { computed, ref } from 'vue'

const { t, locale } = useI18n()

const getLocalizedPath = (idPath, enPath) => {
  return locale.value === 'en' ? enPath : idPath
}

const routes = computed(() => ({
  home: getLocalizedPath('/id/beranda', '/en/home'),
  about: getLocalizedPath('/id/tentang-kami', '/en/about'),
  services: getLocalizedPath('/id/layanan', '/en/services'),
  projects: getLocalizedPath('/id/proyek-kami', '/en/projects'),
  contact: getLocalizedPath('/id/kontak', '/en/contact')
}))

const hoveredLink = ref(null)
const mapLoaded = ref(false)
</script>

<template>
  <footer class="footer font-montserrat">

    <!-- Top accent line -->
    <div class="accent-bar"></div>

    <div class="footer-inner">

      <!-- ── MAIN GRID ── -->
      <div class="footer-grid">

        <!-- Brand -->
        <div class="col-brand">
          <img src="/webcakrawala-logo.png" alt="PT Cakrawala Parama Internasional" class="brand-logo" />
          <p class="brand-desc">{{ t('footer.about_desc') }}</p>

          <!-- Social -->
          <div class="socials">
            <a href="#" class="social-btn" aria-label="Facebook">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
            </a>
            <a href="#" class="social-btn" aria-label="Instagram">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
            </a>
            <a href="#" class="social-btn" aria-label="LinkedIn">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/><rect x="2" y="9" width="4" height="12"/><circle cx="4" cy="4" r="2"/></svg>
            </a>
            <a href="#" class="social-btn" aria-label="YouTube">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33 2.78 2.78 0 0 0 1.94 2C5.12 19.5 12 19.5 12 19.5s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.33 29 29 0 0 0-.46-5.33z"/><polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02"/></svg>
            </a>
          </div>
        </div>

        <!-- Navigation -->
        <div class="col-nav">
          <h4 class="col-title">{{ t('footer.nav_title') }}</h4>
          <ul class="nav-list">
            <li v-for="(path, key) in routes" :key="key">
              <router-link
                :to="path"
                class="nav-link"
                @mouseenter="hoveredLink = key"
                @mouseleave="hoveredLink = null"
              >
                <span class="nav-dot" :class="{ active: hoveredLink === key }"></span>
                {{ t(`footer.nav_${key}`) }}
              </router-link>
            </li>
          </ul>
        </div>

        <!-- Location -->
        <div class="col-location">
          <h4 class="col-title">{{ t('footer.headquarters') }}</h4>
          <address class="address-block">
            <div class="address-icon">
              <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
            </div>
            <span>
              Thamrin City, Jl. Kebon Kacang Raya Lantai 2 Blok C9a No.5 Kb. Melati,
              Kecamatan Tanah Abang, Kota Jakarta Pusat, DKI Jakarta 10230
            </span>
          </address>

          <!-- Map -->
          <div class="map-wrap" @mouseenter="mapLoaded = true">
            <div v-if="!mapLoaded" class="map-placeholder">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
              <span>Hover to load map</span>
            </div>
            <iframe
              v-if="mapLoaded"
              src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.47192667104!2d106.8159193!3d-6.1951556!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f4262cc3c7d1%3A0xc62da2160938fdd3!2sThamrin%20City!5e0!3m2!1sen!2sid!4v1700000000000"
              width="100%" height="100%"
              style="border:0;"
              allowfullscreen=""
              loading="lazy"
              referrerpolicy="no-referrer-when-downgrade"
            ></iframe>
          </div>
        </div>

      </div>

      <!-- ── BOTTOM BAR ── -->
      <div class="footer-bottom">
        <p class="copyright">{{ t('footer.copyright') }}</p>
        <div class="legal-links">
          <router-link to="/privacy-policy" class="legal-link">{{ t('footer.privacy_policy') }}</router-link>
          <span class="divider">·</span>
          <router-link to="/terms-of-service" class="legal-link">{{ t('footer.terms_of_service') }}</router-link>
        </div>
      </div>

    </div>
  </footer>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap');

/* ── Tokens ── */
:root {
  --orange: #ea580c;
  --orange-dim: rgba(234, 88, 12, 0.12);
  --bg: #020617;
  --surface: #0f172a;
  --border: rgba(255,255,255,0.07);
  --text-muted: #64748b;
  --text-body: #94a3b8;
  --text-white: #f1f5f9;
  --radius: 12px;
}

.font-montserrat { font-family: 'Montserrat', sans-serif; }

/* ── Shell ── */
.footer {
  background: var(--bg);
  position: relative;
  overflow: hidden;
}

/* Subtle radial glow */
.footer::before {
  content: '';
  position: absolute;
  bottom: -120px;
  right: -80px;
  width: 480px;
  height: 480px;
  background: radial-gradient(circle, rgba(234,88,12,0.08) 0%, transparent 70%);
  pointer-events: none;
}

/* Accent line at top */
.accent-bar {
  height: 2px;
  background: linear-gradient(90deg, transparent 0%, var(--orange) 40%, transparent 100%);
  opacity: 0.7;
}

.footer-inner {
  max-width: 1200px;
  margin: 0 auto;
  padding: 64px 24px 32px;
}

/* ── Grid ── */
.footer-grid {
  display: grid;
  grid-template-columns: 1.4fr 0.8fr 1.2fr;
  gap: 48px;
  margin-bottom: 48px;
}

@media (max-width: 900px) {
  .footer-grid {
    grid-template-columns: 1fr 1fr;
    gap: 36px;
  }
  .col-brand { grid-column: 1 / -1; }
}

@media (max-width: 560px) {
  .footer-grid {
    grid-template-columns: 1fr;
    gap: 32px;
  }
}

/* ── Brand col ── */
.brand-logo {
  height: 48px;
  object-fit: contain;
  display: block;
  margin-bottom: 16px;
}

.brand-desc {
  font-size: 13px;
  line-height: 1.75;
  color: var(--text-body);
  max-width: 300px;
  margin-bottom: 20px;
}

/* ── Socials ── */
.socials {
  display: flex;
  gap: 10px;
}

.social-btn {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 36px;
  height: 36px;
  border-radius: 8px;
  border: 1px solid var(--border);
  color: var(--text-muted);
  background: var(--surface);
  transition: background 0.2s, border-color 0.2s, color 0.2s, transform 0.2s;
  text-decoration: none;
}

.social-btn:hover {
  background: var(--orange);
  border-color: var(--orange);
  color: #fff;
  transform: translateY(-2px);
}

/* ── Col titles ── */
.col-title {
  font-size: 11px;
  font-weight: 700;
  letter-spacing: 0.12em;
  text-transform: uppercase;
  color: var(--orange);
  margin: 0 0 20px;
}

/* ── Nav list ── */
.nav-list {
  list-style: none;
  margin: 0;
  padding: 0;
  display: flex;
  flex-direction: column;
  gap: 4px;
}

.nav-link {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 13px;
  font-weight: 500;
  color: var(--text-body);
  text-decoration: none;
  padding: 6px 0;
  transition: color 0.2s;
}

.nav-link:hover { color: var(--text-white); }

.nav-dot {
  width: 4px;
  height: 4px;
  border-radius: 50%;
  background: var(--orange);
  opacity: 0;
  transform: scale(0);
  transition: opacity 0.2s, transform 0.2s;
  flex-shrink: 0;
}

.nav-dot.active {
  opacity: 1;
  transform: scale(1);
}

/* ── Location ── */
.address-block {
  display: flex;
  gap: 10px;
  font-style: normal;
  font-size: 13px;
  line-height: 1.7;
  color: var(--text-body);
  margin-bottom: 16px;
}

.address-icon {
  color: var(--orange);
  flex-shrink: 0;
  margin-top: 2px;
}

/* ── Map ── */
.map-wrap {
  width: 100%;
  height: 140px;
  border-radius: var(--radius);
  overflow: hidden;
  border: 1px solid var(--border);
  background: var(--surface);
  cursor: pointer;
  transition: border-color 0.2s;
}

.map-wrap:hover { border-color: var(--orange); }

.map-placeholder {
  width: 100%;
  height: 100%;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 8px;
  color: var(--text-muted);
  font-size: 12px;
  font-weight: 500;
  letter-spacing: 0.04em;
}

.map-placeholder svg { opacity: 0.5; }

/* ── Bottom bar ── */
.footer-bottom {
  border-top: 1px solid var(--border);
  padding-top: 24px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
  gap: 12px;
}

.copyright {
  font-size: 12px;
  color: var(--text-muted);
  letter-spacing: 0.03em;
}

.legal-links {
  display: flex;
  align-items: center;
  gap: 10px;
}

.legal-link {
  font-size: 12px;
  color: var(--text-muted);
  text-decoration: none;
  transition: color 0.2s;
}

.legal-link:hover { color: var(--orange); }

.divider {
  color: var(--border);
  font-size: 14px;
}
</style>