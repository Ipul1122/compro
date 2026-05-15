<template>
  <div class="flex min-h-screen bg-slate-50 relative overflow-x-hidden">
    <Sidebar v-model:is-open="isSidebarOpen" @logout="handleLogout" />

    <div class="flex-1 flex flex-col min-w-0">
      <Navbar :user="user" :breadcrumbs="breadcrumbsData" @toggle-sidebar="isSidebarOpen = !isSidebarOpen" @logout="handleLogout" />

      <main class="p-4 md:p-8">
        <div class="max-w-4xl mx-auto mb-6">
          <div class="bg-amber-500 text-white p-4 rounded-2xl flex justify-between items-center shadow-lg shadow-amber-100">
            <div class="flex items-center gap-3">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
              <span class="font-bold">Persetujuan Artikel: Tinjau dan ambil tindakan.</span>
            </div>
          </div>
        </div>

        <div v-if="loading" class="flex justify-center py-20 italic text-slate-400 animate-pulse">Memuat pratinjau artikel...</div>

        <article v-else-if="article" class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden max-w-4xl mx-auto">
          <div class="relative w-full aspect-video md:aspect-[21/9] overflow-hidden">
            <img :src="getImageUrl(article.image)" @error="handleImageError" class="w-full h-full object-cover" :alt="article.title" />
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent flex items-end p-6 md:p-12">
               <span class="bg-amber-500 text-white text-[10px] font-black uppercase tracking-[0.2em] px-4 py-2 rounded-full shadow-lg">
                {{ article.category?.name }}
              </span>
            </div>
          </div>

          <div class="p-6 md:p-12">
            <div class="flex items-center gap-4 mb-6 text-slate-400 text-xs font-bold uppercase tracking-widest">
              <span>{{ formatDate(article.created_at) }}</span>
              <div class="w-1 h-1 bg-slate-300 rounded-full"></div>
              <span>Direktur Persetujuan</span>
            </div>

            <h1 class="text-3xl md:text-5xl font-black text-slate-900 leading-tight mb-8">{{ article.title }}</h1>
            
            <div class="prose prose-slate prose-lg max-w-none 
                        prose-headings:font-black prose-headings:text-slate-900 
                        prose-a:text-amber-600 prose-img:rounded-3xl" 
                 v-html="article.content">
            </div>

            <div class="mt-12 pt-8 border-t border-slate-100 flex justify-between items-center">
               <div class="flex flex-wrap gap-2">
                  <span v-for="tag in (article.meta_keywords ? article.meta_keywords.split(',') : [])" :key="tag" class="text-[10px] font-bold text-slate-400 border border-slate-200 px-3 py-1 rounded-lg">#{{ tag.trim() }}</span>
               </div>
            </div>
            
            <div class="mt-8 flex justify-end gap-3">
              <button @click="handleReject" :disabled="isUpdating" class="bg-red-50 text-red-600 px-8 py-3 rounded-xl text-sm font-bold hover:bg-red-100 transition-colors text-center cursor-pointer disabled:opacity-50">
                Tolak Artikel
              </button>
              <button @click="handleApprove" :disabled="isUpdating" class="bg-emerald-500 text-white px-8 py-3 rounded-xl text-sm font-bold hover:bg-emerald-600 transition-colors cursor-pointer disabled:opacity-50 flex items-center justify-center min-w-[160px]">
                {{ isUpdating ? 'Memproses...' : 'Setujui Artikel' }}
              </button>
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
import Sidebar from '@/components/direktur/Sidebar.vue';
import Navbar from '@/components/direktur/Navbar.vue';
import { getImageUrl, handleImageError } from '@/utils/imageHelper';

const route = useRoute();
const router = useRouter();
const isSidebarOpen = ref(false);
const loading = ref(true);
const isUpdating = ref(false);
const article = ref(null);
const user = ref({ name: 'Direktur', role: 'direktur' });

const breadcrumbsData = ref([
    { label: 'Artikel', link: '/direktur/articles' },
    { label: 'Persetujuan', link: null }
]);

onMounted(async () => {
    const savedUser = sessionStorage.getItem('user');
    if (savedUser) user.value = JSON.parse(savedUser);
    await fetchArticle();
});

const fetchArticle = async () => {
    loading.value = true;
    try {
        const response = await Api.get(`/admin/articles/preview/${route.params.slug}`);
        article.value = response.data.data;
    } catch (error) {
        console.error("Gagal memuat pratinjau artikel:", error);
    } finally {
        loading.value = false;
    }
};

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' });
};

const handleReject = async () => {
    if (!confirm('Tolak artikel ini dan kembalikan ke draft?')) return;
    isUpdating.value = true;
    try {
        await Api.post(`/direktur/articles/${article.value.id}`, {
            _method: 'PUT',
            published: 'draft'
        });
        alert('Artikel telah ditolak (dikembalikan sebagai draft).');
        router.push('/direktur/articles');
    } catch (error) {
        console.error("Gagal menolak artikel:", error);
        alert('Terjadi kesalahan saat memproses data.');
    } finally {
        isUpdating.value = false;
    }
};

const handleApprove = async () => {
    if (!confirm('Setujui artikel ini untuk dipublikasikan?')) return;
    isUpdating.value = true;
    try {
        await Api.post(`/direktur/articles/${article.value.id}/approve`);
        alert('Artikel berhasil disetujui dan dipublish!');
        router.push('/direktur/articles');
    } catch (error) {
        console.error("Gagal menyetujui artikel:", error);
        if (error.response && error.response.data && error.response.data.message) {
            alert(error.response.data.message);
        } else {
            alert('Terjadi kesalahan saat menyetujui artikel.');
        }
    } finally {
        isUpdating.value = false;
    }
};

const handleLogout = () => {
    sessionStorage.clear();
    router.push('/view/login');
};
</script>

<style>
/* Styling untuk render konten HTML dari Tiptap */
.prose { color: #1e293b; }
.prose h2 { font-size: 1.875rem; margin-top: 2.5rem; margin-bottom: 1.25rem; }
.prose p { margin-bottom: 1.5rem; line-height: 1.8; }
</style>
