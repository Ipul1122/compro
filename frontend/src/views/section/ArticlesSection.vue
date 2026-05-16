 <script setup>
import { ref, onMounted } from 'vue'
import { useI18n } from 'vue-i18n'
import Api from '@/api'
import { getImageUrl, handleImageError } from '@/utils/imageHelper'

import { Swiper, SwiperSlide } from 'swiper/vue';
import { Autoplay, Pagination } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/pagination';

const { locale } = useI18n()
const topArticles = ref([])
const latestArticles = ref([])
const isLoading = ref(true)

const fetchArticles = async () => {
    try {
        const [topRes, latestRes] = await Promise.all([
            Api.get('/articles', { params: { limit: 3, popular: true } }),
            Api.get('/articles', { params: { limit: 3 } })
        ])
        topArticles.value = topRes.data.data || []
        latestArticles.value = latestRes.data.data || []
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
    fetchArticles()
})
</script>

<template>
    <section class="py-20 bg-[#f8f8f8] font-montserrat">
        <div class="container mx-auto px-6 max-w-6xl">

            <!-- Header -->
            <div class="flex flex-col md:flex-row justify-between items-end mb-12 gap-6">
                <div>
                    <div class="font-montserrat w-12 h-1 bg-red-500 rounded-full mb-3"></div>
                    <p class="text-[11px] font-bold tracking-[.2em] uppercase text-red-500 mb-1">
                        {{ locale === 'en' ? 'Blog & Insights' : 'Blog & Wawasan' }}
                    </p>
                    <h2 class="font-montserrat text-3xl md:text-4xl font-black text-slate-900 uppercase tracking-tight leading-none">
                        {{ locale === 'en' ? 'Latest ' : 'Artikel ' }}
                        <span class="font-montserrat text-red-500 relative">
                            {{ locale === 'en' ? 'Articles' : 'Terbaru' }}
                            <span class="absolute bottom-0 left-0 right-0 h-[3px] bg-red-500 rounded-full"></span>
                        </span>
                    </h2>
                    <p class="font-poppins text-slate-500 max-w-md mt-3 text-sm leading-relaxed">
                        {{ locale === 'en'
                            ? 'Get the latest insights, tips, and news about our projects and services.'
                            : 'Dapatkan wawasan terbaru, tips, dan berita seputar proyek serta layanan kami melalui artikel pilihan.'
                        }}
                    </p>
                </div>

                <router-link
                    :to="$i18n.locale === 'id' ? '/id/artikel' : '/en/articles'"
                    class="inline-flex items-center gap-2 text-red-500 font-bold text-[11px] uppercase tracking-[.15em] border-[1.5px] border-red-500 rounded-full px-5 py-2 transition-all hover:bg-red-500 hover:text-white whitespace-nowrap">
                    {{ locale === 'en' ? 'See All Articles' : 'Lihat Semua Artikel' }}
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </router-link>
            </div>

            <!-- Skeleton -->
            <div v-if="isLoading" class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Left Skeleton -->
                <div class="bg-slate-200 rounded-3xl aspect-square animate-pulse"></div>
                <!-- Right Skeleton -->
                <div class="flex flex-col gap-6">
                    <div v-for="n in 3" :key="n" class="bg-slate-200 rounded-2xl flex-1 animate-pulse min-h-[140px]"></div>
                </div>
            </div>

            <!-- Layout Content -->
            <div v-else class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                
                <!-- Left: Carousel Top 3 -->
                <div class="rounded-3xl overflow-hidden aspect-square relative group shadow-lg border border-slate-100 bg-white">
                    <Swiper
                        :modules="[Autoplay, Pagination]"
                        :slides-per-view="1"
                        :autoplay="{ delay: 5000, disableOnInteraction: false }"
                        :pagination="{ clickable: true }"
                        class="w-full h-full"
                    >
                        <SwiperSlide v-for="article in topArticles" :key="'top-'+article.id" class="w-full h-full relative cursor-pointer" @click="$router.push(`/articles/${article.slug}`)">
                            <img :src="getImageUrl(article.image)" @error="handleImageError" :alt="article.title" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" />
                            
                            <!-- Overlay Gradient -->
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-slate-900/40 to-transparent"></div>
                            
                            <!-- Content -->
                            <div class="absolute bottom-0 left-0 right-0 p-8 flex flex-col gap-4">
                                <span class="bg-red-500 text-white self-start px-3 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider shadow-sm">
                                    {{ getCategoryName(article.category?.name) }}
                                </span>
                                
                                <h3 class="font-montserrat text-2xl md:text-3xl font-black text-white leading-tight line-clamp-2 drop-shadow-md">
                                    {{ locale === 'en' && article.title_en ? article.title_en : article.title }}
                                </h3>
                                
                                <div class="flex items-center gap-3 text-white/90 text-[11px] font-bold uppercase tracking-widest mt-2">
                                    <span>{{ article.created_at ? new Date(article.created_at).toLocaleDateString(locale === 'en' ? 'en-US' : 'id-ID', { month: 'short', day: 'numeric', year: 'numeric' }) : '' }}</span>
                                    <span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span>
                                    <span>{{ locale === 'en' ? 'Top Article' : 'Artikel Populer' }}</span>
                                </div>
                            </div>
                        </SwiperSlide>
                    </Swiper>
                </div>

                <!-- Right: Latest 3 Articles -->
                <div class="flex flex-col gap-6">
                    <div
                        v-for="article in latestArticles"
                        :key="'latest-'+article.id"
                        class="bg-white rounded-2xl border border-slate-100 overflow-hidden flex flex-col sm:flex-row group transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_20px_40px_-12px_rgba(249,115,22,0.18)] cursor-pointer flex-1"
                        @click="$router.push(`/articles/${article.slug}`)"
                    >
                        <!-- Image -->
                        <div class="relative w-full sm:w-48 h-48 sm:h-auto overflow-hidden shrink-0">
                            <img
                                :src="getImageUrl(article.image)"
                                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                                @error="handleImageError"
                                :alt="article.title">
                            <span class="absolute top-3 left-3 bg-white/95 backdrop-blur-sm shadow-sm rounded-lg px-2.5 py-1 text-[9px] font-extrabold uppercase tracking-widest text-red-600">
                                {{ getCategoryName(article.category?.name) }}
                            </span>
                        </div>

                        <!-- Body -->
                        <div class="p-6 flex flex-col flex-1 justify-center gap-2 relative">
                            <!-- red accent line -->
                            <div class="absolute left-0 top-0 bottom-0 w-[3px] bg-red-500 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            
                            <div class="flex items-center gap-2 text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">
                                <span>{{ article.created_at ? new Date(article.created_at).toLocaleDateString(locale === 'en' ? 'en-US' : 'id-ID', { month: 'short', day: 'numeric', year: 'numeric' }) : '' }}</span>
                                <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                                <span>{{ locale === 'en' ? 'Latest' : 'Terbaru' }}</span>
                            </div>

                            <h3 class="font-bold text-lg text-slate-900 leading-snug line-clamp-2 group-hover:text-red-500 transition-colors">
                                {{ locale === 'en' && article.title_en ? article.title_en : article.title }}
                            </h3>

                            <div class="mt-4 inline-flex items-center gap-2 text-[11px] font-extrabold text-slate-900 uppercase tracking-widest group/link">
                                <span class="w-4 h-0.5 bg-red-500 rounded-full flex-shrink-0 transition-all group-hover/link:w-6"></span>
                                {{ locale === 'en' ? 'Read More' : 'Baca Selengkapnya' }}
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 transition-transform group-hover/link:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </div>
                        </div>
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
    line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

:deep(.swiper-pagination-bullet) {
    background: rgba(255, 255, 255, 0.5) !important;
    opacity: 1;
    width: 8px;
    height: 8px;
    border-radius: 4px;
    transition: all 0.3s ease;
}

:deep(.swiper-pagination-bullet-active) {
    background: #ea4435 !important;
    width: 24px;
}
</style>