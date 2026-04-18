/**
 * Generate image URL dengan path yang konsisten
 * @param {string} imagePath - Path dari database (bisa berupa hashname atau path lengkap)
 * @returns {string} - Full URL gambar
 */
export const getImageUrl = (imagePath) => {
    if (!imagePath) {
        return '/img/LOGO-KUNING.png';
    }

    // Jika sudah URL lengkap, return apa adanya
    if (imagePath.startsWith('http')) {
        return imagePath;
    }

    // Ambil base URL dari environment atau default
    const apiUrl = import.meta.env.VITE_API_BASE_URL || 'http://localhost:8000/api';
    const baseUrl = apiUrl.replace(/\/api\/?$/, '');

    // Bersihkan path: hapus 'public/' dan slash di awal
    let cleanPath = imagePath.replace(/^public\//, '').replace(/^\//, '');

    // SOLUSI: Cek apakah path mengandung folder (ada karakter '/')
    // Jika tidak ada '/', berarti database hanya menyimpan nama file (milik articles)
    if (!cleanPath.includes('/')) {
        cleanPath = `articles/${cleanPath}`;
    }

    return `${baseUrl}/storage/${cleanPath}`;
};

/**
 * Handle image error dengan fallback ke logo default
 * @param {Event} event - Error event dari img tag
 */
export const handleImageError = (event) => {
    event.target.src = '/img/LOGO-KUNING.png';
};