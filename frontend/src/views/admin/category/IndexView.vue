<template>
  <div class="flex min-h-screen bg-slate-50 relative overflow-x-hidden">
    <div v-if="isSidebarOpen" @click="isSidebarOpen = false" class="fixed inset-0 bg-slate-900/50 z-[60] lg:hidden backdrop-blur-sm cursor-pointer"></div>

    <Sidebar 
      v-model:is-open="isSidebarOpen" 
      v-model:current-view="currentView"
      @update:currentView="handleNavigation"
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
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden p-6">
          <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
            <h2 class="font-black text-xl text-slate-900 uppercase tracking-tight">Daftar Kategori</h2>
            <div class="flex gap-3 w-full md:w-auto">
              <input v-model="search" @input="handleSearch" type="text" placeholder="Cari kategori..." class="border border-slate-200 px-4 py-2 rounded-xl text-sm w-full md:w-64 focus:ring-2 focus:ring-slate-900 focus:outline-none text-black">
              <button @click="openModal()" class="bg-slate-900 text-white px-4 py-2 rounded-xl text-sm font-bold hover:bg-[#ea4435] transition-colors whitespace-nowrap">
                Tambah Data
              </button>
            </div>
          </div>

          <div class="overflow-x-auto">
            <table class="w-full text-left">
              <thead class="bg-slate-50 border-y border-slate-100">
                <tr>
                  <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase">Nama Kategori</th>
                  <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase">Slug</th>
                  <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase text-center">Total Artikel</th>
                  <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase text-right">Aksi</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-50">
                <tr v-if="store.loading">
                  <td colspan="4" class="p-8 text-center text-slate-500 font-bold animate-pulse">Memuat data...</td>
                </tr>
                <tr v-for="cat in store.categories" :key="cat.id" class="hover:bg-slate-50/50 transition-colors">
                  <td class="px-6 py-4 font-bold text-sm text-slate-900">{{ cat.name }}</td>
                  <td class="px-6 py-4 text-xs text-slate-500">{{ cat.slug }}</td>
                  <td class="px-6 py-4 text-center font-bold text-slate-700 text-sm">{{ cat.articles_count }}</td>
                  <td class="px-6 py-4 text-right">
                    <router-link :to="`/admin/categories/edit/${cat.id}`" class="text-slate-400 hover:text-yellow-500 mr-4 transition-colors text-sm font-bold">Edit</router-link>
                   <button @click="confirmDelete(cat.id)" class="text-red-400 hover:text-red-600 transition-colors text-sm font-bold cursor-pointer">Hapus</button>
                  </td>
                </tr>
                <tr v-if="!store.loading && store.categories.length === 0">
                  <td colspan="4" class="p-8 text-center text-slate-400 text-sm">Tidak ada kategori ditemukan.</td>
                </tr>
              </tbody>
            </table>
          </div>

          <div class="mt-6 flex justify-between items-center" v-if="store.pagination?.total > 0">
            <p class="text-xs font-bold text-slate-400">Total: {{ store.pagination.total }} data</p>
            <div class="flex gap-2">
              <button 
                @click="fetchData(store.pagination.current_page - 1)" 
                :disabled="store.pagination.current_page === 1" 
                class="px-3 py-1.5 border border-slate-200 text-xs font-bold rounded-lg disabled:opacity-50 hover:bg-slate-50 transition-colors cursor-pointer text-black">
                Prev
              </button>
              <button 
                @click="fetchData(store.pagination.current_page + 1)" 
                :disabled="store.pagination.current_page === store.pagination.last_page" 
                class="px-3 py-1.5 border border-slate-200 text-xs font-bold rounded-lg disabled:opacity-50 hover:bg-slate-50 transition-colors cursor-pointer text-black">
                Next
              </button>
            </div>
          </div>
        </div>
      </main>
    </div>

    <div v-if="isModalOpen" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm flex items-center justify-center p-4 z-[200]">
      <div class="bg-white p-6 rounded-3xl w-full max-w-md shadow-2xl">
        <h3 class="text-xl font-black text-slate-900 mb-4 uppercase tracking-tight">Tambah Kategori</h3>
        <form @submit.prevent="submitForm">
          <div class="mb-6">
            <label class="block text-s font-bold text-black tracking-widest mb-2">Nama Kategori</label>
            <input v-model="form.name" type="text" placeholder="Masukkan nama..." class="border border-slate-200 w-full p-3 rounded-xl focus:ring-2 focus:ring-slate-900 focus:outline-none text-black" required>
          </div>
          <div class="flex justify-end gap-3">
            <button type="button" @click="isModalOpen = false" class="px-4 py-2 text-sm font-bold text-slate-500 hover:text-slate-800 cursor-pointer">Batal</button>
            <button type="submit" class="bg-slate-900 text-white px-6 py-2 rounded-xl text-sm font-bold hover:bg-[#ea4435] transition-colors cursor-pointer">Simpan</button>
          </div>
        </form>
      </div>
    </div>

    <div v-if="isDeleteModalOpen" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm flex items-center justify-center p-4 z-[200]">
      <div class="bg-white p-6 rounded-3xl w-full max-w-sm shadow-2xl text-center scale-100 transition-transform">
        <div class="w-16 h-16 bg-red-100 text-red-500 rounded-full flex items-center justify-center mx-auto mb-4">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
          </svg>
        </div>
        
        <h3 class="text-lg font-black text-slate-900 mb-2 tracking-tight">Hapus Kategori?</h3>
        <p class="text-xs text-slate-500 mb-6 font-medium leading-relaxed">Tindakan ini tidak dapat dibatalkan. Kategori ini akan dihapus secara permanen dari sistem.</p>
        
        <div class="flex justify-center gap-3">
          <button @click="isDeleteModalOpen = false" class="px-5 py-3 text-sm font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors cursor-pointer w-full">Batal</button>
          <button @click="executeDelete" class="px-5 py-3 text-sm font-bold text-white bg-red-500 hover:bg-red-600 rounded-xl transition-colors cursor-pointer w-full shadow-lg shadow-red-500/30">Ya, Hapus</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import Sidebar from '@/components/admin/Sidebar.vue';
