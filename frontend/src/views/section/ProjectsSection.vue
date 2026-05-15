<script setup>
import { ref, computed } from 'vue'

const props = defineProps({
    showButton: {
        type: Boolean,
        default: true // Default: tombol 'View More' akan muncul (untuk di Home)
    },
    showFilter: {
        type: Boolean,
        default: false // Default: filter akan sembunyi (untuk di Home)
    }
})

const dummyProjects = [
    { id: 'skb_legislative', title: 'Guidance in Developing SKB Test Questions for the Functional Position of Legislative Affairs', category: 'Organizational Development' },
    { id: 'hcdp_plan', title: 'Preparation of the Human Capital Development Plan (HCDP)', category: 'Organizational Development' },
    { id: 'dpr_is', title: 'Development of the Functional Position Information System for the Indonesian House of Representatives (DPR RI)', category: 'Organizational Development' },
    { id: 'bkkbn_ebook', title: 'Creation of an e-book for the Socialization of the BKKBN Stunting Program', category: 'Organizational Development' },
    { id: 'trisakti_hris', title: 'Development of Human Resource Management Systems (HRIS) at STMT, STP, STBM, STIA, STIK Trisakti', category: 'Organizational Development' },
    { id: 'bkkbn_video', title: 'Production of Training Videos for BKKBN', category: 'Organizational Development' },
    { id: 'trisakti_kpi', title: 'Development of Workload Analysis and Key Performance Indicators (KPI) for Trisakti Higher Education Institutions ', category: 'Organizational Development' },
    { id: 'dephub_test', title: 'Administration of the Basic Competency Selection Test at the Ministry of Transportation of the Republic of Indonesia, 2021', category: 'Event Organizer' },
    { id: 'museum_day', title: 'Implementation of National Museum Day Activities, 2023', category: 'Event Organizer' },
    { id: 'bkd_synergy', title: 'Coordination Meeting of the Expert Agency for Performance Evaluation to Enhance Synergy and Strengthen the Products and Services of the Expert Agency (BKD), 2024', category: 'Event Organizer' },
    { id: 'police_press', title: 'Press Release for the Criminal Investigation Agency (Bareskrim), National Police Headquarters, 2025', category: 'Event Organizer' },
    { id: 'conefo_dpr', title: '"From Conefo to the House of the People” – Public Relations of the Indonesian House of Representatives (DPR RI), 2025', category: 'Event Organizer' },
    { id: 'dpd_assessment', title: 'Assessment of All Employees of the Regional Representative Council (DPD RI)', category: 'Employee Potential' },
    { id: 'pu_assessment', title: 'Assessment of Employees at the Ministry of Public Works (PU RI) ', category: 'Employee Potential' },
    { id: 'echelon_selection', title: 'Assessment for the Echelon 1 Leadership Position Selection at the Indonesian House of Representatives (DPR RI)', category: 'Employee Potential' },
    { id: 'cpns_dpr', title: 'Representatives (DPR RI) Potential Assessment of Civil Servant Candidates (CPNS) at DPR RI', category: 'Employee Potential' },
    { id: 'youth_parliament', title: 'Recruitment for Participants of the 2023 Youth Parliament Seminar', category: 'Employee Potential' },
    { id: 'psychometric_dpr', title: 'Psychometric Testing of Prospective Expert Staff for DPR RI Members for the 2024–2029 term (2025)', category: 'Employee Potential' },
    { id: 'stress_workshop', title: 'Self-Transformation Workshop to Enhance Work Motivation & Stress Management, 2023', category: 'Workshop and Training' },
    { id: 'education_seminar', title: 'National Seminar on Strengthening the Implementation of the National Education System through Evaluation and Regulatory Improvement in the Education Sector, Puspanlak DPR RI, 2024', category: 'Workshop and Training' },
    { id: 'leadership_workshop', title: 'Workshop on Enhancing Employee Competence and Performance Capacity, Theme: “Decision Making and Adaptive Leadership”', category: 'Workshop and Training' },
    { id: 'teambuilding', title: 'Teambuilding and Outbound Training to Strengthen Team Collaboration', category: 'Workshop and Training' },
    { id: 'resilience_itb', title: 'Workshop “Resilience & Global Mindset, ” SBM ITB, 2025', category: 'Workshop and Training' },
    { id: 'coaching_gov', title: 'One-on-One Coaching for employees to help plan their career paths and align individual goals with organizational objectives in government institutions ', category: 'Coaching and Counseling' },
    { id: 'itb_career', title: 'Assisting Prospective ITB Graduates in Planning Their Careers', category: 'Coaching and Counseling' },
    { id: 'covid_entrepreneur', title: 'Supporting Entrepreneurs During the COVID Era to Build Greater Endurance and Sustain Their Businesses Under Pandemic Conditions', category: 'Coaching and Counseling' },
    { id: 'iso_37001', title: 'ISO 37001:2016 – AntiBribery Management System ', category: 'Coaching and Counseling' },
    { id: 'bnsp_comm', title: 'Organizational Communication Management Training and Certification for Employees with BNSP Certification', category: 'Coaching and Counseling' },
    { id: 'iso_9001', title: 'ISO 9001:2015 – Quality Management System for the Health Service Unit of DPR RI, 2025', category: 'Coaching and Counseling' }
];

const itemsToShow = 3
const selectedCategory = ref('All')

// Extract unique categories
const categories = computed(() => {
    return ['All', ...new Set(dummyProjects.map(p => p.category))]
})

