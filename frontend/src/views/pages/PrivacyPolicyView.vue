<script setup>
import { onMounted } from 'vue'
import PrivacyPolicySection from '@/views/section/PrivacyPolicySection.vue'

onMounted(() => {
  window.scrollTo(0, 0)
})
</script>

<template>
  <main>
    <Navbar />
    <div class=""> <PrivacyPolicySection />
    </div>
    <Footer />
  </main>
</template>