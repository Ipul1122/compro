# 🚀 Panduan Deploy ke Rumahweb (cPanel)

## Ringkasan Perubahan Yang Sudah Dilakukan

Berikut semua file yang sudah diupdate agar siap production:

### ✅ Frontend (Vue.js)

| File | Perubahan |
|------|-----------|
| `.env.production` | **BARU** — Environment variable `VITE_API_BASE_URL` untuk production |
| `.env.development` | **BARU** — Environment variable untuk development (localhost) |
| `public/.htaccess` | **BARU** — HTTPS redirect, SPA routing, security headers, caching, GZIP |
| `src/api/index.js` | Ganti hardcoded URL → `import.meta.env.VITE_API_BASE_URL` |
| `src/views/LoginView.vue` | Ganti hardcoded URL → env variable, fix path logo |
| `src/views/admin/DashboardView.vue` | Ganti 3 hardcoded URL → centralized `Api` instance |
| `src/views/admin/articles/IndexView.vue` | Ganti 3 hardcoded URL → centralized `Api` instance |
| `src/views/admin/articles/TambahView.vue` | Ganti 2 hardcoded URL → centralized `Api` instance |
| `src/views/admin/articles/EditView.vue` | Ganti 3 hardcoded URL → centralized `Api` instance |
| `src/views/section/HomeSection.vue` | Fix path `/src/img/` → `/img/` |
| `src/views/section/AboutSection.vue` | Fix path `/src/img/` → `/img/` |
| `src/components/Navbar.vue` | Fix path logo `/src/img/` → `/img/` |

### ✅ Backend (Laravel)

| File | Perubahan |
|------|-----------|
| `.env.production` | **BARU** — Template `.env` production lengkap |
| `public/.htaccess` | Force HTTPS, security headers, GZIP compression |
| `bootstrap/app.php` | Tambah `TrustProxies` middleware (wajib untuk shared hosting) |

---

## 📋 Langkah-Langkah Deploy

### STEP 1: Edit Domain/URL di File Environment

> [!IMPORTANT]
> Sebelum build, pastikan URL domain sudah benar!

#### A. Frontend — Edit `.env.production`

