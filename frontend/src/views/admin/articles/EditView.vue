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
        <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm max-w-5xl mx-auto">
          <div class="flex justify-between items-center mb-8">
            <h2 class="text-2xl font-black text-slate-900 uppercase tracking-tight">Ubah Artikel</h2>
            <span v-if="draftSavedText" class="text-xs font-bold text-green-600 bg-green-50 px-3 py-1 rounded-full animate-pulse">
              {{ draftSavedText }}
            </span>
          </div>

          <div v-if="isLoading" class="text-center py-10 font-bold text-black animate-pulse text-sm">Memuat data...</div>

          <form v-else @submit.prevent="confirmUpdate" enctype="multipart/form-data">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
              
              <div class="md:col-span-2">
                <label for="gambar_utama" class="block text-xs font-bold text-black uppercase tracking-widest mb-2">Gambar Utama (Kosongkan jika tidak diubah)</label>
                <div class="flex flex-col items-start gap-4">
                  <img 
                    v-if="previewImage || currentImageUrl" 
                    :src="previewImage || currentImageUrl" 
                    class="w-full max-w-2xl aspect-video rounded-xl object-cover border border-slate-200 shadow-sm" 
                  />
                  <input 
                    id="gambar_utama"
                    name="gambar_utama"
                    type="file" 
                    @change="handleFileChange" 
                    accept="image/*"
                    class="text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-slate-900 file:text-white hover:file:bg-[#ea4435] cursor-pointer"
                  >
                </div>
              </div>

              <div>
                <label for="judul_id" class="block text-xs font-bold text-black uppercase tracking-widest mb-2">Judul Artikel (ID) <span class="text-red-500">*</span></label>
                <input id="judul_id" name="judul_id" v-model="form.title" @input="autoTranslateTitle" type="text" class="w-full border border-slate-300 p-3 rounded-xl focus:ring-2 focus:ring-slate-900 outline-none font-bold text-black bg-slate-50 focus:bg-white transition-colors" required>
              </div>

              <div class="relative">
                <label for="judul_en" class="block text-xs font-bold text-black uppercase tracking-widest mb-2">Judul Artikel (EN)</label>
                <input id="judul_en" name="judul_en" v-model="form.title_en" type="text" class="w-full border border-slate-300 p-3 rounded-xl focus:ring-2 focus:ring-slate-900 outline-none font-bold text-black bg-slate-50 focus:bg-white transition-colors">
                <span v-if="isTranslatingTitle" class="absolute right-4 top-[42px] text-xs font-bold text-slate-400 animate-pulse">Translating...</span>
              </div>

              <div>
                <label for="slug" class="block text-xs font-bold text-black uppercase tracking-widest mb-2">Slug</label>
                <input id="slug" name="slug" v-model="form.slug" type="text" disabled class="w-full border border-slate-300 p-3 rounded-xl outline-none font-bold text-slate-500 bg-slate-100 cursor-not-allowed" placeholder="otomatis-mengikuti-judul">
              </div>

              <div>
                <label for="kategori" class="block text-xs font-bold text-black uppercase tracking-widest mb-2">Kategori <span class="text-red-500">*</span></label>
                <select id="kategori" name="kategori" v-model="form.category_id" class="w-full border border-slate-300 p-3 rounded-xl focus:ring-2 focus:ring-slate-900 outline-none font-bold text-black bg-white" required>
                  <option value="">Pilih Kategori</option>
                  <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                </select>
              </div>

              <div class="md:col-span-2 mt-4">
                <div class="flex justify-between items-end mb-2">
                  <label id="label_konten_id" class="block text-xs font-bold text-black uppercase tracking-widest" aria-label="Isi Konten ID">Isi Konten (ID) <span class="text-red-500">*</span></label>
                  <button type="button" @click="generateTOC(editorId)" class="bg-blue-50 text-blue-600 px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-blue-100 transition-colors flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" /></svg>
                    Buat TOC (Daftar Isi)
                  </button>
                </div>
                
                <div v-if="editorId" class="border border-slate-300 border-b-0 rounded-t-xl p-2 bg-slate-50 flex flex-wrap gap-1">
                  <button type="button" @click="editorId.chain().focus().toggleBold().run()" :class="{'bg-slate-200': editorId.isActive('bold')}" class="p-2 rounded text-black hover:bg-slate-200 text-sm font-bold w-8 h-8 flex items-center justify-center">B</button>
                  <button type="button" @click="editorId.chain().focus().toggleItalic().run()" :class="{'bg-slate-200': editorId.isActive('italic')}" class="p-2 rounded text-black hover:bg-slate-200 text-sm italic serif w-8 h-8 flex items-center justify-center">I</button>
                  <button type="button" @click="setLink(editorId)" :class="{'bg-slate-200': editorId.isActive('link')}" class="px-3 py-1 rounded hover:bg-slate-200 text-black text-sm font-medium flex items-center gap-1">🔗 Link</button>
                  <div class="w-px h-6 bg-slate-300 mx-1 self-center"></div>
                  <button type="button" @click="editorId.chain().focus().toggleHeading({ level: 2 }).run()" :class="{'bg-slate-200': editorId.isActive('heading', { level: 2 })}" class="px-3 py-1 text-black rounded hover:bg-slate-200 text-sm font-bold">H2</button>
                  <button type="button" @click="editorId.chain().focus().toggleHeading({ level: 3 }).run()" :class="{'bg-slate-200': editorId.isActive('heading', { level: 3 })}" class="px-3 py-1 text-black rounded hover:bg-slate-200 text-sm font-bold">H3</button>
                  <div class="w-px h-6 bg-slate-300 mx-1 self-center"></div>
                  <button type="button" @click="editorId.chain().focus().toggleBulletList().run()" :class="{'bg-slate-200': editorId.isActive('bulletList')}" class="px-3 py-1 text-black rounded hover:bg-slate-200 text-sm font-bold flex items-center gap-1">• Bullet</button>
                  <button type="button" @click="editorId.chain().focus().toggleOrderedList().run()" :class="{'bg-slate-200': editorId.isActive('orderedList')}" class="px-3 py-1 text-black rounded hover:bg-slate-200 text-sm font-bold flex items-center gap-1">1. Number</button>
                  <div class="w-px h-6 bg-slate-300 mx-1 self-center"></div>
                  <div class="relative group">
                    <button type="button" class="px-3 py-1 text-black rounded hover:bg-slate-200 text-sm font-bold flex items-center gap-1">Tabel ▾</button>
                    <div class="absolute left-0 mt-1 hidden group-hover:flex flex-col bg-white border border-slate-200 shadow-lg rounded z-10 w-40">
                      <button type="button" @click="editorId.chain().focus().insertTable({ rows: 3, cols: 3, withHeaderRow: true }).run()" class="px-4 py-2 text-left text-sm hover:bg-slate-100 text-black">Buat Tabel (3x3)</button>
                      <button type="button" @click="editorId.chain().focus().addColumnAfter().run()" class="px-4 py-2 text-left text-sm hover:bg-slate-100 text-black">Tambah Kolom</button>
                      <button type="button" @click="editorId.chain().focus().deleteColumn().run()" class="px-4 py-2 text-left text-sm hover:bg-slate-100 text-black">Hapus Kolom</button>
                      <button type="button" @click="editorId.chain().focus().addRowAfter().run()" class="px-4 py-2 text-left text-sm hover:bg-slate-100 text-black">Tambah Baris</button>
                      <button type="button" @click="editorId.chain().focus().deleteRow().run()" class="px-4 py-2 text-left text-sm hover:bg-slate-100 text-black">Hapus Baris</button>
                      <button type="button" @click="editorId.chain().focus().deleteTable().run()" class="px-4 py-2 text-left text-sm hover:bg-slate-100 text-red-600 font-bold">Hapus Tabel</button>
                    </div>
                  </div>
                </div>
                <EditorContent :editor="editorId" class="prose text-black prose-slate max-w-none border border-slate-300 rounded-b-xl p-4 min-h-[300px] bg-white focus:outline-none" />
              </div>

              <div class="md:col-span-2 mt-4">
                <div class="flex justify-between items-end mb-2">
                  <label id="label_konten_en" class="block text-xs font-bold text-black uppercase tracking-widest" aria-label="Isi Konten EN">Isi Konten (EN)</label>
                  <button type="button" @click="generateTOC(editorEn)" class="bg-blue-50 text-blue-600 px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-blue-100 transition-colors flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" /></svg>
                    Buat TOC (Daftar Isi)
                  </button>
                </div>
                
                <div v-if="editorEn" class="border border-slate-300 border-b-0 rounded-t-xl p-2 bg-slate-50 flex flex-wrap gap-1">
                  <button type="button" @click="editorEn.chain().focus().toggleBold().run()" :class="{'bg-slate-200': editorEn.isActive('bold')}" class="p-2 rounded text-black hover:bg-slate-200 text-sm font-bold w-8 h-8 flex items-center justify-center">B</button>
                  <button type="button" @click="editorEn.chain().focus().toggleItalic().run()" :class="{'bg-slate-200': editorEn.isActive('italic')}" class="p-2 rounded text-black hover:bg-slate-200 text-sm italic serif w-8 h-8 flex items-center justify-center">I</button>
                  <button type="button" @click="setLink(editorEn)" :class="{'bg-slate-200': editorEn.isActive('link')}" class="px-3 py-1 rounded text-black hover:bg-slate-200 text-sm font-medium flex items-center gap-1">🔗 Link</button>
                  <div class="w-px h-6 bg-slate-300 mx-1 self-center"></div>
                  <button type="button" @click="editorEn.chain().focus().toggleHeading({ level: 2 }).run()" :class="{'bg-slate-200': editorEn.isActive('heading', { level: 2 })}" class="px-3 py-1 rounded text-black hover:bg-slate-200 text-sm font-bold">H2</button>
                  <button type="button" @click="editorEn.chain().focus().toggleHeading({ level: 3 }).run()" :class="{'bg-slate-200': editorEn.isActive('heading', { level: 3 })}" class="px-3 py-1 rounded text-black hover:bg-slate-200 text-sm font-bold">H3</button>
                  <div class="w-px h-6 bg-slate-300 mx-1 self-center"></div>
                  <button type="button" @click="editorEn.chain().focus().toggleBulletList().run()" :class="{'bg-slate-200': editorEn.isActive('bulletList')}" class="px-3 py-1 text-black rounded hover:bg-slate-200 text-sm font-bold flex items-center gap-1">• Bullet</button>
                  <button type="button" @click="editorEn.chain().focus().toggleOrderedList().run()" :class="{'bg-slate-200': editorEn.isActive('orderedList')}" class="px-3 py-1 text-black rounded hover:bg-slate-200 text-sm font-bold flex items-center gap-1">1. Number</button>
                  <div class="w-px h-6 bg-slate-300 mx-1 self-center"></div>
                  <div class="relative group">
                    <button type="button" class="px-3 py-1 text-black rounded hover:bg-slate-200 text-sm font-bold flex items-center gap-1">Tabel ▾</button>
                    <div class="absolute left-0 mt-1 hidden group-hover:flex flex-col bg-white border border-slate-200 shadow-lg rounded z-10 w-40">
                      <button type="button" @click="editorEn.chain().focus().insertTable({ rows: 3, cols: 3, withHeaderRow: true }).run()" class="px-4 py-2 text-left text-sm hover:bg-slate-100 text-black">Buat Tabel (3x3)</button>
                      <button type="button" @click="editorEn.chain().focus().addColumnAfter().run()" class="px-4 py-2 text-left text-sm hover:bg-slate-100 text-black">Tambah Kolom</button>
                      <button type="button" @click="editorEn.chain().focus().deleteColumn().run()" class="px-4 py-2 text-left text-sm hover:bg-slate-100 text-black">Hapus Kolom</button>
                      <button type="button" @click="editorEn.chain().focus().addRowAfter().run()" class="px-4 py-2 text-left text-sm hover:bg-slate-100 text-black">Tambah Baris</button>
                      <button type="button" @click="editorEn.chain().focus().deleteRow().run()" class="px-4 py-2 text-left text-sm hover:bg-slate-100 text-black">Hapus Baris</button>
                      <button type="button" @click="editorEn.chain().focus().deleteTable().run()" class="px-4 py-2 text-left text-sm hover:bg-slate-100 text-red-600 font-bold">Hapus Tabel</button>
                    </div>
                  </div>
                </div>
                <EditorContent :editor="editorEn" class="prose prose-slate max-w-none border border-slate-300 text-black rounded-b-xl p-4 min-h-[300px] bg-white focus:outline-none" />
              </div>

              <div>
                <label for="meta_title" class="block text-xs font-bold text-black uppercase tracking-widest mb-2">Meta Title (SEO)</label>
                <input id="meta_title" name="meta_title" v-model="form.meta_title" type="text" class="w-full border border-slate-300 p-3 rounded-xl focus:ring-2 focus:ring-slate-900 outline-none font-bold text-black" placeholder="Judul untuk SEO">
              </div>

              <div>
                <label for="meta_keywords" class="block text-xs font-bold text-black uppercase tracking-widest mb-2">Meta Keywords (SEO)</label>
                <div class="w-full border border-slate-300 p-2 rounded-xl focus-within:ring-2 focus-within:ring-slate-900 bg-white flex flex-wrap gap-2 items-center min-h-[50px]">
                  <span v-for="(keyword, index) in metaKeywordsArray" :key="index" class="bg-blue-50 text-blue-600 border border-blue-200 text-xs font-bold px-2.5 py-1 rounded-lg flex items-center gap-1 cursor-default">
                    {{ keyword }}
                    <button type="button" @click="removeKeyword(index)" class="hover:text-red-500 text-blue-400 focus:outline-none flex items-center justify-center">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                      </svg>
                    </button>
                  </span>
                  <input id="meta_keywords" type="text" v-model="keywordInput" @keydown.enter.prevent="addKeyword" @keydown.comma.prevent="addKeyword" @keydown.delete="removeLastKeyword" @blur="addKeyword" class="flex-1 min-w-[120px] outline-none font-bold text-black bg-transparent text-sm p-1" placeholder="Ketik lalu koma/enter">
                </div>
              </div>

              <div class="md:col-span-2">
                <label for="meta_description" class="block text-xs font-bold text-black uppercase tracking-widest mb-2">Meta Description (SEO)</label>
                <textarea id="meta_description" name="meta_description" v-model="form.meta_description" rows="3" class="w-full border border-slate-300 p-3 rounded-xl focus:ring-2 focus:ring-slate-900 outline-none font-medium text-black" placeholder="Deskripsi singkat untuk SEO..."></textarea>
              </div>

              <div>
                <label for="status" class="block text-xs font-bold text-black uppercase tracking-widest mb-2">Status</label>
                <select id="status" name="status" v-model="form.published" class="w-full border border-slate-300 p-3 rounded-xl focus:ring-2 focus:ring-slate-900 outline-none font-bold text-black bg-white">
                  <option v-if="user.role === 'direktur'" value="publish">Publish</option>
                  <option v-else value="pending">Pending (Ajukan ke Direktur)</option>
                  <option value="draft">Draft</option>
                </select>
              </div>

            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
              <router-link to="/admin/articles" class="bg-slate-100 text-slate-600 px-8 py-3 rounded-xl text-sm font-bold hover:bg-slate-200 transition-colors text-center cursor-pointer">Kembali</router-link>
              <button type="submit" :disabled="isSaving" class="bg-slate-900 text-white px-8 py-3 rounded-xl text-sm font-bold hover:bg-[#ea4435] transition-colors cursor-pointer disabled:opacity-50 flex items-center justify-center min-w-[160px]">
                {{ isSaving ? 'Menyimpan...' : 'Simpan Perubahan' }}
              </button>
            </div>
          </form>
        </div>
      </main>

      <div v-if="showConfirmModal" class="fixed inset-0 z-[100] flex items-center justify-center">
        <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" @click="showConfirmModal = false"></div>
        <div class="bg-white rounded-3xl p-8 max-w-sm w-full mx-4 relative z-10 shadow-2xl transform transition-all">
          <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
            </svg>
          </div>
          <h3 class="text-xl font-black text-slate-900 text-center mb-2">Konfirmasi Perubahan</h3>
          <p class="text-sm font-medium text-slate-500 text-center mb-8">Apakah Anda yakin ingin memperbarui artikel ini? Perubahan akan langsung disimpan ke sistem.</p>
          <div class="flex gap-3">
            <button @click="showConfirmModal = false" class="flex-1 bg-slate-100 text-slate-700 font-bold py-3 px-4 rounded-xl hover:bg-slate-200 transition-colors">
              Batal
            </button>
            <button @click="executeSubmit" class="flex-1 bg-slate-900 text-white font-bold py-3 px-4 rounded-xl hover:bg-[#ea4435] transition-colors">
              Ya, Simpan
            </button>
          </div>
        </div>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount, watch } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import Api from '@/api';
