<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const isSidebarOpen = ref(false)
const isProfileOpen = ref(false)
const showSuccessNotif = ref(false)

const user = ref({ name: 'Admin', email: '' })

onMounted(() => {
    const savedUser = localStorage.getItem('user')
    if (savedUser) {
        user.value = JSON.parse(savedUser)
        showSuccessNotif.value = true
        setTimeout(() => { showSuccessNotif.value = false }, 4000)
    } else {
        router.push('/view/login')
    }
})

// Logic to handle logout
const handleLogout = () => {
    console.log("Logging out...") // Debugging check
    localStorage.removeItem('user')
    router.push('/view/login')
}

const toggleSidebar = () => {
    isSidebarOpen.value = !isSidebarOpen.value
}
</script>

<template>
    <div class="flex min-h-screen bg-slate-50 relative overflow-x-hidden">

        <div v-if="isSidebarOpen" @click="isSidebarOpen = false"
            class="fixed inset-0 bg-slate-900/50 z-[60] lg:hidden backdrop-blur-sm cursor-pointer">
        </div>

        <div v-if="isProfileOpen" @click="isProfileOpen = false" class="fixed inset-0 z-[85] bg-transparent"></div>

        <transition name="slide">
            <div v-if="showSuccessNotif"
                class="fixed top-4 right-4 left-4 md:left-auto z-[100] bg-slate-900 text-white px-6 py-4 rounded-2xl shadow-2xl flex items-center gap-4 border border-slate-700 md:w-80">
                <div
                    class="h-8 w-8 shrink-0 bg-emerald-500 rounded-full flex items-center justify-center font-bold text-white">
                    ✓</div>
                <div class="truncate">
                    <p class="font-bold text-sm leading-none">Login Successful</p>
                    <p class="text-xs text-slate-400 mt-1 truncate">Welcome back!</p>
                </div>
            </div>
        </transition>

        <aside :class="isSidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
            class="fixed lg:sticky top-0 inset-y-0 left-0 w-64 bg-slate-900 flex flex-col z-[70] transition-transform duration-300 ease-in-out h-screen">

            <div class="p-6 border-b border-slate-800 flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div class="h-8 w-8 bg-[#ea4435] rounded-lg flex items-center justify-center text-white font-black">
                        W</div>
                    <span class="text-white font-bold tracking-tight text-xl">Cakrawala</span>
                </div>
                <button @click="isSidebarOpen = false" class="lg:hidden text-slate-400 cursor-pointer hover:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <nav class="flex-1 p-4 space-y-2">
                <a href="#"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl bg-[#ea4435] text-white font-bold text-sm cursor-pointer shadow-lg shadow-red-500/20">Dashboard</a>
            </nav>

            <div class="p-4 mt-auto border-t border-slate-800">
                <button @click="handleLogout"
                    class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-red-500/10 hover:text-red-500 transition-all font-bold text-sm cursor-pointer">
                    Sign Out
                </button>
            </div>
        </aside>

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
                    <h2 class="text-base md:text-lg font-bold text-slate-900 truncate">Admin Overview</h2>
                </div>

                <div class="relative">
                    <button @click="isProfileOpen = !isProfileOpen"
                        class="flex items-center gap-3 md:gap-4 hover:bg-slate-50 p-1.5 rounded-2xl transition-all cursor-pointer outline-none border border-transparent active:scale-95 z-[90]">
                        <div class="text-right hidden sm:block">
                            <p class="text-sm font-black text-slate-900 leading-none">{{ user.name }}</p>
                            <p class="text-[10px] text-slate-400 mt-1 uppercase tracking-widest font-bold">Admin</p>
                        </div>
                        <img :src="`https://ui-avatars.com/api/?name=${user.name}&background=0f172a&color=fff&bold=true`"
                            class="h-10 w-10 rounded-full border-2 border-white shadow-sm shrink-0" />
                    </button>

                    <transition name="pop">
                        <div v-if="isProfileOpen"
                            class="absolute right-0 mt-3 w-60 bg-white rounded-2xl shadow-[0_20px_50px_rgba(0,0,0,0.15)] border border-slate-100 py-2 z-[95] overflow-hidden">
                            <div class="px-4 py-3 border-b border-slate-50 mb-1">
                                <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest">Active Email
                                </p>
                                <p class="text-sm font-bold text-slate-900 truncate">{{ user.email }}</p>
                            </div>

                            <button @click.stop="handleLogout"
                                class="w-full flex items-center gap-3 px-4 py-3 text-sm font-bold text-red-500 hover:bg-red-50 transition-colors cursor-pointer text-left">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                Sign Out
                            </button>
                        </div>
                    </transition>
                </div>
            </nav>

            <main class="p-4 md:p-8">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
                    <div v-for="n in 3" :key="n" class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
                        <p class="text-slate-400 text-[10px] font-black uppercase tracking-[0.2em]">Metric Overview</p>
                        <h3 class="text-2xl md:text-3xl font-black text-slate-900 mt-1 uppercase">Ready</h3>
                    </div>
                </div>
            </main>
        </div>
    </div>
</template>

<style scoped>
/* Animations */
.slide-enter-active,
.slide-leave-active {
    transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

.slide-enter-from {
    transform: translateY(-100%);
    opacity: 0;
}

.slide-leave-to {
    transform: scale(0.9);
    opacity: 0;
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
</style>