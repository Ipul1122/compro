<script setup>
import { ref, onMounted, computed } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

// Import Chart.js dan vue-chartjs
import { Bar } from 'vue-chartjs'
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale } from 'chart.js'

// Daftarkan komponen Chart.js
ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale)

// Import Komponen Terpisah
import Sidebar from '@/components/admin/Sidebar.vue'
import Navbar from '@/components/admin/Navbar.vue'

const router = useRouter()

// State yang menghubungkan Sidebar & Navbar
const isSidebarOpen = ref(false)

const showSuccessNotif = ref(false)
const user = ref({ name: 'Admin', email: '' })

// State data statistik
const articles = ref([])
const categories = ref([])
const galleries = ref([])

onMounted(() => {
    const savedUser = localStorage.getItem('user')
    if (savedUser) {
        user.value = JSON.parse(savedUser)
        showSuccessNotif.value = true
        setTimeout(() => { showSuccessNotif.value = false }, 4000)
        
        fetchArticlesStats()
        fetchCategoriesStats()
        fetchGalleriesStats()
    } else {
        router.push('/view/login')
    }
})

const fetchArticlesStats = async () => {
    try {
        const token = localStorage.getItem('token')
        const response = await axios.get('http://localhost:8000/api/admin/articles', {
            headers: { Authorization: `Bearer ${token}` }
        })
        articles.value = response.data.data || response.data 
    } catch (error) {
        console.error("Error fetching article stats:", error)
    }
}

const fetchCategoriesStats = async () => {
    try {
        const token = localStorage.getItem('token')
        const response = await axios.get('http://localhost:8000/api/admin/categories', {
            headers: { Authorization: `Bearer ${token}` }
        })
        categories.value = response.data.data || response.data
    } catch (error) {
        console.error("Error fetching category stats:", error)
    }
}

const fetchGalleriesStats = async () => {
    try {
        const token = localStorage.getItem('token')
        const response = await axios.get('http://localhost:8000/api/admin/galleries', {
            headers: { Authorization: `Bearer ${token}` }
        })
        galleries.value = response.data.data || response.data
    } catch (error) {
        console.error("Error fetching gallery stats:", error)
    }
}

// Data dinamis untuk Chart.js (Mengambil Top 10 Artikel berdasarkan view)
const chartData = computed(() => {
    // Urutkan artikel dari views terbanyak
    const sortedArticles = [...articles.value]
        .sort((a, b) => (b.total_view || 0) - (a.total_view || 0))
        .slice(0, 10);

    return {
        labels: sortedArticles.map(a => a.title.length > 15 ? a.title.substring(0, 15) + '...' : a.title),
        datasets: [
            {
                label: 'Total Views',
                backgroundColor: '#ea4435',
                borderRadius: 4,
                data: sortedArticles.map(a => a.total_view || 0)
            }
        ]
    }
})

const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            display: false
        },
        tooltip: {
            backgroundColor: '#1e293b',
            titleFont: { size: 14 },
            bodyFont: { size: 13 },
            padding: 10,
            cornerRadius: 8,
        }
    },
    scales: {
        y: {
            beginAtZero: true,
            grid: {
                color: '#f1f5f9',
                drawBorder: false,
            }
        },
        x: {
            grid: {
                display: false,
                drawBorder: false,
            }
        }
    }
}

const handleLogout = () => {
    // Komponen Navbar juga menjalankan API logout, jadi kita cukup hapus session & redirect
    localStorage.removeItem('user')
    localStorage.removeItem('token')
    router.push('/view/login')
}

const breadcrumbsData = ref([
    { label: 'Dashboard', link: '/admin' }
])
</script>

