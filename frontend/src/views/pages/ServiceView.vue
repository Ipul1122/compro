<script setup>
import { ref, computed } from 'vue'
import { Swiper, SwiperSlide } from 'swiper/vue';
import { Autoplay, Pagination, EffectFade } from 'swiper/modules';
import { useI18n } from 'vue-i18n';
import 'swiper/css';
import 'swiper/css/pagination';
import 'swiper/css/effect-fade';

const { t } = useI18n();




// Dibungkus computed agar nama preview LMS reaktif ketika bahasa dirubah
const lmsPreviews = computed(() => [
    { name: t('service_view.lms.preview.login'), path: '/img/login-dua.png' },
    { name: t('service_view.lms.preview.upload'), path: '/img/upload-tugas.png' },
    { name: t('service_view.lms.preview.quiz'), path: '/img/kuis.png' },
    { name: t('service_view.lms.preview.results'), path: '/img/kuis-dua.png' }
]);

const projectSteps = [
    { id: 1, items: 2 },
    { id: 2, items: 3 },
    { id: 3, items: 1 },
    { id: 6, items: 1 },
    { id: 5, items: 1 },
    { id: 4, items: 1 }
];

const isModalOpen = ref(false)
const selectedImage = ref(null)

const openModal = (item) => {
    selectedImage.value = item
    isModalOpen.value = true
}

const closeModal = () => {
    isModalOpen.value = false
    selectedImage.value = null
}
</script>

