<template>
  <div class="flex min-h-screen bg-slate-50 relative overflow-x-hidden">
    <div v-if="isSidebarOpen" @click="isSidebarOpen = false" class="fixed inset-0 bg-slate-900/50 z-40 lg:hidden backdrop-blur-sm"></div>
    
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
                <label class="block text-xs font-bold text-black uppercase tracking-widest mb-2">Gambar Utama <span class="text-red-500">*</span></label>
                <div class="flex flex-col items-start gap-4">
                  <img v-if="previewImage" :src="previewImage" class="w-full max-w-2xl aspect-video rounded-xl object-cover border border-slate-200 shadow-sm" />
                  <input type="file" @change="handleFileChange" accept="image/*" class="text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-slate-900 file:text-white hover:file:bg-[#ea4435] cursor-pointer" :required="!previewImage">
                </div>
              </div>

              <div>
                <label class="block text-xs font-bold text-black uppercase tracking-widest mb-2">Judul Artikel (ID) <span class="text-red-500">*</span></label>
                <input v-model="form.title" @input="autoTranslateTitle" type="text" class="w-full border border-slate-300 p-3 rounded-xl focus:ring-2 focus:ring-slate-900 outline-none font-bold text-black bg-slate-50 focus:bg-white transition-colors" required placeholder="Masukkan judul artikel (ID)">
              </div>

              <div class="relative">
                <label class="block text-xs font-bold text-black uppercase tracking-widest mb-2">Judul Artikel (EN)</label>
                <input v-model="form.title_en" type="text" class="w-full border border-slate-300 p-3 rounded-xl focus:ring-2 focus:ring-slate-900 outline-none font-bold text-black bg-slate-50 focus:bg-white transition-colors" placeholder="Terjemahan otomatis muncul di sini...">
                <span v-if="isTranslatingTitle" class="absolute right-4 top-[42px] text-xs font-bold text-slate-400 animate-pulse">Translating...</span>
              </div>

              <div>
                <label class="block text-xs font-bold text-black uppercase tracking-widest mb-2">Slug</label>
                <input v-model="form.slug" type="text" disabled class="w-full border border-slate-300 p-3 rounded-xl outline-none font-bold text-slate-500 bg-slate-100 cursor-not-allowed" placeholder="otomatis-mengikuti-judul">
              </div>

              <div>
                <label class="block text-xs font-bold text-black uppercase tracking-widest mb-2">Kategori <span class="text-red-500">*</span></label>
                <select v-model="form.category_id" @change="handleCategoryChange" class="w-full border border-slate-300 p-3 rounded-xl focus:ring-2 focus:ring-slate-900 outline-none font-bold text-black bg-white" required>
                  <option value="" disabled>Pilih Kategori</option>
                  <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                  <option value="redirect_create" class="font-bold text-blue-600 bg-blue-50">Tidak ada kategori yang sesuai?</option>
                </select>
              </div>

              <div class="md:col-span-2 mt-4">
                <div class="flex justify-between items-end mb-2">
                  <label class="block text-xs font-bold text-black uppercase tracking-widest">Isi Konten (ID) <span class="text-red-500">*</span></label>
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
                <p v-if="isTranslatingContent" class="text-xs text-blue-500 font-bold mt-2 animate-pulse">Menyiapkan terjemahan teks otomatis ke EN...</p>
              </div>

              <div class="md:col-span-2 mt-4">
                <div class="flex justify-between items-end mb-2">
                  <label class="block text-xs font-bold text-black uppercase tracking-widest">Isi Konten (EN)</label>
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
                <label class="block text-xs font-bold text-black uppercase tracking-widest mb-2">Meta Title (SEO)</label>
                <input v-model="form.meta_title" type="text" class="w-full border border-slate-300 p-3 rounded-xl focus:ring-2 focus:ring-slate-900 outline-none font-bold text-black bg-white" placeholder="Judul untuk SEO">
              </div>

              <div>
                <label class="block text-xs font-bold text-black uppercase tracking-widest mb-2">Meta Keywords (SEO)</label>
                <input v-model="form.meta_keywords" type="text" class="w-full border border-slate-300 p-3 rounded-xl focus:ring-2 focus:ring-slate-900 outline-none font-bold text-black bg-white" placeholder="pisahkan, dengan, koma">
              </div>

              <div class="md:col-span-2">
                <label class="block text-xs font-bold text-black uppercase tracking-widest mb-2">Meta Description (SEO)</label>
                <textarea v-model="form.meta_description" rows="3" class="w-full border border-slate-300 p-3 rounded-xl focus:ring-2 focus:ring-slate-900 outline-none font-medium text-black" placeholder="Deskripsi singkat untuk SEO..."></textarea>
              </div>

              <div>
                <label class="block text-xs font-bold text-black uppercase tracking-widest mb-2">Status</label>
                <select v-model="form.published" class="w-full border border-slate-300 p-3 rounded-xl focus:ring-2 focus:ring-slate-900 outline-none font-bold text-black bg-white">
                  <option value="publish">Publish</option>
                  <option value="draft">Draft</option>
                </select>
              </div>

            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
              <button type="button" @click="clearDraftAndBack" class="bg-slate-100 text-slate-600 px-8 py-3 rounded-xl text-sm font-bold hover:bg-slate-200 transition-colors text-center cursor-pointer">Batal & Hapus Draft</button>
              <button type="submit" :disabled="isSaving" class="bg-slate-900 text-white px-8 py-3 rounded-xl text-sm font-bold hover:bg-[#ea4435] transition-colors cursor-pointer disabled:opacity-50 flex items-center justify-center min-w-[160px]">
                {{ isSaving ? 'Menyimpan...' : 'Simpan Artikel' }}
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
import axios from 'axios';
import Sidebar from '@/components/admin/Sidebar.vue';

