<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import HomeSection from './section/HomeSection.vue'
import AboutSection from './section/AboutSection.vue'
import ProjectsSection from './section/ProjectsSection.vue';
import ServicesSection from './section/ServicesSection.vue';
import ClientsSection from './section/ClientsSection.vue';

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

            <div class="clients-view">
                <ClientsSection />
            </div>

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

.text-brand {
    color: #ea4435;
}

.hover\:border-brand:hover {
    border-color: #ea4435;
}
</style>