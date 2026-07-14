<script setup>
import { useI18n } from 'vue-i18n'
import { ref } from 'vue'
import { Swiper, SwiperSlide } from 'swiper/vue'
import { Autoplay, Pagination } from 'swiper/modules'
import 'swiper/css'
import 'swiper/css/pagination'

const { locale } = useI18n()
const activeCard = ref(null)

const teams = [
  {
    id: 2,
    name: 'Ariel Mohammad Rasky',
    title: { en: 'Event Organizer Specialist', id: 'Event Organizer Spesialis' },
    image: '/img/Ariel-Mohammad-Rasky.webp',
    social: { linkedin: 'https://www.linkedin.com/in/arielmohammadrasky' },
    color: '#1a1a2e'
  },
  {
    id: 3,
    name: 'Tri Apriliani Lestari',
    title: { en: 'Event Organizer Multimedia', id: 'Event Organizer Multimedia' },
    image: '/img/Tri-Apriliani-Lestari.webp',
    social: { linkedin: 'https://www.linkedin.com/in/tri-apriliani-4b8016251' },
    color: '#1a1a2e'
  },
  {
    id: 4,
    name: 'Lely Hidayah Rohmah',
    title: { en: 'Human Capital Specialist', id: 'Spesialis Sumber Daya Manusia' },
    image: '/img/Lely.webp',
    social: { linkedin: '#'},
    color: '#16213e'
  },
  {
    id: 5,
    name: 'Diah Sartika Azah',
    title: { en: 'Interior Design', id: ' Desain Interior' },
    image: '/img/azah.webp',
    social: { linkedin: 'https://www.linkedin.com/in/diah-sartika-azah-0304881b4/'},
    color: '#16213e'
  },
  {
    id: 6,
    name: 'Muhammad Syaifulloh',
    title: { en: 'IT (FULLSTACK DEVELOPER)', id: 'IT (Pengembang Fullstack)' },
    image: '/img/ipul.webp',
    social: { linkedin: 'https://id.linkedin.com/in/muhammad-syaifulloh-99a233305?trk=people-guest_people_search-card'},
    color: '#0f3460'
  },
  // {
  //   id: 7,
  //   name: 'Muhammad Azzam',
  //   title: { en: 'Learning and Development Specialist', id: 'Spesialis Pembelajaran dan Pengembangan' },
  //   image: '/img/azzam.webp',
  //   social: { linkedin: 'https://www.linkedin.com/in/muhammad-syaifulloh-99a233305/'},
  //   color: '#0f3460'
  // },
  {
    id: 8,
    name: 'Wahyu Nur A',
    title: { en: 'IT (FULLSTACK DEVELOPER)', id: 'IT (Pengembang Fullstack)' },
    image: '/img/wahyu.webp',
    social: { linkedin: 'https://www.linkedin.com/in/wahyu-nur-a7247923a?utm_source=share_via&utm_content=profile&utm_medium=member_android'},
    color: '#0f3460'
  }
]
</script>

<template>
  <section id="teams" class="team-section">
    <div class="team-container max-w-7xl mx-auto px-6">

      <div class="section-header">
        <div class="section-line"></div>
        <span class="section-tag">{{ locale === 'id' ? 'Tim Kami' : 'Our Team' }}</span>
        <div class="section-line"></div>
      </div>

      <div class="team-swiper-wrapper">
        <Swiper
          :modules="[Autoplay, Pagination]"
          :slides-per-view="1"
          :space-between="20"
          :loop="true"
          :autoplay="{
            delay: 3000,
            disableOnInteraction: false,
            pauseOnMouseEnter: true
          }"
          :pagination="{ clickable: true }"
          :breakpoints="{
            480: { slidesPerView: 1.5, spaceBetween: 20 },
            640: { slidesPerView: 2, spaceBetween: 20 },
            768: { slidesPerView: 3, spaceBetween: 24 },
            1024: { slidesPerView: 4, spaceBetween: 28 }
          }"
          class="team-swiper"
        >
          <SwiperSlide
            v-for="(member, idx) in teams"
            :key="member.id"
            class="team-swiper-slide"
          >
            <div
              class="team-card"
              :class="{ active: activeCard === member.id }"
              :style="{ '--accent': member.color }"
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
          </SwiperSlide>
        </Swiper>
      </div>

    </div>
  </section>
</template>

<style scoped>

.team-section {
  padding: 40px 0 80px;
  background: white;
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
  font-family: 'Montserrat', sans-serif;
  font-size: 2rem;
  font-weight: 900;
  color: #0f172a;
  white-space: nowrap;
}

/* ===================== TEAM SWIPER ===================== */
.team-swiper-wrapper {
  position: relative;
  width: 100%;
}

.team-swiper {
  padding: 20px 8px 60px !important;
}

.team-swiper-slide {
  height: auto;
  display: flex;
  justify-content: center;
  align-items: stretch;
}

/* ===================== TEAM CARD ===================== */
.team-card {
  width: 100%;
  max-width: 250px; /* Reduced container size of the image/card */
  background: #fff;
  border-radius: 24px;
  overflow: hidden;
  box-shadow: 0 4px 20px rgba(0,0,0,0.06);
  transition: transform 0.45s cubic-bezier(0.34, 1.56, 0.64, 1),
              box-shadow 0.45s ease;
  cursor: pointer;
  position: relative;
  border: 1px solid #f1f5f9;
  display: flex;
  flex-direction: column;
}
.team-card:hover {
  transform: translateY(-12px) scale(1.02);
  box-shadow: 0 24px 60px rgba(234, 68, 53, 0.15);
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
  aspect-ratio: 3/4;
  overflow: hidden;
  background: #f1f5f9;
  width: 100%;
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
  flex-grow: 1;
  display: flex;
  flex-direction: column;
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
  min-height: 2.4rem;
}

.card-footer-row {
  margin-top: auto;
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

/* ===================== SWIPER PAGINATION ===================== */
:deep(.swiper-pagination-bullet) {
  background: #cbd5e1 !important;
  opacity: 1;
  width: 8px;
  height: 8px;
  border-radius: 4px;
  transition: all 0.3s ease;
}

:deep(.swiper-pagination-bullet-active) {
  background: #ea4435 !important;
  width: 24px;
}

@media (max-width: 768px) {
  .section-tag { font-size: 1.5rem; }
}

@media (max-width: 640px) {
  /* On mobile, always show overlay elements */
  .card-socials { opacity: 1; transform: translateY(0); }
  .card-img-overlay { opacity: 1; }
  .card-accent-bar { transform: scaleX(1); }
}
</style>
