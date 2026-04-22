<script setup>
import { ref, onMounted, computed } from 'vue'
import { useRouter } from 'vue-router'
import { useI18n } from 'vue-i18n'
import Api from '@/api'
import { getImageUrl, handleImageError } from '@/utils/imageHelper'

const router = useRouter()
// Ekstrak 't' untuk terjemahan statis (locales), dan 'locale' untuk data dinamis (API)
const { t, locale } = useI18n()

const galleries = ref([])
const isLoading = ref(true)

const fetchGalleries = async () => {
    isLoading.value = true
    try {
        const response = await Api.get('/galleries')
        galleries.value = response.data.data || response.data || []
    } catch (error) {
        console.error("Error fetching galleries:", error)
    } finally {
        isLoading.value = false
    }
}

// Logika Pengelompokan Wadah
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
                cover: null,
                images: [] 
            }
        }
        groups[key].images.push(gallery)
    })

    let result = Object.values(groups)

    // Urutkan & Tentukan cover
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

    // AMBIL MAKSIMAL 3 WADAH TERATAS UNTUK PREVIEW HOME
    return result.slice(0, 3)
})

const goToGallery = () => {
    router.push('/galerry') 
}

onMounted(() => {
    fetchGalleries()
})
</script>

<template>
    <section class="py-24 bg-slate-50 relative overflow-hidden">
        <div class="container mx-auto px-4 max-w-7xl">
            <div class="flex flex-col md:flex-row justify-between items-end mb-12 relative z-10">
                <div class="max-w-2xl">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-orange-100/50 border border-orange-200 mb-4">
                        <span class="w-2 h-2 rounded-full bg-orange-500 animate-pulse"></span>
                        <span class="text-orange-600 font-bold tracking-wider uppercase text-xs">
                            {{ t('gallerySection.subtitle') }}
                        </span>
                    </div>
                    <h2 class="text-3xl md:text-4xl font-black text-slate-900 tracking-tight leading-tight">
                        {{ t('gallerySection.title') }}
                    </h2>
                </div>
                
                <button @click="goToGallery" class="mt-6 md:mt-0 px-6 py-3 bg-white hover:bg-slate-100 text-slate-700 font-bold rounded-xl border border-slate-200 transition-all flex items-center gap-2 shadow-sm group">
                    {{ t('gallerySection.seeAll') }}
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                </button>
            </div>

            <div v-if="isLoading" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 relative z-10">
                <div v-for="n in 3" :key="n" class="bg-white rounded-3xl h-80 animate-pulse border border-slate-100"></div>
            </div>

            <div v-else-if="groupedAlbums.length > 0" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 relative z-10">
                <div 
                    v-for="album in groupedAlbums" 
                    :key="album.id" 
                    @click="goToGallery"
                    class="relative group cursor-pointer bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden hover:shadow-xl hover:-translate-y-1 hover:shadow-slate-200/50 transition-all duration-300 aspect-4/3 sm:aspect-square"
                >
                    <img :src="getImageUrl(album.cover)" @error="handleImageError" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" />
                    
                    <div class="absolute top-4 right-4 bg-slate-900/80 backdrop-blur-sm text-white px-3 py-1.5 rounded-xl text-xs font-black tracking-wider flex items-center gap-2 shadow-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-orange-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                        {{ album.images.length }}
                    </div>
                    
                    <div class="absolute inset-0 bg-linear-to-t from-slate-900/90 via-slate-900/10 to-transparent opacity-100 sm:opacity-0 sm:group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-6">
                        <span class="text-orange-400 text-xs font-black uppercase tracking-wider mb-1">
                            {{ locale === 'en' && album.category?.name_en ? album.category.name_en : album.category?.name }}
                        </span>
                        <h3 class="text-white font-bold text-xl leading-tight">
                            {{ locale === 'en' && album.title_en ? album.title_en : album.title }}
                        </h3>
                        <p class="text-slate-300 text-xs mt-2 font-medium flex items-center gap-1 opacity-0 group-hover:opacity-100 transform translate-y-2 group-hover:translate-y-0 transition-all duration-300 delay-100">
                            {{ t('gallerySection.openGallery') }}
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                        </p>
                    </div>
                </div>
            </div>

            <div v-else class="text-center py-16 bg-white rounded-3xl border border-slate-100 shadow-sm relative z-10">
                <p class="text-slate-400 font-medium">{{ t('gallerySection.empty') }}</p>
            </div>
        </div>
    </section>
</template>