<script setup>
import { ref, computed, nextTick } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useI18n } from 'vue-i18n'

const isMenuOpen = ref(false)
const isMobileServiceOpen = ref(false)
const router = useRouter()
const route = useRoute()

const { t, locale } = useI18n()
const isIndonesian = computed(() => locale.value === 'id')

// 1. Buat mapping URL untuk EN dan ID
const routePaths = {
    home: { en: '/en/home', id: '/id/beranda' },
    about: { en: '/en/about', id: '/id/tentang-kami' },
    project: { en: '/en/projects', id: '/id/proyek-kami' },
    articles: { en: '/en/articles', id: '/id/artikel' },
    gallery: { en: '/en/gallery', id: '/id/galeri' },
    service: { en: '/en/services', id: '/id/layanan' },
    InteriorDesign: { en: '/en/interior-design', id: '/id/interior-design' },
    assessment: { en: '/en/assessment', id: '/id/assessment' },
    contact: { en: '/en/contact', id: '/id/kontak' },
}

const handleLanguageToggle = async () => {
    const targetLocale = locale.value === 'id' ? 'en' : 'id'
    locale.value = targetLocale

    let currentKey = null
    for (const [key, paths] of Object.entries(routePaths)) {
        if (paths.en === route.path || paths.id === route.path || (key === 'home' && route.path === '/')) {
            currentKey = key
            break
        }
    }

    if (currentKey) {
        // 1. Beri tanda bahwa kita sedang melakukan transisi bahasa
        window.isLanguageSwitching = true
        
        await router.replace(routePaths[currentKey][targetLocale])
        
        // 2. Hapus tanda setelah navigasi selesai (beri jeda sedikit agar router selesai memproses)
        setTimeout(() => {
            window.isLanguageSwitching = false
        }, 50)
    }
}
</script>

