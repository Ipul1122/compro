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
        <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm max-w-2xl mx-auto">
          <h2 class="text-2xl font-black text-slate-900 uppercase tracking-tight mb-8">Ubah Data Kategori</h2>

          <div v-if="store.loading" class="text-center py-10 font-bold text-black animate-pulse">
            Memuat data kategori...
          </div>

          <form v-else @submit.prevent="updateData">
            <div class="mb-6">
              <label for="nama_kategori" class="block text-xs font-bold text-black uppercase tracking-widest mb-2">Nama Kategori</label>
              <input 
                id="nama_kategori"
                name="nama_kategori"
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
import { useCategoryStore } from '@/stores/category';

// Import komponen reusable
import Sidebar from '@/components/admin/Sidebar.vue';
import Navbar from '@/components/admin/Navbar.vue';

const store = useCategoryStore();
const route = useRoute();
const router = useRouter();

// Layout State
const isSidebarOpen = ref(false);
const user = ref({ name: 'Admin', email: '' });

// Setup Breadcrumbs untuk Navbar
const breadcrumbsData = ref([
  { label: 'Kategori', link: '/admin/categories' },
  { label: 'Edit', link: null }
]);

const category = ref({ name: '' });

onMounted(async () => {
  // 1. Ambil data user dari sessionStorage agar Navbar memiliki nama user
  const savedUser = sessionStorage.getItem('user');
  if (savedUser) {
    user.value = JSON.parse(savedUser);
  }

  // 2. Fetch data kategori yang akan diedit
  const id = route.params.id;
  try {
    const data = await store.getCategoryDetail(id);
    category.value.name = data.name;
  } catch (err) {
    router.push('/admin/categories');
  }
});

const handleLogout = () => {
  sessionStorage.removeItem('user');
  sessionStorage.removeItem('token');
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