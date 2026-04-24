import { createRouter, createWebHistory } from "vue-router";
import HomeView from "@/views/HomeView.vue";
// import IdLanguage from "@/components/IdLanguage.vue";
import NotFoundView from '../views/NotFoundView.vue'
import CategoryIndex from '../views/admin/category/IndexView.vue'
import CategoryEdit from '../views/admin/category/EditView.vue'
import ArticleIndex from '../views/admin/articles/IndexView.vue'
import ArticleCreate from '../views/admin/articles/TambahView.vue'
import ArticleEdit from '../views/admin/articles/EditView.vue'
import ArticlePublicIndex from '../views/articles/IndexView.vue'
import ArticlePublicDetail from '../views/articles/DetailView.vue'
import GalleryIndexView from '../views/galerry/IndexView.vue'
// GALLERY
import GalleryIndex from '../views/admin/gallery/IndexView.vue'
import GalleryTambah from '../views/admin/gallery/TambahView.vue'
import GalleryEdit from '../views/admin/gallery/EditView.vue'

const routes = [
  {
    path: '/',
    redirect: '/id/beranda'
  },
  
  // Rute Bahasa Indonesia - SEO Friendly
  {
    path: '/id/beranda',
    name: 'home-id',
    component: () => import('../views/HomeView.vue'),
    meta: { 
      title: 'Beranda - PT Cakrawala Parama Internasional',
      lang: 'id'
    }
  },

  // Rute Bahasa Inggris - SEO Friendly
  {
    path: '/en/home',
    name: 'home-en',
    component: () => import('../views/HomeView.vue'),
    meta: { 
      title: 'Home - PT Cakrawala Parama Internasional',
      lang: 'en'
    }
  },
  {
      path: '/:pathMatch(.*)*', 
      name: 'NotFound',
      component: NotFoundView
  },
  {
    // Rute About Us (Bahasa Indonesia)
    path: '/id/tentang-kami',
    name: 'about-id',
    component: () => import('../views/pages/AboutView.vue'), 
    meta: { 
      title: 'Tentang Kami - PT Cakrawala Parama Internasional',
      lang: 'id'
    }
  },

  // Rute About Us (Bahasa Inggris)
  {
    path: '/en/about',
    name: 'about-en',
    component: () => import('../views/pages/AboutView.vue'), 
    meta: { 
      title: 'About Us - PT Cakrawala Parama Internasional',
      lang: 'en'
    }
  },
  {
    // Rute Our Projects (Bahasa Indonesia)
    path: '/id/proyek-kami',
    name: 'project-id',
    component: () => import('../views/pages/ProjectView.vue'), 
    meta: { 
      title: 'Proyek Kami - PT Cakrawala Parama Internasional',
      lang: 'id'
    }
  },

  // Rute Our Projects (Bahasa Inggris)
  {
    path: '/en/projects',
    name: 'project-en',
    component: () => import('../views/pages/ProjectView.vue'), 
    meta: { 
      title: 'Our Projects - PT Cakrawala Parama Internasional',
      lang: 'en'
    }
  },
  // ==========================================
  // RUTE SERVICE / LAYANAN
  // ==========================================
  {
    path: '/id/layanan',
    name: 'service-id',
    component: () => import('../views/pages/ServiceView.vue'), 
    meta: { 
      title: 'Layanan Kami - PT Cakrawala Parama Internasional',
      lang: 'id'
    }
  },
  {
    path: '/en/services',
    name: 'service-en',
    component: () => import('../views/pages/ServiceView.vue'), 
    meta: { 
      title: 'Our Services - PT Cakrawala Parama Internasional',
      lang: 'en'
    }
  },
  {
    path: "/view/login",
    name: "login",
    component: () => import("@/views/LoginView.vue"),
    meta: { hideNavbar: true },
  },

  // ==========================================
  // RUTE ARTICLES / ARTIKEL
  // ==========================================
  {
    path: '/id/artikel',
    name: 'articles-id',
    component: () => import('../views/articles/IndexView.vue'),
    meta: { 
      title: 'Artikel Terkini - PT Cakrawala Parama Internasional',
      lang: 'id'
    }
  },
  {
    path: '/en/articles',
    name: 'articles-en',
    component: () => import('../views/articles/IndexView.vue'),
    meta: { 
      title: 'Latest Articles - PT Cakrawala Parama Internasional',
      lang: 'en'
    }
  },
  {
    path: '/articles/:slug', 
    name: 'articles.detail',
    component: ArticlePublicDetail
  },

 // ==========================================
  // RUTE GALLERY / GALERI
  // ==========================================
  {
    path: '/id/galeri',
    name: 'gallery-id',
    component: () => import('../views/galerry/IndexView.vue'),
    meta: { 
      title: 'Galeri Kegiatan - PT Cakrawala Parama Internasional',
      lang: 'id'
    }
  },
  {
    path: '/en/gallery',
    name: 'gallery-en',
    component: () => import('../views/galerry/IndexView.vue'),
    meta: { 
      title: 'Activity Gallery - PT Cakrawala Parama Internasional',
      lang: 'en'
    }
  },

  {
    path: '/id/kontak',
    name: 'contact-id',
    component: () => import('../views/pages/ContactView.vue'),
    meta: { 
      title: 'Hubungi Kami - PT Cakrawala Parama Internasional',
      lang: 'id'
    }
  },
  {
    path: '/en/contact',
    name: 'contact-en',
    component: () => import('../views/pages/ContactView.vue'),
    meta: { 
      title: 'Contact Us - PT Cakrawala Parama Internasional',
      lang: 'en'
    }
  },

  // --------------------------
  // ADMIN ROUTES
  // --------------------------
  {
    path: "/admin/dashboard", 
    name: "dashboard",
    component: () => import("@/views/admin/DashboardView.vue"), 
    meta: { hideNavbar: true },
  },

  {
    path: '/admin/categories',
    name: 'admin.categories.index',
    component: CategoryIndex,
    meta: { requiresAuth: true }
  },
  {
    // edit categories
    path: '/admin/categories/edit/:id',
    name: 'admin.categories.edit',
    component: CategoryEdit
  },
  {
    // tambah categories
    path: '/admin/categories/tambah',
    name: 'admin-categories-tambah',
    component: () => import('@/views/admin/category/TambahView.vue'),
  },
  {
    // index articles
    path: '/admin/articles',
    name: 'admin.articles.index',
    component: ArticleIndex 
  },
  {
    // create articles
    path: '/admin/articles/create',
    name: 'admin.articles.create',
    component: ArticleCreate
  },
  {
    // edit articles
    path: '/admin/articles/edit/:id',
    name: 'admin.articles.edit',
    component: ArticleEdit
  },

  {
    // index gallery
      path: '/admin/gallery',
      name: 'admin.gallery.index',
      component: GalleryIndex,
      meta: { requiresAuth: true }
    },
    {
      // create gallery 
      path: '/admin/gallery/tambah',
      name: 'admin.gallery.tambah',
      component: GalleryTambah,
      meta: { requiresAuth: true }
    },
    {
      // edit gallery
      path: '/admin/gallery/edit/:id',
      name: 'admin.gallery.edit',
      component: GalleryEdit,
      meta: { requiresAuth: true }
    },
];

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
  scrollBehavior(to, from, savedPosition) {
    // TAMBAHKAN KODE INI: Cegah scroll ke atas jika sedang ganti bahasa
    if (window.isLanguageSwitching) {
      return false // false berarti posisi layar tidak akan diubah sama sekali
    }

    // Jika user klik tombol Back/Forward di browser
    if (savedPosition) {
      return savedPosition
    }
    // Jika link mengandung hash (contoh: /#contact)
    if (to.hash) {
      return {
        el: to.hash,
        behavior: 'smooth',
      }
    }
    // DEFAULT: Selalu kembali ke titik paling atas
    return { top: 0, left: 0 }
  }
})

// Dinamis mengganti judul Tab Browser untuk SEO
router.beforeEach((to) => {
  document.title = to.meta.title || 'PT Cakrawala Parama Internasional'
  return true
})

// sync bahasa vue-i18n berdasarkan URL yang dituju
router.beforeEach((to) => {
  // Mengecek apakah URL mengandung /en/
  if (to.path.startsWith('/en/')) {
    localStorage.setItem('user-locale', 'en')
  } 
  // Mengecek apakah URL mengandung /id/
  else if (to.path.startsWith('/id/')) {
    localStorage.setItem('user-locale', 'id')
  }
  return true
})

export default router