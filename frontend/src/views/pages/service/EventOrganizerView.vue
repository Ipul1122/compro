<script setup>
import { useI18n } from 'vue-i18n';
import { Swiper, SwiperSlide } from 'swiper/vue';
import { Autoplay, Pagination, EffectFade } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/pagination';
import 'swiper/css/effect-fade';
import { computed } from 'vue';

const { t } = useI18n();

const images = [
    '/img/tubel1.jpg',
    '/img/tubel2.jpg',
    '/img/tubel3.jpeg',
    '/img/tubel4.jpeg',
    '/img/tubel5.jpeg'
];

const orgService = computed(() => ({
  badge: t('service_view.event.org_badge'),
  title: t('service_view.event.org_title'),
  desc: t('service_view.event.org_desc'),
  features: [
    {
      title: t('service_view.event.org_features.feat1_title'),
      desc: t('service_view.event.org_features.feat1_desc'),
      icon: 'hierarchy',
      accent: 'red'
    },
    {
      title: t('service_view.event.org_features.feat2_title'),
      desc: t('service_view.event.org_features.feat2_desc'),
      icon: 'wla',
      accent: 'orange'
    },
    {
      title: t('service_view.event.org_features.feat3_title'),
      desc: t('service_view.event.org_features.feat3_desc'),
      icon: 'jobdesc',
      accent: 'red'
    },
    {
      title: t('service_view.event.org_features.feat4_title'),
      desc: t('service_view.event.org_features.feat4_desc'),
      icon: 'grading',
      accent: 'orange'
    }
  ],
  processTitle: t('service_view.event.org_process_title'),
  processDesc: t('service_view.event.org_process_desc'),
  steps: [
    {
      step: '01',
      title: t('service_view.event.org_steps.step1_title'),
      desc: t('service_view.event.org_steps.step1_desc'),
      accentColor: 'red'
    },
    {
      step: '02',
      title: t('service_view.event.org_steps.step2_title'),
      desc: t('service_view.event.org_steps.step2_desc'),
      accentColor: 'orange'
    },
    {
      step: '03',
      title: t('service_view.event.org_steps.step3_title'),
      desc: t('service_view.event.org_steps.step3_desc'),
      accentColor: 'red'
    },
    {
      step: '04',
      title: t('service_view.event.org_steps.step4_title'),
      desc: t('service_view.event.org_steps.step4_desc'),
      accentColor: 'orange'
    }
  ]
}));
</script>