<template>
    <nav class="fixed top-4 left-0 right-0 z-50 w-full px-4 font-sans">
        <div class="max-w-7xl mx-auto">
            <div class="bg-white/90 backdrop-blur-xl border border-slate-100 rounded-2xl 
                  shadow-[0_20px_50px_rgba(0,0,0,0.05)] 
                  transition-all duration-500 
                  hover:shadow-[0_20px_50px_rgba(234,68,53,0.15)]">

                <div class="px-6 md:px-8">
                    <div class="flex items-center justify-between h-16">

                        <div class="flex-shrink-0 flex items-center">
                            <router-link :to="routePaths.home[locale]" class="flex items-center gap-2 hover:opacity-80 transition-opacity">
                                <img src="/img/LOGO-CPI.png" alt="Logo" class="h-13 w-auto" />
                                
                                <span class="hidden lg:block font-bold tracking-tight
                                            font-['Montserrat']
                                            bg-gradient-to-r from-red-600 via-red-700 to-red-800 
                                            bg-clip-text text-transparent">
                                    PT Cakrawala Parama Internasional
                                </span>
                            </router-link>
                        </div>

                        <!-- DESKTOP -->
                        <div class="hidden md:flex items-center gap-1 font-['Montserrat']">
                            <router-link :to="routePaths.home[locale]"
                                class="text-black hover:text-brand px-4 py-2 text-sm font-bold tracking-wide transition-all hover:bg-slate-50 rounded-xl">
                                {{ t('nav.home') }}
                            </router-link>
                            
                            <router-link :to="routePaths.about[locale]"
                                class="text-black hover:text-brand px-4 py-2 text-sm font-bold tracking-wide transition-all hover:bg-slate-50 rounded-xl">
                                {{ t('nav.about') }}
                            </router-link>

                            <router-link :to="routePaths.articles[locale]"
                                class="text-black hover:text-brand px-4 py-2 text-sm font-bold tracking-wide transition-all hover:bg-slate-50 rounded-xl">
                                {{ t('nav.articles') }}
                            </router-link>

                            <!-- Dropdown Menu Services -->
                            <div class="relative group">
                                <button class="text-black group-hover:text-brand px-4 py-2 text-sm font-bold tracking-wide transition-all group-hover:bg-slate-50 rounded-xl flex items-center gap-1">
                                    {{ t('nav.services') }}
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform duration-200 group-hover:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>

                                <!-- Isi Dropdown -->
                                <div class="absolute top-full left-0 pt-2 w-48 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50">
                                    <div class="bg-white rounded-xl shadow-xl border border-slate-100 py-2 flex flex-col overflow-hidden">
                                        <router-link :to="routePaths.service[locale]"
                                            class="text-black hover:text-brand px-5 py-2.5 text-sm font-bold tracking-wide transition-all hover:bg-slate-50 block">
                                            {{ t('nav.services') }}
                                        </router-link>

                                        <router-link :to="routePaths.InteriorDesign[locale]"
                                            class="text-black hover:text-brand px-5 py-2.5 text-sm font-bold tracking-wide transition-all hover:bg-slate-50 block">
                                            {{ t('nav.interior_design') }}
                                        </router-link>

                                        <router-link :to="routePaths.assessment[locale]"
                                            class="text-black hover:text-brand px-5 py-2.5 text-sm font-bold tracking-wide transition-all hover:bg-slate-50 block">
                                            {{ t('nav.assessment') }}
                                        </router-link>

                                        <router-link :to="routePaths.project[locale]"
                                            class="text-black hover:text-brand px-5 py-2.5 text-sm font-bold tracking-wide transition-all hover:bg-slate-50 block">
                                            {{ t('nav.projects') }}
                                        </router-link>

                                        <router-link :to="routePaths.gallery[locale]"
                                            class="text-black hover:text-brand px-5 py-2.5 text-sm font-bold tracking-wide transition-all hover:bg-slate-50 block">
                                            {{ t('nav.gallery') }}
                                        </router-link>

                                        <a href="/#clients"
                                            class="text-black hover:text-brand px-5 py-2.5 text-sm font-bold tracking-wide transition-all hover:bg-slate-50 block">
                                            {{ t('nav.clients') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <!-- End of Dropdown Menu Services -->
                            
                            <button @click.prevent="handleLanguageToggle"
                                class="ml-2 flex items-center justify-center text-black w-10 h-10 rounded-xl border border-slate-200 hover:bg-slate-50 transition-all active:scale-95"
                                :title="isIndonesian ? 'Switch to English' : 'Ganti ke Bahasa Indonesia'">
                                <span class="text-xs font-black">{{ isIndonesian ? 'EN' : 'ID' }}</span>
                            </button>

                            <router-link :to="routePaths.contact[locale]"
                                class="ml-4 bg-slate-900 text-white hover:bg-brand px-6 py-2.5 rounded-xl text-sm font-black shadow-lg hover:shadow-brand/30 transition-all active:scale-95 flex items-center gap-2">
                                {{ t('nav.contact') }} <span class="text-xs opacity-50">→</span>
                            </router-link>
                        </div>

                        <div class="md:hidden flex items-center gap-2">
                            <button @click.prevent="handleLanguageToggle"
                                class="flex items-center justify-center text-black w-10 h-10 rounded-xl border border-slate-200 hover:bg-slate-50 transition-all active:scale-95"
                                :title="isIndonesian ? 'Switch to English' : 'Ganti ke Bahasa Indonesia'">
                                <span class="text-xs font-black">{{ isIndonesian ? 'EN' : 'ID' }}</span>
                            </button>

                            <button @click="isMenuOpen = !isMenuOpen"
                                class="p-2 text-black hover:bg-slate-100 rounded-lg transition-colors">
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


                <!-- MOBILE -->
                <div v-show="isMenuOpen"
                    class="md:hidden border-t border-slate-100 p-4 space-y-1 bg-white rounded-b-2xl">
                    <router-link :to="routePaths.home[locale]" @click="isMenuOpen = false"
                        class="block px-4 py-2.5 text-black hover:text-brand hover:bg-slate-50 rounded-xl text-sm font-bold tracking-wide transition-all">
                        {{ t('nav.home') }}
                    </router-link>

                    <router-link :to="routePaths.about[locale]" @click="isMenuOpen = false"
                        class="block px-4 py-2.5 text-black hover:text-brand hover:bg-slate-50 rounded-xl text-sm font-bold tracking-wide transition-all">
                        {{ t('nav.about') }}
                    </router-link>

                    <router-link :to="routePaths.articles[locale]" @click="isMenuOpen = false"
                        class="block px-4 py-2.5 text-black hover:text-brand hover:bg-slate-50 rounded-xl text-sm font-bold tracking-wide transition-all">
                        {{ t('nav.articles') }}
                    </router-link>

                    <div class="space-y-1">
                        <button @click="isMobileServiceOpen = !isMobileServiceOpen"
                            class="w-full flex justify-between items-center px-4 py-2.5 text-black hover:text-brand hover:bg-slate-50 rounded-xl text-sm font-bold tracking-wide transition-all">
                            {{ t('nav.services') }}
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform duration-200"
                                :class="{'rotate-180': isMobileServiceOpen}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div v-show="isMobileServiceOpen" class="pl-4 space-y-1 bg-slate-50/50 rounded-xl py-2 mt-1">
                            <router-link :to="routePaths.service[locale]" @click="isMenuOpen = false"
                                class="block px-4 py-2 text-black hover:text-brand hover:bg-slate-100 rounded-lg text-sm font-bold tracking-wide transition-all">
                                {{ t('nav.services') }}
                            </router-link>
                            
                            <router-link :to="routePaths.InteriorDesign[locale]" @click="isMenuOpen = false"
                                class="block px-4 py-2 text-black hover:text-brand hover:bg-slate-100 rounded-lg text-sm font-bold tracking-wide transition-all">
                                {{ t('nav.interior_design') }}
                            </router-link>

                            <router-link :to="routePaths.assessment[locale]" @click="isMenuOpen = false"
                                class="block px-4 py-2 text-black hover:text-brand hover:bg-slate-100 rounded-lg text-sm font-bold tracking-wide transition-all">
                                {{ t('nav.assessment') }}
                            </router-link>

                            <router-link :to="routePaths.project[locale]" @click="isMenuOpen = false"
                                class="block px-4 py-2 text-black hover:text-brand hover:bg-slate-100 rounded-lg text-sm font-bold tracking-wide transition-all">
                                {{ t('nav.projects') }}
                            </router-link>

                            <router-link :to="routePaths.gallery[locale]" @click="isMenuOpen = false"
                                class="block px-4 py-2 text-black hover:text-brand hover:bg-slate-100 rounded-lg text-sm font-bold tracking-wide transition-all">
                                {{ t('nav.gallery') }}
                            </router-link>

                            <a href="/#clients" @click="isMenuOpen = false"
                                class="block px-4 py-2 text-black hover:text-brand hover:bg-slate-100 rounded-lg text-sm font-bold tracking-wide transition-all">
                                {{ t('nav.clients') }}
                            </a>
                        </div>
                    </div>

                    <div class="pt-3 mt-3 border-t border-slate-100 space-y-2">
                        <router-link :to="routePaths.contact[locale]" @click="isMenuOpen = false"
                            class="block px-4 py-2.5 bg-slate-900 text-white hover:bg-brand rounded-xl text-sm font-black text-center shadow-lg hover:shadow-brand/30 transition-all active:scale-95">
                            {{ t('nav.contact') }} <span class="text-xs opacity-50">→</span>
                        </router-link>
                    </div>
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