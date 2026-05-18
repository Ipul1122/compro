<script setup>
import { computed } from 'vue'
import { useI18n } from 'vue-i18n'

const { locale } = useI18n()
const isEnglish = computed(() => locale.value === 'en')

const copy = computed(() => {
    if (isEnglish.value) {
        return {
            badge: 'Implementation Service',
            title: 'Practical Implementation for Measurable Change',
            desc: 'We help organizations turn strategy, systems, and HR development programs into operational workflows that can be adopted by teams with clear milestones and measurable results.',
            primary: 'Discuss Implementation',
            secondary: 'View Process',
            sectionLabel: 'Implementation Scope',
            sectionTitle: 'From planning to adoption',
            sectionDesc: 'Our team supports implementation with structured coordination, documentation, training, monitoring, and continuous improvement.',
            stepsTitle: 'Implementation Flow',
            ctaTitle: 'Ready to implement your next program?',
            ctaDesc: 'Talk with our team to map priorities, timeline, and the practical support your organization needs.',
            ctaButton: 'Contact Us',
        }
    }

    return {
        badge: 'Layanan Implementasi',
        title: 'Implementasi Praktis untuk Perubahan Terukur',
        desc: 'Kami membantu organisasi menerjemahkan strategi, sistem, dan program pengembangan SDM menjadi alur kerja operasional yang mudah diadopsi tim, dengan tahapan jelas dan hasil yang terukur.',
        primary: 'Diskusikan Implementasi',
        secondary: 'Lihat Proses',
        stepsTitle: 'Alur Implementasi',
        ctaTitle: 'Siap menjalankan program berikutnya?',
        ctaDesc: 'Diskusikan prioritas, timeline, dan bentuk pendampingan praktis yang dibutuhkan organisasi Anda.',
        ctaButton: 'Hubungi Kami',
    }
})

const steps = computed(() => isEnglish.value ? [
    'Discovery',
    'Implementation Plan',
    'Execution',
    'Monitoring',
] : [
    'Pemetaan',
    'Rencana Implementasi',
    'Eksekusi',
    'Monitoring',
])

const workScopes = computed(() => isEnglish.value ? [
    {
        title: 'Designer/Planner',
        icon: '✏️',
        responsibilities: [
            'Develop concept and design of program/system',
            'Create layout & zoning program',
            'Visualize 3D and design images',
            'Determine material utilization',
        ]
    },
    {
        title: 'Estimator',
        icon: '📊',
        responsibilities: [
            'Volume calculation of implementation work',
            'Budget calculation and budget analysis (RAB) and cost analysis',
            'Cost analysis (Cost Control) for internal and external monitoring',
        ]
    },
    {
        title: 'Executor',
        icon: '🛠️',
        responsibilities: [
            'Execute & coordinate field work',
            'Carry out work according to image and specifications',
            'Coordinate work against implementation process',
            'Control implementation progress daily',
        ]
    },
    {
        title: 'Procurement & Logistics',
        icon: '📦',
        responsibilities: [
            'Arrange supply chain for entire material, furniture, and equipment procurement',
            'Arrange supply of materials, equipment, design, and schedule',
            'Coordinate supply, procurement, and material distribution to project site',
        ]
    },
    {
        title: 'Supervisor',
        icon: '👁️',
        responsibilities: [
            'Oversee project work implementation in the field',
            'Document project work according to specified images and specifications',
            'Coordinate with Quality Control',
            'Create project oversight reports',
        ]
    },
    {
        title: 'Administration',
        icon: '📋',
        responsibilities: [
            'Project document administration and project documentation',
            'Submit and collect work contracts, POs, and addendums',
        ]
    },
] : [
    {
        title: 'Designer/Perencana',
        icon: '✏️',
        responsibilities: [
            'Pengembangan konsep desain interior',
            'Pembuatan layout & zoning ruang',
            'Visualisasi 3D & gambar kerja',
            'Menentukan penggunaan material',
        ]
    },
    {
        title: 'Estimator',
        icon: '📊',
        responsibilities: [
            'Perhitungan Volume Pekerjaan',
            'Penyusunan Rencana Anggaran Biaya (RAB) termasuk Analisis Harga Satuan (AHS) dan Pengendalian Biaya (Cost Control) untuk kebijakan internal dan external (Penarawan)',
        ]
    },
    {
        title: 'Pelaksana',
        icon: '🛠️',
        responsibilities: [
            'Mengerjakan & mengatur pekerjaan lapangan sesuai gambar kerja & spesifikasi',
            'Bertanggung jawab terhadap Proses pengerjaan',
            'Mengontrol progres pekerjaan harian',
        ]
    },
    {
        title: 'Purchasing & Logistik',
        icon: '📦',
        responsibilities: [
            'Bertanggung jawab atas seluruh proses pengadaan material, furniture, dan perlengkapan proyek sesuai kebutuhan desain, anggaran, dan jadwal',
            'Bertanggung jawab atas penyimpanan, pengiriman, dan distribusi material ke lokasi proyek',
        ]
    },
    {
        title: 'Pengawas',
        icon: '👁️',
        responsibilities: [
            'Mengawasi pelaksanaan pekerjaan di lapangan dan memastikan pekerjaan sesuai gambar kerja',
            'Bertanggung jawab jawab terhadap Proses pengerjaan',
            'Bertanggung jawab terhadap Quality Control',
            'Membuat laporan pengawasan proyek',
        ]
    },
    {
        title: 'Administrasi',
        icon: '📋',
        responsibilities: [
            'Administrasi Dokumen Proyek Mengelola dan mengarip dokumentasi proyek interior',
            'Menyusun dan menyimpan kontrak kerja, SPK, dan addendum',
        ]
    },
])

