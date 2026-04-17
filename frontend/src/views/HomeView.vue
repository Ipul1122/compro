<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
// import Navbar from './components/Navbar.vue'
import { Swiper, SwiperSlide } from 'swiper/vue';
import { Autoplay, Pagination, EffectFade } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/pagination';
import 'swiper/css/effect-fade';
import HomeSection from './section/HomeSection.vue'
import AboutSection from './section/AboutSection.vue'

const dummyProjects = [
    {
        // id: 'LMS-2026',
        title: 'Guidance in Developing SKB Test Questions for the Functional Position of Legislative Affairs',
        category: 'TESTING',
    },
    {
        // id: 'EVT-2025',
        title: 'Preparation of the Human Capital Development Plan (HCDP)',
        category: 'Organizational Development',
    },
    {
        // id: 'INT-2025',
        title: 'Development of the Functional Position Information System for the Indonesian House of Representatives (DPR RI)',
        category: 'Organizational Development',
    },
    {
        // id: 'INT-2025',
        title: 'Creation of an e-book for the Socialization of the BKKBN Stunting Program',
        category: 'Organizational Development',
    },
    {
        // id: 'INT-2025',
        title: 'Development of Human Resource Management Systems (HRIS) at STMT, STP, STBM, STIA, STIK Trisakti',
        category: 'Organizational Development',
    },
    {
        // id: 'INT-2025',
        title: 'Production of Training Videos for BKKBN',
        category: 'Organizational Development',
    },
    {
        // id: 'INT-2025',
        title: 'Development of Workload Analysis and Key Performance Indicators (KPI) for Trisakti Higher Education Institutions ',
        category: 'Organizational Development',
    },
    {
        // id: 'CONS-2024',
        title: 'Administration of the Basic Competency Selection Test at the Ministry of Transportation of the Republic of Indonesia, 2021',
        category: 'Event Organizer',
    },
    {
        // id: 'CONS-2024',
        title: 'Implementation of National Museum Day Activities, 2023',
        category: 'Event Organizer',
    },
    {
        // id: 'CONS-2024',
        title: 'Coordination Meeting of the Expert Agency for Performance Evaluation to Enhance Synergy and Strengthen the Products and Services of the Expert Agency (BKD), 2024',
        category: 'Event Organizer',
    },
    {
        // id: 'CONS-2024',
        title: 'Press Release for the Criminal Investigation Agency (Bareskrim), National Police Headquarters, 2025',
        category: 'Event Organizer',
    },
    {
        // id: 'CONS-2024',
        title: '"From Conefo to the House of the People” – Public Relations of the Indonesian House of Representatives (DPR RI), 2025',
        category: 'Event Organizer',
    },
    {
        // id: 'CONS-2024',
        title: 'Assessment of All Employees of the Regional Representative Council (DPD RI)',
        category: 'Employee Potential',
    },
    {
        // id: 'CONS-2024',
        title: 'Assessment of Employees at the Ministry of Public Works (PU RI) ',
        category: 'Employee Potential',
    },
    {
        // id: 'CONS-2024',
        title: 'Assessment for the Echelon 1 Leadership Position Selection at the Indonesian House of Representatives (DPR RI)',
        category: 'Employee Potential',
    },
    {
        // id: 'CONS-2024',
        title: 'Representatives (DPR RI) Potential Assessment of Civil Servant Candidates (CPNS) at DPR RI',
        category: 'Employee Potential',
    },
    {
        // id: 'CONS-2024',
        title: 'Recruitment for Participants of the 2023 Youth Parliament Seminar',
        category: 'Employee Potential',
    },
    {
        // id: 'CONS-2024',
        title: 'Psychometric Testing of Prospective Expert Staff for DPR RI Members for the 2024–2029 term (2025)',
        category: 'Employee Potential',
    },
    {
        // id: 'CONS-2024',
        title: 'Self-Transformation Workshop to Enhance Work Motivation & Stress Management, 2023',
        category: 'Workshop and Training',
    },
    {
        // id: 'CONS-2024',
        title: 'National Seminar on Strengthening the Implementation of the National Education System through Evaluation and Regulatory Improvement in the Education Sector, Puspanlak DPR RI, 2024',
        category: 'Workshop and Training',
    },
    {
        // id: 'CONS-2024',
        title: 'Workshop on Enhancing Employee Competence and Performance Capacity, Theme: “Decision Making and Adaptive Leadership”',
        category: 'Workshop and Training',
    },
    {
        // id: 'CONS-2024',
        title: 'Teambuilding and Outbound Training to Strengthen Team Collaboration',
        category: 'Workshop and Training',
    },
    {
        // id: 'CONS-2024',
        title: 'Workshop “Resilience & Global Mindset, ” SBM ITB, 2025',
        category: 'Workshop and Training',
    },
    {
        // id: 'CONS-2024',
        title: 'One-on-One Coaching for employees to help plan their career paths and align individual goals with organizational objectives in government institutions ',
        category: 'Coaching and Counseling',
    },
    {
        // id: 'CONS-2024',
        title: 'Assisting Prospective ITB Graduates in Planning Their Careers',
        category: 'Coaching and Counseling',
    },
    {
        // id: 'CONS-2024',
        title: 'Supporting Entrepreneurs During the COVID Era to Build Greater Endurance and Sustain Their Businesses Under Pandemic Conditions',
        category: 'Coaching and Counseling',
    },
    {
        // id: 'CONS-2024',
        title: 'ISO 37001:2016 – AntiBribery Management System ',
        category: 'Coaching and Counseling',
    },
    {
        // id: 'CONS-2024',
        title: 'Organizational Communication Management Training and Certification for Employees with BNSP Certification',
        category: 'Coaching and Counseling',
    },
    {
        // id: 'CONS-2024',
        title: 'ISO 9001:2015 – Quality Management System for the Health Service Unit of DPR RI, 2025',
        category: 'Coaching and Counseling',
    }
];

