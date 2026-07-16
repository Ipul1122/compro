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

// Logika DED & RAB Portfolio
const projectTabs = ref({
    project1: 'ded',
    project2: 'ded'
});

const isModalOpen = ref(false);
const modalImgSrc = ref('');
const modalImgAlt = ref('');

const openModal = (src, alt) => {
    modalImgSrc.value = src;
    modalImgAlt.value = alt;
    isModalOpen.value = true;
    if (typeof document !== 'undefined') {
        document.body.style.overflow = 'hidden';
    }
};

const closeModal = () => {
    isModalOpen.value = false;
    modalImgSrc.value = '';
    modalImgAlt.value = '';
    if (typeof document !== 'undefined') {
        document.body.style.overflow = '';
    }
};

onMounted(() => {
    timer = setInterval(nextSlide, 5000);
});

onUnmounted(() => {
    if (timer) clearInterval(timer);
});

// Data Paket Desain Interior
const packages = computed(() => [
    {
        name: t('service_view.interior.packages.lite_name'),
        desc: t('service_view.interior.packages.lite_desc'),
        features: [
            t('service_view.interior.packages.lite_f1'),
            t('service_view.interior.packages.lite_f2'),
            t('service_view.interior.packages.lite_f3'),
            t('service_view.interior.packages.lite_f4')
        ],
        whatsappLink: computed(() => {
            const text = locale.value === 'id' 
                ? 'Halo PT Cakrawala Parama Internasional, saya tertarik dengan Layanan Desain Interior Paket Lite.'
                : 'Hello PT Cakrawala Parama Internasional, I am interested in the Lite Package Interior Design Service.';
            return `https://wa.me/6285284201428?text=${encodeURIComponent(text)}`;
        }).value
    },
    {
        name: t('service_view.interior.packages.pro_name'),
        desc: t('service_view.interior.packages.pro_desc'),
        features: [
            t('service_view.interior.packages.pro_f1'),
            t('service_view.interior.packages.pro_f2'),
            t('service_view.interior.packages.pro_f3'),
            t('service_view.interior.packages.pro_f4'),
            t('service_view.interior.packages.pro_f5'),
            t('service_view.interior.packages.pro_f6')
        ],
        popular: true,
        whatsappLink: computed(() => {
            const text = locale.value === 'id' 
                ? 'Halo PT Cakrawala Parama Internasional, saya tertarik dengan Layanan Desain Interior Paket Pro.'
                : 'Hello PT Cakrawala Parama Internasional, I am interested in the Pro Package Interior Design Service.';
            return `https://wa.me/6285284201428?text=${encodeURIComponent(text)}`;
        }).value
    },
    {
        name: t('service_view.interior.packages.deluxe_name'),
        desc: t('service_view.interior.packages.deluxe_desc'),
        features: [
            t('service_view.interior.packages.deluxe_f1'),
            t('service_view.interior.packages.deluxe_f2'),
            t('service_view.interior.packages.deluxe_f3'),
            t('service_view.interior.packages.deluxe_f4'),
            t('service_view.interior.packages.deluxe_f5'),
            t('service_view.interior.packages.deluxe_f6'),
            t('service_view.interior.packages.deluxe_f7')
        ],
        whatsappLink: computed(() => {
            const text = locale.value === 'id' 
                ? 'Halo PT Cakrawala Parama Internasional, saya tertarik dengan Layanan Desain Interior Paket Deluxe.'
                : 'Hello PT Cakrawala Parama Internasional, I am interested in the Deluxe Package Interior Design Service.';
            return `https://wa.me/6285284201428?text=${encodeURIComponent(text)}`;
        }).value
    }
]);

// Data FAQ Desain Interior
const faqs = computed(() => [
    {
        q: t('service_view.interior.faq.q1'),
        a: t('service_view.interior.faq.a1')
    },
    {
        q: t('service_view.interior.faq.q2'),
        a: t('service_view.interior.faq.a2')
    },
    {
        q: t('service_view.interior.faq.q3'),
        a: t('service_view.interior.faq.a3')
    },
    {
        q: t('service_view.interior.faq.q4'),
        a: t('service_view.interior.faq.a4')
    }
]);

// FAQ Accordion State
const activeFaqIndex = ref(null);

