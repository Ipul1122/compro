<template>
    <div 
        v-if="isOpen" 
        @click="$emit('update:isOpen', false)"
        class="fixed inset-0 bg-slate-950/70 backdrop-blur-sm z-40 lg:hidden transition-opacity duration-300"
    ></div>

    <!-- Spacer untuk Desktop agar main content tidak tergeser ke kiri saat aside menjadi fixed -->
    <div class="hidden lg:block w-72 shrink-0"></div>

    <aside :class="[
        'w-72 fixed inset-y-0 left-0 h-[100dvh] z-50 transition-transform duration-300 ease-in-out shadow-2xl',
        isOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'
    ]">
        
        <div class="relative w-full h-full flex flex-col bg-gradient-to-b from-slate-900 via-slate-900 to-slate-950 border-r border-slate-800/60 overflow-hidden">
            
            <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 rounded-full bg-orange-500/5 blur-[80px] pointer-events-none"></div>
            <div class="absolute bottom-1/3 left-0 -ml-16 w-64 h-64 rounded-full bg-blue-500/5 blur-[80px] pointer-events-none"></div>

            <div class="p-6 border-b border-slate-800/60 flex justify-between items-center relative z-10 bg-slate-900/50 backdrop-blur-md shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-lg shadow-blue-500/20">
                        <span class="text-white font-bold text-lg leading-none">D</span>
                    </div>
                    <h2 class="text-white font-black text-xl uppercase tracking-widest bg-clip-text text-transparent bg-gradient-to-r from-white to-slate-400">
                        Direktur
                    </h2>
                </div>
            </div>
            
            <nav class="flex-1 p-4 space-y-1.5 overflow-y-auto custom-scrollbar relative z-10">
                
                <router-link to="/direktur/dashboard" class="group flex items-center gap-3 px-4 py-3 text-slate-300 hover:text-white hover:bg-slate-800/50 rounded-xl transition-all font-medium border border-transparent hover:border-slate-700/50 relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-r from-orange-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400 group-hover:text-orange-500 transition-colors relative z-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span class="relative z-10">Dashboard</span>
                </router-link>

                <div class="pt-2">
                    <button @click="toggleMenu('categories')" class="group w-full flex items-center justify-between px-4 py-3 text-slate-300 hover:text-white hover:bg-slate-800/50 rounded-xl transition-all font-medium border border-transparent hover:border-slate-700/50 relative overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-r from-orange-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <div class="flex items-center gap-3 relative z-10">
                            <svg xmlns="http://www.w3.org/2000/svg" :class="['h-5 w-5 transition-colors', openMenus.categories ? 'text-orange-500' : 'text-slate-400 group-hover:text-orange-500']" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                            <span>Kategori</span>
                        </div>
                        <svg :class="{'rotate-180 text-orange-500': openMenus.categories, 'text-slate-500': !openMenus.categories}" class="w-4 h-4 transition-all duration-300 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <Transition name="slide-fade">
                        <div v-show="openMenus.categories" class="mt-1 space-y-1 pl-11 pr-4 relative">
                            <div class="absolute left-6 top-2 bottom-2 w-px bg-slate-800 rounded-full"></div>
                            <router-link to="/direktur/categories" class="group/sub relative flex items-center py-2.5 text-sm text-slate-400 hover:text-white transition-colors">
                                <div class="absolute -left-5 w-3 h-px bg-slate-800 group-hover/sub:bg-orange-500 transition-colors"></div>
                                <span class="group-hover/sub:translate-x-1 transition-transform duration-200">Index (List)</span>
                            </router-link>
                            <router-link to="/direktur/categories/tambah" class="group/sub relative flex items-center py-2.5 text-sm text-slate-400 hover:text-white transition-colors">
                                <div class="absolute -left-5 w-3 h-px bg-slate-800 group-hover/sub:bg-orange-500 transition-colors"></div>
                                <span class="group-hover/sub:translate-x-1 transition-transform duration-200">Tambah Kategori</span>
                            </router-link>
                        </div>
                    </Transition>
                </div>

                <div class="pt-1">
                    <button @click="toggleMenu('employees')" class="group w-full flex items-center justify-between px-4 py-3 text-slate-300 hover:text-white hover:bg-slate-800/50 rounded-xl transition-all font-medium border border-transparent hover:border-slate-700/50 relative overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-r from-orange-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <div class="flex items-center gap-3 relative z-10">
                            <svg xmlns="http://www.w3.org/2000/svg" :class="['h-5 w-5 transition-colors', openMenus.employees ? 'text-orange-500' : 'text-slate-400 group-hover:text-orange-500']" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-3-3h-1M4 20h5v-2a3 3 0 00-3-3H5m9-4a4 4 0 10-8 0 4 4 0 008 0zm4 0a4 4 0 10-8 0 4 4 0 008 0z" />
                            </svg>
                            <span>Karyawan</span>
                        </div>
                        <svg :class="{'rotate-180 text-orange-500': openMenus.employees, 'text-slate-500': !openMenus.employees}" class="w-4 h-4 transition-all duration-300 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <Transition name="slide-fade">
                        <div v-show="openMenus.employees" class="mt-1 space-y-1 pl-11 pr-4 relative">
                            <div class="absolute left-6 top-2 bottom-2 w-px bg-slate-800 rounded-full"></div>
                            <router-link to="/direktur/karyawan" class="group/sub relative flex items-center py-2.5 text-sm text-slate-400 hover:text-white transition-colors">
                                <div class="absolute -left-5 w-3 h-px bg-slate-800 group-hover/sub:bg-orange-500 transition-colors"></div>
                                <span class="group-hover/sub:translate-x-1 transition-transform duration-200">Index (List)</span>
                            </router-link>
                        </div>
                    </Transition>
                </div>

                <div class="pt-1">
                    <button @click="toggleMenu('articles')" class="group w-full flex items-center justify-between px-4 py-3 text-slate-300 hover:text-white hover:bg-slate-800/50 rounded-xl transition-all font-medium border border-transparent hover:border-slate-700/50 relative overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-r from-orange-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <div class="flex items-center gap-3 relative z-10">
                            <svg xmlns="http://www.w3.org/2000/svg" :class="['h-5 w-5 transition-colors', openMenus.articles ? 'text-orange-500' : 'text-slate-400 group-hover:text-orange-500']" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                            </svg>
                            <span>Artikel</span>
                        </div>
                        <svg :class="{'rotate-180 text-orange-500': openMenus.articles, 'text-slate-500': !openMenus.articles}" class="w-4 h-4 transition-all duration-300 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <Transition name="slide-fade">
                        <div v-show="openMenus.articles" class="mt-1 space-y-1 pl-11 pr-4 relative">
                            <div class="absolute left-6 top-2 bottom-2 w-px bg-slate-800 rounded-full"></div>
                            <router-link to="/direktur/articles" class="group/sub relative flex items-center py-2.5 text-sm text-slate-400 hover:text-white transition-colors">
                                <div class="absolute -left-5 w-3 h-px bg-slate-800 group-hover/sub:bg-orange-500 transition-colors"></div>
                                <span class="group-hover/sub:translate-x-1 transition-transform duration-200">Index (List)</span>
                            </router-link>
                            <router-link to="/direktur/articles/create" class="group/sub relative flex items-center py-2.5 text-sm text-slate-400 hover:text-white transition-colors">
                                <div class="absolute -left-5 w-3 h-px bg-slate-800 group-hover/sub:bg-orange-500 transition-colors"></div>
                                <span class="group-hover/sub:translate-x-1 transition-transform duration-200">Tambah Artikel</span>
                            </router-link>
                        </div>
                    </Transition>
                </div>

                <div class="pt-1 pb-4">
                    <button @click="toggleMenu('gallery')" class="group w-full flex items-center justify-between px-4 py-3 text-slate-300 hover:text-white hover:bg-slate-800/50 rounded-xl transition-all font-medium border border-transparent hover:border-slate-700/50 relative overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-r from-orange-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <div class="flex items-center gap-3 relative z-10">
                            <svg xmlns="http://www.w3.org/2000/svg" :class="['h-5 w-5 transition-colors', openMenus.gallery ? 'text-orange-500' : 'text-slate-400 group-hover:text-orange-500']" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span>Galeri</span>
                        </div>
                        <svg :class="{'rotate-180 text-orange-500': openMenus.gallery, 'text-slate-500': !openMenus.gallery}" class="w-4 h-4 transition-all duration-300 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <Transition name="slide-fade">
                        <div v-show="openMenus.gallery" class="mt-1 space-y-1 pl-11 pr-4 relative">
                            <div class="absolute left-6 top-2 bottom-2 w-px bg-slate-800 rounded-full"></div>
                            <router-link to="/direktur/gallery" class="group/sub relative flex items-center py-2.5 text-sm text-slate-400 hover:text-white transition-colors">
                                <div class="absolute -left-5 w-3 h-px bg-slate-800 group-hover/sub:bg-orange-500 transition-colors"></div>
                                <span class="group-hover/sub:translate-x-1 transition-transform duration-200">Index (List)</span>
                            </router-link>
                            <router-link to="/direktur/gallery/tambah" class="group/sub relative flex items-center py-2.5 text-sm text-slate-400 hover:text-white transition-colors">
                                <div class="absolute -left-5 w-3 h-px bg-slate-800 group-hover/sub:bg-orange-500 transition-colors"></div>
                                <span class="group-hover/sub:translate-x-1 transition-transform duration-200">Tambah Galeri</span>
                            </router-link>
                        </div>
                    </Transition>
                </div>

            </nav>

            <div class="p-4 border-t border-slate-800/60 bg-slate-900/80 backdrop-blur-md relative z-20 mt-auto shrink-0">
                <button @click="$emit('logout')" class="group w-full flex items-center justify-center gap-2.5 px-4 py-3.5 text-red-400 hover:text-white bg-red-500/10 hover:bg-red-500 rounded-xl transition-all duration-300 font-bold border border-red-500/20 hover:border-red-500 hover:shadow-lg hover:shadow-red-500/25">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-hover:-translate-x-1 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span>Logout Session</span>
                </button>
            </div>
        </div>

        <button 
            @click="$emit('update:isOpen', !isOpen)"
            class="lg:hidden absolute top-1/2 -right-10 w-10 h-24 bg-slate-900/95 backdrop-blur-md border-y border-r border-slate-800/60 rounded-r-2xl flex items-center justify-center text-slate-400 hover:text-orange-500 shadow-[8px_0_20px_-5px_rgba(0,0,0,0.6)] z-[60] -translate-y-1/2 transition-all duration-300 active:scale-95"
            aria-label="Toggle Sidebar"
        >
            <svg 
                xmlns="http://www.w3.org/2000/svg" 
                :class="['h-6 w-6 transition-transform duration-500', isOpen ? 'rotate-180 text-orange-500' : '']" 
                fill="none" 
                viewBox="0 0 24 24" 
                stroke="currentColor"
            >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
            </svg>
        </button>

    </aside>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const props = defineProps({
    isOpen: { type: Boolean, default: false },
    currentView: { type: String, default: 'dashboard' }
})

