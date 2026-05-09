<script setup>
import { useI18n } from 'vue-i18n'
import { ref, onMounted, computed } from 'vue'
import Navbar from '@/components/Navbar.vue'

const { locale } = useI18n()
const contactRoute = computed(() => locale.value === 'id' ? { name: 'contact-id' } : { name: 'contact-en' })
const activeCard = ref(null)
const isVisible = ref(false)

onMounted(() => {
  setTimeout(() => { isVisible.value = true }, 100)
})

const teams = [
  {
    id: 1,
    name: 'Ibu Dian Melani',
    title: { en: 'Chief Executive Officer', id: 'Direktur Utama' },
    image: '/img/ibu-dian-melani.jpeg',
    department: { en: 'Executive', id: 'Eksekutif' },
    social: { linkedin: 'https://www.linkedin.com/in/melani-dian-widiawati-m-psi-4a6b7a36/'},
    color: '#ea4435'
  },
  {
    id: 2,
    name: 'Muhammad Ariiq Fawwazi',
    title: { en: 'Quantity Surveyor', id: 'Survey Kuantitas' },
    image: '/img/arik.jpeg',
    social: { linkedin: 'https://id.linkedin.com/in/ariiqfawwazi'},
    color: '#1a1a2e'
  },
  {
    id: 3,
    name: 'Lely Hidayah Rohmah',
    title: { en: 'HR Generalist', id: 'Generalis HR' },
    image: '/img/Lely.jpeg',
    social: { linkedin: '#'},
    color: '#16213e'
  },
  {
    id: 4,
    name: 'Muhammad Syaifulloh',
    title: { en: 'Assesment Fullstack Developer', id: 'Pengembang Fullstack' },
    image: '/img/ipul.jpeg',
    social: { linkedin: 'https://www.linkedin.com/in/muhammad-syaifulloh-99a233305/'},
    color: '#0f3460'
  },
  {
    id: 5,
    name: 'Wahyu Nur A',
    title: { en: 'Intern Fullstack Developer', id: 'Magang Pengembang Fullstack' },
    image: '/img/wahyu.jpeg',
    social: { linkedin: 'https://www.linkedin.com/in/wahyu-nur-a7247923a?utm_source=share_via&utm_content=profile&utm_medium=member_android'},
    color: '#0f3460'
  }
]

const stats = [
  { value: '20+', label: { en: 'Years Experience', id: 'Tahun Pengalaman' } },
  { value: '100+', label: { en: 'Projects Done', id: 'Proyek Selesai' } },
  { value: '10+', label: { en: 'Expert Team', id: 'Tim Ahli' } },
  { value: '98%', label: { en: 'Client Satisfaction', id: 'Kepuasan Klien' } },
]
</script>

