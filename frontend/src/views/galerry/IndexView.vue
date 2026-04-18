<script setup>
import { ref, onMounted, computed, watch, onUnmounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import Api from '@/api'
import Navbar from '@/components/Navbar.vue'
import { getImageUrl, handleImageError } from '@/utils/imageHelper'

// Router Setup untuk Sinkronisasi URL
const route = useRoute()
const router = useRouter()

const galleries = ref([])
const categories = ref([])
const isLoading = ref(true)

// State untuk Filter Kategori
const selectedCategory = ref('')
const showAllCategories = ref(false) // State untuk fitur "+" kategori

// FRONTEND PAGINATION (SINKRON DENGAN URL)
const currentPage = ref(parseInt(route.query.page) || 1)
const itemsPerPage = 10 // Jumlah wadah album yang tampil per halaman

// State untuk Lightbox / Modal
const activeAlbum = ref(null)
const currentIndex = ref(0)

const fetchGalleries = async () => {
    isLoading.value = true
    try {
        const response = await Api.get('/galleries')
        galleries.value = response.data.data || response.data || []
        
        // Ekstrak kategori unik
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

// ==========================================
// LOGIKA 1: FILTER KATEGORI PINTAR (Max 5)
// ==========================================
const visibleCategories = computed(() => {
    if (showAllCategories.value) {
        return categories.value
    }
    return categories.value.slice(0, 5) // Tampilkan hanya 5 pertama
})

const hasMoreCategories = computed(() => categories.value.length > 5)

// Reset Halaman ke 1 jika Kategori berubah
watch(selectedCategory, () => {
    if (currentPage.value !== 1) {
        changePage(1)
    }
})

// ==========================================
// LOGIKA 2: KELOMPOKKAN WADAH + SORTING
// ==========================================
const groupedAlbums = computed(() => {
    const groups = {}
    
    galleries.value.forEach(gallery => {
        const baseTitle = gallery.title_image ? gallery.title_image.replace(/\s\d+$/, '').trim() : 'Galeri'
        const key = `${baseTitle}_${gallery.category_id}`
        
        if (!groups[key]) {
            groups[key] = {
                id: key,
                title: baseTitle,
                category: gallery.category,
                category_id: gallery.category_id,
                cover: null,
                images: [] 
            }
        }
        groups[key].images.push(gallery)
    })

    let result = Object.values(groups)

    // Urutkan gambar di dalam wadah & tentukan cover
    result.forEach(album => {
        album.images.sort((a, b) => {
            const numA = parseInt(a.title_image?.match(/\d+$/)?.[0] || 0)
            const numB = parseInt(b.title_image?.match(/\d+$/)?.[0] || 0)
            return numA - numB 
        })
        
        if(album.images.length > 0) {
            album.cover = album.images[0].image
        }
    })

    // Terapkan Filter Kategori pada hasil Wadah
    if (selectedCategory.value) {
        result = result.filter(g => g.category_id === selectedCategory.value)
    }
    return result
})

// ==========================================
// LOGIKA 3: PAGINASI WADAH (Frontend)
// ==========================================
const totalPages = computed(() => {
    return Math.ceil(groupedAlbums.value.length / itemsPerPage) || 1
})

const paginatedAlbums = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage
    const end = start + itemsPerPage
    return groupedAlbums.value.slice(start, end)
})

// Fungsi ganti halaman merubah URL URL (?page=x)
const changePage = (page) => {
    if (page >= 1 && page <= totalPages.value) {
        router.push({ query: { ...route.query, page: page } })
        window.scrollTo({ top: 0, behavior: 'smooth' })
    }
}

// Pantau jika pengguna klik tombol "Back/Forward" di browser
watch(() => route.query.page, (newPage) => {
    currentPage.value = parseInt(newPage) || 1
})

// ==========================================
// LOGIKA 4: LIGHTBOX
// ==========================================
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
    if (currentIndex.value < activeAlbum.value.images.length - 1) {
        currentIndex.value++
    } else {
        currentIndex.value = 0 
    }
}

