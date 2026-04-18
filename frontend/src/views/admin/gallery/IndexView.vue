<script setup>
import { ref, onMounted, computed } from 'vue'
import Api from '@/api'
import Sidebar from '@/components/admin/Sidebar.vue'
import Navbar from '@/components/admin/Navbar.vue'
import { getImageUrl, handleImageError } from '@/utils/imageHelper'

const galleries = ref([])
const categories = ref([])
const isLoading = ref(false)

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
        galleries.value = response.data.data || response.data || []
    } catch (error) {
        console.error("Error galleries:", error)
        galleries.value = []
    } finally {
        isLoading.value = false
    }
}

// LOGIKA PENGELOMPOKAN (ALBUM/WADAH)
const groupedGalleries = computed(() => {
    const groups = {}
    
    galleries.value.forEach(gallery => {
        // Hilangkan angka di belakang untuk mendapatkan Base Title (contoh: "Gathering 1" -> "Gathering")
        const baseTitle = gallery.title_image ? gallery.title_image.replace(/\s\d+$/, '').trim() : 'Galeri'
        const key = `${baseTitle}_${gallery.category_id}`
        
        if (!groups[key]) {
            groups[key] = {
                id: gallery.id, // ID representatif untuk rute edit
                title: baseTitle,
                category: gallery.category,
                category_id: gallery.category_id,
                cover: gallery.image, // Foto pertama sebagai cover
                total_images: 0,
                ids: [] // Kumpulkan semua ID untuk bulk delete
            }
        }
        groups[key].total_images += 1
        groups[key].ids.push(gallery.id)
    })

    // Kembalikan sebagai Array dan urutkan
    return Object.values(groups)
})

const deleteAlbum = async (album) => {
    if (!confirm(`Yakin ingin menghapus wadah "${album.title}" beserta ${album.total_images} foto di dalamnya?`)) return
    
    try {
        // Gunakan bulk delete backend Anda untuk menghapus seluruh isi wadah
        await Api.post('/admin/galleries/bulk-delete', { ids: album.ids })
        fetchGalleries()
    } catch (error) {
        alert('Gagal menghapus wadah galeri')
    }
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
                        <h1 class="text-2xl font-bold text-slate-800">Manajemen Galeri (Album)</h1>
                        <p class="text-slate-500">Foto-foto dengan judul serupa otomatis dikelompokkan dalam satu wadah.</p>
                    </div>
                    <router-link to="/admin/gallery/tambah" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-bold transition-all flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        Tambah Foto Baru
                    </router-link>
                </div>

                <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 text-slate-500 text-xs uppercase font-black tracking-wider">
                                    <th class="px-6 py-4">Cover Wadah</th>
                                    <th class="px-6 py-4">Judul Album</th>
                                    <th class="px-6 py-4">Kategori</th>
                                    <th class="px-6 py-4">Jumlah Foto</th>
                                    <th class="px-6 py-4 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <tr v-for="album in groupedGalleries" :key="album.id" class="hover:bg-slate-50 transition-colors group">
                                    <td class="px-6 py-4">
                                        <div class="relative w-24 h-16 rounded-lg overflow-hidden shadow-sm border border-slate-200">
                                            <img :src="getImageUrl(album.cover)" @error="handleImageError" class="w-full h-full object-cover" />
                                            <div v-if="album.total_images > 1" class="absolute inset-0 bg-black/40 flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white opacity-80" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-slate-700 text-base">{{ album.title }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="bg-blue-50 text-blue-600 border border-blue-100 px-3 py-1 rounded-full text-[10px] font-black uppercase">
                                            {{ album.category?.name || 'Uncategorized' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="font-bold text-slate-700">{{ album.total_images }} Foto</span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex justify-end gap-2">
                                            <router-link :to="`/admin/gallery/edit/${album.id}`" class="px-3 py-2 text-sm font-bold bg-slate-100 text-slate-600 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all flex items-center gap-2">
                                                Buka Wadah
                                            </router-link>
                                            <button @click="deleteAlbum(album)" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="groupedGalleries.length === 0 && !isLoading">
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