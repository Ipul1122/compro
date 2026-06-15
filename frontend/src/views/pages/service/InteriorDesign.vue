<script setup>
import { ref, onMounted, onUnmounted, computed } from 'vue';
import { useI18n } from 'vue-i18n';

const { t, locale } = useI18n();

// Data Gambar Carousel
const interiorImages = [
    {
        src: '/img/interior.webp',
        title: 'Premium Office Room',
        vibe: 'Warm, Elegant'
    },
    {
        src: '/img/servicesSection/interio-design-1.jpeg',
        title: 'Executive Lounge',
        vibe: 'Modern, Sophisticated'
    },
    {
        src: '/img/servicesSection/interio-design-2.jpeg',
        title: 'Minimalist Workspace',
        vibe: 'Clean, Productive'
    },
    {
        src: '/img/servicesSection/interio-design-3.jpeg',
        title: 'Co-Working Space',
        vibe: 'Creative, Dynamic'
    },
    {
        src: '/img/servicesSection/interio-design-4.jpeg',
        title: 'Private Meeting Suite',
        vibe: 'Professional, Calm'
    }
];

// Data Misi
const missionPoints = computed(() => [
    t('service_view.interior.mission1'),
    t('service_view.interior.mission2'),
    t('service_view.interior.mission3'),
    t('service_view.interior.mission4'),
    t('service_view.interior.mission5')
]);

// Data Why Choose Us
const whyChooseUsPoints = computed(() => [
    {
        title: t('service_view.interior.why_choose_us.items.expertise_title'),
        desc: t('service_view.interior.why_choose_us.items.expertise_desc')
    },
    {
        title: t('service_view.interior.why_choose_us.items.integrated_title'),
        desc: t('service_view.interior.why_choose_us.items.integrated_desc')
    },
    {
        title: t('service_view.interior.why_choose_us.items.sustainable_title'),
        desc: t('service_view.interior.why_choose_us.items.sustainable_desc')
    },
    {
        title: t('service_view.interior.why_choose_us.items.quality_title'),
        desc: t('service_view.interior.why_choose_us.items.quality_desc')
    },
    {
        title: t('service_view.interior.why_choose_us.items.registered_title'),
        desc: t('service_view.interior.why_choose_us.items.registered_desc')
    }
]);

// Data Client Logos (Berdasarkan aset yang tersedia)
const clientLogos = [
    { src: '/img/DPR_RI_LOGO.png', alt: 'DPR RI' },
    { src: '/img/logo/DPR-RI-LOGO.png', alt: 'DPR RI' },
    { src: '/img/logo/SETJEN-DPD-LOGO.png', alt: 'SETJEN DPD RI' },
    { src: '/img/Logo_Dishub.png', alt: 'DISHUB LOGO' },
    { src: '/img/bank-btpn-logo.png', alt: 'BANK BTPN LOGO' },
    { src: '/img/logo/BKKBN-LOGO.png', alt: 'BKKPN LOGO' },
    { src: '/img/logo/KEMENTERIAN-PERINDUSTRIAN-LOGO.png', alt: 'KEMENTERIAN-PERINDUSTRIAN LOGO' },
    { src: '/img/truelogs-logo.png', alt: 'truelogs logo' },
    { src: '/img/Pembangunan-sarana-perkasa-logo.webp', alt: 'Pembangunan-sarana-perkasa logo' },
];

// Logika Carousel
const activeIndex = ref(0);
let timer = null;

const nextSlide = () => {
    activeIndex.value = (activeIndex.value + 1) % interiorImages.length;
};

const prevSlide = () => {
    activeIndex.value = (activeIndex.value - 1 + interiorImages.length) % interiorImages.length;
};

const goToSlide = (index) => {
    activeIndex.value = index;
};

onMounted(() => {
    timer = setInterval(nextSlide, 5000);
});

onUnmounted(() => {
    if (timer) clearInterval(timer);
});
</script>

