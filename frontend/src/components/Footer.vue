<script setup>
import { useI18n } from 'vue-i18n'
import { computed } from 'vue'

const { t, locale } = useI18n()

const getLocalizedPath = (idPath, enPath) => {
  return locale.value === 'en' ? enPath : idPath
}

const navRoutes = computed(() => ({
  home: { path: getLocalizedPath('/id/beranda', '/en/home'), label: t('nav.home') },
  about: { path: getLocalizedPath('/id/tentang-kami', '/en/about'), label: t('nav.about') },
  contact: { path: getLocalizedPath('/id/kontak', '/en/contact'), label: t('nav.contact') }
}))

const layanan1Routes = computed(() => ({
  projects: { path: getLocalizedPath('/id/proyek-kami', '/en/projects'), label: t('nav.projects') },
  articles: { path: getLocalizedPath('/id/artikel', '/en/articles'), label: t('nav.articles') },
  gallery: { path: getLocalizedPath('/id/galeri', '/en/gallery'), label: t('nav.gallery') },
  clients: { path: getLocalizedPath('/id/klien', '/en/clients'), label: t('nav.clients') }
}))

const layanan2Routes = computed(() => ({
  'desain-interior': { path: getLocalizedPath('/id/interior-design', '/en/interior-design'), label: t('nav.interior_design') },
  assessment: { path: getLocalizedPath('/id/assessment', '/en/assessment'), label: t('nav.assessment') },
  'event-organizer': { path: getLocalizedPath('/id/event-organizer', '/en/event-organizer'), label: t('nav.event_organizer') },
  lms: { path: getLocalizedPath('/id/learning-management-system', '/en/learning-management-system'), label: t('nav.lms') },
  'it-multimedia': { path: getLocalizedPath('/id/it-dan-multimedia', '/en/it-and-multimedia'), label: t('nav.it_multimedia') },
  implementation: { path: getLocalizedPath('/id/implementasi', '/en/implementation'), label: 'implementation' }
}))
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
            <a href="https://www.instagram.com/cakrawala.parama?igsh=eHczc3hhYW82MWoy" target="_blank" rel="noopener noreferrer" class="social-btn" aria-label="Instagram">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
            </a>
            <a href="https://www.linkedin.com/in/pt-cakrawala-parama-internasional-b06182356" target="_blank" rel="noopener noreferrer" class="social-btn" aria-label="LinkedIn">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/><rect x="2" y="9" width="4" height="12"/><circle cx="4" cy="4" r="2"/></svg>
            </a>
          </div>
        </div>

        <!-- Navigation -->
        <div class="col-nav">
          <h4 class="col-title">{{ t('footer.nav_title') }}</h4>
          <ul class="nav-list">
            <li v-for="(item, key) in navRoutes" :key="key">
              <router-link :to="item.path" class="nav-link">
                {{ item.label }}
              </router-link>
            </li>
          </ul>
        </div>

        <!-- Layanan 1 -->
        <div class="col-nav">
          <h4 class="col-title">{{ t('nav.services') }}</h4>
          <ul class="nav-list">
            <li v-for="(item, key) in layanan1Routes" :key="key">
              <router-link :to="item.path" class="nav-link">
                {{ item.label }}
              </router-link>
            </li>
          </ul>
        </div>

        <!-- Layanan 2 -->
        <div class="col-nav">
          <h4 class="col-title">{{ t('nav.services') }}</h4>
          <ul class="nav-list">
            <li v-for="(item, key) in layanan2Routes" :key="key">
              <router-link :to="item.path" class="nav-link">
                {{ item.label }}
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

          <!-- Map directly without hover -->
          <div class="map-wrap">
            <iframe
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

/* ── Shell ── */
.footer {
  background: linear-gradient(135deg, #ED0226 0%, #FF6A00 100%);
  position: relative;
  overflow: hidden;
  color: #ffffff;
  
  --surface: rgba(255, 255, 255, 0.1);
  --border: rgba(255, 255, 255, 0.2);
  --text-muted: rgba(255, 255, 255, 0.92);
  --text-body: #ffffff;
  --text-white: #ffffff;
  --radius: 12px;
}

.font-montserrat { font-family: 'Montserrat', sans-serif; }

/* Subtle radial glow */
.footer::before {
  content: '';
  position: absolute;
  bottom: -120px;
  right: -80px;
  width: 480px;
  height: 480px;
  background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 70%);
  pointer-events: none;
}

/* Accent line at top */
.accent-bar {
  height: 2px;
  background: rgba(255,255,255,0.3);
}

.footer-inner {
  max-width: 1200px;
  margin: 0 auto;
  padding: 64px 24px 32px;
}

/* ── Grid ── */
.footer-grid {
  display: flex;
  flex-wrap: wrap;
  gap: 32px;
  margin-bottom: 48px;
}

.col-brand { flex: 1.5; min-width: 250px; }
.col-nav { flex: 1; min-width: 120px; }
.col-location { flex: 1.5; min-width: 250px; }

/* ── Brand col ── */
.brand-logo {
  height: 48px;
  object-fit: contain;
  display: block;
  margin-bottom: 16px;
  filter: brightness(0) invert(1);
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
  color: var(--text-white);
  background: var(--surface);
  transition: background 0.2s, transform 0.2s;
  text-decoration: none;
}

.social-btn:hover {
  background: rgba(255,255,255,0.24);
  transform: translateY(-2px);
}

/* ── Col titles ── */
.col-title {
  font-size: 12px;
  font-weight: 700;
  letter-spacing: 0.1em;
  text-transform: uppercase;
  color: var(--text-white);
  margin: 0 0 20px;
}

/* ── Nav list ── */
.nav-list {
  list-style: none;
  margin: 0;
  padding: 0;
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.nav-link {
  display: inline-block;
  font-size: 13px;
  font-weight: 500;
  color: var(--text-body);
  text-decoration: none;
  transition: color 0.2s, transform 0.2s;
}

.nav-link:hover { 
  color: var(--text-white); 
  transform: translateX(4px);
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
  color: var(--text-white);
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
}

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

.legal-link:hover { color: var(--text-white); }

.divider {
  color: var(--border);
  font-size: 14px;
}
</style>
