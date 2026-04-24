<script setup>
// Menambahkan konfigurasi prop agar tombol bisa dikontrol dari luar
defineProps({
    showButton: {
        type: Boolean,
        default: true // Secara default tombol akan tampil (misal saat dipanggil di HomeView)
    }
})
</script>

<template>
    <section id="about" class="py-24 bg-white overflow-hidden relative">

        <!-- Orange-500 Background Grid -->
        <div class="absolute inset-0 pointer-events-none" aria-hidden="true">
            <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse">
                        <path d="M 40 0 L 0 0 0 40" fill="none" stroke="#f97316" stroke-width="0.5" stroke-opacity="0.25"/>
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#grid)" />
            </svg>
        </div>

        <div class="max-w-7xl mx-auto px-6 relative">
            <div class="flex flex-col lg:flex-row items-center gap-12 lg:gap-20">

                <div class="w-full lg:w-1/2 relative group">
                    <div class="absolute -top-6 -left-6 w-32 h-32 bg-brand/10 rounded-full blur-3xl group-hover:bg-brand/20 transition-all duration-700"></div>

                    <div class="relative rounded-3xl overflow-hidden shadow-2xl transition-transform duration-500 hover:scale-[1.02]">
                        <img src="/src/img/skyscraper.jpg" alt="Our Creative Team" class="w-full h-[400px] md:h-[500px] object-cover" />
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/40 to-transparent"></div>
                    </div>

                    <div class="absolute -bottom-6 -right-6 bg-white p-6 rounded-2xl shadow-xl border border-slate-50 hidden md:block">
                        <p class="text-3xl font-black text-brand">10+</p>
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-widest">
                            {{ $t('about.years_experience') }}
                        </p>
                    </div>
                </div>

                <div class="w-full lg:w-1/2">
                    <div class="space-y-6">
                        <div>
                            <!-- Subtitle -->
                            <h2 class="text-xs font-bold text-orange-600 uppercase tracking-[0.3em] mb-4">
                                {{ $t('about.subtitle') }}
                            </h2>
                            <!-- Main Title -->
                            <h3 class="font-montserrat text-4xl md:text-5xl font-black text-slate-900 tracking-tighter leading-tight">
                                {{ $t('about.title_main') }} <span class="text-orange-600">{{ $t('about.title_highlight') }}</span>
                            </h3>
                        </div>

                        <!-- Description -->
                        <p class="font-poppins text-lg text-slate-600 leading-relaxed">
                            {{ $t('about.desc_1') }}
                        </p>
                        <!-- Description 2 -->
                        <p class="font-poppins text-slate-600 leading-relaxed">
                            {{ $t('about.desc_2') }}
                        </p>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4">
                            <div class="flex gap-4 p-4 rounded-2xl transition-colors hover:bg-slate-50">
                                <div class="flex-shrink-0 w-12 h-12 bg-white shadow-sm rounded-xl flex items-center justify-center text-brand border border-slate-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-montserrat font-bold text-slate-900">{{ $t('about.feature_1_title') }}</h4>
                                    <p class="font-poppins text-sm text-slate-500">{{ $t('about.feature_1_desc') }}</p>
                                </div>
                            </div>

                            <div class="flex gap-4 p-4 rounded-2xl transition-colors hover:bg-slate-50">
                                <div class="flex-shrink-0 w-12 h-12 bg-white shadow-sm rounded-xl flex items-center justify-center text-brand border border-slate-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 21l3-2 3 2-.75-4M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-montserrat font-bold text-slate-900">{{ $t('about.feature_2_title') }}</h4>
                                    <p class="font-poppins text-sm text-slate-500">{{ $t('about.feature_2_desc') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="pt-8" v-if="showButton">
                            <router-link :to="$i18n.locale === 'id' ? '/id/tentang-kami' : '/en/about'"
                                class="inline-flex items-center gap-3 bg-slate-900 text-white px-8 py-4 rounded-2xl font-black hover:bg-brand transition-all duration-300 shadow-xl hover:shadow-brand/20 active:scale-95">
                                {{ $t('about.button') }}
                                <span class="text-xl">→</span>
                            </router-link>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
</template>