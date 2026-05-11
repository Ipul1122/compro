# Setup Project Lokal - PT Cakrawala Parama Internasional

## ✅ Status Persiapan

- ✅ Node.js & npm terinstall
- ✅ Frontend .env sudah konfigurasi
- ✅ Backend .env sudah dibuat
- ⏳ WAITING: XAMPP install

---

## 📋 Langkah-Langkah Setup (Setelah XAMPP Terinstall)

### **STEP 1: Verifikasi XAMPP Terinstall**

Buka PowerShell dan jalankan:

```powershell
# Test PHP
C:\xampp\php\php.exe --version

# Jika berhasil, akan tampil versi PHP
```

---

### **STEP 2: Setup Backend**

```powershell
# Navigate ke folder backend
cd "c:\My Web Sites\compro\backend"

# Install composer dependencies
# (Pastikan composer sudah install global, atau download dari https://getcomposer.org)
composer install

# Generate App Key
C:\xampp\php\php.exe artisan key:generate

# Create database di MySQL
# Buka phpMyAdmin di http://localhost/phpmyadmin
# Create database baru dengan nama: "compro"

# Run migrations (create tables)
C:\xampp\php\php.exe artisan migrate

# Seed data (optional - untuk test data)
C:\xampp\php\php.exe artisan db:seed

# Install npm dependencies
npm install
```

---

### **STEP 3: Setup Frontend**

```powershell
# Navigate ke folder frontend
cd "c:\My Web Sites\compro\frontend"

# Install dependencies
npm install
```

---

### **STEP 4: Jalankan Development Server**

**Buka 2 terminal terpisah:**

**Terminal 1 - Backend (Port 8000):**

```powershell
cd "c:\My Web Sites\compro\backend"
C:\xampp\php\php.exe artisan serve
```

**Terminal 2 - Frontend (Port 5173):**

```powershell
cd "c:\My Web Sites\compro\frontend"
npm run dev
```

---

### **STEP 5: Akses Aplikasi**

| Nama            | URL                         | Catatan             |
| --------------- | --------------------------- | ------------------- |
| **Frontend**    | http://localhost:5173       | Landing page publik |
| **Backend API** | http://localhost:8000/api   | REST API            |
| **phpMyAdmin**  | http://localhost/phpmyadmin | Database management |

---

### **STEP 6: Test Login Admin**

1. Buka http://localhost:5173 di browser
2. Klik tombol "Admin" atau cari LoginView
3. Gunakan credentials:
   - **Email:** user@example.com
   - **Password:** password

> Catatan: Credentials ini dari seeder. Jika belum di-seed, run: `php artisan db:seed`

---

## ⚠️ Troubleshooting

### Error: "Composer not found"

- Download & install Composer dari https://getcomposer.org
- Restart terminal setelah install

### Error: "Database connection refused"

- Pastikan MySQL sudah running (cek XAMPP Control Panel)
- Pastikan database "compro" sudah dibuat di phpMyAdmin
- DB credentials di `.env` sudah benar (`DB_USERNAME=root`, `DB_PASSWORD=` kosong)

### Error: "Port 8000 already in use"

- Ubah port di artisan serve:

```powershell
C:\xampp\php\php.exe artisan serve --port=8001
```

- Update frontend `.env.development` jadi:

```
VITE_API_BASE_URL=http://localhost:8001/api
```

### Error: "npm run dev tidak berjalan"

- Pastikan sudah di folder `frontend`
- Install ulang dependencies: `npm install`
- Cek file `vite.config.js` ada di folder `frontend`

---

## 📝 Project Structure

```
compro/
├── backend/          ← Laravel API (Port 8000)
│   ├── app/
│   ├── config/
│   ├── database/
│   ├── routes/
│   └── .env          ← SUDAH DIBUAT
├── frontend/         ← Vue 3 SPA (Port 5173)
│   ├── src/
│   ├── public/
│   └── .env.development  ← SUDAH KONFIGURASI
└── README.md
```

---

## ✨ Next Steps Setelah Setup Berhasil

1. Explore frontend di http://localhost:5173
2. Test login admin panel
3. Coba tambah artikel baru
4. Upload foto ke galeri
5. Check data di phpMyAdmin

Saat sudah siap, bisa melanjutkan ke **Production Deployment** dengan panduan di `panduan_deployment_rumahweb.md`
