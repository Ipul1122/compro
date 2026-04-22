<script setup>
import { ref, computed } from 'vue'
import { Swiper, SwiperSlide } from 'swiper/vue';
import { Autoplay, Pagination, EffectFade } from 'swiper/modules';
import { useI18n } from 'vue-i18n';
import 'swiper/css';
import 'swiper/css/pagination';
import 'swiper/css/effect-fade';

const { t } = useI18n();

const images = [
    '/img/slide-gallery-10.jpg',
    '/img/slide-gallery-14.jpg',
    '/img/slide-gallery-15.jpg',
    '/img/slide-gallery-16.jpg',
    '/img/slide-gallery-17.jpg',
    '/img/slide-gallery-18.jpg'
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
    <main class=""> 
        <section id="interior-design" class="py-24 relative border-t border-slate-200 bg-white overflow-hidden">
            <div class="absolute -top-24 -left-24 w-96 h-96 bg-brand/5 blur-[120px] rounded-full pointer-events-none">
            </div>

            <div class="max-w-7xl mx-auto px-4 mt-14">
                <div class="grid lg:grid-cols-2 gap-20 items-center">

                    <div class="relative z-10">
                        <h2 class="text-3xl md:text-5xl font-black text-slate-900 leading-[1.1] tracking-tight">
                            {{ $t('service_view.interior.title1') }} <br />
                            <span class="text-brand">{{ $t('service_view.interior.title_highlight') }}</span> {{ $t('service_view.interior.title2') }}
                        </h2>

                        <div class="mt-8 space-y-6">
                            <p class="text-lg md:text-xl text-slate-600 leading-relaxed font-light">
                                {{ $t('service_view.interior.desc1') }} <span class="text-slate-900 font-bold">{{ $t('service_view.interior.desc1_bold') }}</span>
                                {{ $t('service_view.interior.desc1_after') }}
                            </p>

                            <p class="text-slate-500 leading-relaxed">
                                {{ $t('service_view.interior.desc2') }}
                            </p>
                        </div>

                        <div class="mt-12 flex items-center gap-8">
                            <div>
                                <p class="text-4xl font-black text-slate-900">10+</p>
                                <p class="text-xs uppercase tracking-widest text-slate-500 font-bold mt-1">
                                    {{ $t('service_view.interior.years_exp') }}
                                </p>
                            </div>
                            <div class="w-px h-10 bg-slate-200"></div>
                            <div>
                                <p class="text-4xl font-black text-slate-900">100%</p>
                                <p class="text-xs uppercase tracking-widest text-slate-500 font-bold mt-1">
                                    {{ $t('service_view.interior.quality') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="relative group">
                        <div
                            class="absolute -top-6 -left-6 z-20 bg-brand px-6 py-4 rounded-2xl shadow-xl transform -rotate-3 group-hover:rotate-0 transition-transform duration-500">
                            <p class="text-white font-black text-2xl leading-none">{{ $t('service_view.interior.badge_title') }}</p>
                            <p class="text-white/80 text-sm font-bold uppercase tracking-widest mt-1">{{ $t('service_view.interior.badge_subtitle') }}</p>
                        </div>

                        <div
                            class="aspect-[4/5] w-full bg-slate-100 rounded-[2rem] border border-slate-200 overflow-hidden relative shadow-2xl group">

                            <div
                                class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-transparent to-transparent z-10 pointer-events-none">
                            </div>

                            <div class="absolute inset-0 flex items-center justify-center">
                                <span class="italic text-slate-400">{{ $t('service_view.interior.loading') }}</span>
                                <img src="/src/img/interior.jpg" alt="Interior design showcase"
                                    class="absolute inset-0 h-full w-full object-cover" onerror="this.style.display='none'">
                            </div>

                            <div class="absolute bottom-10 left-10 z-20">
                                <h3
                                    class="text-5xl font-black text-white tracking-tighter opacity-40 group-hover:opacity-100 transition-opacity duration-700">
                                    {{ $t('service_view.interior.watermark') }}
                               </h3>
                            </div>

                        </div>

                        <div
                            class="absolute -bottom-6 -right-6 w-full h-full border-2 border-brand/20 rounded-[2rem] -z-10 group-hover:translate-x-4 group-hover:translate-y-4 transition-transform duration-500">
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <section id="services" class="py-24 bg-slate-50 border-y border-slate-200 relative overflow-hidden">
            <div class="absolute top-1/2 -right-[10%] w-[30%] h-[60%] bg-brand/5 blur-[120px] rounded-full -z-10">
            </div>

            <div class="max-w-7xl mx-auto px-4">
                <div class="grid lg:grid-cols-2 gap-16 items-center">

                    <div class="order-1 lg:order-1">
                        <div class="relative group">
                            <div
                                class="aspect-video lg:aspect-square w-full bg-slate-200 rounded-2xl border border-slate-200 overflow-hidden flex items-center justify-center shadow-xl relative z-10">

                                <img src="/src/img/section-two.jpg" alt="Assessment Service"
                                    class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" />

                                <div
                                    class="absolute inset-0 bg-gradient-to-br from-slate-900/60 to-transparent opacity-50 z-10">
                                </div>

                                <p
                                    class="relative z-20 text-white font-bold italic opacity-0 group-hover:opacity-100 transition-opacity">
                                    Assessment Center
                                </p>
                            </div>

                            <div
                                class="absolute -bottom-4 -left-4 w-full h-full border-2 border-brand/20 rounded-2xl z-0 group-hover:translate-x-2 group-hover:translate-y-2 transition-transform">
                            </div>
                        </div>
                    </div>

                    <div id="psikotest" class="order-2 lg:order-2">
                        <div
                            class="inline-block px-3 py-1 rounded-full bg-brand/10 border border-brand/20 text-brand text-xs font-bold uppercase tracking-widest mb-6">
                            {{ $t('service_view.assessment.badge') }}
                        </div>

                        <h2 class="text-3xl md:text-5xl font-bold leading-tight text-slate-900">
                            {{ $t('service_view.assessment.title') }} <br class="hidden md:block" />
                            {{ $t('service_view.assessment.title_break') }} <span class="text-slate-500 text-2xl md:text-3xl font-medium block mt-2">
                                {{ $t('service_view.assessment.subtitle') }}</span>
                        </h2>

                        <p class="mt-6 text-lg text-slate-600 leading-relaxed">
                            {{ $t('service_view.assessment.desc') }}
                        </p>

                        <ul class="mt-8 space-y-4">
                            <li class="flex items-center gap-3 text-slate-700">
                                <div class="w-1.5 h-1.5 rounded-full bg-brand"></div>
                                <span>{{ $t('service_view.assessment.point1') }}</span>
                            </li>
                            <li class="flex items-center gap-3 text-slate-700">
                                <div class="w-1.5 h-1.5 rounded-full bg-brand"></div>
                                <span>{{ $t('service_view.assessment.point2') }}</span>
                            </li>
                        </ul>

                        <div class="mt-10">
                            <button class="group flex items-center gap-2 text-brand font-bold hover:gap-4 transition-all">
                                {{ $t('service_view.assessment.btn_cta') }}
                                <span>→</span>
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <section id="event-organizer" class="py-32 relative bg-slate-50 overflow-hidden">
            <div class="max-w-7xl mx-auto px-4">
                <div class="grid lg:grid-cols-2 gap-16 items-start">

                    <div class="relative mb-10 lg:mb-0">

                        <div class="lg:sticky lg:top-32">
                            <div class="relative group">

                                <div
                                    class="aspect-[4/3] rounded-[2.5rem] bg-white border border-slate-200 overflow-hidden shadow-xl relative">

                                    <div class="absolute inset-0 w-full h-full">
                                        <swiper :modules="[Autoplay, Pagination, EffectFade]" :slides-per-view="1"
                                            :loop="true" :effect="'fade'"
                                            :autoplay="{ delay: 3000, disableOnInteraction: false }"
                                            :pagination="{ clickable: true }" class="h-full w-full">
                                            <swiper-slide v-for="(img, index) in images" :key="index">
                                                <img :src="img" class="w-full h-full object-cover" :alt="'Event ' + index">
                                            </swiper-slide>

                                            <div class="swiper-pagination !bottom-8 !z-20"></div>
                                        </swiper>
                                    </div>

                                    <div
                                        class="absolute inset-x-0 bottom-0 h-1/3 bg-gradient-to-t from-slate-900/60 to-transparent z-10 pointer-events-none">
                                    </div>
                                </div>

                                <div class="absolute -top-4 -right-4 w-24 h-24 bg-brand/10 blur-3xl rounded-full">
                                </div>
                                <div
                                    class="absolute -bottom-8 -left-8 px-6 py-4 bg-white border border-slate-200 rounded-2xl shadow-xl z-20 hidden md:block">
                                    <p class="text-brand font-black text-xl">100+</p>
                                    <p class="text-[10px] uppercase tracking-widest text-slate-500 font-bold">
                                        {{ $t('service_view.event.events_managed') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="relative">
                        <div
                            class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-brand/10 border border-brand/20 text-brand text-xs font-bold uppercase tracking-[0.2em] mb-8">
                            <span class="relative flex h-2 w-2">
                                <span
                                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-brand opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-brand"></span>
                            </span>
                            {{ $t('service_view.event.badge') }}
                        </div>

                        <h2 class="text-4xl md:text-6xl font-black text-slate-900 leading-tight tracking-tighter">
                            {{ $t('service_view.event.title1') }} <br />
                            <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand to-orange-400">
                                {{ $t('service_view.event.title_highlight') }}</span>
                        </h2>

                        <p class="mt-6 text-slate-600 text-lg leading-relaxed">
                            {{ $t('service_view.event.desc') }}
                        </p>

                        <div class="mt-12 space-y-12">
                            <div>
                                <h3 class="text-xl font-bold text-slate-900 flex items-center gap-3">
                                    <span class="text-brand text-sm font-black">01</span>
                                    {{ $t('service_view.event.subtitle') }}
                                </h3>
                                <ul class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-2 text-sm text-slate-600">
                                    <li class="flex items-center gap-2 hover:text-brand transition-colors">•
                                        {{ $t('service_view.event.list1') }}</li>
                                    <li class="flex items-center gap-2 hover:text-brand transition-colors">• 
                                        {{ $t('service_view.event.list2') }}</li>
                                    <li class="flex items-center gap-2 hover:text-brand transition-colors">•
                                        {{ $t('service_view.event.list3') }}</li>
                                    <li class="flex items-center gap-2 hover:text-brand transition-colors">•
                                        {{ $t('service_view.event.list4') }}</li>
                                </ul>
                            </div>

                            <div
                                class="p-8 rounded-3xl bg-gradient-to-br from-brand/10 to-white border border-brand/20 relative overflow-hidden shadow-sm">
                                <div class="absolute top-0 right-0 p-4 opacity-5">
                                    <svg class="w-20 h-20 text-brand" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                                    </svg>
                                </div>
                                <h4 class="text-brand font-black uppercase text-xs tracking-widest mb-3">
                                    {{ $t('service_view.event.quote_title') }}</h4>
                                <p class="text-slate-700 text-sm leading-relaxed italic relative z-10">
                                    {{ $t('service_view.event.quote1') }} <span
                                        class="text-slate-900 font-bold underline decoration-brand">
                                        {{ $t('service_view.event.quote_highlight') }}</span> 
                                    {{ $t('service_view.event.quote2') }}
                                </p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <section id="lms" class="py-32 relative border-t border-slate-200 overflow-hidden bg-white">

            <div
                class="absolute top-0 right-0 w-[500px] h-[500px] bg-brand/5 blur-[150px] rounded-full pointer-events-none -z-10">
            </div>

            <div class="max-w-7xl mx-auto px-4">
                <div class="grid lg:grid-cols-[1.4fr,1fr] gap-12 lg:gap-20 items-start">

                    <div class="relative z-10 flex flex-col justify-center">
                        <div
                            class="inline-block px-3 py-1 rounded-full bg-slate-100 border border-slate-200 text-slate-600 text-[10px] font-bold uppercase tracking-[0.3em] mb-8 w-fit">
                            {{ $t('service_view.lms.badge') }}
                        </div>

                        <h2 class="text-4xl md:text-6xl font-black text-slate-900 leading-[0.9] tracking-tighter mb-6">
                            {{ $t('service_view.lms.title1') }} <br />
                            <span class="text-brand">{{ $t('service_view.lms.title_highlight') }}</span>
                        </h2>

                        <p class="text-lg text-slate-600 leading-relaxed font-light max-w-2xl mb-8">
                            {{ $t('service_view.lms.desc') }}
                        </p>

                        <div class="flex flex-wrap gap-3 mb-12">
                            <span class="px-4 py-2 bg-slate-50 border border-slate-100 rounded-lg text-xs font-medium text-slate-700">
                                📚 {{ $t('service_view.lms.tag1') }}</span>
                            <span class="px-4 py-2 bg-slate-50 border border-slate-100 rounded-lg text-xs font-medium text-slate-700">
                                🤝 {{ $t('service_view.lms.tag2') }}</span>
                            <span class="px-4 py-2 bg-slate-50 border border-slate-100 rounded-lg text-xs font-medium text-slate-700">
                                📱 {{ $t('service_view.lms.tag3') }}</span>
                            <span class="px-4 py-2 bg-slate-50 border border-slate-100 rounded-lg text-xs font-medium text-slate-700">
                                👨‍🏫 {{ $t('service_view.lms.tag4') }}</span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">

                            <div>
                                <h4 class="text-brand font-bold uppercase tracking-widest text-xs mb-4">
                                    {{ $t('service_view.lms.features_title') }}
                                </h4>
                                <ul class="space-y-3">
                                    <li class="flex items-start gap-3 text-sm text-slate-600">
                                        <span class="text-brand">✔</span> {{ $t('service_view.lms.feature1') }}
                                    </li>
                                    <li class="flex items-start gap-3 text-sm text-slate-600">
                                        <span class="text-brand">✔</span> {{ $t('service_view.lms.feature2') }}
                                    </li>
                                    <li class="flex items-start gap-3 text-sm text-slate-600">
                                        <span class="text-brand">✔</span> {{ $t('service_view.lms.feature3') }}
                                    </li>
                                    <li class="flex items-start gap-3 text-sm text-slate-600">
                                        <span class="text-brand">✔</span> {{ $t('service_view.lms.feature4') }}
                                    </li>
                                </ul>
                            </div>

                            <div>
                                <h4 class="text-brand font-bold uppercase tracking-widest text-xs mb-4">
                                    {{ $t('service_view.lms.benefits_title') }}
                                </h4>
                                <ul class="space-y-3">
                                    <li class="flex items-start gap-3 text-sm text-slate-600">
                                        <span class="text-green-500 font-bold">↑</span> <b>{{ $t('service_view.lms.benefit1_bold') }}</b>
                                        {{ $t('service_view.lms.benefit1_desc') }}
                                    </li>
                                    <li class="flex items-start gap-3 text-sm text-slate-600">
                                        <span class="text-green-500 font-bold">↑</span> <b>{{ $t('service_view.lms.benefit2_bold') }}</b>
                                        {{ $t('service_view.lms.benefit2_desc') }}
                                    </li>
                                    <li class="flex items-start gap-3 text-sm text-slate-600">
                                        <span class="text-green-500 font-bold">↑</span> <b>{{ $t('service_view.lms.benefit3_bold') }}</b>
                                        {{ $t('service_view.lms.benefit3_desc') }}
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="mt-12 p-6 bg-brand/5 rounded-2xl border border-brand/10">
                            <p class="text-slate-900 font-semibold mb-1">{{ $t('service_view.lms.cta_title') }}</p>
                            <p class="text-sm text-slate-600 mb-4">{{ $t('service_view.lms.cta_desc') }}
                            </p>
                            <button
                                class="bg-brand text-white px-6 py-3 rounded-xl font-bold text-sm hover:scale-105 transition-transform">
                                {{ $t('service_view.lms.cta_btn') }}
                            </button>
                        </div>
                    </div>


                </div>
            </div>
        </section>

        <Teleport to="body">
            <Transition name="fade">
                <div v-if="isModalOpen"
                    class="fixed inset-0 z-[100] flex flex-col items-center justify-center p-4 md:p-8 bg-slate-900/95 backdrop-blur-md"
                    @click.self="closeModal">

                    <button @click="closeModal"
                        class="mb-4 text-white/70 hover:text-white transition-colors flex items-center gap-2 font-bold uppercase tracking-[0.2em] text-xs">
                        {{ $t('service_view.modal.close') }} <span class="text-3xl leading-none">×</span>
                    </button>

                    <div class="relative max-w-5xl w-full flex flex-col items-center">
                        <div class="w-full flex justify-center items-center overflow-hidden">
                            <img :src="selectedImage?.path" :alt="selectedImage?.name"
                                class="max-w-full max-h-[75vh] object-contain rounded-xl shadow-2xl border border-white/10" />
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
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

body.modal-open {
    overflow: hidden;
}

.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease, transform 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
    transform: translateY(20px);
}
</style>