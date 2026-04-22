<script setup>
import { ref, onMounted, computed, watch, onUnmounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useI18n } from 'vue-i18n'
import Api from '@/api'
import Navbar from '@/components/Navbar.vue'
import { getImageUrl, handleImageError } from '@/utils/imageHelper'

// Router & I18n Setup
const route = useRoute()
const router = useRouter()
const { locale } = useI18n()

const galleries = ref([])
const categories = ref([])
const isLoading = ref(true)

// State untuk Filter Kategori
const selectedCategory = ref('')
const showAllCategories = ref(false)

// FRONTEND PAGINATION (SINKRON DENGAN URL)
const currentPage = ref(parseInt(route.query.page) || 1)
const itemsPerPage = 10

// State untuk Lightbox / Modal
const activeAlbum = ref(null)
const currentIndex = ref(0)

const fetchGalleries = async () => {
    isLoading.value = true
    try {
        const response = await Api.get('/galleries')
        galleries.value = response.data.data || response.data || []
        
        const uniqueCategories = new Map()
        galleries.value.forEach(item => {
            if (item.category) {
                uniqueCategories.set(item.category.id, item.category)
            }
        })
        categories.value = Array.from(uniqueCategories.values())
        
    } catch (error) {
        console.error("Error fetching galleries:", error)
    } finally {
        isLoading.value = false
    }
}

const visibleCategories = computed(() => {
    if (showAllCategories.value) return categories.value
    return categories.value.slice(0, 5)
})

const hasMoreCategories = computed(() => categories.value.length > 5)

watch(selectedCategory, () => {
    if (currentPage.value !== 1) changePage(1)
})

const groupedAlbums = computed(() => {
    const groups = {}
    
    galleries.value.forEach(gallery => {
        const baseTitle = gallery.title_image ? gallery.title_image.replace(/\s\d+$/, '').trim() : 'Galeri'
        const baseTitleEn = gallery.title_image_en ? gallery.title_image_en.replace(/\s\d+$/, '').trim() : baseTitle
        const key = `${baseTitle}_${gallery.category_id}`
        
        if (!groups[key]) {
            groups[key] = {
                id: key,
                title: baseTitle,
                title_en: baseTitleEn,
                category: gallery.category,
                category_id: gallery.category_id,
                cover: null,
                images: [] 
            }
        }
        groups[key].images.push(gallery)
    })

    let result = Object.values(groups)

    result.forEach(album => {
        album.images.sort((a, b) => {
            const numA = parseInt(a.title_image?.match(/\d+$/)?.[0] || 0)
            const numB = parseInt(b.title_image?.match(/\d+$/)?.[0] || 0)
            return numA - numB 
        })
        if(album.images.length > 0) album.cover = album.images[0].image
    })

    if (selectedCategory.value) {
        result = result.filter(g => g.category_id === selectedCategory.value)
    }
    return result
})

const totalPages = computed(() => Math.ceil(groupedAlbums.value.length / itemsPerPage) || 1)

const paginatedAlbums = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage
    return groupedAlbums.value.slice(start, start + itemsPerPage)
})

const changePage = (page) => {
    if (page >= 1 && page <= totalPages.value) {
        router.push({ query: { ...route.query, page: page } })
        window.scrollTo({ top: 0, behavior: 'smooth' })
    }
}

watch(() => route.query.page, (newPage) => {
    currentPage.value = parseInt(newPage) || 1
})

const openAlbum = (album) => {
    activeAlbum.value = album
    currentIndex.value = 0
    document.body.style.overflow = 'hidden'
}

const closeAlbum = () => {
    activeAlbum.value = null
    currentIndex.value = 0
    document.body.style.overflow = 'auto'
}

const nextImage = () => {
    if (!activeAlbum.value) return
    currentIndex.value = currentIndex.value < activeAlbum.value.images.length - 1 ? currentIndex.value + 1 : 0
}

const prevImage = () => {
    if (!activeAlbum.value) return
    currentIndex.value = currentIndex.value > 0 ? currentIndex.value - 1 : activeAlbum.value.images.length - 1
}

const handleKeydown = (e) => {
    if (!activeAlbum.value) return
    if (e.key === 'ArrowRight') nextImage()
    if (e.key === 'ArrowLeft') prevImage()
    if (e.key === 'Escape') closeAlbum()
}