const gallery = computed(() => isEnglish.value ? [
    { title: 'Desk Transformation', desc: 'Modern marble finish upgrade', category: 'Before & After', image: '/img/tubel1.jpg' },
    { title: 'Lounge Makeover', desc: 'Complete seating area renovation', category: 'Before & After', image: '/img/interior.jpg' },
    { title: 'Office Layout Concept', desc: '3D spatial planning', category: 'Design Concept', image: '/img/interior-design.jpg' },
    { title: 'Interior Details Concept', desc: '3D rendering of meeting area', category: 'Design Concept', image: '/img/section-two.jpg' }
] : [
    { title: 'Transformasi Meja', desc: 'Pembaruan dengan finishing marmer modern', category: 'Before & After', image: '/img/tubel1.jpg' },
    { title: 'Renovasi Ruang Tunggu', desc: 'Renovasi total area tempat duduk', category: 'Before & After', image: '/img/interior.jpg' },
    { title: 'Konsep Tata Letak Kantor', desc: 'Perencanaan ruang 3D', category: 'Konsep Desain', image: '/img/interior-design.jpg' },
    { title: 'Konsep Detail Interior', desc: 'Render 3D area pertemuan', category: 'Konsep Desain', image: '/img/section-two.jpg' }
])
</script>

<template>
    <main class="relative bg-white font-montserrat overflow-hidden">
        <div class="absolute inset-0 bg-[linear-gradient(to_right,#f1f5f9_1px,transparent_1px),linear-gradient(to_bottom,#f1f5f9_1px,transparent_1px)] bg-[size:4rem_4rem] [mask-image:radial-gradient(ellipse_80%_50%_at_50%_0%,#000_70%,transparent_100%)] -z-10 pointer-events-none"></div>

        <section class="pt-36 pb-20 relative">
            <div class="absolute -top-28 -right-24 w-[420px] h-[420px] rounded-full bg-[#ED0226]/10 blur-[110px] pointer-events-none"></div>
            <div class="absolute top-40 -left-24 w-[360px] h-[360px] rounded-full bg-[#FF6A00]/10 blur-[100px] pointer-events-none"></div>

            <div class="max-w-7xl mx-auto px-4">
                <div class="grid lg:grid-cols-[1.1fr,0.9fr] gap-14 items-center">
                    <div>
                        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-red-50 border border-red-100 text-[#ED0226] text-xs font-bold uppercase tracking-[0.2em] mb-6">
                            <span class="h-2 w-2 rounded-full bg-[#FF6A00]"></span>
                            {{ copy.badge }}
                        </div>

                        <h1 class="text-4xl lg:text-6xl font-black text-slate-950 leading-tight mb-6">
                            {{ copy.title }}
                        </h1>

                        <p class="text-slate-600 text-lg leading-relaxed max-w-2xl mb-8">
                            {{ copy.desc }}
                        </p>

                        <div class="flex flex-wrap gap-4">
                            <router-link
                                :to="isEnglish ? '/en/contact' : '/id/kontak'"
                                class="bg-[#ED0226] hover:bg-[#c9001f] text-white px-7 py-3.5 rounded-xl font-bold shadow-lg shadow-red-600/25 transition-all active:scale-95"
                            >
                                {{ copy.primary }}
                            </router-link>
                            <a
                                href="#process"
                                class="bg-white border border-slate-200 hover:border-[#FF6A00] text-slate-900 hover:text-[#ED0226] px-7 py-3.5 rounded-xl font-bold shadow-sm transition-all active:scale-95"
                            >
                                {{ copy.secondary }}
                            </a>
                        </div>
                    </div>

                    <div class="relative">
                        <div class="bg-slate-950 rounded-[2rem] p-6 md:p-8 shadow-2xl shadow-slate-900/20 overflow-hidden">
                            <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-[#ED0226] to-[#FF6A00]"></div>
                            <div class="space-y-4">
                                <div v-for="(step, index) in steps" :key="step" class="flex items-center gap-4 bg-white/8 border border-white/10 rounded-2xl p-4">
                                    <div class="h-12 w-12 rounded-xl bg-gradient-to-br from-[#ED0226] to-[#FF6A00] text-white flex items-center justify-center font-black">
                                        {{ index + 1 }}
                                    </div>
                                    <div>
                                        <p class="text-white font-bold">{{ step }}</p>
                                        <p class="text-white/55 text-xs mt-1">{{ isEnglish ? 'Structured implementation checkpoint' : 'Checkpoint implementasi terstruktur' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- WORK SCOPES SECTION -->
        <section class="py-20">
            <div class="max-w-7xl mx-auto px-4">
                <div class="max-w-2xl mb-12">
                    <h2 class="text-3xl lg:text-4xl font-black text-slate-950 mb-4">{{ isEnglish ? 'Scope of Work' : 'Ruang Lingkup Pekerjaan' }}</h2>
                    <p class="text-slate-600 leading-relaxed">{{ isEnglish ? 'Clear responsibilities across teams for successful implementation execution' : 'Tanggung jawab yang jelas di setiap tim untuk eksekusi implementasi yang berhasil' }}</p>
                </div>

                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div v-for="scope in workScopes" :key="scope.title" class="bg-gradient-to-br from-slate-900 to-slate-800 border border-slate-700/50 rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all text-white">
                        <h3 class="text-xl font-black mb-4 text-white">{{ scope.title }}</h3>
                        <ul class="space-y-2.5">
                            <li v-for="(item, idx) in scope.responsibilities" :key="idx" class="flex gap-3 text-sm leading-relaxed">
                                <span class="text-[#FF6A00] font-bold flex-shrink-0 mt-1">✓</span>
                                <span class="text-slate-100">{{ item }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <!-- GALLERY SECTION -->
        <section class="py-24 bg-slate-50 border-t border-slate-200">
            <div class="max-w-7xl mx-auto px-4">
                <div class="text-center max-w-2xl mx-auto mb-16">
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-red-50 border border-red-100 text-[#ED0226] text-xs font-bold uppercase tracking-[0.2em] mb-4">
                        {{ isEnglish ? 'Portfolio' : 'Portofolio' }}
                    </div>
                    <h2 class="text-3xl lg:text-5xl font-black text-slate-950 mb-6">{{ isEnglish ? 'Our Recent Work' : 'Hasil Pekerjaan Kami' }}</h2>
                    <p class="text-slate-600 text-lg leading-relaxed">{{ isEnglish ? 'From 3D design concepts to real-world before and after transformations.' : 'Dari konsep desain 3D hingga transformasi nyata sebelum dan sesudah pengerjaan.' }}</p>
                </div>

                <div class="grid md:grid-cols-2 gap-8">
                    <!-- Gallery Item -->
                    <div v-for="(item, idx) in gallery" :key="idx" class="group relative rounded-3xl overflow-hidden shadow-lg shadow-slate-200/50 bg-white border border-slate-100 hover:shadow-2xl transition-all duration-500 hover:-translate-y-2">
                        <div class="aspect-[4/3] relative overflow-hidden bg-slate-200">
                            <!-- Image -->
                            <img :src="item.image" :alt="item.title" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" />
                            
                            <!-- Gradient Overlay -->
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/90 via-slate-900/30 to-transparent opacity-80 group-hover:opacity-100 transition-opacity duration-500"></div>
                            
                            <!-- Badge -->
                            <div class="absolute top-6 left-6 z-10">
                                <div class="px-4 py-2 rounded-xl bg-white/95 backdrop-blur shadow-sm text-[#ED0226] text-xs font-black uppercase tracking-wider">
                                    {{ item.category }}
                                </div>
                            </div>

                            <!-- Text Content -->
                            <div class="absolute bottom-0 left-0 right-0 p-8 translate-y-4 group-hover:translate-y-0 transition-transform duration-500 z-10">
                                <h3 class="text-2xl font-black text-white mb-2">{{ item.title }}</h3>
                                <p class="text-slate-300 text-sm font-medium leading-relaxed">{{ item.desc }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </main>
</template>
