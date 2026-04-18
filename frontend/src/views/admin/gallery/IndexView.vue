<script setup>
import { ref, onMounted, watch } from 'vue'
import Api from '@/api'
import Sidebar from '@/components/admin/Sidebar.vue'
import Navbar from '@/components/admin/Navbar.vue'
import { getImageUrl, handleImageError } from '@/utils/imageHelper'

const galleries = ref([])
const categories = ref([])
const isLoading = ref(false)
const selectedIds = ref([]) // Untuk fitur bulk delete


const fetchCategories = async () => {
    try {
        const response = await Api.get('/admin/categories/list')
        categories.value = response.data.data || response.data
    } catch (error) {
        console.error("Error categories:", error)
    }
}

const fetchGalleries = async () => {
    isLoading.value = true
    try {
        const response = await Api.get('/admin/galleries')
        
        // Ekstrak data galleries dari response structure
        const data = response.data?.data || []
        galleries.value = Array.isArray(data) ? data : []
        
    } catch (error) {
        console.error("Error fetching galleries:", error)
        galleries.value = [] 
    } finally {
        isLoading.value = false
    }
}

const deleteGallery = async (id) => {
    if (!confirm('Yakin ingin menghapus foto ini?')) return
    try {
        await Api.delete(`/admin/galleries/${id}`)
        fetchGalleries()
    } catch (error) {
        alert('Gagal menghapus data')
    }
}

const bulkDelete = async () => {
    if (selectedIds.value.length === 0) return
    if (!confirm(`Hapus ${selectedIds.value.length} item terpilih?`)) return
    
    try {
        await Api.post('/admin/galleries/bulk-delete', { ids: selectedIds.value })
        selectedIds.value = []
        fetchGalleries()
    } catch (error) {
        alert('Gagal menghapus massal')
    }
}

const toggleSelectAll = (e) => {
    selectedIds.value = e.target.checked ? galleries.value.map(g => g.id) : []
}

onMounted(() => {
    fetchCategories()
    fetchGalleries()
})
</script>

<template>
    <div class="flex min-h-screen bg-slate-50">
        <Sidebar />
        <div class="flex-1">
            <Navbar />
            <main class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h1 class="text-2xl font-bold text-slate-800">Manajemen Galeri</h1>
                        <p class="text-slate-500">Kelola foto-foto dokumentasi perusahaan</p>
                    </div>
                    <router-link to="/admin/gallery/tambah" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-bold transition-all flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        Tambah Foto
                    </router-link>
                </div>

                <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm">
                    <div class="p-4 border-b border-slate-100 flex gap-3">
                        <button v-if="selectedIds.length > 0" @click="bulkDelete" class="bg-red-50 text-red-600 px-4 py-2 rounded-xl text-sm font-bold hover:bg-red-100 transition-all">
                            Hapus Terpilih ({{ selectedIds.length }})
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 text-slate-500 text-xs uppercase font-black tracking-wider">
                                    <th class="px-6 py-4 w-10">
                                        <input type="checkbox" @change="toggleSelectAll" :checked="selectedIds.length === galleries.length && galleries.length > 0" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500" />
                                    </th>
                                    <th class="px-6 py-4">Gambar</th>
                                    <th class="px-6 py-4">Judul / Meta</th>
                                    <th class="px-6 py-4">Kategori</th>
                                    <th class="px-6 py-4 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <tr v-for="gallery in galleries" :key="gallery.id" class="hover:bg-slate-50 transition-colors group">
                                    <td class="px-6 py-4">
                                        <input type="checkbox" v-model="selectedIds" :value="gallery.id" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500" />
                                    </td>
                                    <td class="px-6 py-4">
                                        <img :src="getImageUrl(gallery.image)" @error="handleImageError" class="w-20 h-14 object-cover rounded-lg shadow-sm" />
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-slate-700">{{ gallery.title_image }}</div>
                                        <div class="text-xs text-slate-400">{{ gallery.meta_title_image || '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="bg-slate-100 text-slate-600 px-3 py-1 rounded-full text-[10px] font-black uppercase">
                                            {{ gallery.category?.name || 'Uncategorized' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex justify-end gap-2">
                                            <router-link :to="`/admin/gallery/edit/${gallery.id}`" class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </router-link>
                                            <button @click="deleteGallery(gallery.id)" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="galleries.length === 0 && !isLoading">
                                    <td colspan="5" class="px-6 py-20 text-center text-slate-400 font-medium">Data galeri kosong.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>
</template>