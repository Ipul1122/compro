<script setup>
import { ref, onMounted, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useI18n } from 'vue-i18n'
import Api from '@/api'
import Navbar from '@/components/Navbar.vue'
import { getImageUrl, handleImageError } from '@/utils/imageHelper'

const route = useRoute()
const router = useRouter()
const { locale } = useI18n()

const article = ref(null)
const latestArticles = ref([])
const isLoading = ref(true)

// Update Judul Halaman secara dinamis
const updateDocumentTitle = () => {
    if (article.value) {
        const title = locale.value === 'en' && article.value.title_en 
            ? article.value.title_en 
            : article.value.title
        document.title = `${title} - Cakrawala`
    }
}

const fetchDetail = async (slug) => {
    isLoading.value = true
    try {
        const response = await Api.get(`/articles/${slug}`)
        article.value = response.data.data
        updateDocumentTitle()
    } catch (error) {
        router.push({ name: 'NotFound' })
    } finally {
        isLoading.value = false
    }
}

const fetchLatest = async () => {
    try {
        const response = await Api.get('/articles?limit=3')
        latestArticles.value = response.data.data
    } catch (error) {
        console.error("Error fetching latest articles:", error)
    }
}

onMounted(() => {
    fetchDetail(route.params.slug)
    fetchLatest()
})

// Deteksi perubahan slug untuk navigasi antar artikel
watch(() => route.params.slug, (newSlug) => {
    if (newSlug) fetchDetail(newSlug)
})

// Deteksi perubahan bahasa untuk update title browser
watch(locale, () => {
    updateDocumentTitle()
})

const formatDate = (dateString) => {
    if (!dateString) return '-'
    const currentLocale = locale.value === 'en' ? 'en-US' : 'id-ID'
    return new Date(dateString).toLocaleDateString(currentLocale, {
        day: 'numeric', month: 'long', year: 'numeric'
    })
}
</script>

<template>
    <div class="min-h-screen bg-slate-50 flex flex-col">
        <Navbar />

        <main class="flex-grow container mx-auto px-4 py-12 max-w-6xl">
            <div class="flex flex-col lg:flex-row gap-12">
                
                <div class="lg:w-2/3">
                    <div v-if="isLoading" class="animate-pulse space-y-6">
                        <div class="h-10 bg-slate-200 rounded w-3/4"></div>
                        <div class="h-96 bg-slate-200 rounded-3xl"></div>
                        <div class="h-64 bg-slate-200 rounded-3xl"></div>
                    </div>

                    <div v-else-if="article">
                        <h1 class="text-3xl md:text-5xl font-black text-slate-900 leading-tight my-16">
                            {{ locale === 'en' && article.title_en ? article.title_en : article.title }}
                        </h1>
                        
                        <div class="flex flex-wrap items-center justify-between gap-4 mb-8 border-b border-slate-100 pb-6">
                            <div class="flex items-center gap-4 text-[11px] font-black text-slate-400 uppercase tracking-wider">
                                <span>{{ formatDate(article.created_at) }}</span>
                                <span class="w-1.5 h-1.5 bg-slate-300 rounded-full"></span>
                                <span>{{ article.total_view || 0 }} Views</span>
                            </div>

                            <div class="flex items-center gap-3">
                                <span class="text-[10px] font-black text-slate-400 uppercase mr-2">Share:</span>
                                <div class="flex gap-2">
                                    <button class="p-2 bg-white rounded-xl border border-slate-100 text-pink-600 hover:bg-pink-50 transition-colors">IG</button>
                                    <button class="p-2 bg-white rounded-xl border border-slate-100 text-blue-600 hover:bg-blue-50 transition-colors">FB</button>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-3xl p-2 border border-slate-100 shadow-sm mb-10 overflow-hidden">
                            <img :src="getImageUrl(article.image)" @error="handleImageError" class="w-full h-auto object-cover rounded-2xl" />
                        </div>

                        <article 
                            class="bg-white rounded-3xl border border-slate-100 shadow-sm p-8 md:p-12 text-slate-700 prose prose-slate prose-lg max-w-none" 
                            v-html="locale === 'en' && article.content_en ? article.content_en : article.content">
                        </article>
                    </div>
                </div>

                <div class="lg:w-1/3">
                    <div class="sticky top-8">
                        <h2 class="text-xl font-black text-slate-900 uppercase tracking-tight mb-6">
                            {{ locale === 'en' ? 'Latest Articles' : 'Artikel Terbaru' }}
                        </h2>
                        
                        <div class="flex flex-col gap-6">
                            <router-link v-for="lat in latestArticles" :key="lat.id" :to="`/articles/${lat.slug}`" 
                                class="group flex gap-4 bg-white p-3 rounded-2xl border border-slate-100 hover:shadow-xl hover:shadow-slate-200/50 transition-all">
                                <div class="w-24 h-20 flex-shrink-0 overflow-hidden rounded-xl">
                                    <img :src="getImageUrl(lat.image)" @error="handleImageError" class="w-full h-full object-cover group-hover:scale-110 transition-transform" />
                                </div>
                                <div class="flex flex-col justify-center">
                                    <span class="text-[9px] font-black text-blue-600 uppercase mb-1">
                                        {{ locale === 'en' && lat.category?.name_en ? lat.category?.name_en : lat.category?.name }}
                                    </span>
                                    <h3 class="text-sm font-bold text-slate-900 line-clamp-2 leading-tight group-hover:text-blue-600 transition-colors">
                                        {{ locale === 'en' && lat.title_en ? lat.title_en : lat.title }}
                                    </h3>
                                </div>
                            </router-link>
                        </div>

                        <div class="mt-8 bg-blue-600 rounded-3xl p-8 text-white">
                            <h4 class="text-xl font-black mb-2">{{ locale === 'en' ? 'Need Help?' : 'Butuh Bantuan?' }}</h4>
                            <p class="text-blue-100 text-sm mb-6">{{ locale === 'en' ? 'Contact our team for a free consultation.' : 'Hubungi tim kami untuk konsultasi gratis.' }}</p>
                            <button class="w-full bg-white text-blue-600 py-3 rounded-xl font-black uppercase text-xs">
                                {{ locale === 'en' ? 'Start Now' : 'Mulai Sekarang' }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>

<style scoped>
/* Pastikan line-clamp hanya untuk list samping, bukan konten utama */
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;  
    overflow: hidden;
}
</style>