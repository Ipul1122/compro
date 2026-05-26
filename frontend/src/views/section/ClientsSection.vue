<script setup>
import { computed, ref } from 'vue';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

const partnerLogos = [
    '/img/logo/DPR-RI-LOGO.png',
    '/img/logo/DPR-LOGO.png',
    '/img/logo/SETJEN-DPD-LOGO.png',
    '/img/logo/BKKBN-LOGO.png',
    '/img/logo/BMKG-LOGO.png',
    '/img/logo/BNI-LOGO.png',
    '/img/logo/DYNO-LOGO.png',
    '/img/logo/ITL-TRISAKTI-LOGO.png',
    '/img/logo/KEMENTERIAN-PERINDUSTRIAN-LOGO.png',
    '/img/logo/LOGO-KUNING.png',
    '/img/logo/PARIWISATA-LOGO.png',
    '/img/logo/RISTEK-LOGO.png',
    '/img/logo/STAN-LOGO.png',
    '/img/logo/STIK-LOGO.png',
    '/img/logo/ULAT-UNGU-LOGO.png'
];

// Duplicate list to make scrolling seamless
const doubledLogos = [...partnerLogos, ...partnerLogos];
</script>

<template>
    <section id="clients" class="py-20 bg-white border-y border-slate-100 overflow-hidden">
        <div class="max-w-6xl mx-auto px-6 mb-12">

            <!-- Section Label -->
            <div class="text-center">
                <span class="inline-block text-[10px] font-bold text-red-500 uppercase tracking-[0.35em] mb-3">
                    {{ t('clients_section.trusted_partners') }}
                </span>
                <h2 class="text-4xl font-black text-slate-900 tracking-tighter leading-tight">
                    {{ t('clients_section.collaborating') }}
                </h2>
                <div class="w-10 h-[3px] bg-red-500 rounded-full mx-auto mt-4"></div>
            </div>

        </div>

        <!-- Infinite Logo Marquee -->
        <div class="relative w-full overflow-hidden py-6 bg-slate-50/50 border-y border-slate-100">
            <!-- Left and Right Fading Overlays for premium look -->
            <div class="absolute left-0 top-0 bottom-0 w-24 bg-gradient-to-r from-white via-white/80 to-transparent z-10 pointer-events-none"></div>
            <div class="absolute right-0 top-0 bottom-0 w-24 bg-gradient-to-l from-white via-white/80 to-transparent z-10 pointer-events-none"></div>

            <div class="marquee-track flex gap-6 w-max">
                <div
                    v-for="(logo, index) in doubledLogos"
                    :key="'logo-' + index"
                    class="logo-cell flex-shrink-0 flex items-center justify-center bg-white rounded-2xl border border-slate-100/80 p-5 w-[140px] h-[140px] shadow-sm hover:shadow-md transition-all duration-300"
                >
                    <img
                        :src="logo"
                        :alt="'Partner Logo ' + ((index % partnerLogos.length) + 1)"
                        class="w-full h-full object-contain filter grayscale opacity-70 hover:grayscale-0 hover:opacity-100 transition-all duration-300"
                        loading="lazy"
                    />
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;900&display=swap');

section {
    font-family: 'Montserrat', sans-serif;
}

.marquee-track {
    animation: scroll-marquee 45s linear infinite;
}

.marquee-track:hover {
    animation-play-state: paused;
}

.logo-cell {
    transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.3s ease, border-color 0.3s ease;
}

.logo-cell:hover {
    transform: translateY(-4px) scale(1.03);
    border-color: #fecaca;
    box-shadow: 0 10px 25px -5px rgba(239, 68, 68, 0.08), 0 8px 10px -6px rgba(239, 68, 68, 0.08);
}

@keyframes scroll-marquee {
    0% {
        transform: translate3d(0, 0, 0);
    }
    100% {
        transform: translate3d(-50%, 0, 0);
    }
}
</style>