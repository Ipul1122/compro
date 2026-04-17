<script setup>
import { ref, computed } from 'vue'

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

const showAllProjects = ref(false)
const itemsToShow = 6
const selectedCategory = ref('All')

// Extract unique categories
const categories = computed(() => {
    return ['All', ...new Set(dummyProjects.map(p => p.category))]
})

// Logic to switch view and reset "Show All" when category changes
const setCategory = (cat) => {
    selectedCategory.value = cat
    showAllProjects.value = false // Optional: collapse list when changing category
}

const filteredProjects = computed(() => {
    let list = dummyProjects
    if (selectedCategory.value !== 'All') {
        list = list.filter(p => p.category === selectedCategory.value)
    }
    return showAllProjects.value ? list : list.slice(0, itemsToShow)
})
</script>

<template>
    <section id="our-project" class="py-32 bg-white selection:bg-brand selection:text-white overflow-hidden">
        <div class="max-w-7xl mx-auto px-6">

            <header class="mb-24">
                <div class="flex items-center gap-4 mb-8">
                    <span class="h-[2px] w-12 bg-brand rounded-full"></span>
                    <h2 class="text-[10px] font-black text-brand uppercase tracking-[0.5em]">{{ $t('projects.badge') ||
                        'Global Portfolio' }}</h2>
                </div>

                <div class="mb-12">
                    <h3 class="text-6xl md:text-8xl font-black tracking-tighter leading-[0.85]">
                        <span class="text-transparent" style="-webkit-text-stroke: 1.5px #ea4435;">
                            {{ $t('projects.title_our') || 'Our' }}
                        </span>
                        <br />
                        <span class="text-slate-900">
                            {{ $t('projects.title_projects') || 'Projects.' }}
                        </span>
                    </h3>
                    <p class="mt-6 text-slate-400 font-bold uppercase tracking-widest text-xs">Cakrawala Parama
                        Internasional</p>
                </div>

                <div class="pt-8 border-t border-slate-100 overflow-x-auto no-scrollbar">
                    <div class="flex flex-nowrap md:flex-wrap items-center gap-3">
                        <span
                            class="hidden md:block text-[10px] font-black text-slate-300 uppercase tracking-widest mr-4">{{
                                $t('projects.filter_label') || 'Filter By:' }}</span>
                        <button v-for="cat in categories" :key="cat" @click="setCategory(cat)"
                            :class="selectedCategory === cat ? 'bg-slate-900 text-white border-slate-900 shadow-xl shadow-slate-900/10' : 'bg-white text-slate-500 border-slate-100 hover:border-slate-300'"
                            class="whitespace-nowrap px-6 py-2.5 rounded-full text-[10px] font-black uppercase tracking-widest border transition-all duration-300 cursor-pointer active:scale-95">
                            {{ cat }}
                        </button>
                    </div>
                </div>
            </header>

            <transition-group name="list" tag="div" class="space-y-6">
                <div v-for="(project, index) in filteredProjects" :key="project.id"
                    class="group relative bg-white border border-slate-100 rounded-[2.5rem] p-8 md:p-14 flex flex-col md:flex-row gap-8 items-center transition-all duration-500 hover:shadow-[0_40px_100px_-20px_rgba(0,0,0,0.1)] hover:-translate-y-2 cursor-pointer">

                    <div class="hidden md:block w-20">
                        <span
                            class="text-6xl font-black text-slate-50 group-hover:text-brand/10 transition-colors duration-500 leading-none">
                            {{ (index + 1).toString().padStart(2, '0') }}
                        </span>
                    </div>

                    <div class="flex-1 space-y-4 text-center md:text-left">
                        <div class="flex flex-wrap items-center justify-center md:justify-start gap-3">
                            <span
                                class="px-4 py-1.5 bg-slate-50 text-slate-400 text-[10px] font-black uppercase tracking-widest rounded-xl border border-slate-100 group-hover:bg-brand group-hover:text-white group-hover:border-brand transition-all duration-300">
                                {{ project.category }}
                            </span>
                        </div>
                        <h4
                            class="text-2xl md:text-3xl font-black text-slate-800 leading-[1.15] group-hover:text-slate-900 transition-colors max-w-4xl tracking-tight">
                            {{ $t('projects.list.' + project.id) || project.title }}
                        </h4>
                    </div>

                    <div class="shrink-0">
                        <div
                            class="w-16 h-16 rounded-full border border-slate-100 flex items-center justify-center group-hover:bg-[#ea4435] group-hover:border-[#ea4435] group-hover:shadow-2xl group-hover:shadow-red-500/40 transition-all duration-500 transform group-hover:rotate-45">
                            <svg class="w-7 h-7 text-slate-300 group-hover:text-white transition-colors" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </div>
                    </div>
                </div>
            </transition-group>

            <div class="mt-24 flex flex-col items-center gap-8">
                <button @click="showAllProjects = !showAllProjects"
                    class="group relative px-14 py-6 bg-slate-900 text-white rounded-full font-black text-[11px] uppercase tracking-[0.3em] overflow-hidden transition-all active:scale-95 shadow-[0_25px_50px_-12px_rgba(0,0,0,0.25)]">
                    <span class="relative z-10 flex items-center gap-6">
                        {{ showAllProjects ? ($t('projects.button_less') || 'Close Project Archive') :
                            ($t('projects.button_more') || 'View Full Project Library') }}
                        <div class="w-6 h-6 bg-white/10 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 transition-transform duration-500"
                                :class="showAllProjects ? 'rotate-[-90deg]' : 'rotate-90'" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                    d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </div>
                    </span>
                    <div
                        class="absolute inset-0 bg-[#ea4435] translate-y-full group-hover:translate-y-0 transition-transform duration-500 ease-in-out">
                    </div>
                </button>
                <p class="text-slate-300 text-[10px] font-black uppercase tracking-[0.3em]">
                    {{ $t('projects.stats_prefix') || 'Showing' }} {{ filteredProjects.length }} Projects
                </p>
            </div>

        </div>
    </section>
</template>

<style scoped>
/* Red Brand Colors */
.text-brand {
    color: #ea4435;
}

.bg-brand {
    background-color: #ea4435;
}

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
    transform: translateY(30px);
}
</style>