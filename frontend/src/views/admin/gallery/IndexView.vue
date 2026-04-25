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

// --- STATE USER & UI ---
const user = ref({ name: 'Admin', email: '' })
const isSidebarOpen = ref(false)
const isLoading = ref(true)
const breadcrumbsData = ref([
    { label: 'Gallery', link: '/admin/gallery' }
])

// --- STATE DATA ---
const galleries = ref([])
const categories = ref([])

// --- STATE FILTER ---
const searchQuery = ref(route.query.search || '')
const selectedCategory = ref(route.query.category_id || '')

// FRONTEND PAGINATION (SINKRON DENGAN URL)
const currentPage = ref(parseInt(route.query.page) || 1)
const itemsPerPage = 10

// State Modal Hapus
const deleteModal = ref({
    isOpen: false,
    album: null,
    isDeleting: false
})

onMounted(() => {
    // Ambil data user dari localStorage agar prop 'user' pada Navbar terpenuhi
    const savedUser = localStorage.getItem('user')
    if (savedUser) {
        user.value = JSON.parse(savedUser)
    } else {
        // Jika tidak ada user, tendang ke login
        router.push({ name: 'login' })
    }

    fetchCategories()
    fetchGalleries()
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

// 2. FILTER BERDASARKAN SEARCH & KATEGORI
const filteredAlbums = computed(() => {
    let result = groupedGalleries.value

    if (selectedCategory.value) {
        result = result.filter(album => String(album.category_id) === String(selectedCategory.value))
    }

    if (searchQuery.value.trim()) {
        const q = searchQuery.value.trim().toLowerCase()
        result = result.filter(album => album.title.toLowerCase().includes(q))
    }

    return result
})

// 3. HITUNG TOTAL HALAMAN
const totalPages = computed(() => {
    return Math.ceil(filteredAlbums.value.length / itemsPerPage) || 1
})

// 4. POTONG ARRAY UNTUK PAGINATION
const paginatedAlbums = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage
    const end = start + itemsPerPage
    return filteredAlbums.value.slice(start, end)
})

const changePage = (page) => {
    if (page >= 1 && page <= totalPages.value) {
        router.push({ query: { ...route.query, page: page } })
        window.scrollTo({ top: 0, behavior: 'smooth' })
    }
}

const applyFilters = () => {
    currentPage.value = 1
    router.push({
        query: {
            ...route.query,
            page: 1,
            search: searchQuery.value || undefined,
            category_id: selectedCategory.value || undefined
        }
    })
}

const clearFilters = () => {
    searchQuery.value = ''
    selectedCategory.value = ''
    currentPage.value = 1
    router.push({ query: {} })
}

watch(() => route.query.page, (newPage) => {
    currentPage.value = parseInt(newPage) || 1
})

watch([searchQuery, selectedCategory], () => {
    applyFilters()
})

// MODAL HAPUS LOGIC
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
        if (paginatedAlbums.value.length === 1 && currentPage.value > 1) {
            changePage(currentPage.value - 1)
        }
        closeDeleteModal()
    } catch (error) {
        alert('Gagal menghapus wadah galeri')
        deleteModal.value.isDeleting = false
    }
}

// Fungsi Logout (dipanggil via event dari Navbar)
const handleLogout = () => {
    localStorage.removeItem('user')
    localStorage.removeItem('token')
    router.push({ name: 'login' })
}
</script>

