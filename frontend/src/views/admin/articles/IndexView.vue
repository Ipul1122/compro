<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'
import Sidebar from '@/components/admin/Sidebar.vue' 

const router = useRouter()
const isSidebarOpen = ref(false)
const isProfileOpen = ref(false)

const currentView = ref('articles') // Menandai menu aktif di Sidebar
const user = ref({ name: 'Admin', email: '' })

const articles = ref([])
const isLoading = ref(false)

onMounted(() => {
    const savedUser = localStorage.getItem('user')
    if (savedUser) {
        user.value = JSON.parse(savedUser)
        fetchArticles()
    } else {
        router.push('/view/login')
    }
})

// Fungsi memanggil API List Article (Bisa diganti dengan Pinia nanti jika diperlukan)
const fetchArticles = async () => {
    isLoading.value = true
    try {
        // Jangan lupa menggunakan interceptor/token authorization jika route diproteksi
        const token = localStorage.getItem('token')
        const response = await axios.get('http://localhost:8000/api/admin/articles', {
            headers: { Authorization: `Bearer ${token}` }
        })
        // Asumsi data dibungkus pagination Laravel (response.data.data)
        articles.value = response.data.data || response.data
    } catch (error) {
        console.error("Error fetching articles:", error)
    } finally {
        isLoading.value = false
    }
}

const handleNavigation = (view) => {
    if (view === 'dashboard') {
        router.push('/admin/dashboard')
    } else if (view === 'categories') {
        router.push('/admin/categories')
    }
}

const handleLogout = () => {
    localStorage.removeItem('user')
    localStorage.removeItem('token')
    router.push('/view/login')
}

const formatDate = (dateString) => {
    if (!dateString) return '-'
    return new Date(dateString).toLocaleDateString('id-ID', {
        day: 'numeric', month: 'short', year: 'numeric'
    })
}
</script>

<template>
    <div class="flex min-h-screen bg-slate-50 relative overflow-x-hidden">
        <div v-if="isSidebarOpen" @click="isSidebarOpen = false" class="fixed inset-0 bg-slate-900/50 z-[60] lg:hidden backdrop-blur-sm cursor-pointer"></div>
        <div v-if="isProfileOpen" @click="isProfileOpen = false" class="fixed inset-0 z-[100] bg-transparent"></div>

        <Sidebar 
            v-model:is-open="isSidebarOpen" 
            v-model:current-view="currentView"
            @update:currentView="handleNavigation"
            @logout="handleLogout"
        />

        <div class="flex-1 flex flex-col min-w-0">
            <nav class="h-20 bg-white border-b border-slate-100 px-4 md:px-8 flex items-center justify-between sticky top-0 z-[50]">
                <div class="flex items-center gap-4">
                    <button @click="isSidebarOpen = !isSidebarOpen" class="lg:hidden p-2 text-slate-600 hover:bg-slate-50 rounded-lg cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <h2 class="text-base md:text-lg font-bold text-slate-900 truncate uppercase tracking-tight">Manage Articles</h2>
                </div>

                <div class="relative z-[110]">
                    <button @click="isProfileOpen = !isProfileOpen" class="flex items-center gap-3 md:gap-4 hover:bg-slate-50 p-1.5 rounded-2xl transition-all cursor-pointer outline-none">
                        <div class="text-right hidden sm:block">
                            <p class="text-sm font-black text-slate-900 leading-none">{{ user.name }}</p>
                            <p class="text-[10px] text-slate-400 mt-1 uppercase tracking-widest font-bold">Admin</p>
                        </div>
                        <img :src="`https://ui-avatars.com/api/?name=${user.name}&background=0f172a&color=fff&bold=true`" class="h-10 w-10 rounded-full border-2 border-white shadow-sm shrink-0" />
                    </button>
                </div>
            </nav>

            <main class="p-4 md:p-8">
                <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden p-6">
                    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                        <h3 class="font-black text-xl text-slate-900 uppercase tracking-tight">Article List</h3>
                        <button class="bg-slate-900 text-white px-6 py-2 rounded-xl text-sm font-bold hover:bg-[#ea4435] transition-colors cursor-pointer w-full md:w-auto">
                            Create New
                        </button>
                    </div>

                   <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-slate-50 border-y border-slate-100">
                                <tr>
                                    <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase">Category</th>
                                    
                                    <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase">Article</th>
                                    <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase">Status</th>
                                    <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase text-center">Views</th>
                                    <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase">Created</th>
                                    
                                    <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase">Updated</th>
                                    
                                    <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                <tr v-if="isLoading">
                                    <td colspan="7" class="p-8 text-center text-slate-500 font-bold animate-pulse">Memuat data...</td>
                                </tr>
                                <tr v-for="article in articles" :key="article.id" class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <span class="bg-blue-50 text-blue-600 border border-blue-100 px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-wider whitespace-nowrap">
                                            {{ article.category?.name || 'Tanpa Kategori' }}
                                        </span>
                                    </td>
                                    
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <img :src="article.image || 'https://via.placeholder.com/150'" class="w-12 h-12 rounded-lg object-cover bg-slate-100 shrink-0" />
                                            <div class="max-w-[200px]">
                                                <p class="text-sm font-bold text-slate-900 truncate">{{ article.title }}</p>
                                                <p class="text-[10px] text-slate-400 truncate">{{ article.slug }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <td class="px-6 py-4">
                                        <span :class="article.published === 'publish' ? 'bg-emerald-100 text-emerald-600' : 'bg-orange-100 text-orange-600'" class="px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-wider">
                                            {{ article.published === 'publish' ? 'Published' : 'Draft' }}
                                        </span>
                                    </td>
                                    
                                    <td class="px-6 py-4 text-center font-bold text-slate-700 text-sm">
                                        {{ article.total_view || 0 }}
                                    </td>
                                    
                                    <td class="px-6 py-4 text-xs text-slate-500 font-medium whitespace-nowrap">
                                        {{ formatDate(article.created_at) }}
                                    </td>
                                    
                                    <td class="px-6 py-4 text-xs text-slate-500 font-medium whitespace-nowrap">
                                        {{ formatDate(article.updated_at) }}
                                    </td>
                                    
                                    <td class="px-6 py-4 text-right">
                                        <button class="text-slate-400 hover:text-slate-900 mr-4 transition-colors cursor-pointer text-sm font-bold">Edit</button>
                                        <button class="text-red-400 hover:text-red-600 transition-colors cursor-pointer text-sm font-bold">Del</button>
                                    </td>
                                </tr>
                                <tr v-if="!isLoading && articles.length === 0">
                                    <td colspan="7" class="p-10 text-center text-slate-400 text-sm font-medium">Tidak ada artikel ditemukan.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>
</template>

<style scoped>
button, a, .cursor-pointer { cursor: pointer !important; }
</style>