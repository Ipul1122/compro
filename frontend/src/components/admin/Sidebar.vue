<template>
    <div 
        v-if="isOpen" 
        @click="$emit('update:isOpen', false)"
        class="fixed inset-0 bg-black/60 z-40 lg:hidden transition-opacity"
    ></div>

    <aside :class="[
        'w-64 bg-slate-900 min-h-screen flex flex-col fixed lg:static z-50 transition-transform duration-300 ease-in-out',
        isOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'
    ]">
        <div class="p-6 border-b border-slate-800 flex justify-between items-center">
            <h2 class="text-white font-black text-xl uppercase tracking-tighter">Admin Panel</h2>
            <button @click="$emit('update:isOpen', false)" class="lg:hidden text-white hover:text-orange-600 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        
        <nav class="flex-grow p-4 space-y-2 overflow-y-auto custom-scrollbar">
            
            <router-link to="/admin/dashboard" class="flex items-center gap-3 px-4 py-3 text-white hover:text-orange-600 hover:bg-slate-800 rounded-xl transition-all font-bold">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Dashboard
            </router-link>

            <div>
                <button @click="toggleMenu('categories')" class="w-full flex items-center justify-between px-4 py-3 text-white hover:text-orange-600 hover:bg-slate-800 rounded-xl transition-all font-bold">
                    <div class="flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                        Kategori
                    </div>
                    <svg :class="{'rotate-180': openMenus.categories}" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div v-show="openMenus.categories" class="mt-1 space-y-1 pl-12 pr-4 overflow-hidden">
                    <router-link to="/admin/categories" class="block py-2 text-sm text-white hover:text-orange-600 hover:translate-x-1 transition-all duration-200">Index (List)</router-link>
                    <router-link to="/admin/categories/tambah" class="block py-2 text-sm text-white hover:text-orange-600 hover:translate-x-1 transition-all duration-200">Tambah</router-link>
                    <!-- <router-link to="/admin/categories/edit" class="block py-2 text-sm text-white hover:text-orange-600 hover:translate-x-1 transition-all duration-200">Edit</router-link> -->
                </div>
            </div>

            <div>
                <button @click="toggleMenu('articles')" class="w-full flex items-center justify-between px-4 py-3 text-white hover:text-orange-600 hover:bg-slate-800 rounded-xl transition-all font-bold">
                    <div class="flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                        </svg>
                        Artikel
                    </div>
                    <svg :class="{'rotate-180': openMenus.articles}" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div v-show="openMenus.articles" class="mt-1 space-y-1 pl-12 pr-4 overflow-hidden">
                    <router-link to="/admin/articles" class="block py-2 text-sm text-white hover:text-orange-600 hover:translate-x-1 transition-all duration-200">Index (List)</router-link>
                    <router-link to="/admin/articles/tambah" class="block py-2 text-sm text-white hover:text-orange-600 hover:translate-x-1 transition-all duration-200">Tambah</router-link>
                    <!-- <router-link to="/admin/articles/edit" class="block py-2 text-sm text-white hover:text-orange-600 hover:translate-x-1 transition-all duration-200">Edit</router-link> --> 
                </div>
            </div>

            <div>
                <button @click="toggleMenu('gallery')" class="w-full flex items-center justify-between px-4 py-3 text-white hover:text-orange-600 hover:bg-slate-800 rounded-xl transition-all font-bold">
                    <div class="flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Galeri
                    </div>
                    <svg :class="{'rotate-180': openMenus.gallery}" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div v-show="openMenus.gallery" class="mt-1 space-y-1 pl-12 pr-4 overflow-hidden">
                    <router-link to="/admin/gallery" class="block py-2 text-sm text-white hover:text-orange-600 hover:translate-x-1 transition-all duration-200">Index (List)</router-link>
                    <router-link to="/admin/gallery/tambah" class="block py-2 text-sm text-white hover:text-orange-600 hover:translate-x-1 transition-all duration-200">Tambah</router-link>
                    <!--  <router-link to="/admin/gallery/edit" class="block py-2 text-sm text-white hover:text-orange-600 hover:translate-x-1 transition-all duration-200">Edit</router-link> --> 
                </div>
            </div>
            
            <button @click="$emit('logout')" class="w-full flex items-center gap-3 px-4 py-3 text-red-400 hover:text-orange-600 hover:bg-red-600/20 rounded-xl transition-all font-bold mt-10">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                Logout
            </button>
        </nav>
    </aside>
</template>

<script setup>
import { ref } from 'vue'

const props = defineProps({
    isOpen: {
        type: Boolean,
        default: false
    },
    currentView: {
        type: String,
        default: 'dashboard'
    }
})

const emit = defineEmits(['update:isOpen', 'update:currentView', 'logout'])

// State reaktif untuk melacak dropdown mana saja yang sedang terbuka
const openMenus = ref({
    categories: false,
    articles: false,
    gallery: false
})

// Fungsi untuk membuka / menutup dropdown
const toggleMenu = (menu) => {
    // Opsional: Jika Anda ingin menutup menu lain saat satu menu dibuka, aktifkan kode di bawah ini:
    // Object.keys(openMenus.value).forEach(key => {
    //     if (key !== menu) openMenus.value[key] = false
    // })

    openMenus.value[menu] = !openMenus.value[menu]
}

const changeView = (view) => {
    emit('update:currentView', view)
    emit('update:isOpen', false)
}
</script>

<style scoped>
button,
.cursor-pointer {
    cursor: pointer !important;
}

/* Custom Scrollbar khusus untuk Sidebar */
.custom-scrollbar::-webkit-scrollbar {
    width: 4px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #334155; 
    border-radius: 10px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #475569; 
}
</style>