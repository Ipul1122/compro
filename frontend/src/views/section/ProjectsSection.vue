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
    {
        id: 'dpr_assessment_dec_2026',
        title: 'Pelaksanaan Asesmen Calon Staff Administrasi Anggota DPR RI Desember 2026',
        category: 'DPR RI',
        year: 2026,
        location: 'Jakarta',
        client: 'Biro SDMA Sekretariat Jenderal DPR RI'
    },
    {
        id: 'dpr_digital_content_2025',
        title: 'Pekerjaan Pembuatan Konten Digital Pelatihan ASN Pusbangkom Sekretariat Jenderal DPR RI Tahun 2025',
        category: 'DPR RI',
        year: 2025,
        location: 'Jakarta',
        client: 'Pusat Pengembangan Kompetensi SDM Legislatif Sekretariat jenderal DPR RI'
    },
    {
        id: 'iso_9001_clinic_2025',
        title: 'Pendampingan, Pelatihan, dan Sertifikasi ISO 9001 Klinik Utama',
        category: 'Lainnya',
        year: 2025,
        location: 'Jakarta',
        client: 'Klinik Utama'
    },
    {
        id: 'bareskrim_gambling_2025',
        title: 'Press Conference Dittipidsiber Bareskrim Polri "PERKEMBANGAN PENANGANAN LHA PPATK DAN PENGUNGKAPAN JUDI ONLINE"',
        category: 'Bareskrim',
        year: 2025,
        location: 'Jakarta',
        client: 'Dittipidsiber Bareskrim Polri'
    },
    {
        id: 'dpr_perisalah_2025',
        title: 'Pengembangan Modul dan Konten Digital Pelatihan Perisalah Rapat Legislatif Sekretariat Jenderal DPR RI',
        category: 'DPR RI',
        year: 2025,
        location: 'Jakarta',
        client: 'Pusat Pengembangan Kompetensi SDM Legislatif Sekretariat jenderal DPR RI'
    },
    {
        id: 'dpr_conefo_design_2025',
        title: 'Pekerjaan Desain Event Seminar Nasional "Dari Conefo Menjadi Rumah Rakyat" Museum dan Humas Sekretariat Jenderal DPR RI',
        category: 'DPR RI',
        year: 2025,
        location: 'Jakarta',
        client: 'Humas Sekretariat Jenderal DPR RI'
    },
    {
        id: 'dpr_fgd_performance_2025',
        title: 'Pelaksanaan Focus Group Discussion "Penyusunan Laporan Kinerja DPR RI Secara Komprehensif dan Informatif" Biro Kesekretariatan Pimpinan DPR RI',
        category: 'DPR RI',
        year: 2025,
        location: 'Jakarta',
        client: 'Biro Kesekretariatan Pimpinan Sekretariat Jenderal DPR RI'
    },
    {
        id: 'dpr_fgd_speech_2025',
        title: 'Pelaksanaan Focus Group Discussion "MERUMUSKAN PIDATO KETUA DPR RI YANG RELEVAN REPRESENTATIF DAN BERNAS" Biro Kesekretariatan Pimpinan DPR RI',
        category: 'DPR RI',
        year: 2025,
        location: 'Jakarta',
        client: 'Biro Kesekretariatan Pimpinan Sekretariat Jenderal DPR RI'
    },
    {
        id: 'bareskrim_fb_case_2025',
        title: 'Press Conference Dittipidsiber Bareskrim Polri "PENGUNGKAPAN KASUS GRUP FACEBOOK FANTASI SEDARAH & SUKA DUKA"',
        category: 'Bareskrim',
        year: 2025,
        location: 'Jakarta',
        client: 'Dittipidsiber Bareskrim Polri'
    },
    {
        id: 'sbm_itb_resilience_2025',
        title: 'Workshop resilience and global mindset The Undergraduate Program in Management School of Business & Management ITB',
        category: 'Lainnya',
        year: 2025,
        location: 'Bandung',
        client: 'Sekolah Bisnis dan Manajemen Institut Teknologi Bandung'
    },
    {
        id: 'bareskrim_provocation_2025',
        title: 'Press Conference Dittipidsiber Bareskrim Polri "PENGUNGKAPAN KASUS MENYEBARKAN PROVOKASI, MENGAJAK, DAN MENGHASUT MASYARAKAT MELALUI MEDIA SOSIAL UNTUK MELAKUKAN TINDAKAN MELANGGAR HUKUM PADA SAAT UNJUK RASA"',
        category: 'Bareskrim',
        year: 2025,
        location: 'Jakarta',
        client: 'Dittipidsiber Bareskrim Polri'
    },
    {
        id: 'bareskrim_illegal_access_2025',
        title: 'Press Conference Dittipidsiber Bareskrim Polri "PENGUNGKAPAN TINDAK PIDANA ILLEGAL ACCESS PLATFORM TRADING WWW.MARKETS.COM & PENGUNGKAPAN KASUS PENGANCAMAN DAN PEMERASAN SERTA PENYEBARAN DATA PRIBADI (PINJOL ILEGAL)"',
        category: 'Bareskrim',
        year: 2025,
        location: 'Jakarta',
        client: 'Dittipidsiber Bareskrim Polri'
    },
    {
        id: 'bareskrim_direction_event_2025',
        title: 'Pekerjaan event Dittipidsiber Bareskrim Polri "Arahan Kabareskrim Polri Kepada Personel Dittipidsiber Bareskrim Polri, Ditressiber, dan Subdit Siber Polda Jajaran"',
        category: 'Bareskrim',
        year: 2025,
        location: 'Jakarta',
        client: 'Dittipidsiber Bareskrim Polri'
    },
    {
        id: 'bareskrim_charity_event_2025',
        title: 'Pekerjaan event Dittipidsiber Bareskrim Polri "Kajian dan Santunan Anak Yatim: Pembinaan Rohani dan Mental Personel Bareskrim Polri"',
        category: 'Bareskrim',
        year: 2025,
        location: 'Jakarta',
        client: 'Dittipidsiber Bareskrim Polri'
    },
    {
        id: 'dpr_assessment_june_2025',
        title: 'Pelaksanaan Asesmen Tenaga Ahli Fraksi, Tenaga Ahli Anggota, dan Staff Administrasi Anggota DPR RI Juni 2025',
        category: 'DPR RI',
        year: 2025,
        location: 'Jakarta',
        client: 'Biro SDMA Sekretariat Jenderal DPR RI'
    },
    {
        id: 'dpr_assessment_sept_2025',
        title: 'Pelaksanaan Asesmen Tenaga Ahli Fraksi, Tenaga Ahli Anggota, dan Staff Administrasi Anggota DPR RI September 2025',
        category: 'DPR RI',
        year: 2025,
        location: 'Jakarta',
        client: 'Biro SDMA Sekretariat Jenderal DPR RI'
    },
    {
        id: 'dpr_recruitment_2025',
        title: 'Pelaksanaan Rekrutmen Calon Pegawai Sekretariat Jenderal DPR RI 2025',
        category: 'DPR RI',
        year: 2025,
        location: 'Jakarta',
        client: 'Biro SDMA Sekretariat Jenderal DPR RI'
    },
    {
        id: 'dpr_assessment_oct_2025',
        title: 'Pelaksanaan Asesmen Tenaga Ahli Fraksi, Tenaga Ahli Anggota, dan Staff Administrasi Anggota DPR RI Oktober 2025',
        category: 'DPR RI',
        year: 2025,
        location: 'Jakarta',
        client: 'Biro SDMA Sekretariat Jenderal DPR RI'
    },
    {
        id: 'dpr_assessment_nov_2025',
        title: 'Pelaksanaan Asesmen Tenaga Ahli Fraksi, Tenaga Ahli Anggota, dan Staff Administrasi Anggota DPR RI November 2025',
        category: 'DPR RI',
        year: 2025,
        location: 'Jakarta',
        client: 'Biro SDMA Sekretariat Jenderal DPR RI'
    },
    {
        id: 'dpr_assessment_dec_2025',
        title: 'Pelaksanaan Asesmen Tenaga Ahli Fraksi, Tenaga Ahli Anggota, dan Staff Administrasi Anggota DPR RI Desember 2025',
        category: 'DPR RI',
        year: 2025,
        location: 'Jakarta',
        client: 'Biro SDMA Sekretariat Jenderal DPR RI'
    },
    {
        id: 'dpd_renovation_2025',
        title: 'Proyek Renovasi Ruang Kepala dan Staff Sekretariat Biro Keuangan Sekretariat DPD RI',
        category: 'DPD RI',
        year: 2025,
        location: 'Jakarta',
        client: 'Biro Pemeliharaan Umum Sekretariat Jenderal DPD RI'
    },
    {
        id: 'dpd_toilet_renovation_2025',
        title: 'Proyek Renovasi Toilet dan Tempat Wudhu Wanita DPD RI',
        category: 'DPD RI',
        year: 2025,
        location: 'Jakarta',
        client: 'Biro Pemeliharaan Umum Sekretariat Jenderal DPD RI'
    },
    {
        id: 'bareskrim_gambling_disclosure_2026',
        title: 'Press Conference Dittipidsiber Bareskrim Polri "PENGUNGKAPAN PRAKTIK JUDI ONLINE DAN PERKEMBANGAN PENANGANAN LHA PPATK"',
        category: 'Bareskrim',
        year: 2026,
        location: 'Jakarta',
        client: 'Dittipidsiber Bareskrim Polri'
    },
    {
        id: 'bareskrim_perma_execution_2026',
        title: 'Giat Eksekusi Perma Dittipidsiber Bareskrim Polri',
        category: 'Bareskrim',
        year: 2026,
        location: 'Jakarta',
        client: 'Dittipidsiber Bareskrim Polri'
    },
    {
        id: 'bareskrim_etilang_phishing_2026',
        title: 'Press Conference Dittipidsiber Bareskrim Polri "PENGUNGKAPAN PERKARA SMS BLAST PHISING WEBSITE E-TILANG PALSU"',
        category: 'Bareskrim',
        year: 2026,
        location: 'Jakarta',
        client: 'Dittipidsiber Bareskrim Polri'
    },
    {
        id: 'bareskrim_w3ll_store_phishing_2026',
        title: 'Press Conference Dittipidsiber Bareskrim Polri "PENGUNGKAPAN PERKARA PRODUKSI DAN PENJUALAN PHISING TOOLS ILEGAL W3LL.STORE DITTIPIDSIBER BARESKRIM POLRI"',
        category: 'Bareskrim',
        year: 2026,
        location: 'Jakarta',
        client: 'Dittipidsiber Bareskrim Polri'
    },
    {
        id: 'dpr_tugas_belajar_2026',
        title: 'Pelaksanaan Seleksi Tugas Belajar Dibiayai APBN 2026 Sekretariat Jenderal DPR RI',
        category: 'DPR RI',
        year: 2026,
        location: 'Jakarta',
        client: 'Pusat Pengembangan Kompetensi SDM Legislariat Jenderal DPR RI'
    },
    {
        id: 'bareskrim_fbi_video_2026',
        title: 'Pembuatan Video Selayang Pandang, Penghargaan Anggota, dan Kerja Sama FBI Dittipidsiber Bareskrim Polri',
        category: 'Bareskrim',
        year: 2026,
        location: 'Jakarta',
        client: 'Dittipidsiber Bareskrim Polri'
    }
];

