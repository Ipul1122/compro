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

<<template>
    <div class="flex min-h-screen bg-slate-50 relative overflow-x-hidden">

        <div v-if="isSidebarOpen" @click="isSidebarOpen = false"
            class="fixed inset-0 bg-slate-900/50 z-[60] lg:hidden backdrop-blur-sm">
        </div>

        <transition name="slide">
            <div v-if="showSuccessNotif"
                class="fixed top-4 right-4 left-4 md:left-auto z-[100] bg-slate-900 text-white px-6 py-4 rounded-2xl shadow-2xl flex items-center gap-4 border border-slate-700">
                <div class="h-8 w-8 shrink-0 bg-emerald-500 rounded-full flex items-center justify-center font-bold">✓
                </div>
                <div>
                    <p class="font-bold text-sm leading-none">Login Successful</p>
                    <p class="text-xs text-slate-400 mt-1">Welcome, {{ user.name }}!</p>
                </div>
            </div>
        </transition>

        <aside :class="isSidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
            class="fixed lg:sticky top-0 inset-y-0 left-0 w-64 bg-slate-900 flex flex-col z-[70] transition-transform duration-300 ease-in-out h-screen">

            <div class="p-6 border-b border-slate-800 flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div class="h-8 w-8 bg-brand rounded-lg flex items-center justify-center text-white font-black">W
                    </div>
                    <span class="text-white font-bold tracking-tight text-xl">Cakrawala</span>
                </div>
                <button @click="isSidebarOpen = false" class="lg:hidden text-slate-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <nav class="flex-1 p-4 space-y-2">
                <a href="#"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl bg-brand text-white shadow-lg shadow-brand/20 font-bold text-sm">Dashboard</a>
                <a href="#"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800 hover:text-white transition-all font-bold text-sm">Content</a>
            </nav>

            <div class="p-4 mt-auto">
                <button
                    class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-red-500/10 hover:text-red-500 transition-all font-bold text-sm">Sign
                    Out</button>
            </div>
        </aside>

        <div class="flex-1 flex flex-col min-w-0">

            <nav
                class="h-20 bg-white border-b border-slate-100 px-4 md:px-8 flex items-center justify-between sticky top-0 z-50">
                <div class="flex items-center gap-4">
                    <button @click="toggleSidebar" class="lg:hidden p-2 text-slate-600 hover:bg-slate-50 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <h2 class="text-base md:text-lg font-bold text-slate-900 truncate">Admin Overview</h2>
                </div>

                <div class="flex items-center gap-3 md:gap-4">
                    <div class="text-right hidden xs:block">
                        <p class="text-sm font-bold text-slate-900 leading-none">{{ user.name }}</p>
                        <p class="text-[10px] md:text-xs text-slate-400 mt-1 uppercase tracking-tighter">Admin</p>
                    </div>
                    <img :src="`https://ui-avatars.com/api/?name=${user.name}&background=0f172a&color=fff`"
                        class="h-9 w-9 md:h-10 md:w-10 rounded-full border border-slate-100" />
                </div>
            </nav>

            <main class="p-4 md:p-8">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
                    <div v-for="n in 3" :key="n" class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
                        <p class="text-slate-400 text-xs font-bold uppercase tracking-widest">Metric 0{{ n }}</p>
                        <h3 class="text-2xl md:text-3xl font-black text-slate-900 mt-1">Ready</h3>
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

    /* Animation fixed for mobile */
    .slide-enter-active,
    .slide-leave-active {
        transition: all 0.4s ease;
    }

    .slide-enter-from {
        transform: translateY(-100%);
        opacity: 0;
    }

    .slide-leave-to {
        transform: scale(0.9);
        opacity: 0;
    }
</style>