Buka file [.env.production](file:///c:/xampp/htdocs/company-profile/frontend/.env.production) dan ganti URL:

```diff
-VITE_API_BASE_URL=https://api.cakrawala-internasional.co.id/api
+VITE_API_BASE_URL=https://api.cakrawala-internasional.co.id/api
```

> [!NOTE]
> Jika backend dan frontend satu domain (misal backend di subfolder `/api`), sesuaikan URL-nya.

#### B. Backend — Edit `.env.production`

Buka file [.env.production](file:///c:/xampp/htdocs/company-profile/backend/.env.production) dan sesuaikan:

```env
APP_URL=https://api.cakrawala-internasional.co.id

CORS_ALLOWED_ORIGINS=https://cakrawala-internasional.co.id,https://www.cakrawala-internasional.co.id

DB_DATABASE=nama_database_di_cpanel
DB_USERNAME=username_database_di_cpanel
DB_PASSWORD=password_database_di_cpanel

SESSION_DOMAIN=.cakrawala-internasional.co.id

SANCTUM_STATEFUL_DOMAINS=cakrawala-internasional.co.id,www.cakrawala-internasional.co.id,api.cakrawala-internasional.co.id
```

---

### STEP 2: Build Frontend

Jalankan command ini di terminal folder `frontend`:

```bash
npm run build
```

Hasil build akan ada di folder `frontend/dist/`. File `.htaccess` dari `public/` otomatis masuk ke `dist/`.

---

### STEP 3: Persiapan Backend untuk Zip

> [!CAUTION]
> **JANGAN** zip folder `vendor/` dan `node_modules/`! Install ulang di server.

**Yang perlu diperhatikan:**
1. Rename `.env.production` → `.env` (atau buat `.env` baru di server)
2. Hapus folder/file yang tidak perlu:
   - `node_modules/`
   - `.git/`
   - `tests/`
   - `storage/logs/*.log` (kosongkan log)

---

### STEP 4: Upload ke cPanel Rumahweb

#### Skenario A: Frontend & Backend di Subdomain Terpisah (RECOMMENDED)

```
📁 public_html/                    ← Frontend (isi folder dist/)
├── index.html
├── .htaccess
├── assets/
├── img/
└── ...

📁 api.yourdomain.com/             ← Backend (isi seluruh folder backend/)
├── public/                        ← Document Root subdomain
│   ├── index.php
│   ├── .htaccess
│   └── storage -> ../storage/app/public
├── app/
├── bootstrap/
├── config/
├── database/
├── routes/
├── storage/
├── vendor/
├── .env                           ← Copy dari .env.production
└── ...
```

**Setup di cPanel:**
1. Buat subdomain `api.yourdomain.com` dengan Document Root ke `api.yourdomain.com/public`
2. Domain utama `yourdomain.com` mengarah ke `public_html/`

#### Skenario B: Satu Domain (Backend di subfolder)

```
📁 public_html/                    ← Frontend (isi folder dist/)
├── index.html
├── .htaccess
├── assets/
└── ...

📁 di luar public_html (misal: ~/laravel-backend/)
├── app/
├── bootstrap/
├── config/
├── public/                        ← Symlink ke public_html/api/
└── ...
```

---

### STEP 5: Setup Backend di Server

Setelah upload, jalankan via **cPanel Terminal** atau **SSH**:

```bash
# Masuk ke folder backend
cd ~/api.yourdomain.com

# Install dependencies
composer install --optimize-autoloader --no-dev

# Copy env
cp .env.production .env

# Generate key (jika belum)
php artisan key:generate

# Migrasi database
php artisan migrate --force

# Buat symlink storage
php artisan storage:link

# Cache config untuk performa
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set permission folder
chmod -R 775 storage bootstrap/cache
```

---

### STEP 6: Konfigurasi Database di cPanel

1. Buka **cPanel → MySQL Databases**
2. Buat database baru (misal: `cpanel_username_compro`)
3. Buat user database baru
4. Assign user ke database dengan **ALL PRIVILEGES**
5. Update `.env` di server dengan credential yang benar

---

## 🛡️ Checklist Keamanan Production

| Item | Status |
|------|--------|
| `APP_DEBUG=false` | ✅ Sudah diset di `.env.production` |
| `APP_ENV=production` | ✅ Sudah diset |
| HTTPS redirect (`.htaccess`) | ✅ Sudah ditambahkan |
| CORS hanya izinkan domain sendiri | ✅ Via `CORS_ALLOWED_ORIGINS` |
| `SESSION_SECURE_COOKIE=true` | ✅ Cookie hanya via HTTPS |
| `SESSION_SAME_SITE=none` | ✅ Untuk cross-subdomain |
| Security headers (X-Frame, XSS, etc.) | ✅ Di kedua `.htaccess` |
| `TrustProxies` middleware | ✅ Sudah ditambahkan di `bootstrap/app.php` |
| Token auth via Sanctum | ✅ Sudah ada (bearer token) |
| `LOG_LEVEL=error` (production) | ✅ Hanya log error saja |
| `SESSION_ENCRYPT=true` | ✅ Enkripsi session data |
| GZIP compression | ✅ Di kedua `.htaccess` |
| Static asset caching | ✅ Di frontend `.htaccess` |

---

## ⚠️ Hal Penting Yang Perlu Diperhatikan

> [!WARNING]
> ### Sebelum zip & upload, PASTIKAN:
> 1. **Sudah edit URL domain** di `.env.production` (frontend & backend)
> 2. **Sudah `npm run build`** untuk frontend
> 3. **Sudah hapus `node_modules/`** dan `vendor/` dari zip backend
> 4. **APP_DEBUG = false** di backend
> 5. **Jangan upload `.env` development** ke server!

> [!TIP]
> ### Tips Rumahweb cPanel:
> - Gunakan **File Manager** atau **SSH** untuk upload zip lalu extract
> - Gunakan **cPanel Terminal** untuk jalankan artisan commands
> - Pastikan PHP version di cPanel = **8.2+** (sesuai `composer.json`)
> - Aktifkan **SSL Certificate** (Let's Encrypt gratis) sebelum deploy
> - Jika storage symlink gagal, buat manual di File Manager

---

## 🔧 Troubleshooting

| Masalah | Solusi |
|---------|--------|
| CORS error di browser | Cek `CORS_ALLOWED_ORIGINS` di `.env` backend, pastikan domain frontend benar |
| 500 Internal Server Error | Cek `storage/logs/laravel.log`, pastikan `APP_DEBUG=true` sementara untuk debug |
| Gambar tidak muncul | Jalankan `php artisan storage:link`, cek permission folder `storage/` |
| Login gagal / 401 | Cek `SANCTUM_STATEFUL_DOMAINS` di `.env`, pastikan include domain frontend |
| Vue router 404 | Pastikan `.htaccess` SPA routing ada di root frontend (sudah include) |
| Mixed content (HTTP/HTTPS) | Pastikan `APP_URL` pakai `https://`, `TrustProxies` sudah aktif |
