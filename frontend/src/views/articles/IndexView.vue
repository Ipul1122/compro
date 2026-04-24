<script setup>
import { ref, onMounted, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useI18n } from 'vue-i18n'
import Api from '@/api'
import Navbar from '@/components/Navbar.vue'
import Footer from '@/components/Footer.vue'
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
    <div class="page-root">

        <!-- Animated background blobs -->
        <div class="bg-blob blob-1"></div>
        <div class="bg-blob blob-2"></div>
        <div class="bg-blob blob-3"></div>

        <!-- Grid texture overlay -->
        <div class="grid-overlay"></div>

        <div class="relative z-10 w-full">
            <Navbar />
        </div>

        <main class="main-content">

            <!-- Hero Header -->
            <div class="hero-section">
                <div class="hero-eyebrow">
                    <span class="eyebrow-dot font-montserrat"></span>
                    <span>{{ $t('articles_index.subtitle') }}</span>
                </div>
                <h1 class="hero-title font-montserrat">
                    {{ $t('articles_index.title') }}
                </h1>
                <div class="hero-line"></div>
            </div>

            <!-- Filter Bar -->
            <div class="filter-bar">
                <div class="filter-inner">
                    <div class="search-wrap">
                        <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
                        </svg>
                        <input
                            v-model="filters.search"
                            @keyup.enter="applyFilters"
                            type="text"
                            :placeholder="$t('articles_index.search_placeholder')"
                            class="search-input"
                        />
                    </div>

                    <div class="select-wrap">
                        <select v-model="filters.category_id" @change="applyFilters" class="category-select">
                            <option value="">{{ $t('articles_index.all_categories') }}</option>
                            <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                        </select>
                        <svg class="select-arrow" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>

                    <button @click="applyFilters" class="btn-filter">
                        {{ $t('articles_index.filter') }}
                    </button>

                    <button @click="resetFilters" class="btn-reset" :title="'Reset'">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Results count -->
            <div v-if="!isLoading && articles.length > 0" class="results-meta">
                <span class="results-count">{{ totalItems }}</span> artikel ditemukan
            </div>

            <!-- Skeleton Loading -->
            <div v-if="isLoading" class="articles-grid">
                <div v-for="n in 6" :key="n" class="skeleton-card">
                    <div class="skeleton-img"></div>
                    <div class="skeleton-body">
                        <div class="skeleton-line short"></div>
                        <div class="skeleton-line"></div>
                        <div class="skeleton-line medium"></div>
                    </div>
                </div>
            </div>

            <!-- Articles Grid -->
            <div v-else-if="articles.length > 0" class="articles-grid">
                <article
                    v-for="(article, index) in articles"
                    :key="article.id"
                    class="article-card"
                    :style="`animation-delay: ${index * 60}ms`"
                >
                    <!-- Image -->
                    <router-link :to="`/articles/${article.slug}`" class="article-link">
                        <div class="card-img-wrap">
                            <img
                                :src="getImageUrl(article.image)"
                                :alt="article.title"
                                :title="article.title"
                                @error="handleImageError"
                                class="card-img"
                            />
                            <div class="card-img-overlay"></div>
                            <span class="card-category-badge">
                                {{ getCategoryName(article.category?.name) }}
                            </span>
                            <div class="card-views-badge">
                                <svg xmlns="http://www.w3.org/2000/svg" class="eye-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                {{ article.total_view || 0 }}
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="card-body">
                            <span class="card-date">{{ formatDate(article.created_at) }}</span>
                            <h2 class="card-title">
                                {{ locale === 'en' && article.title_en ? article.title_en : article.title }}
                            </h2>
                            <div class="card-footer">
                                <span class="read-more-link">
                                    {{ $t('articles_index.read_more') || 'Read More' }}
                                    <span class="link-arrow">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                        </svg>
                                    </span>
                                </span>
                            </div>
                        </div>
                    </router-link>
                </article>
            </div>

            <!-- Empty State -->
            <div v-else class="empty-state">
                <div class="empty-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <p class="empty-text">{{ $t('articles_index.no_articles') }}</p>
            </div>

            <!-- Pagination -->
            <div v-if="lastPage > 1" class="pagination">
                <button
                    @click="changePage(currentPage - 1)"
                    :disabled="currentPage === 1"
                    class="page-btn"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </button>

                <div class="page-info">
                    <span class="page-current">{{ currentPage }}</span>
                    <span class="page-sep">/</span>
                    <span class="page-last">{{ lastPage }}</span>
                </div>

                <button
                    @click="changePage(currentPage + 1)"
                    :disabled="currentPage === lastPage"
                    class="page-btn"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>

        </main>

        <!-- <div class="relative z-10 w-full">
            <Footer />
        </div> -->
    </div>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;1,9..40,300&display=swap');

