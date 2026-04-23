<template>
  <div class="flex min-h-screen bg-slate-50 relative overflow-x-hidden">
        <div v-if="isSidebarOpen" @click="isSidebarOpen = false" class="fixed inset-0 bg-slate-900/50 z-40 lg:hidden backdrop-blur-sm"></div>
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
              <button @click="router.push('/admin/categories/tambah')" class="bg-slate-900 text-white px-4 py-2 rounded-xl text-sm font-bold hover:bg-[#ea4435] transition-colors whitespace-nowrap">
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
                    <div class="flex justify-end items-center gap-2">
                      <button @click="router.push('/admin/articles/create')" class="p-2 text-blue-500 hover:bg-blue-50 rounded-lg transition-colors" title="Tambah Artikel">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                      </button>

                      <router-link :to="`/admin/categories/edit/${cat.id}`" class="p-2 text-yellow-500 hover:bg-yellow-50 rounded-lg transition-colors" title="Edit Kategori">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                      </router-link>

                      <button @click="confirmDelete(cat.id)" class="p-2 text-red-400 hover:bg-red-50 rounded-lg transition-colors cursor-pointer" title="Hapus Kategori">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                      </button>
                    </div>
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
            <button type="button" @click="closeModal" class="px-4 py-2 text-sm font-bold text-slate-500 hover:text-slate-800 cursor-pointer">Batal</button>
            <button type="submit" class="bg-slate-900 text-white px-6 py-2 rounded-xl text-sm font-bold hover:bg-[#ea4435] transition-colors cursor-pointer" :disabled="isSubmitting">
              {{ isSubmitting ? 'Menyimpan...' : 'Simpan' }}
            </button>
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
import Navbar from '@/components/admin/Navbar.vue';
import { useCategoryStore } from '@/stores/category';

const router = useRouter();
const route = useRoute(); 
const store = useCategoryStore();

const isSidebarOpen = ref(false);
const user = ref({ name: 'Admin', email: '' });
const currentView = ref('categories');

const breadcrumbsData = ref([
    { label: 'Dashboard', link: '/admin/dashboard' },
    { label: 'Manage Categories', link: null }
]);

const search = ref('');
const isModalOpen = ref(false);
const isSubmitting = ref(false);
const form = ref({ name: '' });

onMounted(() => {
  const savedUser = localStorage.getItem('user');
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

const handleNavigation = (view) => {
  if (view === 'dashboard') router.push('/admin/dashboard');
  else if (view === 'categories') router.push('/admin/categories');
  else if (view === 'articles') router.push('/admin/articles'); 
};

const handleLogout = () => {
  localStorage.removeItem('user');
  localStorage.removeItem('token');
  router.push('/view/login');
};

const handleSearch = () => fetchData(1);
const closeModal = () => { form.value = { name: '' }; isModalOpen.value = false; };

const submitForm = async () => {
  if (!form.value.name?.trim()) return alert("Nama kategori tidak boleh kosong!");
  isSubmitting.value = true;
  try {
    const payload = { name: form.value.name.trim() };
    const response = await store.storeCategory(payload);
    if (response && (response.status === 201 || response.data?.status === 'success')) {
      closeModal();
    }
  } catch (err) {
    alert(err.response?.data?.message || "Terjadi kesalahan!");
  } finally { isSubmitting.value = false; }
};

const isDeleteModalOpen = ref(false);
const deleteId = ref(null);
const confirmDelete = (id) => { deleteId.value = id; isDeleteModalOpen.value = true; };
const executeDelete = async () => {
  if (!deleteId.value) return;
  try {
    await store.destroyCategory(deleteId.value);
    fetchData(store.pagination?.current_page || 1); 
  } catch (err) {
    alert("Gagal menghapus kategori!");
  } finally { isDeleteModalOpen.value = false; deleteId.value = null; }
};
</script>