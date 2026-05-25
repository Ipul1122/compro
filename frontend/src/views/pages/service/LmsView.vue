<script setup>
import { ref, computed } from 'vue'

const activeTab = ref('features')
const courseExpanded = ref(null)
</script>

<template>
    <main class="relative bg-white font-montserrat z-0 overflow-x-hidden">

        <!-- Animated Background -->
        <div class="fixed inset-0 -z-20 overflow-hidden pointer-events-none">
            <div class="absolute top-0 left-1/4 w-96 h-96 bg-red-100/30 rounded-full mix-blend-multiply filter blur-3xl animate-blob"></div>
            <div class="absolute top-0 right-1/4 w-96 h-96 bg-amber-100/30 rounded-full mix-blend-multiply filter blur-3xl animate-blob animation-delay-2000"></div>
            <div class="absolute -bottom-8 left-1/2 w-96 h-96 bg-red-100/20 rounded-full mix-blend-multiply filter blur-3xl animate-blob animation-delay-4000"></div>
        </div>

        <!-- HERO SECTION -->
        <section class="relative pt-32 pb-16 md:pt-48 md:pb-24 overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">
                    <!-- Left: Content -->
                    <div class="space-y-8">
                        <!-- Badge -->
                        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-red-50/80 backdrop-blur-sm border border-red-100/50 text-red-700 text-xs font-semibold uppercase tracking-wider hover:bg-red-50 transition-colors">
                            <span class="flex h-2.5 w-2.5 relative">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-500 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-red-600"></span>
                            </span>
                            Platform Terdepan
                        </div>

                        <!-- Main Headline -->
                        <div class="space-y-4">
                            <h1 class="text-5xl md:text-6xl lg:text-7xl font-black text-slate-900 leading-tight tracking-tight">
                                Learning &<br />
                                <span class="bg-gradient-to-r from-red-600 via-red-500 to-amber-600 bg-clip-text text-transparent">Development</span>
                            </h1>
                            <p class="text-xl md:text-2xl text-slate-600 font-light leading-relaxed max-w-2xl">
                                Platform LMS modern yang memberdayakan organisasi Anda untuk mengelola dan mengoptimalkan pembelajaran karyawan dengan cara yang terukur dan berkelanjutan.
                            </p>
                        </div>

                        <!-- Quick Features -->
                        <div class="flex flex-wrap gap-3">
                            <span class="px-4 py-2 bg-white/60 backdrop-blur-sm border border-slate-200/60 rounded-lg text-sm font-medium text-slate-700 hover:bg-white/80 transition-colors">
                                ✨ Intuitif & Modern
                            </span>
                            <span class="px-4 py-2 bg-white/60 backdrop-blur-sm border border-slate-200/60 rounded-lg text-sm font-medium text-slate-700 hover:bg-white/80 transition-colors">
                                📊 Analytics Real-time
                            </span>
                            <span class="px-4 py-2 bg-white/60 backdrop-blur-sm border border-slate-200/60 rounded-lg text-sm font-medium text-slate-700 hover:bg-white/80 transition-colors">
                                🔒 Aman & Terpercaya
                            </span>
                        </div>

                        <!-- CTA Buttons -->
                        <div class="flex flex-wrap gap-4 pt-4">
                            <a href="#features"
                                class="group relative px-8 py-4 bg-gradient-to-r from-red-600 to-red-700 text-white font-bold rounded-xl shadow-lg shadow-red-600/30 hover:shadow-red-600/50 transition-all duration-300 hover:-translate-y-0.5 active:scale-95 flex items-center gap-2 overflow-hidden">
                                <span class="relative z-10">Jelajahi Fitur</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-hover:translate-y-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                                </svg>
                            </a>
                            <a href="#contact-lms"
                                class="px-8 py-4 bg-white/80 backdrop-blur-sm border border-slate-300/60 text-slate-900 font-bold rounded-xl hover:bg-white hover:border-slate-400 transition-all duration-300 hover:shadow-lg active:scale-95">
                                Konsultasi Gratis
                            </a>
                        </div>

                        <!-- Trust Indicators -->
                        <div class="grid grid-cols-3 gap-6 pt-8 border-t border-slate-200/40">
                            <div class="text-left">
                                <div class="text-3xl font-black text-red-600">500+</div>
                                <p class="text-sm text-slate-600 font-medium mt-1">Pengguna Aktif</p>
                            </div>
                            <div class="text-left">
                                <div class="text-3xl font-black text-red-600">200+</div>
                                <p class="text-sm text-slate-600 font-medium mt-1">Modul Pelatihan</p>
                            </div>
                            <div class="text-left">
                                <div class="text-3xl font-black text-green-600">98%</div>
                                <p class="text-sm text-slate-600 font-medium mt-1">Kepuasan Pengguna</p>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Visual Hero -->
                    <div class="relative hidden lg:block">
                        <!-- Main Card -->
                        <div class="relative">
                            <div class="absolute -inset-0.5 bg-gradient-to-r from-red-600 to-amber-600 rounded-3xl blur opacity-20"></div>
                            <div class="relative bg-white/95 backdrop-blur-xl rounded-3xl p-8 shadow-2xl border border-white/80">
                                <!-- Dashboard Preview -->
                                <div class="space-y-6">
                                    <!-- Header -->
                                    <div class="flex items-center justify-between">
                                        <h3 class="text-lg font-bold text-slate-900">Dashboard Pelatihan</h3>
                                        <div class="flex gap-2">
                                            <div class="w-3 h-3 rounded-full bg-red-500"></div>
                                            <div class="w-3 h-3 rounded-full bg-amber-500"></div>
                                            <div class="w-3 h-3 rounded-full bg-red-400"></div>
                                        </div>
                                    </div>

                                    <!-- Course Progress -->
                                    <div v-for="(course, i) in courses" :key="i" class="space-y-2">
                                        <div class="flex justify-between items-center">
                                            <span class="text-sm font-semibold text-slate-700">{{ course.name }}</span>
                                            <span class="text-xs font-bold text-red-600">{{ course.progress }}%</span>
                                        </div>
                                        <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
                                            <div class="bg-gradient-to-r from-red-500 to-red-600 h-full rounded-full transition-all duration-1000" :style="`width: ${course.progress}%`"></div>
                                        </div>
                                    </div>

                                    <div class="h-px bg-slate-200 my-4"></div>

                                    <!-- Stats Grid -->
                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="bg-gradient-to-br from-red-50 to-red-100/50 rounded-xl p-4 border border-red-200/30">
                                            <div class="text-2xl font-bold text-red-600">47</div>
                                            <p class="text-xs text-slate-600 font-medium mt-1">Kursus Aktif</p>
                                        </div>
                                        <div class="bg-gradient-to-br from-amber-50 to-amber-100/50 rounded-xl p-4 border border-amber-200/30">
                                            <div class="text-2xl font-bold text-amber-600">12</div>
                                            <p class="text-xs text-slate-600 font-medium mt-1">Sertifikat Terbit</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Floating Badges -->
                        <div class="absolute -top-6 -right-6 bg-white rounded-2xl shadow-xl p-4 border border-slate-200/50 backdrop-blur-xl animate-float">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-slate-800">Sertifikasi</p>
                                    <p class="text-[11px] text-slate-500">Terakreditasi Resmi</p>
                                </div>
                            </div>
                        </div>

                        <div class="absolute -bottom-6 -left-6 bg-white rounded-2xl shadow-xl p-4 border border-slate-200/50 backdrop-blur-xl animate-float animation-delay-1000">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-red-100 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-slate-800">User-Friendly</p>
                                    <p class="text-[11px] text-slate-500">Mudah Digunakan</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- FEATURES SECTION -->
        <section id="features" class="relative py-24 md:py-32">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Section Header -->
                <div class="max-w-3xl mx-auto text-center mb-16 md:mb-20">
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-red-50/80 backdrop-blur-sm border border-red-100/50 text-red-700 text-xs font-semibold uppercase tracking-wider mb-6">
                        Fitur & Kemampuan
                    </div>
                    <h2 class="text-4xl md:text-5xl lg:text-6xl font-black text-slate-900 mb-6 leading-tight">
                        Solusi Lengkap untuk <span class="text-transparent bg-clip-text bg-gradient-to-r from-red-600 to-amber-600">Manajemen Pembelajaran</span>
                    </h2>
                    <p class="text-lg md:text-xl text-slate-600 leading-relaxed">
                        Semua tools yang Anda butuhkan untuk merancang, melaksanakan, dan mengukur keberhasilan program pembelajaran korporat.
                    </p>
                </div>

                <!-- Features Grid -->
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
                    <div v-for="(feature, idx) in features" :key="idx"
                        class="group relative">
                        <div class="absolute -inset-1 bg-gradient-to-r from-red-600/10 to-amber-600/10 rounded-2xl blur-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <div class="relative bg-white/60 backdrop-blur-xl border border-white/80 rounded-2xl p-8 hover:bg-white/80 transition-all duration-300 h-full">
                            <!-- Icon -->
                            <div :class="`w-14 h-14 rounded-xl ${feature.bgColor} flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300`">
                                <svg xmlns="http://www.w3.org/2000/svg" :class="`h-7 w-7 ${feature.iconColor}`" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="feature.icon" />
                                </svg>
                            </div>
                            
                            <!-- Content -->
                            <h3 class="text-xl font-bold text-slate-900 mb-3">{{ feature.title }}</h3>
                            <p class="text-slate-600 text-sm leading-relaxed mb-4">{{ feature.desc }}</p>
                            
                            <!-- Check marks -->
                            <div class="space-y-2 text-sm text-slate-700">
                                <div v-for="(benefit, i) in feature.benefits" :key="i" class="flex items-start gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-green-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                    <span class="font-medium">{{ benefit }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- HOW IT WORKS SECTION -->
        <section class="relative py-24 md:py-32 bg-gradient-to-br from-slate-900/5 via-white to-slate-900/5 border-t border-slate-200/40">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="max-w-3xl mx-auto text-center mb-16 md:mb-20">
                    <h2 class="text-4xl md:text-5xl font-black text-slate-900 mb-4 leading-tight">
                        Implementasi yang Mudah
                    </h2>
                    <p class="text-lg text-slate-600">
                        Dari konsultasi hingga go-live dalam waktu singkat dengan dukungan penuh dari tim ahli kami.
                    </p>
                </div>

                <!-- Timeline/Steps -->
                <div class="grid md:grid-cols-4 gap-6 md:gap-8 relative">
                    <!-- Connection Line -->
                    <div class="hidden md:block absolute top-20 left-[12%] right-[12%] h-0.5 bg-gradient-to-r from-transparent via-red-400 to-transparent"></div>

                    <div v-for="(step, idx) in steps" :key="idx" class="text-center relative z-10">
                        <!-- Number Circle -->
                        <div class="relative mb-6 flex justify-center">
                            <div class="absolute inset-0 bg-gradient-to-r from-red-600/20 to-amber-600/20 rounded-full blur-lg"></div>
                            <div class="relative w-24 h-24 rounded-full bg-gradient-to-br from-red-600 to-amber-600 flex items-center justify-center shadow-lg shadow-red-600/30">
                                <span class="text-white font-black text-3xl">{{ idx + 1 }}</span>
                            </div>
                        </div>

                        <!-- Step Info -->
                        <h3 class="text-xl font-bold text-slate-900 mb-2">{{ step.title }}</h3>
                        <p class="text-slate-600 text-sm leading-relaxed">{{ step.desc }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CORE BENEFITS SECTION -->
        <section class="relative py-24 md:py-32">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">
                    <!-- Left: Benefits List -->
                    <div>
                        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-red-50/80 backdrop-blur-sm border border-red-100/50 text-red-700 text-xs font-semibold uppercase tracking-wider mb-6">
                            Manfaat Utama
                        </div>
                        
                        <h2 class="text-4xl md:text-5xl font-black text-slate-900 mb-8 leading-tight">
                            Tingkatkan Produktivitas & ROI Pelatihan
                        </h2>

                        <div class="space-y-6">
                            <div v-for="(benefit, idx) in corebenefits" :key="idx" class="group">
                                <div class="flex gap-4">
                                    <div class="flex-shrink-0">
                                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-red-100 group-hover:bg-red-200 transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="benefit.icon" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-slate-900 mb-1">{{ benefit.title }}</h3>
                                        <p class="text-slate-600 text-sm">{{ benefit.desc }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Statistics/Visual -->
                    <div class="relative">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-gradient-to-br from-red-50 to-red-100/50 rounded-2xl p-6 border border-red-200/30 hover:shadow-lg transition-shadow">
                                <div class="text-4xl font-black text-red-600 mb-2">3x</div>
                                <p class="text-sm font-medium text-slate-700">Peningkatan Retensi Pengetahuan</p>
                            </div>
                            <div class="bg-gradient-to-br from-amber-50 to-amber-100/50 rounded-2xl p-6 border border-amber-200/30 hover:shadow-lg transition-shadow mt-4">
                                <div class="text-4xl font-black text-amber-600 mb-2">2x</div>
                                <p class="text-sm font-medium text-slate-700">Efisiensi Waktu Pelatihan</p>
                            </div>
                            <div class="bg-gradient-to-br from-emerald-50 to-emerald-100/50 rounded-2xl p-6 border border-emerald-200/30 hover:shadow-lg transition-shadow">
                                <div class="text-4xl font-black text-emerald-600 mb-2">45%</div>
                                <p class="text-sm font-medium text-slate-700">Pengurangan Biaya Pelatihan</p>
                            </div>
                            <div class="bg-gradient-to-br from-purple-50 to-purple-100/50 rounded-2xl p-6 border border-purple-200/30 hover:shadow-lg transition-shadow">
                                <div class="text-4xl font-black text-purple-600 mb-2">95%</div>
                                <p class="text-sm font-medium text-slate-700">Tingkat Penyelesaian Kursus</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA SECTION -->
        <section id="contact-lms" class="relative py-24 md:py-32 overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-r from-red-600/10 via-amber-600/10 to-red-600/10 -z-10"></div>
            
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-red-50/80 backdrop-blur-sm border border-red-100/50 text-red-700 text-xs font-semibold uppercase tracking-wider mb-6">
                    Mulai Sekarang
                </div>

                <h2 class="text-4xl md:text-5xl lg:text-6xl font-black text-slate-900 mb-6 leading-tight">
                    Siap Transformasi Program Pembelajaran Anda?
                </h2>

                <p class="text-xl text-slate-600 mb-10 leading-relaxed max-w-2xl mx-auto">
                    Konsultasikan kebutuhan LMS Anda dengan tim expert kami dan dapatkan roadmap implementasi yang terukur untuk organisasi Anda.
                </p>

                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="https://wa.me/6285284201428?text=Halo%2C%20saya%20ingin%20konsultasi%20tentang%20LMS%20dari%20PT%20Cakrawala%20Parama%20Internasional."
                        target="_blank"
                        class="group relative inline-flex items-center justify-center gap-2 px-8 py-4 bg-gradient-to-r from-green-500 to-green-600 text-white font-bold rounded-xl shadow-lg shadow-green-500/30 hover:shadow-green-500/50 hover:-translate-y-0.5 transition-all duration-300 active:scale-95 overflow-hidden">
                        <span class="relative z-10 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                            Hubungi via WhatsApp
                        </span>
                    </a>
                    <a href="mailto:info@cakrawala-internasional.co.id?subject=Konsultasi%20LMS"
                        class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-white border border-slate-300/60 text-slate-900 font-bold rounded-xl hover:bg-slate-50 hover:border-slate-400 transition-all duration-300 active:scale-95 shadow-md hover:shadow-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                        Kirim Email
                    </a>
                </div>

                <p class="text-sm text-slate-500 mt-8">
                    ✓ Konsultasi gratis tanpa komitmen &nbsp; • &nbsp; ✓ Tim berpengalaman siap membantu &nbsp; • &nbsp; ✓ Respon cepat 24 jam
                </p>
            </div>
        </section>

    </main>
</template>

<script>
export default {
    data() {
        return {
            courses: [
                { name: 'Kepemimpinan & Manajemen', progress: 82 },
                { name: 'Keterampilan Komunikasi', progress: 67 },
                { name: 'Kepatuhan & Regulasi', progress: 45 },
            ],
            features: [
                {
                    title: 'Manajemen Kursus Intuitif',
                    desc: 'Buat dan kelola modul pelatihan dengan antarmuka drag-and-drop yang mudah digunakan oleh siapa saja.',
                    icon: 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253',
                    bgColor: 'bg-red-100', 
                    iconColor: 'text-red-600',
                    benefits: ['Template siap pakai', 'Customizable sepenuhnya', 'Multi-format konten']
                },
                {
                    title: 'Analytics & Reporting Real-time',
                    desc: 'Pantau progress peserta dengan dashboard yang komprehensif dan laporan terperinci untuk keputusan berbasis data.',
                    icon: 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
                    bgColor: 'bg-amber-100', 
                    iconColor: 'text-amber-600',
                    benefits: ['Dashboard real-time', 'Export laporan lengkap', 'Insights mendalam']
                },
                {
                    title: 'Ujian & Assessment Online',
                    desc: 'Buat penilaian interaktif dengan berbagai tipe soal untuk mengukur pemahaman peserta secara akurat.',
                    icon: 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4',
                    bgColor: 'bg-red-100', 
                    iconColor: 'text-red-600',
                    benefits: ['Beragam tipe soal', 'Penilaian otomatis', 'Bank soal unlimited']
                },
                {
                    title: 'Sertifikasi Digital Otomatis',
                    desc: 'Terbitkan sertifikat elektronik dengan watermark keamanan saat peserta menyelesaikan pelatihan.',
                    icon: 'M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z',
                    bgColor: 'bg-amber-100', 
                    iconColor: 'text-amber-600',
                    benefits: ['Sertifikat profesi', 'Verifikasi online', 'Arsip digital']
                },
                {
                    title: 'Mobile-First & Responsive',
                    desc: 'Akses pembelajaran di smartphone, tablet, atau desktop dengan pengalaman pengguna yang sempurna.',
                    icon: 'M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z',
                    bgColor: 'bg-red-100', 
                    iconColor: 'text-red-600',
                    benefits: ['Akses offline', 'Sinkronisasi otomatis', 'UI modern']
                },
                {
                    title: 'Integrasi HR & Data Sync',
                    desc: 'Terhubung seamless dengan sistem HR Anda untuk sinkronisasi data karyawan otomatis dan pelaporan terintegrasi.',
                    icon: 'M13 10V3L4 14h7v7l9-11h-7z',
                    bgColor: 'bg-amber-100', 
                    iconColor: 'text-amber-600',
                    benefits: ['API terintegrasi', 'Sinkronisasi real-time', 'SSO single sign-on']
                },
            ],
            steps: [
                { title: 'Konsultasi & Analisis', desc: 'Tim kami menganalisis kebutuhan, struktur organisasi, dan target pembelajaran Anda secara detail.' },
                { title: 'Konfigurasi Platform', desc: 'Setup sistem, customisasi branding, import data karyawan, dan rancang struktur pembelajaran.' },
                { title: 'Training & Onboarding', desc: 'Pelatihan komprehensif untuk admin, trainer, dan end-users dengan dokumentasi lengkap.' },
                { title: 'Monitoring & Optimasi', desc: 'Pantau KPI pembelajaran dan dapatkan laporan berkala untuk continuous improvement.' },
            ],
            corebenefits: [
                {
                    title: 'Efisiensi Waktu',
                    desc: 'Otomatisasi proses pembelajaran mengurangi beban administratif hingga 60% dan membebaskan tim HR untuk strategi.',
                    icon: 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'
                },
                {
                    title: 'Tracking Terukur',
                    desc: 'Pantau setiap aspek pembelajaran dengan metrik detail - completion rate, time spent, assessment scores, dan lebih.',
                    icon: 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'
                },
                {
                    title: 'Engagement Tinggi',
                    desc: 'Konten interaktif, gamification, dan mobile-first design meningkatkan partisipasi peserta hingga 85%.',
                    icon: 'M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m2 1v2.5M14 4l-2 1m2-1l-2-1m2 1v2.5'
                },
                {
                    title: 'ROI Terbukti',
                    desc: 'Kurangi biaya pelatihan hingga 45%, capai ROI positif dalam 6-8 bulan dengan hasil pembelajaran terukur.',
                    icon: 'M13 10V3L4 14h7v7l9-11h-7z'
                },
            ]
        }
    }
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;900&display=swap');

.font-montserrat { 
    font-family: 'Montserrat', sans-serif; 
}

@keyframes blob {
    0%, 100% { transform: translate(0, 0) scale(1); }
    33% { transform: translate(30px, -50px) scale(1.1); }
    66% { transform: translate(-20px, 20px) scale(0.9); }
}

@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-20px); }
}

.animate-blob {
    animation: blob 7s infinite;
}

.animation-delay-2000 {
    animation-delay: 2s;
}

.animation-delay-4000 {
    animation-delay: 4s;
}

.animate-float {
    animation: float 6s ease-in-out infinite;
}

.animation-delay-1000 {
    animation-delay: 1s;
}
</style>
