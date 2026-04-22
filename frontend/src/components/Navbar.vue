<script setup>
import { ref, computed } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useI18n } from 'vue-i18n'

const isMenuOpen = ref(false)
const router = useRouter()
const route = useRoute()

const { locale } = useI18n()
const isIndonesian = computed(() => locale.value === 'id')

const handleLanguageToggle = () => {
    if (locale.value === 'id') {
        locale.value = 'en'
    } else {
        locale.value = 'id'
    }
}
</script>

<template>
    <nav class="fixed top-4 left-0 right-0 z-50 w-full px-4 font-sans">
        <div class="max-w-7xl mx-auto">
            <div class="bg-white/90 backdrop-blur-xl border border-slate-100 rounded-2xl 
                  shadow-[0_20px_50px_rgba(0,0,0,0.05)] 
                  transition-all duration-500 
                  hover:shadow-[0_20px_50px_rgba(234,68,53,0.15)]
                  group">

                <div class="px-6 md:px-8">
                    <div class="flex items-center justify-between h-16">

                        <div class="flex-shrink-0 flex items-center">
                            <a href="#home" class="hover:opacity-80 transition-opacity">
                                <img src="/src/img/webcakrawala-logo.png" alt="Logo" class="h-13 w-auto" />
                            </a>
                        </div>

                        <div class="hidden md:flex items-center gap-1">
                            <a href="/#home"
                                class="text-slate-600 hover:text-brand px-4 py-2 text-sm font-bold tracking-wide transition-all hover:bg-slate-50 rounded-xl">Home</a>

                            <router-link to="/about"
                                class="text-slate-600 hover:text-brand px-4 py-2 text-sm font-bold tracking-wide transition-all hover:bg-slate-50 rounded-xl">About
                                Us</router-link>

                            <router-link to="/project"
                                class="text-slate-600 hover:text-brand px-4 py-2 text-sm font-bold tracking-wide transition-all hover:bg-slate-50 rounded-xl">Our
                                Projects</router-link>

                            <router-link to="/articles"
                                class="text-slate-600 hover:text-brand px-4 py-2 text-sm font-bold tracking-wide transition-all hover:bg-slate-50 rounded-xl">Articles</router-link>

                            <router-link to="/galerry"
                                class="text-slate-600 hover:text-brand px-4 py-2 text-sm font-bold tracking-wide transition-all hover:bg-slate-50 rounded-xl">Gallery</router-link>

                            <a href="/#our-services"
                                class="text-slate-600 hover:text-brand px-4 py-2 text-sm font-bold tracking-wide transition-all hover:bg-slate-50 rounded-xl">Services</a>

                            <a href="/#clients"
                                class="text-slate-600 hover:text-brand px-4 py-2 text-sm font-bold tracking-wide transition-all hover:bg-slate-50 rounded-xl">Clients</a>
                            
                            <button @click="handleLanguageToggle"
                                class="ml-2 flex items-center justify-center w-10 h-10 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 hover:border-brand hover:text-brand transition-all active:scale-95">
                                <span class="text-xs font-black">{{ isIndonesian ? 'EN' : 'ID' }}</span>
                            </button>

                            <a href="#contact"
                                class="ml-4 bg-slate-900 text-white hover:bg-brand px-6 py-2.5 rounded-xl text-sm font-black shadow-lg hover:shadow-brand/30 transition-all active:scale-95 flex items-center gap-2">
                                Contact <span class="text-xs opacity-50">→</span>
                            </a>
                        </div>

                        <div class="md:hidden flex items-center">
                            <button @click="isMenuOpen = !isMenuOpen"
                                class="p-2 text-slate-600 hover:bg-slate-100 rounded-lg transition-colors">
                                <svg v-if="!isMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6h16M4 12h16m-7 6h7"></path>
                                </svg>
                                <svg v-else class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div v-show="isMenuOpen"
                    class="md:hidden border-t border-slate-100 p-4 space-y-2 bg-white rounded-b-2xl">
                    <a href="#home" @click="isMenuOpen = false"
                        class="block px-4 py-3 text-slate-600 hover:bg-slate-50 hover:text-brand rounded-xl text-sm font-bold transition-all">Home</a>
                    <a href="#our-project" @click="isMenuOpen = false"
                        class="block px-4 py-3 text-slate-600 hover:bg-slate-50 hover:text-brand rounded-xl text-sm font-bold transition-all">Our
                        Project</a>
                    
                    <a href="#gallery" @click="isMenuOpen = false"
                        class="block px-4 py-3 text-slate-600 hover:bg-slate-50 hover:text-brand rounded-xl text-sm font-bold transition-all">Gallery</a>

                    <a href="#psikotest" @click="isMenuOpen = false"
                        class="block px-4 py-3 text-slate-600 hover:bg-slate-50 hover:text-brand rounded-xl text-sm font-bold transition-all">Psikotest</a>
                    <a href="#our-services" @click="isMenuOpen = false"
                        class="block px-4 py-3 text-slate-600 hover:bg-slate-50 hover:text-brand rounded-xl text-sm font-bold transition-all">Service</a>
                    <a href="#clients" @click="isMenuOpen = false"
                        class="block px-4 py-3 text-slate-600 hover:bg-slate-50 hover:text-brand rounded-xl text-sm font-bold transition-all">Clients</a>
                    
                    <div class="pt-2 pb-2 border-t border-slate-50 flex items-center justify-between px-4">
                        <span class="text-sm font-bold text-slate-500">Switch Language</span>
                        <button @click="handleLanguageToggle"
                            class="flex items-center justify-center w-12 h-10 rounded-xl border border-slate-200 text-slate-600 active:bg-slate-50">
                            <span class="text-xs font-black">{{ isIndonesian ? 'EN' : 'ID' }}</span>
                        </button>
                    </div>
                    <a href="#contact" @click="isMenuOpen = false"
                        class="block px-4 py-3 text-brand rounded-xl text-sm font-black transition-all">Contact</a>
                </div>
            </div>
        </div>
    </nav>
</template>

<style scoped>
:global(html) {
    scroll-behavior: smooth;
    scroll-padding-top: 6rem;
}

.text-brand {
    color: #ea4435;
}

.hover\:bg-brand:hover {
    background-color: #ea4435;
}

.hover\:text-brand:hover {
    color: #ea4435;
}

.hover\:shadow-brand\/30:hover {
    box-shadow: 0 10px 15px -3px rgba(234, 68, 53, 0.3);
}

a:active {
    transform: translateY(1px);
}
</style>