const emit = defineEmits(['update:isOpen', 'update:currentView', 'logout'])

const user = ref({ role: '' })
const openMenus = ref({
    categories: false,
    articles: false,
    gallery: false,
    employees: false
})

onMounted(() => {
    const savedUser = sessionStorage.getItem('user')
    if (savedUser) {
        try {
            user.value = JSON.parse(savedUser)
        } catch (err) {
            console.error('Gagal mem-parsing user dari sessionStorage', err)
        }
    }
})

const toggleMenu = (menu) => {
    Object.keys(openMenus.value).forEach(key => {
         if (key !== menu) openMenus.value[key] = false
    })
    openMenus.value[menu] = !openMenus.value[menu]
}
</script>

<style scoped>
/* Scrollbar Kustom */
.custom-scrollbar::-webkit-scrollbar {
    width: 4px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: rgba(51, 65, 85, 0.4);
    border-radius: 10px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: rgba(71, 85, 105, 0.8);
}

/* Transisi Buka/Tutup Sub-Menu */
.slide-fade-enter-active,
.slide-fade-leave-active {
  transition: all 0.3s ease-out;
  max-height: 200px;
  opacity: 1;
  overflow: hidden;
}
.slide-fade-enter-from,
.slide-fade-leave-to {
  max-height: 0;
  opacity: 0;
  padding-top: 0;
  padding-bottom: 0;
  margin-top: 0;
  margin-bottom: 0;
}
</style>