// TIPTAP IMPORTS
import { useEditor, EditorContent } from '@tiptap/vue-3';
import StarterKit from '@tiptap/starter-kit';
import Link from '@tiptap/extension-link';

const router = useRouter();

const isSidebarOpen = ref(false);
const currentView = ref('articles');
const isSaving = ref(false);
const categories = ref([]);
const previewImage = ref(null);
const hasDraft = ref(false);

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
const isTranslatingContent = ref(false);
let translateTitleTimeout = null;
let translateContentTimeout = null;

// ==========================================
// LOGIC LOCALSTORAGE DRAFT
// ==========================================
const saveDraft = () => {
    // Kita tidak menyimpan form.image karena objek File tidak bisa di-stringify ke JSON
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
            form.value.published = draft.published || 'publish';

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
  onUpdate: ({ editor }) => {
    autoTranslateContent(editor.getHTML());
    saveDraft(); // Simpan draft otomatis saat editor diubah
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
    saveDraft(); // Simpan draft otomatis saat editor diubah
  }
});

// Pantau perubahan pada form untuk autosave
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
    const savedUser = localStorage.getItem('user');
    const token = localStorage.getItem('token');
    
    if (!savedUser || !token) {
        alert("Sesi kamu tidak valid. Silakan relogin.");
        handleLogout();
        return;
    }
    await fetchCategories();
    // Load draft dipanggil setelah fetch categories agar dropdown bind dengan benar
    loadDraft();
});

const fetchCategories = async () => {
    try {
        const token = localStorage.getItem('token');
        const response = await axios.get('http://localhost:8000/api/admin/categories/list', {
            headers: { Authorization: `Bearer ${token}` }
        });
        categories.value = response.data.data;
    } catch (err) { console.error("Error fetching categories:", err); }
};

// Handle opsi "Tidak ada kategori yang sesuai?" 
const handleCategoryChange = () => {
    if (form.value.category_id === 'redirect_create') {
        saveDraft(); // Pastikan draft terbaru tersimpan sebelum pindah rute
        router.push('/admin/categories/tambah'); // Sesuaikan ini dengan rute untuk membuat Kategori baru di project Anda.
        form.value.category_id = ''; // Reset opsi yang dipilih
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

const autoTranslateContent = (contentHtml) => {
    if (translateContentTimeout) clearTimeout(translateContentTimeout);

    const textOnly = contentHtml.replace(/<[^>]*>?/gm, '').trim();
    
    if (!textOnly) {
        editorEn.value?.commands.setContent('');
        isTranslatingContent.value = false;
        return;
    }

    isTranslatingContent.value = true;
    translateContentTimeout = setTimeout(async () => {
        try {
            const res = await fetch(`https://api.mymemory.translated.net/get?q=${encodeURIComponent(textOnly.slice(0, 500))}&langpair=id|en`);
            const data = await res.json();
            
            if (data && data.responseData) {
                editorEn.value?.commands.setContent(`<p>${data.responseData.translatedText}</p>`);
                saveDraft();
            }
        } catch (error) { console.error("Gagal terjemah konten:", error); } 
        finally { isTranslatingContent.value = false; }
    }, 1200);
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
        formData.append('title_en', form.value.title_en); 
        formData.append('slug', form.value.slug); 
        formData.append('category_id', form.value.category_id);
        
        formData.append('content', editorId.value.getHTML());
        formData.append('content_en', editorEn.value.getHTML()); 
        
        formData.append('meta_title', form.value.meta_title || form.value.title); 
        formData.append('meta_description', form.value.meta_description || '');
        formData.append('meta_keywords', form.value.meta_keywords || '');
        formData.append('published', form.value.published);
        
        if (form.value.image) formData.append('image', form.value.image);

        await axios.post('http://localhost:8000/api/admin/articles', formData, {
            headers: { Authorization: `Bearer ${token}`, 'Content-Type': 'multipart/form-data' }
        });

        // Sukses Simpan -> Hapus history draft dari LocalStorage
        localStorage.removeItem('article_draft');
        hasDraft.value = false;

        alert('Artikel berhasil ditambahkan!');
        router.push('/admin/articles');
    } catch (err) {
        if (err.response && err.response.status === 401) {
            alert("Sesi login sudah habis. Silakan login ulang.");
            handleLogout();
        } else { alert('Gagal menyimpan artikel. Pastikan semua field wajib terisi.'); }
    } finally { isSaving.value = false; }
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