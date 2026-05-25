<script setup>
import { ref, onMounted, computed, watch, onUnmounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useI18n } from 'vue-i18n'
import Api from '@/api'
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
                author: gallery.user ? gallery.user.name : 'Admin',
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
        result = result.filter(g => String(g.category_id) === String(selectedCategory.value))
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
        router.push({ query: { ...route.query, page: page } }).catch((err) => {
            console.warn('Router push ignored:', err);
        })
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
    <div class="min-h-screen bg-white flex flex-col">

        <!-- Hero Header -->
        <header class="pt-32 pb-14 px-4 sm:px-6 bg-gradient-to-b from-red-50/60 to-white">
            <div class="max-w-6xl mx-auto">
                <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6">
                    <div>
                        <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-red-50 border border-red-100 rounded-full mb-5">
                            <span class="w-1.5 h-1.5 bg-red-600 rounded-full animate-pulse"></span>
                            <span class="font-montserrat text-[11px] font-bold uppercase tracking-widest text-red-600">{{ locale === 'en' ? 'Visual Archive' : 'Arsip Visual' }}</span>
                        </div>
                        <h1 class="font-montserrat text-3xl sm:text-4xl md:text-5xl font-extrabold text-slate-900 tracking-tight leading-tight">
                            {{ locale === 'en' ? 'Our' : 'Galeri' }}
                            <span class="text-red-600">{{ locale === 'en' ? 'Gallery' : 'Kami' }}</span>
                        </h1>
                    </div>
                    <p class="font-quicksand text-sm text-slate-500 max-w-xs lg:max-w-sm lg:text-right pb-1">
                        {{ locale === 'en' ? 'Documentation of our activities, facilities, and precious moments.' : 'Dokumentasi kegiatan, fasilitas, dan momen berharga kami.' }}
                    </p>
                </div>

                <!-- Category Filter -->
                <div class="mt-8 flex flex-wrap items-center gap-2.5">
                    <button 
                        @click="selectedCategory = ''" 
                        :class="['px-4 py-2 rounded-full text-xs font-montserrat font-bold uppercase tracking-wider border transition-all duration-200 focus-visible:ring-2 focus-visible:ring-red-500 focus-visible:ring-offset-2', selectedCategory === '' ? 'bg-red-600 border-red-600 text-white shadow-lg shadow-red-200' : 'bg-white border-slate-200 text-slate-600 hover:border-red-200 hover:text-red-600 hover:bg-red-50']"
                    >
                        {{ locale === 'en' ? 'All' : 'Semua' }}
                    </button>

                    <button 
                        v-for="category in visibleCategories" 
                        :key="category.id" 
                        @click="selectedCategory = category.id" 
                        :class="['px-4 py-2 rounded-full text-xs font-montserrat font-bold uppercase tracking-wider border transition-all duration-200 focus-visible:ring-2 focus-visible:ring-red-500 focus-visible:ring-offset-2', selectedCategory === category.id ? 'bg-red-600 border-red-600 text-white shadow-lg shadow-red-200' : 'bg-white border-slate-200 text-slate-600 hover:border-red-200 hover:text-red-600 hover:bg-red-50']"
                    >
                        {{ locale === 'en' && category.name_en ? category.name_en : category.name }}
                    </button>
                    
                    <button 
                        v-if="hasMoreCategories && !showAllCategories" 
                        @click="showAllCategories = true" 
                        class="px-4 py-2 rounded-full text-xs font-montserrat font-bold uppercase tracking-wider border border-red-200 text-red-600 bg-red-50 hover:bg-red-100 transition-all"
                    >
                        +{{ categories.length - 5 }} {{ locale === 'en' ? 'More' : 'Lainnya' }}
                    </button>

                    <button 
                        v-if="showAllCategories" 
                        @click="showAllCategories = false" 
                        class="px-4 py-2 rounded-full text-xs font-montserrat font-bold uppercase tracking-wider border border-slate-200 text-slate-400 hover:text-slate-600 transition-all"
                    >
                        {{ locale === 'en' ? 'Show Less' : 'Tampilkan Sedikit' }}
                    </button>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-grow px-4 sm:px-6 py-10">
            <div class="max-w-6xl mx-auto">

                <!-- Loading Skeleton -->
                <div v-if="isLoading" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-5" aria-busy="true" aria-live="polite">
                    <div v-for="n in 10" :key="n" class="aspect-square rounded-2xl bg-slate-100 animate-pulse"></div>
                </div>

                <!-- Albums Grid -->
                <div v-else-if="paginatedAlbums.length > 0" class="flex flex-col">
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-5 mb-12">
                        <div 
                            v-for="(album, idx) in paginatedAlbums" 
                            :key="album.id" 
                            @click="openAlbum(album)"
                            class="group relative rounded-2xl overflow-hidden cursor-pointer aspect-square bg-slate-100 shadow-sm hover:shadow-xl hover:shadow-red-100/50 hover:-translate-y-1 transition-all duration-300 focus-visible:ring-2 focus-visible:ring-red-500 focus-visible:ring-offset-2"
                            :class="[idx % 7 === 0 ? 'sm:col-span-2 sm:row-span-2 aspect-auto min-h-[200px]' : '']"
                            :style="`animation: cardFadeIn 0.4s ease both; animation-delay: ${idx * 60}ms`"
                            tabindex="0"
                            @keydown.enter="openAlbum(album)"
                            :aria-label="(locale === 'en' && album.title_en ? album.title_en : album.title) + ' - ' + album.images.length + ' photos'"
                        >
                            <img 
                                :src="getImageUrl(album.cover)" 
                                @error="handleImageError" 
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" 
                            />

                            <!-- Photo count badge -->
                            <div class="absolute top-3 right-3 flex items-center gap-1.5 px-2.5 py-1 bg-white/90 backdrop-blur-sm rounded-full text-slate-700 text-[11px] font-montserrat font-bold border border-slate-100 group-hover:bg-red-600 group-hover:text-white group-hover:border-red-600 transition-all duration-200 z-10">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                {{ album.images.length }}
                            </div>

                            <!-- Hover overlay -->
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-slate-900/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end z-[1]">
                                <div class="p-5 w-full transform translate-y-2 group-hover:translate-y-0 transition-transform duration-300">
                                    <span class="block font-montserrat text-[10px] font-bold text-red-400 uppercase tracking-wider mb-1">
                                        {{ locale === 'en' && album.category?.name_en ? album.category.name_en : album.category?.name }}
                                    </span>
                                    <h3 class="font-montserrat text-sm font-bold text-white leading-snug mb-1.5">
                                        {{ locale === 'en' && album.title_en ? album.title_en : album.title }}
                                    </h3>
                                    <div class="flex items-center gap-1.5 text-[10px] font-quicksand font-medium text-slate-300 mb-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        {{ album.author }}
                                    </div>
                                    <span class="inline-flex items-center font-montserrat text-[10px] font-bold uppercase tracking-wider text-white/70 group-hover:text-white transition-colors">
                                        {{ locale === 'en' ? 'Open Collection' : 'Buka Koleksi' }}
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 ml-1" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pagination -->
                    <div v-if="totalPages > 1" class="flex justify-center items-center gap-3 mt-4 mb-6">
                        <button 
                            @click="changePage(currentPage - 1)" 
                            :disabled="currentPage === 1" 
                            class="w-10 h-10 flex items-center justify-center rounded-xl border border-slate-200 text-slate-500 hover:border-red-200 hover:text-red-600 hover:bg-red-50 disabled:opacity-30 disabled:cursor-not-allowed transition-all focus-visible:ring-2 focus-visible:ring-red-500 focus-visible:ring-offset-2"
                            aria-label="Previous"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>

                        <div class="flex items-center gap-1.5">
                            <button 
                                v-for="page in totalPages" 
                                :key="page"
                                @click="changePage(page)"
                                :class="['w-10 h-10 flex items-center justify-center rounded-xl font-montserrat text-sm font-bold transition-all focus-visible:ring-2 focus-visible:ring-red-500 focus-visible:ring-offset-2', currentPage === page ? 'bg-red-600 text-white shadow-lg shadow-red-200' : 'border border-slate-200 text-slate-500 hover:border-red-200 hover:text-red-600 hover:bg-red-50']"
                            >
                                {{ page }}
                            </button>
                        </div>

                        <button 
                            @click="changePage(currentPage + 1)" 
                            :disabled="currentPage === totalPages" 
                            class="w-10 h-10 flex items-center justify-center rounded-xl border border-slate-200 text-slate-500 hover:border-red-200 hover:text-red-600 hover:bg-red-50 disabled:opacity-30 disabled:cursor-not-allowed transition-all focus-visible:ring-2 focus-visible:ring-red-500 focus-visible:ring-offset-2"
                            aria-label="Next"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-else class="text-center py-20 px-6">
                    <div class="w-16 h-16 mx-auto mb-4 flex items-center justify-center bg-red-50 rounded-2xl">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <p class="font-montserrat text-lg font-bold text-slate-700 mb-1">
                        {{ locale === 'en' ? 'Gallery Not Found' : 'Galeri Tidak Ditemukan' }}
                    </p>
                    <p class="font-quicksand text-sm text-slate-400">
                        {{ locale === 'en' ? 'No photos uploaded for this category yet.' : 'Belum ada foto yang diunggah untuk kategori ini.' }}
                    </p>
                </div>
            </div>
        </main>

        <!-- Lightbox -->
        <Transition name="lb">
            <div 
                v-if="activeAlbum" 
                class="fixed inset-0 z-[100] bg-slate-900/97 backdrop-blur-xl flex items-center justify-center"
                @click.self="closeAlbum"
            >
                <!-- Close -->
                <button @click="closeAlbum" class="absolute top-5 right-5 z-[110] w-11 h-11 flex items-center justify-center rounded-full bg-white/10 border border-white/10 text-white/60 hover:bg-red-600 hover:border-red-600 hover:text-white hover:rotate-90 transition-all duration-200" aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <!-- Prev -->
                <button 
                    @click="prevImage" 
                    v-if="activeAlbum.images.length > 1" 
                    class="absolute left-4 sm:left-7 top-1/2 -translate-y-1/2 z-[110] w-12 h-12 flex items-center justify-center rounded-full bg-white/10 border border-white/10 text-white/50 hover:bg-red-600 hover:border-red-600 hover:text-white hover:shadow-lg hover:shadow-red-600/30 backdrop-blur-sm transition-all duration-200"
                    aria-label="Previous"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>

                <!-- Image Content -->
                <div class="flex flex-col items-center max-w-[960px] w-full px-16 sm:px-20 gap-5">
                    <div class="w-full flex justify-center">
                        <img 
                            :src="getImageUrl(activeAlbum.images[currentIndex].image)" 
                            :alt="activeAlbum.title"
                            @error="handleImageError"
                            class="max-h-[64vh] max-w-full object-contain rounded-2xl shadow-2xl"
                            :key="currentIndex"
                        />
                    </div>

                    <div class="w-full flex flex-col items-center gap-3">
                        <div class="text-center">
                            <span class="block font-montserrat text-[10px] font-bold text-red-500 uppercase tracking-widest mb-1.5">
                                {{ locale === 'en' && activeAlbum.category?.name_en ? activeAlbum.category.name_en : activeAlbum.category?.name }}
                            </span>
                            <h2 class="font-montserrat text-xl font-bold text-white mb-2">
                                {{ locale === 'en' && activeAlbum.title_en ? activeAlbum.title_en : activeAlbum.title }}
                            </h2>
                            <div class="flex items-center justify-center gap-1.5 text-xs font-quicksand text-slate-300 mb-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                By {{ activeAlbum.author }}
                            </div>
                            <div v-if="activeAlbum.images.length > 1" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-white/10 border border-white/10 rounded-full font-montserrat text-xs font-bold text-white/60">
                                <span class="text-red-500 text-sm">{{ currentIndex + 1 }}</span>
                                <span class="opacity-30">/</span>
                                <span>{{ activeAlbum.images.length }}</span>
                            </div>
                        </div>
                        <!-- Thumbnail strip -->
                        <div v-if="activeAlbum.images.length > 1" class="flex gap-1.5 items-center flex-wrap justify-center max-w-[500px]">
                            <img 
                                v-for="(img, i) in activeAlbum.images.slice(0, 8)" 
                                :key="i"
                                :src="getImageUrl(img.image)"
                                @error="handleImageError"
                                @click="currentIndex = i"
                                :class="['w-11 h-11 object-cover rounded-lg cursor-pointer border-2 transition-all duration-200', i === currentIndex ? 'opacity-100 border-red-500 shadow-lg shadow-red-500/30' : 'opacity-40 border-transparent hover:opacity-70']"
                            />
                            <span v-if="activeAlbum.images.length > 8" class="w-11 h-11 flex items-center justify-center bg-white/10 border border-white/10 rounded-lg text-white/40 font-montserrat text-[10px] font-bold">
                                +{{ activeAlbum.images.length - 8 }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Next -->
                <button 
                    @click="nextImage" 
                    v-if="activeAlbum.images.length > 1" 
                    class="absolute right-4 sm:right-7 top-1/2 -translate-y-1/2 z-[110] w-12 h-12 flex items-center justify-center rounded-full bg-white/10 border border-white/10 text-white/50 hover:bg-red-600 hover:border-red-600 hover:text-white hover:shadow-lg hover:shadow-red-600/30 backdrop-blur-sm transition-all duration-200"
                    aria-label="Next"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>
        </Transition>
    </div>
</template>

<style scoped>
/* Card fade-in animation */
@keyframes cardFadeIn {
    from { opacity: 0; transform: translateY(12px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Lightbox transition */
.lb-enter-active, .lb-leave-active { transition: opacity 0.25s ease; }
.lb-enter-from, .lb-leave-to { opacity: 0; }

/* Reduced motion */
@media (prefers-reduced-motion: reduce) {
    * {
        animation: none !important;
        transition-duration: 0.01ms !important;
    }
}

/* Mobile lightbox adjustments */
@media (max-width: 480px) {
    .max-h-\[64vh\] {
        max-height: 52vh;
    }
}
</style>