import Sidebar from '@/components/admin/Sidebar.vue';
import { getImageUrl } from '@/utils/imageHelper';

// TIPTAP IMPORTS
import { useEditor, EditorContent } from '@tiptap/vue-3';
import StarterKit from '@tiptap/starter-kit';
import { Link } from '@tiptap/extension-link';
import { Table } from '@tiptap/extension-table';
import { TableRow } from '@tiptap/extension-table-row';
import { TableCell } from '@tiptap/extension-table-cell';
import { TableHeader } from '@tiptap/extension-table-header';

const route = useRoute();
const router = useRouter();

// UI States
const isSidebarOpen = ref(false);
const currentView = ref('articles');
const isLoading = ref(true);
const isSaving = ref(false);
const showConfirmModal = ref(false);

// User State
const user = ref({ role: '' });

// Data States
const categories = ref([]);
const currentImageUrl = ref(null);
const previewImage = ref(null);
const draftSavedText = ref('');
let draftTimeout = null;

const form = ref({
    title: '',
    title_en: '',
    slug: '',
    category_id: '',
    meta_title: '',
    meta_description: '',
    meta_keywords: '',
    published: 'publish',
    image: null
});

const isTranslatingTitle = ref(false);
let translateTitleTimeout = null;

// State untuk Tag Input Meta Keywords
const keywordInput = ref('');
const metaKeywordsArray = ref([]);

