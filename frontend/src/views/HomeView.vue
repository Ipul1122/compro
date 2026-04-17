<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import HomeSection from './section/HomeSection.vue'
import AboutSection from './section/AboutSection.vue'
import ProjectsSection from './section/ProjectsSection.vue';
import ServicesSection from './section/ServicesSection.vue';

const partnerLogos = [
    '/img/BKKBN-LOGO.png',
    '/img/BMKG-LOGO.png',
    '/img/BNI-LOGO.png',
    '/img/DPR-LOGO.png',
    '/img/DPR-RI-LOGO.png',
    '/img/DYNO-LOGO.png',
    '/img/ITL-TRISAKTI-LOGO.png',
    '/img/KEMENTERIAN-PERINDUSTRIAN-LOGO.png',
    '/img/LOGO-KUNING.png',
    '/img/PARIWISATA-LOGO.png',
    '/img/RISTEK-LOGO.png',
    '/img/SETJEN-DPD-LOGO.png',
    '/img/STAN-LOGO.png',
    '/img/STIK-LOGO.png',
    '/img/TRISAKTI-LOGO.png',
    '/img/ULAT-UNGU-LOGO.png'
];

const isModalOpen = ref(false)
const selectedImage = ref(null)

const openModal = (item) => {
    selectedImage.value = item
    isModalOpen.value = true
}

const closeModal = () => {
    isModalOpen.value = false
    selectedImage.value = null
}

const showBackToTop = ref(false)

const checkScroll = () => {
    showBackToTop.value = window.scrollY > 400
}

const scrollToTop = () => {
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    })
}

onMounted(() => {
    window.addEventListener('scroll', checkScroll)
})

onUnmounted(() => {
    window.removeEventListener('scroll', checkScroll)
})

</script>

