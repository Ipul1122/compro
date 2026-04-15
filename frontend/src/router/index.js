import { createRouter, createWebHistory } from "vue-router";
import HomeView from "@/views/HomeView.vue";
import IdLanguage from "@/components/IdLanguage.vue";

const routes = [
  {
    path: "/",
    name: "home",
    component: HomeView,
  },
  {
    path: "/id",
    name: "indonesia",
    component: IdLanguage,
  },
  {
    path: "/login",
    name: "login",
    component: () => import("@/views/LoginView.vue"),
    // This 'meta' tag helps us tell App.vue to hide the Navbar
    meta: { hideNavbar: true },
  },
];

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
});

export default router;
