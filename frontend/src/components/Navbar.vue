<script setup>
import { ref, computed } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useI18n } from 'vue-i18n'

const isMenuOpen = ref(false)
const router = useRouter()
const route = useRoute()

const { t, locale } = useI18n()
const isIndonesian = computed(() => locale.value === 'id')

// 1. Buat mapping URL untuk EN dan ID
const routePaths = {
    home: { en: '/home', id: '/beranda' },
    about: { en: '/about', id: '/tentang-kami' },
    project: { en: '/project', id: '/proyek' },
    articles: { en: '/articles', id: '/artikel' },
    gallery: { en: '/galerry', id: '/galeri' },
    service: { en: '/service', id: '/layanan' },
    contact: { en: '/contact', id: '/kontak' },
}

const handleLanguageToggle = () => {
    // Tentukan target bahasa
    const targetLocale = locale.value === 'id' ? 'en' : 'id'
    locale.value = targetLocale

    // 2. Cek halaman saat ini untuk mengubah URL di Address Bar
    let currentKey = null
    for (const [key, paths] of Object.entries(routePaths)) {
        // Jika path saat ini cocok dengan EN/ID, atau jika sedang di root '/'
        if (paths.en === route.path || paths.id === route.path || (key === 'home' && route.path === '/')) {
            currentKey = key
            break
        }
    }

    // 3. Jika ketemu halamannya, ganti URL (tanpa me-reload page)
    if (currentKey) {
        router.replace(routePaths[currentKey][targetLocale])
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
                            <router-link :to="routePaths.home[locale]" class="hover:opacity-80 transition-opacity">
                                <img src="/src/img/webcakrawala-logo.png" alt="Logo" class="h-13 w-auto" />
                            </router-link>
                        </div>

                        <div class="hidden md:flex items-center gap-1">
                            <router-link :to="routePaths.home[locale]"
                                class="text-slate-600 hover:text-brand px-4 py-2 text-sm font-bold tracking-wide transition-all hover:bg-slate-50 rounded-xl">
                                {{ t('nav.home') }}
                            </router-link>
                            
                            <router-link :to="routePaths.about[locale]"
                                class="text-slate-600 hover:text-brand px-4 py-2 text-sm font-bold tracking-wide transition-all hover:bg-slate-50 rounded-xl">
                                {{ t('nav.about') }}
                            </router-link>

                            <router-link :to="routePaths.project[locale]"
                                class="text-slate-600 hover:text-brand px-4 py-2 text-sm font-bold tracking-wide transition-all hover:bg-slate-50 rounded-xl">
                                {{ t('nav.projects') }}
                            </router-link>

                            <router-link :to="routePaths.articles[locale]"
                                class="text-slate-600 hover:text-brand px-4 py-2 text-sm font-bold tracking-wide transition-all hover:bg-slate-50 rounded-xl">
                                {{ t('nav.articles') }}
                            </router-link>

                            <router-link :to="routePaths.gallery[locale]"
                                class="text-slate-600 hover:text-brand px-4 py-2 text-sm font-bold tracking-wide transition-all hover:bg-slate-50 rounded-xl">
                                {{ t('nav.gallery') }}
                            </router-link>

                            <router-link :to="routePaths.service[locale]"
                                class="text-slate-600 hover:text-brand px-4 py-2 text-sm font-bold tracking-wide transition-all hover:bg-slate-50 rounded-xl">
                                {{ t('nav.services') }}
                            </router-link>

                            <a href="/#clients"
                                class="text-slate-600 hover:text-brand px-4 py-2 text-sm font-bold tracking-wide transition-all hover:bg-slate-50 rounded-xl">
                                {{ t('nav.clients') }}
                            </a>
                            
                            <button @click="handleLanguageToggle"
                                class="ml-2 flex items-center justify-center w-10 h-10 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 hover:border-brand hover:text-brand transition-all active:scale-95">
                                <span class="text-xs font-black">{{ isIndonesian ? 'EN' : 'ID' }}</span>
                            </button>

                            <router-link :to="routePaths.contact[locale]"
                                class="ml-4 bg-slate-900 text-white hover:bg-brand px-6 py-2.5 rounded-xl text-sm font-black shadow-lg hover:shadow-brand/30 transition-all active:scale-95 flex items-center gap-2">
                                {{ t('nav.contact') }} <span class="text-xs opacity-50">→</span>
                            </router-link>
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
                    class="md:hidden border-t border-slate-100 p-4 space-y-1 bg-white rounded-b-2xl">
                    <a href="#home" @click="isMenuOpen = false"
                        class="block px-4 py-2.5 text-slate-600 hover:text-brand hover:bg-slate-50 rounded-xl text-sm font-bold tracking-wide transition-all">
                        {{ t('nav.home') }}
                    </a>

                    <router-link to="/about" @click="isMenuOpen = false"
                        class="block px-4 py-2.5 text-slate-600 hover:text-brand hover:bg-slate-50 rounded-xl text-sm font-bold tracking-wide transition-all">
                        {{ t('nav.about') }}
                    </router-link>

                    <router-link to="/project" @click="isMenuOpen = false"
                        class="block px-4 py-2.5 text-slate-600 hover:text-brand hover:bg-slate-50 rounded-xl text-sm font-bold tracking-wide transition-all">
                        {{ t('nav.projects') }}
                    </router-link>

                    <router-link to="/articles" @click="isMenuOpen = false"
                        class="block px-4 py-2.5 text-slate-600 hover:text-brand hover:bg-slate-50 rounded-xl text-sm font-bold tracking-wide transition-all">
                        {{ t('nav.articles') }}
                    </router-link>

                    <router-link to="/galerry" @click="isMenuOpen = false"
                        class="block px-4 py-2.5 text-slate-600 hover:text-brand hover:bg-slate-50 rounded-xl text-sm font-bold tracking-wide transition-all">
                        {{ t('nav.gallery') }}
                    </router-link>

                    <router-link to="/service" @click="isMenuOpen = false"
                        class="block px-4 py-2.5 text-slate-600 hover:text-brand hover:bg-slate-50 rounded-xl text-sm font-bold tracking-wide transition-all">
                        {{ t('nav.services') }}
                    </router-link>

                    <a href="#clients" @click="isMenuOpen = false"
                        class="block px-4 py-2.5 text-slate-600 hover:text-brand hover:bg-slate-50 rounded-xl text-sm font-bold tracking-wide transition-all">
                        {{ t('nav.clients') }}
                    </a>

                    <div class="pt-3 mt-3 border-t border-slate-100 space-y-2">
                        <div class="flex items-center justify-between px-4 py-2">
                            <span class="text-xs font-bold text-slate-500 uppercase">{{ t('nav.switch_language') }}</span>
                            <button @click="handleLanguageToggle"
                                class="flex items-center justify-center w-10 h-10 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 hover:border-brand hover:text-brand transition-all active:scale-95">
                                <span class="text-xs font-black">{{ isIndonesian ? 'EN' : 'ID' }}</span>
                            </button>
                        </div>

                        <a href="#contact" @click="isMenuOpen = false"
                            class="block px-4 py-2.5 bg-slate-900 text-white hover:bg-brand rounded-xl text-sm font-black text-center shadow-lg hover:shadow-brand/30 transition-all active:scale-95">
                            {{ t('nav.contact') }} <span class="text-xs opacity-50">→</span>
                        </a>
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