<template>
    <div class="min-h-screen bg-white text-slate-900 font-sans selection:bg-brand/30 relative overflow-x-hidden">

        <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none -z-10">
            <div class="absolute -top-[10%] -left-[10%] w-[50%] h-[50%] bg-brand/5 blur-[120px] rounded-full"></div>
            <div class="absolute -bottom-[10%] -right-[10%] w-[40%] h-[40%] bg-blue-500/5 blur-[100px] rounded-full">
            </div>
        </div>

        <Navbar />

        <main id="home" class="relative mt-3">

            <!-- HOME SECTION -->
            <div class="home-view">
                <HomeSection />
            </div>

            <!-- ABOUT SECTION -->
            <div class="about-view">
                <AboutSection />
            </div>

            <div class="projects-view">
                <ProjectsSection />
            </div>

            <div class="services-view">
                <ServicesSection />
            </div>

            <section id="clients" class="py-16 bg-slate-50 border-y border-slate-200 overflow-hidden">
                <div class="py-24">
                    <div class="max-w-7xl mx-auto px-6">
                        <div class="text-center mb-16">
                            <h2 class="text-xs font-bold text-brand uppercase tracking-[0.3em] mb-4">Trusted Partners
                            </h2>
                            <p class="text-3xl font-black text-slate-900 tracking-tighter">Collaborating with the finest
                                institutions.</p>
                        </div>

                        <div class="relative flex overflow-hidden">
                            <div class="flex marquee-content gap-12 items-center py-4">
                                <div v-for="(logo, index) in partnerLogos" :key="'a-' + index"
                                    class="flex-shrink-0 group flex items-center justify-center grayscale opacity-50 hover:grayscale-0 hover:opacity-100 transition-all duration-500">
                                    <img :src="logo" :alt="'Partner Logo ' + index"
                                        class="max-h-12 w-40 object-contain transform group-hover:scale-110 transition-transform duration-300" />
                                </div>
                                <div v-for="(logo, index) in partnerLogos" :key="'b-' + index"
                                    class="flex-shrink-0 group flex items-center justify-center grayscale opacity-50 hover:grayscale-0 hover:opacity-100 transition-all duration-500">
                                    <img :src="logo" :alt="'Partner Logo ' + index"
                                        class="max-h-12 w-40 object-contain transform group-hover:scale-110 transition-transform duration-300" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <footer id="contact" class="bg-slate-950 border-t border-slate-900 pt-20 pb-10 relative overflow-hidden">
                <div class="absolute bottom-0 right-0 w-[300px] h-[300px] bg-brand/5 blur-[120px] rounded-full -z-0">
                </div>

                <div class="max-w-7xl mx-auto px-4 relative z-10">
                    <div class="grid lg:grid-cols-2 gap-16 mb-16">

                        <div class="flex flex-col justify-between">
                            <div>
                                <h2 class="text-5xl md:text-7xl font-black text-white leading-none tracking-tighter">
                                    NOW TAKING<br />
                                    <span class="text-brand">BOOKINGS</span>
                                </h2>
                                <p class="mt-6 text-slate-500 max-w-sm">
                                    Ready to elevate your organization's potential? Let's start a conversation today.
                                </p>
                            </div>

                            <div class="mt-10">
                                <button
                                    class="group flex items-center gap-3 px-8 py-4 bg-white text-black rounded-full font-bold hover:bg-brand hover:text-white transition-all duration-300">
                                    Contact Us
                                    <span class="group-hover:translate-x-1 transition-transform">→</span>
                                </button>
                            </div>
                        </div>

                        <div class="space-y-10">
                            <div>
                                <h3 class="text-white font-bold mb-4 uppercase tracking-widest text-sm">Stay Connected
                                </h3>
                                <p class="text-slate-400 leading-relaxed mb-6">
                                    Get updates and information anytime from <span
                                        class="text-slate-200 font-semibold">PT
                                        Cakrawala
                                        Parama Internasional</span> by following our social media!
                                </p>

                                <div class="flex gap-4">
                                    <a href="#"
                                        class="w-12 h-12 rounded-full border border-slate-800 flex items-center justify-center text-slate-400 hover:border-brand hover:text-brand transition-all">
                                        <span class="text-xs font-bold">FB</span>
                                    </a>
                                    <a href="#"
                                        class="w-12 h-12 rounded-full border border-slate-800 flex items-center justify-center text-slate-400 hover:border-brand hover:text-brand transition-all">
                                        <span class="text-xs font-bold">IG</span>
                                    </a>
                                    <a href="#"
                                        class="w-12 h-12 rounded-full border border-slate-800 flex items-center justify-center text-slate-400 hover:border-brand hover:text-brand transition-all">
                                        <span class="text-xs font-bold">YT</span>
                                    </a>
                                    <a href="#"
                                        class="w-12 h-12 rounded-full border border-slate-800 flex items-center justify-center text-slate-400 hover:border-brand hover:text-brand transition-all">
                                        <span class="text-xs font-bold">IN</span>
                                    </a>
                                </div>
                            </div>

                            <div>
                                <h3 class="text-white font-bold mb-4 uppercase tracking-widest text-sm">Headquarters
                                </h3>
                                <address class="not-italic text-slate-500 text-sm leading-loose">
                                    South Quarter Tower A, Lt 18<br />
                                    Jl. R.A. Kartini No.Kav 8, Kec. Cilandak<br />
                                    Daerah Khusus Ibukota Jakarta 12430
                                </address>
                            </div>
                        </div>
                    </div>

                    <div
                        class="pt-8 border-t border-slate-900 flex flex-col md:flex-row justify-between items-center gap-4">
                        <p class="text-slate-600 text-xs uppercase tracking-widest">
                            &copy; 2026 PT Cakrawala Parama Internasional. All Rights Reserved.
                        </p>
                        <div class="flex gap-6 text-xs text-slate-600 uppercase tracking-widest">
                            <a href="#" class="hover:text-brand transition-colors">Privacy Policy</a>
                            <a href="#" class="hover:text-brand transition-colors">Terms of Service</a>
                        </div>
                    </div>
                </div>
            </footer>
        </main>
    </div>

    <Teleport to="body">
        <Transition name="fade">
            <div v-if="isModalOpen"
                class="fixed inset-0 z-[100] flex flex-col items-center justify-center p-4 md:p-8 bg-slate-900/95 backdrop-blur-md"
                @click.self="closeModal">

                <button @click="closeModal"
                    class="mb-4 text-white/70 hover:text-white transition-colors flex items-center gap-2 font-bold uppercase tracking-[0.2em] text-xs">
                    Close <span class="text-3xl leading-none">×</span>
                </button>

                <div class="relative max-w-5xl w-full flex flex-col items-center">
                    <div class="w-full flex justify-center items-center overflow-hidden">
                        <img :src="selectedImage?.path" :alt="selectedImage?.name"
                            class="max-w-full max-h-[75vh] object-contain rounded-xl shadow-2xl border border-white/10" />
                    </div>

                    <p class="text-white text-center mt-6 font-bold tracking-[0.3em] uppercase text-xs">
                        {{ selectedImage?.name }}
                    </p>
                </div>

            </div>
        </Transition>
    </Teleport>

    <transition name="fade">
        <button v-show="showBackToTop" @click="scrollToTop"
            class="fixed bottom-8 right-8 z-[60] flex items-center justify-center w-12 h-12 bg-white border border-slate-100 rounded-2xl shadow-xl cursor-pointer transition-all duration-300 hover:border-brand hover:text-brand hover:-translate-y-1 active:scale-95 group">
            <svg xmlns="http://www.w3.org/2000/svg"
                class="w-6 h-6 text-slate-600 group-hover:text-brand transition-colors" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7" />
            </svg>
        </button>
    </transition>

</template>

<style scoped>
:deep(.swiper-pagination-bullet) {
    background: #cbd5e1 !important;
    opacity: 1;
    width: 12px;
    height: 4px;
    border-radius: 2px;
    transition: all 0.3s ease;
}

:deep(.swiper-pagination-bullet-active) {
    background: #fff !important;
    /* Or your brand color */
    width: 32px;
}

.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

body.modal-open {
    overflow: hidden;
}

.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease, transform 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
    transform: translateY(20px);
}

.text-brand {
    color: #ea4435;
}

.hover\:border-brand:hover {
    border-color: #ea4435;
}

/* The animation moves the content 50% because we doubled the logos */
@keyframes marquee {
    0% {
        transform: translateX(0);
    }

    100% {
        transform: translateX(-50%);
    }
}

.marquee-content {
    animation: marquee 30s linear infinite;
    display: flex;
    width: max-content;
    /* Important: makes container as wide as all logos */
}

/* Pause animation when user hovers */
.marquee-content:hover {
    animation-play-state: paused;
}

/* Optional: Add a fade effect to the edges */
#clients .relative::before,
#clients .relative::after {
    content: "";
    position: absolute;
    top: 0;
    width: 100px;
    height: 100%;
    z-index: 2;
}

#clients .relative::before {
    left: 0;
    background: linear-gradient(to right, #f8fafc, transparent);
    /* Matches bg-slate-50 */
}

#clients .relative::after {
    right: 0;
    background: linear-gradient(to left, #f8fafc, transparent);
}
</style>