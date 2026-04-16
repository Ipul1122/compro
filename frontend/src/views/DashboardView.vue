<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'
import Sidebar from '@/components/admin/Sidebar.vue' 

const router = useRouter()
const isSidebarOpen = ref(false)
const isProfileOpen = ref(false)
const showSuccessNotif = ref(false)

// Navigation State
const currentView = ref('dashboard')

const user = ref({ name: 'Admin', email: '' })

// Article Data State
const articles = ref([])
const isLoading = ref(false)

onMounted(() => {
    const savedUser = localStorage.getItem('user')
    if (savedUser) {
        user.value = JSON.parse(savedUser)
        showSuccessNotif.value = true
        setTimeout(() => { showSuccessNotif.value = false }, 4000)
        fetchArticles()
    } else {
        router.push('/view/login')
    }
})

const fetchArticles = async () => {
    isLoading.value = true
    try {
        const response = await axios.get('http://localhost:8000/api/admin/articles')
        articles.value = response.data
    } catch (error) {
        console.error("Error fetching articles:", error)
    } finally {
        isLoading.value = false
    }
}

const handleLogout = () => {
    localStorage.removeItem('user')
    router.push('/view/login')
}

const toggleSidebar = () => {
    isSidebarOpen.value = !isSidebarOpen.value
}

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('en-ID', {
        day: 'numeric', month: 'short', year: 'numeric'
    })
}
</script>