const addKeyword = () => {
    let val = keywordInput.value.replace(/,/g, '').trim();
    if (val && !metaKeywordsArray.value.includes(val)) {
        metaKeywordsArray.value.push(val);
        form.value.meta_keywords = metaKeywordsArray.value.join(', ');
    }
    keywordInput.value = '';
};

const removeKeyword = (index) => {
    metaKeywordsArray.value.splice(index, 1);
    form.value.meta_keywords = metaKeywordsArray.value.join(', ');
};

const removeLastKeyword = (e) => {
    if (keywordInput.value === '' && metaKeywordsArray.value.length > 0) {
        metaKeywordsArray.value.pop();
        form.value.meta_keywords = metaKeywordsArray.value.join(', ');
    }
};

// ==========================================
// INISIALISASI TIPTAP EDITOR (ID & EN)
// ==========================================
const editorId = useEditor({
  content: '',
  extensions: [
    StarterKit.configure({ link: false }),
    Link.configure({
      openOnClick: false,
      HTMLAttributes: { class: 'text-blue-600 underline' }
    }),
    Table.configure({ resizable: true }),
    TableRow,
    TableHeader,
    TableCell,
  ],
  onUpdate: () => {
    saveDraftToLocalStorage();
  }
});

const editorEn = useEditor({
  content: '',
  extensions: [
    StarterKit.configure({ link: false }),
    Link.configure({
      openOnClick: false,
      HTMLAttributes: { class: 'text-blue-600 underline' }
    }),
    Table.configure({ resizable: true }),
    TableRow,
    TableHeader,
    TableCell,
  ],
  editable: true, 
  onUpdate: () => {
    saveDraftToLocalStorage();
  }
});

