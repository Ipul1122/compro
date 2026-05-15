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

const MAX_VISIBLE = 11;
const showAll = ref(false);

const visibleLogos = computed(() =>
    showAll.value ? partnerLogos : partnerLogos.slice(0, MAX_VISIBLE)
);

const remainingCount = computed(() => partnerLogos.length - MAX_VISIBLE);
</script>

<template>
    <section id="clients" class="py-20 bg-white border-y border-slate-100 overflow-hidden">
        <div class="max-w-6xl mx-auto px-6">

            <!-- Section Label -->
            <div class="text-center mb-14">
                <span class="inline-block text-[10px] font-bold text-red-500 uppercase tracking-[0.35em] mb-3">
                    {{ t('clients_section.trusted_partners') }}
                </span>
                <h2 class="text-4xl font-black text-slate-900 tracking-tighter leading-tight">
                    {{ t('clients_section.collaborating') }}
                </h2>
                <div class="w-10 h-[3px] bg-red-500 rounded-full mx-auto mt-4"></div>
            </div>

            <!-- Logo Grid -->
            <div>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-5">
                    <div
                        v-for="(logo, index) in visibleLogos"
                        :key="'logo-' + index"
                        class="logo-cell flex items-center justify-center bg-slate-50 rounded-xl border border-slate-100 p-4 aspect-square"
                    >
                        <img
                            :src="logo"
                            :alt="'Partner Logo ' + (index + 1)"
                            class="w-full h-full object-contain"
                        />
                    </div>

                    <!-- +N More Button -->
                    <button
                        v-if="!showAll && remainingCount > 0"
                        @click="showAll = true"
                        class="more-btn aspect-square flex flex-col items-center justify-center bg-red-50 rounded-xl border-[1.5px] border-dashed border-red-300 text-red-500 hover:bg-red-100 hover:border-red-500 transition-all duration-200 gap-1"
                        :aria-label="'Show ' + remainingCount + ' more partners'"
                    >
                        <span class="text-2xl font-black leading-none">+{{ remainingCount }}</span>
                        <span class="text-[10px] font-bold uppercase tracking-widest opacity-70">Lainnya</span>
                    </button>

                    <!-- Collapse Button -->
                    <button
                        v-if="showAll && remainingCount > 0"
                        @click="showAll = false"
                        class="more-btn aspect-square flex flex-col items-center justify-center bg-slate-100 rounded-xl border-[1.5px] border-dashed border-slate-300 text-slate-400 hover:bg-slate-200 hover:border-slate-400 transition-all duration-200 gap-1"
                        aria-label="Show less"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7" />
                        </svg>
                        <span class="text-[10px] font-bold uppercase tracking-widest opacity-70">Tutup</span>
                    </button>
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

.logo-cell {
    transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
}

.logo-cell:hover {
    transform: translateY(-2px);
    border-color: #fca5a5;
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.08);
}

.logo-cell img {
    filter: grayscale(30%);
    opacity: 0.75;
    transition: filter 0.3s ease, opacity 0.3s ease;
}

.logo-cell:hover img {
    filter: grayscale(0%);
    opacity: 1;
}
</style>