const prevImage = () => {
    if (!activeAlbum.value) return
    if (currentIndex.value > 0) {
        currentIndex.value--
    } else {
        currentIndex.value = activeAlbum.value.images.length - 1 
    }
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
    <div class="min-h-screen bg-slate-50 flex flex-col">
        <Navbar />

        <main class="flex-grow container mx-auto px-4 py-8 max-w-7xl">
            <div class="mb-10 text-center">
                <h1 class="text-3xl font-black text-slate-900 uppercase tracking-tight mt-16 mb-5">Galeri Kami</h1>
                <p class="text-slate-500 mb-8 font-medium">Dokumentasi kegiatan, fasilitas, dan momen berharga kami.</p>

                <div class="flex flex-wrap justify-center items-center gap-3 mb-8">
                    <button 
                        @click="selectedCategory = ''" 
                        :class="['px-6 py-2 rounded-full font-bold text-sm transition-all duration-300', selectedCategory === '' ? 'bg-slate-900 text-white shadow-md' : 'bg-white text-slate-500 border border-slate-200 hover:border-slate-400']"
                    >
                        Semua
                    </button>

                    <button 
                        v-for="category in visibleCategories" 
                        :key="category.id" 
                        @click="selectedCategory = category.id" 
                        :class="['px-6 py-2 rounded-full font-bold text-sm transition-all duration-300', selectedCategory === category.id ? 'bg-slate-900 text-white shadow-md' : 'bg-white text-slate-500 border border-slate-200 hover:border-slate-400']"
                    >
                        {{ category.name }}
                    </button>
                    
                    <button 
                        v-if="hasMoreCategories && !showAllCategories" 
                        @click="showAllCategories = true" 
                        class="px-5 py-2 rounded-full font-black text-sm bg-orange-50 text-orange-600 hover:bg-orange-100 transition-all border border-orange-100"
                    >
                        + {{ categories.length - 5 }} Lainnya
                    </button>

                    <button 
                        v-if="showAllCategories" 
                        @click="showAllCategories = false" 
                        class="px-5 py-2 rounded-full font-bold text-sm bg-slate-200 text-slate-600 hover:bg-slate-300 transition-all"
                    >
                        Tampilkan Sedikit
                    </button>
                </div>
            </div>

            <div v-if="isLoading" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 min-h-[50vh]">
                <div v-for="n in 8" :key="n" class="bg-white rounded-3xl h-64 animate-pulse border border-slate-100"></div>
            </div>

            <div v-else-if="paginatedAlbums.length > 0" class="min-h-[50vh] flex flex-col">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mb-10 flex-grow">
                    <div 
                        v-for="album in paginatedAlbums" 
                        :key="album.id" 
                        @click="openAlbum(album)"
                        class="relative group cursor-pointer bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden hover:shadow-xl hover:-translate-y-1 hover:shadow-slate-200/50 transition-all duration-300 aspect-square"
                    >
                        <img :src="getImageUrl(album.cover)" @error="handleImageError" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" />
                        
                        <div class="absolute top-4 right-4 bg-slate-900/80 backdrop-blur-sm text-white px-3 py-1.5 rounded-xl text-xs font-black tracking-wider flex items-center gap-2 shadow-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-orange-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            {{ album.images.length }}
                        </div>
                        
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-slate-900/10 to-transparent opacity-100 sm:opacity-0 sm:group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-6">
                            <span class="text-orange-400 text-xs font-black uppercase tracking-wider mb-1">
                                {{ album.category?.name }}
                            </span>
                            <h3 class="text-white font-bold text-xl leading-tight">
                                {{ album.title }}
                            </h3>
                            <p class="text-slate-300 text-xs mt-2 font-medium flex items-center gap-1 opacity-0 group-hover:opacity-100 transform translate-y-2 group-hover:translate-y-0 transition-all duration-300 delay-100">
                                Lihat Koleksi 
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                            </p>
                        </div>
                    </div>
                </div>

                <div v-if="totalPages > 1" class="flex flex-col sm:flex-row items-center justify-between bg-white border border-slate-200 px-6 py-4 rounded-2xl shadow-sm gap-4">
                    <span class="text-sm text-slate-500 font-medium">Halaman <span class="font-bold text-slate-800">{{ currentPage }}</span> dari <span class="font-bold text-slate-800">{{ totalPages }}</span></span>
                    
                    <div class="flex items-center gap-2">
                        <button 
                            @click="changePage(currentPage - 1)" 
                            :disabled="currentPage === 1" 
                            class="px-4 py-2 rounded-lg font-bold text-sm bg-slate-50 border border-slate-200 text-slate-600 hover:bg-slate-100 hover:text-slate-900 disabled:opacity-50 disabled:cursor-not-allowed transition-all"
                        >
                            Sebelumnya
                        </button>
                        
                        <div class="hidden sm:flex gap-1 mx-2">
                            <button 
                                v-for="page in totalPages" 
                                :key="page"
                                @click="changePage(page)"
                                :class="[
                                    'px-3.5 py-2 rounded-lg font-bold text-sm transition-all border',
                                    currentPage === page ? 'bg-slate-900 text-white border-slate-900 shadow-md' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-100'
                                ]"
                            >
                                {{ page }}
                            </button>
                        </div>

                        <button 
                            @click="changePage(currentPage + 1)" 
                            :disabled="currentPage === totalPages" 
                            class="px-4 py-2 rounded-lg font-bold text-sm bg-slate-50 border border-slate-200 text-slate-600 hover:bg-slate-100 hover:text-slate-900 disabled:opacity-50 disabled:cursor-not-allowed transition-all"
                        >
                            Selanjutnya
                        </button>
                    </div>
                </div>
            </div>

            <div v-else class="text-center py-24 bg-white rounded-3xl border border-slate-100 shadow-sm mt-10">
                <div class="mx-auto bg-slate-50 w-20 h-20 rounded-full flex items-center justify-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <h3 class="text-slate-700 font-bold text-xl mb-1">Galeri Tidak Ditemukan</h3>
                <p class="text-slate-400 font-medium">Belum ada foto yang diunggah untuk kategori ini.</p>
            </div>
        </main>

        <div v-if="activeAlbum" class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-950/95 backdrop-blur-md" @click.self="closeAlbum">
            
            <button @click="closeAlbum" class="absolute top-6 right-6 text-white/50 hover:text-white z-50 transition-colors cursor-pointer bg-white/10 p-2 rounded-full hover:bg-red-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <button @click="prevImage" v-if="activeAlbum.images.length > 1" class="absolute left-4 sm:left-10 p-3 sm:p-4 text-white/50 hover:text-white bg-black/20 hover:bg-black/50 rounded-full backdrop-blur transition-all cursor-pointer z-50">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 sm:h-10 sm:w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>
            
            <div class="relative max-w-6xl w-full px-12 flex flex-col items-center animate-fadeIn">
                <img 
                    :src="getImageUrl(activeAlbum.images[currentIndex].image)" 
                    :alt="activeAlbum.title"
                    @error="handleImageError"
                    class="max-h-[75vh] w-auto object-contain rounded-xl shadow-2xl shadow-black/50 transition-all duration-300"
                />
                
                <div class="mt-6 text-center">
                    <h2 class="text-white text-2xl font-bold">{{ activeAlbum.title }}</h2>
                    <p class="text-orange-400 mt-1 font-black text-sm uppercase tracking-widest">{{ activeAlbum.category?.name }}</p>
                    
                    <div v-if="activeAlbum.images.length > 1" class="mt-4 bg-white/10 text-white px-4 py-1.5 rounded-full text-xs font-black tracking-widest inline-block backdrop-blur-sm shadow-inner">
                        {{ currentIndex + 1 }} / {{ activeAlbum.images.length }}
                    </div>
                </div>
            </div>

            <button @click="nextImage" v-if="activeAlbum.images.length > 1" class="absolute right-4 sm:right-10 p-3 sm:p-4 text-white/50 hover:text-white bg-black/20 hover:bg-black/50 rounded-full backdrop-blur transition-all cursor-pointer z-50">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 sm:h-10 sm:w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
        </div>
    </div>
</template>

<style scoped>
.animate-fadeIn {
    animation: fadeIn 0.2s ease-out forwards;
}
@keyframes fadeIn {
    from { opacity: 0.8; transform: scale(0.98); }
    to { opacity: 1; transform: scale(1); }
}
</style>