onBeforeUnmount(() => {
  editorId.value?.destroy();
  editorEn.value?.destroy();
});

// ==========================================
// FUNGSI RICH TEXT (TOC & LINK)
// ==========================================
const generateTOC = (editorInstance) => {
  if (!editorInstance) return;
  const html = editorInstance.getHTML();
  
  const headings = Array.from(new DOMParser().parseFromString(html, 'text/html').querySelectorAll('h2, h3'));
  
  if (headings.length === 0) {
    alert("Silakan buat setidaknya satu Heading (H2 / H3) di dalam editor sebelum membuat Daftar Isi.");
    return;
  }

  let tocHtml = `<div class="bg-slate-50 p-6 rounded-xl border border-slate-200 mb-6">
    <p class="font-black text-slate-900 uppercase text-xs mb-4 tracking-widest">Daftar Isi</p>
    <ul class="space-y-2 m-0 p-0 list-none">`;

  headings.forEach((heading, index) => {
    const text = heading.innerText;
    const level = heading.tagName.toLowerCase();
    const padding = level === 'h3' ? 'ml-6' : 'ml-0';
    tocHtml += `<li class="${padding}"><a href="#section-${index}" class="text-blue-600 hover:underline text-sm font-bold no-underline">${text}</a></li>`;
  });

  tocHtml += `</ul></div><p></p>`;
  
  editorInstance.chain().focus().insertContentAt(0, tocHtml).run();
};

