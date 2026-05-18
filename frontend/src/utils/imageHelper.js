/**
 * Generate image URL dengan path yang konsisten
 * Mendukung localhost (dev) dan production hosting
 * @param {string} imagePath - Path dari database (bisa berupa hashname atau path lengkap)
 * @returns {string} - Full URL gambar
 */

// Ambil base URL SEKALI saat module dimuat (bukan di dalam fungsi)
// agar Vite bisa meng-embed nilai env variable dengan benar pada saat build
const _apiUrl = import.meta.env.VITE_API_BASE_URL;

if (!_apiUrl) {
    console.warn('[imageHelper] VITE_API_BASE_URL tidak ditemukan! Gambar mungkin tidak tampil dengan benar. Pastikan file .env.production sudah dikonfigurasi dan build ulang.');
}

const _baseUrl = (_apiUrl || 'http://localhost:8000/api').replace(/\/api\/?$/, '');

export const getImageUrl = (imagePath) => {
    if (!imagePath) {
        return '/img/LOGO-KUNING.png';
    }

    // Jika sudah URL lengkap (http/https), return apa adanya
    if (imagePath.startsWith('http://') || imagePath.startsWith('https://')) {
        return imagePath;
    }

    // Bersihkan path: hapus prefix 'public/' dan slash di awal
    let cleanPath = imagePath
        .replace(/^public\//, '')
        .replace(/^\/+/, '');

    // Jika tidak ada folder dalam path (misal: "abc123.jpg"),
    // asumsikan ini nama file artikel dan tambahkan prefix folder
    if (!cleanPath.includes('/')) {
        cleanPath = `articles/${cleanPath}`;
    }

    return `${_baseUrl}/storage/${cleanPath}`;
};

/**
 * Handle image error dengan fallback ke logo default
 * @param {Event} event - Error event dari img tag
 */
export const handleImageError = (event) => {
    event.target.src = '/img/LOGO-KUNING.png';
};