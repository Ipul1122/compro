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