const toggleFaq = (index) => {
    if (activeFaqIndex.value === index) {
        activeFaqIndex.value = null;
    } else {
        activeFaqIndex.value = index;
    }
};
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

        <!-- DED & RAB SECTION -->
        <div class="py-24 bg-[#FAF9F6] border-b border-red-100/50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <p class="text-red-600 font-bold tracking-[0.3em] uppercase text-[10px] mb-2">
                        {{ $t('service_view.interior.project_docs.subtitle') }}
                    </p>
                    <h2 class="text-3xl md:text-4xl font-black text-gray-900 uppercase tracking-tighter">
                        {{ $t('service_view.interior.project_docs.title') }}
                    </h2>
                    <div class="w-16 h-1 bg-red-600 mx-auto mt-4 mb-6 rounded-full"></div>
                    <p class="font-quicksand text-gray-600 font-medium leading-relaxed text-base md:text-lg">
                        {{ $t('service_view.interior.project_docs.description') }}
                    </p>
                </div>

                <div class="grid lg:grid-cols-2 gap-12">
                    <!-- Project 1 Card -->
                    <div class="bg-white rounded-3xl border border-gray-100 shadow-xl overflow-hidden flex flex-col justify-between transform hover:-translate-y-1 transition-all duration-300">
                        <div class="p-8">
                            <h3 class="text-xl md:text-2xl font-black text-gray-900 uppercase tracking-tight mb-3">
                                {{ $t('service_view.interior.project_docs.project1_title') }}
                            </h3>
                            <p class="text-sm text-gray-600 font-medium leading-relaxed mb-6 font-quicksand min-h-[60px]">
                                {{ $t('service_view.interior.project_docs.project1_desc') }}
                            </p>

                            <!-- Tabs -->
                            <div class="flex bg-gray-100 p-1.5 rounded-xl mb-6">
                                <button @click="projectTabs.project1 = 'ded'"
                                        class="flex-1 py-2.5 text-xs md:text-sm font-bold rounded-lg transition-all"
                                        :class="projectTabs.project1 === 'ded' ? 'bg-white text-red-600 shadow-sm' : 'text-gray-500 hover:text-gray-800'">
                                    {{ $t('service_view.interior.project_docs.tab_ded') }}
                                </button>
                                <button @click="projectTabs.project1 = 'rab'"
                                        class="flex-1 py-2.5 text-xs md:text-sm font-bold rounded-lg transition-all"
                                        :class="projectTabs.project1 === 'rab' ? 'bg-white text-red-600 shadow-sm' : 'text-gray-500 hover:text-gray-800'">
                                    {{ $t('service_view.interior.project_docs.tab_rab') }}
                                </button>
                            </div>

                            <!-- Image Container -->
                            <div class="relative bg-gray-50 rounded-2xl overflow-hidden group aspect-[4/3] border border-gray-100 flex items-center justify-center cursor-zoom-in"
                                 @click="openModal(
                                     projectTabs.project1 === 'ded' 
                                         ? '/img/desain-interior/ded-ruang-kepala-biro-keuangan.webp' 
                                         : '/img/desain-interior/rab-ruang-kepala-biro-keuangan.jpeg',
                                     projectTabs.project1 === 'ded' 
                                         ? $t('service_view.interior.project_docs.tab_ded') 
                                         : $t('service_view.interior.project_docs.tab_rab')
                                 )"
                                 :title="$t('service_view.interior.project_docs.zoom_tooltip')">
                                <img :src="projectTabs.project1 === 'ded' 
                                         ? '/img/desain-interior/ded-ruang-kepala-biro-keuangan.webp' 
                                         : '/img/desain-interior/rab-ruang-kepala-biro-keuangan.jpeg'" 
                                     :alt="$t('service_view.interior.project_docs.project1_title')"
                                     class="max-h-full max-w-full object-contain p-2 transform group-hover:scale-[1.02] transition-all duration-500" />
                                
                                <!-- Hover Zoom Overlay -->
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center z-20">
                                    <div class="bg-white/90 backdrop-blur-sm p-3 rounded-full text-red-600 shadow-lg transform translate-y-4 group-hover:translate-y-0 transition-transform">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m3-3H7" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Project 2 Card -->
                    <div class="bg-white rounded-3xl border border-gray-100 shadow-xl overflow-hidden flex flex-col justify-between transform hover:-translate-y-1 transition-all duration-300">
                        <div class="p-8">
                            <h3 class="text-xl md:text-2xl font-black text-gray-900 uppercase tracking-tight mb-3">
                                {{ $t('service_view.interior.project_docs.project2_title') }}
                            </h3>
                            <p class="text-sm text-gray-600 font-medium leading-relaxed mb-6 font-quicksand min-h-[60px]">
                                {{ $t('service_view.interior.project_docs.project2_desc') }}
                            </p>

                            <!-- Tabs -->
                            <div class="flex bg-gray-100 p-1.5 rounded-xl mb-6">
                                <button @click="projectTabs.project2 = 'ded'"
                                        class="flex-1 py-2.5 text-xs md:text-sm font-bold rounded-lg transition-all"
                                        :class="projectTabs.project2 === 'ded' ? 'bg-white text-red-600 shadow-sm' : 'text-gray-500 hover:text-gray-800'">
                                    {{ $t('service_view.interior.project_docs.tab_ded') }}
                                </button>
                                <button @click="projectTabs.project2 = 'rab'"
                                        class="flex-1 py-2.5 text-xs md:text-sm font-bold rounded-lg transition-all"
                                        :class="projectTabs.project2 === 'rab' ? 'bg-white text-red-600 shadow-sm' : 'text-gray-500 hover:text-gray-800'">
                                    {{ $t('service_view.interior.project_docs.tab_rab') }}
                                </button>
                            </div>

                            <!-- Image Container -->
                            <div class="relative bg-gray-50 rounded-2xl overflow-hidden group aspect-[4/3] border border-gray-100 flex items-center justify-center cursor-zoom-in"
                                 @click="openModal(
                                     projectTabs.project2 === 'ded' 
                                         ? '/img/desain-interior/ded-tempat-wudhu.jpeg' 
                                         : '/img/desain-interior/rab-tempat-wudhu.jpeg',
                                     projectTabs.project2 === 'ded' 
                                         ? $t('service_view.interior.project_docs.tab_ded') 
                                         : $t('service_view.interior.project_docs.tab_rab')
                                 )"
                                 :title="$t('service_view.interior.project_docs.zoom_tooltip')">
                                <img :src="projectTabs.project2 === 'ded' 
                                         ? '/img/desain-interior/ded-tempat-wudhu.jpeg' 
                                         : '/img/desain-interior/rab-tempat-wudhu.jpeg'" 
                                     :alt="$t('service_view.interior.project_docs.project2_title')"
                                     class="max-h-full max-w-full object-contain p-2 transform group-hover:scale-[1.02] transition-all duration-500" />
                                
                                <!-- Hover Zoom Overlay -->
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center z-20">
                                    <div class="bg-white/90 backdrop-blur-sm p-3 rounded-full text-red-600 shadow-lg transform translate-y-4 group-hover:translate-y-0 transition-transform">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m3-3H7" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Zoom Image Modal -->
        <transition name="fade">
            <div v-if="isModalOpen" 
                 class="fixed inset-0 z-50 flex flex-col items-center justify-center bg-black/90 p-4 md:p-8 overflow-hidden backdrop-blur-md cursor-zoom-out"
                 @click="closeModal">
                <button @click="closeModal" 
                        class="absolute top-6 right-6 text-white hover:text-red-500 bg-white/10 hover:bg-white/20 p-3 rounded-full transition-all duration-300 hover:scale-110 focus:outline-none z-50"
                        aria-label="Close modal">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                <div class="relative max-w-5xl max-h-[80vh] flex flex-col items-center justify-center" @click.stop>
                    <img :src="modalImgSrc" :alt="modalImgAlt" class="max-w-full max-h-[75vh] object-contain rounded-lg shadow-2xl border border-white/10" />
                    <p class="text-white/80 font-bold mt-4 tracking-wider uppercase text-sm md:text-base font-montserrat">
                        {{ modalImgAlt }}
                    </p>
                </div>
            </div>
        </transition>

        <!-- PACKAGES SECTION -->
        <section class="py-24 bg-white border-t border-red-100/30">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-20">
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-red-50 border border-red-100 text-[#ED0226] text-xs font-bold uppercase tracking-[0.2em] mb-4">
                        {{ locale === 'id' ? 'Paket Layanan' : 'Service Packages' }}
                    </div>
                    <h2 class="text-3xl lg:text-5xl font-black text-slate-950 mb-6">
                        {{ $t('service_view.interior.packages.title') }}
                    </h2>
                    <p class="text-slate-600 text-lg leading-relaxed font-quicksand font-medium">
                        {{ $t('service_view.interior.packages.desc') }}
                    </p>
                </div>

                <div class="grid lg:grid-cols-3 gap-8 items-stretch">
                    <div v-for="pkg in packages" :key="pkg.name" 
                         class="relative bg-white border rounded-3xl p-8 shadow-xl flex flex-col justify-between transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl"
                         :class="pkg.popular ? 'border-[#ED0226] ring-2 ring-[#ED0226]/10 lg:scale-105 z-10' : 'border-slate-200/80'">
                        
                        <div v-if="pkg.popular" class="absolute -top-4 left-1/2 -translate-x-1/2 bg-gradient-to-r from-[#ED0226] to-[#FF6A00] text-white text-xs font-black uppercase tracking-widest px-4 py-1.5 rounded-full shadow-md">
                            {{ locale === 'id' ? 'Terpopuler' : 'Most Popular' }}
                        </div>

                        <div>
                            <div class="mb-6">
                                <h3 class="text-2xl font-black text-slate-950 mb-3" :class="pkg.popular ? 'text-[#ED0226]' : ''">
                                    {{ pkg.name }}
                                </h3>
                                <p class="text-slate-500 text-sm leading-relaxed min-h-[48px] font-medium font-quicksand">
                                    {{ pkg.desc }}
                                </p>
                            </div>

                            <div class="w-full h-px bg-slate-100 my-6"></div>

                            <ul class="space-y-4">
                                <li v-for="feature in pkg.features" :key="feature" class="flex items-start gap-3">
                                    <span class="flex-shrink-0 text-[#ED0226] bg-red-50 rounded-full p-1 mt-0.5">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                    <span class="text-slate-700 text-sm font-semibold font-quicksand text-left">{{ feature }}</span>
                                </li>
                            </ul>
                        </div>

                        <div class="mt-8 pt-6 border-t border-slate-50">
                            <a :href="pkg.whatsappLink" 
                               target="_blank"
                               rel="noopener noreferrer"
                               class="w-full inline-flex items-center justify-center py-4 px-6 rounded-2xl font-bold text-center transition-all duration-300"
                               :class="pkg.popular 
                                   ? 'bg-[#ED0226] text-white hover:bg-[#c9001f] shadow-lg shadow-red-600/20' 
                                   : 'bg-slate-100 text-slate-800 hover:bg-slate-200'">
                                {{ locale === 'id' ? 'Konsultasi Sekarang' : 'Consult Now' }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- FAQ SECTION -->
        <section class="py-24 bg-[#FAF9F6] border-y border-red-100/30">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-red-50 border border-red-100 text-[#ED0226] text-xs font-bold uppercase tracking-[0.2em] mb-4">
                        FAQ
                    </div>
                    <h2 class="text-3xl lg:text-5xl font-black text-slate-950 mb-6">
                        {{ $t('service_view.interior.faq.title') }}
                    </h2>
                    <p class="text-slate-600 text-lg font-quicksand font-medium">
                        {{ $t('service_view.interior.faq.desc') }}
                    </p>
                </div>

                <div class="space-y-4">
                    <div v-for="(faq, index) in faqs" :key="index" 
                         class="bg-white border border-slate-150 rounded-2xl p-6 md:p-8 shadow-sm hover:shadow-md transition-all duration-300">
                        <button @click="toggleFaq(index)" 
                                class="w-full flex items-center justify-between gap-6 text-left group">
                            <span class="text-slate-900 font-extrabold text-base md:text-lg transition-colors group-hover:text-[#ED0226]">
                                {{ faq.q }}
                            </span>
                            <span class="transform transition-transform duration-300" 
                                  :class="activeFaqIndex === index ? 'rotate-180 text-[#ED0226]' : 'rotate-0 text-slate-400'">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                                </svg>
                            </span>
                        </button>
                        
                        <transition
                            enter-active-class="transition-all duration-300 ease-out"
                            leave-active-class="transition-all duration-200 ease-in"
                            enter-from-class="max-h-0 opacity-0 overflow-hidden"
                            enter-to-class="max-h-[32rem] opacity-100 overflow-hidden"
                            leave-from-class="max-h-[32rem] opacity-100 overflow-hidden"
                            leave-to-class="max-h-0 opacity-0 overflow-hidden"
                        >
                            <div v-show="activeFaqIndex === index" 
                                 class="mt-4 text-slate-600 text-sm md:text-base leading-relaxed border-t border-slate-100 pt-4 font-quicksand font-medium text-left">
                                {{ faq.a }}
                            </div>
                        </transition>
                    </div>
                </div>
            </div>
        </section>

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