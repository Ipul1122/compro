<script setup>
import { ref, onMounted, watch } from 'vue'
import { useRouter } from 'vue-router'
import Api from '@/api'
import Sidebar from '@/components/admin/Sidebar.vue'
import Navbar from '@/components/admin/Navbar.vue'

const router = useRouter()
const isSidebarOpen = ref(false)
const user = ref({ name: 'Admin', email: '' })
const categories = ref([])
const isLoading = ref(false)
const errors = ref({})

const breadcrumbsData = ref([
    { label: 'Dashboard', link: '/admin/dashboard' },
    { label: 'Manage Gallery', link: '/admin/gallery' },
    { label: 'Upload Foto', link: null }
])

const isTranslatingTitle = ref(false)
let translateTitleTimeout = null
const hasDraft = ref(false)

const form = ref({
    category_id: '',
    title_image: '',
    title_image_en: '',
    slug: '',
    images: []
})

const previews = ref([])
const isConfirmModalOpen = ref(false)
const draggedIndex = ref(null)
const dragOverIndex = ref(null)

// ==========================================
// 1. LOGIC LOCALSTORAGE DRAFT
// ==========================================
const saveDraft = () => {
    const draftData = {
        category_id: form.value.category_id,
        title_image: form.value.title_image,
        title_image_en: form.value.title_image_en,
        slug: form.value.slug,
        previews: previews.value // Simpan base64 images
    }
    
    try {
        localStorage.setItem('gallery_draft', JSON.stringify(draftData))
        hasDraft.value = true
    } catch (e) {
        console.warn("Gagal menyimpan draft (Mungkin gambar terlalu besar)", e)
    }
}

const loadDraft = () => {
    const draftStr = localStorage.getItem('gallery_draft')
    if (draftStr) {
        try {
            const draft = JSON.parse(draftStr)
            form.value.category_id = draft.category_id || ''
            form.value.title_image = draft.title_image || ''
            form.value.title_image_en = draft.title_image_en || ''
            form.value.slug = draft.slug || ''
            
            if (draft.previews && draft.previews.length > 0) {
                 previews.value = draft.previews
                 hasDraft.value = true
                 reconstructFilesFromBase64(draft.previews)
            }
        } catch (err) {
            console.error("Gagal memuat draft", err)
        }
    }
}

const reconstructFilesFromBase64 = async (base64Array) => {
    const reconstructedFiles = []
    for (let i = 0; i < base64Array.length; i++) {
        const base64 = base64Array[i]
        try {
            const match = base64.match(/data:([a-zA-Z0-9]+\/[a-zA-Z0-9-.+]+).*,.*/)
            if (match) {
                const mimeType = match[1]
                const response = await fetch(base64)
                const blob = await response.blob()
                const file = new File([blob], `draft-image-${i}.${mimeType.split('/')[1]}`, { type: mimeType })
                reconstructedFiles.push(file)
            }
        } catch (e) {
            console.error("Gagal merekonstruksi file", e)
        }
    }
    form.value.images = reconstructedFiles
}

const clearDraftAndBack = () => {
    localStorage.removeItem('gallery_draft')
    hasDraft.value = false
    router.push('/admin/gallery')
}

// Pantau perubahan pada form text untuk autosave
watch(() => [form.value.category_id, form.value.title_image, form.value.title_image_en], () => {
    saveDraft()
}, { deep: true })

// ==========================================
// 2. FUNGSI API & UTILS
// ==========================================
const fetchCategories = async () => {
    try {
        const response = await Api.get('/admin/categories/list')
        categories.value = response.data.data || response.data
    } catch (error) {
        console.error(error)
    }
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
            saveDraft()
        } catch (error) {
            console.error("Gagal terjemah judul:", error)
        } finally {
            isTranslatingTitle.value = false
        }
    }, 600)
}

