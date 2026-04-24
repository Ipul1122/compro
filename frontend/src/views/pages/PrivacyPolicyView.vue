<script setup>
import { onMounted } from 'vue'
import PrivacyPolicySection from '@/views/section/PrivacyPolicySection.vue'

onMounted(() => {
  window.scrollTo(0, 0)
})
</script>

<template>
  <main>
    <div class=""> 
      <PrivacyPolicySection />
    </div>
  </main>
</template>