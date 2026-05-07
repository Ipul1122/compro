<script setup>
import { ref, computed } from 'vue'
import { Swiper, SwiperSlide } from 'swiper/vue';
import { Autoplay, Pagination, EffectFade } from 'swiper/modules';
import { useI18n } from 'vue-i18n';
import 'swiper/css';
import 'swiper/css/pagination';
import 'swiper/css/effect-fade';

const { t } = useI18n();


const assessmentImages = [
    '/img/servicesSection/assestmen-1.jpg',
    '/img/servicesSection/assestmen-2.jpeg',
    '/img/servicesSection/assestmen-3.jpeg',
    '/img/servicesSection/assestmen-4.jpeg' 
];

const images = [
    '/img/servicesSection/slide-gallery-10.jpg',
    '/img/servicesSection/slide-gallery-14.jpg',
    '/img/servicesSection/slide-gallery-15.jpg',
    '/img/servicesSection/slide-gallery-16.jpg',
    '/img/servicesSection/slide-gallery-17.jpg',
    '/img/servicesSection/slide-gallery-18.jpg'
];

// Dibungkus computed agar nama preview LMS reaktif ketika bahasa dirubah
const lmsPreviews = computed(() => [
    { name: t('service_view.lms.preview.login'), path: '/img/login-dua.png' },
    { name: t('service_view.lms.preview.upload'), path: '/img/upload-tugas.png' },
    { name: t('service_view.lms.preview.quiz'), path: '/img/kuis.png' },
    { name: t('service_view.lms.preview.results'), path: '/img/kuis-dua.png' }
]);

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


        <!-- SECTION 2 -->
       <section id="services" class="py-24 relative overflow-hidden bg-white/50 border-y border-gray-100">
            <div class="max-w-7xl mx-auto px-4">
                <div class="grid lg:grid-cols-2 gap-16 items-center">

                    <div class="order-1 lg:order-1">
                        <div class="relative group">
                            
                            <div class="aspect-video lg:aspect-square w-full flex bg-gray-100 rounded-[2rem] border border-gray-200 overflow-hidden relative shadow-lg group-hover:shadow-2xl transition-all duration-500">
                                
                                <div v-for="(img, index) in assessmentImages" :key="index"
                                    class="group/item relative h-full flex-1 hover:flex-[5] transition-all duration-700 ease-in-out cursor-pointer z-10 hover:z-[99] border-r border-white/20 last:border-r-0 overflow-hidden">
                                    
                                    <img :src="img" :alt="'Assessment ' + index" class="absolute inset-0 w-full h-full object-cover" />
                                    
                                    <div class="absolute inset-0 bg-gray-900/50 group-hover/item:bg-gradient-to-t group-hover/item:from-gray-900/80 group-hover/item:via-transparent group-hover/item:to-transparent transition-all duration-500"></div>

                                    <div class="absolute bottom-8 left-6 z-20 opacity-0 group-hover/item:opacity-100 transition-all duration-700 delay-100 translate-y-4 group-hover/item:translate-y-0 whitespace-nowrap">
                                        <p class="text-orange-500 font-bold tracking-[0.2em] uppercase text-[10px] mb-2">Process 0{{ index + 1 }}</p>
                                        <h3 class="text-2xl md:text-3xl font-black text-white tracking-tighter drop-shadow-lg">
                                            Assessment
                                        </h3>
                                    </div>
                                </div>
                                
                            </div>
                            
                            <div class="absolute -bottom-4 -right-4 w-full h-full border-2 border-orange-600/20 rounded-[2rem] -z-10 group-hover:translate-x-2 group-hover:translate-y-2 transition-transform duration-500"></div>
                            
                        </div>
                    </div>

                    <div id="psikotest" class="order-2 lg:order-2">
                        <span class="inline-block py-1.5 px-4 rounded-full bg-gray-900 text-white font-bold tracking-[0.2em] uppercase text-xs mb-6 shadow-sm">
                            {{ $t('service_view.assessment.badge') }}
                        </span>

                        <h2 class="text-4xl md:text-5xl font-black leading-[1.1] text-gray-900 uppercase tracking-tight">
                            {{ $t('service_view.assessment.title') }} <br class="hidden md:block" />
                            <span class="text-orange-600">{{ $t('service_view.assessment.title_break') }}</span> 
                            <span class="text-gray-400 text-2xl md:text-3xl font-medium block mt-2 normal-case tracking-normal">
                                {{ $t('service_view.assessment.subtitle') }}
                            </span>
                        </h2>

                        <p class="mt-6 text-lg text-gray-500 leading-relaxed font-medium">
                            {{ $t('service_view.assessment.desc') }}
                        </p>

                        <ul class="mt-8 space-y-4 bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                            <li class="flex items-center gap-4 text-gray-700 font-medium">
                                <div class="flex-shrink-0 w-8 h-8 rounded-full bg-orange-50 flex items-center justify-center text-orange-600 font-bold">1</div>
                                <span>{{ $t('service_view.assessment.point1') }}</span>
                            </li>
                            <li class="flex items-center gap-4 text-gray-700 font-medium">
                                <div class="flex-shrink-0 w-8 h-8 rounded-full bg-orange-50 flex items-center justify-center text-orange-600 font-bold">2</div>
                                <span>{{ $t('service_view.assessment.point2') }}</span>
                            </li>
                        </ul>

                        <div class="mt-10">
                            <button class="group flex items-center gap-3 text-white bg-gray-900 px-8 py-4 rounded-full font-bold text-sm hover:bg-orange-600 transition-all duration-300 shadow-md hover:shadow-xl hover:-translate-y-1">
                                {{ $t('service_view.assessment.btn_cta') }}
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </section>

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
                                
                                <div class="aspect-[4/3] w-full flex bg-gray-100 rounded-[2rem] border border-gray-100 overflow-hidden shadow-xl relative z-10">
                                    
                                    <div v-for="(img, index) in images.slice(0, 4)" :key="index"
                                        class="group/item relative h-full flex-1 hover:flex-[5] transition-all duration-700 ease-in-out cursor-pointer z-10 hover:z-[99] border-r border-white/20 last:border-r-0 overflow-hidden">
                                        
                                        <img :src="img" class="absolute inset-0 w-full h-full object-cover" :alt="'Event ' + index" />
                                        
                                        <div class="absolute inset-0 bg-gray-900/50 group-hover/item:bg-gradient-to-t group-hover/item:from-gray-900/80 group-hover/item:via-transparent group-hover/item:to-transparent transition-all duration-500"></div>
                                        
                                        <div class="absolute bottom-8 left-6 z-20 opacity-0 group-hover/item:opacity-100 transition-all duration-700 delay-100 translate-y-4 group-hover/item:translate-y-0 whitespace-nowrap">
                                            <p class="text-orange-500 font-bold tracking-[0.2em] uppercase text-[10px] mb-1">Moment 0{{ index + 1 }}</p>
                                            <h3 class="text-2xl lg:text-3xl font-black text-white tracking-tighter drop-shadow-lg font-montserrat">
                                                Event Highlight
                                            </h3>
                                        </div>
                                    </div>
                                    
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

        <section id="lms" class="py-32 relative border-t border-gray-100 overflow-hidden bg-white/80">
            <div class="max-w-7xl mx-auto px-4">
                <div class="grid lg:grid-cols-[1.2fr,1fr] gap-12 lg:gap-20 items-center">

                    <div class="relative z-10">
                        <div class="inline-block px-4 py-1.5 rounded-full bg-white border border-gray-200 text-gray-900 text-[10px] font-bold uppercase tracking-[0.3em] mb-8 shadow-sm">
                            {{ $t('service_view.lms.badge') }}
                        </div>

                        <h2 class="text-4xl md:text-5xl font-black text-gray-900 leading-[1.1] tracking-tight uppercase mb-6">
                            {{ $t('service_view.lms.title1') }} <br />
                            <span class="text-orange-600">{{ $t('service_view.lms.title_highlight') }}</span>
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
                                    <li class="flex items-start gap-3 text-sm text-gray-600 font-medium"><span class="text-orange-600">✔</span> {{ $t('service_view.lms.feature1') }}</li>
                                    <li class="flex items-start gap-3 text-sm text-gray-600 font-medium"><span class="text-orange-600">✔</span> {{ $t('service_view.lms.feature2') }}</li>
                                    <li class="flex items-start gap-3 text-sm text-gray-600 font-medium"><span class="text-orange-600">✔</span> {{ $t('service_view.lms.feature3') }}</li>
                                    <li class="flex items-start gap-3 text-sm text-gray-600 font-medium"><span class="text-orange-600">✔</span> {{ $t('service_view.lms.feature4') }}</li>
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
                            <button class="bg-gray-900 text-white px-8 py-4 rounded-full font-bold text-sm hover:bg-orange-600 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
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
                        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full h-full bg-orange-100 rounded-full blur-[100px] -z-10"></div>
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
                        class="mb-6 text-gray-400 hover:text-orange-500 transition-colors flex items-center gap-2 font-bold uppercase tracking-[0.2em] text-xs">
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