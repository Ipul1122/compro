<script setup>
import { onMounted } from 'vue'
import ProjectsSection from '../section/ProjectsSection.vue'

// Memaksa browser scroll ke paling atas setiap kali halaman ini dibuka
onMounted(() => {
    window.scrollTo({
        top: 0,
        left: 0,
        behavior: 'instant' 
    })
})
</script>

<template>
  <div class="pt-24 pb-12 bg-white min-h-screen"> 
    
    <section class="max-w-7xl mx-auto px-6 py-12">
        <div class="bg-slate-50 rounded-3xl p-8 md:p-12 border border-slate-100 shadow-sm text-center">
            <h1 class="text-3xl md:text-4xl font-black text-slate-900 mb-4">
                {{ $t('projects_view.explore_title') }}
            </h1>
            <p class="text-slate-600 leading-relaxed max-w-3xl mx-auto">
                {{ $t('projects_view.explore_desc') }}
            </p>
        </div>
    </section>

    <ProjectsSection :showButton="false" :showFilter="true" />

  </div>
</template>