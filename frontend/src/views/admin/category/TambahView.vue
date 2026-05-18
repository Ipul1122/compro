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
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden p-6 max-w-2xl mx-auto">
          <div class="mb-6 border-b border-slate-100 pb-4">
            <h2 class="font-black text-xl text-slate-900 uppercase tracking-tight">Tambah Kategori Baru</h2>
            <p class="text-xs text-slate-500 mt-1">Tambahkan kategori untuk mengelompokkan artikel di sistem.</p>
          </div>

          <form @submit.prevent="submitForm" class="space-y-6">
            <div>
              <label for="nama_kategori" class="block text-sm font-bold text-slate-900 tracking-widest mb-2">Nama Kategori <span class="text-red-500">*</span></label>
              <input 
                id="nama_kategori"
                name="nama_kategori"
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
                @click="router.push(backPath)" 
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

    <!-- Error Popover / Toast -->
    <div 
      v-if="showError" 
      class="fixed top-4 right-4 z-50 bg-red-500 text-white px-6 py-3 rounded-xl shadow-lg flex items-center gap-3 transition-opacity duration-300"
    >
      <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
      </svg>
      <span class="font-medium text-sm">{{ errorMessage }}</span>
      <button @click="showError = false" class="ml-4 text-white hover:text-red-200 transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
        </svg>
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import { useCategoryStore } from '@/stores/category';

// Import komponen reusable
import Sidebar from '@/components/admin/Sidebar.vue';
import Navbar from '@/components/admin/Navbar.vue';

const router = useRouter();
const route = useRoute();
const store = useCategoryStore();

// Jika datang dari halaman artikel, setelah simpan redirect balik ke artikel
const fromArticles = computed(() => route.query.from === 'articles');
const backPath = computed(() => fromArticles.value ? '/admin/articles/create' : '/admin/categories');

// Layout State
const isSidebarOpen = ref(false);
const user = ref({ name: 'Admin', email: '' });

// Breadcrumbs Setup
const breadcrumbsData = ref([
    { label: 'Kategori', link: '/admin/categories' },
    { label: 'Tambah Kategori', link: null }
]);

// Form State
const isSubmitting = ref(false);
const form = ref({
  name: ''
});

// Error Popover State
const showError = ref(false);
const errorMessage = ref('');
let errorTimeout = null;

const showPopover = (msg) => {
  errorMessage.value = msg;
  showError.value = true;
  if (errorTimeout) clearTimeout(errorTimeout);
  errorTimeout = setTimeout(() => {
    showError.value = false;
  }, 4000);
};

onMounted(() => {
  const savedUser = sessionStorage.getItem('user');
  const token = sessionStorage.getItem('token');
  
  // Guard untuk mengecek keberadaan token sebelum bisa mengakses form
  if (!savedUser || !token) {
    alert("Sesi kamu tidak valid atau sudah habis. Silakan relogin.");
    handleLogout();
    return;
  }
  
  user.value = JSON.parse(savedUser);
});

const handleLogout = () => {
  sessionStorage.removeItem('user');
  sessionStorage.removeItem('token');
  router.push('/view/login');
};

// Form Submission
const submitForm = async () => {
  if (!form.value.name || !form.value.name.trim()) {
    showPopover("Nama kategori tidak boleh kosong!");
    return;
  }

  isSubmitting.value = true;

  try {
    // Memisah payload menjadi plain object untuk menghindari bentrok
    const payload = {
      name: form.value.name.trim()
    };

    const response = await store.storeCategory(payload);
    const newCategoryId = response?.data?.data?.id;
    
    // Redirect: jika datang dari halaman artikel, kembali ke sana dengan category_id; jika tidak, ke index kategori
    if (fromArticles.value && newCategoryId) {
        router.push(`/admin/articles/create?category_id=${newCategoryId}`);
    } else {
        router.push(backPath.value);
    }

  } catch (err) {
    console.error("Gagal menyimpan kategori:", err);

    let errorMsg = "Terjadi kesalahan sistem saat menyimpan!";
    
    if (err.response && err.response.data) {
      // Menangkap status Unauthenticated (401) secara spesifik
      if (err.response.status === 401 || err.response.data.message === 'Unauthenticated.') {
         showPopover("Sesi login kamu sudah habis (Unauthenticated). Silakan login ulang ya.");
         setTimeout(handleLogout, 2000);
         return;
      }
      
      // Menangkap error duplikat entry dari database
      const dbMessage = err.response.data.message || '';
      if (dbMessage.includes("Integrity constraint violation") && dbMessage.includes("Duplicate entry") && dbMessage.includes("categories_slug_unique")) {
         errorMsg = "Kategori sudah ada! Silakan gunakan nama kategori yang berbeda.";
      } else if (err.response.data.errors) {
        errorMsg = Object.values(err.response.data.errors).flat().join('\n');
      } else if (err.response.data.message) {
        errorMsg = err.response.data.message;
      }
    } else if (err.message) {
      errorMsg = err.message; 
    }

    showPopover(errorMsg);
  } finally {
    isSubmitting.value = false;
  }
};
</script>

<style scoped>
button, a, .cursor-pointer { cursor: pointer !important; }
</style>