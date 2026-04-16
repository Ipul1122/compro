<template>
  <div class="flex min-h-screen bg-slate-50 relative overflow-x-hidden">
    <div v-if="isSidebarOpen" @click="isSidebarOpen = false" class="fixed inset-0 bg-slate-900/50 z-60 lg:hidden backdrop-blur-sm cursor-pointer"></div>
    
    <Sidebar 
      v-model:is-open="isSidebarOpen" 
      v-model:current-view="currentView"
      @update:currentView="handleNavigation"
      @logout="handleLogout"
    />

    <div class="flex-1 flex flex-col min-w-0">
      <nav class="h-20 bg-white border-b border-slate-100 px-4 md:px-8 flex items-center justify-between sticky top-0 z-50">
        <div class="flex items-center gap-4">
          <button @click="isSidebarOpen = !isSidebarOpen" class="lg:hidden p-2 text-slate-600 hover:bg-slate-50 rounded-lg cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
          </button>
          <h2 class="text-base md:text-lg font-bold text-slate-900 uppercase tracking-tight">Edit Category</h2>
        </div>
      </nav>

      <main class="p-4 md:p-8">
        <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm max-w-2xl mx-auto">
          <h2 class="text-2xl font-black text-slate-900 uppercase tracking-tight mb-8">Ubah Data Kategori</h2>

          <div v-if="store.loading" class="text-center py-10 font-bold text-black animate-pulse">
            Memuat data kategori...
          </div>

          <form v-else @submit.prevent="updateData">
            <div class="mb-6">
              <label class="block text-xs font-bold text-black uppercase tracking-widest mb-2">Nama Kategori</label>
              <input 
                v-model="category.name" 
                type="text" 
                class="w-full border border-slate-500 p-3 rounded-xl focus:ring-2 focus:ring-slate-900 focus:outline-none font-bold text-black"
                placeholder="Masukkan nama kategori"
                required
              >
            </div>

            <div class="flex gap-3 pt-4 border-t border-slate-100">
              <button type="submit" class="bg-slate-900 text-white px-8 py-3 rounded-xl text-sm font-bold hover:bg-[#ea4435] transition-colors cursor-pointer">
                Simpan Perubahan
              </button>
              <router-link to="/admin/categories" class="bg-slate-100 text-slate-600 px-8 py-3 rounded-xl text-sm font-bold hover:bg-slate-200 transition-colors">
                Kembali
              </router-link>
            </div>
          </form>
        </div>
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import Sidebar from '@/components/admin/Sidebar.vue';
import { useCategoryStore } from '@/stores/category';

const store = useCategoryStore();
const route = useRoute();
const router = useRouter();

// Layout State
const isSidebarOpen = ref(false);
const currentView = ref('categories'); 

const category = ref({ name: '' });

onMounted(async () => {
  const id = route.params.id;
  try {
    const data = await store.getCategoryDetail(id);
    category.value.name = data.name;
  } catch (err) {
    router.push('/admin/categories');
  }
});

// Menangani Klik Menu dari Sidebar
const handleNavigation = (view) => {
  if (view === 'dashboard' || view === 'articles') {
    router.push('/admin/dashboard'); 
  } else if (view === 'categories') {
    router.push('/admin/categories');
  }
};

const handleLogout = () => {
  localStorage.removeItem('user');
  localStorage.removeItem('token');
  router.push('/view/login');
};

const updateData = async () => {
  try {
    await store.updateCategory(route.params.id, { name: category.value.name });
    router.push('/admin/categories');
  } catch (err) {
    alert('Gagal memperbarui data. Pastikan tidak ada duplikasi nama.');
  }
};
</script>

<style scoped>
button, a, .cursor-pointer { cursor: pointer !important; }
</style>