<script setup>
import { ref, onMounted, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useI18n } from 'vue-i18n'
import Api from '@/api'
import Navbar from '@/components/Navbar.vue'
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
        const response = await Api.get('/admin/categories/list')
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

    router.push({ name: 'articles.index', query: queryParams })
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
    <div class="min-h-screen bg-slate-50 flex flex-col">
        <Navbar />

        <main class="flex-grow container mx-auto px-4 py-8 max-w-7xl">
            <div class="mb-10 text-center md:text-left">
                <h1 class="text-3xl font-black text-slate-900 uppercase tracking-tight mt-16 mb-5 text-center">
                    {{ $t('articles_index.title') }}
                </h1>
                <p class="text-slate-500 mb-8 font-medium text-center">
                    {{ $t('articles_index.subtitle') }}
                </p>

                <div class="bg-white rounded-3xl border border-slate-100 text-black p-4 shadow-sm flex flex-col md:flex-row gap-4">
                    <div class="flex-1">
                        <input v-model="filters.search" @keyup.enter="applyFilters" type="text" :placeholder="$t('articles_index.search_placeholder')" 
                            class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-slate-900/5 transition-all" />
                    </div>
                    <div class="md:w-64">
                        <select v-model="filters.category_id" @change="applyFilters" 
                            class="w-full bg-slate-50 text-black border border-slate-100 rounded-2xl px-5 py-3 text-sm focus:outline-none cursor-pointer">
                            <option value="">{{ $t('articles_index.all_categories') }}</option>
                            <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                        </select>
                    </div>
                    <button @click="applyFilters" class="bg-slate-900 text-white px-8 py-3 rounded-2xl font-bold hover:bg-red-600 transition-colors cursor-pointer">
                        {{ $t('articles_index.filter') }}
                    </button>
                    <button @click="resetFilters" class="p-3 text-slate-400 hover:text-red-600 bg-slate-50 rounded-2xl transition-all cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                    </button>
                </div>
            </div>

            <div v-if="isLoading" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div v-for="n in 6" :key="n" class="bg-white rounded-3xl h-96 animate-pulse border border-slate-100"></div>
            </div>

            <div v-else-if="articles.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div v-for="article in articles" :key="article.id" class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden hover:shadow-xl hover:shadow-slate-200/50 transition-all group flex flex-col">
                    <div class="relative h-56 overflow-hidden">
                        <img 
                            :src="getImageUrl(article.image)" 
                            :alt="article.title"
                            :title="article.title"
                            @error="handleImageError" 
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" 
                        />
                        <span class="absolute top-4 left-4 bg-white/90 backdrop-blur px-3 py-1 rounded-xl text-[10px] font-black uppercase text-blue-600">
                            {{ getCategoryName(article.category?.name) }}
                        </span>
                    </div>
                    
                    <div class="p-6 flex flex-col flex-1">
                        <div class="flex items-center gap-3 text-[10px] font-black text-slate-400 uppercase mb-3">
                            <span>{{ formatDate(article.created_at) }}</span>
                            <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                            <span>{{ article.total_view || 0 }} {{ $t('articles_index.views') }}</span>
                        </div>
                        
                        <h3 class="text-xl font-bold text-slate-900 mb-4 line-clamp-2 leading-tight group-hover:text-blue-600 transition-colors">
                            {{ locale === 'en' && article.title_en ? article.title_en : article.title }}
                        </h3>
                        
                        <div class="mt-auto pt-5 border-t border-slate-50 flex justify-between items-center">
                            <router-link :to="`/articles/${article.slug}`" class="text-sm font-black text-slate-900 uppercase tracking-wider flex items-center gap-2 group/link">
                                {{ $t('articles_index.read_more') }}
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 group-hover/link:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </router-link>
                        </div>
                    </div>
                </div>
            </div>

            <div v-else class="text-center py-20 bg-white rounded-3xl border border-slate-100">
                <p class="text-slate-400 font-bold">{{ $t('articles_index.no_articles') }}</p>
            </div>

            <div v-if="lastPage > 1" class="flex justify-center items-center mt-12 gap-2">
                <button @click="changePage(currentPage - 1)" :disabled="currentPage === 1"
                    class="p-2 rounded-xl bg-white text-black border border-slate-100 disabled:opacity-50 cursor-pointer hover:bg-slate-50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </button>
                
                <span class="px-4 text-sm font-bold text-slate-600">
                    {{ $t('articles_index.page', { current: currentPage, last: lastPage }) }}
                </span>
                
                <button @click="changePage(currentPage + 1)" :disabled="currentPage === lastPage"
                    class="p-2 rounded-xl bg-white text-black border border-slate-100 disabled:opacity-50 cursor-pointer hover:bg-slate-50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </button>
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
button { cursor: pointer; }
</style>