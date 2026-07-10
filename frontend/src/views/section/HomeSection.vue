<script setup>
import { ref, onMounted, onUnmounted } from 'vue';

const heroImages = [
    '/img/heroSection/lcc.jpeg',
    '/img/heroSection/lcc-2.jpeg',
    '/img/heroSection/lcc-3.jpeg'
];

const activeIndex = ref(0);
let timer = null;

const nextSlide = () => {
    activeIndex.value = (activeIndex.value + 1) % heroImages.length;
};

const prevSlide = () => {
    activeIndex.value = (activeIndex.value - 1 + heroImages.length) % heroImages.length;
};

const goToSlide = (index) => {
    activeIndex.value = index;
};

onMounted(() => {
    timer = setInterval(nextSlide, 5000);
});

onUnmounted(() => {
    if (timer) clearInterval(timer);
});
</script>

<template>
    <main id="home" class="relative mt-3 overflow-hidden">

        <!-- Grid Background -->
        <div class="absolute inset-0 -z-20">
            <div class="absolute inset-0"
                style="background-image: linear-gradient(rgba(234,68,53,0.06) 1px, transparent 1px), linear-gradient(90deg, rgba(234,68,53,0.06) 1px, transparent 1px); background-size: 48px 48px;">
            </div>
            <div class="absolute inset-0 bg-gradient-to-br from-white via-white/95 to-red-50/60"></div>
        </div>

        <!-- Glowing blobs -->
        <div class="absolute top-0 right-0 w-[700px] h-[700px] -z-10 pointer-events-none">
            <div class="absolute top-[-100px] right-[-150px] w-[500px] h-[500px] rounded-full bg-red-500/10 blur-[120px]"></div>
        </div>
        <div class="absolute bottom-0 left-0 w-[400px] h-[400px] -z-10 pointer-events-none">
            <div class="absolute bottom-[-80px] left-[-80px] w-[300px] h-[300px] rounded-full bg-red-400/8 blur-[100px]"></div>
        </div>

        <!-- Decorative corner accent -->
        <div class="absolute top-0 left-0 w-64 h-64 -z-10 pointer-events-none overflow-hidden">
            <div class="absolute -top-16 -left-16 w-64 h-64 border border-red-500/10 rounded-full"></div>
            <div class="absolute -top-8 -left-8 w-40 h-40 border border-red-500/15 rounded-full"></div>
        </div>

        <section class="max-w-7xl mx-auto px-4 pt-16 pb-32">
            <div class="grid lg:grid-cols-2 gap-16 items-center">

                <!-- Left Content -->
                <div class="text-left relative z-10">

                    <!-- Badge -->
                    <div class="inline-flex items-center gap-3 mb-6">
                        <span class="relative flex items-center gap-2 px-4 py-1.5 rounded-full border border-red-500/20 bg-red-500/5 backdrop-blur-sm">
                            <span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse"></span>
                            <span class="text-red-600 font-black uppercase tracking-[0.35em] text-[9px] md:text-[10px]">
                                {{ $t('home.subtitle') }}
                            </span>
                        </span>
                    </div>

                    <!-- Heading -->
                    <h1 class="font-montserrat flex flex-col gap-1">
                        <span class="text-3xl md:text-5xl font-bold tracking-tight text-slate-900 leading-tight">
                            PT <span class="bg-gradient-to-r from-red-600 via-red-500 to-amber-400 bg-clip-text text-transparent">
                            Cakrawala</span>
                        </span>
                        <span class="text-3xl md:text-5xl font-bold tracking-tight leading-tight relative">
                            <span class="relative inline-block">
                                <span class="bg-gradient-to-r from-red-600 via-red-500 to-amber-400 bg-clip-text text-transparent">
                                    Parama
                                </span>
                                <!-- Underline accent -->
                            </span>
                            <span class="text-slate-800 ml-3">Internasional</span>
                        </span>
                    </h1>

                    <!-- Description -->
                    <p class="font-quicksand mt-7 text-sm md:text-base text-slate-500 tracking-wide leading-relaxed max-w-xl">
                        {{ $t('home.description') }}
                    </p>

                    <!-- Stats strip -->
                    <div class="mt-8 flex items-center gap-6">
                        <div class="flex flex-col">
                            <span class="text-2xl font-black text-slate-900">20<span class="text-red-500">+</span></span>
                            <span class="text-[10px] uppercase tracking-widest text-slate-400">Years</span>
                        </div>
                        <div class="w-px h-10 bg-slate-200"></div>
                        <div class="flex flex-col">
                            <span class="text-2xl font-black text-slate-900">500<span class="text-red-500">+</span></span>
                            <span class="text-[10px] uppercase tracking-widest text-slate-400">Projects</span>
                        </div>
                        <div class="w-px h-10 bg-slate-200"></div>
                        <div class="flex flex-col">
                            <span class="text-2xl font-black text-slate-900">100<span class="text-red-500">+</span></span>
                            <span class="text-[10px] uppercase tracking-widest text-slate-400">Clients</span>
                        </div>
                    </div>

                    <!-- CTA Button -->
                    <div class="mt-8 flex flex-col sm:flex-row gap-4">
                        <a href="#our-services"
                            class="group relative inline-flex items-center justify-center gap-3 px-10 py-4 rounded-xl font-bold text-white overflow-hidden transition-all duration-300 hover:shadow-[0_16px_40px_rgba(234,68,53,0.35)] hover:scale-[1.02] active:scale-95">
                            <!-- Button BG -->
                            <span class="absolute inset-0 bg-gradient-to-r from-red-600 via-red-500 to-amber-500 transition-all duration-300"></span>
                            <span class="absolute inset-0 bg-gradient-to-r from-red-500 to-red-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                            <!-- Grid texture on button -->
                            <span class="absolute inset-0 opacity-[0.07]"
                                style="background-image: linear-gradient(white 1px, transparent 1px), linear-gradient(90deg, white 1px, transparent 1px); background-size: 12px 12px;"></span>
                            <span class="relative z-10">{{ $t('home.button_service') }}</span>
                            <svg class="relative z-10 w-4 h-4 transition-transform duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                            </svg>
                        </a>

                        <a href="https://wa.me/6285284201428?text=Halo%20PT%20Cakrawala%20Parama%20Internasional%2C%20saya%20tertarik%20dengan%20layanan%20Anda%20dan%20ingin%20berkonsultasi."
                            target="_blank"
                            class="group relative inline-flex items-center justify-center gap-3 px-8 py-4 rounded-xl font-bold text-slate-700 bg-white border border-slate-200 shadow-sm transition-all duration-300 hover:shadow-lg hover:border-emerald-500 hover:text-emerald-600 hover:scale-[1.02] active:scale-95">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-emerald-500 group-hover:scale-110 transition-transform duration-300" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                            </svg>
                            <span class="relative z-10">{{ $t('home.button_whatsapp') }}</span>
                        </a>
                    </div>
                </div>

                <!-- Right: Image Panel -->
                <div class="hidden lg:flex justify-center items-center relative min-h-[600px] w-full overflow-visible py-10">

                    <!-- Background glow -->
                    <div class="absolute w-[580px] h-[580px] bg-[radial-gradient(circle,_var(--tw-gradient-stops))] from-red-500/12 via-red-500/4 to-transparent blur-[80px] -z-10"></div>

                    <!-- Outer grid decorative frame -->
                    <div class="absolute inset-0 flex items-center justify-center pointer-events-none -z-5">
                        <div class="w-[500px] h-[500px] border border-red-500/8 rounded-[3rem] rotate-3"></div>
                        <div class="absolute w-[480px] h-[480px] border border-red-500/5 rounded-[3rem] -rotate-2"></div>
                    </div>

                    <div class="relative group">

                        <!-- Floating top-left orb -->
                        <div class="absolute -top-8 -left-8 w-16 h-16 bg-white/80 backdrop-blur-xl rounded-2xl border border-red-100 shadow-xl z-30 transition-all duration-500 group-hover:-translate-y-3 group-hover:-translate-x-1 flex items-center justify-center rotate-12 group-hover:rotate-6">
                            <div class="w-6 h-6 rounded-lg bg-gradient-to-br from-red-500 to-amber-400 flex items-center justify-center">
                                <div class="w-2 h-2 bg-white rounded-sm"></div>
                            </div>
                        </div>

                        <!-- Floating bottom-right orb -->
                        <div class="absolute -bottom-6 -right-6 w-20 h-20 bg-gradient-to-br from-red-500/15 to-amber-400/10 backdrop-blur-lg rounded-2xl border border-red-200/40 shadow-lg z-30 transition-all duration-700 group-hover:scale-110 group-hover:translate-y-2 -rotate-6">
                            <div class="absolute top-3 left-3 w-2 h-2 rounded-full bg-red-400/60"></div>
                            <div class="absolute bottom-3 right-3 w-1.5 h-1.5 rounded-full bg-amber-400/60"></div>
                        </div>

                        <!-- Floating top-right small dot -->
                        <div class="absolute -top-3 right-12 w-8 h-8 rounded-full bg-red-500/10 border border-red-300/30 z-30 transition-all duration-700 group-hover:-translate-y-2 group-hover:translate-x-1 backdrop-blur-sm"></div>

                        <!-- Image card -->
                        <div class="w-[440px] h-[460px] bg-white rounded-[2.5rem] border border-slate-100 relative overflow-hidden flex items-center justify-center shadow-[0_40px_80px_-15px_rgba(0,0,0,0.12),0_0_0_1px_rgba(234,68,53,0.06)] z-20 transition-all duration-700 group-hover:shadow-[0_60px_100px_-15px_rgba(234,68,53,0.18)]">

                            <transition-group name="fade">
                                <div v-for="(imgSrc, index) in heroImages"
                                     v-show="activeIndex === index"
                                     :key="imgSrc"
                                     class="absolute inset-0">
                                    <img :src="imgSrc" alt="PT Cakrawala Project"
                                        class="absolute inset-0 w-full h-full object-cover transition-transform duration-1000 group-hover:scale-105">
                                </div>
                            </transition-group>

                            <!-- Overlay gradient -->
                            <div class="absolute inset-0 bg-gradient-to-tr from-red-600/10 via-transparent to-amber-400/5 pointer-events-none z-10"></div>

                            <!-- Navigation Buttons (appears on hover) -->
                            <button @click="prevSlide" aria-label="Previous slide"
                                    class="absolute left-4 top-1/2 -translate-y-1/2 w-9 h-9 bg-black/35 backdrop-blur-md rounded-full flex items-center justify-center text-white opacity-0 group-hover:opacity-100 transition-all duration-300 hover:bg-red-500 border border-white/10 z-30">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                                </svg>
                            </button>
                            <button @click="nextSlide" aria-label="Next slide"
                                    class="absolute right-4 top-1/2 -translate-y-1/2 w-9 h-9 bg-black/35 backdrop-blur-md rounded-full flex items-center justify-center text-white opacity-0 group-hover:opacity-100 transition-all duration-300 hover:bg-red-500 border border-white/10 z-30">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                                </svg>
                            </button>

                            <!-- Bottom info bar & dots -->
                            <div class="absolute bottom-0 left-0 right-0 p-5 bg-gradient-to-t from-black/60 to-transparent z-25 flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <div class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></div>
                                    <span class="text-white/90 text-xs font-semibold tracking-widest uppercase">Active Projects</span>
                                </div>
                                <div class="flex gap-1.5">
                                    <button v-for="(_, index) in heroImages" :key="index" @click="goToSlide(index)"
                                            :aria-label="'Go to slide ' + (index + 1)"
                                            class="h-1.5 rounded-full transition-all duration-300"
                                            :class="activeIndex === index ? 'w-5 bg-red-500' : 'w-1.5 bg-white/40 hover:bg-white/60'">
                                    </button>
                                </div>
                            </div>

                            <!-- Grid overlay subtle -->
                            <div class="absolute inset-0 opacity-[0.03] pointer-events-none z-10"
                                style="background-image: linear-gradient(white 1px, transparent 1px), linear-gradient(90deg, white 1px, transparent 1px); background-size: 24px 24px;">
                            </div>
                        </div>

                        <!-- Outer ring -->
                        <div class="absolute -inset-6 border border-red-500/8 rounded-[3.5rem] -z-10 transition-transform duration-700 group-hover:scale-105"></div>
                        <div class="absolute -inset-12 border border-red-500/4 rounded-[4rem] -z-10 transition-transform duration-700 group-hover:scale-102"></div>
                    </div>
                </div>

            </div>
        </section>
    </main>
</template>

<style scoped>
.fade-enter-active, .fade-leave-active {
    transition: all 0.8s ease-in-out;
}
.fade-enter-from {
    opacity: 0;
    transform: scale(1.05);
}
.fade-leave-to {
    opacity: 0;
    transform: scale(0.95);
}
</style>