const showAllProjects = ref(false)
const itemsToShow = 5

const displayedProjects = computed(() => {
    if (showAllProjects.value) {
        return dummyProjects
    }
    return dummyProjects.slice(0, itemsToShow)
})


const images = [
    '/img/slide-gallery-10.jpg',
    '/img/slide-gallery-14.jpg',
    '/img/slide-gallery-15.jpg',
    '/img/slide-gallery-16.jpg',
    '/img/slide-gallery-17.jpg',
    '/img/slide-gallery-18.jpg'
];

const lmsPreviews = [
    { name: 'Login Interface', path: '/img/login-dua.png' },
    { name: 'Assignment Upload', path: '/img/upload-tugas.png' },
    { name: 'Quiz System', path: '/img/kuis.png' },
    { name: 'Results View', path: '/img/kuis-dua.png' }
];

const partnerLogos = [
    '/img/BKKBN-LOGO.png',
    '/img/BMKG-LOGO.png',
    '/img/BNI-LOGO.png',
    '/img/DPR-LOGO.png',
    '/img/DPR-RI-LOGO.png',
    '/img/DYNO-LOGO.png',
    '/img/ITL-TRISAKTI-LOGO.png',
    '/img/KEMENTERIAN-PERINDUSTRIAN-LOGO.png',
    '/img/LOGO-KUNING.png',
    '/img/PARIWISATA-LOGO.png',
    '/img/RISTEK-LOGO.png',
    '/img/SETJEN-DPD-LOGO.png',
    '/img/STAN-LOGO.png',
    '/img/STIK-LOGO.png',
    '/img/TRISAKTI-LOGO.png',
    '/img/ULAT-UNGU-LOGO.png'
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

const showBackToTop = ref(false)

const checkScroll = () => {
    showBackToTop.value = window.scrollY > 400
}

const scrollToTop = () => {
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    })
}

onMounted(() => {
    window.addEventListener('scroll', checkScroll)
})

onUnmounted(() => {
    window.removeEventListener('scroll', checkScroll)
})

</script>

