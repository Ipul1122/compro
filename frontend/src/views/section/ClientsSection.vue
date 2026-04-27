<script setup>
import { computed, ref } from 'vue';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

const partnerLogos = [
    '/img/logo/BKKBN-LOGO.png',
    '/img/logo/BMKG-LOGO.png',
    '/img/logo/BNI-LOGO.png',
    '/img/logo/DPR-LOGO.png',
    '/img/logo/DPR-RI-LOGO.png',
    '/img/logo/DYNO-LOGO.png',
    '/img/logo/ITL-TRISAKTI-LOGO.png',
    '/img/logo/KEMENTERIAN-PERINDUSTRIAN-LOGO.png',
    '/img/logo/LOGO-KUNING.png',
    '/img/logo/PARIWISATA-LOGO.png',
    '/img/logo/RISTEK-LOGO.png',
    '/img/logo/SETJEN-DPD-LOGO.png',
    '/img/logo/STAN-LOGO.png',
    '/img/logo/STIK-LOGO.png',
    '/img/logo/TRISAKTI-LOGO.png',
    '/img/logo/ULAT-UNGU-LOGO.png'
];

const testimonials = computed(() => [
    {
        name: 'Budi Santoso',
        company: 'PT Maju Jaya',
        message: t('clients_section.testimonials.budi_message')
    },
    {
        name: 'Siti Aminah',
        company: 'PT Teknologi Inovasi',
        message: t('clients_section.testimonials.siti_message')
    },
    {
        name: 'Andi Wijaya',
        company: 'PT Bangun Persada',
        message: t('clients_section.testimonials.andi_message')
    }
]);

const MAX_VISIBLE = 8;
const showAll = ref(false);

const visibleLogos = computed(() =>
    showAll.value ? partnerLogos : partnerLogos.slice(0, MAX_VISIBLE)
);

const remainingCount = computed(() => partnerLogos.length - MAX_VISIBLE);

const currentIndex = ref(0);

function prevSlide() {
    currentIndex.value = (currentIndex.value - 1 + testimonials.value.length) % testimonials.value.length;
}

function nextSlide() {
    currentIndex.value = (currentIndex.value + 1) % testimonials.value.length;
}

function goToSlide(index) {
    currentIndex.value = index;
}
</script>

