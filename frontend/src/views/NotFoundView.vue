<template>
  <div class="not-found">
    <!-- Animated background grid -->
    <div class="bg-grid" />

    <!-- Ambient glow blobs -->
    <div class="blob blob-1" />
    <div class="blob blob-2" />

    <!-- Floating particles -->
    <div class="particles">
      <span
        v-for="p in particles"
        :key="p.id"
        class="particle"
        :style="p.style"
      />
    </div>

    <!-- Main content -->
    <div class="container">
      <!-- 404 glitch number -->
      <div class="number-wrap">
        <h1 class="number" data-text="404">404</h1>
      </div>

      <!-- Divider accent -->
      <div class="divider">
        <span class="divider-line" />
        <span class="divider-dot" />
        <span class="divider-line" />
      </div>

      <h2 class="title">Halaman Tidak Ditemukan</h2>
      <p class="subtitle">
        Maaf, halaman yang Anda cari tidak ada atau telah dipindahkan ke lokasi lain.
      </p>

      <div class="btn-wrap">
        <router-link to="/" class="btn btn-primary">
          <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
            <polyline points="9 22 9 12 15 12 15 22" />
          </svg>
          Kembali ke Beranda
        </router-link>

        <button class="btn btn-ghost" @click="$router.go(-1)">
          <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <polyline points="15 18 9 12 15 6" />
          </svg>
          Kembali
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

// Generate random floating particles
const particles = ref(
  Array.from({ length: 18 }, (_, i) => ({
    id: i,
    style: {
      left: `${Math.random() * 100}vw`,
      width: `${2 + Math.random() * 4}px`,
      height: `${2 + Math.random() * 4}px`,
      animationDuration: `${6 + Math.random() * 12}s`,
      animationDelay: `${Math.random() * 10}s`,
    },
  }))
)
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600;700;900&display=swap');

/* ── Variables ─────────────────────────────────────────── */
.not-found {
  --orange:       #ea580c;
  --orange-light: #fb923c;
  --orange-glow:  rgba(234, 88, 12, 0.18);
  --bg:           #0f0f0f;
  --text:         #f5f5f5;
  --muted:        #888;

  font-family: 'Montserrat', sans-serif;
  background: var(--bg);
  color: var(--text);
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
  position: relative;
}

/* ── Background grid ───────────────────────────────────── */
.bg-grid {
  position: fixed;
  inset: 0;
  z-index: 0;
  background-image:
    linear-gradient(rgba(234, 88, 12, 0.06) 1px, transparent 1px),
    linear-gradient(90deg, rgba(234, 88, 12, 0.06) 1px, transparent 1px);
  background-size: 48px 48px;
  animation: gridMove 20s linear infinite;
  pointer-events: none;
}
@keyframes gridMove {
  0%   { transform: translateY(0); }
  100% { transform: translateY(48px); }
}

/* ── Glow blobs ────────────────────────────────────────── */
.blob {
  position: fixed;
  border-radius: 50%;
  filter: blur(90px);
  opacity: 0.25;
  z-index: 0;
  pointer-events: none;
  animation: blobFloat 8s ease-in-out infinite alternate;
}
.blob-1 {
  width: 380px; height: 380px;
  background: var(--orange);
  top: -80px; right: -60px;
  animation-delay: 0s;
}
.blob-2 {
  width: 260px; height: 260px;
  background: var(--orange-light);
  bottom: -60px; left: -40px;
  animation-delay: -4s;
}
@keyframes blobFloat {
  from { transform: translate(0, 0) scale(1); }
  to   { transform: translate(30px, 20px) scale(1.1); }
}

/* ── Particles ─────────────────────────────────────────── */
.particles {
  position: fixed;
  inset: 0;
  z-index: 0;
  pointer-events: none;
  overflow: hidden;
}
.particle {
  position: absolute;
  background: var(--orange);
  border-radius: 50%;
  opacity: 0;
  animation: particleFloat linear infinite;
}
@keyframes particleFloat {
  0%   { opacity: 0;   transform: translateY(100vh) scale(0); }
  10%  { opacity: 0.6; }
  90%  { opacity: 0.2; }
  100% { opacity: 0;   transform: translateY(-10vh) scale(1.5); }
}

/* ── Container ─────────────────────────────────────────── */
.container {
  position: relative;
  z-index: 1;
  text-align: center;
  padding: 2rem 1.5rem;
  max-width: 600px;
  width: 100%;
  animation: fadeUp 0.8s cubic-bezier(0.22, 0.68, 0, 1.2) both;
}
@keyframes fadeUp {
  from { opacity: 0; transform: translateY(40px); }
  to   { opacity: 1; transform: translateY(0); }
}

