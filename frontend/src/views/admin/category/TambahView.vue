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
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden p-6 max-w-2xl mx-auto">
          <div class="mb-6 border-b border-slate-100 pb-4">
            <h2 class="font-black text-xl text-slate-900 uppercase tracking-tight">Tambah Kategori Baru</h2>
            <p class="text-xs text-slate-500 mt-1">Tambahkan kategori untuk mengelompokkan artikel di sistem.</p>
          </div>

          <form @submit.prevent="submitForm" class="space-y-6">
            <div>
              <label class="block text-sm font-bold text-slate-900 tracking-widest mb-2">Nama Kategori <span class="text-red-500">*</span></label>
              <input 
                v-model="form.name" 
                type="text" 
                placeholder="Contoh: Teknologi, Pendidikan, dll..." 
                class="border border-slate-200 w-full p-3 rounded-xl focus:ring-2 focus:ring-slate-900 focus:outline-none text-black bg-slate-50 focus:bg-white transition-colors" 
                required
              >
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
              <button 
                type="button" 
                @click="router.push('/admin/categories')" 
                class="px-6 py-3 text-sm font-bold text-slate-500 hover:text-slate-800 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors cursor-pointer"
              >
                Kembali
              </button>
              <button 
                type="submit" 
                class="bg-slate-900 text-white px-8 py-3 rounded-xl text-sm font-bold hover:bg-[#ea4435] transition-colors cursor-pointer flex items-center justify-center min-w-[140px]" 
                :disabled="isSubmitting"
              >
                <span v-if="isSubmitting" class="animate-pulse">Menyimpan...</span>
                <span v-else>Simpan Data</span>
              </button>
            </div>
          </form>
        </div>
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import Sidebar from '@/components/admin/Sidebar.vue';
import Navbar from '@/components/admin/Navbar.vue';
import { useCategoryStore } from '@/stores/category';

const router = useRouter();
const store = useCategoryStore();

// Layout State
const isSidebarOpen = ref(false);
const user = ref({ name: 'Admin', email: '' });
const currentView = ref('categories');

// Breadcrumbs Setup
const breadcrumbsData = ref([
    { label: 'Dashboard', link: '/admin/dashboard' },
    { label: 'Manage Categories', link: '/admin/categories' },
    { label: 'Tambah Kategori', link: null }
]);

// Form State
const isSubmitting = ref(false);
const form = ref({
  name: ''
});

onMounted(() => {
  const savedUser = localStorage.getItem('user');
  const token = localStorage.getItem('token');
  
  // Guard untuk nge-cek token sebelum bisa ngakses form
  if (!savedUser || !token) {
    alert("Sesi kamu tidak valid atau sudah habis. Silakan relogin.");
    handleLogout();
    return;
  }
  
  user.value = JSON.parse(savedUser);
});

// Navigation Helpers
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

// Form Submission
const submitForm = async () => {
  if (!form.value.name || !form.value.name.trim()) {
    alert("Nama kategori tidak boleh kosong!");
    return;
  }

  isSubmitting.value = true;

  try {
    // Memisah payload jadi plain object untuk menghindari bentrok i18n
    const payload = {
      name: form.value.name.trim()
    };

    await store.storeCategory(payload);
    
    // Redirect balik ke tabel setelah sukses
    router.push('/admin/categories');

  } catch (err) {
    console.error("Gagal nyimpen kategori:", err);

    let errorMessage = "Terjadi kesalahan sistem saat menyimpan!";
    
    if (err.response && err.response.data) {
      // Menangkap Unauthenticated secara spesifik
      if (err.response.status === 401 || err.response.data.message === 'Unauthenticated.') {
         alert("Sesi login kamu sudah habis (Unauthenticated). Silakan login ulang ya.");
         handleLogout();
         return;
      }
      
      if (err.response.data.errors) {
        errorMessage = Object.values(err.response.data.errors).flat().join('\n');
      } else if (err.response.data.message) {
        errorMessage = err.response.data.message;
      }
    } else if (err.message) {
      errorMessage = err.message; 
    }

    alert(errorMessage);
  } finally {
    isSubmitting.value = false;
  }
};
</script>

<style scoped>
button, a, .cursor-pointer { cursor: pointer !important; }
</style>