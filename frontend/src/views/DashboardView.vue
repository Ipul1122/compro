<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const showSuccessNotif = ref(false)
const user = ref({ name: 'Admin', email: '' })

onMounted(() => {
    // 1. Retrieve the real user from storage
    const savedUser = localStorage.getItem('user')

    if (savedUser) {
        user.value = JSON.parse(savedUser)

        // 2. Show welcome notification
        showSuccessNotif.value = true
        setTimeout(() => { showSuccessNotif.value = false }, 4000)
    } else {
        // 3. Kick out if not logged in
        router.push('/view/login')
    }
})

const logout = () => {
    localStorage.removeItem('user')
    router.push('/view/login')
}
</script>

<template>
    <div class="flex min-h-screen bg-slate-50 relative">

        <transition name="slide">
            <div v-if="showSuccessNotif"
                class="fixed top-6 right-6 z-[100] bg-slate-900 text-white px-6 py-4 rounded-2xl shadow-2xl flex items-center gap-4 border border-slate-700">
                <div class="h-8 w-8 bg-emerald-500 rounded-full flex items-center justify-center font-bold">✓</div>
                <div>
                    <p class="font-bold text-sm leading-none">Login Successful</p>
                    <p class="text-xs text-slate-400 mt-1">Welcome back, {{ user.name }}!</p>
                </div>
            </div>
        </transition>

        <aside class="w-64 bg-slate-900 flex flex-col sticky top-0 h-screen">
            <div class="p-6 border-b border-slate-800">
                <div class="flex items-center gap-3">
                    <div class="h-8 w-8 bg-brand rounded-lg flex items-center justify-center text-white font-black">W
                    </div>
                    <span class="text-white font-bold tracking-tight text-xl">Cakrawala</span>
                </div>
            </div>

            <nav class="flex-1 p-4 space-y-2">
                <a href="#"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl bg-brand text-white shadow-lg shadow-brand/20 font-bold text-sm">Dashboard</a>
                <a href="#"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800 hover:text-white transition-all font-bold text-sm">Content</a>
            </nav>

            <div class="p-4 mt-auto">
                <button @click="logout"
                    class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-red-500/10 hover:text-red-500 transition-all font-bold text-sm">
                    Sign Out
                </button>
            </div>
        </aside>

        <div class="flex-1 flex flex-col">
            <nav
                class="h-20 bg-white border-b border-slate-100 px-8 flex items-center justify-between sticky top-0 z-10">
                <h2 class="text-lg font-bold text-slate-900">Admin Overview</h2>
                <div class="flex items-center gap-4">
                    <div class="text-right">
                        <p class="text-sm font-bold text-slate-900">{{ user.name }}</p>
                        <p class="text-xs text-slate-400">{{ user.email }}</p>
                    </div>
                    <img :src="`https://ui-avatars.com/api/?name=${user.name}&background=0f172a&color=fff`"
                        class="h-10 w-10 rounded-full border border-slate-100" />
                </div>
            </nav>

            <main class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div v-for="n in 3" :key="n" class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
                        <p class="text-slate-400 text-xs font-bold uppercase tracking-widest">Metric 0{{ n }}</p>
                        <h3 class="text-3xl font-black text-slate-900 mt-1">Ready</h3>
                    </div>
                </div>
            </main>
        </div>
    </div>
</template>

<style scoped>
.bg-brand {
    background-color: #ea4435;
}

.shadow-brand\/20 {
    box-shadow: 0 10px 15px -3px rgba(234, 68, 53, 0.2);
}

.slide-enter-active,
.slide-leave-active {
    transition: all 0.4s ease;
}

.slide-enter-from {
    transform: translateX(100%);
    opacity: 0;
}

.slide-leave-to {
    transform: translateY(-20px);
    opacity: 0;
}
</style>