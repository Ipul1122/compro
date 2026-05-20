<script setup>
import { ref, onMounted, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useI18n } from 'vue-i18n'
import Api from '@/api'
import { getImageUrl, handleImageError } from '@/utils/imageHelper'

const route = useRoute()
const router = useRouter()
const { t, locale } = useI18n() 

const articles = ref([])
const categories = ref([])
const isLoading = ref(false)

const currentPage = ref(1)
const lastPage = ref(1)
const totalItems = ref(0)

const filters = ref({
    search: route.query.search || '',
    category_id: route.query.category_id || ''
})

const fetchCategories = async () => {
    try {
        const response = await Api.get('/categories')
        categories.value = response.data.data || response.data
    } catch (error) {
        console.error("Error fetching categories:", error)
    }
}

const fetchArticles = async (page = 1) => {
    isLoading.value = true
    try {
        const params = {
            page: page,
            search: filters.value.search,
            category_id: filters.value.category_id
        }

        const response = await Api.get('/articles', { params })
        
        articles.value = response.data.data
        
        if (response.data.pagination) {
            currentPage.value = response.data.pagination.current_page
            lastPage.value = response.data.pagination.last_page
            totalItems.value = response.data.pagination.total
        }
    } catch (error) {
        console.error("Error fetching articles:", error)
    } finally {
        isLoading.value = false
    }
}

const changePage = (page) => {
    const queryParams = { page: page }
    if (filters.value.search) queryParams.search = filters.value.search
    if (filters.value.category_id) queryParams.category_id = filters.value.category_id

    router.push({ name: route.name, query: queryParams })
}

watch(() => route.query, (newQuery) => {
    const page = parseInt(newQuery.page) || 1
    filters.value.search = newQuery.search || ''
    filters.value.category_id = newQuery.category_id || ''
    
    fetchArticles(page)
}, { deep: true })

onMounted(() => {
    fetchCategories()
    const pageFromUrl = parseInt(route.query.page) || 1
    fetchArticles(pageFromUrl)
})

const applyFilters = () => {
    changePage(1)
}

const resetFilters = () => {
    filters.value = { search: '', category_id: '' }
    changePage(1)
}

const formatDate = (dateString) => {
    if (!dateString) return '-'
    const activeLang = locale.value === 'id' ? 'id-ID' : 'en-US'
    return new Date(dateString).toLocaleDateString(activeLang, {
        day: 'numeric', month: 'long', year: 'numeric'
    })
}