<template>
  <div class="page-wrapper">
    <Navbar />

    <!-- Hero Section -->
    <section class="hero-section">
      <div class="hero-bg-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
      </div>

      <div class="hero-content" :class="{ visible: isVisible }">
        <span class="hero-badge">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
          {{ locale === 'id' ? 'Tim Profesional Kami' : 'Our Professional Team' }}
        </span>

        <h1 class="hero-title">
          {{ locale === 'id' ? 'Kenali' : 'Meet' }}
          <span class="hero-title-highlight"> {{ locale === 'id' ? 'Tim Kami' : 'Our Team' }}</span>
        </h1>

        <p class="hero-desc">
          {{ locale === 'id'
            ? 'Di balik setiap proyek sukses, ada tim profesional yang berdedikasi tinggi. Kami berkomitmen memberikan standar layanan internasional terbaik untuk Anda.'
            : 'Behind every successful project is a highly dedicated professional team. We are committed to delivering the best international service standards for you.' }}
        </p>

        <div class="hero-scroll-hint">
          <span>{{ locale === 'id' ? 'Gulir ke bawah' : 'Scroll down' }}</span>
          <div class="scroll-line"></div>
        </div>
      </div>
    </section>

    <!-- Stats Bar -->
    <section class="stats-section">
      <div class="stats-container">
        <div v-for="(stat, i) in stats" :key="i" class="stat-item">
          <div class="stat-value">{{ stat.value }}</div>
          <div class="stat-label">{{ stat.label[locale] }}</div>
        </div>
      </div>
    </section>

    <!-- Team Grid -->
    <main class="team-section">
      <div class="team-container">

        <div class="section-header">
          <div class="section-line"></div>
          <span class="section-tag">{{ locale === 'id' ? 'Anggota Tim' : 'Team Members' }}</span>
          <div class="section-line"></div>
        </div>

        <div class="team-grid">
          <div
            v-for="(member, idx) in teams"
            :key="member.id"
            class="team-card"
            :class="{ active: activeCard === member.id }"
            :style="{ '--card-delay': `${idx * 0.12}s`, '--accent': member.color }"
            @mouseenter="activeCard = member.id"
            @mouseleave="activeCard = null"
          >
            <!-- Card Top Accent -->
            <div class="card-accent-bar"></div>

            <!-- Image Area -->
            <div class="card-image-wrap">
              <img
                :src="member.image"
                :alt="member.name"
                class="card-img"
                loading="lazy"
              />
              <div class="card-img-overlay"></div>

              <!-- Social Buttons (hover) -->
              <div class="card-socials">
                <a :href="member.social.linkedin" class="social-btn" title="LinkedIn" target="_blank">
                  <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                  </svg>
                </a>
              </div>
            </div>

            <!-- Card Body -->
            <div class="card-body">
              <div class="card-divider"></div>
              <h2 class="card-name">{{ member.name }}</h2>
              <p class="card-title">{{ member.title[locale] }}</p>

              <div class="card-footer-row">
                <div class="card-dots">
                  <span class="dot dot-active"></span>
                  <span class="dot"></span>
                  <span class="dot"></span>
                </div>
                <span class="card-arrow">
                  <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                  </svg>
                </span>
              </div>
            </div>
          </div>
        </div>

      </div>
    </main>

    <!-- CTA Banner -->
    <section class="cta-section">
      <div class="cta-glow"></div>
      <div class="cta-content">
        <h2 class="cta-title">
          {{ locale === 'id' ? 'Siap Bekerja Sama?' : 'Ready to Collaborate?' }}
        </h2>
        <p class="cta-desc">
          {{ locale === 'id'
            ? 'Tim kami siap membantu Anda mencapai tujuan bisnis dengan solusi terbaik.'
            : 'Our team is ready to help you achieve your business goals with the best solutions.' }}
        </p>
        <RouterLink :to="contactRoute" class="cta-btn">
          {{ locale === 'id' ? 'Hubungi Kami' : 'Contact Us' }}
          <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
          </svg>
        </RouterLink>
      </div>
    </section>
  </div>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800;900&family=Quicksand:wght@400;500;600;700&display=swap');

/* ===================== BASE ===================== */
.page-wrapper {
  min-height: 100vh;
  background: #f4f6fb;
  font-family: 'Quicksand', sans-serif;
  overflow-x: hidden;
}

