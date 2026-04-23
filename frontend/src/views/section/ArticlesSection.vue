<script setup>
import { ref, onMounted } from 'vue'
import { useI18n } from 'vue-i18n'
import Api from '@/api'
import { getImageUrl, handleImageError } from '@/utils/imageHelper'

const { locale } = useI18n()
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
    <section class="py-20 bg-[#f8f8f8] font-montserrat">
        <div class="container mx-auto px-6 max-w-6xl">

            <!-- Header -->
            <div class="flex flex-col md:flex-row justify-between items-end mb-12 gap-6">
                <div>
                    <div class="w-12 h-1 bg-orange-500 rounded-full mb-3"></div>
                    <p class="text-[11px] font-bold tracking-[.2em] uppercase text-orange-500 mb-1">
                        {{ locale === 'en' ? 'Blog & Insights' : 'Blog & Wawasan' }}
                    </p>
                    <h2 class="text-3xl md:text-4xl font-black text-slate-900 uppercase tracking-tight leading-none">
                        {{ locale === 'en' ? 'Latest ' : 'Artikel ' }}
                        <span class="text-orange-500 relative">
                            {{ locale === 'en' ? 'Articles' : 'Terbaru' }}
                            <span class="absolute bottom-0 left-0 right-0 h-[3px] bg-orange-500 rounded-full"></span>
                        </span>
                    </h2>
                    <p class="text-slate-500 max-w-md mt-3 text-sm leading-relaxed">
                        {{ locale === 'en'
                            ? 'Get the latest insights, tips, and news about our projects and services.'
                            : 'Dapatkan wawasan terbaru, tips, dan berita seputar proyek serta layanan kami melalui artikel pilihan.'
                        }}
                    </p>
                </div>

                <router-link
                    to="/articles"
                    class="inline-flex items-center gap-2 text-orange-500 font-bold text-[11px] uppercase tracking-[.15em] border-[1.5px] border-orange-500 rounded-full px-5 py-2 transition-all hover:bg-orange-500 hover:text-white whitespace-nowrap">
                    {{ locale === 'en' ? 'See All Articles' : 'Lihat Semua Artikel' }}
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </router-link>
            </div>

            <!-- Skeleton -->
            <div v-if="isLoading" class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div v-for="n in 3" :key="n" class="bg-slate-200 rounded-2xl h-80 animate-pulse"></div>
            </div>

            <!-- Cards -->
            <div v-else class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div
                    v-for="article in articles"
                    :key="article.id"
                    class="bg-white rounded-2xl border border-slate-100 overflow-hidden flex flex-col group transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_20px_40px_-12px_rgba(249,115,22,0.18)] cursor-pointer">

                    <!-- Image -->
                    <div class="relative h-48 overflow-hidden">
                        <img
                            :src="getImageUrl(article.image)"
                            class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                            @error="handleImageError"
                            :alt="article.title">
                        <span class="absolute top-3 left-3 bg-white rounded-lg px-2.5 py-1 text-[9px] font-extrabold uppercase tracking-widest text-blue-600">
                            {{ getCategoryName(article.category?.name) }}
                        </span>
                    </div>

                    <!-- Orange accent bar -->
                    <div class="h-[3px] bg-slate-100 relative">
                        <div class="absolute inset-y-0 left-0 w-3/5 bg-gradient-to-r from-orange-500 to-orange-300 rounded-full"></div>
                    </div>

                    <!-- Body -->
                    <div class="p-5 flex flex-col flex-1 gap-3">
                        <div class="flex items-center gap-2 text-[11px] font-semibold text-slate-400 uppercase tracking-wider">
                            <span>{{ article.created_at ? new Date(article.created_at).toLocaleDateString(locale === 'en' ? 'en-US' : 'id-ID', { month: 'short', year: 'numeric' }) : '' }}</span>
                            <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                            <span>{{ locale === 'en' ? '5 min read' : '5 min baca' }}</span>
                        </div>

                        <h3 class="font-bold text-base text-slate-900 leading-snug line-clamp-2 group-hover:text-orange-500 transition-colors">
                            {{ locale === 'en' && article.title_en ? article.title_en : article.title }}
                        </h3>

                        <router-link
                            :to="`/articles/${article.slug}`"
                            class="mt-auto inline-flex items-center gap-2 text-[11px] font-extrabold text-slate-900 uppercase tracking-widest relative z-10 group/link">
                            <span class="w-5 h-0.5 bg-orange-500 rounded-full flex-shrink-0"></span>
                            {{ locale === 'en' ? 'Read More' : 'Baca Selengkapnya' }}
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 transition-transform group-hover/link:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
@import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;900&display=swap');

.font-montserrat {
    font-family: 'Montserrat', sans-serif;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>