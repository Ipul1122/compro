<script setup>
import { RouterLink } from 'vue-router'
</script>

<template>
    <section id="our-services" class="py-32 relative overflow-hidden bg-white">
        <div class="max-w-7xl mx-auto px-4">

            <div class="text-center mb-20 relative z-10">
                <h2 class="text-brand font-bold tracking-[0.4em] uppercase text-sm mb-4 italic">
                    {{ $t('services_section.capabilities') }}
                </h2>
                <h3 class="text-4xl md:text-6xl font-black text-slate-900 mb-6 tracking-tight uppercase">
                    {{ $t('services_section.title') }}
                </h3>
                <p class="text-slate-600 text-lg md:text-xl max-w-2xl mx-auto leading-relaxed">
                    {{ $t('services_section.desc') }}
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6 relative z-10">

                <div
                    class="group p-8 rounded-3xl bg-white border border-slate-200 hover:border-brand/50 hover:scale-105 hover:shadow-2xl transition-all duration-500">
                    <div
                        class="w-12 h-12 mb-6 rounded-2xl bg-brand/10 flex items-center justify-center text-brand font-bold text-xl group-hover:bg-brand group-hover:text-white transition-all duration-500">
                        1
                    </div>
                    <h4 class="text-xl font-bold text-slate-900 mb-4">{{ $t('services_section.item1.title') }}</h4>
                    <p class="text-slate-600 text-sm leading-relaxed">
                        {{ $t('services_section.item1.desc1') }} <span class="text-slate-900 font-semibold">
                            {{ $t('services_section.item1.highlight') }}</span>.
                    </p>
                </div>

                <div
                    class="group p-8 rounded-3xl bg-white border border-slate-200 hover:border-brand/50 hover:scale-105 hover:shadow-2xl transition-all duration-500">
                    <div
                        class="w-12 h-12 mb-6 rounded-2xl bg-brand/10 flex items-center justify-center text-brand font-bold text-xl group-hover:bg-brand group-hover:text-white transition-all duration-500">
                        2
                    </div>
                    <h4 class="text-xl font-bold text-slate-900 mb-4">{{ $t('services_section.item2.title') }}</h4>
                    <ul class="space-y-4 text-xs text-slate-600">
                        <li class="border-b border-slate-100 pb-2"><strong
                                class="text-slate-900 block text-sm mb-1">{{ $t('services_section.item2.list1_title') }}</strong>
                                {{ $t('services_section.item2.list1_desc') }}</li>
                        <li class="border-b border-slate-100 pb-2"><strong
                                class="text-slate-900 block text-sm mb-1">{{ $t('services_section.item2.list2_title') }}</strong> 
                                {{ $t('services_section.item2.list2_desc') }}
                        </li>
                        <li><strong class="text-slate-900 block text-sm mb-1">{{ $t('services_section.item2.list3_title') }}</strong>
                            {{ $t('services_section.item2.list3_desc') }}</li>
                    </ul>
                </div>

                <div
                    class="group p-8 rounded-3xl bg-white border border-slate-200 hover:border-brand/50 hover:scale-105 hover:shadow-2xl transition-all duration-500">
                    <div
                        class="w-12 h-12 mb-6 rounded-2xl bg-brand/10 flex items-center justify-center text-brand font-bold text-xl group-hover:bg-brand group-hover:text-white transition-all duration-500">
                        3
                    </div>
                    <h4 class="text-xl font-bold text-slate-900 mb-4">{{ $t('services_section.item3.title') }}</h4>
                    <ul class="space-y-4 text-xs text-slate-600">
                        <li class="border-b border-slate-100 pb-2"><strong
                                class="text-slate-900 block text-sm mb-1">{{ $t('services_section.item3.list1_title') }}</strong> 
                                {{ $t('services_section.item3.list1_desc') }}</li>
                        <li class="border-b border-slate-100 pb-2"><strong
                                class="text-slate-900 block text-sm mb-1">{{ $t('services_section.item3.list2_title') }}</strong> 
                                {{ $t('services_section.item3.list2_desc') }}</li>
                        <li><strong class="text-slate-900 block text-sm mb-1">{{ $t('services_section.item3.list3_title') }}</strong>
                            {{ $t('services_section.item3.list3_desc') }}</li>
                    </ul>
                </div>

                <div
                    class="group p-8 rounded-3xl bg-white border border-slate-200 hover:border-brand/50 hover:scale-105 hover:shadow-2xl transition-all duration-500">
                    <div
                        class="w-12 h-12 mb-6 rounded-2xl bg-brand/10 flex items-center justify-center text-brand font-bold text-xl group-hover:bg-brand group-hover:text-white transition-all duration-500">
                        4
                    </div>
                    <h4 class="text-xl font-bold text-slate-900 mb-4">{{ $t('services_section.item4.title') }}</h4>
                    <ul class="space-y-4 text-xs text-slate-600">
                        <li class="border-b border-slate-100 pb-2"><strong
                                class="text-slate-900 block text-sm mb-1 italic">{{ $t('services_section.item4.list1_title') }}</strong>
                                {{ $t('services_section.item4.list1_desc') }}</li>
                        <li class="border-b border-slate-100 pb-2"><strong
                                class="text-slate-900 block text-sm mb-1 italic">{{ $t('services_section.item4.list2_title') }}</strong> 
                                {{ $t('services_section.item4.list2_desc') }}</li>
                        <li><strong class="text-slate-900 block text-sm mb-1 italic">{{ $t('services_section.item4.list3_title') }}</strong>
                            {{ $t('services_section.item4.list3_desc') }}</li>
                    </ul>
                </div>

            </div>

            <div class="mt-16 flex justify-center relative z-10">
                <RouterLink :to="$i18n.locale === 'id' ? '/id/layanan' : '/en/services'"
                    class="group flex items-center gap-3 bg-brand text-white px-8 py-4 rounded-full font-bold text-sm hover:scale-105 hover:shadow-xl hover:shadow-brand/20 transition-all duration-300">
                    {{ $t('services_section.btn_view_all') }}
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </RouterLink>
            </div>

            <div
                class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 text-[15rem] font-black text-slate-900/[0.03] pointer-events-none select-none -z-10 tracking-tighter">
                {{ $t('services_section.watermark') }}
            </div>
        </div>
    </section>
</template>