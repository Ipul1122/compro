<script setup>
import { ref, onMounted, onUnmounted, computed } from 'vue'
import { useRouter } from 'vue-router'
import Api from '@/api'
import { Bar } from 'vue-chartjs'
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale } from 'chart.js'
ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale)

import Sidebar from '@/components/direktur/Sidebar.vue'
import Navbar from '@/components/direktur/Navbar.vue'

const router = useRouter()
const isSidebarOpen = ref(false)
const showSuccessNotif = ref(false)
const user = ref({ name: 'Direktur', email: '' })
const isLoading = ref(true)

const articles = ref([])
const categories = ref([])
const galleries = ref([])
const articleActivities = ref([])

const viewsFilter = ref('semua')

const currentTime = ref(new Date())
let clockInterval = null
let activityInterval = null

const formattedDate = computed(() => currentTime.value.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }))
const formattedTime = computed(() => currentTime.value.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }))

const greeting = computed(() => {
    const h = currentTime.value.getHours()
    if (h < 12) return 'Selamat Pagi'
    if (h < 15) return 'Selamat Siang'
    if (h < 18) return 'Selamat Sore'
    return 'Selamat Malam'
})

onMounted(() => {
    clockInterval = setInterval(() => { currentTime.value = new Date() }, 1000)
    const savedUser = sessionStorage.getItem('user')
    if (savedUser) {
        user.value = JSON.parse(savedUser)
        showSuccessNotif.value = true
        setTimeout(() => { showSuccessNotif.value = false }, 4000)
        Promise.all([fetchArticlesStats(), fetchCategoriesStats(), fetchGalleriesStats(), fetchArticleActivities()])
            .finally(() => { isLoading.value = false })
        activityInterval = setInterval(fetchArticleActivities, 60000)
    } else {
        router.push('/view/login')
    }
})

onUnmounted(() => {
    clearInterval(clockInterval)
    clearInterval(activityInterval)
})

const fetchArticlesStats = async () => {
    try { const r = await Api.get('/direktur/articles'); articles.value = r.data.data || r.data } catch (e) { console.error(e) }
}
const fetchCategoriesStats = async () => {
    try { const r = await Api.get('/direktur/categories'); categories.value = r.data.data || r.data } catch (e) { console.error(e) }
}
const fetchGalleriesStats = async () => {
    try { const r = await Api.get('/direktur/galleries'); galleries.value = r.data.data || r.data } catch (e) { console.error(e) }
}
const fetchArticleActivities = async () => {
    try {
        const r = await Api.get('/direktur/dashboard/article-activities', { params: { limit: 8 } })
        articleActivities.value = r.data?.data || []
    } catch (e) {
        console.error(e)
    }
}

const filteredArticlesForViews = computed(() => {
    const now = new Date()
    return articles.value.filter(art => {
        if (viewsFilter.value === 'semua') return true
        
        const artDate = new Date(art.created_at)
        
        if (viewsFilter.value === 'hari') {
            return artDate.getDate() === now.getDate() &&
                   artDate.getMonth() === now.getMonth() &&
                   artDate.getFullYear() === now.getFullYear()
        }
        if (viewsFilter.value === 'minggu') {
            const oneWeekAgo = new Date(now.getTime() - 7 * 24 * 60 * 60 * 1000)
            return artDate >= oneWeekAgo && artDate <= now
        }
        if (viewsFilter.value === 'bulan') {
            return artDate.getMonth() === now.getMonth() &&
                   artDate.getFullYear() === now.getFullYear()
        }
        if (viewsFilter.value === 'tahun') {
            return artDate.getFullYear() === now.getFullYear()
        }
        return true
    })
})

const totalViews = computed(() => filteredArticlesForViews.value.reduce((sum, art) => sum + (art.total_view || 0), 0))
const recentActivityCount = computed(() => articleActivities.value.filter((item) => item.is_recent).length)

const chartData = computed(() => {
    const sorted = [...articles.value].sort((a, b) => (b.total_view || 0) - (a.total_view || 0)).slice(0, 10)
    return {
        labels: sorted.map(a => a.title.length > 18 ? a.title.substring(0, 18) + '…' : a.title),
        datasets: [{
            label: 'Total Views',
            backgroundColor: (ctx) => {
                const { ctx: c, chartArea } = ctx.chart
                if (!chartArea) return '#f59e0b'
                const g = c.createLinearGradient(0, chartArea.bottom, 0, chartArea.top)
                g.addColorStop(0, 'rgba(245,158,11,0.6)')
                g.addColorStop(1, 'rgba(245,158,11,1)')
                return g
            },
            hoverBackgroundColor: '#d97706',
            borderRadius: 8, borderSkipped: false,
            data: sorted.map(a => a.total_view || 0),
            barThickness: 32, maxBarThickness: 40
        }]
    }
})

