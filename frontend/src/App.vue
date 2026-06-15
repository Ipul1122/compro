<script setup>
import { ref, onMounted, onUnmounted, computed } from 'vue';
import { useRoute } from 'vue-router';
// Pastikan path import Navbar di bawah ini sesuai dengan struktur folder lu
import Navbar from './components/Navbar.vue'; 
import Footer from './components/Footer.vue'; 
import WhatsappWidget from './components/WhatsappWidget.vue';

const route = useRoute();

// Fungsi untuk mengecek apakah URL saat ini adalah halaman Admin atau halaman dengan meta `hideNavbar`
const hideLayoutNav = computed(() => {
  const shouldHide = route.path.startsWith('/admin') || route.path.startsWith('/direktur') || Boolean(route.meta.hideNavbar);
  return shouldHide;
});

const isScrolled = ref(false);

const checkScroll = () => {
  isScrolled.value = window.scrollY > 402;
};

onMounted(() => {
  window.addEventListener('scroll', checkScroll);
});

onUnmounted(() => {
  window.removeEventListener('scroll', checkScroll);
});
</script>

<template>
  <Navbar v-if="!hideLayoutNav" />

  <main class="min-h-screen">
    <router-view></router-view>
  </main>
  <Footer v-if="!hideLayoutNav" />

  <!-- Floating WhatsApp Widget -->
  <WhatsappWidget v-if="!hideLayoutNav" />
</template>