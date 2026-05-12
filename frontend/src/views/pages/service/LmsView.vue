<script setup>
</script>

<template>
    <main class="relative bg-white font-montserrat z-0 overflow-x-hidden">

        <!-- Background Grid -->
        <div class="absolute inset-0 bg-[linear-gradient(to_right,#f1f5f9_1px,transparent_1px),linear-gradient(to_bottom,#f1f5f9_1px,transparent_1px)] bg-[size:4rem_4rem] [mask-image:radial-gradient(ellipse_80%_50%_at_50%_0%,#000_70%,transparent_100%)] -z-10 pointer-events-none"></div>

        <!-- HERO SECTION -->
        <section class="pt-36 pb-20 relative overflow-hidden">
            <div class="absolute -top-32 -left-32 w-[600px] h-[600px] rounded-full bg-blue-500/5 blur-[120px] pointer-events-none"></div>
            <div class="absolute -bottom-32 -right-32 w-[500px] h-[500px] rounded-full bg-indigo-500/5 blur-[100px] pointer-events-none"></div>

            <div class="max-w-7xl mx-auto px-4">
                <div class="grid lg:grid-cols-2 gap-16 items-center">
                    <!-- Kiri: Teks -->
                    <div>
                        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-blue-50 border border-blue-100 text-blue-600 text-xs font-bold uppercase tracking-[0.2em] mb-6">
                            <span class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-500 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-600"></span>
                            </span>
                            Layanan Unggulan
                        </div>

                        <h1 class="text-4xl lg:text-5xl font-black text-slate-900 leading-tight mb-6">
                            Learning <br />
                            <span class="bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">Management</span><br />
                            System
                        </h1>

                        <p class="text-slate-600 text-lg leading-relaxed mb-8">
                            Platform e-learning terintegrasi yang dirancang khusus untuk mendukung pengembangan SDM organisasi Anda secara terstruktur, terukur, dan berkelanjutan — di mana saja, kapan saja.
                        </p>

                        <div class="flex flex-wrap gap-4">
                            <a href="#features"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-7 py-3.5 rounded-xl font-bold shadow-lg shadow-blue-600/25 hover:shadow-blue-600/40 transition-all active:scale-95 flex items-center gap-2">
                                Lihat Fitur
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                            </a>
                            <a href="#contact-lms"
                                class="bg-white border border-slate-200 hover:border-blue-400 text-slate-800 hover:text-blue-700 px-7 py-3.5 rounded-xl font-bold shadow-sm hover:shadow-md transition-all active:scale-95">
                                Konsultasi Gratis
                            </a>
                        </div>

                        <!-- Stats -->
                        <div class="mt-12 grid grid-cols-3 gap-6">
                            <div class="text-center">
                                <div class="text-3xl font-black text-blue-600">500+</div>
                                <div class="text-xs text-slate-500 font-semibold mt-1">Pengguna Aktif</div>
                            </div>
                            <div class="text-center border-x border-slate-100">
                                <div class="text-3xl font-black text-blue-600">200+</div>
                                <div class="text-xs text-slate-500 font-semibold mt-1">Modul Pelatihan</div>
                            </div>
                            <div class="text-center">
                                <div class="text-3xl font-black text-blue-600">98%</div>
                                <div class="text-xs text-slate-500 font-semibold mt-1">Tingkat Kepuasan</div>
                            </div>
                        </div>
                    </div>

                    <!-- Kanan: Visual Card -->
                    <div class="relative">
                        <div class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-3xl p-8 shadow-2xl shadow-blue-600/20">
                            <!-- Dashboard Mock -->
                            <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-5 mb-4 border border-white/20">
                                <div class="flex items-center gap-3 mb-4">
                                    <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                                    </div>
                                    <div>
                                        <div class="text-white font-bold text-sm">Dashboard Pelatihan</div>
                                        <div class="text-blue-200 text-xs">Progress karyawan real-time</div>
                                    </div>
                                </div>
                                <!-- Progress Bars -->
                                <div class="space-y-3">
                                    <div v-for="item in courses" :key="item.name">
                                        <div class="flex justify-between text-xs text-blue-100 mb-1">
                                            <span>{{ item.name }}</span>
                                            <span>{{ item.progress }}%</span>
                                        </div>
                                        <div class="w-full bg-white/10 rounded-full h-2">
                                            <div class="bg-white rounded-full h-2 transition-all duration-1000" :style="`width: ${item.progress}%`"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Bottom Cards -->
                            <div class="grid grid-cols-2 gap-3">
                                <div class="bg-white/10 rounded-xl p-4 border border-white/20 text-center">
                                    <div class="text-2xl font-black text-white">47</div>
                                    <div class="text-blue-200 text-xs mt-1">Kursus Aktif</div>
                                </div>
                                <div class="bg-white/10 rounded-xl p-4 border border-white/20 text-center">
                                    <div class="text-2xl font-black text-white">12</div>
                                    <div class="text-blue-200 text-xs mt-1">Sertifikat Dikeluarkan</div>
                                </div>
                            </div>
                        </div>

                        <!-- Floating badges -->
                        <div class="absolute -top-4 -right-4 bg-white rounded-2xl shadow-xl p-3 flex items-center gap-2 border border-slate-100">
                            <div class="w-8 h-8 rounded-lg bg-green-50 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            </div>
                            <div>
                                <div class="text-xs font-bold text-slate-800">Sertifikasi Resmi</div>
                                <div class="text-[10px] text-slate-500">Terakreditasi</div>
                            </div>
                        </div>

                        <div class="absolute -bottom-4 -left-4 bg-white rounded-2xl shadow-xl p-3 flex items-center gap-2 border border-slate-100">
                            <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.069A1 1 0 0121 8.87v6.26a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                            </div>
                            <div>
                                <div class="text-xs font-bold text-slate-800">Video Interaktif</div>
                                <div class="text-[10px] text-slate-500">HD Quality</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- FEATURES SECTION -->
        <section id="features" class="py-24 bg-slate-50 border-y border-slate-100">
            <div class="max-w-7xl mx-auto px-4">
                <div class="text-center mb-16">
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-blue-50 border border-blue-100 text-blue-600 text-xs font-bold uppercase tracking-[0.2em] mb-4">
                        Fitur Platform
                    </div>
                    <h2 class="text-3xl lg:text-4xl font-black text-slate-900 mb-4">
                        Semua yang Anda Butuhkan <br />
                        <span class="text-blue-600">dalam Satu Platform</span>
                    </h2>
                    <p class="text-slate-500 max-w-xl mx-auto">Solusi LMS komprehensif yang mempermudah pengelolaan pelatihan karyawan dari awal hingga akhir.</p>
                </div>

                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div v-for="feature in features" :key="feature.title"
                        class="bg-white rounded-2xl p-6 shadow-sm hover:shadow-lg border border-slate-100 hover:border-blue-100 transition-all duration-300 group hover:-translate-y-1">
                        <div :class="`w-12 h-12 rounded-xl ${feature.bg} flex items-center justify-center mb-5 group-hover:scale-110 transition-transform`">
                            <svg xmlns="http://www.w3.org/2000/svg" :class="`h-6 w-6 ${feature.color}`" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="feature.icon" />
                            </svg>
                        </div>
                        <h3 class="font-black text-slate-900 text-lg mb-2">{{ feature.title }}</h3>
                        <p class="text-slate-500 text-sm leading-relaxed">{{ feature.desc }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- HOW IT WORKS -->
        <section class="py-24">
            <div class="max-w-7xl mx-auto px-4">
                <div class="text-center mb-16">
                    <h2 class="text-3xl lg:text-4xl font-black text-slate-900 mb-4">Cara Kerja Platform</h2>
                    <p class="text-slate-500 max-w-lg mx-auto">Implementasi mudah, hasil terukur. Mulai dalam hitungan hari, bukan bulan.</p>
                </div>

                <div class="grid md:grid-cols-4 gap-8 relative">
                    <!-- Connector line -->
                    <div class="hidden md:block absolute top-10 left-[12%] right-[12%] h-0.5 bg-gradient-to-r from-blue-200 via-blue-400 to-indigo-200"></div>

                    <div v-for="(step, i) in steps" :key="step.title" class="text-center relative">
                        <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center mx-auto mb-5 shadow-lg shadow-blue-500/25 relative z-10">
                            <span class="text-white font-black text-2xl">{{ i + 1 }}</span>
                        </div>
                        <h3 class="font-black text-slate-900 mb-2">{{ step.title }}</h3>
                        <p class="text-slate-500 text-sm leading-relaxed">{{ step.desc }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA SECTION -->
        <section id="contact-lms" class="py-24 bg-gradient-to-br from-blue-600 to-indigo-700 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-96 h-96 rounded-full bg-white/5 blur-[80px]"></div>
            <div class="absolute bottom-0 left-0 w-64 h-64 rounded-full bg-indigo-400/10 blur-[60px]"></div>

            <div class="max-w-3xl mx-auto px-4 text-center relative z-10">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/10 border border-white/20 text-white text-xs font-bold uppercase tracking-[0.2em] mb-6">
                    Mulai Sekarang
                </div>
                <h2 class="text-3xl lg:text-4xl font-black text-white mb-6 leading-tight">
                    Siap Transformasi <br />Pelatihan SDM Anda?
                </h2>
                <p class="text-blue-100 text-lg mb-10 leading-relaxed">
                    Hubungi tim kami untuk demo gratis dan konsultasi kebutuhan LMS yang sesuai dengan organisasi Anda.
                </p>
                <div class="flex flex-wrap justify-center gap-4">
                    <a href="https://wa.me/6281234567890?text=Halo%2C%20saya%20ingin%20konsultasi%20tentang%20LMS%20dari%20PT%20Cakrawala%20Parama%20Internasional."
                        target="_blank"
                        class="bg-white text-blue-700 hover:bg-blue-50 px-8 py-4 rounded-xl font-black shadow-xl hover:shadow-2xl transition-all active:scale-95 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                        Hubungi via WhatsApp
                    </a>
                    <a href="mailto:info@cakrawala-internasional.co.id?subject=Konsultasi%20LMS"
                        class="bg-white/10 backdrop-blur-sm border border-white/30 hover:bg-white/20 text-white px-8 py-4 rounded-xl font-bold transition-all active:scale-95 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                        Kirim Email
                    </a>
                </div>
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
                    title: 'Manajemen Kursus',
                    desc: 'Buat, atur, dan kelola modul pelatihan dengan antarmuka drag-and-drop yang intuitif.',
                    icon: 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253',
                    bg: 'bg-blue-50', color: 'text-blue-600'
                },
                {
                    title: 'Tracking & Pelaporan',
                    desc: 'Pantau progress belajar setiap karyawan secara real-time dengan laporan analitik mendalam.',
                    icon: 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
                    bg: 'bg-indigo-50', color: 'text-indigo-600'
                },
                {
                    title: 'Ujian & Kuis Online',
                    desc: 'Buat penilaian interaktif dengan berbagai tipe soal untuk mengukur pemahaman peserta.',
                    icon: 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4',
                    bg: 'bg-violet-50', color: 'text-violet-600'
                },
                {
                    title: 'Sertifikasi Digital',
                    desc: 'Terbitkan sertifikat elektronik otomatis saat peserta berhasil menyelesaikan pelatihan.',
                    icon: 'M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z',
                    bg: 'bg-amber-50', color: 'text-amber-600'
                },
                {
                    title: 'Akses Multi-Perangkat',
                    desc: 'Belajar di mana saja menggunakan laptop, tablet, atau smartphone dengan UI yang responsif.',
                    icon: 'M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z',
                    bg: 'bg-emerald-50', color: 'text-emerald-600'
                },
                {
                    title: 'Integrasi HR System',
                    desc: 'Terhubung seamless dengan sistem HR perusahaan Anda untuk sinkronisasi data karyawan otomatis.',
                    icon: 'M13 10V3L4 14h7v7l9-11h-7z',
                    bg: 'bg-rose-50', color: 'text-rose-600'
                },
            ],
            steps: [
                { title: 'Konsultasi', desc: 'Kami analisis kebutuhan pelatihan & struktur organisasi Anda.' },
                { title: 'Konfigurasi', desc: 'Setup platform, import data karyawan, dan atur struktur kursus.' },
                { title: 'Pelatihan', desc: 'Tim Anda mulai belajar dengan panduan onboarding dari kami.' },
                { title: 'Monitoring', desc: 'Pantau progress dan dapatkan laporan berkala untuk evaluasi.' },
            ]
        }
    }
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;900&display=swap');
.font-montserrat { font-family: 'Montserrat', sans-serif; }
</style>