<template>
    <section id="clients" class="py-20 bg-white border-y border-slate-100 overflow-hidden">
        <div class="max-w-6xl mx-auto px-6">

            <!-- Section Label -->
            <div class="text-center mb-14">
                <span class="inline-block text-[10px] font-bold text-orange-500 uppercase tracking-[0.35em] mb-3">
                    {{ t('clients_section.trusted_partners') }}
                </span>
                <h2 class="text-4xl font-black text-slate-900 tracking-tighter leading-tight">
                    {{ t('clients_section.collaborating') }}
                </h2>
                <div class="w-10 h-[3px] bg-orange-500 rounded-full mx-auto mt-4"></div>
            </div>

            <!-- Main Grid: Logo Left | Testimonial Right -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">

                <!-- LEFT: Logo Grid -->
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.3em] mb-5">
                        {{ t('clients_section.trusted_partners') }}
                    </p>
                    <div class="grid grid-cols-4 gap-4">
                        <div
                            v-for="(logo, index) in visibleLogos"
                            :key="'logo-' + index"
                            class="logo-cell flex items-center justify-center bg-slate-50 rounded-xl border border-slate-100 p-3 aspect-square"
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
                            class="more-btn aspect-square flex flex-col items-center justify-center bg-orange-50 rounded-xl border-[1.5px] border-dashed border-orange-300 text-orange-500 hover:bg-orange-100 hover:border-orange-500 transition-all duration-200 gap-1"
                            :aria-label="'Show ' + remainingCount + ' more partners'"
                        >
                            <span class="text-xl font-black leading-none">+{{ remainingCount }}</span>
                            <span class="text-[9px] font-bold uppercase tracking-widest opacity-70">Lainnya</span>
                        </button>

                        <!-- Collapse Button -->
                        <button
                            v-if="showAll && remainingCount > 0"
                            @click="showAll = false"
                            class="more-btn aspect-square flex flex-col items-center justify-center bg-slate-100 rounded-xl border-[1.5px] border-dashed border-slate-300 text-slate-400 hover:bg-slate-200 hover:border-slate-400 transition-all duration-200 gap-1"
                            aria-label="Show less"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7" />
                            </svg>
                            <span class="text-[9px] font-bold uppercase tracking-widest opacity-70">Tutup</span>
                        </button>
                    </div>
                </div>

                <!-- RIGHT: Testimonial Slider -->
                <div class="flex flex-col h-full">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.3em] mb-5">
                        {{ t('clients_section.client_testimonials') }}
                    </p>

                    <!-- Slider Card -->
                    <div class="relative flex-1">
                        <div class="testimonial-card bg-white border-[1.5px] border-slate-200 rounded-2xl p-8 min-h-[280px] flex flex-col justify-between overflow-hidden">

                            <!-- Decorative quote bg -->
                            <div class="absolute top-4 right-6 text-[120px] font-serif leading-none text-orange-100 select-none pointer-events-none">"</div>

                            <!-- Active testimonial -->
                            <transition name="fade" mode="out-in">
                                <div :key="currentIndex" class="relative z-10 flex flex-col justify-between h-full">
                                    <div>
                                        <!-- Stars -->
                                        <div class="flex gap-0.5 mb-5">
                                            <svg v-for="i in 5" :key="i" class="w-4 h-4 text-orange-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                        </div>

                                        <p class="text-slate-600 text-base leading-relaxed italic mb-8">
                                            "{{ testimonials[currentIndex].message }}"
                                        </p>
                                    </div>

                                    <!-- Author -->
                                    <div class="flex items-center gap-4 border-t border-slate-100 pt-5">
                                        <div class="w-11 h-11 rounded-full bg-orange-500 flex items-center justify-center text-white font-bold text-base flex-shrink-0 ring-2 ring-orange-100">
                                            {{ testimonials[currentIndex].name.charAt(0) }}
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-slate-900 text-sm leading-tight">{{ testimonials[currentIndex].name }}</h4>
                                            <p class="text-xs text-orange-500 font-semibold mt-0.5">{{ testimonials[currentIndex].company }}</p>
                                        </div>
                                        <div class="ml-auto text-xs text-slate-300 font-mono tabular-nums">
                                            {{ String(currentIndex + 1).padStart(2, '0') }} / {{ String(testimonials.length).padStart(2, '0') }}
                                        </div>
                                    </div>
                                </div>
                            </transition>
                        </div>

                        <!-- Navigation -->
                        <div class="flex items-center justify-between mt-5">
                            <!-- Dots -->
                            <div class="flex gap-2">
                                <button
                                    v-for="(_, i) in testimonials"
                                    :key="'dot-' + i"
                                    @click="goToSlide(i)"
                                    :class="[
                                        'transition-all duration-300 rounded-full',
                                        i === currentIndex
                                            ? 'w-6 h-2 bg-orange-500'
                                            : 'w-2 h-2 bg-slate-200 hover:bg-slate-300'
                                    ]"
                                    :aria-label="'Go to testimonial ' + (i + 1)"
                                />
                            </div>

                            <!-- Arrows -->
                            <div class="flex gap-2">
                                <button
                                    @click="prevSlide"
                                    class="arrow-btn w-10 h-10 rounded-xl border-[1.5px] border-slate-200 flex items-center justify-center text-slate-400 hover:border-orange-500 hover:text-orange-500 hover:bg-orange-50 transition-all duration-200"
                                    aria-label="Previous"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                                    </svg>
                                </button>
                                <button
                                    @click="nextSlide"
                                    class="arrow-btn w-10 h-10 rounded-xl bg-orange-500 border-[1.5px] border-orange-500 flex items-center justify-center text-white hover:bg-orange-600 transition-all duration-200"
                                    aria-label="Next"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
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
    border-color: #fed7aa;
    box-shadow: 0 4px 12px rgba(249, 115, 22, 0.08);
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

/* Fade transition for testimonial swap */
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.25s ease, transform 0.25s ease;
}

.fade-enter-from {
    opacity: 0;
    transform: translateX(12px);
}

.fade-leave-to {
    opacity: 0;
    transform: translateX(-12px);
}
</style>