/* ─── Root & Background ─────────────────────────── */
.page-root {
    min-height: 100vh;
    background-color: #0d0d0d;
    font-family: 'DM Sans', sans-serif;
    color: #f0ede8;
    position: relative;
    overflow-x: hidden;
    display: flex;
    flex-direction: column;
}

.bg-blob {
    position: fixed;
    border-radius: 50%;
    filter: blur(80px);
    pointer-events: none;
    z-index: 0;
    animation: blobFloat 12s ease-in-out infinite alternate;
}
.blob-1 {
    width: 520px; height: 520px;
    background: radial-gradient(circle, rgba(249,115,22,0.22) 0%, transparent 70%);
    top: -120px; left: -100px;
    animation-duration: 14s;
}
.blob-2 {
    width: 400px; height: 400px;
    background: radial-gradient(circle, rgba(234,88,12,0.18) 0%, transparent 70%);
    top: 40%; right: -80px;
    animation-duration: 10s;
    animation-delay: -4s;
}
.blob-3 {
    width: 300px; height: 300px;
    background: radial-gradient(circle, rgba(249,115,22,0.12) 0%, transparent 70%);
    bottom: 100px; left: 30%;
    animation-duration: 16s;
    animation-delay: -8s;
}
@keyframes blobFloat {
    from { transform: translate(0, 0) scale(1); }
    to   { transform: translate(30px, 40px) scale(1.08); }
}

.grid-overlay {
    position: fixed;
    inset: 0;
    z-index: 0;
    pointer-events: none;
    background-image:
        linear-gradient(rgba(249,115,22,0.04) 1px, transparent 1px),
        linear-gradient(90deg, rgba(249,115,22,0.04) 1px, transparent 1px);
    background-size: 48px 48px;
}

/* ─── Main ──────────────────────────────────────── */
.main-content {
    flex-grow: 1;
    position: relative;
    z-index: 10;
    max-width: 1280px;
    width: 100%;
    margin: 0 auto;
    padding: 2rem 1.5rem 5rem;
}

