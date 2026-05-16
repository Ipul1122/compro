<script setup>
import AboutSection from '../section/AboutSection.vue'
import TeamsSection from '../section/TeamsSection.vue'
</script>

<template>
  <div class="about-page bg-slate-50">
    <main class="mx-auto max-w-6xl px-5 py-12 md:px-8 md:py-16">
      <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white">
        <div class="grid grid-cols-2 md:grid-cols-4">
          <article class="border-b border-r border-slate-200 p-6 text-center md:border-b-0 md:p-8">
            <p class="font-montserrat text-5xl font-black text-red-600">20+</p>
            <p class="mt-3 font-montserrat text-xs font-semibold uppercase tracking-[0.14em] text-slate-400 md:text-sm">
              Tahun Pengalaman
            </p>
          </article>

          <article class="border-b border-slate-200 p-6 text-center md:border-b-0 md:border-r md:p-8">
            <p class="font-montserrat text-5xl font-black text-red-600">100+</p>
            <p class="mt-3 font-montserrat text-xs font-semibold uppercase tracking-[0.14em] text-slate-400 md:text-sm">
              Proyek Selesai
            </p>
          </article>

          <article class="border-r border-slate-200 p-6 text-center md:p-8">
            <p class="font-montserrat text-5xl font-black text-red-600">10+</p>
            <p class="mt-3 font-montserrat text-xs font-semibold uppercase tracking-[0.14em] text-slate-400 md:text-sm">
              Tim Ahli
            </p>
          </article>

          <article class="p-6 text-center md:p-8">
            <p class="font-montserrat text-5xl font-black text-red-600">98%</p>
            <p class="mt-3 font-montserrat text-xs font-semibold uppercase tracking-[0.14em] text-slate-400 md:text-sm">
              Kepuasan Klien
            </p>
          </article>
        </div>
      </section>

      <section class="mt-8 rounded-2xl border border-slate-200 bg-white p-6 md:p-8">
        <h2 class="font-montserrat text-2xl font-bold text-slate-900">
          {{ $t('about.vision_title') }}
        </h2>
        <div class="mt-5 grid grid-cols-1 gap-4 md:grid-cols-2">
          <article class="rounded-xl border border-slate-200 p-4">
            <h3 class="font-montserrat text-base font-semibold text-slate-900">
              {{ $t('about.feature_1_title') }}
            </h3>
            <p class="mt-2 font-quicksand text-sm leading-6 text-slate-600">
              {{ $t('about.feature_1_desc') }}
            </p>
          </article>

          <article class="rounded-xl border border-slate-200 p-4">
            <h3 class="font-montserrat text-base font-semibold text-slate-900">
              {{ $t('about.feature_2_title') }}
            </h3>
            <p class="mt-2 font-quicksand text-sm leading-6 text-slate-600">
              {{ $t('about.feature_2_desc') }}
            </p>
          </article>
        </div>
      </section>
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
