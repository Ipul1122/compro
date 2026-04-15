<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()

// UI State
const isSidebarOpen = ref(false)
const isProfileOpen = ref(false)
const showSuccessNotif = ref(false)

// User Data
const user = ref({
    name: 'Admin',
    email: '',
    role: 'Administrator'
})

onMounted(() => {
    // 1. Check if user is logged in
    const savedUser = localStorage.getItem('user')

    if (savedUser) {
        user.value = JSON.parse(savedUser)

        // 2. Show the "Login Successful" toast
        showSuccessNotif.value = true
        setTimeout(() => {
            showSuccessNotif.value = false
        }, 4000)
    } else {
        // 3. Security: Redirect to login if no data found
        router.push('/view/login')
    }
})

const toggleSidebar = () => {
    isSidebarOpen.value = !isSidebarOpen.value
}

const logout = () => {
    // Clear session and redirect
    localStorage.removeItem('user')
    router.push('/view/login')
}
</script>

<template>
    <div class="flex min-h-screen bg-slate-50 relative overflow-x-hidden">

        <div v-if="isSidebarOpen" @click="isSidebarOpen = false"
            class="fixed inset-0 bg-slate-900/60 z-[60] lg:hidden backdrop-blur-sm transition-opacity">
        </div>

        <div v-if="isProfileOpen" @click="isProfileOpen = false" class="fixed inset-0 z-[80] bg-transparent">
        </div>

        <transition name="slide">
            <div v-if="showSuccessNotif"
                class="fixed top-4 right-4 left-4 md:left-auto z-[100] bg-slate-900 text-white px-6 py-4 rounded-2xl shadow-2xl flex items-center gap-4 border border-slate-700 md:w-80">
                <div class="h-10 w-10 shrink-0 bg-emerald-500 rounded-full flex items-center justify-center text-xl">✓
                </div>
                <div class="min-w-0">
                    <p class="font-bold text-sm leading-none">Login Successful</p>
                    <p class="text-xs text-slate-400 mt-1 truncate">Welcome back, {{ user.name }}</p>
                </div>
            </div>
        </transition>

        <aside :class="isSidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
            class="fixed lg:sticky top-0 inset-y-0 left-0 w-64 bg-slate-900 flex flex-col z-[70] transition-transform duration-300 ease-in-out h-screen shadow-2xl lg:shadow-none">

            <div class="p-6 border-b border-slate-800 flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div
                        class="h-9 w-9 bg-[#ea4435] rounded-xl flex items-center justify-center text-white font-black shadow-lg shadow-red-500/20">
                        W</div>
                    <span class="text-white font-bold tracking-tight text-xl">Cakrawala</span>
                </div>
                <button @click="isSidebarOpen = false"
                    class="lg:hidden text-slate-400 p-1 hover:bg-slate-800 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <nav class="flex-1 p-4 space-y-1.5 mt-4">
                <a href="#"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl bg-[#ea4435] text-white shadow-lg shadow-red-500/20 font-bold text-sm transition-all active:scale-95">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                    Dashboard
                </a>
                <a href="#"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800 hover:text-white transition-all font-bold text-sm group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-500 group-hover:text-white"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    Content Manager
                </a>
            </nav>

            <div class="p-4 mt-auto border-t border-slate-800">
                <button @click="logout"
                    class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-red-500/10 hover:text-red-500 transition-all font-bold text-sm group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-hover:stroke-red-500" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    Logout System
                </button>
            </div>
        </aside>

        <div class="flex-1 flex flex-col min-w-0">

            <nav
                class="h-20 bg-white border-b border-slate-100 px-4 md:px-8 flex items-center justify-between sticky top-0 z-[50]">
                <div class="flex items-center gap-3 min-w-0">
                    <button @click="toggleSidebar"
                        class="lg:hidden p-2.5 text-slate-600 hover:bg-slate-50 rounded-xl shrink-0 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <h2 class="text-base md:text-lg font-bold text-slate-900 truncate tracking-tight">Admin Overview
                    </h2>
                </div>

                <div class="relative">
                    <button @click="isProfileOpen = !isProfileOpen"
                        class="flex items-center gap-2 md:gap-3 hover:bg-slate-50 p-1.5 rounded-2xl transition-all outline-none border border-transparent active:border-slate-100">
                        <div class="text-right hidden sm:block">
                            <p class="text-sm font-bold text-slate-900 leading-none">{{ user.name }}</p>
                            <p class="text-[10px] text-slate-400 mt-1 uppercase tracking-widest font-bold">Admin</p>
                        </div>
                        <img :src="`https://ui-avatars.com/api/?name=${user.name}&background=0f172a&color=fff&bold=true`"
                            class="h-9 w-9 md:h-10 md:w-10 rounded-full border-2 border-white shadow-md" />
                    </button>

                    <transition name="pop">
                        <div v-if="isProfileOpen"
                            class="absolute right-0 mt-3 w-64 bg-white rounded-3xl shadow-[0_20px_50px_rgba(0,0,0,0.2)] border border-slate-100 py-2 z-[90] overflow-hidden">
                            <div class="px-5 py-4 border-b border-slate-50 mb-1 bg-slate-50/50">
                                <p class="text-[10px] text-slate-400 font-black uppercase tracking-[0.1em] mb-1">Active
                                    Session</p>
                                <p class="text-sm font-bold text-slate-900 truncate">{{ user.email }}</p>
                            </div>

                            <div class="p-2 space-y-1">
                                <a href="#"
                                    class="flex items-center gap-3 px-4 py-2.5 text-sm font-bold text-slate-600 hover:bg-slate-50 hover:text-slate-900 rounded-xl transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    Profile Settings
                                </a>
                                <button @click="logout"
                                    class="w-full flex items-center gap-3 px-4 py-2.5 text-sm font-bold text-red-500 hover:bg-red-50 rounded-xl transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    Sign Out
                                </button>
                            </div>
                        </div>
                    </transition>
                </div>
            </nav>

            <main class="p-4 md:p-8">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
                    <div v-for="n in 3" :key="n"
                        class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-slate-400 text-[10px] font-black uppercase tracking-[0.15em]">Stat
                                    Overview</p>
                                <h3 class="text-2xl md:text-3xl font-black text-slate-900 mt-1">Ready</h3>
                            </div>
                            <div
                                class="h-10 w-10 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-400">
                                0{{ n }}
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    class="mt-8 border-2 border-dashed border-slate-200 rounded-[2.5rem] h-64 flex items-center justify-center text-slate-400 font-bold italic">
                    Feature content area...
                </div>
            </main>
        </div>
    </div>
</template>

<style scoped>
/* Core Brand Colors & Shadows */
.shadow-red-500\/20 {
    box-shadow: 0 10px 15px -3px rgba(234, 68, 53, 0.2);
}

/* Success Toast Animation */
.slide-enter-active,
.slide-leave-active {
    transition: all 0.5s cubic-bezier(0.19, 1, 0.22, 1);
}

.slide-enter-from {
    transform: translateY(-120%);
    opacity: 0;
}

.slide-leave-to {
    transform: scale(0.9) translateY(-20px);
    opacity: 0;
}

/* Profile Dropdown Animation (Pop) */
.pop-enter-active,
.pop-leave-active {
    transition: all 0.25s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

.pop-enter-from {
    transform: translateY(-10px) scale(0.9);
    opacity: 0;
}

.pop-leave-to {
    transform: translateY(-5px) scale(0.95);
    opacity: 0;
}

/* Hide Scrollbar for cleaner look but keep scrolling functional */
aside::-webkit-scrollbar {
    width: 0px;
}
</style>