<template>
    <div class="flex min-h-screen bg-slate-50">
        <div v-if="isSidebarOpen" @click="isSidebarOpen = false" class="fixed inset-0 bg-slate-900/50 z-40 lg:hidden backdrop-blur-sm"></div>
        
        <Sidebar v-model:is-open="isSidebarOpen" @logout="handleLogout" />

        <div class="flex-1 relative max-w-full overflow-hidden">
            <Navbar 
                :user="user" 
                :breadcrumbs="breadcrumbsData"
                @toggle-sidebar="isSidebarOpen = !isSidebarOpen" 
                @logout="handleLogout"
            />
            
            <main class="p-4 sm:p-6 w-full">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6">
                    <div>
                        <h1 class="text-xl sm:text-2xl font-bold text-slate-800">Manajemen Galeri (Album)</h1>
                        <p class="text-sm sm:text-base text-slate-500 mt-1">
                            Menampilkan <span class="font-bold text-blue-600">{{ filteredAlbums.length }}</span> dari <span class="font-bold text-slate-700">{{ groupedGalleries.length }}</span> Wadah Album.
                        </p>
                    </div>
                    <router-link to="/admin/gallery/tambah" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-bold transition-all flex items-center justify-center gap-2 shadow-sm shadow-blue-600/30 w-full sm:w-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        Tambah Foto Baru
                    </router-link>
                </div>

                <!-- SEARCH & FILTER TOOLBAR -->
                <div class="flex flex-col sm:flex-row gap-3 mb-5">
                    <!-- Search Input -->
                    <div class="relative flex-1">
                        <span class="absolute inset-y-0 left-3 flex items-center pointer-events-none text-black">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 0 5 11a6 6 0 0 0 12 0z" />
                            </svg>
                        </span>
                        <input
                            id="gallery-search"
                            v-model="searchQuery"
                            type="text"
                            placeholder="Cari nama album..."
                            class="w-full text-black pl-9 pr-4 py-2.5 text-sm bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-400 transition-all shadow-sm"
                        />
                    </div>

                    <!-- Category Dropdown -->
                    <div class="relative sm:w-56">
                        <span class="absolute inset-y-0 left-3 flex items-center pointer-events-none text-black">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h12M3 17h6" />
                            </svg>
                        </span>
                        <select
                            id="gallery-category-filter"
                            v-model="selectedCategory"
                            class="w-full text-black pl-9 pr-8 py-2.5 text-sm bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-400 transition-all shadow-sm appearance-none cursor-pointer"
                        >
                            <option value="">Semua Kategori</option>
                            <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                        </select>
                        <span class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-slate-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </span>
                    </div>

                    <!-- Clear Filters Button (visible only when filters are active) -->
                    <button
                        v-if="searchQuery || selectedCategory"
                        @click="clearFilters"
                        class="flex items-center justify-center gap-1.5 px-4 py-2.5 text-sm font-semibold text-slate-500 bg-slate-100 hover:bg-red-50 hover:text-red-500 border border-slate-200 rounded-xl transition-all shadow-sm whitespace-nowrap"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Reset Filter
                    </button>
                </div>

                <div class="bg-white rounded-2xl sm:rounded-3xl border border-slate-200 shadow-sm flex flex-col relative w-full overflow-hidden">
                    <div class="overflow-x-auto w-full flex-grow min-h-[300px]">
                        <table class="w-full text-left border-collapse min-w-[600px]">
                            <thead>
                                <tr class="bg-slate-50 text-slate-500 text-xs uppercase font-black tracking-wider">
                                    <th class="px-4 sm:px-6 py-4 whitespace-nowrap w-24">Cover</th>
                                    <th class="px-4 sm:px-6 py-4 whitespace-nowrap">Judul Album</th>
                                    <th class="px-4 sm:px-6 py-4 whitespace-nowrap">Kategori</th>
                                    <th class="px-4 sm:px-6 py-4 whitespace-nowrap">Isi Wadah</th>
                                    <th class="px-4 sm:px-6 py-4 text-right whitespace-nowrap">Aksi</th>
                                </tr>
                            </thead>
                            
                            <tbody class="divide-y divide-slate-100">
                                <template v-if="isLoading">
                                    <tr v-for="n in 5" :key="n" class="animate-pulse">
                                        <td colspan="5" class="px-4 py-4"><div class="h-16 bg-slate-100 rounded-xl w-full"></div></td>
                                    </tr>
                                </template>

                                <template v-else-if="paginatedAlbums.length > 0">
                                    <tr v-for="album in paginatedAlbums" :key="album.id" class="hover:bg-slate-50 transition-colors group">
                                        <td class="px-4 sm:px-6 py-4">
                                            <div class="relative w-20 h-14 sm:w-24 sm:h-16 rounded-lg overflow-hidden shadow-sm border border-slate-200">
                                                <img :src="getImageUrl(album.cover)" @error="handleImageError" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" />
                                                <div v-if="album.total_images > 1" class="absolute inset-0 bg-black/40 flex items-center justify-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 sm:px-6 py-4">
                                            <div class="font-bold text-slate-700 text-sm sm:text-base">{{ album.title }}</div>
                                        </td>
                                        <td class="px-4 sm:px-6 py-4">
                                            <span class="bg-blue-50 text-blue-600 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider">
                                                {{ album.category?.name || 'Uncategorized' }}
                                            </span>
                                        </td>
                                        <td class="px-4 sm:px-6 py-4">
                                            <span class="font-bold text-slate-700 text-sm">{{ album.total_images }} <span class="text-slate-400 font-medium">Foto</span></span>
                                        </td>
                                        <td class="px-4 sm:px-6 py-4 text-right">
                                            <div class="flex justify-end items-center gap-2">
                                                <router-link :to="`/admin/gallery/edit/${album.id}`" class="px-4 py-2 text-xs font-bold bg-slate-100 text-slate-600 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all">
                                                    Buka
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

                                <!-- EMPTY STATE SAAT FILTER AKTIF -->
                                <template v-else-if="!isLoading && filteredAlbums.length === 0">
                                    <tr>
                                        <td colspan="5" class="px-6 py-16 text-center">
                                            <div class="flex flex-col items-center gap-3 text-slate-400">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 opacity-40" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-4.35-4.35M17 11A6 6 0 1 0 5 11a6 6 0 0 0 12 0z" />
                                                </svg>
                                                <p class="font-semibold text-slate-500">Album tidak ditemukan</p>
                                                <p class="text-sm">Coba ubah kata kunci atau pilih kategori lain.</p>
                                                <button @click="clearFilters" class="mt-1 text-sm font-bold text-blue-600 hover:underline">Reset Filter</button>
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- PAGINATION -->
                <div v-if="totalPages > 1" class="flex justify-center items-center gap-2 mt-6">
                    <button
                        @click="changePage(currentPage - 1)"
                        :disabled="currentPage === 1"
                        class="px-3 py-2 rounded-xl text-sm font-semibold bg-white border border-slate-200 text-slate-600 hover:bg-blue-50 hover:text-blue-600 disabled:opacity-40 disabled:cursor-not-allowed transition-all shadow-sm"
                    >
                        &larr; Prev
                    </button>
                    <span class="text-sm text-slate-500 font-medium px-2">
                        Halaman {{ currentPage }} / {{ totalPages }}
                    </span>
                    <button
                        @click="changePage(currentPage + 1)"
                        :disabled="currentPage === totalPages"
                        class="px-3 py-2 rounded-xl text-sm font-semibold bg-white border border-slate-200 text-slate-600 hover:bg-blue-50 hover:text-blue-600 disabled:opacity-40 disabled:cursor-not-allowed transition-all shadow-sm"
                    >
                        Next &rarr;
                    </button>
                </div>
            </main>
        </div>

        <div v-if="deleteModal.isOpen" class="fixed inset-0 z-[100] flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="closeDeleteModal"></div>
            <div class="relative bg-white rounded-3xl w-full max-w-sm p-6 shadow-2xl">
                <h2 class="text-xl font-bold text-slate-800 mb-2">Hapus Album?</h2>
                <p class="text-slate-500 text-sm mb-6">Mengahapus "{{ deleteModal.album?.title }}" akan menghapus {{ deleteModal.album?.total_images }} foto secara permanen.</p>
                <div class="flex gap-3">
                    <button @click="closeDeleteModal" class="flex-1 bg-slate-100 py-3 rounded-xl font-bold text-slate-600">Batal</button>
                    <button @click="confirmDelete" :disabled="deleteModal.isDeleting" class="flex-1 bg-red-500 py-3 rounded-xl font-bold text-white shadow-lg shadow-red-500/30">
                        {{ deleteModal.isDeleting ? 'Loading...' : 'Ya, Hapus' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
    
</template>