<template>
    <div class="flex min-h-screen bg-slate-50 relative overflow-x-hidden">
        <transition name="slide">
            <div v-if="showSuccessNotif" class="fixed top-4 right-4 left-4 md:left-auto z-150 bg-slate-900 text-white px-6 py-4 rounded-2xl shadow-2xl flex items-center gap-4 border border-slate-700 md:w-80">
                <div class="h-8 w-8 shrink-0 bg-emerald-500 rounded-full flex items-center justify-center font-bold">✓</div>
                <div class="truncate">
                    <p class="font-bold text-sm leading-none">Login Successful</p>
                    <p class="text-xs text-slate-400 mt-1 truncate">Welcome back!</p>
                </div>
            </div>
        </transition>

        <Sidebar 
            v-model:is-open="isSidebarOpen" 
            @logout="handleLogout"
        />

        <div class="flex-1 flex flex-col min-w-0">
            <Navbar 
                :user="user" 
                :breadcrumbs="breadcrumbsData" 
                @toggle-sidebar="isSidebarOpen = !isSidebarOpen" 
                @logout="handleLogout"
            />

            <main class="p-4 md:p-8">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-8">
                    
                    <router-link to="/admin/categories" class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-md hover:border-[#ea4435] transition-all group flex flex-col justify-between">
                        <div class="flex justify-between items-center mb-4">
                            <p class="text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] group-hover:text-[#ea4435] transition-colors">Kategori</p>
                            <div class="w-8 h-8 rounded-full bg-slate-50 flex items-center justify-center group-hover:bg-red-50 transition-colors">
                                <svg class="w-4 h-4 text-slate-400 group-hover:text-[#ea4435]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                            </div>
                        </div>
                        <h3 class="text-3xl md:text-4xl font-black text-slate-900 mt-1">{{ categories.length || 0 }}</h3>
                    </router-link>

                    <router-link to="/admin/articles" class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-md hover:border-[#ea4435] transition-all group flex flex-col justify-between">
                        <div class="flex justify-between items-center mb-4">
                            <p class="text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] group-hover:text-[#ea4435] transition-colors">Artikel</p>
                            <div class="w-8 h-8 rounded-full bg-slate-50 flex items-center justify-center group-hover:bg-red-50 transition-colors">
                                <svg class="w-4 h-4 text-slate-400 group-hover:text-[#ea4435]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2.5 2.5 0 00-2.5-2.5H14"></path></svg>
                            </div>
                        </div>
                        <h3 class="text-3xl md:text-4xl font-black text-slate-900 mt-1">{{ articles.length || 0 }}</h3>
                    </router-link>

                    <router-link to="/admin/gallery" class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-md hover:border-[#ea4435] transition-all group flex flex-col justify-between">
                        <div class="flex justify-between items-center mb-4">
                            <p class="text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] group-hover:text-[#ea4435] transition-colors">Galeri</p>
                            <div class="w-8 h-8 rounded-full bg-slate-50 flex items-center justify-center group-hover:bg-red-50 transition-colors">
                                <svg class="w-4 h-4 text-slate-400 group-hover:text-[#ea4435]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                        </div>
                        <h3 class="text-3xl md:text-4xl font-black text-slate-900 mt-1">{{ galleries.length || 0 }}</h3>
                    </router-link>

                    <div class="bg-gradient-to-br from-[#ea4435] to-[#c53225] p-6 rounded-3xl shadow-lg flex flex-col justify-between text-white relative overflow-hidden">
                        <div class="absolute -right-6 -top-6 w-24 h-24 bg-white opacity-10 rounded-full blur-xl"></div>
                        <div class="absolute -left-6 -bottom-6 w-24 h-24 bg-black opacity-10 rounded-full blur-xl"></div>
                        
                        <div class="flex justify-between items-center mb-4 relative z-10">
                            <p class="text-white/80 text-[10px] font-black uppercase tracking-[0.2em]">Total Views</p>
                            <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center backdrop-blur-sm">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </div>
                        </div>
                        <h3 class="text-3xl md:text-4xl font-black mt-1 relative z-10">
                            {{ articles.reduce((sum, art) => sum + (art.total_view || 0), 0).toLocaleString('id-ID') }}
                        </h3>
                    </div>
                </div>

                <div class="bg-white p-6 md:p-8 rounded-3xl border border-slate-100 shadow-sm">
                    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <div>
                            <h2 class="text-xl font-black text-slate-900">Analisis Kunjungan Artikel</h2>
                            <p class="text-sm text-slate-500 mt-1">10 Artikel dengan performa views terbaik</p>
                        </div>
                    </div>
                    
                    <div class="h-[300px] md:h-[400px] w-full">
                        <Bar v-if="articles.length > 0" :data="chartData" :options="chartOptions" />
                        <div v-else class="w-full h-full flex items-center justify-center text-slate-400 text-sm font-medium">
                            Memuat data grafik...
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</template>

<style scoped>
a { text-decoration: none; }
.slide-enter-active, .slide-leave-active { transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
.slide-enter-from { transform: translateX(100%); opacity: 0; }
</style>