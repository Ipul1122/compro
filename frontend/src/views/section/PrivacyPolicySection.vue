<script setup>
import { useI18n } from 'vue-i18n'
const { t } = useI18n()
</script>

<template>
  <section class="py-20 bg-white font-montserrat">
    <div class="max-w-4xl mx-auto px-6">
      <div class="mb-12 text-center">
        <h1 class="text-4xl font-black text-slate-900 mb-4 mt-16">{{ t('privacy_policy.title') }}</h1>
        <p class="text-sm text-slate-500 italic">{{ t('privacy_policy.last_updated') }}</p>
      </div>

      <div class="space-y-10 text-slate-700 leading-relaxed">
        <div>
          <h2 class="text-xl font-bold text-slate-900 mb-3">{{ t('privacy_policy.sections.intro_title') }}</h2>
          <p>{{ t('privacy_policy.sections.intro_text') }}</p>
        </div>

        <div>
          <h2 class="text-xl font-bold text-slate-900 mb-3">{{ t('privacy_policy.sections.collection_title') }}</h2>
          <p>{{ t('privacy_policy.sections.collection_text') }}</p>
        </div>

        <div>
          <h2 class="text-xl font-bold text-slate-900 mb-3">{{ t('privacy_policy.sections.usage_title') }}</h2>
          <p>{{ t('privacy_policy.sections.usage_text') }}</p>
        </div>

        <div>
          <h2 class="text-xl font-bold text-slate-900 mb-3">{{ t('privacy_policy.sections.security_title') }}</h2>
          <p>{{ t('privacy_policy.sections.security_text') }}</p>
        </div>

        <div class="p-6 bg-orange-50 rounded-2xl border border-orange-100">
          <h2 class="text-xl font-bold text-orange-600 mb-3">{{ t('privacy_policy.sections.contact_title') }}</h2>
          <p>{{ t('privacy_policy.sections.contact_text') }}</p>
        </div>
      </div>
    </div>
  </section>
</template>