<script setup>
import { ref, onMounted, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import Api from '@/api'
import Navbar from '@/components/Navbar.vue'
import { getImageUrl, handleImageError } from '@/utils/imageHelper'

const route = useRoute()
const router = useRouter()

const article = ref(null)
const latestArticles = ref([]) // State untuk 3 artikel terbaru
const isLoading = ref(true)

// Fungsi untuk fetch detail artikel
const fetchDetail = async (slug) => {
    isLoading.value = true
    try {
        const response = await Api.get(`/articles/${slug}`)
        article.value = response.data.data
        document.title = `${article.value.title} - Cakrawala`
    } catch (error) {
        router.push({ name: 'NotFound' })
    } finally {
        isLoading.value = false
    }
}

// Fungsi untuk fetch 3 artikel terbaru
// Di dalam script DetailView.vue
const fetchLatest = async () => {
    try {
        // Memanggil endpoint publik dengan limit 3
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

// Watcher untuk mendeteksi perubahan slug (saat klik artikel terbaru dari halaman detail)
watch(() => route.params.slug, (newSlug) => {
    if (newSlug) fetchDetail(newSlug)
})

const formatDate = (dateString) => {
    if (!dateString) return '-'
    return new Date(dateString).toLocaleDateString('id-ID', {
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
                    </div>

                    <div v-else-if="article">
                        <h1 class="text-3xl md:text-5xl font-black text-slate-900 leading-tight my-16">{{ article.title }}</h1>
                        
                        <div class="flex flex-wrap items-center justify-between gap-4 mb-8 border-b border-slate-100 pb-6">
                            <div class="flex items-center gap-4 text-[11px] font-black text-slate-400 uppercase tracking-wider">
                                <span>{{ formatDate(article.created_at) }}</span>
                                <span class="w-1.5 h-1.5 bg-slate-300 rounded-full"></span>
                                <span>{{ article.total_view || 0 }} Views</span>
                            </div>

                            <div class="flex items-center gap-3">
                                <span class="text-[10px] font-black text-slate-400 uppercase mr-2">Share:</span>
                                <a href="#" class="p-2 bg-white rounded-xl border border-slate-100 text-pink-600 hover:bg-pink-50 transition-colors">
                                    <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                                </a>
                                <a href="#" class="p-2 bg-white rounded-xl border border-slate-100 text-blue-600 hover:bg-blue-50 transition-colors">
                                    <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.469h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.469h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                </a>
                                <a href="#" class="p-2 bg-white rounded-xl border border-slate-100 text-slate-900 hover:bg-slate-50 transition-colors">
                                    <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.84 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                                </a>
                            </div>
                        </div>

                        <div class="bg-white rounded-3xl p-2 border border-slate-100 shadow-sm mb-10 overflow-hidden">
                            <img :src="getImageUrl(article.image)" @error="handleImageError" class="w-full h-auto object-cover rounded-2xl" />
                        </div>

                        <article class="bg-white rounded-3xl border border-slate-100 shadow-sm p-8 md:p-12 text-slate-700 prose prose-slate prose-lg max-w-none" v-html="article.content"></article>
                    </div>
                </div>

                <div class="lg:w-1/3">
                    <div class="sticky top-8">
                        <h2 class="text-xl font-black text-slate-900 uppercase tracking-tight mb-6">Artikel Terbaru</h2>
                        
                        <div class="flex flex-col gap-6">
                            <router-link v-for="lat in latestArticles" :key="lat.id" :to="`/articles/${lat.slug}`" 
                                class="group flex gap-4 bg-white p-3 rounded-2xl border border-slate-100 hover:shadow-xl hover:shadow-slate-200/50 transition-all">
                                <div class="w-24 h-20 flex-shrink-0 overflow-hidden rounded-xl">
                                    <img :src="getImageUrl(lat.image)" @error="handleImageError" class="w-full h-full object-cover group-hover:scale-110 transition-transform" />
                                </div>
                                <div class="flex flex-col justify-center">
                                    <span class="text-[9px] font-black text-blue-600 uppercase mb-1">{{ lat.category?.name }}</span>
                                    <h3 class="text-sm font-bold text-slate-900 line-clamp-2 leading-tight group-hover:text-blue-600 transition-colors">
                                        {{ lat.title }}
                                    </h3>
                                </div>
                            </router-link>
                        </div>

                        <div class="mt-8 bg-blue-600 rounded-3xl p-8 text-white">
                            <h4 class="text-xl font-black mb-2">Butuh Bantuan?</h4>
                            <p class="text-blue-100 text-sm mb-6">Hubungi tim kami untuk konsultasi desain interior gratis.</p>
                            <button class="w-full bg-white text-blue-600 py-3 rounded-xl font-black uppercase text-xs">Mulai Sekarang</button>
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>
</template>

<style scoped>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;  
    overflow: hidden;
}
</style>