const setLink = (editorInstance) => {
  if (!editorInstance) return;
  const previousUrl = editorInstance.getAttributes('link').href;
  const url = window.prompt('Masukkan URL link:', previousUrl);

  if (url === null) return;
  if (url === '') {
    editorInstance.chain().focus().extendMarkRange('link').unsetLink().run();
    return;
  }
  editorInstance.chain().focus().extendMarkRange('link').setLink({ href: url }).run();
};

// ==========================================
// FITUR AUTO SAVE LOCAL STORAGE DRAFT
// ==========================================
const draftKey = `draft_article_${route.params.id}`;

const saveDraftToLocalStorage = () => {
    if (isLoading.value) return; // Jangan simpan draft saat masih loading inisiasi
    
    const draftData = {
        title: form.value.title,
        title_en: form.value.title_en,
        slug: form.value.slug,
        category_id: form.value.category_id,
        meta_title: form.value.meta_title,
        meta_description: form.value.meta_description,
        meta_keywords: form.value.meta_keywords,
        published: form.value.published,
        content: editorId.value?.getHTML() || '',
        content_en: editorEn.value?.getHTML() || ''
        // Catatan: File gambar (form.value.image) tidak di-save ke localstorage untuk menghindari memory issue
    };
    
    localStorage.setItem(draftKey, JSON.stringify(draftData));
    
    draftSavedText.value = 'Draft Tersimpan ✓';
    if (draftTimeout) clearTimeout(draftTimeout);
    draftTimeout = setTimeout(() => {
        draftSavedText.value = '';
    }, 2000);
};