const getCategoryName = (name) => {
    if (!name) return t('articles_index.general')
    
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
</script>

<template>
    <div class="min-h-screen bg-white flex flex-col">

        <main class="flex-grow container mx-auto px-4 sm:px-6 max-w-6xl">

            <!-- Hero Header -->
            <div class="pt-32 pb-12 text-center">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-red-50 border border-red-100 rounded-full mb-6">
                    <span class="w-1.5 h-1.5 bg-red-600 rounded-full animate-pulse"></span>
                    <span class="font-montserrat text-[11px] font-bold uppercase tracking-widest text-red-600">{{ $t('articles_index.subtitle') }}</span>
                </div>
                <h1 class="font-montserrat text-3xl sm:text-4xl md:text-5xl font-extrabold text-slate-900 tracking-tight mb-4">
                    {{ $t('articles_index.title') }}
                </h1>
                <div class="flex items-center justify-center gap-2">
                    <span class="w-8 h-1 bg-red-600 rounded-full"></span>
                    <span class="w-3 h-1 bg-orange-500 rounded-full"></span>
                </div>
            </div>

            <!-- Filter Bar -->
            <div class="mb-10">
                <div class="flex flex-wrap items-center gap-3 p-3 bg-slate-50 border border-slate-200 rounded-2xl">
                    <div class="flex-1 min-w-[200px] relative">
                        <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-red-500 pointer-events-none" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
                        </svg>
                        <input
                            v-model="filters.search"
                            @keyup.enter="applyFilters"
                            type="text"
                            :placeholder="$t('articles_index.search_placeholder')"
                            class="w-full bg-white border border-slate-200 rounded-xl py-2.5 pl-10 pr-4 text-sm font-quicksand text-slate-700 placeholder:text-slate-400 focus:outline-none focus:border-red-300 focus:ring-2 focus:ring-red-100 transition-all"
                        />
                    </div>

                    <div class="relative min-w-[180px]">
                        <select v-model="filters.category_id" @change="applyFilters" class="w-full appearance-none bg-white border border-slate-200 rounded-xl py-2.5 pl-4 pr-9 text-sm font-quicksand text-slate-700 cursor-pointer focus:outline-none focus:border-red-300 focus:ring-2 focus:ring-red-100 transition-all">
                            <option value="">{{ $t('articles_index.all_categories') }}</option>
                            <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                        </select>
                        <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>

                    <button @click="applyFilters" class="bg-red-600 hover:bg-red-700 text-white rounded-xl px-6 py-2.5 font-montserrat text-xs font-bold uppercase tracking-wider transition-all hover:shadow-lg hover:shadow-red-200 active:scale-95 focus-visible:ring-2 focus-visible:ring-red-500 focus-visible:ring-offset-2">
                        {{ $t('articles_index.filter') }}
                    </button>

                    <button @click="resetFilters" :title="'Reset'" class="p-2.5 bg-white border border-slate-200 rounded-xl text-slate-400 hover:text-red-600 hover:border-red-200 hover:bg-red-50 transition-all focus-visible:ring-2 focus-visible:ring-red-500 focus-visible:ring-offset-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Results count -->
            <div v-if="!isLoading && articles.length > 0" class="mb-6 font-quicksand text-sm text-slate-500">
                <span class="font-montserrat font-bold text-red-600">{{ totalItems }}</span> {{ locale === 'en' ? 'articles found' : 'artikel ditemukan' }}
            </div>

            <!-- Skeleton Loading -->
            <div v-if="isLoading" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6" aria-busy="true" aria-live="polite">
                <div v-for="n in 6" :key="n" class="rounded-2xl border border-slate-100 overflow-hidden">
                    <div class="h-52 bg-slate-100 animate-pulse"></div>
                    <div class="p-5 space-y-3">
                        <div class="h-3 bg-slate-100 rounded-full w-1/3 animate-pulse"></div>
                        <div class="h-4 bg-slate-100 rounded-full w-full animate-pulse"></div>
                        <div class="h-4 bg-slate-100 rounded-full w-2/3 animate-pulse"></div>
                    </div>
                </div>
            </div>

            <!-- Articles Grid -->
            <div v-else-if="articles.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <article
                    v-for="(article, index) in articles"
                    :key="article.id"
                    class="group rounded-2xl border border-slate-100 bg-white overflow-hidden flex flex-col transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-red-100/50 hover:border-red-100 focus-within:ring-2 focus-within:ring-red-500 focus-within:ring-offset-2"
                    :style="`animation: cardFadeIn 0.4s ease both; animation-delay: ${index * 60}ms`"
                >
                    <router-link :to="`/articles/${article.slug}`" class="flex flex-col h-full focus:outline-none">
                        <!-- Image -->
                        <div class="relative h-52 overflow-hidden">
                            <img
                                :src="getImageUrl(article.image)"
                                :alt="article.title"
                                :title="article.title"
                                @error="handleImageError"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                            />
                            <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent"></div>
                            <span class="absolute top-3 left-3 px-3 py-1 bg-red-600 text-white font-montserrat text-[10px] font-bold uppercase tracking-wider rounded-full">
                                {{ getCategoryName(article.category?.name) }}
                            </span>
                            <div class="absolute bottom-3 right-3 flex items-center gap-1 px-2.5 py-1 bg-white/90 backdrop-blur-sm rounded-full text-slate-600 text-[11px] font-medium">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                {{ article.total_view || 0 }}
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="p-5 flex flex-col flex-grow">
                            <span class="font-quicksand text-[11px] font-medium text-slate-400 uppercase tracking-wider mb-2">{{ formatDate(article.created_at) }}</span>
                            <h2 class="font-montserrat text-base font-bold text-slate-900 leading-snug line-clamp-2 mb-auto group-hover:text-red-600 transition-colors duration-200">
                                {{ locale === 'en' && article.title_en ? article.title_en : article.title }}
                            </h2>
                            <div class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-between">
                                <span class="font-montserrat text-[11px] font-bold uppercase tracking-wider text-red-600 group-hover:text-red-700 transition-colors">
                                    {{ $t('articles_index.read_more') || 'Read More' }}
                                </span>
                                <span class="w-7 h-7 flex items-center justify-center bg-red-50 rounded-full group-hover:bg-red-600 transition-all duration-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-red-600 group-hover:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                    </svg>
                                </span>
                            </div>
                        </div>
                    </router-link>
                </article>
            </div>

            <!-- Empty State -->
            <div v-else class="text-center py-20 px-6">
                <div class="w-16 h-16 mx-auto mb-4 flex items-center justify-center bg-red-50 rounded-2xl">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <p class="font-montserrat text-lg font-bold text-slate-700 mb-1">{{ $t('articles_index.no_articles') }}</p>
                <p class="font-quicksand text-sm text-slate-400">{{ locale === 'en' ? 'Try adjusting your search or filter' : 'Coba ubah pencarian atau filter Anda' }}</p>
            </div>

            <!-- Pagination -->
            <div v-if="lastPage > 1" class="flex justify-center items-center gap-3 mt-16 mb-10">
                <button
                    @click="changePage(currentPage - 1)"
                    :disabled="currentPage === 1"
                    class="w-10 h-10 flex items-center justify-center rounded-xl border border-slate-200 text-slate-500 hover:border-red-200 hover:text-red-600 hover:bg-red-50 disabled:opacity-30 disabled:cursor-not-allowed transition-all focus-visible:ring-2 focus-visible:ring-red-500 focus-visible:ring-offset-2"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </button>

                <div class="flex items-center gap-1.5 px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl font-montserrat text-sm font-bold">
                    <span class="text-red-600">{{ currentPage }}</span>
                    <span class="text-slate-300">/</span>
                    <span class="text-slate-400">{{ lastPage }}</span>
                </div>

                <button
                    @click="changePage(currentPage + 1)"
                    :disabled="currentPage === lastPage"
                    class="w-10 h-10 flex items-center justify-center rounded-xl border border-slate-200 text-slate-500 hover:border-red-200 hover:text-red-600 hover:bg-red-50 disabled:opacity-30 disabled:cursor-not-allowed transition-all focus-visible:ring-2 focus-visible:ring-red-500 focus-visible:ring-offset-2"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>

        </main>
    </div>
</template>

<style scoped>
/* Card fade-in animation */
@keyframes cardFadeIn {
    from { opacity: 0; transform: translateY(12px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Line clamp utility */
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Reduced motion */
@media (prefers-reduced-motion: reduce) {
    * {
        animation: none !important;
        transition-duration: 0.01ms !important;
    }
}
</style>
