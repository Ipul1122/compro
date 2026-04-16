<template>
  <div class="flex min-h-screen bg-slate-50 relative overflow-x-hidden">
    <div v-if="isSidebarOpen" @click="isSidebarOpen = false" class="fixed inset-0 bg-slate-900/50 z-[60] lg:hidden backdrop-blur-sm cursor-pointer"></div>
    
    <Sidebar v-model:is-open="isSidebarOpen" v-model:current-view="currentView" @update:currentView="handleNavigation" @logout="handleLogout" />

    <div class="flex-1 flex flex-col min-w-0">
      <nav class="h-20 bg-white border-b border-slate-100 px-4 md:px-8 flex items-center justify-between sticky top-0 z-[50]">
        <div class="flex items-center gap-4">
          <button @click="isSidebarOpen = !isSidebarOpen" class="lg:hidden p-2 text-slate-600 hover:bg-slate-50 rounded-lg cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
          </button>
          <h2 class="text-base md:text-lg font-bold text-slate-900 uppercase tracking-tight">Edit Article</h2>
        </div>
      </nav>

      <main class="p-4 md:p-8">
        <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm max-w-4xl mx-auto">
          <h2 class="text-2xl font-black text-slate-900 uppercase tracking-tight mb-8">Ubah Artikel</h2>

          <div v-if="isLoading" class="text-center py-10 font-bold text-black animate-pulse text-sm">Memuat data...</div>

          <form v-else @submit.prevent="updateArticle" enctype="multipart/form-data">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
              <div class="md:col-span-2">
                <label class="block text-xs font-bold text-black uppercase tracking-widest mb-2">Judul Artikel</label>
                <input v-model="form.title" type="text" class="w-full border border-slate-300 p-3 rounded-xl focus:ring-2 focus:ring-slate-900 outline-none font-bold text-black" required>
              </div>

              <div>
                <label class="block text-xs font-bold text-black uppercase tracking-widest mb-2">Kategori</label>
                <select v-model="form.category_id" class="w-full border border-slate-300 p-3 rounded-xl focus:ring-2 focus:ring-slate-900 outline-none font-bold text-black bg-white" required>
                  <option value="">Pilih Kategori</option>
                  <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                </select>
              </div>

              <div>
                <label class="block text-xs font-bold text-black uppercase tracking-widest mb-2">Status</label>
                <select v-model="form.published" class="w-full border border-slate-300 p-3 rounded-xl focus:ring-2 focus:ring-slate-900 outline-none font-bold text-black bg-white">
                  <option value="publish">Publish</option>
                  <option value="draft">Draft</option>
                </select>
              </div>

              <div class="md:col-span-2">
                <label class="block text-xs font-bold text-black uppercase tracking-widest mb-2">Isi Konten</label>
                <textarea v-model="form.content" rows="8" class="w-full border border-slate-300 p-3 rounded-xl focus:ring-2 focus:ring-slate-900 outline-none font-medium text-black" required></textarea>
              </div>

              <div class="md:col-span-2">
                <label class="block text-xs font-bold text-black uppercase tracking-widest mb-2">Gambar Utama (Kosongkan jika tidak diubah)</label>
                
                <div class="flex flex-col items-start gap-4">
                  
                  <img 
                    v-if="previewImage || currentImageUrl" 
                    :src="previewImage || currentImageUrl" 
                    class="w-full max-w-2xl aspect-video rounded-xl object-cover border border-slate-200 shadow-sm" 
                  />
                  
                  <input 
                    type="file" 
                    @change="handleFileChange" 
                    class="text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-slate-900 file:text-white hover:file:bg-[#ea4435] cursor-pointer"
                  >
                </div>
              </div>
            </div>

            <div class="flex gap-3 pt-4 border-t border-slate-100">
              <button type="submit" :disabled="isSaving" class="bg-slate-900 text-white px-8 py-3 rounded-xl text-sm font-bold hover:bg-[#ea4435] transition-colors cursor-pointer disabled:opacity-50">
                {{ isSaving ? 'Menyimpan...' : 'Simpan Perubahan' }}
              </button>
              <router-link to="/admin/articles" class="bg-slate-100 text-slate-600 px-8 py-3 rounded-xl text-sm font-bold hover:bg-slate-200 transition-colors text-center">Kembali</router-link>
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
import axios from 'axios';
import Sidebar from '@/components/admin/Sidebar.vue';