<template>
    <section id="interior-design" class="pt-20 pb-16 relative overflow-hidden font-montserrat bg-[#FAF9F6]">
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 mb-24">
            <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">
                
                <div class="order-2 lg:order-1">
                    <div class="flex items-center gap-3 mb-6">
                        <span class="h-px w-8 bg-red-600"></span>
                        <span class="text-red-600 font-bold tracking-[0.2em] uppercase text-xs">
                            Interior Design Service
                        </span>
                    </div>

                    <h2 class="text-3xl sm:text-4xl md:text-5xl font-black text-gray-900 leading-[1.2] tracking-tight uppercase mb-8">
                        {{ $t('service_view.interior.title1') }} 
                        <span class="text-red-600 block sm:inline">{{ $t('service_view.interior.title_highlight') }}</span> 
                        {{ $t('service_view.interior.title2') }}
                    </h2>

                    <div class="space-y-6 text-gray-600">
                        <p class="text-lg md:text-xl leading-relaxed font-medium">
                            {{ $t('service_view.interior.desc1') }} 
                            <span class="text-gray-900 font-extrabold">{{ $t('service_view.interior.desc1_bold') }}</span>
                            {{ $t('service_view.interior.desc1_after') }}
                        </p>
                    </div>

                    <div class="mt-10 grid grid-cols-2 gap-4 sm:flex sm:items-center sm:gap-8">
                        <div class="bg-white p-5 rounded-2xl border border-red-100 shadow-sm flex-1 sm:flex-none">
                            <p class="text-3xl md:text-4xl font-black text-gray-900">10<span class="text-red-600">+</span></p>
                            <p class="text-[10px] uppercase tracking-widest text-gray-400 font-bold mt-1">
                                {{ $t('service_view.interior.years_exp') }}
                            </p>
                        </div>
                        <div class="bg-white p-5 rounded-2xl border border-red-100 shadow-sm flex-1 sm:flex-none">
                            <p class="text-3xl md:text-4xl font-black text-gray-900">100<span class="text-red-600">%</span></p>
                            <p class="text-[10px] uppercase tracking-widest text-gray-400 font-bold mt-1">
                                {{ $t('service_view.interior.quality') }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="order-1 lg:order-2">
                    <div class="relative bg-white p-3 rounded-[2.5rem] shadow-2xl shadow-red-100 border border-gray-100 overflow-hidden">
                        <div class="relative aspect-[4/5] rounded-[2rem] overflow-hidden group">
                            <transition-group name="fade">
                                <div v-for="(item, index) in interiorImages" 
                                     v-show="activeIndex === index" 
                                     :key="item.src"
                                     class="absolute inset-0">
                                    <img :src="item.src" :alt="item.title" class="w-full h-full object-cover transform scale-105 group-hover:scale-100 transition-transform duration-1000" />
                                    <div class="absolute inset-0 bg-gradient-to-t from-gray-900/80 via-gray-900/20 to-transparent"></div>
                                    
                                    <div class="absolute top-6 right-6 z-30">
                                        <div class="bg-white/90 backdrop-blur-sm p-2 rounded-lg shadow-lg border border-white/50">
                                            <img src="/img/logo/SETJEN-DPD-LOGO.png" alt="Client Logo" class="h-8 md:h-10 w-auto object-contain" />
                                        </div>
                                    </div>

                                    <div class="absolute bottom-8 left-8 z-30">
                                        <div class="space-y-1">
                                            <h3 class="text-2xl md:text-3xl font-black text-white uppercase tracking-tighter leading-none">
                                                {{ item.title }}
                                            </h3>
                                            <p class="text-red-400 font-bold tracking-widest text-[11px] md:text-xs uppercase flex items-center gap-2">
                                                <span class="w-4 h-px bg-red-400"></span>
                                                Vibe : {{ item.vibe }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </transition-group>

                            <button @click="prevSlide" aria-label="Previous slide" class="absolute left-4 top-1/2 -translate-y-1/2 w-10 h-10 bg-black/20 backdrop-blur-md rounded-full flex items-center justify-center text-white opacity-0 group-hover:opacity-100 transition-all hover:bg-red-600 z-40">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                            </button>
                            <button @click="nextSlide" aria-label="Next slide" class="absolute right-4 top-1/2 -translate-y-1/2 w-10 h-10 bg-black/20 backdrop-blur-md rounded-full flex items-center justify-center text-white opacity-0 group-hover:opacity-100 transition-all hover:bg-red-600 z-40">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                            </button>
                        </div>
                        <div class="flex justify-center gap-2 mt-5 mb-2">
                            <button v-for="(_, index) in interiorImages" :key="'dot-' + index" @click="goToSlide(index)"
                                    :aria-label="'Go to slide ' + (index + 1)"
                                    class="h-1.5 transition-all duration-300 rounded-full"
                                    :class="activeIndex === index ? 'w-8 bg-red-600' : 'w-2 bg-gray-200 hover:bg-red-200'">
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white py-20 border-y border-red-100/50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid md:grid-cols-2 gap-12 items-start">
                    
                    <div class="space-y-6">
                        <div class="flex items-center gap-4">
                            <div class="bg-red-600 text-white p-3 rounded-xl shadow-lg shadow-red-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                            </div>
                            <h2 class="font-montserrat text-3xl font-black text-gray-900 uppercase tracking-tighter">{{ $t('service_view.interior.our_vision') }}</h2>
                        </div>
                        <p class="font-quicksand text-xl text-gray-700 leading-relaxed font-semibold italic border-l-4 border-red-500 pl-6 py-2">
                            {{ $t('service_view.interior.vision_desc') }}
                        </p>
                    </div>

                    <!-- MISSION -->
                    <div class="space-y-6">
                        <div class="flex items-center gap-4">
                            <div class="bg-gray-900 text-white p-3 rounded-xl shadow-lg shadow-gray-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                            </div>
                            <h2 class="text-3xl font-black text-gray-900 uppercase tracking-tighter">{{ $t('service_view.interior.our_mission') }}</h2>
                        </div>
                        <ul class="space-y-4">
                            <li v-for="(mission, index) in missionPoints" :key="index" class="flex items-start gap-4 group">
                                <span class="flex-shrink-0 w-6 h-6 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-xs font-bold group-hover:bg-red-600 group-hover:text-white transition-colors duration-300">
                                    {{ index + 1 }}
                                </span>
                                <p class="font-quicksand text-gray-600 font-medium leading-snug">{{ mission }}</p>
                            </li>
                        </ul>
                    </div>

                </div>
            </div>
        </div>

        <!-- WHY CHOOSE US -->
        <div class="py-24 relative overflow-hidden bg-[#EAE8E3]">
            <div class="absolute inset-0">
                <img src="/img/interior.webp" alt="Background" class="w-full h-full object-cover opacity-20 mix-blend-multiply" />
                <div class="absolute inset-0 bg-gradient-to-r from-[#F0EDDF]/95 via-[#F0EDDF]/85 to-[#F0EDDF]/95"></div>
            </div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="grid lg:grid-cols-12 gap-12 lg:gap-16 items-start">
                    
                    <div class="lg:col-span-5 relative">
                        <div class="sticky top-24">
                            <img src="/img/LOGO-CPI.png" alt="PT Cakrawala Parama Internasional" class="h-16 md:h-20 mb-8 object-contain" />
                            
                            <div class="bg-[#E7BA71] p-8 md:p-12 rounded-2xl shadow-xl inline-block w-full">
                                <h2 class="text-5xl md:text-6xl font-black text-white leading-tight tracking-tight drop-shadow-md uppercase">
                                    {{ $t('service_view.interior.why_choose_us.title1') }}<br/>
                                    {{ $t('service_view.interior.why_choose_us.title2') }}<br/>
                                    {{ $t('service_view.interior.why_choose_us.title3') }}
                                </h2>
                            </div>

                            <div class="mt-12">
                                <router-link :to="locale === 'id' ? '/id/galeri' : '/en/gallery'" class="inline-flex items-center gap-3 bg-red-600 text-white px-8 py-4 rounded-full font-bold hover:bg-red-700 transition-colors shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                                    {{ $t('service_view.interior.why_choose_us.gallery_button') }}
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </router-link>
                            </div>
                        </div>
                    </div>

                    <div class="lg:col-span-7 space-y-6">
                        <div v-for="(item, index) in whyChooseUsPoints" :key="index" class="bg-white/50 backdrop-blur-sm p-6 md:p-8 rounded-2xl hover:bg-white/70 transition-colors border border-white/40 shadow-sm hover:shadow-md">
                            <h3 class="text-xl md:text-2xl font-black text-gray-900 mb-2">{{ item.title }}</h3>
                            <p class="text-gray-700 leading-relaxed font-medium">{{ item.desc }}</p>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="py-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <p class="text-red-600 font-bold tracking-[0.3em] uppercase text-[10px] mb-2">Trusted Partnership</p>
                <h2 class="text-3xl font-black text-gray-900 uppercase tracking-tighter">Our Clients</h2>
                <div class="w-16 h-1 bg-red-600 mx-auto mt-4 rounded-full"></div>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-8 md:gap-12 items-center">
                <div v-for="(logo, index) in clientLogos" :key="index" 
                     class="group bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-xl hover:border-red-200 transition-all duration-500 flex items-center justify-center h-32">
                    <img :src="logo.src" :alt="logo.alt" 
                         class="max-h-full max-w-full object-contain grayscale opacity-60 group-hover:grayscale-0 group-hover:opacity-100 transition-all duration-500 transform group-hover:scale-110" />
                </div>
            </div>
        </div>

    </section>
</template>

<style scoped>
.fade-enter-active, .fade-leave-active { transition: all 0.8s ease-in-out; }
.fade-enter-from { opacity: 0; transform: scale(1.05); }
.fade-leave-to { opacity: 0; transform: scale(0.95); }
</style>