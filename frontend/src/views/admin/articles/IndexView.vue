<script setup>
import { ref, onMounted, watch, computed } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import axios from 'axios'
import { getImageUrl, handleImageError } from '@/utils/imageHelper' 

// Import komponen reusable
import Sidebar from '@/components/admin/Sidebar.vue'
import Navbar from '@/components/admin/Navbar.vue' 

const router = useRouter()
const route = useRoute() 

const isSidebarOpen = ref(false)
const user = ref({ name: 'Admin', email: '' })

// Setup Breadcrumbs untuk Navbar
const breadcrumbsData = ref([
    { label: 'Artikel', link: '/admin/articles' },
    { label: 'Index', link: null }
])

const articles = ref([])
const categories = ref([])
const isLoading = ref(false)

// State Pagination
const currentPage = ref(1)
const lastPage = ref(1)
const totalItems = ref(0)

// State Filter
const filters = ref({
    search: route.query.search || '',
    status: route.query.status || '',
    category_id: route.query.category_id || ''
})

// Searchable Category Dropdown Logic
const categorySearchText = ref('')
const isCategoryDropdownOpen = ref(false)

const filteredCategories = computed(() => {
    return categories.value.filter(cat => 
        cat.name.toLowerCase().includes(categorySearchText.value.toLowerCase())
    )
})

const selectCategory = (cat) => {
    filters.value.category_id = cat.id
    categorySearchText.value = cat.name
    isCategoryDropdownOpen.value = false
    applyFilters()
}

onMounted(() => {
    const savedUser = localStorage.getItem('user')
    if (savedUser) {
        user.value = JSON.parse(savedUser)
        
        fetchCategories()
        const pageFromUrl = parseInt(route.query.page) || 1
        fetchArticles(pageFromUrl)
    } else {
        router.push('/view/login')
    }
})

watch(() => route.query.page, (newPage) => {
    const page = parseInt(newPage) || 1
    if (page !== currentPage.value) {
        fetchArticles(page)
    }
})

// Ambil daftar kategori untuk dropdown
const fetchCategories = async () => {
    try {
        const token = localStorage.getItem('token')
        const response = await axios.get('http://localhost:8000/api/admin/categories/list', {
            headers: { Authorization: `Bearer ${token}` }
        })
        categories.value = response.data.data || response.data
        
        // Set label teks pencarian jika URL punya filter category_id
        if (filters.value.category_id) {
            const currentCat = categories.value.find(c => c.id == filters.value.category_id)
            if (currentCat) categorySearchText.value = currentCat.name
        }
    } catch (error) {
        console.error("Error fetching categories:", error)
    }
}

const fetchArticles = async (page = 1) => {
    isLoading.value = true
    try {
        const token = localStorage.getItem('token')
        const params = {
            page: page,
            search: filters.value.search,
            published: filters.value.status,
            category_id: filters.value.category_id
        }

        const response = await axios.get('http://localhost:8000/api/admin/articles', {
            headers: { Authorization: `Bearer ${token}` },
            params: params
        })
        
        articles.value = response.data.data
        
        if (response.data.pagination) {
            currentPage.value = response.data.pagination.current_page
            lastPage.value = response.data.pagination.last_page
            totalItems.value = response.data.pagination.total
            
            // Bersihkan params kosong agar URL terlihat rapi
            const queryParams = { page: page }
            if (params.search) queryParams.search = params.search
            if (params.published) queryParams.status = params.published
            if (params.category_id) queryParams.category_id = params.category_id

            router.push({ path: '/admin/articles', query: queryParams })
        }
    } catch (error) {
        console.error("Error fetching articles:", error)
    } finally {
        isLoading.value = false
    }
}

const applyFilters = () => {
    fetchArticles(1) // Selalu kembali ke halaman 1 ketika menerapkan filter baru
}

const resetFilters = () => {
    filters.value = { search: '', status: '', category_id: '' }
    categorySearchText.value = ''
    fetchArticles(1)
}

const handleLogout = () => {
    localStorage.removeItem('user')
    localStorage.removeItem('token')
    router.push('/view/login')
}

const formatDate = (dateString) => {
    if (!dateString) return '-'
    return new Date(dateString).toLocaleDateString('id-ID', {
        day: 'numeric', month: 'short', year: 'numeric'
    })
}

