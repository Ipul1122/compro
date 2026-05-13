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
        <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm max-w-5xl mx-auto">
          <div class="flex justify-between items-center mb-8">
            <h2 class="text-2xl font-black text-slate-900 uppercase tracking-tight">Buat Artikel Baru</h2>
            <span v-if="hasDraft" class="text-xs font-bold text-green-600 bg-green-50 px-3 py-1 rounded-full animate-pulse">
              Draft tersimpan otomatis
            </span>
          </div>

          <form @submit.prevent="storeArticle" enctype="multipart/form-data">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
              
              <div class="md:col-span-2">
                <label for="gambar_utama" class="block text-xs font-bold text-black uppercase tracking-widest mb-2">Gambar Utama <span class="text-red-500">*</span></label>
                <div class="flex flex-col items-start gap-4">
                  <img v-if="previewImage" :src="previewImage" class="w-full max-w-2xl aspect-video rounded-xl object-cover border border-slate-200 shadow-sm" />
                  <input id="gambar_utama" name="gambar_utama" type="file" @change="handleFileChange" accept="image/*" class="text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-slate-900 file:text-white hover:file:bg-[#ea4435] cursor-pointer" :required="!previewImage">
                </div>
              </div>

              <div>
                <label for="judul_id" class="block text-xs font-bold text-black uppercase tracking-widest mb-2">Judul Artikel (ID) <span class="text-red-500">*</span></label>
                <input id="judul_id" name="judul_id" v-model="form.title" @input="autoTranslateTitle" type="text" class="w-full border border-slate-300 p-3 rounded-xl focus:ring-2 focus:ring-slate-900 outline-none font-bold text-black bg-slate-50 focus:bg-white transition-colors" required placeholder="Masukkan judul artikel (ID)">
              </div>

              <div class="relative">
                <label for="judul_en" class="block text-xs font-bold text-black uppercase tracking-widest mb-2">Judul Artikel (EN)</label>
                <input id="judul_en" name="judul_en" v-model="form.title_en" type="text" class="w-full border border-slate-300 p-3 rounded-xl focus:ring-2 focus:ring-slate-900 outline-none font-bold text-black bg-slate-50 focus:bg-white transition-colors" placeholder="Terjemahan otomatis muncul di sini...">
                <span v-if="isTranslatingTitle" class="absolute right-4 top-[42px] text-xs font-bold text-slate-400 animate-pulse">Translating...</span>
              </div>

              <div>
                <label for="slug" class="block text-xs font-bold text-black uppercase tracking-widest mb-2">Slug</label>
                <input id="slug" name="slug" v-model="form.slug" type="text" disabled class="w-full border border-slate-300 p-3 rounded-xl outline-none font-bold text-slate-500 bg-slate-100 cursor-not-allowed" placeholder="otomatis-mengikuti-judul">
              </div>

              <div>
                <label for="kategori" class="block text-xs font-bold text-black uppercase tracking-widest mb-2">Kategori <span class="text-red-500">*</span></label>
                <select id="kategori" name="kategori" v-model="form.category_id" @change="handleCategoryChange" class="w-full border border-slate-300 p-3 rounded-xl focus:ring-2 focus:ring-slate-900 outline-none font-bold text-black bg-white" required>
                  <option value="" disabled>Pilih Kategori</option>
                  <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                  <option value="redirect_create" class="font-bold text-blue-600 bg-blue-50">Tidak ada kategori yang sesuai?</option>
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
                </div>
                <EditorContent :editor="editorId" class="prose  text-black prose-slate max-w-none border border-slate-300 rounded-b-xl p-4 min-h-[300px] bg-white focus:outline-none" />
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
                </div>
                <EditorContent :editor="editorEn" class="prose prose-slate max-w-none border border-slate-300 text-black rounded-b-xl p-4 min-h-[300px] bg-white focus:outline-none" />
              </div>
              
              <div>
                <label for="meta_title" class="block text-xs font-bold text-black uppercase tracking-widest mb-2">Meta Title (SEO)</label>
                <input id="meta_title" name="meta_title" v-model="form.meta_title" type="text" class="w-full border border-slate-300 p-3 rounded-xl focus:ring-2 focus:ring-slate-900 outline-none font-bold text-black bg-white" placeholder="Judul untuk SEO">
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



            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
              <button type="button" @click="clearDraftAndBack" class="bg-slate-100 text-slate-600 px-8 py-3 rounded-xl text-sm font-bold hover:bg-slate-200 transition-colors text-center cursor-pointer">Batal & Hapus Draft</button>
              <button type="submit" :disabled="isSaving" class="bg-slate-900 text-white px-8 py-3 rounded-xl text-sm font-bold hover:bg-[#ea4435] transition-colors cursor-pointer disabled:opacity-50 flex items-center justify-center min-w-[160px]">
                {{ isSaving ? 'Menyiapkan Pratinjau...' : 'Preview Artikel' }}
              </button>
            </div>
          </form>
        </div>
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount, watch } from 'vue';
import { useRouter } from 'vue-router';
import Api from '@/api';

