<script setup>
import AboutSection from '../section/AboutSection.vue'
</script>

<template>
  <div class="about-page">
    <AboutSection :showButton="false" />

    <main class="container mx-auto px-6 py-16">
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-stretch">
        
        <div class="lg:col-span-7 group">
          <div class="h-full p-8 md:p-12 rounded-3xl bg-white/70 backdrop-blur-md border border-red-100 shadow-xl shadow-red-50/50 hover:shadow-2xl hover:shadow-red-100/50 transition-all duration-500 hover:-translate-y-1">
            <div class="flex items-center gap-4 mb-6">
              <div class="p-3 bg-red-500 rounded-2xl shadow-lg shadow-red-200">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
              </div>
              <h2 class="font-montserrat font-extrabold text-2xl md:text-3xl text-slate-900 tracking-tight">
                {{ $t('about.our_vision') }}
              </h2>
            </div>
            <p class="font-quicksand text-lg text-slate-600 leading-relaxed text-justify">
              {{ $t('about.vision_desc') }}
            </p>
            <div class="mt-8 pt-8 border-t border-slate-100 flex items-center justify-between">
              <span class="font-montserrat text-xs font-bold uppercase tracking-widest text-red-500">{{ $t('about.company_foundation') }}</span>
              <div class="h-1 w-24 bg-gradient-to-r from-red-500 to-transparent rounded-full"></div>
            </div>
          </div>
        </div>

        <div class="lg:col-span-5 flex flex-col gap-6">
          <div class="flex-1 p-8 rounded-3xl bg-slate-900 text-white shadow-2xl relative overflow-hidden group">
            <div class="absolute -right-4 -bottom-4 w-32 h-32 bg-red-500/10 rounded-full blur-3xl group-hover:bg-red-500/20 transition-colors"></div>
            <h3 class="font-montserrat font-bold text-xl mb-4 relative z-10">{{ $t('about.vision_title') }}</h3>
            <p class="font-quicksand text-slate-300 text-sm leading-relaxed mb-6 relative z-10">
              {{ $t('about.desc_1') }}
            </p>
            <div class="flex gap-4 items-center">
              <div class="h-12 w-12 rounded-full border border-white/20 flex items-center justify-center font-montserrat font-black text-red-400">CPI</div>
              <div class="text-xs font-montserrat tracking-tighter text-slate-400 uppercase">Global Reach<br/>Local Insight</div>
            </div>
          </div>
        </div>
      </div>

      <section class="mt-20">
        <div class="text-center mb-12">
          <h2 class="font-montserrat font-black text-3xl md:text-4xl text-slate-900 mb-4">
            {{ $t('about.our_missions') }}
          </h2>
          <div class="w-20 h-1.5 bg-red-500 mx-auto rounded-full"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div v-for="n in 7" :key="n" 
               class="group p-6 rounded-2xl bg-white border border-slate-100 shadow-sm hover:shadow-xl hover:border-red-200 transition-all duration-300"
               :class="{'lg:col-span-1': true, 'md:col-span-2 lg:col-span-1': n === 7}">
            <div class="flex items-start gap-4">
              <span class="font-montserrat font-black text-3xl text-red-100 group-hover:text-red-500 transition-colors duration-300">
                0{{ n }}
              </span>
              <p class="font-quicksand text-slate-600 group-hover:text-slate-900 leading-snug transition-colors pt-1">
                {{ $t(`about.mission_${n}`) }}
              </p>
            </div>
          </div>
        </div>
      </section>
    </main>

    <footer class="container mx-auto px-6 pb-12">
      <div class="p-8 rounded-3xl bg-slate-50 border border-slate-100 flex flex-col md:flex-row justify-between items-center gap-6">
        <p class="font-quicksand text-sm text-slate-500 italic">
          {{ $t('about.footer_text') }}
        </p>
        <div class="flex gap-2">
          <div v-for="i in 3" :key="i" :class="`w-2 h-2 rounded-full ${i===1?'bg-red-500':i===2?'bg-red-300':'bg-red-100'}`"></div>
        </div>
      </div>
    </footer>
  </div>
</template>

<style scoped>
.about-page {
  background-color: #fdfdfd;
  background-image: 
    radial-gradient(at 0% 0%, rgba(249, 115, 22, 0.03) 0px, transparent 50%),
    radial-gradient(at 100% 100%, rgba(249, 115, 22, 0.03) 0px, transparent 50%);
  min-height: 100vh;
}

/* Animasi Masuk */
.container {
  animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1);
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>