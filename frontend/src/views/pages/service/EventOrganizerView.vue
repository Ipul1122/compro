<script setup>
import { useI18n } from 'vue-i18n';
import { Swiper, SwiperSlide } from 'swiper/vue';
import { Autoplay, Pagination, EffectFade } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/pagination';
import 'swiper/css/effect-fade';

const { t } = useI18n();

const images = [
    '/img/tubel1.jpg',
    '/img/tubel2.jpg',
    '/img/tubel3.jpeg',
    '/img/tubel4.jpeg',
    '/img/tubel5.jpeg'
];
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
    </main>
</template>