// Import komponen Sidebar dan Navbar
import Sidebar from '@/components/admin/Sidebar.vue';
import Navbar from '@/components/admin/Navbar.vue';

// TIPTAP IMPORTS
import { useEditor, EditorContent } from '@tiptap/vue-3';
import StarterKit from '@tiptap/starter-kit';
import Link from '@tiptap/extension-link';

const router = useRouter();

// UI State
const isSidebarOpen = ref(false);
const isSaving = ref(false);
const categories = ref([]);
const previewImage = ref(null);
const hasDraft = ref(false);

// State untuk dikirim ke Navbar
const user = ref({ name: 'Admin', email: '' });
const breadcrumbsData = ref([
    { label: 'Artikel', link: '/admin/articles' },
    { label: 'Tambah Artikel', link: null }
]);

const form = ref({
    title: '',
    title_en: '', 
    slug: '',
    category_id: '',
    meta_title: '',
    meta_description: '',
    meta_keywords: '',
    published: 'draft',
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
// LOGIC LOCALSTORAGE DRAFT
// ==========================================
const saveDraft = () => {
    const draftData = {
        title: form.value.title,
        title_en: form.value.title_en,
        slug: form.value.slug,
        category_id: form.value.category_id !== 'redirect_create' ? form.value.category_id : '',
        meta_title: form.value.meta_title,
        meta_description: form.value.meta_description,
        meta_keywords: form.value.meta_keywords,
        published: form.value.published,
        contentId: editorId.value?.getHTML() || '',
        contentEn: editorEn.value?.getHTML() || ''
    };
    localStorage.setItem('article_draft', JSON.stringify(draftData));
    hasDraft.value = true;
};

const loadDraft = () => {
    const draftStr = localStorage.getItem('article_draft');
    if (draftStr) {
        try {
            const draft = JSON.parse(draftStr);
            form.value.title = draft.title || '';
            form.value.title_en = draft.title_en || '';
            form.value.slug = draft.slug || '';
            form.value.category_id = draft.category_id || '';
            form.value.meta_title = draft.meta_title || '';
            form.value.meta_description = draft.meta_description || '';
            form.value.meta_keywords = draft.meta_keywords || '';
            if (form.value.meta_keywords) {
                metaKeywordsArray.value = form.value.meta_keywords.split(',').map(s => s.trim()).filter(s => s);
            } else {
                metaKeywordsArray.value = [];
            }
            form.value.published = draft.published || 'draft';

            if (draft.contentId && editorId.value) {
                editorId.value.commands.setContent(draft.contentId);
            }
            if (draft.contentEn && editorEn.value) {
                editorEn.value.commands.setContent(draft.contentEn);
            }
            hasDraft.value = true;
        } catch (err) {
            console.error("Gagal memuat draft", err);
        }
    }
};

const clearDraftAndBack = () => {
    localStorage.removeItem('article_draft');
    router.push('/admin/articles');
};

// ==========================================
// INISIALISASI TIPTAP EDITOR (ID & EN)
// ==========================================
const editorId = useEditor({
  content: '',
  extensions: [
    StarterKit.configure({
      link: {
        openOnClick: false,
        HTMLAttributes: { class: 'text-blue-600 underline' }
      }
    })
  ],
  onUpdate: () => {
    saveDraft();
  }
});

const editorEn = useEditor({
  content: '',
  extensions: [
    StarterKit.configure({
      link: {
        openOnClick: false,
        HTMLAttributes: { class: 'text-blue-600 underline' }
      }
    })
  ],
  editable: true, 
  onUpdate: () => {
    saveDraft();
  }
});

watch(() => form.value, () => {
  saveDraft();
}, { deep: true });

onBeforeUnmount(() => {
  editorId.value?.destroy();
  editorEn.value?.destroy();
});

// ==========================================
// FUNGSI RICH TEXT (TOC & LINK)
// ==========================================
const generateHeadingId = (text) => {
  return text.toLowerCase().replace(/\s+/g, '-').replace(/[^\w\-]/g, '').replace(/\-+/g, '-').replace(/^-|-$/g, '');
};

const generateTOC = (editorInstance) => {
  if (!editorInstance) return;
  const html = editorInstance.getHTML();
  
  const doc = new DOMParser().parseFromString(html, 'text/html');
  const headings = Array.from(doc.querySelectorAll('h2, h3, h4'));
  
  if (headings.length === 0) {
    alert("Silakan buat setidaknya satu Heading (H2 / H3 / H4) di dalam editor sebelum membuat Daftar Isi.");
    return;
  }

  const headingsWithIds = [];
  const usedIds = new Set();

  headings.forEach((heading) => {
    let text = heading.textContent || heading.innerText;
    let id = generateHeadingId(text);
    
    let uniqueId = id;
    let counter = 1;
    while (usedIds.has(uniqueId)) {
      uniqueId = `${id}-${counter}`;
      counter++;
    }
    id = uniqueId;
    usedIds.add(id);
    
    heading.id = id;
    
    headingsWithIds.push({ text, id, level: heading.tagName.toLowerCase() });
  });

  let tocHtml = `<div class="bg-slate-50 p-6 rounded-xl border border-slate-200 mb-6">
    <p class="font-black text-slate-900 uppercase text-xs mb-4 tracking-widest">Daftar Isi</p>
    <ul class="space-y-2 m-0 p-0 list-none">`;

  headingsWithIds.forEach(({ text, id, level }) => {
    const padding = (level === 'h3' || level === 'h4') ? 'ml-6' : 'ml-0';
    tocHtml += `<li class="${padding}"><a href="#${id}" class="text-blue-600 hover:underline text-sm font-bold no-underline">${text}</a></li>`;
  });

  tocHtml += `</ul></div><p></p>`;
  
  const bodyContent = Array.from(doc.body.childNodes)
    .map(node => {
      if (node.nodeType === 3) return node.textContent; 
      if (node.nodeType === 1) return node.outerHTML; 
      return '';
    })
    .join('');

  const finalHtml = tocHtml + bodyContent;
  editorInstance.commands.setContent(finalHtml);
  saveDraft();
};

const setLink = (editorInstance) => {
  if (!editorInstance) return;
  const previousUrl = editorInstance.getAttributes('link').href;
  const url = window.prompt('Masukkan URL link:', previousUrl);

  if (url === null) return;
  if (url === '') {
    editorInstance.chain().focus().extendMarkRange('link').unsetLink().run();
  } else {
    editorInstance.chain().focus().extendMarkRange('link').setLink({ href: url }).run();
  }
  saveDraft();
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
    
    user.value = JSON.parse(savedUser);
    
    // Default selalu draft sebelum masuk ke preview
    form.value.published = 'draft';
    
    await fetchCategories();
    loadDraft();
});

const fetchCategories = async () => {
    try {
        const response = await Api.get('/admin/categories/list');
        categories.value = response.data.data;
    } catch (err) { console.error("Error fetching categories:", err); }
};

const handleCategoryChange = () => {
    if (form.value.category_id === 'redirect_create') {
        saveDraft(); 
        router.push('/admin/categories/tambah'); 
        form.value.category_id = ''; 
    }
};

const generateSlug = (text) => {
    return text.toString().toLowerCase().replace(/\s+/g, '-').replace(/[^\w\-]+/g, '').replace(/\-\-+/g, '-').replace(/^-+/, '').replace(/-+$/, '');            
};

const handleTitleInput = () => {
    form.value.slug = generateSlug(form.value.title);
};

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
            saveDraft();
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
            previewImage.value = null;
            return; 
        }

        form.value.image = file;
        previewImage.value = URL.createObjectURL(file);
    }
};

