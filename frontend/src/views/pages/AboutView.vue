<script setup>
import AboutSection from '../section/AboutSection.vue'
</script>

<template>
  <div class="page-wrapper">

    <AboutSection :showButton="false" />

    <section class="content-wrap">
      <div class="card">

        <!-- Header -->
        <div class="card-header">
          <div class="header-accent"></div>
          <div>
            <h1 class="card-title">Visi &amp; Misi <span class="text-orange">Perusahaan</span></h1>
            <div class="badge">
              <span class="badge-dot"></span>
              Company Foundation
            </div>
          </div>
        </div>

        <!-- Body -->
        <div class="card-body">

          <!-- Visi -->
          <div class="section-block">
            <div class="section-icon-row">
              <div class="icon-box">
                <svg class="icon-svg" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                  <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zm0 12.5a5 5 0 1 1 0-10 5 5 0 0 1 0 10zm0-8a3 3 0 1 0 0 6 3 3 0 0 0 0-6z"/>
                </svg>
              </div>
              <span class="section-label">Visi Kami</span>
            </div>
            <p class="section-text">
              Menjadi perusahaan terdepan berskala internasional yang memberikan solusi komprehensif
              dan inovatif, serta menciptakan nilai tambah bagi seluruh pemangku kepentingan.
            </p>
          </div>

          <!-- Misi -->
          <div class="section-block">
            <div class="section-icon-row">
              <div class="icon-box">
                <svg class="icon-svg" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                  <path d="M12 2a10 10 0 1 0 0 20A10 10 0 0 0 12 2zm0 18a8 8 0 1 1 0-16 8 8 0 0 1 0 16zm0-12a4 4 0 1 0 0 8 4 4 0 0 0 0-8zm0 6a2 2 0 1 1 0-4 2 2 0 0 1 0 4z"/>
                </svg>
              </div>
              <span class="section-label">Misi Kami</span>
            </div>
            <ul class="misi-list">
              <li>
                <span class="li-num">01</span>
                Memberikan layanan dan produk berkualitas tinggi yang melebihi ekspektasi pelanggan.
              </li>
              <li>
                <span class="li-num">02</span>
                Mengembangkan inovasi berkelanjutan dalam setiap aspek operasional perusahaan.
              </li>
              <li>
                <span class="li-num">03</span>
                Membangun kemitraan strategis yang saling menguntungkan.
              </li>
            </ul>
          </div>

        </div>

        <!-- Footer -->
        <div class="card-footer">
          <span class="footer-text">© 2026 Perusahaan · All rights reserved</span>
          <div class="footer-dots">
            <div class="dot dot-1"></div>
            <div class="dot dot-2"></div>
            <div class="dot dot-3"></div>
          </div>
        </div>

      </div>
    </section>
  </div>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:ital,wght@0,300;0,400;0,500;1,300&display=swap');

:root {
  --orange: #f97316;
  --orange-dark: #ea6005;
  --orange-light: #fed7aa;
}

/* Grid background */
.page-wrapper {
  position: relative;
  background-color: #fff8f4;
  background-image:
    linear-gradient(rgba(249,115,22,0.08) 1px, transparent 1px),
    linear-gradient(90deg, rgba(249,115,22,0.08) 1px, transparent 1px);
  background-size: 40px 40px;
  min-height: 100vh;
  font-family: 'DM Sans', sans-serif;
}

.content-wrap {
  max-width: 860px;
  margin: 0 auto;
  padding: 48px 24px 80px;
}

/* Card */
.card {
  background: rgba(255,255,255,0.85);
  backdrop-filter: blur(12px);
  border-radius: 28px;
  border: 1px solid rgba(249,115,22,0.15);
  box-shadow: 0 8px 40px rgba(249,115,22,0.08), 0 1px 3px rgba(0,0,0,0.04);
  overflow: hidden;
  transition: box-shadow 0.3s ease;
  animation: slideUp 0.5s cubic-bezier(0.22,1,0.36,1) both;
}
.card:hover {
  box-shadow: 0 16px 60px rgba(249,115,22,0.14), 0 2px 8px rgba(0,0,0,0.06);
}