/* ─── Hero ──────────────────────────────────────── */
.hero-section {
    padding: 6rem 0 3.5rem;
    text-align: center;
}
.hero-eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: 0.7rem;
    font-weight: 600;
    letter-spacing: 0.18em;
    text-transform: uppercase;
    color: #f97316;
    background: rgba(249,115,22,0.1);
    border: 1px solid rgba(249,115,22,0.25);
    border-radius: 999px;
    padding: 6px 16px;
    margin-bottom: 1.5rem;
}
.eyebrow-dot {
    width: 6px; height: 6px;
    background: #f97316;
    border-radius: 50%;
    animation: pulse 2s ease-in-out infinite;
}
@keyframes pulse {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.5; transform: scale(0.8); }
}
.hero-title {
    /* font-family: */
    font-size: clamp(2.8rem, 7vw, 5.5rem);
    font-weight: 800;
    line-height: 1.0;
    letter-spacing: -0.03em;
    color: #f0ede8;
    text-transform: uppercase;
    margin-bottom: 1.5rem;
}
.hero-title span {
    color: #f97316;
    display: inline-block;
}
.hero-line {
    width: 60px;
    height: 3px;
    background: linear-gradient(90deg, #f97316, #ea580c);
    border-radius: 2px;
    margin: 0 auto;
}

/* ─── Filter Bar ────────────────────────────────── */
.filter-bar {
    margin-bottom: 2.5rem;
}
.filter-inner {
    display: flex;
    align-items: center;
    gap: 12px;
    background: rgba(255,255,255,0.04);
    border: 1px solid rgba(249,115,22,0.2);
    border-radius: 20px;
    padding: 10px;
    backdrop-filter: blur(12px);
    flex-wrap: wrap;
}

.search-wrap {
    flex: 1;
    min-width: 200px;
    position: relative;
    display: flex;
    align-items: center;
}
.search-icon {
    position: absolute;
    left: 14px;
    width: 16px; height: 16px;
    color: #f97316;
    opacity: 0.7;
    pointer-events: none;
}
.search-input {
    width: 100%;
    background: rgba(255,255,255,0.06);
    border: 1px solid rgba(255,255,255,0.08);
    border-radius: 12px;
    padding: 10px 14px 10px 40px;
    font-family: 'DM Sans', sans-serif;
    font-size: 0.88rem;
    color: #f0ede8;
    transition: border-color 0.2s, background 0.2s;
    outline: none;
}
.search-input::placeholder { color: rgba(240,237,232,0.35); }
.search-input:focus {
    border-color: rgba(249,115,22,0.5);
    background: rgba(249,115,22,0.06);
}

.select-wrap {
    position: relative;
    min-width: 180px;
}
.category-select {
    width: 100%;
    appearance: none;
    background: rgba(255,255,255,0.06);
    border: 1px solid rgba(255,255,255,0.08);
    border-radius: 12px;
    padding: 10px 36px 10px 16px;
    font-family: 'DM Sans', sans-serif;
    font-size: 0.88rem;
    color: #f0ede8;
    cursor: pointer;
    transition: border-color 0.2s;
    outline: none;
}
.category-select:focus { border-color: rgba(249,115,22,0.5); }
.category-select option { background: #1a1a1a; color: #f0ede8; }
.select-arrow {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    width: 14px; height: 14px;
    color: #f97316;
    pointer-events: none;
}

.btn-filter {
    background: linear-gradient(135deg, #f97316, #ea580c);
    color: #fff;
    border: none;
    border-radius: 12px;
    padding: 10px 24px;
    font-family: 'Syne', sans-serif;
    font-size: 0.82rem;
    font-weight: 700;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    cursor: pointer;
    transition: transform 0.15s, box-shadow 0.15s, opacity 0.15s;
    white-space: nowrap;
    box-shadow: 0 4px 16px rgba(249,115,22,0.3);
}
.btn-filter:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 24px rgba(249,115,22,0.45);
}
.btn-filter:active { transform: scale(0.97); }

.btn-reset {
    background: rgba(255,255,255,0.05);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 12px;
    padding: 10px;
    color: rgba(240,237,232,0.5);
    cursor: pointer;
    transition: color 0.2s, background 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
}
.btn-reset svg { width: 18px; height: 18px; }
.btn-reset:hover { color: #f97316; background: rgba(249,115,22,0.1); }

/* ─── Results Meta ──────────────────────────────── */
.results-meta {
    font-size: 0.8rem;
    color: rgba(240,237,232,0.4);
    margin-bottom: 1.5rem;
    letter-spacing: 0.02em;
}
.results-count {
    font-family: 'Syne', sans-serif;
    font-weight: 700;
    color: #f97316;
    font-size: 1rem;
}

/* ─── Grid ──────────────────────────────────────── */
.articles-grid {
    display: grid;
    grid-template-columns: repeat(1, 1fr);
    gap: 24px;
}
@media (min-width: 640px) {
    .articles-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (min-width: 1024px) {
    .articles-grid { grid-template-columns: repeat(3, 1fr); }
}

/* ─── Article Card ──────────────────────────────── */
.article-card {
    background: rgba(255,255,255,0.03);
    border: 1px solid rgba(255,255,255,0.07);
    border-radius: 20px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    transition: transform 0.3s ease, border-color 0.3s ease, box-shadow 0.3s ease;
    animation: cardReveal 0.5s ease both;
}
.article-card:hover {
    transform: translateY(-6px);
    border-color: rgba(249,115,22,0.35);
    box-shadow: 0 20px 60px rgba(0,0,0,0.5), 0 0 0 1px rgba(249,115,22,0.15);
}

.article-link {
    display: flex;
    flex-direction: column;
    height: 100%;
    text-decoration: none;
    color: inherit;
}
@keyframes cardReveal {
    from { opacity: 0; transform: translateY(20px); }
    to   { opacity: 1; transform: translateY(0); }
}

.card-img-wrap {
    position: relative;
    height: 220px;
    overflow: hidden;
}
.card-img {
    width: 100%; height: 100%;
    object-fit: cover;
    transition: transform 0.6s ease;
}
.article-card:hover .card-img { transform: scale(1.06); }
.card-img-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to bottom, transparent 40%, rgba(0,0,0,0.65) 100%);
}

.card-category-badge {
    position: absolute;
    top: 14px; left: 14px;
    background: #f97316;
    color: #fff;
    font-family: 'Syne', sans-serif;
    font-size: 0.65rem;
    font-weight: 700;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    padding: 4px 12px;
    border-radius: 999px;
}
.card-views-badge {
    position: absolute;
    bottom: 12px; right: 14px;
    display: flex;
    align-items: center;
    gap: 4px;
    background: rgba(0,0,0,0.5);
    backdrop-filter: blur(6px);
    color: rgba(255,255,255,0.7);
    font-size: 0.7rem;
    font-weight: 500;
    padding: 4px 10px;
    border-radius: 999px;
    border: 1px solid rgba(255,255,255,0.1);
}
.eye-icon { width: 13px; height: 13px; }

.card-body {
    padding: 1.4rem 1.5rem 1.5rem;
    display: flex;
    flex-direction: column;
    flex: 1;
}
.card-date {
    font-size: 0.72rem;
    font-weight: 500;
    color: rgba(240,237,232,0.35);
    text-transform: uppercase;
    letter-spacing: 0.06em;
    margin-bottom: 0.75rem;
    display: block;
}
.card-title {
    font-family: 'Syne', sans-serif;
    font-size: 1.05rem;
    font-weight: 700;
    line-height: 1.45;
    color: #f0ede8;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    transition: color 0.2s;
    margin: 0 0 auto;
    padding-bottom: 1.2rem;
}
.article-card:hover .card-title { color: #f97316; }

.card-footer {
    border-top: 1px solid rgba(255,255,255,0.07);
    padding-top: 1rem;
}
.read-more-link {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-family: 'Syne', sans-serif;
    font-size: 0.75rem;
    font-weight: 700;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: rgba(240,237,232,0.6);
    text-decoration: none;
    transition: color 0.2s;
}
.read-more-link:hover { color: #f97316; }
.link-arrow {
    width: 28px; height: 28px;
    display: flex; align-items: center; justify-content: center;
    background: rgba(249,115,22,0.1);
    border: 1px solid rgba(249,115,22,0.2);
    border-radius: 50%;
    transition: background 0.2s, transform 0.2s;
}
.link-arrow svg { width: 13px; height: 13px; color: #f97316; }
.read-more-link:hover .link-arrow {
    background: #f97316;
    transform: translateX(3px);
}
.read-more-link:hover .link-arrow svg { color: #fff; }

/* ─── Skeleton ──────────────────────────────────── */
.skeleton-card {
    background: rgba(255,255,255,0.03);
    border: 1px solid rgba(255,255,255,0.06);
    border-radius: 20px;
    overflow: hidden;
}
.skeleton-img {
    height: 220px;
    background: linear-gradient(90deg, rgba(255,255,255,0.04) 25%, rgba(255,255,255,0.08) 50%, rgba(255,255,255,0.04) 75%);
    background-size: 200% 100%;
    animation: shimmer 1.6s infinite;
}
.skeleton-body { padding: 1.4rem 1.5rem; }
.skeleton-line {
    height: 10px;
    background: linear-gradient(90deg, rgba(255,255,255,0.04) 25%, rgba(255,255,255,0.08) 50%, rgba(255,255,255,0.04) 75%);
    background-size: 200% 100%;
    animation: shimmer 1.6s infinite;
    border-radius: 6px;
    margin-bottom: 12px;
}
.skeleton-line.short { width: 40%; }
.skeleton-line.medium { width: 65%; }
@keyframes shimmer {
    0% { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}

/* ─── Empty State ───────────────────────────────── */
.empty-state {
    text-align: center;
    padding: 5rem 2rem;
    background: rgba(255,255,255,0.02);
    border: 1px dashed rgba(249,115,22,0.3);
    border-radius: 24px;
}
.empty-icon {
    width: 60px; height: 60px;
    margin: 0 auto 1.25rem;
    color: rgba(249,115,22,0.4);
}
.empty-icon svg { width: 60px; height: 60px; }
.empty-text {
    font-family: 'Syne', sans-serif;
    font-size: 1rem;
    font-weight: 600;
    color: rgba(240,237,232,0.4);
}

/* ─── Pagination ────────────────────────────────── */
.pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 16px;
    margin-top: 4rem;
}
.page-btn {
    width: 42px; height: 42px;
    display: flex; align-items: center; justify-content: center;
    background: rgba(255,255,255,0.04);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 12px;
    color: rgba(240,237,232,0.6);
    cursor: pointer;
    transition: all 0.2s;
}
.page-btn svg { width: 18px; height: 18px; }
.page-btn:hover:not(:disabled) {
    background: rgba(249,115,22,0.15);
    border-color: rgba(249,115,22,0.4);
    color: #f97316;
}
.page-btn:disabled { opacity: 0.25; cursor: not-allowed; }

.page-info {
    display: flex;
    align-items: center;
    gap: 6px;
    background: rgba(255,255,255,0.04);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 12px;
    padding: 8px 20px;
    font-family: 'Syne', sans-serif;
    font-size: 0.85rem;
    font-weight: 700;
}
.page-current { color: #f97316; font-size: 1rem; }
.page-sep { color: rgba(240,237,232,0.2); }
.page-last { color: rgba(240,237,232,0.4); }

/* ─── Responsive tweaks ─────────────────────────── */
@media (max-width: 640px) {
    .filter-inner { padding: 8px; gap: 8px; }
    .search-wrap { min-width: 100%; }
    .select-wrap { min-width: calc(50% - 4px); flex: 1; }
    .btn-filter { flex: 1; }
    .hero-title { font-size: 2.4rem; }
}
</style>