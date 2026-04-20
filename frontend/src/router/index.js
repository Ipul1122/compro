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
    path: "/",
    name: "home",
    component: HomeView,
  },
  {
      path: '/:pathMatch(.*)*', 
      name: 'NotFound',
      component: NotFoundView
  },
  // {
  //   path: "/id",
  //   name: "indonesia",
  //   component: IdLanguage,
  // },
  {
    path: "/view/login",
    name: "login",
    component: () => import("@/views/LoginView.vue"),
    meta: { hideNavbar: true },
  },

  {
    path: '/articles',
    name: 'articles.index',
    component: ArticlePublicIndex
  },

  {
    path: '/articles/:slug', 
    name: 'articles.detail',
    component: ArticlePublicDetail
  },

  {
    path: '/galeri',
    name: 'galeri.index',
    component: GalleryIndexView
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
    // create articles (Tambahkan ini sebelum route edit)
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
});

export default router;
