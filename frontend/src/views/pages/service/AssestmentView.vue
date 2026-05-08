<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

const assessmentImages = [
    '/img/servicesSection/assestmen-1.jpg',
    '/img/servicesSection/assestmen-2.jpeg',
    '/img/servicesSection/assestmen-3.jpeg',
    '/img/servicesSection/assestmen-4.jpeg' 
];

const currentIndex = ref(0);
let autoSlideInterval = null;

const nextImage = () => {
    currentIndex.value = (currentIndex.value + 1) % assessmentImages.length;
};

const prevImage = () => {
    currentIndex.value = (currentIndex.value - 1 + assessmentImages.length) % assessmentImages.length;
};

const goToImage = (index) => {
    currentIndex.value = index;
};

// Pause auto slide when interacting
const pauseSlide = () => {
    if (autoSlideInterval) clearInterval(autoSlideInterval);
};

const resumeSlide = () => {
    pauseSlide();
    autoSlideInterval = setInterval(nextImage, 4000);
};

onMounted(() => {
    resumeSlide();
});

onUnmounted(() => {
    pauseSlide();
});
</script>

<template>
    <main class="relative bg-white font-montserrat z-0 pt-20 md:pt-24">
        <div class="absolute inset-0 bg-[linear-gradient(to_right,#f1f5f9_1px,transparent_1px),linear-gradient(to_bottom,#f1f5f9_1px,transparent_1px)] bg-[size:4rem_4rem] [mask-image:radial-gradient(ellipse_80%_50%_at_50%_0%,#000_70%,transparent_100%)] -z-10 pointer-events-none"></div>

        <!-- SECTION ASSESSMENT  -->
        <section id="assessment" class="py-12 md:py-24 relative overflow-hidden bg-white/50 border-y border-gray-100">
            <div class="max-w-7xl mx-auto px-4">
                <div class="grid lg:grid-cols-2 gap-16 items-center">

                    <div class="order-1 lg:order-1">
                        <div class="relative group">
                            <div class="aspect-video lg:aspect-square w-full bg-gray-100 rounded-[2rem] border border-gray-200 overflow-hidden relative shadow-lg group-hover:shadow-2xl transition-all duration-500"
                                 @mouseenter="pauseSlide" 
                                 @mouseleave="resumeSlide">
                                
                                <!-- Carousel Images -->
                                <div class="relative w-full h-full">
                                    <div v-for="(img, index) in assessmentImages" :key="img">
                                        <transition name="fade">
                                            <div v-show="currentIndex === index" class="absolute inset-0 w-full h-full">
                                                <img :src="img" :alt="'Assessment ' + index" class="w-full h-full object-cover transition-transform duration-1000 hover:scale-105" />
                                                
                                                <!-- Overlay gradient -->
                                                <div class="absolute inset-0 bg-gradient-to-t from-gray-900/90 via-gray-900/30 to-transparent"></div>
                                                
                                                <!-- Text info on image -->
                                                <div class="absolute bottom-12 left-8 z-20">
                                                    <p class="text-orange-500 font-bold tracking-[0.2em] uppercase text-xs mb-3 drop-shadow-md">Process 0{{ index + 1 }}</p>
                                                    <h3 class="text-3xl md:text-4xl font-black text-white tracking-tighter drop-shadow-lg">
                                                        Assessment
                                                    </h3>
                                                </div>
                                            </div>
                                        </transition>
                                    </div>
                                </div>

                                <!-- DPR RI Logo Overlay -->
                                <div class="absolute top-6 right-6 z-30 bg-white/95 backdrop-blur-sm p-3 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.12)] border border-white/60 transition-transform duration-300 hover:scale-105">
                                    <img src="/img/DPR-RI-LOGO.png" alt="DPR RI Logo" class="h-10 md:h-14 w-auto object-contain" />
                                </div>

                                <!-- Navigation Arrows -->
                                <button @click.stop="prevImage" class="absolute left-4 top-1/2 -translate-y-1/2 z-30 w-12 h-12 flex items-center justify-center rounded-full bg-white/20 backdrop-blur-md text-white border border-white/30 hover:bg-white hover:text-orange-600 transition-all duration-300 shadow-[0_4px_20px_rgb(0,0,0,0.1)] opacity-0 group-hover:opacity-100 hover:scale-110">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                                    </svg>
                                </button>
                                <button @click.stop="nextImage" class="absolute right-4 top-1/2 -translate-y-1/2 z-30 w-12 h-12 flex items-center justify-center rounded-full bg-white/20 backdrop-blur-md text-white border border-white/30 hover:bg-white hover:text-orange-600 transition-all duration-300 shadow-[0_4px_20px_rgb(0,0,0,0.1)] opacity-0 group-hover:opacity-100 hover:scale-110">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                                    </svg>
                                </button>

                                <!-- Indicators (Dots) -->
                                <div class="absolute bottom-6 left-1/2 -translate-x-1/2 z-30 flex gap-2.5">
                                    <button v-for="(img, index) in assessmentImages" :key="'dot-'+index"
                                            @click.stop="goToImage(index)"
                                            :aria-label="'Go to slide ' + (index + 1)"
                                            :class="['h-2 rounded-full transition-all duration-500 ease-out', currentIndex === index ? 'bg-orange-500 w-8 shadow-[0_0_10px_rgba(249,115,22,0.8)]' : 'bg-white/50 w-2 hover:bg-white hover:w-4']">
                                    </button>
                                </div>
                            </div>
                            <!-- Background decoration behind the image -->
                            <div class="absolute -bottom-4 -right-4 w-full h-full border-2 border-orange-600/20 rounded-[2rem] -z-10 group-hover:translate-x-3 group-hover:translate-y-3 transition-transform duration-500"></div>
                        </div>
                    </div>

                    <div id="psikotest" class="order-2 lg:order-2">
                        <span class="inline-block py-1.5 px-4 rounded-full bg-gray-900 text-white font-bold tracking-[0.2em] uppercase text-xs mb-6 shadow-sm">
                            {{ $t('service_view.assessment.badge') }}
                        </span>

                        <h2 class="text-4xl md:text-5xl font-black leading-[1.1] text-gray-900 uppercase tracking-tight">
                            {{ $t('service_view.assessment.title') }} <br class="hidden md:block" />
                            <span class="text-orange-600">{{ $t('service_view.assessment.title_break') }}</span> 
                            <span class="text-gray-400 text-2xl md:text-3xl font-medium block mt-2 normal-case tracking-normal">
                                {{ $t('service_view.assessment.subtitle') }}
                            </span>
                        </h2>

                        <p class="mt-6 text-lg text-gray-500 leading-relaxed font-medium">
                            {{ $t('service_view.assessment.desc') }}
                        </p>

                        <ul class="mt-8 space-y-4 bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                            <li class="flex items-center gap-4 text-gray-700 font-medium">
                                <div class="flex-shrink-0 w-8 h-8 rounded-full bg-orange-50 flex items-center justify-center text-orange-600 font-bold">1</div>
                                <span>{{ $t('service_view.assessment.point1') }}</span>
                            </li>
                            <li class="flex items-center gap-4 text-gray-700 font-medium">
                                <div class="flex-shrink-0 w-8 h-8 rounded-full bg-orange-50 flex items-center justify-center text-orange-600 font-bold">2</div>
                                <span>{{ $t('service_view.assessment.point2') }}</span>
                            </li>
                        </ul>

                        <div class="mt-10">
                            <button class="group flex items-center gap-3 text-white bg-gray-900 px-8 py-4 rounded-full font-bold text-sm hover:bg-orange-600 transition-all duration-300 shadow-md hover:shadow-xl hover:-translate-y-1">
                                {{ $t('service_view.assessment.btn_cta') }}
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <!-- SECTION Psychological Asessment  -->
         
    </main>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.8s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>