// ==========================================
// 3. FILE HANDLING (APPEND, 10 MAX, 2MB LIMIT)
// ==========================================
const handleFileChange = (e) => {
    const newFiles = Array.from(e.target.files)
    if (newFiles.length === 0) return;
    
    // Hitung total gambar yang sudah ada di list + gambar baru
    const totalFiles = form.value.images.length + newFiles.length;

    // Cek Batas 10 Gambar (Total keseluruhan)
    if (totalFiles > 10) {
        alert('Gambar sudah maksimal (10), hapus salah satu terlebih dahulu jika ingin mengganti/menambah.')
        e.target.value = '' // Reset input agar bisa klik ulang
        return // Hentikan eksekusi, biarkan gambar yang lama tetap ada
    }

    // Cek Validasi Ukuran (Maks 2MB per gambar baru)
    const maxSize = 2 * 1024 * 1024;
    const oversizedFiles = [];

    newFiles.forEach(file => {
        if (file.size > maxSize) {
            oversizedFiles.push(file.name);
        }
    });

    if (oversizedFiles.length > 0) {
        alert(`Ups, foto melebihi 2MB, mohon dicompress dahulu. Berikut file yang melebihi batas:\n\n- ${oversizedFiles.join('\n- ')}`);
        e.target.value = ''; 
        return; // Hentikan eksekusi, jangan masukkan gambar yang ukurannya kebesaran
    }

    // Eksekusi Jika Lolos Validasi (Menambahkan ke array eksisting)
    form.value.images.push(...newFiles)
    errors.value.images = null 
    
    let loadedCount = 0;
    
    newFiles.forEach(file => {
        const reader = new FileReader()
        reader.onload = (event) => {
            previews.value.push(event.target.result)
            loadedCount++
            // Save jika semua gambar baru selesai diload preview-nya
            if (loadedCount === newFiles.length) saveDraft() 
        }
        reader.readAsDataURL(file)
    })

    // Reset value input supaya file yang sama bisa dipilih lagi jika dihapus
    e.target.value = '';
}

// Fungsi Hapus Satu Gambar
const removeImage = (index) => {
    form.value.images.splice(index, 1) // Hapus file dari object FormData
    previews.value.splice(index, 1) // Hapus preview UI
    saveDraft() // Simpan draft terbaru
}

// ==========================================
// 4. DRAG AND DROP & SUBMIT
// ==========================================
const handleDragStart = (index, event) => {
    draggedIndex.value = index
    event.dataTransfer.effectAllowed = 'move'
}

const handleDrop = (index) => {
    if (draggedIndex.value === null || draggedIndex.value === index) return
    
    const draggedFile = form.value.images.splice(draggedIndex.value, 1)[0]
    form.value.images.splice(index, 0, draggedFile)
    
    const draggedPreview = previews.value.splice(draggedIndex.value, 1)[0]
    previews.value.splice(index, 0, draggedPreview)

    draggedIndex.value = null
    dragOverIndex.value = null
    saveDraft() 
}

const openConfirmModal = () => {
    errors.value = {}
    let hasError = false
    
    if (!form.value.category_id) {
        errors.value.category_id = ['Kategori wajib dipilih.']
        hasError = true
    }
    
    if (form.value.images.length === 0) {
        errors.value.images = ['Silakan pilih minimal 1 gambar.']
        hasError = true
    }

    if (!hasError) isConfirmModalOpen.value = true
}

const closeConfirmModal = () => {
    if (isLoading.value) return 
    isConfirmModalOpen.value = false
}

const confirmSubmit = async () => {
    isLoading.value = true
    errors.value = {}

    const formData = new FormData()
    formData.append('category_id', form.value.category_id)
    formData.append('title_image', form.value.title_image)
    formData.append('title_image_en', form.value.title_image_en)
    formData.append('slug', form.value.slug)
    
    form.value.images.forEach((file) => {
        formData.append('images[]', file)
    })

    try {
        await Api.post('/admin/galleries/bulk-store', formData)
        
        localStorage.removeItem('gallery_draft')
        hasDraft.value = false
        
        isConfirmModalOpen.value = false
        router.push('/admin/gallery')
        
    } catch (error) {
        if (error.response?.status === 422) {
            errors.value = error.response.data.errors
        } else {
            alert('Terjadi kesalahan saat menyimpan data.')
        }
        isConfirmModalOpen.value = false 
    } finally {
        isLoading.value = false
    }
}

onMounted(() => {
    const savedUser = localStorage.getItem('user')
    if (savedUser) {
        user.value = JSON.parse(savedUser)
    } else {
        router.push('/view/login')
    }
    fetchCategories()
    loadDraft()
})
</script>