onMounted(() => {
    fetchGalleries()
    window.addEventListener('keydown', handleKeydown)
})

onUnmounted(() => {
    window.removeEventListener('keydown', handleKeydown)
    document.body.style.overflow = 'auto'
})
</script>

<template>
    <div class="gallery-root min-h-screen flex flex-col">
        <Navbar />

        <!-- ░░ HERO HEADER ░░ -->
        <header class="hero-header relative overflow-hidden pt-28 pb-16 px-4">
            <!-- Decorative blobs -->
            <div class="blob blob-1"></div>
            <div class="blob blob-2"></div>
            <div class="blob blob-3"></div>
            <!-- Grid overlay -->
            <div class="grid-overlay"></div>

            <div class="relative z-10 max-w-7xl mx-auto">
                <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-8">
                    <div>
                        <div class="eyebrow-tag mb-4">
                            <span class="eyebrow-dot"></span>
                            <span>{{ locale === 'en' ? 'Visual Archive' : 'Arsip Visual' }}</span>
                        </div>
                        <h1 class="hero-title">
                            {{ locale === 'en' ? 'Our' : 'Galeri' }}<br>
                            <span class="hero-title-accent">{{ locale === 'en' ? 'Gallery' : 'Kami' }}</span>
                        </h1>
                    </div>
                    <p class="hero-desc max-w-xs lg:max-w-sm lg:text-right pb-2">
                        {{ locale === 'en' ? 'Documentation of our activities, facilities, and precious moments.' : 'Dokumentasi kegiatan, fasilitas, dan momen berharga kami.' }}
                    </p>
                </div>

                <!-- CATEGORY FILTER -->
                <div class="filter-bar mt-10 flex flex-wrap items-center gap-2.5">
                    <button 
                        @click="selectedCategory = ''" 
                        :class="['filter-btn', selectedCategory === '' ? 'filter-btn--active' : '']"
                    >
                        {{ locale === 'en' ? 'All' : 'Semua' }}
                    </button>

                    <button 
                        v-for="category in visibleCategories" 
                        :key="category.id" 
                        @click="selectedCategory = category.id" 
                        :class="['filter-btn', selectedCategory === category.id ? 'filter-btn--active' : '']"
                    >
                        {{ locale === 'en' && category.name_en ? category.name_en : category.name }}
                    </button>
                    
                    <button 
                        v-if="hasMoreCategories && !showAllCategories" 
                        @click="showAllCategories = true" 
                        class="filter-btn filter-btn--more"
                    >
                        +{{ categories.length - 5 }} {{ locale === 'en' ? 'More' : 'Lainnya' }}
                    </button>

                    <button 
                        v-if="showAllCategories" 
                        @click="showAllCategories = false" 
                        class="filter-btn filter-btn--less"
                    >
                        {{ locale === 'en' ? 'Show Less' : 'Tampilkan Sedikit' }}
                    </button>
                </div>
            </div>
        </header>

        <!-- ░░ MAIN CONTENT ░░ -->
        <main class="flex-grow main-content px-4 py-12">
            <div class="max-w-7xl mx-auto">

                <!-- LOADING SKELETON -->
                <div v-if="isLoading" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-5 min-h-[50vh]">
                    <div v-for="n in 10" :key="n" class="skeleton-card" :style="`animation-delay: ${n * 0.07}s`"></div>
                </div>

                <!-- ALBUMS GRID -->
                <div v-else-if="paginatedAlbums.length > 0" class="min-h-[50vh] flex flex-col">
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-5 mb-12 flex-grow">
                        <div 
                            v-for="(album, idx) in paginatedAlbums" 
                            :key="album.id" 
                            @click="openAlbum(album)"
                            class="album-card"
                            :class="[idx % 7 === 0 ? 'album-card--featured sm:col-span-2 sm:row-span-2' : '']"
                            :style="`animation-delay: ${idx * 0.06}s`"
                        >
                            <img 
                                :src="getImageUrl(album.cover)" 
                                @error="handleImageError" 
                                class="album-card__img" 
                            />

                            <!-- Photo count badge -->
                            <div class="album-card__badge">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                {{ album.images.length }}
                            </div>

                            <!-- Hover overlay -->
                            <div class="album-card__overlay">
                                <div class="album-card__overlay-inner">
                                    <span class="album-card__cat">
                                        {{ locale === 'en' && album.category?.name_en ? album.category.name_en : album.category?.name }}
                                    </span>
                                    <h3 class="album-card__title">
                                        {{ locale === 'en' && album.title_en ? album.title_en : album.title }}
                                    </h3>
                                    <span class="album-card__cta">
                                        {{ locale === 'en' ? 'Open Collection' : 'Buka Koleksi' }}
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 ml-1" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- PAGINATION -->
                    <div v-if="totalPages > 1" class="pagination-bar">
                        <span class="pagination-info">
                            {{ locale === 'en' ? 'Page' : 'Hal.' }}
                            <strong>{{ currentPage }}</strong>
                            {{ locale === 'en' ? 'of' : 'dari' }}
                            <strong>{{ totalPages }}</strong>
                        </span>
                        
                        <div class="pagination-controls">
                            <button 
                                @click="changePage(currentPage - 1)" 
                                :disabled="currentPage === 1" 
                                class="pg-btn pg-btn--nav"
                                aria-label="Previous"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                                </svg>
                                <span class="hidden sm:inline ml-1">{{ locale === 'en' ? 'Prev' : 'Sebelumnya' }}</span>
                            </button>
                            
                            <div class="hidden sm:flex gap-1.5">
                                <button 
                                    v-for="page in totalPages" 
                                    :key="page"
                                    @click="changePage(page)"
                                    :class="['pg-btn', currentPage === page ? 'pg-btn--active' : 'pg-btn--number']"
                                >
                                    {{ page }}
                                </button>
                            </div>

                            <button 
                                @click="changePage(currentPage + 1)" 
                                :disabled="currentPage === totalPages" 
                                class="pg-btn pg-btn--nav"
                                aria-label="Next"
                            >
                                <span class="hidden sm:inline mr-1">{{ locale === 'en' ? 'Next' : 'Selanjutnya' }}</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- EMPTY STATE -->
                <div v-else class="empty-state">
                    <div class="empty-state__icon">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="empty-state__title">
                        {{ locale === 'en' ? 'Gallery Not Found' : 'Galeri Tidak Ditemukan' }}
                    </h3>
                    <p class="empty-state__desc">
                        {{ locale === 'en' ? 'No photos uploaded for this category yet.' : 'Belum ada foto yang diunggah untuk kategori ini.' }}
                    </p>
                </div>
            </div>
        </main>

        <!-- ░░ LIGHTBOX ░░ -->
        <Transition name="lb">
            <div 
                v-if="activeAlbum" 
                class="lightbox"
                @click.self="closeAlbum"
            >
                <!-- Close -->
                <button @click="closeAlbum" class="lb-close" aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <!-- Prev -->
                <button 
                    @click="prevImage" 
                    v-if="activeAlbum.images.length > 1" 
                    class="lb-nav lb-nav--prev"
                    aria-label="Previous"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>

                <!-- Image -->
                <div class="lb-content">
                    <div class="lb-img-wrap">
                        <img 
                            :src="getImageUrl(activeAlbum.images[currentIndex].image)" 
                            :alt="activeAlbum.title"
                            @error="handleImageError"
                            class="lb-img"
                            :key="currentIndex"
                        />
                    </div>

                    <div class="lb-meta">
                        <div class="lb-meta-inner">
                            <span class="lb-cat">
                                {{ locale === 'en' && activeAlbum.category?.name_en ? activeAlbum.category.name_en : activeAlbum.category?.name }}
                            </span>
                            <h2 class="lb-title">
                                {{ locale === 'en' && activeAlbum.title_en ? activeAlbum.title_en : activeAlbum.title }}
                            </h2>
                            <div v-if="activeAlbum.images.length > 1" class="lb-counter">
                                <span class="lb-counter-current">{{ currentIndex + 1 }}</span>
                                <span class="lb-counter-sep">/</span>
                                <span class="lb-counter-total">{{ activeAlbum.images.length }}</span>
                            </div>
                        </div>
                        <!-- Thumbnail strip -->
                        <div v-if="activeAlbum.images.length > 1" class="lb-thumbs">
                            <img 
                                v-for="(img, i) in activeAlbum.images.slice(0, 8)" 
                                :key="i"
                                :src="getImageUrl(img.image)"
                                @error="handleImageError"
                                @click="currentIndex = i"
                                :class="['lb-thumb', i === currentIndex ? 'lb-thumb--active' : '']"
                            />
                            <span v-if="activeAlbum.images.length > 8" class="lb-thumb-more">
                                +{{ activeAlbum.images.length - 8 }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Next -->
                <button 
                    @click="nextImage" 
                    v-if="activeAlbum.images.length > 1" 
                    class="lb-nav lb-nav--next"
                    aria-label="Next"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>
        </Transition>
    </div>
</template>

<style scoped>
/* ═══════════════════════════════════════════
   IMPORTS & CSS VARIABLES
═══════════════════════════════════════════ */
@import url('https://fonts.googleapis.com/css2?family=Syne:wght@400;700;800;900&family=DM+Sans:wght@300;400;500;600&display=swap');

:root {
    --orange: #f97316;
    --orange-deep: #ea580c;
    --orange-glow: #fb923c;
    --orange-pale: #fff7ed;
    --ink: #0f0a00;
    --ink-soft: #1c1410;
    --text: #3d2b1a;
    --text-muted: #8a7060;
    --border: #e8ddd5;
    --surface: #fffaf6;
    --white: #ffffff;
    --radius: 16px;
    --radius-lg: 24px;
}

/* ═══════════════════════════════════════════
   BASE
═══════════════════════════════════════════ */
.gallery-root {
    font-family: 'DM Sans', sans-serif;
    background-color: var(--surface);
    color: var(--text);
}

/* ═══════════════════════════════════════════
   HERO HEADER
═══════════════════════════════════════════ */
.hero-header {
    background: var(--ink);
    position: relative;
}

.grid-overlay {
    position: absolute;
    inset: 0;
    background-image: 
        linear-gradient(rgba(249,115,22,0.06) 1px, transparent 1px),
        linear-gradient(90deg, rgba(249,115,22,0.06) 1px, transparent 1px);
    background-size: 48px 48px;
    pointer-events: none;
}

.blob {
    position: absolute;
    border-radius: 9999px;
    filter: blur(80px);
    pointer-events: none;
    opacity: 0.35;
}
.blob-1 {
    width: 500px; height: 500px;
    background: radial-gradient(circle, #ea580c, #f97316);
    top: -180px; left: -100px;
}
.blob-2 {
    width: 350px; height: 350px;
    background: radial-gradient(circle, #fb923c, #fdba74);
    top: -80px; right: 80px;
    opacity: 0.2;
}
.blob-3 {
    width: 200px; height: 200px;
    background: #fff7ed;
    bottom: -60px; left: 40%;
    opacity: 0.07;
}

.eyebrow-tag {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(249,115,22,0.15);
    border: 1px solid rgba(249,115,22,0.3);
    color: var(--orange-glow);
    font-family: 'Syne', sans-serif;
    font-size: 0.7rem;
    font-weight: 700;
    letter-spacing: 0.15em;
    text-transform: uppercase;
    padding: 6px 14px;
    border-radius: 100px;
    /* backdrop-blur: 8px; */
}
.eyebrow-dot {
    width: 6px; height: 6px;
    border-radius: 50%;
    background: var(--orange);
    animation: pulse-dot 2s ease-in-out infinite;
}
@keyframes pulse-dot {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.5; transform: scale(0.7); }
}

.hero-title {
    font-family: 'Syne', sans-serif;
    font-size: clamp(3rem, 8vw, 6.5rem);
    font-weight: 900;
    line-height: 0.9;
    color: rgba(255,255,255,0.92);
    letter-spacing: -0.03em;
    margin: 0;
}
.hero-title-accent {
    color: var(--orange);
    display: block;
    -webkit-text-stroke: 2px var(--orange);
    /* text-stroke: 2px var(--orange); */
}

.hero-desc {
    color: rgba(255,255,255,0.45);
    font-size: 0.95rem;
    font-weight: 400;
    line-height: 1.65;
}

/* ═══════════════════════════════════════════
   FILTER BAR
═══════════════════════════════════════════ */
.filter-btn {
    font-family: 'Syne', sans-serif;
    font-size: 0.78rem;
    font-weight: 700;
    letter-spacing: 0.04em;
    padding: 8px 20px;
    border-radius: 100px;
    border: 1.5px solid rgba(255,255,255,0.12);
    background: rgba(255,255,255,0.06);
    color: rgba(255,255,255,0.55);
    cursor: pointer;
    transition: all 0.2s ease;
    backdrop-filter: blur(8px);
    white-space: nowrap;
}
.filter-btn:hover {
    border-color: rgba(249,115,22,0.5);
    color: var(--orange-glow);
    background: rgba(249,115,22,0.1);
}
.filter-btn--active {
    background: var(--orange);
    border-color: var(--orange);
    color: var(--white);
    box-shadow: 0 0 20px rgba(249,115,22,0.4), 0 4px 12px rgba(249,115,22,0.3);
}
.filter-btn--more {
    border-color: rgba(249,115,22,0.4);
    color: var(--orange-glow);
    background: rgba(249,115,22,0.08);
}
.filter-btn--less {
    border-color: rgba(255,255,255,0.12);
    color: rgba(255,255,255,0.4);
}

/* ═══════════════════════════════════════════
   MAIN CONTENT
═══════════════════════════════════════════ */
.main-content {
    background: var(--surface);
}

/* ═══════════════════════════════════════════
   SKELETON
═══════════════════════════════════════════ */
.skeleton-card {
    border-radius: var(--radius);
    aspect-ratio: 1;
    background: linear-gradient(110deg, #f0e8e0 30%, #e8ddd5 50%, #f0e8e0 70%);
    background-size: 200% 100%;
    animation: shimmer 1.4s ease infinite;
}
@keyframes shimmer {
    0% { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}

/* ═══════════════════════════════════════════
   ALBUM CARDS
═══════════════════════════════════════════ */
.album-card {
    position: relative;
    border-radius: var(--radius);
    overflow: hidden;
    cursor: pointer;
    aspect-ratio: 1;
    background: #e8ddd5;
    animation: card-in 0.5s ease both;
    box-shadow: 0 2px 16px rgba(0,0,0,0.06);
    transition: transform 0.35s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.3s ease;
}
.album-card:hover {
    transform: translateY(-6px) scale(1.02);
    box-shadow: 0 20px 40px rgba(249,115,22,0.18), 0 8px 20px rgba(0,0,0,0.12);
}
.album-card--featured {
    aspect-ratio: auto;
    min-height: 200px;
}

@keyframes card-in {
    from { opacity: 0; transform: translateY(24px) scale(0.96); }
    to { opacity: 1; transform: translateY(0) scale(1); }
}

.album-card__img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    display: block;
}
.album-card:hover .album-card__img {
    transform: scale(1.08);
}

.album-card__badge {
    position: absolute;
    top: 12px;
    right: 12px;
    display: flex;
    align-items: center;
    gap: 5px;
    background: rgba(15,10,0,0.75);
    backdrop-filter: blur(8px);
    color: var(--orange-glow);
    font-family: 'Syne', sans-serif;
    font-size: 0.7rem;
    font-weight: 800;
    letter-spacing: 0.05em;
    padding: 5px 10px;
    border-radius: 100px;
    border: 1px solid rgba(249,115,22,0.25);
    transition: all 0.2s ease;
    z-index: 2;
}
.album-card:hover .album-card__badge {
    background: var(--orange);
    color: white;
    border-color: var(--orange);
    box-shadow: 0 0 12px rgba(249,115,22,0.5);
}

.album-card__overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(
        to top,
        rgba(15,10,0,0.95) 0%,
        rgba(15,10,0,0.5) 45%,
        transparent 70%
    );
    opacity: 0;
    transition: opacity 0.3s ease;
    z-index: 1;
    display: flex;
    align-items: flex-end;
}
.album-card:hover .album-card__overlay,
/* @media (hover: none) {
    .album-card__overlay { opacity: 1; }
} */
/* Always show overlay on mobile */
/* @media (max-width: 640px) {
    .album-card__overlay { opacity: 1; }
} */

.album-card__overlay-inner {
    padding: 20px;
    transform: translateY(8px);
    transition: transform 0.3s ease 0.05s;
    width: 100%;
}
.album-card:hover .album-card__overlay-inner {
    transform: translateY(0);
}

.album-card__cat {
    display: block;
    color: var(--orange);
    font-family: 'Syne', sans-serif;
    font-size: 0.65rem;
    font-weight: 800;
    letter-spacing: 0.15em;
    text-transform: uppercase;
    margin-bottom: 4px;
}

.album-card__title {
    color: white;
    font-family: 'Syne', sans-serif;
    font-size: 1rem;
    font-weight: 800;
    line-height: 1.2;
    margin: 0 0 8px;
}

.album-card__cta {
    display: flex;
    align-items: center;
    color: rgba(255,255,255,0.55);
    font-size: 0.7rem;
    font-weight: 600;
    letter-spacing: 0.05em;
    opacity: 0;
    transform: translateX(-6px);
    transition: all 0.25s ease 0.1s;
}
.album-card:hover .album-card__cta {
    opacity: 1;
    transform: translateX(0);
}

/* ═══════════════════════════════════════════
   PAGINATION
═══════════════════════════════════════════ */
.pagination-bar {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    background: var(--white);
    border: 1.5px solid var(--border);
    border-radius: var(--radius-lg);
    padding: 18px 24px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.04);
}
@media (min-width: 640px) {
    .pagination-bar { flex-direction: row; }
}

.pagination-info {
    font-family: 'DM Sans', sans-serif;
    font-size: 0.85rem;
    color: var(--text-muted);
    gap: 4px;
}
.pagination-info strong {
    color: var(--ink);
    font-weight: 700;
}

.pagination-controls {
    display: flex;
    align-items: center;
    gap: 8px;
}

.pg-btn {
    display: flex;
    align-items: center;
    font-family: 'Syne', sans-serif;
    font-weight: 700;
    font-size: 0.8rem;
    padding: 9px 16px;
    border-radius: 12px;
    border: 1.5px solid var(--border);
    background: var(--white);
    color: var(--text);
    cursor: pointer;
    transition: all 0.2s ease;
}
.pg-btn:hover:not(:disabled) {
    border-color: var(--orange);
    color: var(--orange);
    background: var(--orange-pale);
}
.pg-btn:disabled {
    opacity: 0.35;
    cursor: not-allowed;
}
.pg-btn--active {
    background: var(--orange);
    border-color: var(--orange);
    color: white;
    box-shadow: 0 4px 14px rgba(249,115,22,0.35);
    padding: 9px 14px;
}
.pg-btn--number {
    padding: 9px 14px;
}
.pg-btn--nav {
    gap: 4px;
}

/* ═══════════════════════════════════════════
   EMPTY STATE
═══════════════════════════════════════════ */
.empty-state {
    text-align: center;
    padding: 80px 24px;
    background: var(--white);
    border: 1.5px solid var(--border);
    border-radius: var(--radius-lg);
    margin-top: 24px;
}
.empty-state__icon {
    width: 80px; height: 80px;
    background: var(--orange-pale);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    color: var(--orange);
    border: 2px solid rgba(249,115,22,0.15);
}
.empty-state__title {
    font-family: 'Syne', sans-serif;
    font-size: 1.3rem;
    font-weight: 800;
    color: var(--ink);
    margin: 0 0 8px;
}
.empty-state__desc {
    color: var(--text-muted);
    font-size: 0.9rem;
}

/* ═══════════════════════════════════════════
   LIGHTBOX
═══════════════════════════════════════════ */
.lightbox {
    position: fixed;
    inset: 0;
    z-index: 100;
    background: rgba(15,10,0,0.97);
    display: flex;
    align-items: center;
    justify-content: center;
    backdrop-filter: blur(16px);
}

.lb-close {
    position: absolute;
    top: 20px; right: 20px;
    z-index: 110;
    background: rgba(255,255,255,0.07);
    border: 1px solid rgba(255,255,255,0.1);
    color: rgba(255,255,255,0.6);
    border-radius: 50%;
    width: 44px; height: 44px;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
}
.lb-close:hover {
    background: #ef4444;
    border-color: #ef4444;
    color: white;
    transform: rotate(90deg);
}

.lb-nav {
    position: absolute;
    top: 50%; 
    transform: translateY(-50%);
    z-index: 110;
    background: rgba(255,255,255,0.07);
    border: 1px solid rgba(255,255,255,0.1);
    color: rgba(255,255,255,0.5);
    border-radius: 50%;
    width: 52px; height: 52px;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
    backdrop-filter: blur(8px);
}
.lb-nav:hover {
    background: var(--orange);
    border-color: var(--orange);
    color: white;
    box-shadow: 0 0 24px rgba(249,115,22,0.5);
}
.lb-nav--prev { left: 16px; }
.lb-nav--next { right: 16px; }
@media (min-width: 640px) {
    .lb-nav--prev { left: 28px; }
    .lb-nav--next { right: 28px; }
}

.lb-content {
    display: flex;
    flex-direction: column;
    align-items: center;
    max-width: 960px;
    width: 100%;
    padding: 0 80px;
    gap: 20px;
    animation: lb-in 0.25s ease-out;
}

@keyframes lb-in {
    from { opacity: 0; transform: scale(0.95); }
    to { opacity: 1; transform: scale(1); }
}

.lb-img-wrap {
    width: 100%;
    display: flex;
    justify-content: center;
}
.lb-img {
    max-height: 64vh;
    max-width: 100%;
    object-fit: contain;
    border-radius: 14px;
    box-shadow: 0 32px 80px rgba(0,0,0,0.7);
    animation: lb-img-in 0.3s ease;
}
@keyframes lb-img-in {
    from { opacity: 0; transform: scale(0.97); }
    to { opacity: 1; transform: scale(1); }
}

.lb-meta {
    width: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 14px;
}
.lb-meta-inner {
    text-align: center;
}
.lb-cat {
    display: inline-block;
    color: var(--orange);
    font-family: 'Syne', sans-serif;
    font-size: 0.65rem;
    font-weight: 800;
    letter-spacing: 0.18em;
    text-transform: uppercase;
    margin-bottom: 6px;
}
.lb-title {
    color: white;
    font-family: 'Syne', sans-serif;
    font-size: 1.35rem;
    font-weight: 800;
    margin: 0 0 10px;
    line-height: 1.2;
}
.lb-counter {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    background: rgba(255,255,255,0.08);
    border: 1px solid rgba(255,255,255,0.1);
    padding: 5px 14px;
    border-radius: 100px;
    font-family: 'Syne', sans-serif;
    font-size: 0.75rem;
    font-weight: 700;
    color: rgba(255,255,255,0.6);
}
.lb-counter-current { color: var(--orange); font-size: 0.9rem; }
.lb-counter-sep { opacity: 0.3; }

/* Thumbnail strip */
.lb-thumbs {
    display: flex;
    gap: 6px;
    align-items: center;
    flex-wrap: wrap;
    justify-content: center;
    max-width: 500px;
}
.lb-thumb {
    width: 46px; height: 46px;
    object-fit: cover;
    border-radius: 8px;
    cursor: pointer;
    opacity: 0.45;
    border: 2px solid transparent;
    transition: all 0.2s ease;
}
.lb-thumb:hover { opacity: 0.8; }
.lb-thumb--active {
    opacity: 1;
    border-color: var(--orange);
    box-shadow: 0 0 12px rgba(249,115,22,0.5);
}
.lb-thumb-more {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 46px; height: 46px;
    background: rgba(255,255,255,0.07);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 8px;
    color: rgba(255,255,255,0.4);
    font-family: 'Syne', sans-serif;
    font-size: 0.7rem;
    font-weight: 700;
}

/* ═══════════════════════════════════════════
   LIGHTBOX TRANSITION
═══════════════════════════════════════════ */
.lb-enter-active, .lb-leave-active { transition: opacity 0.25s ease; }
.lb-enter-from, .lb-leave-to { opacity: 0; }

/* ═══════════════════════════════════════════
   RESPONSIVE TWEAKS
═══════════════════════════════════════════ */
@media (max-width: 480px) {
    .lb-content { padding: 0 60px; }
    .lb-img { max-height: 52vh; }
    .lb-title { font-size: 1rem; }
    .lb-thumbs { display: none; }
}
</style>