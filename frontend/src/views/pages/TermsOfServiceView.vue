<script setup>
import { onMounted } from 'vue'
import TermsOfServiceSection from '@/views/section/TermsOfServiceSection.vue'

onMounted(() => {
  window.scrollTo(0, 0)
})
</script>

<template>
  <main>
    <Navbar />
    <div class="pt-20"> <TermsOfServiceSection />
    </div>
    <Footer />
  </main>
</template>