// Logic to switch view category
const setCategory = (cat) => {
    selectedCategory.value = cat
}

// Menghitung proyek yang akan ditampilkan
const filteredProjects = computed(() => {
    let list = dummyProjects
    if (selectedCategory.value !== 'All') {
        list = list.filter(p => p.category === selectedCategory.value)
    }
    
    // Jika showButton true (berarti sedang di HomeView), batasi hanya tampil 6
    // Jika showButton false (berarti sedang di ProjectsView), tampilkan semua hasil filter
    return props.showButton ? list.slice(0, itemsToShow) : list
})
</script>

<template>
    <section id="our-project" class="py-24 bg-transparent overflow-hidden font-['Quicksand']">
        <div class="max-w-7xl mx-auto px-6">

            <header class="mb-16 text-center">
                <div class="inline-flex items-center gap-3 mb-4">
                    <span class="h-1 w-12 bg-gradient-to-r from-red-600 to-red-600 rounded-full"></span>
                    <h2 class="text-xs font-bold text-red-600 uppercase tracking-widest font-['Montserrat']">
                        {{ $t('projects.badge') || 'Global Portfolio' }}
                    </h2>
                    <span class="h-1 w-12 bg-gradient-to-r from-red-600 to-red-600 rounded-full"></span>
                </div>

                <div class="mb-10">
                    <h3 class="text-4xl md:text-6xl font-extrabold tracking-tight text-slate-900 font-['Montserrat']">
                        {{ $t('projects.title_our') || 'Our' }}
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-red-600 to-red-600">
                            {{ $t('projects.title_projects') || 'Projects.' }}
                        </span>
                    </h3>
                    <p class="mt-4 text-slate-500 font-medium text-sm md:text-base max-w-2xl mx-auto">
                        Cakrawala Parama Internasional
                    </p>
                </div>

                <div class="pt-6 overflow-x-auto no-scrollbar" v-if="showFilter">
                    <div class="flex flex-nowrap md:flex-wrap justify-center items-center gap-3 md:gap-4 pb-4">
                        <button v-for="cat in categories" :key="cat" @click="setCategory(cat)"
                            :class="selectedCategory === cat ? 'bg-gradient-to-r from-red-600 to-red-600 text-white shadow-lg shadow-red-600/30 border-transparent' : 'bg-white text-slate-600 border-slate-200 hover:border-red-400 hover:text-red-600'"
                            class="whitespace-nowrap px-6 py-2.5 rounded-full text-sm font-semibold transition-all duration-300 cursor-pointer active:scale-95 border">
                            {{ cat }}
                        </button>
                    </div>
                </div>
            </header>

            <transition-group name="list" tag="div" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div v-for="(project, index) in filteredProjects" :key="project.id"
                    class="group relative bg-white border border-slate-100 rounded-2xl p-8 flex flex-col justify-between transition-all duration-300 hover:shadow-2xl hover:shadow-red-600/10 hover:-translate-y-1 cursor-pointer overflow-hidden">
                    
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-red-50 to-red-50 rounded-bl-full -z-10 group-hover:scale-110 transition-transform duration-500"></div>

                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="px-3 py-1 bg-red-50 text-red-600 text-xs font-bold rounded-lg border border-red-100">
                                {{ project.category }}
                            </span>
                            <span class="text-4xl font-black text-slate-100 group-hover:text-red-100 transition-colors font-['Montserrat']">
                                {{ (index + 1).toString().padStart(2, '0') }}
                            </span>
                        </div>
                        <h4 class="text-xl font-bold text-slate-800 leading-snug group-hover:text-red-600 transition-colors font-['Montserrat']">
                            {{ $t('projects.list.' + project.id) || project.title }}
                        </h4>
                    </div>

                    <div class="mt-8 flex items-center gap-3 text-sm font-bold text-slate-400 group-hover:text-red-500 transition-colors">
                        <span>View Details</span>
                        <svg class="w-5 h-5 transform group-hover:translate-x-2 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </div>
                </div>
            </transition-group>

            <div class="mt-16 flex flex-col items-center gap-6" v-if="showButton">
                <router-link :to="$i18n.locale === 'id' ? '/id/proyek-kami' : '/en/projects'" 
                    class="group relative px-8 py-4 bg-gradient-to-r from-red-600 to-red-600 text-white rounded-full font-bold text-sm uppercase tracking-wider overflow-hidden transition-all active:scale-95 shadow-lg shadow-red-600/30 flex items-center gap-4">
                    <span class="relative z-10 flex items-center gap-3 font-['Montserrat']">
                        {{ $t('projects.button_more') || 'View All Projects' }}
                        <svg class="w-5 h-5 transition-transform duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </span>
                </router-link>
            </div>

            <div class="mt-12 text-center" v-if="showFilter">
                <p class="text-slate-400 text-sm font-medium">
                    {{ $t('projects.stats_prefix') || 'Showing' }} <span class="font-bold text-slate-700">{{ filteredProjects.length }}</span> Projects
                </p>
            </div>

        </div>
    </section>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800;900&family=Quicksand:wght@400;500;600;700&display=swap');

/* Hide scrollbar for category filter on mobile */
.no-scrollbar::-webkit-scrollbar {
    display: none;
}

.no-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
}

/* List Animation */
.list-enter-active,
.list-leave-active {
    transition: all 0.5s ease;
}

.list-enter-from,
.list-leave-to {
    opacity: 0;
    transform: translateY(20px);
}

.list-move {
    transition: transform 0.5s ease;
}
</style>