<template>
    <main class="relative bg-white font-montserrat z-0">
        <div class="absolute inset-0 bg-[linear-gradient(to_right,#f1f5f9_1px,transparent_1px),linear-gradient(to_bottom,#f1f5f9_1px,transparent_1px)] bg-[size:4rem_4rem] [mask-image:radial-gradient(ellipse_80%_50%_at_50%_0%,#000_70%,transparent_100%)] -z-10 pointer-events-none"></div>

        <section id="lms" class="py-32 relative border-t border-gray-100 overflow-hidden bg-white/80">
            <div class="max-w-7xl mx-auto px-4">
                <div class="grid lg:grid-cols-[1.2fr,1fr] gap-12 lg:gap-20 items-center">

                    <div class="relative z-10">
                        <div class="inline-block px-4 py-1.5 rounded-full bg-white border border-gray-200 text-gray-900 text-[10px] font-bold uppercase tracking-[0.3em] mb-8 shadow-sm">
                            {{ $t('service_view.lms.badge') }}
                        </div>

                        <h2 class="text-4xl md:text-5xl font-black text-gray-900 leading-[1.1] tracking-tight uppercase mb-6">
                            {{ $t('service_view.lms.title1') }} <br />
                            <span class="text-red-600">{{ $t('service_view.lms.title_highlight') }}</span>
                        </h2>

                        <p class="text-lg text-gray-500 leading-relaxed font-medium max-w-2xl mb-8">
                            {{ $t('service_view.lms.desc') }}
                        </p>

                        <div class="flex flex-wrap gap-3 mb-12">
                            <span class="px-4 py-2 bg-white border border-gray-200 rounded-full text-xs font-bold text-gray-700 shadow-sm">
                                📚 {{ $t('service_view.lms.tag1') }}
                            </span>
                            <span class="px-4 py-2 bg-white border border-gray-200 rounded-full text-xs font-bold text-gray-700 shadow-sm">
                                🤝 {{ $t('service_view.lms.tag2') }}
                            </span>
                            <span class="px-4 py-2 bg-white border border-gray-200 rounded-full text-xs font-bold text-gray-700 shadow-sm">
                                📱 {{ $t('service_view.lms.tag3') }}
                            </span>
                            <span class="px-4 py-2 bg-white border border-gray-200 rounded-full text-xs font-bold text-gray-700 shadow-sm">
                                👨‍🏫 {{ $t('service_view.lms.tag4') }}
                            </span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 mb-12">
                            <div class="bg-gray-50 p-6 rounded-2xl border border-gray-100">
                                <h4 class="text-gray-900 font-extrabold uppercase tracking-widest text-xs mb-4">
                                    {{ $t('service_view.lms.features_title') }}
                                </h4>
                                <ul class="space-y-3">
                                    <li class="flex items-start gap-3 text-sm text-gray-600 font-medium"><span class="text-red-600">✔</span> {{ $t('service_view.lms.feature1') }}</li>
                                    <li class="flex items-start gap-3 text-sm text-gray-600 font-medium"><span class="text-red-600">✔</span> {{ $t('service_view.lms.feature2') }}</li>
                                    <li class="flex items-start gap-3 text-sm text-gray-600 font-medium"><span class="text-red-600">✔</span> {{ $t('service_view.lms.feature3') }}</li>
                                    <li class="flex items-start gap-3 text-sm text-gray-600 font-medium"><span class="text-red-600">✔</span> {{ $t('service_view.lms.feature4') }}</li>
                                </ul>
                            </div>
                            <div class="bg-gray-50 p-6 rounded-2xl border border-gray-100">
                                <h4 class="text-gray-900 font-extrabold uppercase tracking-widest text-xs mb-4">
                                    {{ $t('service_view.lms.benefits_title') }}
                                </h4>
                                <ul class="space-y-3">
                                    <li class="flex items-start gap-3 text-sm text-gray-600">
                                        <span class="text-green-500 font-black">↑</span> 
                                        <span><b class="text-gray-900">{{ $t('service_view.lms.benefit1_bold') }}</b> {{ $t('service_view.lms.benefit1_desc') }}</span>
                                    </li>
                                    <li class="flex items-start gap-3 text-sm text-gray-600">
                                        <span class="text-green-500 font-black">↑</span> 
                                        <span><b class="text-gray-900">{{ $t('service_view.lms.benefit2_bold') }}</b> {{ $t('service_view.lms.benefit2_desc') }}</span>
                                    </li>
                                    <li class="flex items-start gap-3 text-sm text-gray-600">
                                        <span class="text-green-500 font-black">↑</span> 
                                        <span><b class="text-gray-900">{{ $t('service_view.lms.benefit3_bold') }}</b> {{ $t('service_view.lms.benefit3_desc') }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="flex items-center gap-6">
                            <button class="bg-gray-900 text-white px-8 py-4 rounded-full font-bold text-sm hover:bg-red-600 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                                {{ $t('service_view.lms.cta_btn') }}
                            </button>
                            <div class="flex flex-col">
                                <span class="text-gray-900 font-extrabold text-sm">{{ $t('service_view.lms.cta_title') }}</span>
                                <span class="text-gray-500 text-xs font-medium">{{ $t('service_view.lms.cta_desc') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="relative">
                        <div class="grid grid-cols-2 gap-4 relative z-10">
                            <div v-for="(item, index) in lmsPreviews" :key="index" 
                                @click="openModal(item)" 
                                class="group cursor-pointer relative aspect-[4/3] rounded-2xl overflow-hidden border border-gray-200 shadow-sm hover:shadow-xl transition-all duration-300"
                                :class="{'translate-y-6': index % 2 !== 0}"> <img :src="item.path" :alt="item.name" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"/>
                                
                                <div class="absolute inset-0 bg-gray-900/60 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col items-center justify-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white translate-y-4 group-hover:translate-y-0 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                                    </svg>
                                    <span class="text-white font-bold text-xs uppercase tracking-widest translate-y-4 group-hover:translate-y-0 transition-transform duration-300 delay-75">
                                        {{ item.name }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full h-full bg-red-100 rounded-full blur-[100px] -z-10"></div>
                    </div>

                </div>
            </div>
        </section>

        <section id="hc-development" class="py-32 relative bg-gray-50 border-t border-gray-100">
            <div class="max-w-7xl mx-auto px-4">
                <div class="text-center mb-16">
                    <div class="inline-block px-4 py-1.5 rounded-full bg-white border border-gray-200 text-gray-900 text-[10px] font-bold uppercase tracking-[0.3em] mb-4 shadow-sm">
                        {{ $t('service_view.hc.badge') }}
                    </div>
                    <h2 class="text-4xl md:text-5xl font-black text-gray-900 leading-tight uppercase mb-6">
                        {{ $t('service_view.hc.title') }}
                    </h2>
                    <p class="text-lg text-gray-500 max-w-3xl mx-auto font-medium">
                        {{ $t('service_view.hc.desc') }}
                    </p>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                    <div v-for="i in 9" :key="i" class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                        <div class="w-12 h-12 bg-red-50 text-red-600 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300">
                            <svg v-if="i===1" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                            <svg v-else-if="i===2" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                            <svg v-else-if="i===3" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path></svg>
                            <svg v-else-if="i===4" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <svg v-else-if="i===5" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                            <svg v-else-if="i===6" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            <svg v-else-if="i===7" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                            <svg v-else-if="i===8" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                            <svg v-else class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"></path></svg>
                        </div>
                        <h3 class="text-gray-900 font-bold text-sm leading-relaxed">{{ $t(`service_view.hc.list${i}`) }}</h3>
                    </div>
                </div>
            </div>
        </section>

        <section id="alur-project" class="py-32 relative bg-[#fefdf8] border-t border-gray-100 overflow-hidden font-montserrat">
            <!-- Graph Paper Background -->
            <div class="absolute inset-0 z-0" style="background-image: linear-gradient(to right, #f0f0f0 1px, transparent 1px), linear-gradient(to bottom, #f0f0f0 1px, transparent 1px); background-size: 40px 40px;"></div>
            
            <div class="max-w-7xl mx-auto px-4 relative z-10">
                <div class="text-center mb-24">
                    <h2 class="text-5xl md:text-7xl font-black text-gray-900 uppercase tracking-tighter mb-4" style="text-shadow: 2px 2px 0px rgba(0,0,0,0.1);">
                        {{ $t('service_view.alur.title') }}
                    </h2>
                    <h3 class="text-xl md:text-2xl font-bold text-gray-900 tracking-tight">
                        {{ $t('service_view.alur.subtitle') }}
                    </h3>
                </div>

                <div class="relative max-w-6xl mx-auto flex flex-col items-center">
                    
                    <!-- Top Row -->
                    <div class="flex flex-col md:flex-row w-full justify-between items-start relative">
                        <!-- Step 1: Site Visit -->
                        <div class="w-full md:w-[28%] flex flex-col z-10 relative">
                            <div class="bg-[#ccff00] text-gray-900 font-black px-6 py-4 rounded-full shadow-md text-center text-lg md:text-xl border-2 border-transparent hover:scale-105 transition-transform cursor-default">
                                {{ $t('service_view.alur.step1_title') }}
                            </div>
                            <ul class="mt-6 text-sm font-bold text-gray-900 space-y-3 px-2">
                                <li class="flex items-start gap-3">
                                    <span class="text-gray-900 text-[8px] mt-1.5">●</span> 
                                    <span class="leading-tight">{{ $t('service_view.alur.step1_item1') }}</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <span class="text-gray-900 text-[8px] mt-1.5">●</span> 
                                    <span class="leading-tight">{{ $t('service_view.alur.step1_item2') }}</span>
                                </li>
                            </ul>
                        </div>
                        
                        <!-- Arrow 1 to 2 -->
                        <div class="hidden md:flex flex-col items-center justify-center absolute left-[29%] top-7 w-[10%] z-0">
                            <span class="text-[11px] font-extrabold text-gray-900 mb-2 whitespace-nowrap">{{ $t('service_view.alur.step1_time') }}</span>
                            <div class="w-full h-1 bg-red-600 relative flex items-center">
                                <div class="absolute -right-1 w-0 h-0 border-t-[5px] border-t-transparent border-l-[8px] border-l-red-600 border-b-[5px] border-b-transparent"></div>
                            </div>
                        </div>

                        <!-- Step 2: Design -->
                        <div class="w-full md:w-[28%] flex flex-col z-10 relative mt-12 md:mt-0">
                            <div class="bg-[#ccff00] text-gray-900 font-black px-6 py-4 rounded-full shadow-md text-center text-lg md:text-xl border-2 border-transparent hover:scale-105 transition-transform cursor-default">
                                {{ $t('service_view.alur.step2_title') }}
                            </div>
                            <ul class="mt-6 text-sm font-bold text-gray-900 space-y-3 px-2">
                                <li class="flex items-start gap-3">
                                    <span class="text-gray-900 text-[8px] mt-1.5">●</span> 
                                    <span class="leading-tight">{{ $t('service_view.alur.step2_item1') }}</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <span class="text-gray-900 text-[8px] mt-1.5">●</span> 
                                    <span class="leading-tight">{{ $t('service_view.alur.step2_item2') }}</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <span class="text-gray-900 text-[8px] mt-1.5">●</span> 
                                    <span class="leading-tight">{{ $t('service_view.alur.step2_item3') }}</span>
                                </li>
                            </ul>
                        </div>

                        <!-- Arrow 2 to 3 -->
                        <div class="hidden md:flex flex-col items-center justify-center absolute left-[63%] top-7 w-[10%] z-0">
                            <span class="text-[11px] font-extrabold text-gray-900 mb-2 whitespace-nowrap">{{ $t('service_view.alur.step2_time') }}</span>
                            <div class="w-full h-1 bg-red-600 relative flex items-center">
                                <div class="absolute -right-1 w-0 h-0 border-t-[5px] border-t-transparent border-l-[8px] border-l-red-600 border-b-[5px] border-b-transparent"></div>
                            </div>
                        </div>

                        <!-- Step 3: Pra Produksi -->
                        <div class="w-full md:w-[28%] flex flex-col z-10 relative mt-12 md:mt-0">
                            <div class="bg-[#ccff00] text-gray-900 font-black px-6 py-4 rounded-full shadow-md text-center text-lg md:text-xl border-2 border-transparent hover:scale-105 transition-transform cursor-default">
                                {{ $t('service_view.alur.step3_title') }}
                            </div>
                            <ul class="mt-6 text-sm font-bold text-gray-900 space-y-3 px-2">
                                <li class="flex items-start gap-3">
                                    <span class="text-gray-900 text-[8px] mt-1.5">●</span> 
                                    <span class="leading-tight">{{ $t('service_view.alur.step3_item1') }}<br/>{{ $t('service_view.alur.step3_time') }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Dashed connection line from right top to right bottom -->
                    <div class="hidden md:block absolute top-[60px] right-[14%] w-10 h-[180px] border-r-[3px] border-b-[3px] border-dashed border-red-600 rounded-br-[20px] z-0">
                        <div class="absolute bottom-[-4px] left-[-4px] w-0 h-0 border-t-[5px] border-t-transparent border-r-[8px] border-r-red-600 border-b-[5px] border-b-transparent"></div>
                    </div>

                    <!-- Bottom Row (Reverse Direction) -->
                    <div class="flex flex-col md:flex-row w-full justify-between items-start relative mt-16 md:mt-[80px]">
                        
                        <!-- Step 6: Serah Terima (Visual Left) -->
                        <div class="w-full md:w-[28%] flex flex-col z-10 relative mt-12 md:mt-0 order-3 md:order-1">
                            <div class="bg-[#ccff00] text-gray-900 font-black px-6 py-4 rounded-full shadow-md text-center text-lg md:text-xl border-2 border-transparent hover:scale-105 transition-transform cursor-default">
                                {{ $t('service_view.alur.step6_title') }}
                            </div>
                            <ul class="mt-6 text-sm font-bold text-gray-900 space-y-3 px-2">
                                <li class="flex items-start gap-3">
                                    <span class="text-gray-900 text-[8px] mt-1.5">●</span> 
                                    <span class="leading-tight">{{ $t('service_view.alur.step6_item1') }}</span>
                                </li>
                            </ul>
                        </div>

                        <!-- Arrow 5 to 6 -->
                        <div class="hidden md:flex flex-col items-center justify-center absolute left-[29%] top-7 w-[10%] z-0 order-2">
                            <span class="text-[11px] font-extrabold text-gray-900 mb-2 whitespace-nowrap">{{ $t('service_view.alur.step5_time') }}</span>
                            <div class="w-full h-1 bg-red-600 relative flex items-center">
                                <div class="absolute -left-1 w-0 h-0 border-t-[5px] border-t-transparent border-r-[8px] border-r-red-600 border-b-[5px] border-b-transparent"></div>
                            </div>
                        </div>

                        <!-- Step 5: Instalasi (Visual Middle) -->
                        <div class="w-full md:w-[28%] flex flex-col z-10 relative mt-12 md:mt-0 order-2 md:order-2">
                            <div class="bg-[#ccff00] text-gray-900 font-black px-6 py-4 rounded-full shadow-md text-center text-lg md:text-xl border-2 border-transparent hover:scale-105 transition-transform cursor-default">
                                {{ $t('service_view.alur.step5_title') }}
                            </div>
                        </div>

                        <!-- Arrow 4 to 5 -->
                        <div class="hidden md:flex flex-col items-center justify-center absolute left-[63%] top-7 w-[10%] z-0 order-4">
                            <span class="text-[11px] font-extrabold text-gray-900 mb-2 whitespace-nowrap">{{ $t('service_view.alur.step4_time') }}</span>
                            <div class="w-full h-1 bg-red-600 relative flex items-center">
                                <div class="absolute -left-1 w-0 h-0 border-t-[5px] border-t-transparent border-r-[8px] border-r-red-600 border-b-[5px] border-b-transparent"></div>
                            </div>
                        </div>

                        <!-- Step 4: Produksi (Visual Right) -->
                        <div class="w-full md:w-[28%] flex flex-col z-10 relative order-1 md:order-3">
                            <div class="bg-[#ccff00] text-gray-900 font-black px-6 py-4 rounded-full shadow-md text-center text-lg md:text-xl border-2 border-transparent hover:scale-105 transition-transform cursor-default">
                                {{ $t('service_view.alur.step4_title') }}
                            </div>
                            <ul class="mt-6 text-sm font-bold text-gray-900 space-y-3 px-2">
                                <li class="flex items-start gap-3">
                                    <span class="text-gray-900 text-[8px] mt-1.5">●</span> 
                                    <span class="leading-tight">{{ $t('service_view.alur.step4_item1') }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    
                </div>
            </div>
        </section>

        <Teleport to="body">
            <Transition name="fade">
                <div v-if="isModalOpen"
                    class="fixed inset-0 z-[100] flex flex-col items-center justify-center p-4 md:p-8 bg-gray-900/95 backdrop-blur-md"
                    @click.self="closeModal">

                    <button @click="closeModal"
                        class="mb-6 text-gray-400 hover:text-red-500 transition-colors flex items-center gap-2 font-bold uppercase tracking-[0.2em] text-xs">
                        {{ $t('service_view.modal.close') }} <span class="text-3xl leading-none">×</span>
                    </button>

                    <div class="relative max-w-5xl w-full flex flex-col items-center">
                        <div class="w-full flex justify-center items-center overflow-hidden">
                            <img :src="selectedImage?.path" :alt="selectedImage?.name"
                                class="max-w-full max-h-[75vh] object-contain rounded-2xl shadow-2xl border border-white/10" />
                        </div>
                        <p class="text-white text-center mt-6 font-bold tracking-[0.3em] uppercase text-xs">
                            {{ selectedImage?.name }}
                        </p>
                    </div>

                </div>
            </Transition>
        </Teleport>
    </main>
</template>

<style scoped>
/* Transisi Modal yang lebih smooth */
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.4s ease, transform 0.4s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
    transform: translateY(10px) scale(0.98);
}

body.modal-open {
    overflow: hidden;
}
</style>