<template>
    <div class="flex min-h-screen bg-slate-50 relative overflow-x-hidden">
        <div v-if="isSidebarOpen" @click="isSidebarOpen = false"
            class="fixed inset-0 bg-slate-900/50 z-[60] lg:hidden backdrop-blur-sm cursor-pointer"></div>
        <div v-if="isProfileOpen" @click="isProfileOpen = false" class="fixed inset-0 z-[100] bg-transparent"></div>

        <transition name="slide">
            <div v-if="showSuccessNotif"
                class="fixed top-4 right-4 left-4 md:left-auto z-[150] bg-slate-900 text-white px-6 py-4 rounded-2xl shadow-2xl flex items-center gap-4 border border-slate-700 md:w-80">
                <div class="h-8 w-8 shrink-0 bg-emerald-500 rounded-full flex items-center justify-center font-bold">✓
                </div>
                <div class="truncate">
                    <p class="font-bold text-sm leading-none">Login Successful</p>
                    <p class="text-xs text-slate-400 mt-1 truncate">Welcome back!</p>
                </div>
            </div>
        </transition>

        <Sidebar 
            v-model:is-open="isSidebarOpen" 
            v-model:current-view="currentView"
            @logout="handleLogout"
        />

        <div class="flex-1 flex flex-col min-w-0">
            <nav
                class="h-20 bg-white border-b border-slate-100 px-4 md:px-8 flex items-center justify-between sticky top-0 z-[50]">
                <div class="flex items-center gap-4">
                    <button @click="toggleSidebar"
                        class="lg:hidden p-2 text-slate-600 hover:bg-slate-50 rounded-lg cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <h2 class="text-base md:text-lg font-bold text-slate-900 truncate uppercase tracking-tight">
                        {{ currentView === 'dashboard' ? 'Overview' : currentView }}
                    </h2>
                </div>

                <div class="relative z-[110]">
                    <button @click="isProfileOpen = !isProfileOpen"
                        class="flex items-center gap-3 md:gap-4 hover:bg-slate-50 p-1.5 rounded-2xl transition-all cursor-pointer outline-none border border-transparent active:scale-95">
                        <div class="text-right hidden sm:block">
                            <p class="text-sm font-black text-slate-900 leading-none">{{ user.name }}</p>
                            <p class="text-[10px] text-slate-400 mt-1 uppercase tracking-widest font-bold">Admin</p>
                        </div>
                        <img :src="`https://ui-avatars.com/api/?name=${user.name}&background=0f172a&color=fff&bold=true`"
                            class="h-10 w-10 rounded-full border-2 border-white shadow-sm shrink-0" />
                    </button>

                    <transition name="pop">
                        <div v-if="isProfileOpen"
                            class="absolute right-0 mt-3 w-60 bg-white rounded-2xl shadow-[0_20px_50px_rgba(0,0,0,0.15)] border border-slate-100 py-2 z-[120] overflow-hidden">
                            <div class="px-4 py-3 border-b border-slate-50 mb-1">
                                <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest">Active Email
                                </p>
                                <p class="text-sm font-bold text-slate-900 truncate">{{ user.email }}</p>
                            </div>
                            <button @click="handleLogout"
                                class="btn-logout w-full flex items-center gap-3 px-4 py-3 text-sm font-bold text-red-500 hover:bg-red-50 transition-colors text-left">
                                Sign Out
                            </button>
                        </div>
                    </transition>
                </div>
            </nav>

            <main class="p-4 md:p-8">
                <div v-if="currentView === 'dashboard'"
                    class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
                    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
                        <p class="text-slate-400 text-[10px] font-black uppercase tracking-[0.2em]">Total Articles</p>
                        <h3 class="text-2xl md:text-3xl font-black text-slate-900 mt-1 uppercase">{{ articles.length }}
                        </h3>
                    </div>
                    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
                        <p class="text-slate-400 text-[10px] font-black uppercase tracking-[0.2em]">Total Views</p>
                        <h3 class="text-2xl md:text-3xl font-black text-slate-900 mt-1 uppercase">
                            {{articles.reduce((sum, art) => sum + (art.total_view || 0), 0)}}
                        </h3>
                    </div>
                </div>

                <div v-if="currentView === 'articles'"
                    class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-slate-50 flex justify-between items-center">
                        <h3 class="font-black text-slate-900 uppercase tracking-tight">Article List</h3>
                        <button
                            class="bg-slate-900 text-white px-4 py-2 rounded-xl text-xs font-bold hover:bg-[#ea4435] transition-colors cursor-pointer">Create
                            New</button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase">Article</th>
                                    <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase">Status</th>
                                    <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase text-center">
                                        Views</th>
                                    <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase">Created</th>
                                    <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase text-right">
                                        Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                <tr v-for="article in articles" :key="article.slug"
                                    class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <img :src="article.image"
                                                class="w-10 h-10 rounded-lg object-cover bg-slate-100" />
                                            <div class="max-w-[180px]">
                                                <p class="text-sm font-bold text-slate-900 truncate">{{ article.title }}
                                                </p>
                                                <p class="text-[10px] text-slate-400 truncate">{{ article.slug }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            :class="article.published ? 'bg-emerald-100 text-emerald-600' : 'bg-orange-100 text-orange-600'"
                                            class="px-2 py-1 rounded-md text-[10px] font-black uppercase">
                                            {{ article.published ? 'Published' : 'Draft' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center font-bold text-slate-700 text-sm">
                                        {{ article.total_view }}
                                    </td>
                                    <td class="px-6 py-4 text-[11px] text-slate-500 font-medium">
                                        {{ formatDate(article.timestamp) }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <button
                                            class="text-slate-400 hover:text-slate-900 mr-3 transition-colors cursor-pointer">Edit</button>
                                        <button class="text-red-400 hover:text-red-600 transition-colors cursor-pointer">Del</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div v-if="articles.length === 0" class="p-10 text-center text-slate-400 text-sm">No articles
                            found.</div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</template>

<style scoped>
button,
a,
.cursor-pointer,
.btn-logout {
    cursor: pointer !important;
}

.btn-logout {
    position: relative;
    z-index: 130;
}

.pop-enter-active,
.pop-leave-active {
    transition: all 0.2s ease-out;
}

.pop-enter-from {
    transform: translateY(-10px) scale(0.95);
    opacity: 0;
}

.pop-leave-to {
    transform: translateY(-10px) scale(0.95);
    opacity: 0;
}

.slide-enter-active,
.slide-leave-active {
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

.slide-enter-from {
    transform: translateX(100%);
    opacity: 0;
}
</style>