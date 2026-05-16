<script setup>
import { ref } from 'vue'
import AboutSection from '../section/AboutSection.vue'
import TeamsSection from '../section/TeamsSection.vue'

const activeAccordion = ref(1)

const toggleAccordion = (index) => {
  activeAccordion.value = activeAccordion.value === index ? null : index
}
</script>

<template>

        </div>
        
      </div>
    </main>

    <AboutSection :showButton="false" />
    <TeamsSection />
  </div>
</template>

<style scoped>
.about-page {
  min-height: 100vh;
}
</style>