const storeArticle = async () => {
    isSaving.value = true;
    try {
        const token = sessionStorage.getItem('token');
        const formData = new FormData();
        
        formData.append('title', form.value.title);
        formData.append('title_en', form.value.title_en); 
        formData.append('slug', form.value.slug); 
        formData.append('category_id', form.value.category_id);
        
        formData.append('content', editorId.value.getHTML());
        formData.append('content_en', editorEn.value.getHTML()); 
        
        formData.append('meta_title', form.value.meta_title || form.value.title); 
        formData.append('meta_description', form.value.meta_description || '');
        formData.append('meta_keywords', form.value.meta_keywords || '');
        formData.append('published', 'draft');
        
        if (form.value.image) formData.append('image', form.value.image);

        // 1. Simpan ke variabel response agar datanya bisa diambil
        const response = await Api.post('/admin/articles', formData, {
            headers: { 'Content-Type': 'multipart/form-data' }
        });

        localStorage.removeItem('article_draft');
        hasDraft.value = false;

        // 2. Ambil slug dari backend agar akurat (dengan penanganan jika response.data berupa string)
        let responseData = response.data;
        if (typeof responseData === 'string') {
            try {
                const jsonStart = responseData.indexOf('{');
                const jsonEnd = responseData.lastIndexOf('}');
                if (jsonStart !== -1 && jsonEnd !== -1) {
                    responseData = JSON.parse(responseData.substring(jsonStart, jsonEnd + 1));
                }
            } catch (e) {
                console.error("Gagal parse response JSON:", e);
            }
        }

        const newSlug = responseData?.data?.slug;
        if (!newSlug) {
            throw new Error("Slug tidak ditemukan di response. Data: " + JSON.stringify(responseData));
        }

        // 3. Redirect ke Halaman Preview menggunakan slug baru
        router.push(`/admin/articles/preview/${newSlug}`);
        
    } catch (err) {
        console.error("Gagal menyimpan:", err);
        if (err.response) {
            console.error("Response data:", err.response.data);
            if (err.response.status === 401) {
                alert("Sesi login sudah habis. Silakan login ulang.");
                handleLogout();
                return;
            }
        }
        alert('Gagal menyimpan artikel. Pastikan semua field wajib terisi atau periksa console untuk detail error.');
    } finally { isSaving.value = false; }
};

const handleLogout = () => {
    sessionStorage.removeItem('user');
    sessionStorage.removeItem('token');
    router.push('/view/login');
};
</script>

<style>
.ProseMirror {
  outline: none;
}
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
button, a, .cursor-pointer { cursor: pointer !important; }
</style>