const itemsToShow = 6
const selectedCategory = ref('All')
const selectedYear = ref('All')

// Extract unique categories in custom order
const categories = computed(() => {
    const order = ['All', 'DPR RI', 'Bareskrim', 'DPD RI', 'Lainnya']
    const uniqueCategories = new Set(dummyProjects.map(p => p.category))
    return order.filter(cat => cat === 'All' || uniqueCategories.has(cat))
})

// Extract unique years sorted descending
const years = computed(() => {
    const uniqueYears = Array.from(new Set(dummyProjects.map(p => p.year.toString())))
    uniqueYears.sort((a, b) => parseInt(b) - parseInt(a))
    return ['All', ...uniqueYears]
})

// Logic to switch view category
const setCategory = (cat) => {
    selectedCategory.value = cat
}

// Logic to switch view year
const setYear = (yr) => {
    selectedYear.value = yr
}

// Menghitung proyek yang akan ditampilkan, terurut dari tahun terbaru -> terlama
const filteredProjects = computed(() => {
    let list = [...dummyProjects]
    
    // Sort by year descending (newest -> oldest).
    list.sort((a, b) => b.year - a.year)

    if (selectedCategory.value !== 'All') {
        list = list.filter(p => p.category === selectedCategory.value)
    }

    if (selectedYear.value !== 'All') {
        list = list.filter(p => p.year.toString() === selectedYear.value)
    }
    
    // Jika showButton true (berarti sedang di HomeView), batasi hanya tampil itemsToShow
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

                <div class="pt-6 flex flex-col items-center gap-4" v-if="showFilter">
                    <!-- Kategori Filter -->
                    <div class="overflow-x-auto no-scrollbar w-full flex justify-center">
                        <div class="flex flex-nowrap md:flex-wrap items-center gap-3 md:gap-4 pb-2">
                            <button v-for="cat in categories" :key="cat" @click="setCategory(cat)"
                                :class="selectedCategory === cat ? 'bg-gradient-to-r from-red-600 to-red-600 text-white shadow-lg shadow-red-600/30 border-transparent' : 'bg-white text-slate-600 border-slate-200 hover:border-red-400 hover:text-red-600'"
                                class="whitespace-nowrap px-6 py-2.5 rounded-full text-sm font-semibold transition-all duration-300 cursor-pointer active:scale-95 border">
                                {{ $t('projects.categories.' + cat) || cat }}
                            </button>
                        </div>
                    </div>

                    <!-- Tahun Filter -->
                    <div class="overflow-x-auto no-scrollbar w-full flex justify-center">
                        <div class="flex flex-nowrap md:flex-wrap items-center gap-3 md:gap-4 pb-2">
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider mr-2 hidden md:inline-block">
                                {{ $t('projects.year_label') || 'Tahun:' }}
                            </span>
                            <button v-for="yr in years" :key="yr" @click="setYear(yr)"
                                :class="selectedYear === yr ? 'bg-slate-800 text-white shadow-lg border-transparent' : 'bg-white text-slate-600 border-slate-200 hover:border-slate-400 hover:text-slate-800'"
                                class="whitespace-nowrap px-5 py-2 rounded-full text-xs font-bold transition-all duration-300 cursor-pointer active:scale-95 border">
                                {{ yr === 'All' ? ($t('projects.filter_all') || 'Semua') : yr }}
                            </button>
                        </div>
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
                                {{ $t('projects.categories.' + project.category) || project.category }}
                            </span>
                            <span class="text-4xl font-black text-slate-100 group-hover:text-red-100 transition-colors font-['Montserrat']">
                                {{ (index + 1).toString().padStart(2, '0') }}
                            </span>
                        </div>
                        
                        <h4 class="text-xl font-bold text-slate-800 leading-snug group-hover:text-red-600 transition-colors font-['Montserrat']">
                            {{ $t('projects.list.' + project.id) || project.title }}
                        </h4>

                        <div class="flex items-center gap-4 text-xs font-semibold text-slate-400">
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                {{ project.year }}
                            </span>
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                {{ project.location }}
                            </span>
                        </div>

                        <p class="text-xs text-slate-400 font-medium line-clamp-2" v-if="project.client">
                            {{ project.client }}
                        </p>
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
                    {{ $t('projects.stats_text', { count: filteredProjects.length, total: dummyProjects.length }) }}
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