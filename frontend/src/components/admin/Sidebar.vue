<template>
    <div v-if="isOpen" @click="$emit('update:isOpen', false)" class="fixed inset-0 bg-slate-900/50 z-[60] lg:hidden backdrop-blur-sm cursor-pointer"></div>

    <aside :class="isOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
        class="fixed lg:sticky top-0 inset-y-0 left-0 w-64 bg-slate-900 flex flex-col z-[70] transition-transform duration-300 ease-in-out h-screen">
        <div class="p-6 border-b border-slate-800 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="h-8 w-8 bg-[#ea4435] rounded-lg flex items-center justify-center text-white font-black">
                    W</div>
                <span class="text-white font-bold tracking-tight text-xl">Cakrawala</span>
            </div>
        </div>

        <nav class="flex-1 p-4 space-y-2 overflow-y-auto custom-scrollbar">
            <button @click="changeView('dashboard')"
                :class="currentView === 'dashboard' ? 'bg-[#ea4435] text-white' : 'text-slate-400 hover:bg-slate-800'"
                class="w-full flex items-center gap-3 px-4 py-3 rounded-xl font-bold text-sm transition-all cursor-pointer">
                Dashboard
            </button>
            <button @click="changeView('articles')"
                :class="currentView === 'articles' ? 'bg-[#ea4435] text-white' : 'text-slate-400 hover:bg-slate-800'"
                class="w-full flex items-center gap-3 px-4 py-3 rounded-xl font-bold text-sm transition-all cursor-pointer">
                Articles
            </button>
            <button @click="changeView('categories')"
                :class="currentView === 'categories' ? 'bg-[#ea4435] text-white' : 'text-slate-400 hover:bg-slate-800'"
                class="w-full flex items-center gap-3 px-4 py-3 rounded-xl font-bold text-sm transition-all cursor-pointer">
                Categories
            </button>
        </nav>

        <div class="p-4 mt-auto border-t border-slate-800">
            <button @click="$emit('logout')"
                class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-red-500/10 hover:text-red-500 transition-all font-bold text-sm cursor-pointer">
                Sign Out
            </button>
        </div>
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