<script setup>
defineProps({
    showButton: {
        type: Boolean,
        default: true
    }
})
</script>

<template>
    <section id="about" class="bg-white py-16 md:py-20">
        <div class="mx-auto max-w-6xl px-5 md:px-8">
            <div class="grid grid-cols-1 items-start gap-10 lg:grid-cols-2">
                <figure class="order-2 lg:order-1">
                    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-slate-100 shadow-sm">
                        <img src="/img/ibu-dian-melani.jpeg" alt="Dian Melani M.Psi - CEO" class="h-[320px] w-full object-cover md:h-[420px]" />
                    </div>
                    <figcaption class="mt-4 rounded-xl border border-slate-200 bg-white p-4">
                        <p class="font-montserrat text-sm font-semibold uppercase tracking-[0.12em] text-red-600">Melani Dian M.Psi</p>
                        <p class="mt-1 font-quicksand text-sm text-slate-600">Direktur Utama</p>
                    </figcaption>
                </figure>

                <div class="order-1 space-y-5 lg:order-2">
                    <h2 class="font-montserrat text-xs font-semibold uppercase tracking-[0.16em] text-red-600">
                        {{ $t('about.subtitle') }}
                    </h2>
                    <h3 class="font-montserrat text-3xl font-extrabold leading-tight text-slate-900 md:text-4xl">
                        {{ $t('about.title_main') }} <span class="text-red-600">{{ $t('about.title_highlight') }}</span>
                    </h3>
                    <p class="font-quicksand text-base leading-7 text-slate-600 md:text-lg">
                        {{ $t('about.desc_1') }}
                    </p>
                    <p class="font-quicksand text-base leading-7 text-slate-600">
                        {{ $t('about.desc_2') }}
                    </p>

                    <div class="grid grid-cols-1 gap-3 pt-2 sm:grid-cols-2">
                        <article class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                            <h4 class="font-montserrat text-sm font-semibold text-slate-900">{{ $t('about.feature_1_title') }}</h4>
                            <p class="mt-2 font-quicksand text-sm leading-6 text-slate-600">{{ $t('about.feature_1_desc') }}</p>
                        </article>
                        <article class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                            <h4 class="font-montserrat text-sm font-semibold text-slate-900">{{ $t('about.feature_2_title') }}</h4>
                            <p class="mt-2 font-quicksand text-sm leading-6 text-slate-600">{{ $t('about.feature_2_desc') }}</p>
                        </article>
                    </div>

                    <div v-if="showButton" class="pt-2">
                        <router-link
                            :to="$i18n.locale === 'id' ? '/id/tentang-kami' : '/en/about'"
                            class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-6 py-3 font-montserrat text-sm font-semibold text-white transition-colors duration-200 hover:bg-red-600"
                        >
                            {{ $t('about.button') }}
                            <span aria-hidden="true">-></span>
                        </router-link>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
