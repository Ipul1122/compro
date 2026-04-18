<template>
    <aside class="w-64 bg-slate-900 min-h-screen flex flex-col">
        <div class="p-6 border-b border-slate-800">
            <h2 class="text-white font-black text-xl uppercase tracking-tighter">Admin Panel</h2>
        </div>
        
        <nav class="flex-grow p-4 space-y-2">
            <router-link to="/admin/dashboard" class="flex items-center gap-3 px-4 py-3 text-slate-400 hover:text-white hover:bg-slate-800 rounded-xl transition-all font-bold">
                Dashboard
            </router-link>

            <router-link to="/admin/categories" class="flex items-center gap-3 px-4 py-3 text-slate-400 hover:text-white hover:bg-slate-800 rounded-xl transition-all font-bold">
                Kategori
            </router-link>

            <router-link to="/admin/articles" class="flex items-center gap-3 px-4 py-3 text-slate-400 hover:text-white hover:bg-slate-800 rounded-xl transition-all font-bold">
                Artikel
            </router-link>

            <router-link to="/admin/gallery" class="flex items-center gap-3 px-4 py-3 text-slate-400 hover:text-white hover:bg-slate-800 rounded-xl transition-all font-bold">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                Galeri
            </router-link>
            
            <button @click="logout" class="w-full flex items-center gap-3 px-4 py-3 text-red-400 hover:text-white hover:bg-red-600/20 rounded-xl transition-all font-bold mt-10">
                Logout
            </button>
        </nav>
    </aside>
</template>


<script setup>
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