/* Card Header */
.card-header {
  padding: 40px 48px 32px;
  border-bottom: 1px solid rgba(249,115,22,0.10);
  display: flex;
  align-items: flex-start;
  gap: 20px;
}
.header-accent {
  width: 5px;
  min-height: 56px;
  background: linear-gradient(180deg, #f97316 0%, #fed7aa 100%);
  border-radius: 99px;
  flex-shrink: 0;
  margin-top: 4px;
}
.card-title {
  font-family: 'Syne', sans-serif;
  font-weight: 800;
  font-size: clamp(26px, 4vw, 38px);
  color: #111111;
  letter-spacing: -0.03em;
  line-height: 1.1;
}
.text-orange { color: #f97316; }

.badge {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  background: rgba(249,115,22,0.08);
  color: #ea6005;
  font-size: 11px;
  font-weight: 600;
  letter-spacing: 0.08em;
  text-transform: uppercase;
  padding: 4px 12px;
  border-radius: 99px;
  margin-top: 10px;
  border: 1px solid rgba(249,115,22,0.2);
}
.badge-dot {
  width: 6px; height: 6px;
  background: #f97316;
  border-radius: 50%;
  animation: pulse 2s infinite;
}

/* Section Blocks */
.section-block {
  padding: 36px 48px;
  transition: background 0.25s ease;
  animation: fadeIn 0.4s both;
}
.section-block:not(:last-child) {
  border-bottom: 1px solid rgba(249,115,22,0.08);
}
.section-block:nth-child(1) { animation-delay: 0.15s; }
.section-block:nth-child(2) { animation-delay: 0.28s; }
.section-block:hover { background: rgba(249,115,22,0.02); }

.section-icon-row {
  display: flex;
  align-items: center;
  gap: 14px;
  margin-bottom: 16px;
}
.icon-box {
  width: 42px; height: 42px;
  border-radius: 12px;
  background: linear-gradient(135deg, #f97316 0%, #fb923c 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
  box-shadow: 0 4px 14px rgba(249,115,22,0.3);
}
.icon-svg { width: 20px; height: 20px; fill: white; }
.section-label {
  font-family: 'Syne', sans-serif;
  font-size: 18px;
  font-weight: 700;
  color: #111111;
  letter-spacing: -0.01em;
}
.section-text {
  font-size: 15px;
  color: #4b5563;
  line-height: 1.75;
  padding-left: 56px;
}

/* Misi List */
.misi-list {
  list-style: none;
  padding-left: 56px;
  display: flex;
  flex-direction: column;
  gap: 12px;
}
.misi-list li {
  display: flex;
  align-items: flex-start;
  gap: 12px;
  font-size: 15px;
  color: #4b5563;
  line-height: 1.7;
  padding: 14px 18px;
  background: rgba(249,115,22,0.035);
  border-radius: 12px;
  border: 1px solid rgba(249,115,22,0.08);
  transition: border-color 0.2s, background 0.2s, transform 0.2s;
  cursor: default;
}
.misi-list li:hover {
  border-color: rgba(249,115,22,0.25);
  background: rgba(249,115,22,0.07);
  transform: translateX(4px);
}
.li-num {
  font-family: 'Syne', sans-serif;
  font-weight: 800;
  font-size: 13px;
  color: #f97316;
  background: rgba(249,115,22,0.1);
  border-radius: 6px;
  min-width: 26px; height: 26px;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
  margin-top: 1px;
}

/* Footer */
.card-footer {
  padding: 20px 48px;
  background: rgba(249,115,22,0.04);
  border-top: 1px solid rgba(249,115,22,0.10);
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 12px;
  flex-wrap: wrap;
}
.footer-text { font-size: 12px; color: #9ca3af; letter-spacing: 0.02em; }
.footer-dots { display: flex; gap: 6px; }
.dot { width: 8px; height: 8px; border-radius: 50%; }
.dot-1 { background: #f97316; }
.dot-2 { background: #fb923c; }
.dot-3 { background: #fed7aa; }

/* Animations */
@keyframes slideUp {
  from { opacity: 0; transform: translateY(28px); }
  to   { opacity: 1; transform: translateY(0); }
}
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(12px); }
  to   { opacity: 1; transform: translateY(0); }
}
@keyframes pulse {
  0%, 100% { opacity: 1; transform: scale(1); }
  50%       { opacity: 0.5; transform: scale(0.85); }
}

@media (max-width: 640px) {
  .card-header, .section-block, .card-footer { padding-left: 24px; padding-right: 24px; }
  .section-text, .misi-list { padding-left: 0; }
}
</style>