const confirmDelete = async (id) => {
    if (confirm('Apakah Anda yakin ingin menghapus artikel ini?')) {
        try {
            const token = localStorage.getItem('token')
            await axios.delete(`http://localhost:8000/api/admin/articles/${id}`, {
                headers: { Authorization: `Bearer ${token}` }
            })
            
            alert('Artikel berhasil dihapus!')
            fetchArticles(currentPage.value)
        } catch (error) {
            console.error("Error deleting article:", error)
            alert('Gagal menghapus artikel. Silakan coba lagi.')
        }
    }
}
</script>

<template>
    <div class="flex min-h-screen bg-slate-50 relative overflow-x-hidden">
        <div v-if="isCategoryDropdownOpen" @click="isCategoryDropdownOpen = false" class="fixed inset-0 z-[90] bg-transparent"></div>

        <Sidebar 
            v-model:is-open="isSidebarOpen" 
            @logout="handleLogout"
        />

        <div class="flex-1 flex flex-col min-w-0">
            
            <Navbar 
                :user="user" 
                :breadcrumbs="breadcrumbsData" 
                @toggle-sidebar="isSidebarOpen = !isSidebarOpen" 
                @logout="handleLogout"
            />

            <main class="p-4 md:p-8">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                    <div>
                        <h3 class="font-black text-xl text-slate-900 uppercase tracking-tight">Article List</h3>
                        <p class="text-xs text-slate-500 font-bold mt-1">Total: {{ totalItems }} Artikel ditemukan</p>
                    </div>
                    <router-link to="/admin/articles/create" class="bg-slate-900 text-white px-6 py-2 rounded-xl text-sm font-bold hover:bg-[#ea4435] transition-colors cursor-pointer w-full md:w-auto text-center inline-block">
                        Create New
                    </router-link>
                </div>

                <div class="bg-white rounded-3xl border border-slate-100 p-6 mb-6 shadow-sm">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase mb-2 block">Search Title</label>
                            <input v-model="filters.search" @keyup.enter="applyFilters" type="text" placeholder="Ketik judul artikel..." 
                                class="w-full bg-slate-50 border border-slate-100 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-900/5 transition-all text-black" />
                        </div>

                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase mb-2 block">Status</label>
                            <select v-model="filters.status" @change="applyFilters" 
                                class="w-full bg-slate-50 border text-black border-slate-100 rounded-xl px-4 py-2 text-sm focus:outline-none cursor-pointer">
                                <option value="">Semua Status</option>
                                <option value="publish">Published</option>
                                <option value="draft">Draft</option>
                            </select>
                        </div>

                        <div class="relative">
                            <label class="text-[10px] font-black text-slate-400 uppercase mb-2 block">Category</label>
                            <div class="relative">
                                <input 
                                    v-model="categorySearchText" 
                                    @focus="isCategoryDropdownOpen = true"
                                    placeholder="Cari & pilih kategori..." 
                                    class="w-full bg-slate-50 border text-black border-slate-100 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-900/5 transition-all"
                                />
                                <div v-if="isCategoryDropdownOpen" class="absolute z-[120] w-full mt-2 bg-white border border-slate-100 rounded-xl shadow-xl max-h-48 overflow-y-auto">
                                    <div @click="filters.category_id = ''; categorySearchText = ''; isCategoryDropdownOpen = false; applyFilters()" 
                                        class="px-4 py-3 text-sm hover:bg-slate-50 cursor-pointer text-slate-500 italic font-medium">
                                        Semua Kategori
                                    </div>
                                    <div v-for="cat in filteredCategories" :key="cat.id" @click="selectCategory(cat)"
                                        class="px-4 py-3 text-sm hover:bg-slate-50 cursor-pointer border-t border-slate-50 font-medium text-slate-700">
                                        {{ cat.name }}
                                    </div>
                                    <div v-if="filteredCategories.length === 0" class="px-4 py-3 text-xs text-slate-400 text-center">
                                        Tidak ditemukan
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-end gap-2">
                            <button @click="applyFilters" class="flex-1 bg-blue-50 text-blue-600 border border-blue-100 font-bold py-2 rounded-xl text-sm hover:bg-blue-100 transition-all cursor-pointer">
                                Filter
                            </button>
                            <button @click="resetFilters" class="p-2 text-slate-400 hover:text-red-600 bg-slate-50 border border-slate-100 hover:bg-red-50 rounded-xl transition-all cursor-pointer" title="Reset Semua Filter">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden p-6">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-slate-50 border-y border-slate-100">
                                <tr>
                                    <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase">Category</th>
                                    <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase">Article</th>
                                    <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase">Status</th>
                                    <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase text-center">Views</th>
                                    <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase">Created</th>
                                    <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase">Updated</th>
                                    <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                <tr v-if="isLoading">
                                    <td colspan="7" class="p-8 text-center text-slate-500 font-bold animate-pulse">Memuat data...</td>
                                </tr>
                                <tr v-for="article in articles" :key="article.id" class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <span class="bg-blue-50 text-blue-600 border border-blue-100 px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-wider whitespace-nowrap">
                                            {{ article.category?.name || 'Tanpa Kategori' }}
                                        </span>
                                    </td>
                                    
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <img 
                                                :src="getImageUrl(article.image)" 
                                                @error="handleImageError"
                                                class="w-12 h-12 rounded-lg object-cover bg-slate-100 shrink-0 border border-slate-200" 
                                                alt="Article Image" 
                                            />
                                            <div class="max-w-[200px]">
                                                <p class="text-sm font-bold text-slate-900 truncate" :title="article.title">{{ article.title }}</p>
                                                <p class="text-[10px] text-slate-400 truncate">{{ article.slug }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <td class="px-6 py-4">
                                        <span :class="article.published === 'publish' ? 'bg-emerald-100 text-emerald-600 border-emerald-200' : 'bg-orange-100 text-orange-600 border-orange-200'" class="px-3 py-1.5 rounded-lg border text-[10px] font-black uppercase tracking-wider">
                                            {{ article.published === 'publish' ? 'Published' : 'Draft' }}
                                        </span>
                                    </td>
                                    
                                    <td class="px-6 py-4 text-center font-bold text-slate-700 text-sm">
                                        {{ article.total_view || 0 }}
                                    </td>
                                    
                                    <td class="px-6 py-4 text-xs text-slate-500 font-medium whitespace-nowrap">
                                        {{ formatDate(article.created_at) }}
                                    </td>
                                    
                                    <td class="px-6 py-4 text-xs text-slate-500 font-medium whitespace-nowrap">
                                        {{ formatDate(article.updated_at) }}
                                    </td>
                                    
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex justify-end gap-2">
                                            <router-link :to="`/admin/articles/edit/${article.id}`" 
                                                class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all"
                                                title="Edit Artikel">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </router-link>

                                            <button @click="confirmDelete(article.id)" 
                                                class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all cursor-pointer"
                                                title="Hapus Artikel">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="!isLoading && articles.length === 0">
                                    <td colspan="7" class="p-10 text-center text-slate-400 text-sm font-medium">Tidak ada artikel ditemukan dengan kriteria tersebut.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div v-if="lastPage > 1" class="flex flex-col sm:flex-row justify-between items-center mt-6 pt-6 border-t border-slate-100 gap-4">
                        <span class="text-sm font-medium text-slate-500">
                            Menampilkan halaman <span class="font-bold text-slate-800">{{ currentPage }}</span> dari <span class="font-bold text-slate-800">{{ lastPage }}</span> 
                        </span>
                        
                        <div class="flex items-center gap-2">
                            <button 
                                @click="fetchArticles(currentPage - 1)" 
                                :disabled="currentPage === 1"
                                class="px-4 py-2 text-sm font-bold text-slate-600 bg-slate-50 border border-slate-100 rounded-lg hover:bg-slate-100 disabled:opacity-50 disabled:cursor-not-allowed transition-colors cursor-pointer">
                                Sebelumnya
                            </button>
                            
                            <button 
                                @click="fetchArticles(currentPage + 1)" 
                                :disabled="currentPage === lastPage"
                                class="px-4 py-2 text-sm font-bold text-slate-600 bg-slate-50 border border-slate-100 rounded-lg hover:bg-slate-100 disabled:opacity-50 disabled:cursor-not-allowed transition-colors cursor-pointer">
                                Selanjutnya
                            </button>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</template>

<style scoped>
button, a, .cursor-pointer { cursor: pointer !important; }
</style>