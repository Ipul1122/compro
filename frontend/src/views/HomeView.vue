<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
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
    <div class="min-h-screen bg-white text-slate-900 font-sans selection:bg-brand/30">

        <main id="home" class="pt-3">

            <section class="home-view">
                <HomeSection />
            </section>

            <section class="about-view section-gap">
                <AboutSection />
            </section>

            <section class="projects-view section-gap">
                <ProjectsSection />
            </section>

            <section class="articles-view section-gap">
                <ArticlesSection />
            </section>
            
            <section class="gallery-view section-gap">
                <GallerySection />
            </section>

            <section class="services-view section-gap">
                <ServicesSection />
            </section>

            <section class="clients-view section-gap">
                <ClientsSection />
            </section>

            <section class="contact-view section-gap">
                <ContactSection />
            </section>
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

.section-gap {
    margin-top: 2rem;
}

@media (min-width: 768px) {
    .section-gap {
        margin-top: 2.5rem;
    }
}
</style>