/* ===================== HERO ===================== */
.hero-section {
  position: relative;
  min-height: 100vh;
  background: linear-gradient(135deg, #0d0d1a 0%, #1a1a2e 40%, #16213e 70%, #0f3460 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
  padding: 120px 24px 60px;
}

.hero-bg-shapes {
  position: absolute;
  inset: 0;
  pointer-events: none;
}

.shape {
  position: absolute;
  border-radius: 50%;
  filter: blur(80px);
  opacity: 0.35;
}
.shape-1 {
  width: 500px; height: 500px;
  background: radial-gradient(circle, #ea4435, transparent);
  top: -100px; left: -100px;
  animation: floatShape 8s ease-in-out infinite;
}
.shape-2 {
  width: 400px; height: 400px;
  background: radial-gradient(circle, #4f46e5, transparent);
  bottom: -80px; right: -80px;
  animation: floatShape 10s ease-in-out infinite reverse;
}
.shape-3 {
  width: 300px; height: 300px;
  background: radial-gradient(circle, #06b6d4, transparent);
  top: 50%; left: 50%;
  transform: translate(-50%, -50%);
  animation: floatShape 12s ease-in-out infinite 2s;
}

@keyframes floatShape {
  0%, 100% { transform: translateY(0) scale(1); }
  50% { transform: translateY(-30px) scale(1.08); }
}

.hero-content {
  position: relative;
  text-align: center;
  max-width: 780px;
  opacity: 0;
  transform: translateY(40px);
  transition: opacity 0.9s ease, transform 0.9s ease;
}
.hero-content.visible {
  opacity: 1;
  transform: translateY(0);
}

.hero-badge {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  background: rgba(234, 68, 53, 0.15);
  border: 1px solid rgba(234, 68, 53, 0.35);
  color: #f87171;
  font-family: 'Quicksand', sans-serif;
  font-weight: 600;
  font-size: 0.85rem;
  letter-spacing: 0.04em;
  padding: 8px 20px;
  border-radius: 100px;
  margin-bottom: 28px;
  backdrop-filter: blur(10px);
}

.hero-title {
  font-family: 'Montserrat', sans-serif;
  font-size: clamp(2.5rem, 7vw, 5.5rem);
  font-weight: 900;
  color: #fff;
  line-height: 1.05;
  margin-bottom: 24px;
  letter-spacing: -0.02em;
}

.hero-title-highlight {
  background: linear-gradient(90deg, #ea4435, #f97316);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.hero-desc {
  font-family: 'Quicksand', sans-serif;
  font-size: clamp(1rem, 2.5vw, 1.2rem);
  color: rgba(255,255,255,0.65);
  font-weight: 500;
  line-height: 1.8;
  max-width: 620px;
  margin: 0 auto 40px;
}

.hero-scroll-hint {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 12px;
  color: rgba(255,255,255,0.35);
  font-size: 0.75rem;
  font-weight: 600;
  letter-spacing: 0.1em;
  text-transform: uppercase;
}
.scroll-line {
  width: 1px;
  height: 50px;
  background: linear-gradient(to bottom, rgba(255,255,255,0.4), transparent);
  animation: scrollPulse 2s ease-in-out infinite;
}
@keyframes scrollPulse {
  0%, 100% { opacity: 0.4; transform: scaleY(1); }
  50% { opacity: 1; transform: scaleY(1.2); }
}

/* ===================== STATS ===================== */
.stats-section {
  background: #fff;
  box-shadow: 0 4px 30px rgba(0,0,0,0.06);
  position: relative;
  z-index: 10;
}

.stats-container {
  max-width: 1100px;
  margin: 0 auto;
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 0;
}

.stat-item {
  padding: 36px 24px;
  text-align: center;
  border-right: 1px solid #f0f0f0;
  transition: background 0.3s;
}
.stat-item:last-child { border-right: none; }
.stat-item:hover { background: #fef2f2; }

.stat-value {
  font-family: 'Montserrat', sans-serif;
  font-size: clamp(1.8rem, 4vw, 2.8rem);
  font-weight: 900;
  color: #ea4435;
  line-height: 1;
  margin-bottom: 6px;
}
.stat-label {
  font-family: 'Quicksand', sans-serif;
  font-size: 0.85rem;
  font-weight: 600;
  color: #94a3b8;
  text-transform: uppercase;
  letter-spacing: 0.06em;
}

/* ===================== TEAM SECTION ===================== */
.team-section {
  padding: 80px 24px 100px;
}

.team-container {
  max-width: 1200px;
  margin: 0 auto;
}

.section-header {
  display: flex;
  align-items: center;
  gap: 16px;
  margin-bottom: 56px;
  justify-content: center;
}
.section-line {
  flex: 1;
  max-width: 120px;
  height: 1.5px;
  background: linear-gradient(90deg, transparent, #e2e8f0);
}
.section-line:last-child {
  background: linear-gradient(90deg, #e2e8f0, transparent);
}
.section-tag {
  font-family: 'Quicksand', sans-serif;
  font-size: 0.8rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.15em;
  color: #ea4435;
  white-space: nowrap;
}

/* ===================== TEAM GRID ===================== */
.team-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 28px;
}

/* ===================== TEAM CARD ===================== */
.team-card {
  background: #fff;
  border-radius: 24px;
  overflow: hidden;
  box-shadow: 0 4px 20px rgba(0,0,0,0.06);
  transition: transform 0.45s cubic-bezier(0.34, 1.56, 0.64, 1),
              box-shadow 0.45s ease;
  cursor: pointer;
  animation: cardFadeIn 0.6s ease both;
  animation-delay: var(--card-delay, 0s);
  position: relative;
}
.team-card:hover {
  transform: translateY(-12px) scale(1.02);
  box-shadow: 0 24px 60px rgba(234, 68, 53, 0.15);
}

@keyframes cardFadeIn {
  from { opacity: 0; transform: translateY(30px); }
  to   { opacity: 1; transform: translateY(0); }
}

/* Top accent bar */
.card-accent-bar {
  height: 4px;
  background: linear-gradient(90deg, var(--accent, #ea4435), #f97316);
  transform: scaleX(0);
  transform-origin: left;
  transition: transform 0.4s ease;
}
.team-card:hover .card-accent-bar {
  transform: scaleX(1);
}

/* Image */
.card-image-wrap {
  position: relative;
  aspect-ratio: 3/3.5;
  overflow: hidden;
  background: #f1f5f9;
}
.card-img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  object-position: center top;
  transition: transform 0.7s ease;
}
.team-card:hover .card-img {
  transform: scale(1.08);
}

.card-img-overlay {
  position: absolute;
  inset: 0;
  background: linear-gradient(to top, rgba(10,10,30,0.85) 0%, rgba(10,10,30,0.2) 50%, transparent 100%);
  opacity: 0;
  transition: opacity 0.4s ease;
}
.team-card:hover .card-img-overlay {
  opacity: 1;
}

/* Department badge */
.dept-badge {
  position: absolute;
  top: 14px;
  right: 14px;
  background: rgba(255,255,255,0.15);
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255,255,255,0.25);
  color: #fff;
  font-family: 'Quicksand', sans-serif;
  font-size: 0.72rem;
  font-weight: 700;
  letter-spacing: 0.08em;
  text-transform: uppercase;
  padding: 5px 12px;
  border-radius: 100px;
  opacity: 0;
  transform: translateY(-8px);
  transition: opacity 0.4s ease 0.1s, transform 0.4s ease 0.1s;
}
.team-card:hover .dept-badge {
  opacity: 1;
  transform: translateY(0);
}

/* Socials */
.card-socials {
  position: absolute;
  bottom: 18px;
  left: 0; right: 0;
  display: flex;
  justify-content: center;
  gap: 10px;
  opacity: 0;
  transform: translateY(16px);
  transition: opacity 0.4s ease 0.1s, transform 0.4s ease 0.1s;
  z-index: 5;
}
.team-card:hover .card-socials {
  opacity: 1;
  transform: translateY(0);
}

.social-btn {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background: rgba(255,255,255,0.15);
  backdrop-filter: blur(12px);
  border: 1px solid rgba(255,255,255,0.3);
  display: flex;
  align-items: center;
  justify-content: center;
  color: #fff;
  text-decoration: none;
  transition: background 0.25s, transform 0.25s;
}
.social-btn:hover {
  background: #ea4435;
  transform: scale(1.15);
}

/* Card Body */
.card-body {
  padding: 20px 22px 22px;
}

.card-divider {
  width: 36px;
  height: 3px;
  background: linear-gradient(90deg, #ea4435, #f97316);
  border-radius: 4px;
  margin-bottom: 14px;
  transition: width 0.4s ease;
}
.team-card:hover .card-divider {
  width: 64px;
}

.card-name {
  font-family: 'Montserrat', sans-serif;
  font-size: 1.05rem;
  font-weight: 800;
  color: #1e293b;
  margin-bottom: 5px;
  transition: color 0.3s;
  letter-spacing: -0.01em;
}
.team-card:hover .card-name {
  color: #ea4435;
}

.card-title {
  font-family: 'Quicksand', sans-serif;
  font-size: 0.8rem;
  font-weight: 600;
  color: #94a3b8;
  text-transform: uppercase;
  letter-spacing: 0.07em;
  margin-bottom: 16px;
}

.card-footer-row {
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.card-dots { display: flex; gap: 5px; }
.dot {
  width: 7px; height: 7px;
  border-radius: 50%;
  background: #e2e8f0;
  transition: background 0.3s;
}
.dot-active { background: #ea4435; }

.card-arrow {
  width: 32px; height: 32px;
  border-radius: 50%;
  border: 1.5px solid #e2e8f0;
  display: flex; align-items: center; justify-content: center;
  color: #94a3b8;
  transition: all 0.3s;
}
.team-card:hover .card-arrow {
  background: #ea4435;
  border-color: #ea4435;
  color: #fff;
  transform: rotate(-45deg);
}

/* ===================== CTA ===================== */
.cta-section {
  position: relative;
  background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
  padding: 90px 24px;
  text-align: center;
  overflow: hidden;
}

.cta-glow {
  position: absolute;
  top: 50%; left: 50%;
  transform: translate(-50%, -50%);
  width: 600px; height: 300px;
  background: radial-gradient(ellipse, rgba(234,68,53,0.25), transparent 70%);
  pointer-events: none;
}

.cta-content {
  position: relative;
  max-width: 600px;
  margin: 0 auto;
}

.cta-title {
  font-family: 'Montserrat', sans-serif;
  font-size: clamp(1.8rem, 4vw, 3rem);
  font-weight: 900;
  color: #fff;
  margin-bottom: 16px;
  letter-spacing: -0.02em;
}

.cta-desc {
  font-family: 'Quicksand', sans-serif;
  font-size: 1.05rem;
  font-weight: 500;
  color: rgba(255,255,255,0.6);
  margin-bottom: 36px;
  line-height: 1.7;
}

.cta-btn {
  display: inline-flex;
  align-items: center;
  gap: 10px;
  background: linear-gradient(90deg, #ea4435, #f97316);
  color: #fff;
  font-family: 'Quicksand', sans-serif;
  font-weight: 700;
  font-size: 1rem;
  text-decoration: none;
  padding: 16px 36px;
  border-radius: 100px;
  transition: transform 0.3s, box-shadow 0.3s;
  box-shadow: 0 8px 30px rgba(234, 68, 53, 0.4);
  letter-spacing: 0.02em;
}
.cta-btn:hover {
  transform: translateY(-3px);
  box-shadow: 0 16px 40px rgba(234, 68, 53, 0.55);
}
.cta-btn svg {
  transition: transform 0.3s;
}
.cta-btn:hover svg {
  transform: translateX(4px);
}

/* ===================== RESPONSIVE ===================== */
@media (max-width: 1024px) {
  .team-grid { grid-template-columns: repeat(2, 1fr); }
  .stats-container { grid-template-columns: repeat(2, 1fr); }
  .stat-item:nth-child(2) { border-right: none; }
  .stat-item:nth-child(3) { border-top: 1px solid #f0f0f0; }
  .stat-item:nth-child(4) { border-top: 1px solid #f0f0f0; }
}

@media (max-width: 640px) {
  .hero-section { padding: 100px 20px 50px; }
  .team-section { padding: 56px 16px 70px; }
  .team-grid {
    grid-template-columns: 1fr;
    gap: 20px;
    max-width: 400px;
    margin: 0 auto;
  }
  .stats-container {
    grid-template-columns: repeat(2, 1fr);
  }
  .stat-item { padding: 24px 16px; }
  .card-image-wrap { aspect-ratio: 3/2.5; }

  /* On mobile, always show overlay elements */
  .dept-badge { opacity: 1; transform: translateY(0); }
  .card-socials { opacity: 1; transform: translateY(0); }
  .card-img-overlay { opacity: 1; }
  .card-accent-bar { transform: scaleX(1); }
}
</style>