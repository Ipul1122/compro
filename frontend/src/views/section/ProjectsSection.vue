<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'

const dummyProjects = [
    {
        // id: 'LMS-2026',
        title: 'Guidance in Developing SKB Test Questions for the Functional Position of Legislative Affairs',
        category: 'Organizational Development',
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
</script>

<template>
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
                        :class="showAllProjects ? 'rotate-[-90deg]' : 'rotate-90'" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M14 5l7 7m0 0l-7 7m7-7H3">
                        </path>
                    </svg>

                    <div class="absolute inset-0 bg-brand opacity-0 group-hover:opacity-100 transition-opacity z-0">
                    </div>
                </button>
            </div>

        </div>
    </section>
</template>

<style scoped></style>