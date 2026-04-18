<script setup>
import { ref, onMounted, computed, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import Api from '@/api'
import Sidebar from '@/components/admin/Sidebar.vue'
import Navbar from '@/components/admin/Navbar.vue'
import { getImageUrl, handleImageError } from '@/utils/imageHelper'

// Router Setup untuk Sinkronisasi URL
const route = useRoute()
const router = useRouter()

const galleries = ref([])
const categories = ref([])
const isLoading = ref(true)

// FRONTEND PAGINATION (SINKRON DENGAN URL)
// Mengambil nilai page dari URL saat pertama kali dimuat, default ke 1
const currentPage = ref(parseInt(route.query.page) || 1)
const itemsPerPage = 10

// State Modal Hapus
const deleteModal = ref({
    isOpen: false,
    album: null,
    isDeleting: false
})

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

// 1. KELOMPOKKAN SEMUA DATA MENJADI "WADAH" (ALBUM)
const groupedGalleries = computed(() => {
    const groups = {}
    
    galleries.value.forEach(gallery => {
        const baseTitle = gallery.title_image ? gallery.title_image.replace(/\s\d+$/, '').trim() : 'Galeri'
        const key = `${baseTitle}_${gallery.category_id}`
        
        if (!groups[key]) {
            groups[key] = {
                id: gallery.id, 
                title: baseTitle,
                category: gallery.category,
                category_id: gallery.category_id,
                cover: gallery.image,
                total_images: 0,
                ids: []
            }
        }
        groups[key].total_images += 1
        groups[key].ids.push(gallery.id)
    })

    return Object.values(groups)
})

// 2. HITUNG TOTAL HALAMAN BERDASARKAN JUMLAH WADAH
const totalPages = computed(() => {
    return Math.ceil(groupedGalleries.value.length / itemsPerPage) || 1
})

// 3. POTONG ARRAY WADAH UNTUK DITAMPILKAN DI HALAMAN SAAT INI
const paginatedAlbums = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage
    const end = start + itemsPerPage
    return groupedGalleries.value.slice(start, end)
})

// 4. FUNGSI GANTI HALAMAN YANG MENGUBAH URL BROWSER (?page=x)
const changePage = (page) => {
    if (page >= 1 && page <= totalPages.value) {
        // Mengupdate URL. Ini akan memicu watcher di bawah.
        router.push({ query: { ...route.query, page: page } })
        window.scrollTo({ top: 0, behavior: 'smooth' })
    }
}

// 5. PANTAU PERUBAHAN URL (Untuk fitur tombol Back/Forward di browser)
watch(() => route.query.page, (newPage) => {
    // Jika URL berubah (misal user klik Back), perbarui data yang tampil
    currentPage.value = parseInt(newPage) || 1
})

// MODAL HAPUS
const openDeleteModal = (album) => {
    deleteModal.value = { isOpen: true, album: album, isDeleting: false }
}

const closeDeleteModal = () => {
    if (deleteModal.value.isDeleting) return
    deleteModal.value.isOpen = false
    setTimeout(() => { deleteModal.value.album = null }, 300)
}

