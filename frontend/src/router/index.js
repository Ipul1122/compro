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
    name: 'home',
    component: () => import('../views/HomeView.vue'),
    meta: { title: 'PT Cakrawala Parama Internasional - Home' }
  },
  {
      path: '/:pathMatch(.*)*', 
      name: 'NotFound',
      component: NotFoundView
  },
  {
    path: '/about',
    name: 'about',
    component: () => import('../views/pages/AboutView.vue'), 
    meta: { title: 'Tentang Kami - PT Cakrawala Parama Internasional' }
  },
  {
    path: '/project',
    name: 'project',
    component: () => import('../views/pages/ProjectView.vue'), 
    meta: { title: 'Proyek Kami - PT Cakrawala Parama Internasional' }
  },
  {
    path: '/service',
    name: 'service',
    component: () => import('../views/pages/ServiceView.vue'), 
    meta: { title: 'Layanan Kami - PT Cakrawala Parama Internasional' }
  },
  {
    path: "/view/login",
    name: "login",
    component: () => import("@/views/LoginView.vue"),
    meta: { hideNavbar: true },
  },

  {
    path: '/articles',
    name: 'articles',
    component: () => import('../views/articles/IndexView.vue'),
    meta: { title: 'Berita & Artikel Terkini' }
  },
  {
    path: '/articles/:slug', 
    name: 'articles.detail',
    component: ArticlePublicDetail
  },

  {
    path: '/galerry',
    name: 'galerry',
    component: () => import('../views/galerry/IndexView.vue'),
    meta: { title: 'Galeri Kegiatan' }
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
  // KONFIGURASI SCROLL: Ini yang mengatur pergerakan saat pindah halaman
  scrollBehavior(to, from, savedPosition) {
    // Jika user klik tombol Back/Forward di browser, kembali ke posisi sebelumnya
    if (savedPosition) {
      return savedPosition
    }
    // Jika link mengandung hash (contoh: /#contact), scroll ke elemen tersebut
    if (to.hash) {
      return {
        el: to.hash,
        behavior: 'smooth',
      }
    }
    // DEFAULT: Selalu kembali ke titik paling atas (X:0, Y:0) saat pindah halaman
    return { top: 0, left: 0 }
  }
})

// Dinamis mengganti judul Tab Browser untuk SEO
router.beforeEach((to, from, next) => {
  document.title = to.meta.title || 'PT Cakrawala Parama Internasional'
  next()
})

export default router