// Watch form reguler untuk mentrigger autosave draft
watch(() => form.value, (newVal, oldVal) => {
    if (!isLoading.value) {
        saveDraftToLocalStorage();
    }
}, { deep: true });

const checkAndRestoreDraft = (apiData) => {
    const savedDraft = localStorage.getItem(draftKey);
    if (savedDraft) {
        const confirmRestore = confirm("Ditemukan draft yang belum tersimpan untuk artikel ini (mungkin karena refresh/kembali). Apakah Anda ingin mengembalikan ketikan terakhir Anda?");
        
        if (confirmRestore) {
            try {
                const parsedDraft = JSON.parse(savedDraft);
                
                // Set form dari Draft
                form.value.title = parsedDraft.title || '';
                form.value.title_en = parsedDraft.title_en || '';
                form.value.slug = parsedDraft.slug || '';
                form.value.category_id = parsedDraft.category_id || '';
                form.value.meta_title = parsedDraft.meta_title || '';
                form.value.meta_description = parsedDraft.meta_description || '';
                form.value.meta_keywords = parsedDraft.meta_keywords || '';
                if (form.value.meta_keywords) {
                    metaKeywordsArray.value = form.value.meta_keywords.split(',').map(s => s.trim()).filter(s => s);
                } else {
                    metaKeywordsArray.value = [];
                }
                form.value.published = parsedDraft.published || 'publish';

                // Set content editor dari draft
                if (parsedDraft.content && editorId.value) {
                    editorId.value.commands.setContent(parsedDraft.content);
                }
                if (parsedDraft.content_en && editorEn.value) {
                    editorEn.value.commands.setContent(parsedDraft.content_en);
                }
                
                return; // Keluar agar tidak ditimpa oleh data API di bawah
            } catch (e) {
                console.error("Gagal parse draft localstorage", e);
            }
        } else {
            // User menolak restore draft, hapus draft
            localStorage.removeItem(draftKey);
        }
    }
    
    // Jika tidak ada draft / User reject draft -> Load API Data Original
    form.value.title = apiData.title;
    form.value.title_en = apiData.title_en || '';
    form.value.slug = apiData.slug || '';
    form.value.category_id = apiData.category_id;
    form.value.meta_title = apiData.meta_title || '';
    form.value.meta_description = apiData.meta_description || '';
    form.value.meta_keywords = apiData.meta_keywords || '';
    if (form.value.meta_keywords) {
        metaKeywordsArray.value = form.value.meta_keywords.split(',').map(s => s.trim()).filter(s => s);
    } else {
        metaKeywordsArray.value = [];
    }
    form.value.published = apiData.published;

    if (apiData.content && editorId.value) {
        editorId.value.commands.setContent(apiData.content);
    }
    if (apiData.content_en && editorEn.value) {
        editorEn.value.commands.setContent(apiData.content_en);
    }
};


