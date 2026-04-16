import { createRouter, createWebHistory } from "vue-router";
import HomeView from "@/views/HomeView.vue";
import IdLanguage from "@/components/IdLanguage.vue";
import NotFoundView from '../views/NotFoundView.vue'
import CategoryIndex from '../views/admin/category/IndexView.vue'
import CategoryEdit from '../views/admin/category/EditView.vue'
import ArticleIndex from '../views/admin/articles/IndexView.vue'
import ArticleEdit from '../views/admin/articles/EditView.vue'

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
  {
    path: "/id",
    name: "indonesia",
    component: IdLanguage,
  },
  {
    path: "/view/login",
    name: "login",
    component: () => import("@/views/LoginView.vue"),
    meta: { hideNavbar: true },
  },
  {
    path: "/view/dashboard",
    name: "dashboard",
    component: () => import("@/views/DashboardView.vue"),
    // This 'meta' tag helps us tell App.vue to hide the Navbar
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
    // index articles
    path: '/admin/articles',
    name: 'admin.articles.index',
    component: ArticleIndex 
  },
  {
    // edir articles
    path: '/admin/articles/edit/:id',
    name: 'admin.articles.edit',
    component: ArticleEdit
  },
];

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
});

export default router;
