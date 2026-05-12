import { createRouter, createWebHistory } from "vue-router";

// Kita akan menggunakan Dynamic Import () => import('...') di dalam routes agar loading website sangat cepat.

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
      lang: 'id',
      description: 'Selamat Datang Di PT Cakrawal Parama Internasional'
    }
  },

  // Rute Bahasa Inggris - SEO Friendly
  {
    path: '/en/home',
    name: 'home-en',
    component: () => import('../views/HomeView.vue'),
    meta: { 
      title: 'Home - PT Cakrawala Parama Internasional',
      lang: 'en',
      description: 'Welcome at PT Cakrawala Parama Internasional'
    }
  },
  {
      path: '/:pathMatch(.*)*', 
      name: 'NotFound',
      component: () => import('../views/NotFoundView.vue')
  },
  {
    // Rute About Us (Bahasa Indonesia)
    path: '/id/tentang-kami',
    name: 'about-id',
    component: () => import('../views/pages/AboutView.vue'), 
    meta: { 
      title: 'Tentang Kami - PT Cakrawala Parama Internasional',
      lang: 'id',
      description: 'Mengenal lebih dekat tim profesional kami di bidang pengembangan dan manajemen sumber daya manusia.'
    }
  },

  // Rute About Us (Bahasa Inggris)
  {
    path: '/en/about',
    name: 'about-en',
    component: () => import('../views/pages/AboutView.vue'), 
    meta: { 
      title: 'About Us - PT Cakrawala Parama Internasional',
      lang: 'en',
      description: "Get to know our professional team in the field of human resource development and management"
    }
  },
  // Rute About Us (Bahasa Inggris)
  {
    path: '/en/about',
    name: 'about-en',
    component: () => import('../views/pages/AboutView.vue'), 
    meta: { 
      title: 'About Us - PT Cakrawala Parama Internasional',
      lang: 'en',
      description: "Get to know our professional team in the field of human resource development and management"
    }
  },

  // ==========================================
  // RUTE OUR TEAMS / TIM KAMI
  // ==========================================
  {
    path: '/id/tim-kami',
    name: 'our-teams-id',
    component: () => import('../views/pages/OurTeamsView.vue'), 
    meta: { 
      title: 'Tim Kami - PT Cakrawala Parama Internasional',
      lang: 'id',
      description: 'Kenali tim profesional di balik PT Cakrawala Parama Internasional'
    }
  },
  {
    path: '/en/our-teams',
    name: 'our-teams-en',
    component: () => import('../views/pages/OurTeamsView.vue'), 
    meta: { 
      title: 'Our Teams - PT Cakrawala Parama Internasional',
      lang: 'en',
      description: 'Meet the professional team behind PT Cakrawala Parama Internasional'
    }
  },
  {
    // Rute Our Projects (Bahasa Indonesia)
    path: '/id/proyek-kami',
    name: 'project-id',
    component: () => import('../views/pages/ProjectView.vue'), 
    meta: { 
      title: 'Proyek Kami - PT Cakrawala Parama Internasional',
      lang: 'id',
      description: 'Berikut hasil project yang telah kami lakukan'
    }
  },

  // Rute Our Projects (Bahasa Inggris)
  {
    path: '/en/projects',
    name: 'project-en',
    component: () => import('../views/pages/ProjectView.vue'), 
    meta: { 
      title: 'Our Projects - PT Cakrawala Parama Internasional',
      lang: 'en',
      description: 'The following are the results of the projects we have carried out'
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
      lang: 'id',
      description: 'Layanan yang kami sediakan untuk membantu bisnis anda'
    }
  },
  {
    path: '/en/services',
    name: 'service-en',
    component: () => import('../views/pages/ServiceView.vue'), 
    meta: { 
      title: 'Our Services - PT Cakrawala Parama Internasional',
      lang: 'en',
      description: 'Services we provide to help your business'
    }
  },
  // INTERIOR DESIGN
  {
    path: "/id/interior-design",
    name: "interior-design-id",
    component: () => import("@/views/pages/service/InteriorDesign.vue"),
    meta: { 
      title: 'Interior Design - PT Cakrawala Parama Internasional',
      lang: 'id',
      description: 'Layanan Interior Design kami'
    },
  },
  {
    path: "/en/interior-design",
    name: "interior-design-en",
    component: () => import("@/views/pages/service/InteriorDesign.vue"),
    meta: { 
      title: 'Interior Design - PT Cakrawala Parama Internasional',
      lang: 'en',
      description: 'Our Interior Design Services'
    },
  },

  // ASSESSMENT
  {
    path: "/id/assessment",
    name: "assessment-id",
    component: () => import("@/views/pages/service/AssestmentView.vue"),
    meta: { 
      title: 'Assessment - PT Cakrawala Parama Internasional',
      lang: 'id',
      description: 'Layanan Assessment kami'
    },
  },
  {
    path: "/en/assessment",
    name: "assessment-en",
    component: () => import("@/views/pages/service/AssestmentView.vue"),
    meta: { 
      title: 'Assessment - PT Cakrawala Parama Internasional',
      lang: 'en',
      description: 'Our Assessment Services'
    },
  },
  
  // EVENT ORGANIZER
  {
    path: "/id/event-organizer",
    name: "event-organizer-id",
    component: () => import("@/views/pages/service/EventOrganizerView.vue"),
    meta: { 
      title: 'Event Organizer - PT Cakrawala Parama Internasional',
      lang: 'id',
      description: 'Layanan Event Organizer kami'
    },
  },
  {
    path: "/en/event-organizer",
    name: "event-organizer-en",
    component: () => import("@/views/pages/service/EventOrganizerView.vue"),
    meta: { 
      title: 'Event Organizer - PT Cakrawala Parama Internasional',
      lang: 'en',
      description: 'Our Event Organizer Services'
    },
  },

  // LEARNING MANAGEMENT SYSTEM
  {
    path: "/id/learning-management-system",
    name: "lms-id",
    component: () => import("@/views/pages/service/LmsView.vue"),
    meta: {
      title: 'Learning Management System - PT Cakrawala Parama Internasional',
      lang: 'id',
      description: 'Platform e-learning terintegrasi untuk pengembangan SDM organisasi Anda'
    },
  },
  {
    path: "/en/learning-management-system",
    name: "lms-en",
    component: () => import("@/views/pages/service/LmsView.vue"),
    meta: {
      title: 'Learning Management System - PT Cakrawala Parama Internasional',
      lang: 'en',
      description: 'Integrated e-learning platform for your organizational HR development'
    },
  },

  // EVENT ORGANIZER
  {
    path: "/id/event-organizer",
    name: "event-organizer-id",
    component: () => import("@/views/pages/service/EventOrganizerView.vue"),
    meta: { 
      title: 'Event Organizer - PT Cakrawala Parama Internasional',
      lang: 'id',
      description: 'Layanan Event Organizer kami'
    },
  },
  {
    path: "/en/event-organizer",
    name: "event-organizer-en",
    component: () => import("@/views/pages/service/EventOrganizerView.vue"),
    meta: { 
      title: 'Event Organizer - PT Cakrawala Parama Internasional',
      lang: 'en',
      description: 'Our Event Organizer Services'
    },
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
    path: '/articles',
    redirect: '/id/artikel'
  },
  {
    path: '/artikel',
    redirect: '/id/artikel'
  },
  {
    path: '/id/artikel',
    name: 'articles-id',
    component: () => import('../views/articles/IndexView.vue'),
    meta: { 
      title: 'Artikel Terkini - PT Cakrawala Parama Internasional',
      lang: 'id',
      description: 'Lihat artikel terbaru kami'
    }
  },
  {
    path: '/en/articles',
    name: 'articles-en',
    component: () => import('../views/articles/IndexView.vue'),
    meta: { 
      title: 'Latest Articles - PT Cakrawala Parama Internasional',
      lang: 'en',
      description: 'Check out our latest articles'
    }
  },
  {
    path: '/articles/:slug', 
    name: 'articles.detail',
    component: () => import('../views/articles/DetailView.vue')
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
      lang: 'id',
      description: 'Momen hasil project, event dan layanan yang telah kami dokumentasi'
    }
  },
  {
    path: '/en/gallery',
    name: 'gallery-en',
    component: () => import('../views/galerry/IndexView.vue'),
    meta: { 
      title: 'Activity Gallery - PT Cakrawala Parama Internasional',
      lang: 'en',
      description:'Moments of project results, events and services that we have documented'
    }
  },

  {
    path: '/id/kontak',
    name: 'contact-id',
    component: () => import('../views/pages/ContactView.vue'),
    meta: { 
      title: 'Hubungi Kami - PT Cakrawala Parama Internasional',
      lang: 'id',
      description: 'butuh bantuan? kami siap'
    }
  },
  {
    path: '/en/contact',
    name: 'contact-en',
    component: () => import('../views/pages/ContactView.vue'),
    meta: { 
      title: 'Contact Us - PT Cakrawala Parama Internasional',
      lang: 'en',
      description: 'Need some help? We Ready'
    }
  },
  {
    path: '/privacy-policy',
    name: 'privacy-policy',
    component: () => import('../views/pages/PrivacyPolicyView.vue')
  },
  {
    path: '/terms-of-service',
    name: 'terms-of-service',
    component: () => import('../views/pages/TermsOfServiceView.vue')
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
    path: "/direktur/dashboard", 
    name: "direktur.dashboard",
    component: () => import("@/views/direktur/DashboardDirekturView.vue"), 
    meta: { hideNavbar: true },
  },

  // --------------------------
  // DIREKTUR ROUTES (file terpisah dari Admin)
  // --------------------------
  {
    path: '/direktur/categories',
    name: 'direktur.categories.index',
    component: () => import('../views/direktur/category/IndexView.vue'),
    meta: { requiresAuth: true }
  },
  {
    path: '/direktur/categories/tambah',
    name: 'direktur.categories.tambah',
    component: () => import('../views/direktur/category/TambahView.vue'),
    meta: { requiresAuth: true }
  },
  {
    path: '/direktur/categories/edit/:id',
    name: 'direktur.categories.edit',
    component: () => import('../views/direktur/category/EditView.vue'),
    meta: { requiresAuth: true }
  },
  {
    path: '/direktur/karyawan',
    name: 'direktur.karyawan.index',
    component: () => import('../views/direktur/employee/IndexView.vue'),
    meta: { requiresAuth: true }
  },
  {
    path: '/direktur/karyawan/edit/:id',
    name: 'direktur.karyawan.edit',
    component: () => import('../views/direktur/employee/EditView.vue'),
    meta: { requiresAuth: true }
  },
  {
    path: '/direktur/articles',
    name: 'direktur.articles.index',
    component: () => import('../views/direktur/articles/IndexView.vue'),
    meta: { requiresAuth: true }
  },
  {
    path: '/direktur/articles/create',
    name: 'direktur.articles.create',
    component: () => import('../views/direktur/articles/TambahView.vue'),
    meta: { requiresAuth: true }
  },
  {
    path: '/direktur/articles/edit/:id',
    name: 'direktur.articles.edit',
    component: () => import('../views/direktur/articles/EditView.vue'),
    meta: { requiresAuth: true }
  },
  {
    path: '/direktur/gallery',
    name: 'direktur.gallery.index',
    component: () => import('../views/direktur/gallery/IndexView.vue'),
    meta: { requiresAuth: true }
  },
  {
    path: '/direktur/gallery/tambah',
    name: 'direktur.gallery.tambah',
    component: () => import('../views/direktur/gallery/TambahView.vue'),
    meta: { requiresAuth: true }
  },
  {
    path: '/direktur/gallery/edit/:id',
    name: 'direktur.gallery.edit',
    component: () => import('../views/direktur/gallery/EditView.vue'),
    meta: { requiresAuth: true }
  },

  // --------------------------
  // ADMIN ROUTES
  // --------------------------
  {
    path: '/admin/categories',
    name: 'admin.categories.index',
    component: () => import('../views/admin/category/IndexView.vue'),
    meta: { requiresAuth: true }
  },
  {
    path: '/admin/karyawan',
    name: 'admin.karyawan.index',
    component: () => import('../views/admin/employee/IndexView.vue'),
    meta: { requiresAuth: true }
  },
  {
    path: '/admin/karyawan/edit/:id',
    name: 'admin.karyawan.edit',
    component: () => import('../views/admin/employee/EditView.vue'),
    meta: { requiresAuth: true }
  },
  {
    path: '/admin/categories/edit/:id',
    name: 'admin.categories.edit',
    component: () => import('../views/admin/category/EditView.vue')
  },
  {
    path: '/admin/categories/tambah',
    name: 'admin-categories-tambah',
    component: () => import('@/views/admin/category/TambahView.vue'),
  },
  {
    path: '/admin/articles',
    name: 'admin.articles.index',
    component: () => import('../views/admin/articles/IndexView.vue') 
  },
  {
    path: '/admin/articles/create',
    name: 'admin.articles.create',
    component: () => import('../views/admin/articles/TambahView.vue')
  },
  {
    path: '/admin/articles/edit/:id',
    name: 'admin.articles.edit',
    component: () => import('../views/admin/articles/EditView.vue')
  },
  {
    path: '/admin/gallery',
    name: 'admin.gallery.index',
    component: () => import('../views/admin/gallery/IndexView.vue'),
    meta: { requiresAuth: true }
  },
  {
    path: '/admin/gallery/tambah',
    name: 'admin.gallery.tambah',
    component: () => import('../views/admin/gallery/TambahView.vue'),
    meta: { requiresAuth: true }
  },
  {
    path: '/admin/gallery/edit/:id',
    name: 'admin.gallery.edit',
    component: () => import('../views/admin/gallery/EditView.vue'),
    meta: { requiresAuth: true }
  },
];

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
  scrollBehavior(to, from, savedPosition) {
    // TAMBAHKAN KODE INI: Cegah scroll ke atas jika sedang ganti bahasa
    if (window.isLanguageSwitching) {
      return false 
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

// Dinamis mengganti judul Tab Browser untuk SEOOOOOOO
router.beforeEach((to, from) => {
  document.title = to.meta.title || 'CPI Group';
  
  // Update Meta Description
  let metaDesc = document.querySelector('meta[name="description"]');
  if (metaDesc) {
    metaDesc.setAttribute('content', to.meta.description || 'Deskripsi default website');
  }

  const token = sessionStorage.getItem('token')
  const requiresAuth = to.meta.requiresAuth || to.path.startsWith('/admin') || to.path.startsWith('/direktur')
  if (requiresAuth && !token) {
    return { path: '/view/login' }
  }
});

// sync bahasa vue-i18n berdasarkan URL yang dituju
router.beforeEach((to) => {
  // Mengecek apakah URL mengandung /en/
  if (to.path.startsWith('/en/')) {
    sessionStorage.setItem('user-locale', 'en')
  } 
  // Mengecek apakah URL mengandung /id/
  else if (to.path.startsWith('/id/')) {
    sessionStorage.setItem('user-locale', 'id')
  }
  return true
})

export default router;