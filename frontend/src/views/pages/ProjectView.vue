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
  <div class="pt-24 pb-12 bg-slate-50 min-h-screen font-['Quicksand']"> 
    
    <section class="max-w-7xl mx-auto px-6 pt-12 pb-6">
        <div class="relative bg-white rounded-3xl p-10 md:p-16 shadow-xl shadow-slate-200/50 text-center overflow-hidden border border-slate-100">
            
            <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-red-600 to-orange-600"></div>
            <div class="absolute -top-24 -right-24 w-64 h-64 bg-gradient-to-br from-red-100 to-orange-50 rounded-full blur-3xl opacity-60"></div>
            <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-gradient-to-tr from-orange-100 to-red-50 rounded-full blur-3xl opacity-60"></div>

            <div class="relative z-10">
                <span class="inline-block py-1 px-3 rounded-full bg-red-50 text-red-600 text-sm font-bold tracking-wider mb-4 font-['Montserrat']">
                    PORTFOLIO
                </span>
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-slate-900 mb-6 font-['Montserrat'] tracking-tight">
                    {{ $t('projects_view.explore_title') || 'Explore Our Projects' }}
                </h1>
                <p class="text-slate-500 text-base md:text-lg leading-relaxed max-w-3xl mx-auto font-medium">
                    {{ $t('projects_view.explore_desc') || 'Discover the successful projects and initiatives we have accomplished over the years.' }}
                </p>
            </div>
        </div>
    </section>

    <ProjectsSection :showButton="false" :showFilter="true" />

  </div>
</template>