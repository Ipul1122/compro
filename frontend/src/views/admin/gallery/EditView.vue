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
const errors = ref({})

const form = ref({
    category_id: '',
    title_image: '',
    meta_title_image: '',
    image: null
})

const currentImage = ref('')
const preview = ref(null)

const fetchGallery = async () => {
    try {
        const response = await Api.get(`/admin/galleries/${route.params.id}`)
        const data = response.data.data
        form.value.category_id = data.category_id
        form.value.title_image = data.title_image
        form.value.meta_title_image = data.meta_title_image
        currentImage.value = data.image
    } catch (error) {
        console.error(error)
    }
}

const handleFileChange = (e) => {
    const file = e.target.files[0]
    form.value.image = file
    preview.value = URL.createObjectURL(file)
}

const handleSubmit = async () => {
    isLoading.value = true
    errors.value = {}

    const formData = new FormData()
    formData.append('_method', 'PUT') // Laravel spoofing untuk multipart update
    formData.append('category_id', form.value.category_id)
    formData.append('title_image', form.value.title_image)
    formData.append('meta_title_image', form.value.meta_title_image || '')
    if (form.value.image) formData.append('image', form.value.image)

    try {
        await Api.post(`/admin/galleries/${route.params.id}`, formData)
        router.push('/admin/gallery')
    } catch (error) {
        if (error.response?.status === 422) {
            errors.value = error.response.data.errors
        }
    } finally {
        isLoading.value = false
    }
}

onMounted(async () => {
    const res = await Api.get('/admin/categories/list')
    categories.value = res.data.data || res.data
    fetchGallery()
})
</script>

<template>
    <div class="flex min-h-screen bg-slate-50">
        <Sidebar />
        <div class="flex-1">
            <Navbar />
            <main class="p-6">
                <div class="max-w-4xl mx-auto">
                    <div class="mb-6 flex justify-between items-end">
                        <div>
                            <router-link to="/admin/gallery" class="text-slate-400 hover:text-slate-600 text-sm font-bold flex items-center gap-2 mb-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                Kembali
                            </router-link>
                            <h1 class="text-2xl font-bold text-slate-800">Edit Data Galeri</h1>
                        </div>
                    </div>

                    <form @submit.prevent="handleSubmit" class="bg-white rounded-3xl p-8 border border-slate-200 shadow-sm space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-black text-slate-700 uppercase tracking-wider mb-2">Kategori</label>
                                <select v-model="form.category_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:outline-none">
                                    <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-black text-slate-700 uppercase tracking-wider mb-2">Judul Foto</label>
                                <input v-model="form.title_image" type="text" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:outline-none" />
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-black text-slate-700 uppercase tracking-wider mb-2">Meta Title</label>
                            <input v-model="form.meta_title_image" type="text" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:outline-none" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start">
                            <div>
                                <label class="block text-sm font-black text-slate-700 uppercase tracking-wider mb-2">Ganti Gambar</label>
                                <input type="file" @change="handleFileChange" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-all" />
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-black text-slate-400 uppercase tracking-wider">Preview</label>
                                <img :src="preview || getImageUrl(currentImage)" @error="handleImageError" class="w-full h-48 object-cover rounded-2xl border border-slate-100 shadow-inner" />
                            </div>
                        </div>

                        <div class="pt-4 border-t border-slate-100 flex justify-end">
                            <button type="submit" :disabled="isLoading" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-xl font-black uppercase tracking-wider transition-all">
                                {{ isLoading ? 'Menyimpan...' : 'Update Foto' }}
                            </button>
                        </div>
                    </form>
                </div>
            </main>
        </div>
    </div>
</template>