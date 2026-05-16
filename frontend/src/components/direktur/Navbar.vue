<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router' //
import Api from '@/api' //

const props = defineProps({
    user: {
        type: Object,
        default: () => ({ name: 'Direktur', email: '', role: 'direktur' })
    },
    breadcrumbs: {
        type: Array,
        default: () => [{ label: 'Overview', link: null }]
    }
})

// Tetap deklarasikan 'logout' di emits untuk menghilangkan Vue Warn, 
// meskipun kita menangani logikanya langsung di sini.
const emit = defineEmits(['toggle-sidebar', 'logout']) 

const router = useRouter()
const isProfileOpen = ref(false)

const handleLogout = async () => {
    try {
        // 1. Panggil API Logout ke backend (menggunakan token yang ada di interceptor)
        await Api.post('/logout') //
    } catch (error) {
        console.error('Logout error:', error)
    } finally {
        // 2. Hapus data autentikasi dari penyimpanan lokal
        sessionStorage.removeItem('token') //
        sessionStorage.removeItem('user')

        // 3. Arahkan kembali ke halaman login
        router.push({ name: 'login' }) //
        
        // 4. (Opsional) Beritahu parent jika perlu
        emit('logout') 
    }
}
</script>

<template>
    <div
        v-if="isProfileOpen"
        @click="isProfileOpen = false"
        class="fixed inset-0 z-[100] bg-transparent"
    ></div>

    <nav class="h-20 bg-white border-b border-slate-100 px-4 md:px-8 flex items-center justify-between sticky top-0 z-[50]">
        <div class="flex items-center gap-4 min-w-0">
            <button @click="$emit('toggle-sidebar')" class="lg:hidden p-2 text-slate-600 hover:bg-slate-50 rounded-lg cursor-pointer shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <nav class="flex items-center text-sm md:text-base font-bold overflow-hidden">
                <div v-for="(item, index) in breadcrumbs" :key="index" class="flex items-center whitespace-nowrap">
                    <span v-if="index > 0" class="mx-2 text-slate-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </span>
                    <span :class="index === breadcrumbs.length - 1 ? 'text-slate-900' : 'text-slate-400 font-medium'" 
                          class="uppercase tracking-tight truncate max-w-[100px] md:max-w-none">
                        {{ item.label }}
                    </span>
                </div>
            </nav>
        </div>

        <div class="relative z-[110] flex items-center gap-2">
            <button @click="isProfileOpen = !isProfileOpen" class="flex items-center gap-3 md:gap-4 hover:bg-slate-50 p-1.5 rounded-2xl transition-all cursor-pointer outline-none shrink-0">
                <div class="text-right hidden sm:block">
                    <p class="text-sm font-black text-slate-900 leading-none">{{ user?.name }}</p>
                    <p class="text-[10px] text-slate-400 mt-1 uppercase tracking-widest font-bold">{{ user?.role === 'direktur' ? 'Direktur' : user?.role === 'admin' ? 'Admin' : user?.role }}</p>
                </div>
                <img :src="`https://ui-avatars.com/api/?name=${user?.name}&background=0f172a&color=fff&bold=true`" class="h-10 w-10 rounded-full border-2 border-white shadow-sm shrink-0" />
            </button>

            <transition name="pop">
                <div v-if="isProfileOpen" class="absolute right-0 mt-3 w-60 bg-white rounded-2xl shadow-[0_20px_50px_rgba(0,0,0,0.15)] border border-slate-100 py-2 z-[120]">
                    <div class="px-4 py-3 border-b border-slate-50 mb-1">
                        <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest">Active Email</p>
                        <p class="text-sm font-bold text-slate-900 truncate">{{ user.email }}</p>
                    </div>
                    
                    <!-- <button @click="handleLogout" class="w-full flex items-center gap-3 px-4 py-3 text-sm font-bold text-red-500 hover:bg-red-50 text-left cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Sign Out
                    </button> -->
                </div>
            </transition>
        </div>
    </nav>
</template>

<style scoped>
.pop-enter-active, .pop-leave-active { transition: all 0.2s ease-out; }
.pop-enter-from, .pop-leave-to { transform: translateY(-10px) scale(0.95); opacity: 0; }
</style>