const confirmDelete = async () => {
    if (!deleteModal.value.album) return
    deleteModal.value.isDeleting = true 
    
    try {
        await Api.post('/admin/galleries/bulk-delete', { ids: deleteModal.value.album.ids })
        await fetchGalleries()
        
        // Cek jika halaman ini kosong setelah dihapus, mundur 1 halaman
        if (paginatedAlbums.value.length === 1 && currentPage.value > 1) {
            changePage(currentPage.value - 1)
        }
        
        closeDeleteModal()
    } catch (error) {
        alert('Gagal menghapus wadah galeri')
        deleteModal.value.isDeleting = false
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
        <div class="flex-1 relative">
            <Navbar />
            <main class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h1 class="text-2xl font-bold text-slate-800">Manajemen Galeri (Album)</h1>
                        <p class="text-slate-500">Terdapat total <span class="font-bold text-blue-600">{{ groupedGalleries.length }}</span> Wadah Album.</p>
                    </div>
                    <router-link to="/admin/gallery/tambah" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-bold transition-all flex items-center gap-2 shadow-sm shadow-blue-600/30">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        Tambah Foto Baru
                    </router-link>
                </div>

                <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm flex flex-col">
                    <div class="overflow-x-auto flex-grow min-h-[400px]">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 text-slate-500 text-xs uppercase font-black tracking-wider">
                                    <th class="px-6 py-4">Cover Wadah</th>
                                    <th class="px-6 py-4">Judul Album</th>
                                    <th class="px-6 py-4">Kategori</th>
                                    <th class="px-6 py-4">Isi Wadah</th>
                                    <th class="px-6 py-4 text-right">Aksi</th>
                                </tr>
                            </thead>
                            
                            <tbody class="divide-y divide-slate-100">
                                <template v-if="isLoading">
                                    <tr v-for="n in 5" :key="n" class="animate-pulse">
                                        <td class="px-6 py-4"><div class="w-24 h-16 bg-slate-200/70 rounded-lg"></div></td>
                                        <td class="px-6 py-4">
                                            <div class="h-5 w-48 bg-slate-200/70 rounded mb-2"></div>
                                            <div class="h-3 w-24 bg-slate-100 rounded"></div>
                                        </td>
                                        <td class="px-6 py-4"><div class="h-6 w-24 bg-slate-200/70 rounded-full"></div></td>
                                        <td class="px-6 py-4"><div class="h-5 w-16 bg-slate-200/70 rounded"></div></td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex justify-end gap-2">
                                                <div class="h-9 w-28 bg-slate-200/70 rounded-lg"></div>
                                                <div class="h-9 w-9 bg-slate-200/70 rounded-lg"></div>
                                            </div>
                                        </td>
                                    </tr>
                                </template>

                                <template v-else-if="paginatedAlbums.length > 0">
                                    <tr v-for="album in paginatedAlbums" :key="album.id" class="hover:bg-slate-50 transition-colors group">
                                        <td class="px-6 py-4">
                                            <div class="relative w-24 h-16 rounded-lg overflow-hidden shadow-sm border border-slate-200">
                                                <img :src="getImageUrl(album.cover)" @error="handleImageError" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" />
                                                <div v-if="album.total_images > 1" class="absolute inset-0 bg-black/40 flex items-center justify-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white opacity-90" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="font-bold text-slate-700 text-base">{{ album.title }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="bg-blue-50 text-blue-600 border border-blue-100 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider">
                                                {{ album.category?.name || 'Uncategorized' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="font-bold text-slate-700">{{ album.total_images }} <span class="text-slate-400 font-medium">Foto</span></span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex justify-end gap-2">
                                                <router-link :to="`/admin/gallery/edit/${album.id}`" class="px-4 py-2 text-sm font-bold bg-slate-100 text-slate-600 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all">
                                                    Buka Wadah
                                                </router-link>
                                                <button @click="openDeleteModal(album)" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </template>

                                <template v-else>
                                    <tr>
                                        <td colspan="5" class="px-6 py-20 text-center">
                                            <div class="mx-auto bg-slate-50 w-20 h-20 rounded-full flex items-center justify-center mb-4">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                            <h3 class="text-slate-700 font-bold text-lg mb-1">Belum Ada Galeri</h3>
                                            <p class="text-slate-400 font-medium mb-4">Mulai dengan mengupload momen pertama Anda.</p>
                                            <router-link to="/admin/gallery/tambah" class="text-blue-600 font-bold hover:underline">
                                                Upload Foto Sekarang
                                            </router-link>
                                        </td>
                                    </tr>
                                </template>

                            </tbody>
                        </table>
                    </div>
                    
                    <div v-if="totalPages > 1 && !isLoading" class="px-6 py-4 border-t border-slate-100 flex items-center justify-between bg-slate-50/50">
                        <span class="text-sm text-slate-500 font-medium">Halaman <span class="font-bold text-slate-700">{{ currentPage }}</span> dari <span class="font-bold text-slate-700">{{ totalPages }}</span></span>
                        <div class="flex gap-2">
                            <button 
                                @click="changePage(currentPage - 1)" 
                                :disabled="currentPage === 1" 
                                class="px-4 py-2 rounded-lg font-bold text-sm bg-white border border-slate-200 text-slate-600 hover:bg-slate-100 hover:text-slate-900 disabled:opacity-50 disabled:cursor-not-allowed transition-all shadow-sm"
                            >
                                Sebelumnya
                            </button>
                            
                            <div class="hidden sm:flex gap-1">
                                <button 
                                    v-for="page in totalPages" 
                                    :key="page"
                                    @click="changePage(page)"
                                    :class="[
                                        'px-3.5 py-2 rounded-lg font-bold text-sm transition-all shadow-sm border',
                                        currentPage === page ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-100'
                                    ]"
                                >
                                    {{ page }}
                                </button>
                            </div>

                            <button 
                                @click="changePage(currentPage + 1)" 
                                :disabled="currentPage === totalPages" 
                                class="px-4 py-2 rounded-lg font-bold text-sm bg-white border border-slate-200 text-slate-600 hover:bg-slate-100 hover:text-slate-900 disabled:opacity-50 disabled:cursor-not-allowed transition-all shadow-sm"
                            >
                                Selanjutnya
                            </button>
                        </div>
                    </div>
                </div>
            </main>
        </div>

        <div v-if="deleteModal.isOpen" class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-0">
            <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" @click="closeDeleteModal"></div>
            
            <div class="relative bg-white rounded-3xl w-full max-w-sm p-6 shadow-2xl animate-popIn">
                <div class="w-16 h-16 bg-red-50 text-red-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                
                <h2 class="text-xl font-bold text-center text-slate-800 mb-2">Hapus Album?</h2>
                <p class="text-center text-slate-500 text-sm mb-6 leading-relaxed">
                    Anda akan menghapus wadah <span class="font-black text-slate-700">"{{ deleteModal.album?.title }}"</span> yang berisi <span class="font-black text-red-500">{{ deleteModal.album?.total_images }} foto</span>. Tindakan ini permanen.
                </p>
                
                <div class="flex gap-3">
                    <button @click="closeDeleteModal" :disabled="deleteModal.isDeleting" class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold py-3 rounded-xl transition-colors disabled:opacity-50">
                        Batal
                    </button>
                    <button @click="confirmDelete" :disabled="deleteModal.isDeleting" class="flex-1 bg-red-500 hover:bg-red-600 text-white font-bold py-3 rounded-xl transition-colors flex items-center justify-center gap-2 shadow-sm shadow-red-500/30 disabled:opacity-50 disabled:cursor-not-allowed">
                        <span v-if="deleteModal.isDeleting" class="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>
                        {{ deleteModal.isDeleting ? 'Loading...' : 'Ya, Hapus' }}
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