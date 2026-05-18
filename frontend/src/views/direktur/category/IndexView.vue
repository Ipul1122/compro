<template>
  <div class="flex min-h-screen bg-slate-50 relative overflow-x-hidden">
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
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden p-6">
          <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
            <h2 class="font-black text-xl text-slate-900 uppercase tracking-tight">Daftar Kategori</h2>
            <div class="flex gap-3 w-full md:w-auto">
              <input id="search_kategori" name="search_kategori" aria-label="Cari kategori" v-model="search" @input="handleSearch" type="text" placeholder="Cari kategori..." class="border border-slate-200 px-4 py-2 rounded-xl text-sm w-full md:w-64 focus:ring-2 focus:ring-slate-900 focus:outline-none text-black">
              <button @click="router.push('/direktur/categories/tambah')" class="bg-slate-900 text-white px-4 py-2 rounded-xl text-sm font-bold hover:bg-[#ea4435] transition-colors whitespace-nowrap cursor-pointer">
                Tambah Data
              </button>
            </div>
          </div>

          <div class="overflow-x-auto">
            <table class="w-full text-left">
              <thead class="bg-slate-50 border-y border-slate-100">
                <tr>
                  <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase">Nama Kategori</th>
                  <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase">Dibuat Oleh</th>
                  <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase text-center">Total Artikel</th>
                  <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase text-center">Total Galeri</th>
                  <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase text-right">Aksi</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-50">
                <tr v-if="store.loading">
                  <td colspan="5" class="p-8 text-center text-slate-500 font-bold animate-pulse">Memuat data...</td>
                </tr>
                <tr v-for="cat in store.categories" :key="cat.id" class="hover:bg-slate-50/50 transition-colors">
                  <td class="px-6 py-4">
                    <p class="font-bold text-sm text-slate-900">{{ cat.name }}</p>
                    <p class="text-[10px] text-slate-400 mt-0.5">{{ cat.slug }}</p>
                  </td>
                  <td class="px-6 py-4">
                    <div class="flex items-center gap-2">
                      <div class="w-6 h-6 rounded-full bg-amber-600 flex items-center justify-center text-white text-[9px] font-black uppercase flex-shrink-0">
                        {{ (cat.creator_name || '?').charAt(0) }}
                      </div>
                      <div>
                        <p class="text-xs font-bold text-slate-700">{{ cat.creator_name || 'Tidak diketahui' }}</p>
                        <span v-if="cat.user_id == user.id" class="text-[9px] font-black text-amber-600 bg-amber-50 px-1.5 py-0.5 rounded">Anda</span>
                      </div>
                    </div>
                  </td>
                  <td class="px-6 py-4 text-center font-bold text-slate-700 text-sm">{{ cat.articles_count }}</td>
                  <td class="px-6 py-4 text-center font-bold text-slate-700 text-sm">{{ cat.galleries_count }}</td>
                  <td class="px-6 py-4 text-right">
                    <div class="flex justify-end items-center gap-2">
                      <!-- Tombol Tambah Artikel (semua bisa) — kirim category_id -->
                      <button 
                        @click="router.push(`/direktur/articles/create?category_id=${cat.id}`)" 
                        class="p-2 text-blue-500 hover:bg-blue-50 rounded-lg transition-colors cursor-pointer" 
                        title="Tambah Artikel dengan Kategori ini"
                      >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                      </button>

                      <!-- Tombol Tambah Galeri (semua bisa) -->
                      <button 
                        @click="router.push(`/direktur/gallery/tambah?category_id=${cat.id}`)" 
                        class="p-2 text-emerald-500 hover:bg-emerald-50 rounded-lg transition-colors cursor-pointer" 
                        title="Tambah Galeri"
                      >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                      </button>

                      <!-- Edit: hanya pemilik -->
                      <template v-if="isOwner(cat)">
                        <router-link :to="`/direktur/categories/edit/${cat.id}`" class="p-2 text-yellow-500 hover:bg-yellow-50 rounded-lg transition-colors cursor-pointer" title="Edit Kategori">
                          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                          </svg>
                        </router-link>

                        <button @click="confirmDelete(cat)" class="p-2 text-red-400 hover:bg-red-50 rounded-lg transition-colors cursor-pointer" title="Hapus Kategori">
                          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                          </svg>
                        </button>
                      </template>

                      <!-- Bukan pemilik: tampilkan lock icon -->
                      <template v-else>
                        <span class="p-2 text-slate-300 cursor-not-allowed" title="Hanya pemilik yang bisa edit">
                          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                          </svg>
                        </span>
                      </template>
                    </div>
                  </td>
                </tr>
                <tr v-if="!store.loading && store.categories.length === 0">
                  <td colspan="5" class="p-8 text-center text-slate-400 text-sm">Tidak ada kategori ditemukan.</td>
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

    <div v-if="isDeleteModalOpen" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm flex items-center justify-center p-4 z-[200]">
      <div class="bg-white p-6 rounded-3xl w-full max-w-sm shadow-2xl text-center scale-100 transition-transform">
        <div class="w-16 h-16 bg-red-100 text-red-500 rounded-full flex items-center justify-center mx-auto mb-4">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
          </svg>
        </div>
        <h3 class="text-lg font-black text-slate-900 mb-2 tracking-tight">Hapus Kategori?</h3>
        <p class="text-xs text-slate-500 mb-2 font-medium leading-relaxed">Yakin anda ingin delete? Tindakan ini tidak dapat dibatalkan.</p>
        
        <p v-if="deleteCategory && (deleteCategory.articles_count > 0 || deleteCategory.galleries_count > 0)" class="text-xs text-red-500 mb-6 font-bold leading-relaxed bg-red-50 p-3 rounded-xl border border-red-100">
          Category ini telah terdata di <span v-if="deleteCategory.articles_count > 0">{{ deleteCategory.articles_count }} articles</span><span v-if="deleteCategory.articles_count > 0 && deleteCategory.galleries_count > 0"> dan </span><span v-if="deleteCategory.galleries_count > 0">{{ deleteCategory.galleries_count }} gallery</span>.
        </p>
        <p v-else class="mb-6"></p>

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
import Sidebar from '@/components/direktur/Sidebar.vue';
import Navbar from '@/components/direktur/Navbar.vue';
import { useCategoryStore } from '@/stores/category';

