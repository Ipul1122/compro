<script setup>
import { ref, onMounted } from 'vue'
import { useI18n } from 'vue-i18n' // Import i18n
import Api from '@/api'
import { getImageUrl, handleImageError } from '@/utils/imageHelper'

const { locale } = useI18n() // Ambil locale aktif
const articles = ref([])
const isLoading = ref(true)

const fetchLatestArticles = async () => {
    try {
        const response = await Api.get('/articles', { params: { limit: 3 } })
        articles.value = response.data.data
    } catch (error) {
        console.error("Gagal memuat artikel:", error)
    } finally {
        isLoading.value = false
    }
}

// Fungsi untuk translate nama kategori (sama seperti di IndexView)
const getCategoryName = (name) => {
    if (!name) return locale.value === 'en' ? 'General' : 'Umum'
    
    if (locale.value === 'en') {
        const categoryDict = {
            'Berita': 'News',
            'Umum': 'General',
            'Tips & Trick': 'Tips & Tricks',
            'Pengumuman': 'Announcement',
            'Artikel': 'Article'
        }
        return categoryDict[name] || name 
    }
    return name
}

onMounted(() => {
    fetchLatestArticles()
})
</script>

<template>
    <section class="py-20 bg-slate-50">
        <div class="container mx-auto px-4 max-w-7xl">
            <div class="flex flex-col md:flex-row justify-between items-end mb-12 gap-6">
                <div>
                    <h2 class="text-3xl md:text-4xl font-black text-slate-900 uppercase tracking-tight mb-4">
                        {{ locale === 'en' ? 'Latest Articles' : 'Artikel Terbaru' }}
                    </h2>
                    <p class="text-slate-600 max-w-2xl">
                        {{ locale === 'en' 
                            ? 'Get the latest insights, tips, and news about our projects and services.' 
                            : 'Dapatkan wawasan terbaru, tips, dan berita seputar proyek serta layanan kami melalui artikel pilihan.' 
                        }}
                    </p>
                </div>
                <router-link to="/articles" class="group flex items-center gap-2 text-orange-600 font-bold uppercase tracking-wider text-sm transition-all hover:gap-3 whitespace-nowrap">
                    {{ locale === 'en' ? 'See All Articles' : 'Lihat Semua Artikel' }}
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </router-link>
            </div>

            <div v-if="isLoading" class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div v-for="n in 3" :key="n" class="bg-white rounded-3xl h-96 animate-pulse border border-slate-100 shadow-sm"></div>
            </div>

            <div v-else class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div v-for="article in articles" :key="article.id" 
                     class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden hover:shadow-xl transition-all group flex flex-col">
                    
                    <div class="relative h-56 overflow-hidden">
                        <img :src="getImageUrl(article.image)"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" 
                             @error="handleImageError"
                             :alt="article.title">
                        
                        <span class="absolute top-4 left-4 bg-white/90 backdrop-blur px-3 py-1 rounded-xl text-[10px] font-black uppercase text-blue-600">
                            {{ getCategoryName(article.category?.name) }}
                        </span>
                    </div>

                    <div class="p-6 flex flex-col flex-1">
                        <h3 class="font-bold text-xl text-slate-900 mb-4 line-clamp-2 leading-tight group-hover:text-orange-600 transition-colors">
                            {{ locale === 'en' && article.title_en ? article.title_en : article.title }}
                        </h3>
                        
                        <router-link :to="`/articles/${article.slug}`" class="mt-auto text-sm font-black text-slate-900 uppercase tracking-wider flex items-center gap-2 group/link relative z-10">
                            {{ locale === 'en' ? 'Read More' : 'Baca Selengkapnya' }}
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 group-hover/link:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </router-link>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;  
  overflow: hidden;
}
</style>