/* ── 404 glitch number ─────────────────────────────────── */
.number-wrap {
  display: inline-block;
  margin-bottom: 0.5rem;
}
.number {
  font-size: clamp(7rem, 22vw, 13rem);
  font-weight: 900;
  letter-spacing: -0.04em;
  line-height: 1;
  color: transparent;
  -webkit-text-stroke: 2px var(--orange);
  position: relative;
  user-select: none;
  animation: glitch 5s ease-in-out infinite;
}
.number::before,
.number::after {
  content: attr(data-text);
  position: absolute;
  top: 0; left: 0;
  width: 100%;
  color: transparent;
  -webkit-text-stroke: 2px var(--orange);
}
.number::before {
  color: var(--orange);
  opacity: 0.12;
  clip-path: inset(0 0 60% 0);
  animation: glitchTop 5s ease-in-out infinite;
}
.number::after {
  color: var(--orange-light);
  opacity: 0.1;
  clip-path: inset(60% 0 0 0);
  animation: glitchBot 5s ease-in-out infinite;
}
@keyframes glitch {
  0%, 90%, 100% { transform: translate(0); }
  92% { transform: translate(-3px,  1px); }
  94% { transform: translate( 3px, -1px); }
  96% { transform: translate(-2px,  2px); }
}
@keyframes glitchTop {
  0%, 90%, 100% { transform: translate(0); }
  92% { transform: translate( 4px, 0); }
  94% { transform: translate(-4px, 0); }
}
@keyframes glitchBot {
  0%, 90%, 100% { transform: translate(0); }
  92% { transform: translate(-4px, 0); }
  94% { transform: translate( 4px, 0); }
}

/* ── Divider accent ────────────────────────────────────── */
.divider {
  display: flex;
  align-items: center;
  gap: 12px;
  justify-content: center;
  margin: 0.75rem 0 1.5rem;
}
.divider-line {
  height: 2px;
  width: 48px;
  background: var(--orange);
  border-radius: 2px;
  animation: expandLine 0.8s 0.5s cubic-bezier(0.22, 0.68, 0, 1.2) both;
}
@keyframes expandLine {
  from { width: 0; opacity: 0; }
  to   { width: 48px; opacity: 1; }
}
.divider-dot {
  width: 8px; height: 8px;
  border-radius: 50%;
  background: var(--orange);
}

/* ── Typography ────────────────────────────────────────── */
.title {
  font-size: clamp(1.2rem, 4vw, 1.7rem);
  font-weight: 700;
  letter-spacing: -0.01em;
  margin-bottom: 0.75rem;
  animation: fadeUp 0.8s 0.2s both;
}
.subtitle {
  font-size: clamp(0.85rem, 2.5vw, 1rem);
  color: var(--muted);
  font-weight: 400;
  line-height: 1.6;
  max-width: 380px;
  margin: 0 auto 2.5rem;
  animation: fadeUp 0.8s 0.35s both;
}

/* ── Buttons ───────────────────────────────────────────── */
.btn-wrap {
  display: flex;
  gap: 1rem;
  justify-content: center;
  flex-wrap: wrap;
  animation: fadeUp 0.8s 0.5s both;
}
.btn {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 0.75rem 2rem;
  font-family: 'Montserrat', sans-serif;
  font-size: 0.9rem;
  font-weight: 700;
  letter-spacing: 0.04em;
  text-transform: uppercase;
  border-radius: 8px;
  cursor: pointer;
  border: none;
  text-decoration: none;
  transition: transform 0.2s, box-shadow 0.2s, background 0.2s, color 0.2s, border-color 0.2s;
}
.btn-primary {
  background: var(--orange);
  color: #fff;
}
.btn-primary:hover {
  background: var(--orange-light);
  transform: translateY(-3px) scale(1.03);
  box-shadow: 0 8px 32px var(--orange-glow), 0 0 0 3px rgba(234, 88, 12, 0.2);
}
.btn-primary:active {
  transform: translateY(0) scale(0.98);
}
.btn-ghost {
  background: transparent;
  color: var(--muted);
  border: 1.5px solid #333;
}
.btn-ghost:hover {
  border-color: var(--orange);
  color: var(--orange);
  transform: translateY(-3px);
}

/* ── Responsive ────────────────────────────────────────── */
@media (max-width: 480px) {
  .btn-wrap { flex-direction: column; align-items: center; }
  .btn { width: 100%; max-width: 280px; justify-content: center; }
}
</style>