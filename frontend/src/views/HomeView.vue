<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { getImageUrl, handleImageError } from '@/utils/imageHelper' // Import helper gambar
import Navbar from '@/components/Navbar.vue'
import HomeSection from './section/HomeSection.vue'
import AboutSection from './section/AboutSection.vue'
import ProjectsSection from './section/ProjectsSection.vue';
import ServicesSection from './section/ServicesSection.vue';
import ClientsSection from './section/ClientsSection.vue';
import ArticlesSection from './section/ArticlesSection.vue';
import GallerySection from './section/GallerySection.vue';
import ContactSection from './section/ContactSection.vue'

const showBackToTop = ref(false)

const checkScroll = () => {
    showBackToTop.value = window.scrollY > 402
}

const scrollToTop = () => {
    window.scrollTo({ top: 0, behavior: 'smooth' })
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

            <div class="home-view">
                <HomeSection />
            </div>

            <div class="about-view">
                <AboutSection />
            </div>

            <div class="projects-view">
                <ProjectsSection />
            </div>

            <div class="articles-view">
                <ArticlesSection />
            </div>
            
            <div class="gallery-view">
                <GallerySection />
            </div>

            <div class="services-view">
                <ServicesSection />
            </div>

            <div class="clients-view">
                <ClientsSection />
            </div>

            <div class="contact-view">
                <ContactSection />
            </div>
        </main>
    </div>

    <transition name="fade">
        <button v-show="showBackToTop" @click="scrollToTop"
            class="fixed bottom-8 right-8 z-60 flex items-center justify-center w-12 h-12 bg-white border border-slate-100 rounded-2xl shadow-xl cursor-pointer transition-all duration-300 hover:border-brand hover:text-brand hover:-translate-y-1 active:scale-95 group">
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