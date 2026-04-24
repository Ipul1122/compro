<script setup>
import { useI18n } from 'vue-i18n'
const { t } = useI18n()
</script>

<template>
  <section class="py-20 bg-white font-montserrat">
    <div class="max-w-4xl mx-auto px-6">
      <div class="mb-12 text-center">
        <h1 class="text-4xl font-black text-slate-900 mb-4">{{ t('terms_of_service.title') }}</h1>
        <p class="text-sm text-slate-500 italic">{{ t('terms_of_service.last_updated') }}</p>
      </div>

      <div class="space-y-10 text-slate-700 leading-relaxed text-justify">
        <div>
          <h2 class="text-xl font-bold text-slate-900 mb-3">{{ t('terms_of_service.sections.acceptance_title') }}</h2>
          <p>{{ t('terms_of_service.sections.acceptance_text') }}</p>
        </div>

        <div>
          <h2 class="text-xl font-bold text-slate-900 mb-3">{{ t('terms_of_service.sections.services_title') }}</h2>
          <p>{{ t('terms_of_service.sections.services_text') }}</p>
        </div>

        <div>
          <h2 class="text-xl font-bold text-slate-900 mb-3">{{ t('terms_of_service.sections.intellectual_title') }}</h2>
          <p>{{ t('terms_of_service.sections.intellectual_text') }}</p>
        </div>

        <div>
          <h2 class="text-xl font-bold text-slate-900 mb-3">{{ t('terms_of_service.sections.limitation_title') }}</h2>
          <p>{{ t('terms_of_service.sections.limitation_text') }}</p>
        </div>

        <div>
          <h2 class="text-xl font-bold text-slate-900 mb-3">{{ t('terms_of_service.sections.governing_title') }}</h2>
          <p>{{ t('terms_of_service.sections.governing_text') }}</p>
        </div>

        <div class="p-6 bg-slate-50 rounded-2xl border border-slate-100">
          <h2 class="text-xl font-bold text-slate-900 mb-3">{{ t('terms_of_service.sections.changes_title') }}</h2>
          <p>{{ t('terms_of_service.sections.changes_text') }}</p>
        </div>
      </div>
    </div>
  </section>
</template>