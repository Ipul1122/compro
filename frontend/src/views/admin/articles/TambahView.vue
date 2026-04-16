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
          <h2 class="text-base md:text-lg font-bold text-slate-900 uppercase tracking-tight">Tambah Artikel</h2>
        </div>
      </nav>

      <main class="p-4 md:p-8">
        <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm max-w-4xl mx-auto">
          <h2 class="text-2xl font-black text-slate-900 uppercase tracking-tight mb-8">Buat Artikel Baru</h2>

          <form @submit.prevent="storeArticle" enctype="multipart/form-data">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
              
              <div>
                <label class="block text-xs font-bold text-black uppercase tracking-widest mb-2">Judul Artikel</label>
                <input v-model="form.title" @input="handleTitleInput" type="text" class="w-full border border-slate-300 p-3 rounded-xl focus:ring-2 focus:ring-slate-900 outline-none font-bold text-black" required placeholder="Masukkan judul artikel">
              </div>

              <div>
                <label class="block text-xs font-bold text-black uppercase tracking-widest mb-2">Slug</label>
                <input v-model="form.slug" type="text" disabled class="w-full border border-slate-300 p-3 rounded-xl outline-none font-bold text-slate-500 bg-slate-100 cursor-not-allowed" placeholder="otomatis-mengikuti-judul">
              </div>

              <div>
                <label class="block text-xs font-bold text-black uppercase tracking-widest mb-2">Kategori</label>
                <select v-model="form.category_id" class="w-full border border-slate-300 p-3 rounded-xl focus:ring-2 focus:ring-slate-900 outline-none font-bold text-black bg-white" required>
                  <option value="" disabled>Pilih Kategori</option>
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
                <textarea v-model="form.content" rows="8" class="w-full border border-slate-300 p-3 rounded-xl focus:ring-2 focus:ring-slate-900 outline-none font-medium text-black" required placeholder="Tulis isi artikel di sini..."></textarea>
              </div>

              <div>
                <label class="block text-xs font-bold text-black uppercase tracking-widest mb-2">Meta Title (SEO)</label>
                <input v-model="form.meta_title" type="text" class="w-full border border-slate-300 p-3 rounded-xl focus:ring-2 focus:ring-slate-900 outline-none font-bold text-black" placeholder="Judul untuk SEO">
              </div>

              <div>
                <label class="block text-xs font-bold text-black uppercase tracking-widest mb-2">Meta Keywords (SEO)</label>
                <input v-model="form.meta_keywords" type="text" class="w-full border border-slate-300 p-3 rounded-xl focus:ring-2 focus:ring-slate-900 outline-none font-bold text-black" placeholder="pisahkan, dengan, koma">
              </div>

              <div class="md:col-span-2">
                <label class="block text-xs font-bold text-black uppercase tracking-widest mb-2">Meta Description (SEO)</label>
                <textarea v-model="form.meta_description" rows="3" class="w-full border border-slate-300 p-3 rounded-xl focus:ring-2 focus:ring-slate-900 outline-none font-medium text-black" placeholder="Deskripsi singkat untuk SEO..."></textarea>
              </div>

              <div class="md:col-span-2">
                <label class="block text-xs font-bold text-black uppercase tracking-widest mb-2">Gambar Utama</label>
                <div class="flex flex-col items-start gap-4">
                  <img v-if="previewImage" :src="previewImage" class="w-full max-w-2xl aspect-video rounded-xl object-cover border border-slate-200 shadow-sm" />
                  <input type="file" @change="handleFileChange" accept="image/*" class="text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-slate-900 file:text-white hover:file:bg-[#ea4435] cursor-pointer" required>
                </div>
              </div>
            </div>

            <div class="flex gap-3 pt-4 border-t border-slate-100">
              <button type="submit" :disabled="isSaving" class="bg-slate-900 text-white px-8 py-3 rounded-xl text-sm font-bold hover:bg-[#ea4435] transition-colors cursor-pointer disabled:opacity-50">
                {{ isSaving ? 'Menyimpan...' : 'Simpan Artikel' }}
              </button>
              <router-link to="/admin/articles" class="bg-slate-100 text-slate-600 px-8 py-3 rounded-xl text-sm font-bold hover:bg-slate-200 transition-colors text-center">Batal</router-link>
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
import axios from 'axios';
import Sidebar from '@/components/admin/Sidebar.vue';
import { getImageUrl } from '@/utils/imageHelper';

const router = useRouter();

// UI States
const isSidebarOpen = ref(false);
const currentView = ref('articles');
const isSaving = ref(false);

// Data States
const categories = ref([]);
const previewImage = ref(null);

const form = ref({
    title: '',
    slug: '',
    category_id: '',
    content: '',
    meta_title: '',
    meta_description: '',
    meta_keywords: '',
    published: 'publish',
    image: null
});

onMounted(async () => {
    await fetchCategories();
});

const fetchCategories = async () => {
    try {
        const token = localStorage.getItem('token');
        const response = await axios.get('http://localhost:8000/api/admin/categories/list', {
            headers: { Authorization: `Bearer ${token}` }
        });
        categories.value = response.data.data;
    } catch (err) { 
        console.error("Error fetching categories:", err); 
    }
};

// Auto Generate Slug
const generateSlug = (text) => {
    return text.toString().toLowerCase()
        .replace(/\s+/g, '-')           
        .replace(/[^\w\-]+/g, '')       
        .replace(/\-\-+/g, '-')         
        .replace(/^-+/, '')             
        .replace(/-+$/, '');            
};

// Fungsi ini akan terus mengupdate slug setiap kali judul diketik
const handleTitleInput = () => {
    form.value.slug = generateSlug(form.value.title);
};

const handleFileChange = (e) => {
    const file = e.target.files[0];
    if (file) {
        form.value.image = file;
        previewImage.value = URL.createObjectURL(file);
    }
};

const storeArticle = async () => {
    isSaving.value = true;
    try {
        const token = localStorage.getItem('token');
        const formData = new FormData();
        
        formData.append('title', form.value.title);
        formData.append('slug', form.value.slug); // Slug tetap terkirim meski inputnya disabled
        formData.append('category_id', form.value.category_id);
        formData.append('content', form.value.content);
        formData.append('meta_title', form.value.meta_title || form.value.title); 
        formData.append('meta_description', form.value.meta_description);
        formData.append('meta_keywords', form.value.meta_keywords);
        formData.append('published', form.value.published);
        
        if (form.value.image) {
            formData.append('image', form.value.image);
        }

        await axios.post('http://localhost:8000/api/admin/articles', formData, {
            headers: { 
                Authorization: `Bearer ${token}`,
                'Content-Type': 'multipart/form-data'
            }
        });

        alert('Artikel berhasil ditambahkan!');
        router.push('/admin/articles');
    } catch (err) {
        alert('Gagal menyimpan artikel. Pastikan semua field wajib terisi.');
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