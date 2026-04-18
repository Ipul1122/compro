<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import Api from '@/api'
import Sidebar from '@/components/admin/Sidebar.vue'
import Navbar from '@/components/admin/Navbar.vue'

const router = useRouter()
const categories = ref([])
const isLoading = ref(false)
const errors = ref({})

const form = ref({
    category_id: '',
    title_image: '',
    images: [] // Untuk multiple files
})

const previews = ref([])

// STATE UNTUK DRAG AND DROP
const draggedIndex = ref(null)
const dragOverIndex = ref(null)

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
    
    // Pindahkan urutan di array file
    const draggedFile = form.value.images.splice(draggedIndex.value, 1)[0]
    form.value.images.splice(index, 0, draggedFile)
    
    // Pindahkan urutan di array preview
    const draggedPreview = previews.value.splice(draggedIndex.value, 1)[0]
    previews.value.splice(index, 0, draggedPreview)

    draggedIndex.value = null
    dragOverIndex.value = null
}

const fetchCategories = async () => {
    try {
        const response = await Api.get('/admin/categories/list')
        categories.value = response.data.data || response.data
    } catch (error) {
        console.error(error)
    }
}

const handleSubmit = async () => {
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
        router.push('/admin/gallery')
    } catch (error) {
        if (error.response?.status === 422) {
            errors.value = error.response.data.errors
        }
    } finally {
        isLoading.value = false
    }
}

onMounted(fetchCategories)
</script>

<template>
    <div class="flex min-h-screen bg-slate-50">
        <Sidebar />
        <div class="flex-1">
            <Navbar />
            <main class="p-6">
                <div class="max-w-4xl mx-auto">
                    <div class="mb-6">
                        <router-link to="/admin/gallery" class="text-slate-400 hover:text-slate-600 text-sm font-bold flex items-center gap-2 mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                            Kembali ke Daftar
                        </router-link>
                        <h1 class="text-2xl font-bold text-slate-800">Upload Foto Baru</h1>
                    </div>

                    <form @submit.prevent="handleSubmit" class="bg-white rounded-3xl p-8 border border-slate-200 shadow-sm space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-black text-slate-700 uppercase tracking-wider mb-2">Kategori</label>
                                <select v-model="form.category_id" class="w-full text-black bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500/10">
                                    <option value="">Pilih Kategori</option>
                                    <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                                </select>
                                <p v-if="errors.category_id" class="text-red-500 text-xs mt-1">{{ errors.category_id[0] }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-black text-slate-700 uppercase tracking-wider mb-2">Judul Dasar (Opsional)</label>
                                <input v-model="form.title_image" type="text" placeholder="Contoh: Momen Gathering" class="w-full text-black bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500/10" />
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-black text-slate-700 uppercase tracking-wider mb-2">Pilih Gambar (Maksimal 10)</label>
                            <div class="border-2 border-dashed border-slate-200 rounded-2xl p-8 text-center hover:bg-slate-50 transition-all cursor-pointer relative" :class="{'border-red-400 bg-red-50': form.images.length > 10}">
                                <input type="file" multiple @change="handleFileChange" class="absolute inset-0 opacity-0 cursor-pointer" accept="image/*" />
                                <div class="text-slate-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                    <p class="font-bold">Klik atau seret foto ke sini</p>
                                    <p class="text-xs">PNG, JPG, WEBP hingga 2MB per file</p>
                                </div>
                            </div>
                            <p v-if="errors.images" class="text-red-500 text-xs mt-1">{{ errors.images[0] }}</p>
                        </div>

                        <div v-if="previews.length > 0">
                            <p class="text-xs font-bold text-slate-400 mb-3 uppercase tracking-wider">Tahan & Geser untuk mengubah urutan</p>
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
                                        dragOverIndex === index ? 'border-blue-500 scale-105 z-10' : 'border-slate-100',
                                        draggedIndex === index ? 'opacity-40' : 'opacity-100'
                                    ]"
                                >
                                    <img :src="src" class="w-full h-full object-cover pointer-events-none" />
                                    <div class="absolute top-1 left-1 bg-black/60 text-white text-[10px] font-bold px-1.5 rounded-md pointer-events-none">{{ index + 1 }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-slate-100 flex justify-end">
                            <button type="submit" :disabled="isLoading" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-xl font-black uppercase tracking-wider transition-all disabled:opacity-50">
                                {{ isLoading ? 'Mengupload...' : 'Simpan Galeri' }}
                            </button>
                        </div>
                    </form>
                </div>
            </main>
        </div>
    </div>
</template>