<template>
    <div class="flex min-h-screen bg-slate-50 relative overflow-x-hidden">
        <div v-if="isSidebarOpen" @click="isSidebarOpen = false" class="fixed inset-0 bg-slate-900/50 z-40 lg:hidden backdrop-blur-sm"></div>
        <Sidebar v-model:is-open="isSidebarOpen" />
        <div class="flex-1 relative">
            <Navbar :user="user" :breadcrumbs="breadcrumbsData" @toggle-sidebar="isSidebarOpen = !isSidebarOpen" />
            <main class="p-6">
                <div class="max-w-4xl mx-auto">
                    <div class="mb-6 flex justify-between items-end">
                        <div>
                            <button @click="router.push('/admin/gallery')" class="text-slate-400 hover:text-slate-600 text-sm font-bold flex items-center gap-2 mb-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                                Kembali ke Daftar
                            </button>
                            <h1 class="text-2xl font-bold text-slate-800">Upload Foto Baru</h1>
                            <p class="text-slate-500 text-sm">Pilih kategori, berikan judul, dan upload hingga 10 foto sekaligus.</p>
                        </div>
                        
                        <span v-if="hasDraft" class="text-xs font-bold text-green-600 bg-green-50 px-3 py-1.5 rounded-full animate-pulse border border-green-200">
                            Draft tersimpan otomatis
                        </span>
                    </div>

                    <form @submit.prevent="openConfirmModal" class="bg-white rounded-3xl p-8 border border-slate-200 shadow-sm space-y-6">
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="kategori" class="block text-sm font-black text-slate-700 uppercase tracking-wider mb-2">Kategori <span class="text-red-500">*</span></label>
                                <select id="kategori" name="kategori" v-model="form.category_id" class="w-full bg-slate-50 text-black border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500/20 font-medium transition-all" :class="{'border-red-400 bg-red-50': errors.category_id}">
                                    <option value="" disabled>Pilih Kategori</option>
                                    <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                                </select>
                                <p v-if="errors.category_id" class="text-red-500 text-xs mt-1 font-bold">{{ errors.category_id[0] }}</p>
                            </div>
                            <div>
                                <label for="judul_id" class="block text-sm font-black text-slate-700 uppercase tracking-wider mb-2">Judul Dasar Wadah (ID)</label>
                                <input id="judul_id" name="judul_id" v-model="form.title_image" @input="autoTranslateTitle" type="text" placeholder="Contoh: Momen Gathering" class="w-full text-black bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500/20 font-medium transition-all" required />
                            </div>
                            <div class="relative">
                                <label for="judul_en" class="block text-sm font-black text-slate-700 uppercase tracking-wider mb-2">Judul Dasar Wadah (EN)</label>
                                <input id="judul_en" name="judul_en" v-model="form.title_image_en" type="text" placeholder="Terjemahan otomatis muncul di sini..." class="w-full text-black bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500/20 font-medium transition-all" />
                                <span v-if="isTranslatingTitle" class="absolute right-4 top-[42px] text-xs font-bold text-slate-400 animate-pulse">Translating...</span>
                            </div>
                            <div>
                                <label for="slug" class="block text-sm font-black text-slate-700 uppercase tracking-wider mb-2">Slug</label>
                                <input id="slug" name="slug" v-model="form.slug" type="text" disabled placeholder="otomatis-mengikuti-judul" class="w-full text-slate-500 bg-slate-100 border border-slate-200 rounded-xl px-4 py-3 cursor-not-allowed font-medium transition-all" />
                            </div>
                        </div>

                            <div>
                                <label for="pilih_gambar" class="block text-sm font-black text-slate-700 uppercase tracking-wider mb-2">Pilih Gambar (Maksimal 10) <span class="text-red-500">*</span></label>
                                <div class="border-2 border-dashed border-slate-200 rounded-2xl p-8 text-center transition-all relative" :class="[errors.images ? 'border-red-400 bg-red-50' : 'hover:bg-slate-50', form.images.length >= 10 ? 'opacity-50 cursor-not-allowed bg-slate-100' : 'cursor-pointer']">
                                    <input 
                                        v-if="form.images.length < 10"
                                        id="pilih_gambar" 
                                        name="pilih_gambar" 
                                        type="file" 
                                        multiple 
                                        @change="handleFileChange" 
                                        class="absolute inset-0 opacity-0 cursor-pointer z-10" 
                                        accept="image/*" 
                                    />
                                    <div class="text-slate-400 pointer-events-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto mb-2" :class="errors.images ? 'text-red-400' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                        
                                        <div v-if="form.images.length >= 10">
                                            <p class="font-bold text-red-500">Kuota gambar sudah penuh (10/10)</p>
                                            <p class="text-xs mt-1 text-slate-500">Hapus salah satu gambar di bawah untuk menambah lagi</p>
                                        </div>
                                        <div v-else>
                                            <p class="font-bold text-slate-600">Klik atau seret foto ke sini</p>
                                            <p class="text-xs mt-1"><span class="text-blue-600 font-black">{{ form.images.length }}</span> / 10 file terpilih</p>
                                            <p class="text-xs mt-1">PNG, JPG, WEBP hingga 2MB per file</p>
                                        </div>
                                    </div>
                            </div>
                            <p v-if="errors.images" class="text-red-500 text-xs mt-1 font-bold">{{ errors.images[0] }}</p>
                        </div>

                        <div v-if="previews.length > 0" class="pt-4 border-t border-slate-100">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-sm font-black text-slate-700 uppercase tracking-wider">Urutan Gambar</h3>
                                <span class="text-xs bg-blue-50 text-blue-600 font-bold px-3 py-1 rounded-full border border-blue-100">Tahan & Geser untuk mengubah urutan</span>
                            </div>
                            
                            <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 gap-4">
                                <div 
                                    v-for="(src, index) in previews" 
                                    :key="index" 
                                    draggable="true"
                                    @dragstart="handleDragStart(index, $event)"
                                    @dragover.prevent="dragOverIndex = index"
                                    @dragleave.prevent="dragOverIndex = null"
                                    @drop.prevent="handleDrop(index)"
                                    :class="[
                                        'relative aspect-square rounded-xl overflow-hidden shadow-sm transition-all duration-200 border-2 group bg-slate-100',
                                        dragOverIndex === index ? 'border-blue-500 scale-105 z-10' : 'border-slate-200',
                                        draggedIndex === index ? 'opacity-40' : 'opacity-100 cursor-move'
                                    ]"
                                >
                                    <img :src="src" class="w-full h-full object-cover pointer-events-none" />
                                    <div class="absolute top-1.5 left-1.5 bg-slate-900/80 backdrop-blur-sm text-white text-[10px] font-black px-2 py-0.5 rounded-md pointer-events-none shadow-sm">{{ index + 1 }}</div>
                                    
                                    <button 
                                        type="button" 
                                        @click.stop="removeImage(index)" 
                                        class="absolute top-1.5 right-1.5 bg-red-500/90 hover:bg-red-600 text-white p-1 rounded-md opacity-0 group-hover:opacity-100 transition-opacity shadow-sm z-20"
                                        title="Hapus Gambar"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="pt-6 mt-6 border-t border-slate-100 flex justify-end gap-3">
                            <button type="button" @click="clearDraftAndBack" class="bg-slate-100 text-slate-600 px-6 py-3.5 rounded-xl font-bold hover:bg-slate-200 transition-colors cursor-pointer">
                                Batal & Hapus Draft
                            </button>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3.5 rounded-xl font-black uppercase tracking-wider transition-all shadow-lg shadow-blue-600/20 hover:shadow-blue-600/40 hover:-translate-y-0.5">
                                Simpan Galeri
                            </button>
                        </div>
                    </form>
                </div>
            </main>
        </div>

        <div v-if="isConfirmModalOpen" class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-0">
            <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" @click="closeConfirmModal"></div>
            
            <div class="relative bg-white rounded-3xl w-full max-w-md p-8 shadow-2xl animate-popIn">
                <div class="w-16 h-16 bg-blue-50 text-blue-500 rounded-full flex items-center justify-center mx-auto mb-5 border-4 border-white shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                    </svg>
                </div>
                
                <h2 class="text-2xl font-black text-center text-slate-800 mb-2 tracking-tight">Konfirmasi Simpan</h2>
                <p class="text-center text-slate-500 mb-6 leading-relaxed">
                    Yakin sudah benar? Anda akan mengunggah <span class="font-black text-blue-600">{{ form.images.length }} foto</span> ke dalam galeri.
                </p>
                
                <div class="flex flex-col-reverse sm:flex-row gap-3 mt-8">
                    <button 
                        @click="closeConfirmModal" 
                        :disabled="isLoading" 
                        class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold py-3.5 rounded-xl transition-colors disabled:opacity-50"
                    >
                        Periksa Lagi
                    </button>
                    <button 
                        @click="confirmSubmit" 
                        :disabled="isLoading" 
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 rounded-xl transition-all flex items-center justify-center gap-2 shadow-lg shadow-blue-600/30 disabled:opacity-70 disabled:cursor-not-allowed hover:-translate-y-0.5"
                    >
                        <span v-if="isLoading" class="w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>
                        {{ isLoading ? 'Menyimpan...' : 'Ya, Simpan!' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.animate-popIn {
    animation: popIn 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

@keyframes popIn {
    0% { opacity: 0; transform: scale(0.9) translateY(10px); }
    100% { opacity: 1; transform: scale(1) translateY(0); }
}

@keyframes spin {
    to { transform: rotate(360deg); }
}
.animate-spin {
    animation: spin 1s linear infinite;
}
</style>