const chartOptions = {
    responsive: true, maintainAspectRatio: false,
    animation: { duration: 800, easing: 'easeOutQuart' },
    plugins: {
        legend: { display: false },
        tooltip: { backgroundColor: '#0f172a', titleFont: { size: 13, weight: '600', family: 'Inter' }, bodyFont: { size: 12, family: 'Inter' }, padding: 12, cornerRadius: 10, displayColors: false, callbacks: { label: (ctx) => `${ctx.parsed.y.toLocaleString('id-ID')} views` } }
    },
    scales: {
        y: { beginAtZero: true, border: { display: false }, grid: { color: '#f1f5f9', drawBorder: false }, ticks: { font: { size: 11, family: 'Inter' }, color: '#94a3b8', padding: 8 } },
        x: { border: { display: false }, grid: { display: false }, ticks: { font: { size: 10, family: 'Inter' }, color: '#94a3b8', maxRotation: 45, padding: 4 } }
    }
}

const handleLogout = () => {
    sessionStorage.removeItem('user')
    sessionStorage.removeItem('token')
    router.push('/view/login')
}

const breadcrumbsData = ref([{ label: 'Dashboard', link: '/direktur' }])

const statCards = computed(() => [
    { label: 'Kategori', value: categories.value.length, route: '/direktur/categories', icon: 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z', color: 'violet' },
    { label: 'Artikel', value: articles.value.length, route: '/direktur/articles', icon: 'M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2.5 2.5 0 00-2.5-2.5H14', color: 'sky' },
    { label: 'Galeri', value: galleries.value.length, route: '/direktur/gallery', icon: 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z', color: 'amber' }
])
</script>

<template>
    <div class="flex min-h-screen bg-slate-50 relative overflow-x-hidden">
        <transition name="slide">
            <div v-if="showSuccessNotif" class="fixed top-4 right-4 left-4 md:left-auto z-[150] bg-slate-900 text-white px-5 py-3.5 rounded-2xl shadow-2xl flex items-center gap-3 border border-amber-700/40 md:w-80">
                <div class="h-8 w-8 shrink-0 bg-amber-500 rounded-full flex items-center justify-center font-bold text-sm">✓</div>
                <div class="truncate">
                    <p class="font-bold text-sm leading-none">Login Successful</p>
                    <p class="text-xs text-slate-400 mt-1 truncate">Welcome back, {{ user.name }}!</p>
                </div>
            </div>
        </transition>

        <Sidebar v-model:is-open="isSidebarOpen" @logout="handleLogout" />

        <div class="flex-1 flex flex-col min-w-0">
            <Navbar
                :user="user"
                :breadcrumbs="breadcrumbsData"
                @toggle-sidebar="isSidebarOpen = !isSidebarOpen"
                @logout="handleLogout"
            />

            <main class="p-4 md:p-6 lg:p-8 space-y-6">
                <div class="dash-greeting flex flex-col sm:flex-row sm:items-end justify-between gap-4">
                    <div>
                        <p class="text-slate-400 text-xs font-semibold uppercase tracking-widest mb-1">{{ formattedDate }}</p>
                        <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900 leading-tight">
                            {{ greeting }}, <span class="text-amber-500">{{ user.name }}</span> 👋
                        </h1>
                        <p class="text-slate-500 text-sm mt-1">Berikut ringkasan konten website Anda hari ini.</p>
                    </div>
                    <div class="flex items-center gap-2 text-slate-400 text-sm font-mono bg-white border border-slate-100 rounded-xl px-4 py-2 shadow-sm shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        {{ formattedTime }}
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <router-link v-for="(card, i) in statCards" :key="card.label" :to="card.route" class="stat-card group relative bg-white rounded-2xl border border-slate-100 p-5 shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden" :style="{ animationDelay: `${i * 80}ms` }">
                        <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none" :class="{ 'bg-gradient-to-br from-violet-50/80 to-transparent': card.color === 'violet', 'bg-gradient-to-br from-sky-50/80 to-transparent': card.color === 'sky', 'bg-gradient-to-br from-amber-50/80 to-transparent': card.color === 'amber' }"></div>
                        <div class="relative z-10">
                            <div class="flex justify-between items-center mb-4">
                                <p class="text-slate-400 text-[10px] font-bold uppercase tracking-[0.15em] group-hover:text-slate-600 transition-colors">{{ card.label }}</p>
                                <div class="w-9 h-9 rounded-xl flex items-center justify-center transition-all duration-300" :class="{ 'bg-violet-50 group-hover:bg-violet-100': card.color === 'violet', 'bg-sky-50 group-hover:bg-sky-100': card.color === 'sky', 'bg-amber-50 group-hover:bg-amber-100': card.color === 'amber' }">
                                    <svg class="w-4 h-4 transition-colors duration-300" :class="{ 'text-violet-500': card.color === 'violet', 'text-sky-500': card.color === 'sky', 'text-amber-500': card.color === 'amber' }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="card.icon"></path></svg>
                                </div>
                            </div>
                            <div class="flex items-end justify-between">
                                <h3 class="text-3xl font-extrabold text-slate-900 tabular-nums">{{ isLoading ? '—' : card.value }}</h3>
                                <svg class="w-4 h-4 text-slate-300 group-hover:text-slate-500 group-hover:translate-x-0.5 transition-all duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </div>
                        </div>
                    </router-link>

                    <div class="stat-card relative bg-gradient-to-br from-amber-500 to-orange-600 rounded-2xl p-5 shadow-lg text-white overflow-hidden" style="animation-delay: 240ms">
                        <div class="absolute -right-8 -top-8 w-28 h-28 bg-white/10 rounded-full blur-2xl"></div>
                        <div class="absolute -left-6 -bottom-6 w-20 h-20 bg-black/10 rounded-full blur-2xl"></div>
                        <div class="relative z-10">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <p class="text-white/70 text-[10px] font-bold uppercase tracking-[0.15em] mb-1.5">Total Views</p>
                                    <div class="relative inline-block">
                                        <select v-model="viewsFilter" class="bg-white/20 border border-white/20 text-white text-xs font-medium rounded-lg pl-2.5 pr-7 py-1 outline-none focus:ring-2 focus:ring-white/30 cursor-pointer backdrop-blur-sm appearance-none">
                                            <option value="semua" class="text-slate-800">Semua Waktu</option>
                                            <option value="hari" class="text-slate-800">Hari Ini</option>
                                            <option value="minggu" class="text-slate-800">7 Hari Terakhir</option>
                                            <option value="bulan" class="text-slate-800">Bulan Ini</option>
                                            <option value="tahun" class="text-slate-800">Tahun Ini</option>
                                        </select>
                                        <svg class="w-3.5 h-3.5 text-white absolute top-1/2 right-2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                </div>
                                <div class="w-9 h-9 rounded-xl bg-white/15 backdrop-blur-sm flex items-center justify-center shrink-0">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </div>
                            </div>
                            <h3 class="text-3xl font-extrabold tabular-nums">{{ isLoading ? '—' : totalViews.toLocaleString('id-ID') }}</h3>
                        </div>
                    </div>
                </div>

                <!-- Fitur Direktur -->
                <div class="bg-white rounded-3xl border border-slate-100 p-6 shadow-sm">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                        <div>
                            <h2 class="text-lg font-extrabold text-slate-900">Fitur Direktur</h2>
                            <p class="text-sm text-slate-500 mt-1">Akses cepat ke fungsi direktur: tambah/hapus karyawan, approve artikel, dan lihat pembuat artikel.</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <router-link to="/direktur/karyawan" class="group block rounded-3xl border border-slate-200 p-5 bg-slate-50 hover:bg-slate-100 transition-all">
                            <div class="flex items-center justify-between gap-3 mb-4">
                                <div>
                                    <p class="text-slate-400 uppercase tracking-[0.2em] text-[10px] font-black">Karyawan</p>
                                    <h3 class="text-xl font-bold text-slate-900 mt-2">Tambah & hapus</h3>
                                </div>
                                <div class="h-12 w-12 rounded-2xl bg-slate-900 text-white flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-3-3h-1M4 20h5v-2a3 3 0 00-3-3H5m9-4a4 4 0 10-8 0 4 4 0 008 0zm4 0a4 4 0 10-8 0 4 4 0 008 0z" /></svg>
                                </div>
                            </div>
                            <p class="text-slate-500 text-sm">Input data karyawan baru dan hapus akun karyawan melalui halaman manajemen karyawan.</p>
                        </router-link>
                        <router-link to="/direktur/articles" class="group block rounded-3xl border border-slate-200 p-5 bg-slate-50 hover:bg-slate-100 transition-all">
                            <div class="flex items-center justify-between gap-3 mb-4">
                                <div>
                                    <p class="text-slate-400 uppercase tracking-[0.2em] text-[10px] font-black">Artikel</p>
                                    <h3 class="text-xl font-bold text-slate-900 mt-2">Approve</h3>
                                </div>
                                <div class="h-12 w-12 rounded-2xl bg-slate-900 text-white flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                </div>
                            </div>
                            <p class="text-slate-500 text-sm">Approve artikel yang berstatus pending agar dapat dipublikasikan.</p>
                        </router-link>
                        <router-link to="/direktur/articles" class="group block rounded-3xl border border-slate-200 p-5 bg-slate-50 hover:bg-slate-100 transition-all">
                            <div class="flex items-center justify-between gap-3 mb-4">
                                <div>
                                    <p class="text-slate-400 uppercase tracking-[0.2em] text-[10px] font-black">Author</p>
                                    <h3 class="text-xl font-bold text-slate-900 mt-2">Lihat pembuat</h3>
                                </div>
                                <div class="h-12 w-12 rounded-2xl bg-slate-900 text-white flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 15c2.766 0 5.353.88 7.379 2.379M15 10a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                </div>
                            </div>
                            <p class="text-slate-500 text-sm">Lihat nama pembuat artikel langsung di kolom Author.</p>
                        </router-link>
                    </div>
                </div>

                <!-- Chart -->
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                    <div class="p-5 md:p-6 border-b border-slate-50 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                        <div>
                            <h2 class="text-lg font-extrabold text-slate-900">Analisis Kunjungan Artikel</h2>
                            <p class="text-xs text-slate-400 mt-0.5">10 artikel dengan performa views terbaik</p>
                        </div>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400 bg-slate-50 px-3 py-1.5 rounded-lg">Top 10</span>
                    </div>
                    <div class="p-5 md:p-6">
                        <div class="h-[280px] md:h-[360px] w-full">
                            <Bar v-if="articles.length > 0" :data="chartData" :options="chartOptions" />
                            <div v-else class="w-full h-full flex flex-col items-center justify-center text-slate-300 gap-3">
                                <svg class="w-10 h-10 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                                <p class="text-sm font-medium">Memuat data grafik...</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <router-link to="/direktur/articles/create" class="quick-action group flex items-center gap-4 bg-white rounded-2xl border border-slate-100 p-4 shadow-sm hover:shadow-md hover:border-emerald-200 transition-all duration-300">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 group-hover:bg-emerald-100 flex items-center justify-center transition-colors shrink-0">
                            <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        </div>
                        <div class="min-w-0"><p class="text-sm font-bold text-slate-900">Tulis Artikel Baru</p><p class="text-[11px] text-slate-400 truncate">Buat konten baru untuk website</p></div>
                    </router-link>
                    <router-link to="/direktur/categories" class="quick-action group flex items-center gap-4 bg-white rounded-2xl border border-slate-100 p-4 shadow-sm hover:shadow-md hover:border-violet-200 transition-all duration-300">
                        <div class="w-10 h-10 rounded-xl bg-violet-50 group-hover:bg-violet-100 flex items-center justify-center transition-colors shrink-0">
                            <svg class="w-5 h-5 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        </div>
                        <div class="min-w-0"><p class="text-sm font-bold text-slate-900">Kelola Kategori</p><p class="text-[11px] text-slate-400 truncate">Atur kategori konten</p></div>
                    </router-link>
                    <router-link to="/direktur/gallery" class="quick-action group flex items-center gap-4 bg-white rounded-2xl border border-slate-100 p-4 shadow-sm hover:shadow-md hover:border-amber-200 transition-all duration-300">
                        <div class="w-10 h-10 rounded-xl bg-amber-50 group-hover:bg-amber-100 flex items-center justify-center transition-colors shrink-0">
                            <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <div class="min-w-0"><p class="text-sm font-bold text-slate-900">Upload Galeri</p><p class="text-[11px] text-slate-400 truncate">Tambah foto ke galeri</p></div>
                    </router-link>
                </div>
            </main>
        </div>
    </div>
</template>

<style scoped>
a { text-decoration: none; }
.slide-enter-active, .slide-leave-active { transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
.slide-enter-from { transform: translateX(100%); opacity: 0; }
.slide-leave-to { transform: translateX(100%); opacity: 0; }
.stat-card { animation: cardUp 0.5s cubic-bezier(0.22, 1, 0.36, 1) both; }
@keyframes cardUp { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: translateY(0); } }
.dash-greeting { animation: fadeIn 0.6s ease-out both; }
@keyframes fadeIn { from { opacity: 0; transform: translateY(-8px); } to { opacity: 1; transform: translateY(0); } }
.quick-action { transition: transform 0.25s ease, box-shadow 0.25s ease; }
.quick-action:hover { transform: translateY(-2px); }
</style>
