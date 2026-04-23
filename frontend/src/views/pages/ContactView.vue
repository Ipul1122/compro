<script setup>
import Navbar from '@/components/Navbar.vue'
import Footer from '@/components/Footer.vue' // Hapus import Footer jika Anda belum membuatnya
import ContactSection from '@/views/section/ContactSection.vue'
</script>

<template>
    <div class="min-h-screen bg-white">
        <Navbar />
        
        <main class="pt-24 pb-12">
            <ContactSection />
        </main>

        <Footer /> </div>
</template>