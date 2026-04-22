<script setup>
import { computed } from 'vue';
import { useRoute } from 'vue-router';
// Pastikan path import Navbar di bawah ini sesuai dengan struktur folder lu
import Navbar from './components/Navbar.vue'; 
import Footer from './components/Footer.vue'; 

const route = useRoute();

// Fungsi untuk mengecek apakah URL saat ini adalah halaman Admin
const isAdminRoute = computed(() => {
  // Jika URL diawali dengan '/admin', maka nilainya true
  return route.path.startsWith('/admin');
});
</script>

<template>
  <Navbar v-if="!isAdminRoute" />

  <router-view></router-view>
  <Footer v-if="!isAdminRoute" />
</template>