const router = useRouter();
const route = useRoute(); 
const store = useCategoryStore();

const isSidebarOpen = ref(false);
const user = ref({ name: 'Direktur', email: '', id: null });

const breadcrumbsData = ref([
  { label: 'Dashboard', link: '/direktur/dashboard' },
  { label: 'Manage Categories', link: null }
]);

const search = ref('');

onMounted(() => {
  const savedUser = sessionStorage.getItem('user');
  if (savedUser) {
    user.value = JSON.parse(savedUser);
  } else {
    router.push('/view/login');
  }

  if (route.query.search) {
    search.value = route.query.search;
  }
  const page = route.query.page || 1;
  store.fetchCategories(page, search.value);
});

const fetchData = (page = 1) => {
  router.replace({
    query: {
      page: page > 1 ? page : undefined, 
      search: search.value ? search.value : undefined 
    }
  });
  store.fetchCategories(page, search.value);
};

const handleSearch = () => fetchData(1);

const handleLogout = () => {
  sessionStorage.removeItem('user');
  sessionStorage.removeItem('token');
  router.push('/view/login');
};

// Cek apakah user yang login adalah pemilik kategori, atau admin/direktur
const isOwner = (cat) => {
  // Jika user_id null (kategori lama tanpa pemilik), semua bisa edit
  if (!cat.user_id) return true;
  return cat.user_id == user.value.id || user.value.role === 'admin' || user.value.role === 'direktur';
};

// Modal Hapus Kategori
const isDeleteModalOpen = ref(false);
const deleteId = ref(null);
const deleteCategory = ref(null);

const confirmDelete = (cat) => { 
  deleteId.value = cat.id; 
  deleteCategory.value = cat;
  isDeleteModalOpen.value = true; 
};

const executeDelete = async () => {
  if (!deleteId.value) return;
  try {
    await store.destroyCategory(deleteId.value);
    fetchData(store.pagination?.current_page || 1); 
  } catch (err) {
    alert("Gagal menghapus kategori!");
  } finally { 
    isDeleteModalOpen.value = false; 
    deleteId.value = null; 
    deleteCategory.value = null;
  }
};
</script>