<template>
    <div class="min-h-screen bg-white text-slate-900 font-sans selection:bg-brand/30 relative overflow-x-hidden">

        <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none -z-10">
            <div class="absolute -top-[10%] -left-[10%] w-[50%] h-[50%] bg-brand/5 blur-[120px] rounded-full"></div>
            <div class="absolute -bottom-[10%] -right-[10%] w-[40%] h-[40%] bg-blue-500/5 blur-[100px] rounded-full">
            </div>
        </div>

        <Navbar />

        <main id="home" class="relative mt-3">

            <!-- HOME SECTION -->
            <div class="home-view">        
            <HomeSection />
            </div>

            <!-- ABOUT SECTION -->
             <div class="about-view">
                <AboutSection />
             </div>

            <section id="our-project" class="py-24 bg-white">
                <div class="max-w-7xl mx-auto px-6">

                    <div
                        class="flex flex-col md:flex-row justify-between items-end mb-16 gap-6 border-b border-slate-100 pb-12">
                        <div class="max-w-2xl">
                            <h2 class="text-xs font-bold text-brand uppercase tracking-[0.3em] mb-4">Selected Works</h2>
                            <h3 class="text-5xl font-black text-slate-900 tracking-tighter leading-none">
                                Our Projects <br /> <span class="text-slate-400">Cakrawala Parama Internasional</span>
                            </h3>
                        </div>
                    </div>

                    <div class="divide-y divide-slate-100">
                        <div v-for="(project, index) in displayedProjects" :key="index"
                            class="group py-12 flex flex-col md:flex-row gap-8 items-start hover:bg-slate-50 transition-colors duration-300 px-4 -mx-4 rounded-3xl">

                            <div class="w-full md:w-1/4">
                                <span class="text-sm font-mono text-slate-300 mb-2 block">
                                    {{ (index + 1).toString().padStart(2, '0') }}
                                </span>
                                <span
                                    class="px-3 py-1 bg-brand/5 text-brand text-[10px] font-bold uppercase tracking-widest rounded-full">
                                    {{ project.category }}
                                </span>
                            </div>

                            <div class="w-full md:w-2/4 space-y-4">
                                <h4
                                    class="text-2xl font-black text-slate-900 group-hover:text-brand transition-colors leading-tight">
                                    {{ project.title }}
                                </h4>
                                <p v-if="project.description" class="text-slate-500 text-sm leading-relaxed max-w-xl">
                                    {{ project.description }}
                                </p>
                            </div>

                            <!-- <div class="w-full md:w-1/4 flex flex-col justify-end items-start md:items-end h-full">
                                <button
                                    class="flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-slate-900 group-hover:gap-4 transition-all">
                                    Detail Project
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M14 5l7 7m0 0l-7 7m7-7H3">
                                        </path>
                                    </svg>
                                </button>
                            </div> -->

                        </div>
                    </div>

                    <div class="mt-16 flex justify-center">
                        <button @click="showAllProjects = !showAllProjects"
                            class="group relative flex items-center gap-3 px-10 py-4 bg-slate-900 text-white rounded-full font-bold overflow-hidden transition-all hover:pr-12 active:scale-95">
                            <span class="relative z-10 uppercase tracking-widest text-xs">
                                {{ showAllProjects ? 'Show Less' : 'Show All Projects' }}
                            </span>

                            <svg class="w-4 h-4 transition-transform duration-300 group-hover:translate-x-1 relative z-10"
                                :class="showAllProjects ? 'rotate-[-90deg]' : 'rotate-90'" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M14 5l7 7m0 0l-7 7m7-7H3">
                                </path>
                            </svg>

                            <div
                                class="absolute inset-0 bg-brand opacity-0 group-hover:opacity-100 transition-opacity z-0">
                            </div>
                        </button>
                    </div>

                </div>
            </section>

            <!-- <section id="services" class="py-24 bg-slate-50 border-y border-slate-200 relative overflow-hidden">
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
                                Specialized Service
                            </div>

                            <h2 class="text-3xl md:text-5xl font-bold leading-tight text-slate-900">
                                Psikotest and Assessment <br class="hidden md:block" />
                                Center Service <span
                                    class="text-slate-500 text-2xl md:text-3xl font-medium block mt-2">Online &
                                    Offline</span>
                            </h2>

                            <p class="mt-6 text-lg text-slate-600 leading-relaxed">
                                Measuring competencies and performance to assess the suitability for their current
                                position
                                and to identify potential for transfer or promotion to other roles.
                            </p>

                            <ul class="mt-8 space-y-4">
                                <li class="flex items-center gap-3 text-slate-700">
                                    <div class="w-1.5 h-1.5 rounded-full bg-brand"></div>
                                    <span>Comprehensive Performance Analytics</span>
                                </li>
                                <li class="flex items-center gap-3 text-slate-700">
                                    <div class="w-1.5 h-1.5 rounded-full bg-brand"></div>
                                    <span>Tailored Promotion Identification</span>
                                </li>
                            </ul>

                            <div class="mt-10">
                                <button
                                    class="group flex items-center gap-2 text-brand font-bold hover:gap-4 transition-all">
                                    Learn More About Assessments
                                    <span>→</span>
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
            </section> -->

            <section id="our-services" class="py-32 relative overflow-hidden bg-white">
                <div class="max-w-7xl mx-auto px-4">

                    <div class="text-center mb-20 relative z-10">
                        <h2 class="text-brand font-bold tracking-[0.4em] uppercase text-sm mb-4 italic">Capabilities
                        </h2>
                        <h3 class="text-4xl md:text-6xl font-black text-slate-900 mb-6 tracking-tight uppercase">
                            Our Services
                        </h3>
                        <p class="text-slate-600 text-lg md:text-xl max-w-2xl mx-auto leading-relaxed">
                            We are the professionals providing people to achieve more through integrated digital
                            ecosystems.
                        </p>
                    </div>

                    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6 relative z-10">

                        <div
                            class="group p-8 rounded-3xl bg-white border border-slate-200 hover:border-brand/50 hover:scale-105 hover:shadow-2xl transition-all duration-500">
                            <div
                                class="w-12 h-12 mb-6 rounded-2xl bg-brand/10 flex items-center justify-center text-brand font-bold text-xl group-hover:bg-brand group-hover:text-white transition-all duration-500">
                                1
                            </div>
                            <h4 class="text-xl font-bold text-slate-900 mb-4">E-Recruitment</h4>
                            <p class="text-slate-600 text-sm leading-relaxed">
                                Finding the best people using our effective one-stop <span
                                    class="text-slate-900 font-semibold">Online
                                    Recruitment Process</span>.
                            </p>
                        </div>

                        <div
                            class="group p-8 rounded-3xl bg-white border border-slate-200 hover:border-brand/50 hover:scale-105 hover:shadow-2xl transition-all duration-500">
                            <div
                                class="w-12 h-12 mb-6 rounded-2xl bg-brand/10 flex items-center justify-center text-brand font-bold text-xl group-hover:bg-brand group-hover:text-white transition-all duration-500">
                                2
                            </div>
                            <h4 class="text-xl font-bold text-slate-900 mb-4">Assess</h4>
                            <ul class="space-y-4 text-xs text-slate-600">
                                <li class="border-b border-slate-100 pb-2"><strong
                                        class="text-slate-900 block text-sm mb-1">Psychological Test</strong> Instant
                                    online potential tests
                                    with real-time results.</li>
                                <li class="border-b border-slate-100 pb-2"><strong
                                        class="text-slate-900 block text-sm mb-1">Assessment
                                        Center</strong> Integrative methods for identifying leadership and competencies.
                                </li>
                                <li><strong class="text-slate-900 block text-sm mb-1">360° Assessment</strong>
                                    Performance feedback for
                                    clear career path mapping.</li>
                            </ul>
                        </div>

                        <div
                            class="group p-8 rounded-3xl bg-white border border-slate-200 hover:border-brand/50 hover:scale-105 hover:shadow-2xl transition-all duration-500">
                            <div
                                class="w-12 h-12 mb-6 rounded-2xl bg-brand/10 flex items-center justify-center text-brand font-bold text-xl group-hover:bg-brand group-hover:text-white transition-all duration-500">
                                3
                            </div>
                            <h4 class="text-xl font-bold text-slate-900 mb-4">Develop</h4>
                            <ul class="space-y-4 text-xs text-slate-600">
                                <li class="border-b border-slate-100 pb-2"><strong
                                        class="text-slate-900 block text-sm mb-1">Interactive
                                        Training</strong> Inspirating learning methods for knowledge transfer.</li>
                                <li class="border-b border-slate-100 pb-2"><strong
                                        class="text-slate-900 block text-sm mb-1">Coaching
                                        Clinic</strong> Self-development action plans through awareness.</li>
                                <li><strong class="text-slate-900 block text-sm mb-1">Business Simulation</strong>
                                    Sharpening skills
                                    through reality-case games.</li>
                            </ul>
                        </div>

                        <div
                            class="group p-8 rounded-3xl bg-white border border-slate-200 hover:border-brand/50 hover:scale-105 hover:shadow-2xl transition-all duration-500">
                            <div
                                class="w-12 h-12 mb-6 rounded-2xl bg-brand/10 flex items-center justify-center text-brand font-bold text-xl group-hover:bg-brand group-hover:text-white transition-all duration-500">
                                4
                            </div>
                            <h4 class="text-xl font-bold text-slate-900 mb-4">Manage & Supervise</h4>
                            <ul class="space-y-4 text-xs text-slate-600">
                                <li class="border-b border-slate-100 pb-2"><strong
                                        class="text-slate-900 block text-sm mb-1 italic">E-Coach & Career</strong>
                                    System for coaching
                                    process and career path review.</li>
                                <li class="border-b border-slate-100 pb-2"><strong
                                        class="text-slate-900 block text-sm mb-1 italic">E-Service</strong> Developing
                                    service culture for
                                    teams and organizations.</li>
                                <li><strong class="text-slate-900 block text-sm mb-1 italic">E-Survey</strong>
                                    Integration system for
                                    employee engagement drivers.</li>
                            </ul>
                        </div>

                    </div>

                    <div
                        class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 text-[15rem] font-black text-slate-900/[0.03] pointer-events-none select-none -z-10 tracking-tighter">
                        EXCELLENCE
                    </div>
                </div>
            </section>

            <section id="interior-design" class="py-24 relative border-t border-slate-200 bg-white overflow-hidden">
                <div
                    class="absolute -top-24 -left-24 w-96 h-96 bg-brand/5 blur-[120px] rounded-full pointer-events-none">
                </div>

                <div class="max-w-7xl mx-auto px-4">
                    <div class="grid lg:grid-cols-2 gap-20 items-center">

                        <div class="relative z-10">
                            <h2 class="text-3xl md:text-5xl font-black text-slate-900 leading-[1.1] tracking-tight">
                                Trusted in matters of <br />
                                <span class="text-brand">attractive and luxurious</span> design.
                            </h2>

                            <div class="mt-8 space-y-6">
                                <p class="text-lg md:text-xl text-slate-600 leading-relaxed font-light">
                                    With over <span class="text-slate-900 font-bold">10 years of experience</span>
                                    managing interior
                                    design
                                    projects at PT Cakrawala Parama Internasional.
                                </p>

                                <p class="text-slate-500 leading-relaxed">
                                    We have become one of the best choices for realizing your needs in space design,
                                    including personal spaces, cafes, hotels, and commercial establishments.
                                </p>
                            </div>

                            <div class="mt-12 flex items-center gap-8">
                                <div>
                                    <p class="text-4xl font-black text-slate-900">10+</p>
                                    <p class="text-xs uppercase tracking-widest text-slate-500 font-bold mt-1">Years Exp
                                    </p>
                                </div>
                                <div class="w-px h-10 bg-slate-200"></div>
                                <div>
                                    <p class="text-4xl font-black text-slate-900">100%</p>
                                    <p class="text-xs uppercase tracking-widest text-slate-500 font-bold mt-1">Quality
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="relative group">
                            <div
                                class="absolute -top-6 -left-6 z-20 bg-brand px-6 py-4 rounded-2xl shadow-xl transform -rotate-3 group-hover:rotate-0 transition-transform duration-500">
                                <p class="text-white font-black text-2xl leading-none">Professional</p>
                                <p class="text-white/80 text-sm font-bold uppercase tracking-widest mt-1">Design</p>
                            </div>

                            <div
                                class="aspect-[4/5] w-full bg-slate-100 rounded-[2rem] border border-slate-200 overflow-hidden relative shadow-2xl group">

                                <div
                                    class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-transparent to-transparent z-10 pointer-events-none">
                                </div>

                                <div class="absolute inset-0 flex items-center justify-center">
                                    <span class="italic text-slate-400">Loading...</span>
                                    <img src="/src/img/interior.jpg" alt="Interior design showcase"
                                        class="absolute inset-0 h-full w-full object-cover"
                                        onerror="this.style.display='none'">
                                </div>

                                <div class="absolute bottom-10 left-10 z-20">
                                    <h3
                                        class="text-5xl font-black text-white tracking-tighter opacity-40 group-hover:opacity-100 transition-opacity duration-700">
                                        INTERIOR
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
                                Specialized Service
                            </div>

                            <h2 class="text-3xl md:text-5xl font-bold leading-tight text-slate-900">
                                Psikotest and Assessment <br class="hidden md:block" />
                                Center Service <span
                                    class="text-slate-500 text-2xl md:text-3xl font-medium block mt-2">Online &
                                    Offline</span>
                            </h2>

                            <p class="mt-6 text-lg text-slate-600 leading-relaxed">
                                Measuring competencies and performance to assess the suitability for their current
                                position
                                and to identify potential for transfer or promotion to other roles.
                            </p>

                            <ul class="mt-8 space-y-4">
                                <li class="flex items-center gap-3 text-slate-700">
                                    <div class="w-1.5 h-1.5 rounded-full bg-brand"></div>
                                    <span>Comprehensive Performance Analytics</span>
                                </li>
                                <li class="flex items-center gap-3 text-slate-700">
                                    <div class="w-1.5 h-1.5 rounded-full bg-brand"></div>
                                    <span>Tailored Promotion Identification</span>
                                </li>
                            </ul>

                            <div class="mt-10">
                                <button
                                    class="group flex items-center gap-2 text-brand font-bold hover:gap-4 transition-all">
                                    Learn More About Assessments
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
                                                    <img :src="img" class="w-full h-full object-cover"
                                                        :alt="'Event ' + index">
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
                                        <p class="text-[10px] uppercase tracking-widest text-slate-500 font-bold">Events
                                            Managed</p>
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
                                Excellence in Every Event
                            </div>

                            <h2 class="text-4xl md:text-6xl font-black text-slate-900 leading-tight tracking-tighter">
                                Cakrawala <br />
                                <span
                                    class="text-transparent bg-clip-text bg-gradient-to-r from-brand to-orange-400">Event
                                    Organizer</span>
                            </h2>

                            <p class="mt-6 text-slate-600 text-lg leading-relaxed">
                                Your reliable partner in organizing various events, from intimate weddings to
                                large-scale international
                                seminars.
                            </p>

                            <div class="mt-12 space-y-12">
                                <!-- <div class="group">
                                    <h3 class="text-xl font-bold text-slate-900 flex items-center gap-3">
                                        <span class="text-brand text-sm font-black">01</span>
                                        Special Intimate Wedding
                                    </h3>
                                    <p class="text-slate-500 text-sm mt-2 mb-4">Flexible and customizable wedding
                                        packages including:</p>
                                    <div class="grid grid-cols-2 gap-3">
                                        <div v-for="item in ['Exclusive Decor', 'Custom Attire', 'Professional MC', 'Premium Catering']"
                                            :key="item"
                                            class="flex items-center gap-2 text-xs text-slate-700 bg-white p-3 rounded-xl border border-slate-200 shadow-sm">
                                            <div class="w-1 h-1 rounded-full bg-brand"></div> {{ item }}
                                        </div>
                                    </div>
                                </div> -->

                                <div>
                                    <h3 class="text-xl font-bold text-slate-900 flex items-center gap-3">
                                        <span class="text-brand text-sm font-black">01</span>
                                        National & International Events
                                    </h3>
                                    <ul
                                        class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-2 text-sm text-slate-600">
                                        <li class="flex items-center gap-2 hover:text-brand transition-colors">•
                                            Business Conferences</li>
                                        <li class="flex items-center gap-2 hover:text-brand transition-colors">• Product
                                            Launches</li>
                                        <li class="flex items-center gap-2 hover:text-brand transition-colors">•
                                            Corporate Gatherings</li>
                                        <li class="flex items-center gap-2 hover:text-brand transition-colors">•
                                            Government Events</li>
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
                                    <h4 class="text-brand font-black uppercase text-xs tracking-widest mb-3">Proven
                                        Expertise</h4>
                                    <p class="text-slate-700 text-sm leading-relaxed italic relative z-10">
                                        "One of our notable achievements was successfully organizing an international
                                        seminar during the
                                        COVID-19 pandemic,
                                        in collaboration with the <span
                                            class="text-slate-900 font-bold underline decoration-brand">House of
                                            Representatives</span> and global delegates."
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
                                Digital Ecosystem
                            </div>

                            <h2
                                class="text-4xl md:text-6xl font-black text-slate-900 leading-[0.9] tracking-tighter mb-6">
                                Our Learning <br />
                                <span class="text-brand">Management System</span>
                            </h2>

                            <p class="text-lg text-slate-600 leading-relaxed font-light max-w-2xl mb-8">
                                Learning and training are now easier and more efficient with our integrated Learning
                                Management System
                                (LMS). A smart solution for adaptive and flexible human resource development.
                            </p>

                            <div class="flex flex-wrap gap-3 mb-12">
                                <span
                                    class="px-4 py-2 bg-slate-50 border border-slate-100 rounded-lg text-xs font-medium text-slate-700">📚
                                    Self Learning</span>
                                <span
                                    class="px-4 py-2 bg-slate-50 border border-slate-100 rounded-lg text-xs font-medium text-slate-700">🤝
                                    Blended Learning</span>
                                <span
                                    class="px-4 py-2 bg-slate-50 border border-slate-100 rounded-lg text-xs font-medium text-slate-700">📱
                                    Microlearning</span>
                                <span
                                    class="px-4 py-2 bg-slate-50 border border-slate-100 rounded-lg text-xs font-medium text-slate-700">👨‍🏫
                                    Instructor-led Training</span>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">

                                <div>
                                    <h4 class="text-brand font-bold uppercase tracking-widest text-xs mb-4">Key Features
                                    </h4>
                                    <ul class="space-y-3">
                                        <li class="flex items-start gap-3 text-sm text-slate-600">
                                            <span class="text-brand">✔</span> Management of courses, materials, &
                                            virtual classes
                                        </li>
                                        <li class="flex items-start gap-3 text-sm text-slate-600">
                                            <span class="text-brand">✔</span> Discussion forums, chat, & video
                                            conferencing
                                        </li>
                                        <li class="flex items-start gap-3 text-sm text-slate-600">
                                            <span class="text-brand">✔</span> Interactive content: videos, quizzes, &
                                            case studies
                                        </li>
                                        <li class="flex items-start gap-3 text-sm text-slate-600">
                                            <span class="text-brand">✔</span> Notifications & academic calendar
                                            integration
                                        </li>
                                    </ul>
                                </div>

                                <div>
                                    <h4 class="text-brand font-bold uppercase tracking-widest text-xs mb-4">Benefits
                                    </h4>
                                    <ul class="space-y-3">
                                        <li class="flex items-start gap-3 text-sm text-slate-600">
                                            <span class="text-green-500 font-bold">↑</span> <b>Cost Efficiency:</b>
                                            Reduce training costs
                                        </li>
                                        <li class="flex items-start gap-3 text-sm text-slate-600">
                                            <span class="text-green-500 font-bold">↑</span> <b>Workforce Quality:</b>
                                            Optimized Competencies
                                        </li>
                                        <li class="flex items-start gap-3 text-sm text-slate-600">
                                            <span class="text-green-500 font-bold">↑</span> <b>Business Performance:</b>
                                            A vastly superior
                                            organization
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <div class="mt-12 p-6 bg-brand/5 rounded-2xl border border-brand/10">
                                <p class="text-slate-900 font-semibold mb-1">Ready to transform?</p>
                                <p class="text-sm text-slate-600 mb-4">Contact us now for a free demo & consultation!
                                </p>
                                <button
                                    class="bg-brand text-white px-6 py-3 rounded-xl font-bold text-sm hover:scale-105 transition-transform">
                                    Get Free Demo
                                </button>
                            </div>
                        </div>

                        <div class="relative">
                            <div class="lg:sticky lg:top-32 flex flex-col">
                                <div
                                    class="flex-1 flex flex-col bg-white rounded-[3rem] border border-slate-200 overflow-hidden shadow-2xl relative">

                                    <div class="p-8 space-y-6 flex-1">
                                        <div class="flex items-center justify-between">
                                            <span
                                                class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">
                                                Dashboard Preview
                                            </span>
                                            <div class="flex gap-1.5">
                                                <div class="w-1.5 h-1.5 rounded-full bg-slate-200"></div>
                                                <div class="w-1.5 h-1.5 rounded-full bg-slate-200"></div>
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-2 gap-4">
                                            <div v-for="item in lmsPreviews" :key="item.name"
                                                class="group/img relative cursor-pointer" @click="openModal(item)">
                                                <div
                                                    class="aspect-video rounded-xl bg-slate-100 border border-slate-100 overflow-hidden shadow-sm">
                                                    <img :src="item.path" :alt="item.name"
                                                        class="w-full h-full object-cover group-hover/img:scale-110 transition-transform duration-500" />

                                                    <div
                                                        class="absolute inset-0 bg-black/20 opacity-0 group-hover/img:opacity-100 transition-opacity flex items-center justify-center">
                                                        <span
                                                            class="text-white text-xs font-bold bg-black/40 px-3 py-1 rounded-full backdrop-blur-sm">View
                                                            Image</span>
                                                    </div>
                                                </div>
                                                <p
                                                    class="text-[10px] font-bold text-slate-400 mt-2 uppercase tracking-tighter">
                                                    {{ item.name }}
                                                </p>
                                            </div>
                                        </div>

                                        <!-- <div class="pt-4 mt-4 border-t border-slate-50">
                      <div class="h-2 w-full bg-slate-100 rounded-full overflow-hidden">
                        <div class="h-full bg-brand w-2/3 rounded-full"></div>
                      </div>
                      <p class="text-[9px] text-slate-400 mt-2 font-mono">System Load: Stable</p>
                    </div> -->
                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </section>

            <section id="clients" class="py-16 bg-slate-50 border-y border-slate-200 overflow-hidden">
                <div class="py-24">
                    <div class="max-w-7xl mx-auto px-6">
                        <div class="text-center mb-16">
                            <h2 class="text-xs font-bold text-brand uppercase tracking-[0.3em] mb-4">Trusted Partners
                            </h2>
                            <p class="text-3xl font-black text-slate-900 tracking-tighter">Collaborating with the finest
                                institutions.</p>
                        </div>

                        <div class="relative flex overflow-hidden">
                            <div class="flex marquee-content gap-12 items-center py-4">
                                <div v-for="(logo, index) in partnerLogos" :key="'a-' + index"
                                    class="flex-shrink-0 group flex items-center justify-center grayscale opacity-50 hover:grayscale-0 hover:opacity-100 transition-all duration-500">
                                    <img :src="logo" :alt="'Partner Logo ' + index"
                                        class="max-h-12 w-40 object-contain transform group-hover:scale-110 transition-transform duration-300" />
                                </div>
                                <div v-for="(logo, index) in partnerLogos" :key="'b-' + index"
                                    class="flex-shrink-0 group flex items-center justify-center grayscale opacity-50 hover:grayscale-0 hover:opacity-100 transition-all duration-500">
                                    <img :src="logo" :alt="'Partner Logo ' + index"
                                        class="max-h-12 w-40 object-contain transform group-hover:scale-110 transition-transform duration-300" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <footer id="contact" class="bg-slate-950 border-t border-slate-900 pt-20 pb-10 relative overflow-hidden">
                <div class="absolute bottom-0 right-0 w-[300px] h-[300px] bg-brand/5 blur-[120px] rounded-full -z-0">
                </div>

                <div class="max-w-7xl mx-auto px-4 relative z-10">
                    <div class="grid lg:grid-cols-2 gap-16 mb-16">

                        <div class="flex flex-col justify-between">
                            <div>
                                <h2 class="text-5xl md:text-7xl font-black text-white leading-none tracking-tighter">
                                    NOW TAKING<br />
                                    <span class="text-brand">BOOKINGS</span>
                                </h2>
                                <p class="mt-6 text-slate-500 max-w-sm">
                                    Ready to elevate your organization's potential? Let's start a conversation today.
                                </p>
                            </div>

                            <div class="mt-10">
                                <button
                                    class="group flex items-center gap-3 px-8 py-4 bg-white text-black rounded-full font-bold hover:bg-brand hover:text-white transition-all duration-300">
                                    Contact Us
                                    <span class="group-hover:translate-x-1 transition-transform">→</span>
                                </button>
                            </div>
                        </div>

                        <div class="space-y-10">
                            <div>
                                <h3 class="text-white font-bold mb-4 uppercase tracking-widest text-sm">Stay Connected
                                </h3>
                                <p class="text-slate-400 leading-relaxed mb-6">
                                    Get updates and information anytime from <span
                                        class="text-slate-200 font-semibold">PT Cakrawala
                                        Parama Internasional</span> by following our social media!
                                </p>

                                <div class="flex gap-4">
                                    <a href="#"
                                        class="w-12 h-12 rounded-full border border-slate-800 flex items-center justify-center text-slate-400 hover:border-brand hover:text-brand transition-all">
                                        <span class="text-xs font-bold">FB</span>
                                    </a>
                                    <a href="#"
                                        class="w-12 h-12 rounded-full border border-slate-800 flex items-center justify-center text-slate-400 hover:border-brand hover:text-brand transition-all">
                                        <span class="text-xs font-bold">IG</span>
                                    </a>
                                    <a href="#"
                                        class="w-12 h-12 rounded-full border border-slate-800 flex items-center justify-center text-slate-400 hover:border-brand hover:text-brand transition-all">
                                        <span class="text-xs font-bold">YT</span>
                                    </a>
                                    <a href="#"
                                        class="w-12 h-12 rounded-full border border-slate-800 flex items-center justify-center text-slate-400 hover:border-brand hover:text-brand transition-all">
                                        <span class="text-xs font-bold">IN</span>
                                    </a>
                                </div>
                            </div>

                            <div>
                                <h3 class="text-white font-bold mb-4 uppercase tracking-widest text-sm">Headquarters
                                </h3>
                                <address class="not-italic text-slate-500 text-sm leading-loose">
                                    South Quarter Tower A, Lt 18<br />
                                    Jl. R.A. Kartini No.Kav 8, Kec. Cilandak<br />
                                    Daerah Khusus Ibukota Jakarta 12430
                                </address>
                            </div>
                        </div>
                    </div>

                    <div
                        class="pt-8 border-t border-slate-900 flex flex-col md:flex-row justify-between items-center gap-4">
                        <p class="text-slate-600 text-xs uppercase tracking-widest">
                            &copy; 2026 PT Cakrawala Parama Internasional. All Rights Reserved.
                        </p>
                        <div class="flex gap-6 text-xs text-slate-600 uppercase tracking-widest">
                            <a href="#" class="hover:text-brand transition-colors">Privacy Policy</a>
                            <a href="#" class="hover:text-brand transition-colors">Terms of Service</a>
                        </div>
                    </div>
                </div>
            </footer>
        </main>
    </div>

    <Teleport to="body">
        <Transition name="fade">
            <div v-if="isModalOpen"
                class="fixed inset-0 z-[100] flex flex-col items-center justify-center p-4 md:p-8 bg-slate-900/95 backdrop-blur-md"
                @click.self="closeModal">

                <button @click="closeModal"
                    class="mb-4 text-white/70 hover:text-white transition-colors flex items-center gap-2 font-bold uppercase tracking-[0.2em] text-xs">
                    Close <span class="text-3xl leading-none">×</span>
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

    <transition name="fade">
        <button v-show="showBackToTop" @click="scrollToTop"
            class="fixed bottom-8 right-8 z-[60] flex items-center justify-center w-12 h-12 bg-white border border-slate-100 rounded-2xl shadow-xl cursor-pointer transition-all duration-300 hover:border-brand hover:text-brand hover:-translate-y-1 active:scale-95 group">
            <svg xmlns="http://www.w3.org/2000/svg"
                class="w-6 h-6 text-slate-600 group-hover:text-brand transition-colors" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7" />
            </svg>
        </button>
    </transition>

