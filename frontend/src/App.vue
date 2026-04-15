<script setup>
import Navbar from '@/components/Navbar.vue'
import { useRoute } from 'vue-router' // 1. Add this import

const route = useRoute() // 2. Create the route variable
</script>

<template>
  <div>
    <Navbar v-if="!route.meta.hideNavbar" />

    <router-view />
  </div>
</template>