import Navbar from '@/components/admin/Navbar.vue'; // IMPORT COMPONENT NAVBAR
import { useCategoryStore } from '@/stores/category';

const router = useRouter();
const route = useRoute(); 
const store = useCategoryStore();

// Layout State
const isSidebarOpen = ref(false);
const user = ref({ name: 'Admin', email: '' });
const currentView = ref('categories');

// Data Breadcrumbs untuk dikirim ke Navbar
const breadcrumbsData = ref([
    { label: 'Dashboard', link: '/admin/dashboard' },
    { label: 'Manage Categories', link: null }
]);

// Category State
const search = ref('');
const isModalOpen = ref(false);
const form = ref({ name: '' });

onMounted(() => {
  const savedUser = localStorage.getItem('user');
  if (savedUser) {
    user.value = JSON.parse(savedUser);
  } else {
    router.push('/view/login');
  }

  // 1. BACA URL SAAT HALAMAN PERTAMA KALI DIBUKA / DI-REFRESH
  if (route.query.search) {
    search.value = route.query.search;
  }
  const page = route.query.page || 1;
  
  // Fetch dengan parameter dari URL
  store.fetchCategories(page, search.value);
});

// 2. FUNGSI UNTUK MENSINKRONKAN URL BROWSER & MENGAMBIL DATA
const fetchData = (page = 1) => {
  // Update URL Browser
  router.replace({
    query: {
      page: page > 1 ? page : undefined, // Sembunyikan ?page=1 agar URL lebih bersih
      search: search.value ? search.value : undefined // Sembunyikan ?search= jika kosong
    }
  });

  // Panggil API Backend
  store.fetchCategories(page, search.value);
};

// Navigasi Sidebar
const handleNavigation = (view) => {
  if (view === 'dashboard') {
    router.push('/admin/dashboard');
  } else if (view === 'categories') {
    router.push('/admin/categories');
  } else if (view === 'articles') {
    router.push('/admin/articles'); 
  }
};

const handleLogout = () => {
  localStorage.removeItem('user');
  localStorage.removeItem('token');
  router.push('/view/login');
};

// CRUD Functions
const handleSearch = () => {
  fetchData(1);
};

const openModal = () => {
  form.value.name = '';
  isModalOpen.value = true;
};

const submitForm = async () => {
  try {
    await store.storeCategory(form.value);
    isModalOpen.value = false;
    fetchData(1); // Refresh data
  } catch (err) {
    alert("Terjadi kesalahan saat menyimpan!");
  }
};

const isDeleteModalOpen = ref(false);
const deleteId = ref(null);

const confirmDelete = (id) => {
  deleteId.value = id;
  isDeleteModalOpen.value = true;
};

const executeDelete = async () => {
  if (!deleteId.value) return;
  
  try {
    await store.destroyCategory(deleteId.value);
    fetchData(store.pagination.current_page); // Refresh data
  } catch (err) {
    alert("Gagal menghapus kategori!");
  } finally {
    isDeleteModalOpen.value = false;
    deleteId.value = null;
  }
};
</script>

<style scoped>
button, a, .cursor-pointer { cursor: pointer !important; }
</style>