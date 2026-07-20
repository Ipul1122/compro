# PT Cakrawala Parama Internasional - Web Application

Aplikasi Web Official Company Profile (Compro) & Content Management System (CMS) untuk **PT Cakrawala Parama Internasional**.

Proyek ini dibangun menggunakan arsitektur Decoupled / Monorepo yang memisahkan **Backend RESTful API** (Laravel) dan **Frontend SPA** (Vue 3 + Vite).

---

## 🛠️ Teknologi yang Digunakan

### **Frontend**
- **Framework:** [Vue 3](https://vuejs.org/) (Composition API)
- **Build Tool:** [Vite](https://vitejs.dev/)
- **Styling:** [Tailwind CSS v4](https://tailwindcss.com/)
- **State Management:** [Pinia](https://pinia.vuejs.org/)
- **Routing:** [Vue Router](https://router.vuejs.org/)
- **Internationalization (i18n):** [Vue I18n](https://vue-i18n.intlify.dev/)
- **Rich Text Editor:** [Tiptap](https://tiptap.dev/)
- **Charts & Visualisasi:** [Chart.js](https://www.chartjs.org/) + `vue-chartjs`
- **Slider / Carousel:** [Swiper](https://swiperjs.com/)
- **HTTP Client:** [Axios](https://axios-http.com/)

### **Backend**
- **Framework:** [Laravel 12](https://laravel.com/)
- **Language:** PHP ^8.2
- **Authentication:** Laravel Sanctum (API Tokens & Cookie Authentication)
- **Database:** MySQL / MariaDB

---

## 📁 Struktur Direktori Project

```text
compro/
├── backend/                        # Laravel 12 API Project (Port 8000)
│   ├── app/                        # Controllers, Models, Middleware
│   ├── database/                   # Migrations, Seeders, Factories
│   ├── routes/                     # API & Web routes
│   └── .env                        # Konfigurasi environment backend
├── frontend/                       # Vue 3 SPA Project (Port 5173)
│   ├── src/                        # Components, Views, Stores, Assets, i18n
│   ├── public/                     # Static assets
│   └── .env.development            # Konfigurasi environment frontend
├── SETUP_LOKAL.md                  # Panduan detail instalasi & setup environment lokal
├── panduan_deployment_rumahweb.md # Panduan deployment ke server hosting (RumahWeb)
└── README.md                       # Dokumentasi utama proyek
```

---

## 🚀 Quick Start (Pengembangan Lokal)

### **Prasyarat Sistem**
- **Node.js** >= 20.19.0 atau >= 22.12.0
- **PHP** >= 8.2 (via XAMPP, Laragon, atau native PHP)
- **Composer** (Package Manager PHP)
- **MySQL Database Server**

---

### **1. Setup Backend (Laravel API)**

```bash
# Masuk ke folder backend
cd backend

# Install dependencies PHP
composer install

# Buat file .env dari .env.example (jika belum ada)
cp .env.example .env

# Generate Application Key
php artisan key:generate

# Konfigurasi database pada file .env (DB_DATABASE=compro, DB_USERNAME, DB_PASSWORD)

# Jalankan migrasi tabel & seeder
php artisan migrate --seed

# Jalankan server lokal Laravel
php artisan serve
# Server akan berjalan di http://localhost:8000
```

---

### **2. Setup Frontend (Vue 3 SPA)**

```bash
# Masuk ke folder frontend
cd frontend

# Install dependencies Node.js
npm install

# Buat file .env.development jika diperlukan (VITE_API_BASE_URL=http://localhost:8000/api)

# Jalankan development server
npm run dev
# Aplikasi akan berjalan di http://localhost:5173
```

---

## 🔑 Fitur Utama Aplikasi

- 🌐 **Landing Page Publik Multi-Bahasa:** Tampilan company profile yang responsif dan interaktif dengan dukungan multi-bahasa.
- 🔐 **Authentication & Authorization:** Sistem Login untuk Admin dan Direktur berbasis role.
- 📝 **CMS Management:** Editor artikel dan konten dinamis menggunakan Rich Text Editor Tiptap.
- 📊 **Dashboard Analytics:** Grafik dan visualisasi data performa/konten perusahaan.
- 🖼️ **Galeri & Media:** Manajemen gambar, video, dan berkas perusahaan.

---

## 📖 Dokumen Panduan Lainnya

- 🛠️ **Setup Lokal Lengkap:** Silakan baca [SETUP_LOKAL.md](file:///c:/Users/PT%20CPI/compro/SETUP_LOKAL.md) untuk langkah-langkah detail pengembangan lokal.
- 🌐 **Panduan Deployment:** Silakan baca [panduan_deployment_rumahweb.md](file:///c:/Users/PT%20CPI/compro/panduan_deployment_rumahweb.md) untuk panduan rilis ke server cPanel / RumahWeb.

---

© **PT Cakrawala Parama Internasional**. All rights reserved.
