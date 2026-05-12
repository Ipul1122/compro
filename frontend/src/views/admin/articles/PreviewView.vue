<template>
  <div class="flex min-h-screen bg-slate-50 relative overflow-x-hidden">
    <Sidebar v-model:is-open="isSidebarOpen" @logout="handleLogout" />

    <div class="flex-1 flex flex-col min-w-0">
      <Navbar :user="user" :breadcrumbs="breadcrumbsData" @toggle-sidebar="isSidebarOpen = !isSidebarOpen" @logout="handleLogout" />

      <main class="p-4 md:p-8">
        <div class="max-w-4xl mx-auto mb-6">
          <div class="bg-green-600 text-white p-4 rounded-2xl flex justify-between items-center shadow-lg shadow-green-100">
            <div class="flex items-center gap-3">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
              <span class="font-bold">Pratinjau: Artikel berhasil disimpan!</span>
            </div>
            <router-link to="/admin/articles" class="bg-white text-green-600 px-4 py-2 rounded-xl text-xs font-black uppercase hover:bg-slate-100 transition">Selesai</router-link>
          </div>
        </div>

        <div v-if="loading" class="flex justify-center py-20 italic text-slate-400 animate-pulse">Memuat pratinjau artikel...</div>

        <article v-else-if="article" class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden max-w-4xl mx-auto">
          <div class="relative w-full aspect-video md:aspect-[21/9] overflow-hidden">
            <img :src="getImageUrl(article.image)" class="w-full h-full object-cover" :alt="article.title" />
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent flex items-end p-6 md:p-12">
               <span class="bg-[#ea4435] text-white text-[10px] font-black uppercase tracking-[0.2em] px-4 py-2 rounded-full shadow-lg">
                {{ article.category?.name }}
              </span>
            </div>
          </div>

          <div class="p-6 md:p-12">
            <div class="flex items-center gap-4 mb-6 text-slate-400 text-xs font-bold uppercase tracking-widest">
              <span>{{ formatDate(article.created_at) }}</span>
              <div class="w-1 h-1 bg-slate-300 rounded-full"></div>
              <span>Admin Preview</span>
            </div>

            <h1 class="text-3xl md:text-5xl font-black text-slate-900 leading-tight mb-8">{{ article.title }}</h1>
            
            <div class="prose prose-slate prose-lg max-w-none 
                        prose-headings:font-black prose-headings:text-slate-900 
                        prose-a:text-blue-600 prose-img:rounded-3xl" 
                 v-html="article.content">
            </div>

            <div class="mt-12 pt-8 border-t border-slate-100 flex justify-between items-center">
               <div class="flex flex-wrap gap-2">
                  <span v-for="tag in (article.meta_keywords ? article.meta_keywords.split(',') : [])" :key="tag" class="text-[10px] font-bold text-slate-400 border border-slate-200 px-3 py-1 rounded-lg">#{{ tag.trim() }}</span>
               </div>
               <button @click="$router.push('/admin/articles')" class="text-slate-900 font-black text-xs uppercase tracking-widest hover:text-[#ea4435] transition">Kembali ke Index</button>
            </div>
          </div>
        </article>
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import Api from '@/api';
import Sidebar from '@/components/admin/Sidebar.vue';
import Navbar from '@/components/admin/Navbar.vue';

const route = useRoute();
const router = useRouter();
const isSidebarOpen = ref(false);
const loading = ref(true);
const article = ref(null);
const user = ref({ name: 'Admin' });

const breadcrumbsData = ref([
    { label: 'Artikel', link: '/admin/articles' },
    { label: 'Pratinjau', link: null }
]);

onMounted(async () => {
    const savedUser = sessionStorage.getItem('user');
    if (savedUser) user.value = JSON.parse(savedUser);
    await fetchArticle();
});

const fetchArticle = async () => {
    loading.value = true;
    try {
        // PERHATIKAN: URL-nya memakai /preview/
        const response = await Api.get(`/admin/articles/preview/${route.params.slug}`);
        article.value = response.data.data;
    } catch (error) {
        console.error("Gagal memuat pratinjau artikel:", error);
    } finally {
        loading.value = false;
    }
};

const getImageUrl = (path) => {
    if (!path) return '';
    const baseURL = Api.defaults.baseURL.replace('/api', '');
    return `${baseURL}/storage/${path}`;
};

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' });
};

const handleLogout = () => {
    sessionStorage.clear();
    router.push('/view/login');
};
</script>

<style>
/* Pastikan styling Tailwind Typography (prose) ter-load untuk render HTML dari Tiptap */
.prose { color: #1e293b; }
.prose h2 { font-size: 1.875rem; margin-top: 2.5rem; margin-bottom: 1.25rem; }
.prose p { margin-bottom: 1.5rem; line-height: 1.8; }
</style>