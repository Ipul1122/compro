<script setup>
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

const interiorImages = [
    '/img/interior.jpg',
    '/img/servicesSection/interio-design-1.jpeg',
    '/img/servicesSection/interio-design-2.jpeg',
    '/img/servicesSection/interio-design-3.jpeg',
    '/img/servicesSection/interio-design-4.jpeg'
];
</script>

<template>
    <section id="interior-design" class="py-24 relative overflow-hidden font-montserrat z-0">
        <div class="max-w-7xl mx-auto px-4 mt-14">
            
            <div class="grid lg:grid-cols-2 gap-20 items-center">

                <div class="relative z-10">
                    <span class="inline-block py-1.5 px-4 rounded-full bg-orange-50 text-orange-600 font-bold tracking-[0.2em] uppercase text-xs mb-6 border border-orange-100">
                        Space & Comfort
                    </span>
                    <h2 class="text-4xl md:text-5xl font-black text-gray-900 leading-[1.1] tracking-tight uppercase">
                        {{ $t('service_view.interior.title1') }} <br />
                        <span class="text-orange-600">{{ $t('service_view.interior.title_highlight') }}</span> {{ $t('service_view.interior.title2') }}
                    </h2>

                    <div class="mt-8 space-y-6">
                        <p class="text-lg md:text-xl text-gray-600 leading-relaxed font-medium">
                            {{ $t('service_view.interior.desc1') }} <span class="text-gray-900 font-extrabold">{{ $t('service_view.interior.desc1_bold') }}</span>
                            {{ $t('service_view.interior.desc1_after') }}
                        </p>
                        <p class="text-gray-500 leading-relaxed text-sm md:text-base">
                            {{ $t('service_view.interior.desc2') }}
                        </p>
                    </div>

                    <div class="mt-12 flex items-center gap-8 bg-white p-6 rounded-2xl border border-gray-100 shadow-sm w-fit">
                        <div>
                            <p class="text-4xl font-black text-gray-900">10<span class="text-orange-600">+</span></p>
                            <p class="text-xs uppercase tracking-widest text-gray-400 font-bold mt-1">
                                {{ $t('service_view.interior.years_exp') }}
                            </p>
                        </div>
                        <div class="w-px h-12 bg-gray-200"></div>
                        <div>
                            <p class="text-4xl font-black text-gray-900">100<span class="text-orange-600">%</span></p>
                            <p class="text-xs uppercase tracking-widest text-gray-400 font-bold mt-1">
                                {{ $t('service_view.interior.quality') }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="relative group">
                    
                    <div class="absolute -top-6 -left-6 z-50 bg-gray-900 px-6 py-4 rounded-2xl shadow-xl transform -rotate-3 group-hover:rotate-0 transition-transform duration-500 border border-gray-700">
                        <p class="text-white font-black text-2xl leading-none">{{ $t('service_view.interior.badge_title') }}</p>
                        <p class="text-orange-400 text-sm font-bold uppercase tracking-widest mt-1">{{ $t('service_view.interior.badge_subtitle') }}</p>
                    </div>

                    <div class="aspect-[4/5] w-full flex bg-gray-100 rounded-[2rem] border border-gray-200 overflow-hidden relative shadow-lg group-hover:shadow-2xl transition-all duration-500">
                        
                        <div v-for="(img, index) in interiorImages" :key="index"
                            class="group/item relative h-full flex-1 hover:flex-[5] transition-all duration-700 ease-in-out cursor-pointer z-10 hover:z-[99] border-r border-white/20 last:border-r-0 overflow-hidden">
                            
                            <img :src="img" :alt="'Interior ' + index" class="absolute inset-0 w-full h-full object-cover" />
                            
                            <div class="absolute inset-0 bg-gray-900/50 group-hover/item:bg-gradient-to-t group-hover/item:from-gray-600/80 group-hover/item:via-transparent group-hover/item:to-transparent transition-all duration-500"></div>

                            <div class="absolute bottom-10 left-8 z-20 opacity-0 group-hover/item:opacity-100 transition-all duration-700 delay-100 translate-y-4 group-hover/item:translate-y-0 whitespace-nowrap">
                                <p class="text-orange-500 font-bold tracking-[0.2em] uppercase text-xs mb-2">Style 0{{ index + 1 }}</p>
                                <h3 class="text-3xl md:text-4xl font-black text-white tracking-tighter drop-shadow-lg">
                                    {{ $t('service_view.interior.watermark') }}
                                </h3>
                            </div>
                            
                        </div>
                        
                    </div>

                    <div class="absolute -bottom-6 -right-6 w-full h-full border-2 border-orange-600/20 rounded-[2rem] -z-10 group-hover:translate-x-2 group-hover:translate-y-2 transition-transform duration-500"></div>
                    
                </div>

            </div>
        </div>
    </section>
</template>