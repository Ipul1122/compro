<script setup>
import { ref, onMounted, onUnmounted, computed } from 'vue'
import { useRouter } from 'vue-router'

import Sidebar from '@/components/direktur/Sidebar.vue'
import Navbar from '@/components/admin/Navbar.vue'

const router = useRouter()
const isSidebarOpen = ref(false)
const showSuccessNotif = ref(false)
const user = ref({ name: 'Direktur', email: '' })

const currentTime = ref(new Date())
let clockInterval = null

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
    } else {
        router.push('/view/login')
    }
})

onUnmounted(() => { clearInterval(clockInterval) })

const handleLogout = () => {
    sessionStorage.removeItem('user')
    sessionStorage.removeItem('token')
    router.push('/view/login')
}

const breadcrumbsData = ref([{ label: 'Dashboard', link: '/direktur' }])
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
            <Navbar :user="user" :breadcrumbs="breadcrumbsData" @toggle-sidebar="isSidebarOpen = !isSidebarOpen" @logout="handleLogout" />

            <main class="p-4 md:p-6 lg:p-8 space-y-6">
                <!-- Greeting Header -->
                <div class="dash-greeting flex flex-col sm:flex-row sm:items-end justify-between gap-4">
                    <div>
                        <p class="text-slate-400 text-xs font-semibold uppercase tracking-widest mb-1">{{ formattedDate }}</p>
                        <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900 leading-tight">
                            {{ greeting }}, <span class="text-amber-500">{{ user.name }}</span> 👋
                        </h1>
                        <p class="text-slate-500 text-sm mt-1">Selamat datang di portal direktur.</p>
                    </div>
                    <div class="flex items-center gap-2 text-slate-400 text-sm font-mono bg-white border border-slate-100 rounded-xl px-4 py-2 shadow-sm shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        {{ formattedTime }}
                    </div>
                </div>

                <!-- Fitur Direktur -->
                <div class="bg-white rounded-3xl border border-slate-100 p-6 shadow-sm">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                        <div>
                            <h2 class="text-lg font-extrabold text-slate-900">Fitur Direktur</h2>
                            <p class="text-sm text-slate-500 mt-1">Akses cepat ke fungsi direktur: kelola data karyawan.</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <router-link to="/admin/karyawan" class="group block rounded-3xl border border-slate-200 p-5 bg-slate-50 hover:bg-slate-100 transition-all">
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
                        <router-link to="/admin/karyawan" class="group block rounded-3xl border border-slate-200 p-5 bg-slate-50 hover:bg-slate-100 transition-all">
                            <div class="flex items-center justify-between gap-3 mb-4">
                                <div>
                                    <p class="text-slate-400 uppercase tracking-[0.2em] text-[10px] font-black">Data</p>
                                    <h3 class="text-xl font-bold text-slate-900 mt-2">Lihat daftar</h3>
                                </div>
                                <div class="h-12 w-12 rounded-2xl bg-slate-900 text-white flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 15c2.766 0 5.353.88 7.379 2.379M15 10a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                </div>
                            </div>
                            <p class="text-slate-500 text-sm">Lihat daftar seluruh karyawan yang terdaftar dalam sistem.</p>
                        </router-link>
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
.slide-leave-to { transform: translateX(100%); opacity: 0; }
.dash-greeting { animation: fadeIn 0.6s ease-out both; }
@keyframes fadeIn { from { opacity: 0; transform: translateY(-8px); } to { opacity: 1; transform: translateY(0); } }
</style>
