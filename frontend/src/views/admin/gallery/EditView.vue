<script setup>
import { ref, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import Api from '@/api'
import Sidebar from '@/components/admin/Sidebar.vue'
import Navbar from '@/components/admin/Navbar.vue'
import { getImageUrl, handleImageError } from '@/utils/imageHelper'

const router = useRouter()
const route = useRoute()
const categories = ref([])
const isLoading = ref(false)

const baseTitle = ref('')
const albumItems = ref([])

const isTranslatingTitle = ref(false)
let translateTitleTimeout = null

const form = ref({
    category_id: '',
    title_image: '',
    title_image_en: '',
    slug: '',
    new_images: []
})
const newPreviews = ref([])

// STATE & FUNGSI DRAG (FOTO LAMA/ALBUM ITEMS)
const draggedOldIndex = ref(null)
const dragOverOldIndex = ref(null)

const handleDragStartOld = (index, event) => {
    draggedOldIndex.value = index
    event.dataTransfer.effectAllowed = 'move'
}

const handleDropOld = (index) => {
    if (draggedOldIndex.value === null || draggedOldIndex.value === index) return
    const draggedItem = albumItems.value.splice(draggedOldIndex.value, 1)[0]
    albumItems.value.splice(index, 0, draggedItem)
    draggedOldIndex.value = null
    dragOverOldIndex.value = null
}

// STATE & FUNGSI DRAG (FOTO BARU/NEW PREVIEWS)
const draggedNewIndex = ref(null)
const dragOverNewIndex = ref(null)

const handleDragStartNew = (index, event) => {
    draggedNewIndex.value = index
    event.dataTransfer.effectAllowed = 'move'
}

const handleDropNew = (index) => {
    if (draggedNewIndex.value === null || draggedNewIndex.value === index) return
    const draggedFile = form.value.new_images.splice(draggedNewIndex.value, 1)[0]
    form.value.new_images.splice(index, 0, draggedFile)
    
    const draggedPreview = newPreviews.value.splice(draggedNewIndex.value, 1)[0]
    newPreviews.value.splice(index, 0, draggedPreview)
    
    draggedNewIndex.value = null
    dragOverNewIndex.value = null
}

const generateSlug = (text) => {
    return text.toString().toLowerCase().replace(/\s+/g, '-').replace(/[^\w\-]+/g, '').replace(/\-\-+/g, '-').replace(/^-+/, '').replace(/-+$/, '')
}

const handleTitleInput = () => {
    form.value.slug = generateSlug(form.value.title_image)
}

const autoTranslateTitle = () => {
    handleTitleInput()
    if (translateTitleTimeout) clearTimeout(translateTitleTimeout)

    if (!form.value.title_image.trim()) {
        form.value.title_image_en = ''
        isTranslatingTitle.value = false
        return
    }

    isTranslatingTitle.value = true
    translateTitleTimeout = setTimeout(async () => {
        try {
            const textToTranslate = encodeURIComponent(form.value.title_image.trim())
            const res = await fetch(`https://api.mymemory.translated.net/get?q=${textToTranslate}&langpair=id|en`)
            const data = await res.json()
            if (data && data.responseData) form.value.title_image_en = data.responseData.translatedText
        } catch (error) {
            console.error("Gagal terjemah judul:", error)
        } finally {
            isTranslatingTitle.value = false
        }
    }, 600)
}

const fetchData = async () => {
    isLoading.value = true
    try {
        const catRes = await Api.get('/admin/categories/list')
        categories.value = catRes.data.data || catRes.data

        const galRes = await Api.get('/admin/galleries')
        const allGalleries = galRes.data.data || galRes.data || []

        const representativeItem = allGalleries.find(g => g.id == route.params.id)
        
        if (representativeItem) {
            baseTitle.value = representativeItem.title_image.replace(/\s\d+$/, '').trim()
            const baseTitleEn = representativeItem.title_image_en ? representativeItem.title_image_en.replace(/\s\d+$/, '').trim() : ''
            const baseSlug = representativeItem.slug ? representativeItem.slug.replace(/\-\d+$/, '') : generateSlug(baseTitle.value)
            
            // PERBAIKAN: Filter sekaligus SORTING berdasarkan angka di belakang nama file
            albumItems.value = allGalleries.filter(g => {
                const gBase = g.title_image ? g.title_image.replace(/\s\d+$/, '').trim() : 'Galeri'
                return gBase === baseTitle.value && g.category_id === representativeItem.category_id
            }).sort((a, b) => {
                // Ekstrak angka dari title (contoh: "Gathering 2" -> 2)
                const numA = parseInt(a.title_image?.match(/\d+$/)?.[0] || 0)
                const numB = parseInt(b.title_image?.match(/\d+$/)?.[0] || 0)
                return numA - numB // Urutkan dari kecil ke besar (1, 2, 3...)
            })

            form.value.title_image = baseTitle.value
            form.value.title_image_en = baseTitleEn
            form.value.slug = baseSlug
            form.value.category_id = representativeItem.category_id
        }
    } catch (error) {
        console.error(error)
        alert("Gagal memuat data wadah.")
    } finally {
        isLoading.value = false
    }
}

const handleFileChange = (e) => {
    const files = Array.from(e.target.files)
    if (files.length + albumItems.value.length > 20) {
        alert('Terlalu banyak gambar dalam satu wadah. Batas wajar disarankan max 20.')
        return
    }

    form.value.new_images = files
    newPreviews.value = []
    files.forEach(file => {
        const reader = new FileReader()
        reader.onload = (e) => newPreviews.value.push(e.target.result)
        reader.readAsDataURL(file)
    })
}

const deleteSingleImage = async (id) => {
    if (!confirm('Hapus foto ini dari wadah?')) return
    try {
        await Api.delete(`/admin/galleries/${id}`)
        albumItems.value = albumItems.value.filter(item => item.id !== id)
        if (albumItems.value.length === 0) {
            router.push('/admin/gallery')
        }
    } catch (error) {
        alert('Gagal menghapus foto')
    }
}

const handleSubmit = async () => {
    isLoading.value = true
    try {
        // Update urutan nama di Database berdasarkan urutan array visual (setelah di drag)
        const updatePromises = albumItems.value.map((item, index) => {
            const formData = new FormData()
            formData.append('_method', 'PUT')
            formData.append('category_id', form.value.category_id)
            formData.append('title_image', `${form.value.title_image} ${index + 1}`)
            if(form.value.title_image_en) {
                formData.append('title_image_en', `${form.value.title_image_en} ${index + 1}`)
            }
            formData.append('slug', `${form.value.slug}-${index + 1}`)
            formData.append('meta_title_image', `${form.value.title_image} ${index + 1}`)
            return Api.post(`/admin/galleries/${item.id}`, formData)
        })
        await Promise.all(updatePromises)

        // Upload baru
        if (form.value.new_images.length > 0) {
            const bulkData = new FormData()
            bulkData.append('category_id', form.value.category_id)
            bulkData.append('title_image', form.value.title_image) 
            bulkData.append('title_image_en', form.value.title_image_en)
            bulkData.append('slug', form.value.slug)
            
            form.value.new_images.forEach(file => {
                bulkData.append('images[]', file)
            })

            await Api.post('/admin/galleries/bulk-store', bulkData)
        }

        alert('Wadah galeri & urutan berhasil diperbarui!')
        router.push('/admin/gallery')

    } catch (error) {
        console.error(error)
        alert('Terjadi kesalahan saat menyimpan perubahan.')
    } finally {
        isLoading.value = false
    }
}

onMounted(fetchData)
</script>

<template>
    <div class="flex min-h-screen bg-slate-50">
        <Sidebar />
        <div class="flex-1">
            <Navbar />
            <main class="p-6">
                <div class="max-w-6xl mx-auto">
                    <div class="mb-6 flex justify-between items-end">
                        <div>
                            <router-link to="/admin/gallery" class="text-slate-400 hover:text-slate-600 text-sm font-bold flex items-center gap-2 mb-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                                Kembali
                            </router-link>
                            <h1 class="text-2xl font-bold text-slate-800">Manajemen Wadah: "{{ baseTitle }}"</h1>
                        </div>
                    </div>

                    <div v-if="isLoading && albumItems.length === 0" class="bg-white rounded-3xl p-8 border border-slate-200 shadow-sm animate-pulse h-64"></div>

                    <form v-else @submit.prevent="handleSubmit" class="space-y-6">
                        
                        <div class="bg-white rounded-3xl p-8 border border-slate-200 shadow-sm">
                            <h2 class="text-sm font-black text-slate-700 uppercase tracking-wider mb-6 border-b border-slate-100 pb-3">1. Informasi Wadah</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Kategori</label>
                                    <select v-model="form.category_id" class="w-full text-black bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500/20 font-medium">
                                        <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Judul Dasar Wadah (ID)</label>
                                    <input v-model="form.title_image" @input="autoTranslateTitle" type="text" class="w-full text-black bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500/20 font-medium" required />
                                </div>
                                <div class="relative">
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Judul Dasar Wadah (EN)</label>
                                    <input v-model="form.title_image_en" type="text" class="w-full text-black bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500/20 font-medium" placeholder="Terjemahan otomatis muncul di sini..." />
                                    <span v-if="isTranslatingTitle" class="absolute right-4 top-[42px] text-xs font-bold text-slate-400 animate-pulse">Translating...</span>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Slug</label>
                                    <input v-model="form.slug" type="text" class="w-full text-slate-500 bg-slate-100 border border-slate-200 rounded-xl px-4 py-3 cursor-not-allowed font-medium" disabled placeholder="otomatis-mengikuti-judul" />
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-3xl p-8 border border-slate-200 shadow-sm">
                            <div class="flex justify-between items-center border-b border-slate-100 pb-3 mb-6">
                                <h2 class="text-sm font-black text-slate-700 uppercase tracking-wider">2. Foto di Dalam Wadah ({{ albumItems.length }})</h2>
                                <span class="text-xs bg-slate-100 text-slate-500 px-3 py-1 rounded-full font-bold">Geser untuk mengatur urutan penayangan</span>
                            </div>
                            
                            <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 gap-4">
                                <div 
                                    v-for="(item, index) in albumItems" 
                                    :key="item.id"
                                    draggable="true"
                                    @dragstart="handleDragStartOld(index, $event)"
                                    @dragover.prevent="dragOverOldIndex = index"
                                    @dragleave.prevent="dragOverOldIndex = null"
                                    @drop.prevent="handleDropOld(index)"
                                    :class="[
                                        'relative group aspect-square rounded-2xl overflow-hidden shadow-sm cursor-move transition-all duration-200 border-2',
                                        dragOverOldIndex === index ? 'border-blue-500 scale-105 z-10' : 'border-transparent border-slate-200',
                                        draggedOldIndex === index ? 'opacity-40' : 'opacity-100'
                                    ]"
                                >
                                    <img :src="getImageUrl(item.image)" @error="handleImageError" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500 pointer-events-none" />
                                    
                                    <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center backdrop-blur-sm pointer-events-auto">
                                        <button @click.prevent="deleteSingleImage(item.id)" class="bg-red-500 text-white p-2 rounded-full hover:bg-red-600 transform hover:scale-110 transition-all">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                                        </button>
                                    </div>
                                    <span class="absolute bottom-2 left-2 bg-slate-900/80 text-white text-[10px] px-2 py-0.5 rounded-full pointer-events-none shadow-sm">{{ index + 1 }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-3xl p-8 border border-slate-200 shadow-sm">
                            <h2 class="text-sm font-black text-slate-700 uppercase tracking-wider mb-6 border-b border-slate-100 pb-3">3. Tambah Foto Ke Wadah Ini</h2>
                            <div class="border-2 border-dashed border-slate-200 rounded-2xl p-6 text-center hover:bg-blue-50/50 hover:border-blue-300 transition-all cursor-pointer relative mb-6">
                                <input type="file" multiple @change="handleFileChange" class="absolute inset-0 opacity-0 cursor-pointer z-10" accept="image/*" />
                                <div class="text-slate-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mx-auto mb-2 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                                    <p class="font-bold text-slate-600">Klik / Seret foto tambahan ke sini</p>
                                </div>
                            </div>

                            <div v-if="newPreviews.length > 0" class="grid grid-cols-3 sm:grid-cols-6 gap-4">
                                <div 
                                    v-for="(src, idx) in newPreviews" 
                                    :key="idx"
                                    draggable="true"
                                    @dragstart="handleDragStartNew(idx, $event)"
                                    @dragover.prevent="dragOverNewIndex = idx"
                                    @dragleave.prevent="dragOverNewIndex = null"
                                    @drop.prevent="handleDropNew(idx)"
                                    :class="[
                                        'aspect-square rounded-xl overflow-hidden relative cursor-move transition-all duration-200 border-2',
                                        dragOverNewIndex === idx ? 'border-blue-500 scale-105 z-10' : 'border-green-300',
                                        draggedNewIndex === idx ? 'opacity-40' : 'opacity-100'
                                    ]"
                                >
                                    <img :src="src" class="w-full h-full object-cover pointer-events-none" />
                                    <div class="absolute top-1 left-1 bg-black/60 text-white text-[10px] px-1.5 rounded-md pointer-events-none">{{ idx + 1 }}</div>
                                    <div class="absolute bottom-1 right-1 bg-green-500 text-white text-[9px] font-bold px-1.5 rounded-sm uppercase pointer-events-none">Baru</div>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end sticky bottom-6 z-20">
                            <button type="submit" :disabled="isLoading" class="bg-slate-900 hover:bg-black text-white px-8 py-4 rounded-2xl font-black uppercase tracking-widest transition-all shadow-xl shadow-slate-900/20 disabled:opacity-50 flex items-center gap-3">
                                <span v-if="isLoading" class="w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>
                                {{ isLoading ? 'Menyimpan Wadah...' : 'Simpan Semua Perubahan' }}
                            </button>
                        </div>
                    </form>
                </div>
            </main>
        </div>
    </div>
</template>

<style scoped>
/* Animasi Putar untuk Loading */
@keyframes spin {
    to { transform: rotate(360deg); }
}
.animate-spin {
    animation: spin 1s linear infinite;
}
</style>