// ==========================================
// LOGIC API & FORM
// ==========================================
onMounted(async () => {
    const savedUser = sessionStorage.getItem('user');
    const token = sessionStorage.getItem('token');
    
    if (!savedUser || !token) {
        alert("Sesi kamu tidak valid. Silakan relogin.");
        handleLogout();
        return;
    }

    try {
        user.value = JSON.parse(savedUser);
    } catch (e) {
        console.error('Gagal parse user dari sessionStorage', e);
    }

    await fetchCategories();
    await fetchArticleDetail();
});

const fetchCategories = async () => {
    try {
        const response = await Api.get('/admin/categories/list');
        categories.value = response.data.data;
    } catch (err) { console.error(err); }
};

const fetchArticleDetail = async () => {
    isLoading.value = true;
    try {
        const response = await Api.get(`/admin/articles/${route.params.id}`);
        const data = response.data.data;
        
        // Pengecekan draft atau apply API data
        checkAndRestoreDraft(data);

        // Load gambar current API (tidak masuk sessionStorage draft)
        if (data.image) {
            currentImageUrl.value = getImageUrl(data.image);
        } else {
            currentImageUrl.value = null;
        }

    } catch (err) {
        console.error(err);
        alert('Gagal memuat data artikel.');
        router.push('/admin/articles');
    } finally {
        isLoading.value = false;
    }
};

const generateSlug = (text) => {
    return text.toString().toLowerCase().replace(/\s+/g, '-').replace(/[^\w\-]+/g, '').replace(/\-\-+/g, '-').replace(/^-+/, '').replace(/-+$/, '');            
};

const handleTitleInput = () => {
    form.value.slug = generateSlug(form.value.title);
};

