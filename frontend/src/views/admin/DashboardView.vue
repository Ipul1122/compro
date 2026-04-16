<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

// Import Komponen Terpisah
import Sidebar from '@/components/admin/Sidebar.vue'
import Navbar from '@/components/admin/Navbar.vue'

const router = useRouter()

// State yang menghubungkan Sidebar & Navbar
const isSidebarOpen = ref(false)

const showSuccessNotif = ref(false)
const currentView = ref('dashboard')
const user = ref({ name: 'Admin', email: '' })

// State data statistik
const articles = ref([])
const categories = ref([])

onMounted(() => {
    const savedUser = localStorage.getItem('user')
    if (savedUser) {
        user.value = JSON.parse(savedUser)
        showSuccessNotif.value = true
        setTimeout(() => { showSuccessNotif.value = false }, 4000)
        
        fetchArticlesStats()
        fetchCategoriesStats()
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

const handleNavigation = (view) => {
    if (view === 'categories') {
        router.push('/admin/categories')
    } else if (view === 'articles') {
        router.push('/admin/articles')
    }
}

const handleLogout = () => {
    localStorage.removeItem('user')
    localStorage.removeItem('token')
    router.push('/view/login')
}

const breadcrumbsData = ref([
    { label: 'Dashboard', link: '/admin' },
    // { label: 'Overview', link: null }
])
</script>

<template>
    <div class="flex min-h-screen bg-slate-50 relative overflow-x-hidden">
        <transition name="slide">
            <div v-if="showSuccessNotif" class="fixed top-4 right-4 left-4 md:left-auto z-[150] bg-slate-900 text-white px-6 py-4 rounded-2xl shadow-2xl flex items-center gap-4 border border-slate-700 md:w-80">
                <div class="h-8 w-8 shrink-0 bg-emerald-500 rounded-full flex items-center justify-center font-bold">✓</div>
                <div class="truncate">
                    <p class="font-bold text-sm leading-none">Login Successful</p>
                    <p class="text-xs text-slate-400 mt-1 truncate">Welcome back!</p>
                </div>
            </div>
        </transition>

        <Sidebar 
            v-model:is-open="isSidebarOpen" 
            v-model:current-view="currentView"
            @update:currentView="handleNavigation"
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
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
                    <router-link to="/admin/categories" class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:border-[#ea4435] transition-all group">
                        <p class="text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] group-hover:text-[#ea4435] transition-colors">Total Categories</p>
                        <h3 class="text-2xl md:text-3xl font-black text-slate-900 mt-1 uppercase">{{ categories.length || 0 }}</h3>
                    </router-link>

                    <router-link to="/admin/articles" class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:border-[#ea4435] transition-all group">
                        <p class="text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] group-hover:text-[#ea4435] transition-colors">Total Articles</p>
                        <h3 class="text-2xl md:text-3xl font-black text-slate-900 mt-1 uppercase">{{ articles.length || 0 }}</h3>
                    </router-link>

                    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
                        <p class="text-slate-400 text-[10px] font-black uppercase tracking-[0.2em]">Total Views</p>
                        <h3 class="text-2xl md:text-3xl font-black text-slate-900 mt-1 uppercase">
                            {{ articles.reduce((sum, art) => sum + (art.total_view || 0), 0) }}
                        </h3>
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