const route = useRoute();
const router = useRouter();

// UI States
const isSidebarOpen = ref(false);
const currentView = ref('articles');
const isLoading = ref(true);
const isSaving = ref(false);

// Data States
const categories = ref([]);
const currentImageUrl = ref(null);
const previewImage = ref(null);
const form = ref({
    title: '',
    category_id: '',
    content: '',
    published: 'publish',
    image: null
});

onMounted(async () => {
    await fetchCategories();
    await fetchArticleDetail();
});

const fetchCategories = async () => {
    try {
        // Jangan lupa sertakan Bearer Token jika route ini di dalam grup auth:sanctum
        const token = localStorage.getItem('token');
        const response = await axios.get('http://localhost:8000/api/admin/categories/list', {
            headers: { Authorization: `Bearer ${token}` }
        });
        
        // Sekarang responsnya sudah pasti array data kategori
        categories.value = response.data.data;
    } catch (err) { 
        console.error(err); 
    }
};

const fetchArticleDetail = async () => {
    isLoading.value = true;
    try {
        const token = localStorage.getItem('token');
        const response = await axios.get(`http://localhost:8000/api/admin/articles/${route.params.id}`, {
            headers: { Authorization: `Bearer ${token}` }
        });
        const data = response.data.data;
        
        form.value.title = data.title;
        form.value.category_id = data.category_id;
        form.value.content = data.content;
        form.value.published = data.published;

        // --- PERBAIKAN URL GAMBAR ---
        if (data.image) {
            // Cek apakah data.image sudah berawalan http (berarti URL lengkap)
            if (data.image.startsWith('http')) {
                currentImageUrl.value = data.image;
            } else {
                // Jika cuma path, gabungkan dengan localhost backend dan folder storage
                // Note: Sesuaikan 'storage/' dengan tempat kamu nyimpen file public di Laravel
                currentImageUrl.value = `http://localhost:8000/storage/${data.image}`;
            }
        } else {
            currentImageUrl.value = null;
        }

    } catch (err) {
        console.error(err);
        router.push('/admin/articles');
    } finally {
        isLoading.value = false;
    }
};

const handleFileChange = (e) => {
    const file = e.target.files[0];
    form.value.image = file;
    previewImage.value = URL.createObjectURL(file);
};

const updateArticle = async () => {
    isSaving.value = true;
    try {
        const token = localStorage.getItem('token');
        const formData = new FormData();
        formData.append('_method', 'PUT'); // RAHASIA: Method Spoofing
        formData.append('title', form.value.title);
        formData.append('category_id', form.value.category_id);
        formData.append('content', form.value.content);
        formData.append('published', form.value.published);
        
        if (form.value.image) {
            formData.append('image', form.value.image);
        }

        await axios.post(`http://localhost:8000/api/admin/articles/${route.params.id}`, formData, {
            headers: { 
                Authorization: `Bearer ${token}`,
                'Content-Type': 'multipart/form-data'
            }
        });

        alert('Artikel berhasil diperbarui!');
        router.push('/admin/articles');
    } catch (err) {
        alert('Gagal memperbarui artikel. Periksa inputan Anda.');
        console.error(err);
    } finally {
        isSaving.value = false;
    }
};

const handleNavigation = (view) => {
    if (view === 'dashboard') router.push('/admin/dashboard');
    else if (view === 'categories') router.push('/admin/categories');
};

const handleLogout = () => {
    localStorage.removeItem('user');
    localStorage.removeItem('token');
    router.push('/view/login');
};
</script>

<style scoped>
button, a, .cursor-pointer { cursor: pointer !important; }
</style>