// Auto Translate Judul
const autoTranslateTitle = () => {
    handleTitleInput();
    if (translateTitleTimeout) clearTimeout(translateTitleTimeout);

    if (!form.value.title.trim()) {
        form.value.title_en = '';
        isTranslatingTitle.value = false;
        return;
    }

    isTranslatingTitle.value = true;
    translateTitleTimeout = setTimeout(async () => {
        try {
            const textToTranslate = encodeURIComponent(form.value.title.trim());
            const res = await fetch(`https://api.mymemory.translated.net/get?q=${textToTranslate}&langpair=id|en`);
            const data = await res.json();
            if (data && data.responseData) form.value.title_en = data.responseData.translatedText;
        } catch (error) { console.error("Gagal terjemah judul:", error); } 
        finally { isTranslatingTitle.value = false; }
    }, 600);
};

const handleFileChange = (e) => {
    const file = e.target.files[0];
    if (file) {
        if (file.size > 2 * 1024 * 1024) {
            alert("ups, foto melebih 2MB, mohon dicompress dahulu");
            e.target.value = ''; 
            form.value.image = null;
            
           
            return; // Hentikan eksekusi
        }

        form.value.image = file;
        previewImage.value = URL.createObjectURL(file);
    }
};

const confirmUpdate = () => {
    showConfirmModal.value = true;
};

// Eksekusi Update Artikel
const executeSubmit = async () => {
    showConfirmModal.value = false;
    isSaving.value = true;
    try {
        const token = sessionStorage.getItem('token');
        const formData = new FormData();
        
        formData.append('_method', 'PUT'); // Method Spoofing
        formData.append('title', form.value.title);
        formData.append('title_en', form.value.title_en);
        formData.append('slug', form.value.slug);
        formData.append('category_id', form.value.category_id);
        
        // Ambil dari Tiptap
        formData.append('content', editorId.value.getHTML());
        formData.append('content_en', editorEn.value.getHTML());
        
        formData.append('meta_title', form.value.meta_title || form.value.title); 
        formData.append('meta_description', form.value.meta_description || '');
        formData.append('meta_keywords', form.value.meta_keywords || '');
        formData.append('published', form.value.published);
        
        if (form.value.image) {
            formData.append('image', form.value.image);
        }

        await Api.post(`/admin/articles/${route.params.id}`, formData, {
            headers: { 
                'Content-Type': 'multipart/form-data'
            }
        });
        
        // Jika berhasil di-save ke database, bersihkan draft localstorage
        localStorage.removeItem(draftKey);

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
    sessionStorage.removeItem('user');
    sessionStorage.removeItem('token');
    router.push('/view/login');
};
</script>

<style>
/* CSS UNTUK TIPTAP */
.ProseMirror { outline: none; }
.ProseMirror p.is-editor-empty:first-child::before {
  content: attr(data-placeholder);
  float: left;
  color: #adb5bd;
  pointer-events: none;
  height: 0;
}
.prose h2 { font-size: 1.25rem; font-weight: 800; margin-top: 1rem; margin-bottom: 0.5rem; color: #0f172a;}
.prose h3 { font-size: 1.1rem; font-weight: 700; margin-top: 1rem; margin-bottom: 0.5rem; color: #334155;}
.prose a { color: #2563eb; text-decoration: underline; cursor: pointer; }
.prose ul { list-style-type: disc; padding-left: 1.5rem; margin-top: 0.5rem; margin-bottom: 0.5rem; }
.prose ol { list-style-type: decimal; padding-left: 1.5rem; margin-top: 0.5rem; margin-bottom: 0.5rem; }
.prose li { margin-bottom: 0.25rem; }
.prose table { border-collapse: collapse; width: 100%; margin: 1rem 0; }
.prose th, .prose td { border: 1px solid #cbd5e1; padding: 0.5rem; text-align: left; }
.prose th { background-color: #f1f5f9; font-weight: bold; }
button, a, .cursor-pointer { cursor: pointer !important; }
</style>