<template>
    <main class="relative bg-white font-montserrat z-0">
        <div class="absolute inset-0 bg-[linear-gradient(to_right,#f1f5f9_1px,transparent_1px),linear-gradient(to_bottom,#f1f5f9_1px,transparent_1px)] bg-[size:4rem_4rem] [mask-image:radial-gradient(ellipse_80%_50%_at_50%_0%,#000_70%,transparent_100%)] -z-10 pointer-events-none"></div>

       <section id="event-organizer" class="py-32 relative overflow-hidden bg-slate-50 border-y border-gray-100"> <div class="max-w-7xl mx-auto px-4">
                <div class="grid lg:grid-cols-2 gap-16 lg:gap-24 items-start">

                    <div class="relative order-2 lg:order-1 mb-16 lg:mb-0">
                        <div class="inline-flex items-center gap-3 px-4 py-1.5 rounded-full bg-orange-50 border border-orange-100 text-orange-600 text-xs font-bold uppercase tracking-[0.2em] mb-8">
                            <span class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-orange-500 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-orange-600"></span>
                            </span>
                            {{ $t('service_view.event.badge') }}
                        </div>

                        <h2 class="text-4xl md:text-5xl font-black text-gray-900 leading-[1.1] tracking-tight uppercase">
                            {{ $t('service_view.event.title1') }} <br />
                            <span class="text-orange-600">
                                {{ $t('service_view.event.title_highlight') }}
                            </span>
                        </h2>

                        <p class="mt-6 text-gray-500 text-lg leading-relaxed font-medium">
                            {{ $t('service_view.event.desc') }}
                        </p>

                        <div class="mt-12 space-y-10">
                            <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm hover:shadow-lg transition-shadow duration-300">
                                <h3 class="text-xl font-extrabold text-gray-900 flex items-center gap-3 border-b border-gray-100 pb-4">
                                    <span class="text-orange-600 text-sm font-black bg-orange-50 px-2 py-1 rounded">01</span>
                                    {{ $t('service_view.event.subtitle') }}
                                </h3>
                                <ul class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4 text-sm text-gray-600 font-medium">
                                    <li class="flex items-center gap-3">
                                        <span class="text-orange-600">✔</span> {{ $t('service_view.event.list1') }}
                                    </li>
                                    <li class="flex items-center gap-3">
                                        <span class="text-orange-600">✔</span> {{ $t('service_view.event.list2') }}
                                    </li>
                                    <li class="flex items-center gap-3">
                                        <span class="text-orange-600">✔</span> {{ $t('service_view.event.list3') }}
                                    </li>
                                    <li class="flex items-center gap-3">
                                        <span class="text-orange-600">✔</span> {{ $t('service_view.event.list4') }}
                                    </li>
                                </ul>
                            </div>

                            <div class="p-8 rounded-3xl bg-gray-900 relative overflow-hidden shadow-xl">
                                <div class="absolute top-0 right-0 p-4 opacity-10">
                                    <svg class="w-24 h-24 text-white" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z" />
                                    </svg>
                                </div>
                                <h4 class="text-orange-500 font-black uppercase text-xs tracking-widest mb-4">
                                    {{ $t('service_view.event.quote_title') }}
                                </h4>
                                <p class="text-gray-300 text-sm md:text-base leading-relaxed italic relative z-10 font-medium">
                                    "{{ $t('service_view.event.quote1') }} 
                                    <span class="text-white font-bold bg-orange-600/20 px-1 rounded">
                                        {{ $t('service_view.event.quote_highlight') }}
                                    </span> 
                                    {{ $t('service_view.event.quote2') }}"
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="relative order-1 lg:order-2">
                        <div class="lg:sticky lg:top-32">
                            <div class="relative group">
                                
                                <div class="aspect-[4/3] w-full bg-gray-100 rounded-[2rem] border border-gray-100 overflow-hidden shadow-xl relative z-10">
                                    <Swiper
                                        :modules="[Autoplay, Pagination, EffectFade]"
                                        :effect="'fade'"
                                        :pagination="{ clickable: true }"
                                        :autoplay="{ delay: 3500, disableOnInteraction: false }"
                                        :loop="true"
                                        class="w-full h-full"
                                    >
                                        <SwiperSlide v-for="(img, index) in images" :key="index" class="w-full h-full relative">
                                            <img :src="img" class="absolute inset-0 w-full h-full object-cover" :alt="'Event ' + index" />
                                            <div class="absolute inset-0 bg-gradient-to-t from-gray-900/90 via-gray-900/20 to-transparent"></div>
                                            
                                            <!-- Logo Overlay -->
                                            <div class="absolute top-6 left-6 md:top-8 md:left-8 z-20">
                                                <div class="bg-white/90 backdrop-blur-sm p-3 rounded-2xl shadow-xl flex items-center justify-center">
                                                    <img src="/img/DPR-RI-LOGO.png" alt="DPR RI Logo" class="h-10 w-auto object-contain" />
                                                </div>
                                            </div>

                                            <div class="absolute bottom-12 left-6 md:bottom-14 md:left-8 z-20">
                                                <p class="text-orange-500 font-bold tracking-[0.2em] uppercase text-xs mb-2">Moment 0{{ index + 1 }}</p>
                                                <h3 class="text-2xl md:text-4xl font-black text-white tracking-tighter drop-shadow-lg font-montserrat">
                                                    Event Highlight
                                                </h3>
                                            </div>
                                        </SwiperSlide>
                                    </Swiper>
                                </div>

                                <div class="absolute -bottom-8 -right-8 px-8 py-5 bg-white border border-gray-100 rounded-2xl shadow-xl z-20 hidden md:block group-hover:-translate-y-2 transition-transform duration-300">
                                    <p class="text-gray-900 font-black text-3xl mb-1">100<span class="text-orange-600">+</span></p>
                                    <p class="text-[10px] uppercase tracking-[0.2em] text-gray-500 font-bold">
                                        {{ $t('service_view.event.events_managed') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <!-- Section: Jasa Pembuatan Struktur Organisasi -->
        <section id="org-service" class="py-24 relative overflow-hidden bg-white border-t border-gray-100">
            <!-- Background Decorative Grid -->
            <div class="absolute inset-0 bg-[radial-gradient(#e2e8f0_1px,transparent_1px)] [background-size:20px_20px] opacity-60 pointer-events-none"></div>

            <div class="max-w-7xl mx-auto px-4 relative z-10">
                <!-- Section Header -->
                <div class="text-center max-w-3xl mx-auto mb-20">
                    <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-red-50 border border-red-100 text-red-600 text-xs font-bold uppercase tracking-[0.2em] mb-4 font-quicksand">
                        {{ orgService.badge }}
                    </span>
                    <h2 class="text-3xl md:text-5xl font-black text-gray-900 tracking-tight uppercase font-montserrat">
                        {{ orgService.title }}
                    </h2>
                    <p class="mt-4 text-gray-500 text-lg leading-relaxed font-medium font-quicksand">
                        {{ orgService.desc }}
                    </p>
                    <div class="flex items-center justify-center gap-2 mt-6">
                        <div class="w-12 h-1 bg-red-600 rounded"></div>
                        <div class="w-4 h-1 bg-orange-600 rounded"></div>
                        <div class="w-12 h-1 bg-red-600 rounded"></div>
                    </div>
                </div>

                <!-- Scope of Services (Features) Grid -->
                <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8 mb-24">
                    <div v-for="feat in orgService.features" :key="feat.title" class="bg-white p-8 rounded-3xl border border-slate-100 hover:border-red-100 shadow-md hover:shadow-2xl transition-all duration-300 flex flex-col justify-between group transform hover:-translate-y-2 relative overflow-hidden">
                        <!-- Left decorative accent bar -->
                        <div :class="feat.accent === 'red' ? 'bg-red-600' : 'bg-orange-600'" class="absolute inset-y-0 left-0 w-1.5 group-hover:w-2 transition-all"></div>
                        
                        <div class="pl-4">
                            <!-- Icon Circle with background -->
                            <div :class="feat.accent === 'red' ? 'bg-red-50 text-red-600' : 'bg-orange-50 text-orange-600'" class="w-14 h-14 rounded-2xl flex items-center justify-center mb-6 shadow-inner">
                                <!-- Dynamic SVG based on icon key -->
                                <!-- 1. hierarchy -->
                                <svg v-if="feat.icon === 'hierarchy'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-7 h-7">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                                </svg>
                                <!-- 2. wla -->
                                <svg v-else-if="feat.icon === 'wla'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-7 h-7">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                                <!-- 3. jobdesc -->
                                <svg v-else-if="feat.icon === 'jobdesc'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-7 h-7">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                </svg>
                                <!-- 4. grading -->
                                <svg v-else-if="feat.icon === 'grading'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-7 h-7">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                                </svg>
                            </div>
                            <!-- Title -->
                            <h3 class="text-xl font-bold font-montserrat text-gray-900 group-hover:text-red-600 transition-colors mb-3">
                                {{ feat.title }}
                            </h3>
                            <!-- Description -->
                            <p class="text-sm text-gray-500 font-quicksand font-medium leading-relaxed">
                                {{ feat.desc }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Workflow (Process) Title & Desc -->
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <h3 class="text-2xl md:text-3xl font-black text-gray-900 tracking-tight uppercase font-montserrat">
                        {{ orgService.processTitle }}
                    </h3>
                    <p class="mt-3 text-gray-500 font-quicksand font-medium">
                        {{ orgService.processDesc }}
                    </p>
                </div>

                <!-- Process Steps Grid -->
                <div class="relative">
                    <!-- Progress Line (Desktop) -->
                    <div class="hidden lg:block absolute top-1/2 left-8 right-8 h-0.5 bg-gradient-to-r from-red-600 via-orange-500 to-orange-600 -translate-y-1/2 -z-10"></div>
                    
                    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                        <div v-for="step in orgService.steps" :key="step.step" class="bg-slate-50 p-6 rounded-2xl relative overflow-hidden group border border-slate-100 hover:border-orange-200 transition-all duration-300">
                            <!-- Step Number Top Right -->
                            <div class="absolute -right-4 -top-4 text-7xl font-black opacity-5 group-hover:opacity-10 transition-opacity font-montserrat">
                                {{ step.step }}
                            </div>
                            
                            <!-- Small Accent dot/bar -->
                            <div class="flex items-center gap-3 mb-4">
                                <div :class="step.accentColor === 'red' ? 'bg-red-600' : 'bg-orange-600'" class="w-8 h-8 rounded-lg text-white flex items-center justify-center font-bold text-sm font-montserrat">
                                    {{ step.step }}
                                </div>
                                <div :class="step.accentColor === 'red' ? 'bg-red-600' : 'bg-orange-600'" class="h-0.5 w-12 rounded"></div>
                            </div>

                            <!-- Content -->
                            <h4 class="text-lg font-bold text-gray-900 font-montserrat mb-2">
                                {{ step.title }}
                            </h4>
                            <p class="text-xs text-gray-500 leading-relaxed font-quicksand font-semibold">
                                {{ step.desc }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
</template>