</template>

<style scoped>
:deep(.swiper-pagination-bullet) {
    background: #cbd5e1 !important;
    opacity: 1;
    width: 12px;
    height: 4px;
    border-radius: 2px;
    transition: all 0.3s ease;
}

:deep(.swiper-pagination-bullet-active) {
    background: #fff !important;
    /* Or your brand color */
    width: 32px;
}

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

.text-brand {
    color: #ea4435;
}

.hover\:border-brand:hover {
    border-color: #ea4435;
}

/* The animation moves the content 50% because we doubled the logos */
@keyframes marquee {
    0% {
        transform: translateX(0);
    }

    100% {
        transform: translateX(-50%);
    }
}

.marquee-content {
    animation: marquee 30s linear infinite;
    display: flex;
    width: max-content;
    /* Important: makes container as wide as all logos */
}

/* Pause animation when user hovers */
.marquee-content:hover {
    animation-play-state: paused;
}

/* Optional: Add a fade effect to the edges */
#clients .relative::before,
#clients .relative::after {
    content: "";
    position: absolute;
    top: 0;
    width: 100px;
    height: 100%;
    z-index: 2;
}

#clients .relative::before {
    left: 0;
    background: linear-gradient(to right, #f8fafc, transparent);
    /* Matches bg-slate-50 */
}

#clients .relative::after {
    right: 0;
    background: linear-gradient(to left, #f8fafc, transparent);
}
</style>