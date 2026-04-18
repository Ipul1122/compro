<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import Api from '@/api'
import Sidebar from '@/components/admin/Sidebar.vue'
import Navbar from '@/components/admin/Navbar.vue'

const router = useRouter()
const categories = ref([])
const isLoading = ref(false) // State untuk efek loading saat submit
const errors = ref({})

const form = ref({
    category_id: '',
    title_image: '',
    images: [] // Untuk multiple files
})

const previews = ref([])

// STATE UNTUK MODAL KONFIRMASI
const isConfirmModalOpen = ref(false)

// STATE UNTUK DRAG AND DROP
const draggedIndex = ref(null)
const dragOverIndex = ref(null)

// Load daftar kategori
const fetchCategories = async () => {
    try {
        const response = await Api.get('/admin/categories/list')
        categories.value = response.data.data || response.data
    } catch (error) {
        console.error(error)
    }
}

// Handle pemilihan file gambar
const handleFileChange = (e) => {
    const files = Array.from(e.target.files)
    
    if (files.length > 10) {
        alert('Maksimal upload adalah 10 gambar sekaligus.')
        e.target.value = ''
        form.value.images = []
        previews.value = []
        return
    }

    form.value.images = files
    errors.value.images = null // Hapus error jika ada
    
    // Generate Previews
    previews.value = []
    files.forEach(file => {
        const reader = new FileReader()
        reader.onload = (e) => previews.value.push(e.target.result)
        reader.readAsDataURL(file)
    })
}

// FUNGSI DRAG AND DROP
const handleDragStart = (index, event) => {
    draggedIndex.value = index
    event.dataTransfer.effectAllowed = 'move'
}

const handleDrop = (index) => {
    if (draggedIndex.value === null || draggedIndex.value === index) return
    
    // Pindahkan urutan di array file form
    const draggedFile = form.value.images.splice(draggedIndex.value, 1)[0]
    form.value.images.splice(index, 0, draggedFile)
    
    // Pindahkan urutan di array preview visual
    const draggedPreview = previews.value.splice(draggedIndex.value, 1)[0]
    previews.value.splice(index, 0, draggedPreview)

    draggedIndex.value = null
    dragOverIndex.value = null
}

// Validasi Frontend sebelum membuka Modal
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

    if (!hasError) {
        isConfirmModalOpen.value = true
    }
}

const closeConfirmModal = () => {
    if (isLoading.value) return // Cegah tutup saat sedang loading submit
    isConfirmModalOpen.value = false
}

// Eksekusi Submit Data setelah dikonfirmasi
const confirmSubmit = async () => {
    isLoading.value = true
    errors.value = {}

    const formData = new FormData()
    formData.append('category_id', form.value.category_id)
    formData.append('title_image', form.value.title_image)
    
    form.value.images.forEach((file) => {
        formData.append('images[]', file)
    })

    try {
        await Api.post('/admin/galleries/bulk-store', formData)
        
        // Tutup modal dan arahkan ke halaman list
        isConfirmModalOpen.value = false
        router.push('/admin/gallery')
        
    } catch (error) {
        if (error.response?.status === 422) {
            errors.value = error.response.data.errors
        } else {
            alert('Terjadi kesalahan saat menyimpan data.')
        }
        isConfirmModalOpen.value = false // Tutup modal jika error
    } finally {
        isLoading.value = false
    }
}

onMounted(fetchCategories)
</script>

<template>
    <div class="flex min-h-screen bg-slate-50">
        <Sidebar />
        <div class="flex-1 relative">
            <Navbar />
            <main class="p-6">
                <div class="max-w-4xl mx-auto">
                    <div class="mb-6">
                        <router-link to="/admin/gallery" class="text-slate-400 hover:text-slate-600 text-sm font-bold flex items-center gap-2 mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                            Kembali ke Daftar
                        </router-link>
                        <h1 class="text-2xl font-bold text-slate-800">Upload Foto Baru</h1>
                        <p class="text-slate-500 text-sm">Pilih kategori, berikan judul, dan upload hingga 10 foto sekaligus.</p>
                    </div>

                    <form @submit.prevent="openConfirmModal" class="bg-white rounded-3xl p-8 border border-slate-200 shadow-sm space-y-6">
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-black text-slate-700 uppercase tracking-wider mb-2">Kategori <span class="text-red-500">*</span></label>
                                <select v-model="form.category_id" class="w-full bg-slate-50 text-black border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500/20 font-medium transition-all" :class="{'border-red-400 bg-red-50': errors.category_id}">
                                    <option value="" disabled>Pilih Kategori</option>
                                    <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                                </select>
                                <p v-if="errors.category_id" class="text-red-500 text-xs mt-1 font-bold">{{ errors.category_id[0] }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-black text-slate-700 uppercase tracking-wider mb-2">Judul Dasar Wadah (Opsional)</label>
                                <input v-model="form.title_image" type="text" placeholder="Contoh: Momen Gathering" class="w-full text-black bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500/20 font-medium transition-all" />
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-black text-slate-700 uppercase tracking-wider mb-2">Pilih Gambar (Maksimal 10) <span class="text-red-500">*</span></label>
                            <div class="border-2 border-dashed border-slate-200 rounded-2xl p-8 text-center hover:bg-slate-50 transition-all cursor-pointer relative" :class="{'border-red-400 bg-red-50': form.images.length > 10 || errors.images}">
                                <input type="file" multiple @change="handleFileChange" class="absolute inset-0 opacity-0 cursor-pointer z-10" accept="image/*" />
                                <div class="text-slate-400 pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto mb-2" :class="errors.images ? 'text-red-400' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                    <p class="font-bold text-slate-600">Klik atau seret foto ke sini</p>
                                    <p class="text-xs mt-1" v-if="form.images.length > 0"><span class="text-blue-600 font-black">{{ form.images.length }}</span> file terpilih</p>
                                    <p class="text-xs mt-1" v-else>PNG, JPG, WEBP hingga 2MB per file</p>
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
                                        'relative aspect-square rounded-xl overflow-hidden shadow-sm cursor-move transition-all duration-200 border-2',
                                        dragOverIndex === index ? 'border-blue-500 scale-105 z-10' : 'border-slate-200',
                                        draggedIndex === index ? 'opacity-40' : 'opacity-100'
                                    ]"
                                >
                                    <img :src="src" class="w-full h-full object-cover pointer-events-none" />
                                    <div class="absolute top-1.5 left-1.5 bg-slate-900/80 backdrop-blur-sm text-white text-[10px] font-black px-2 py-0.5 rounded-md pointer-events-none shadow-sm">{{ index + 1 }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="pt-6 mt-6 border-t border-slate-100 flex justify-end">
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

/* Animasi Putar untuk Loading */
@keyframes spin {
    to { transform: rotate(360deg); }
}
.animate-spin {
    animation: spin 1s linear infinite;
}
</style>