<script setup>
import { ref, onMounted, computed } from 'vue'
import { useRouter } from 'vue-router'
import { useI18n } from 'vue-i18n'
import Api from '@/api'
import { getImageUrl, handleImageError } from '@/utils/imageHelper'

const router = useRouter()
// Ekstrak 't' untuk terjemahan statis (locales), dan 'locale' untuk data dinamis (API)
const { t, locale } = useI18n()

const galleries = ref([])
const isLoading = ref(true)

const fetchGalleries = async () => {
    isLoading.value = true
    try {
        const response = await Api.get('/galleries')
        galleries.value = response.data.data || response.data || []
    } catch (error) {
        console.error("Error fetching galleries:", error)
    } finally {
        isLoading.value = false
    }
}

// Logika Pengelompokan Wadah
const groupedAlbums = computed(() => {
    const groups = {}
    
    galleries.value.forEach(gallery => {
        const baseTitle = gallery.title_image ? gallery.title_image.replace(/\s\d+$/, '').trim() : 'Galeri'
        const baseTitleEn = gallery.title_image_en ? gallery.title_image_en.replace(/\s\d+$/, '').trim() : baseTitle
        
        const key = `${baseTitle}_${gallery.category_id}`
        
        if (!groups[key]) {
            groups[key] = {
                id: key,
                title: baseTitle,
                title_en: baseTitleEn,
                category: gallery.category,
                cover: null,
                images: [] 
            }
        }
        groups[key].images.push(gallery)
    })

    let result = Object.values(groups)

    // Urutkan & Tentukan cover
    result.forEach(album => {
        album.images.sort((a, b) => {
            const numA = parseInt(a.title_image?.match(/\d+$/)?.[0] || 0)
            const numB = parseInt(b.title_image?.match(/\d+$/)?.[0] || 0)
            return numA - numB 
        })
        
        if(album.images.length > 0) {
            album.cover = album.images[0].image
        }
    })

    // AMBIL MAKSIMAL 3 WADAH TERATAS UNTUK PREVIEW HOME
    return result.slice(0, 3)
})

const goToGallery = () => {
    router.push('/galerry') 
}

onMounted(() => {
    fetchGalleries()
})
</script>

<template>
    <!-- Import Montserrat font -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&display=swap" rel="stylesheet" />

    <section class="gallery-section py-28 relative overflow-hidden">

        <!-- Decorative background elements -->
        <div class="bg-orb bg-orb-1"></div>
        <div class="bg-orb bg-orb-2"></div>
        <div class="bg-grid"></div>

        <div class="container mx-auto px-6 max-w-7xl relative z-10">

            <!-- Header -->
            <div class="flex flex-col md:flex-row justify-between items-end mb-16">
                <div class="max-w-xl">
                    <!-- Badge -->
                    <div class="badge-pill mb-5">
                        <span class="badge-dot"></span>
                        <span class="badge-text">{{ t('gallerySection.subtitle') }}</span>
                    </div>

                    <h2 class="section-title">
                        {{ t('gallerySection.title') }}
                    </h2>

                    <!-- Accent underline -->
                    <div class="title-underline mt-4"></div>
                </div>

                <button @click="goToGallery" class="see-all-btn mt-8 md:mt-0">
                    <span>{{ t('gallerySection.seeAll') }}</span>
                    <span class="btn-arrow-wrap">
                        <svg xmlns="http://www.w3.org/2000/svg" class="btn-arrow" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </span>
                </button>
            </div>

            <!-- Loading skeleton -->
            <div v-if="isLoading" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                <div v-for="n in 3" :key="n" class="skeleton-card">
                    <div class="skeleton-shimmer"></div>
                    <div class="skeleton-footer">
                        <div class="skeleton-line w-1/3"></div>
                        <div class="skeleton-line w-2/3 mt-2"></div>
                    </div>
                </div>
            </div>

            <!-- Gallery grid -->
            <div v-else-if="groupedAlbums.length > 0" class="gallery-grid">
                <div
                    v-for="(album, index) in groupedAlbums"
                    :key="album.id"
                    @click="goToGallery"
                    class="album-card"
                    :class="`album-card--${index}`"
                    :style="`--delay: ${index * 80}ms`"
                >
                    <!-- Cover image -->
                    <img
                        :src="getImageUrl(album.cover)"
                        @error="handleImageError"
                        class="album-img"
                    />

                    <!-- Image count badge -->
                    <div class="count-badge">
                        <svg xmlns="http://www.w3.org/2000/svg" class="count-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span>{{ album.images.length }}</span>
                    </div>

                    <!-- Hover overlay -->
                    <div class="album-overlay">
                        <div class="overlay-content">
                            <span class="overlay-category">
                                {{ locale === 'en' && album.category?.name_en ? album.category.name_en : album.category?.name }}
                            </span>
                            <h3 class="overlay-title">
                                {{ locale === 'en' && album.title_en ? album.title_en : album.title }}
                            </h3>
                            <div class="overlay-cta">
                                <span class="overlay-cta-text">{{ t('gallerySection.openGallery') }}</span>
                                <span class="overlay-cta-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </span>
                            </div>
                        </div>

                        <!-- Orange corner accent -->
                        <!-- <div class="corner-accent"></div> -->
                    </div>

                    <!-- Bottom label always visible on mobile -->
                    <div class="mobile-label">
                        <span class="mobile-label-cat">
                            {{ locale === 'en' && album.category?.name_en ? album.category.name_en : album.category?.name }}
                        </span>
                        <h3 class="mobile-label-title">
                            {{ locale === 'en' && album.title_en ? album.title_en : album.title }}
                        </h3>
                    </div>
                </div>
            </div>

            <!-- Empty state -->
            <div v-else class="empty-state">
                <div class="empty-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <p class="empty-text">{{ t('gallerySection.empty') }}</p>
            </div>
        </div>
    </section>
</template>

<style scoped>
/* ─── Base / Font ─────────────────────────────────── */
.gallery-section {
    font-family: 'Montserrat', sans-serif;
    background-color: #fafafa;
    background-image: radial-gradient(ellipse 80% 60% at 50% -10%, #fff7ed 0%, transparent 70%);
}

/* ─── Background decorations ──────────────────────── */
.bg-orb {
    position: absolute;
    border-radius: 50%;
    filter: blur(80px);
    pointer-events: none;
}
.bg-orb-1 {
    width: 500px; height: 500px;
    background: radial-gradient(circle, #fb923c22 0%, transparent 70%);
    top: -120px; right: -100px;
}
.bg-orb-2 {
    width: 350px; height: 350px;
    background: radial-gradient(circle, #f9731622 0%, transparent 70%);
    bottom: -80px; left: -60px;
}
.bg-grid {
    position: absolute;
    inset: 0;
    background-image:
        linear-gradient(rgba(0,0,0,0.03) 1px, transparent 1px),
        linear-gradient(90deg, rgba(0,0,0,0.03) 1px, transparent 1px);
    background-size: 48px 48px;
    mask-image: radial-gradient(ellipse 70% 60% at 50% 50%, black 40%, transparent 100%);
}

/* ─── Badge pill ──────────────────────────────────── */
.badge-pill {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 6px 16px;
    background: linear-gradient(135deg, #fff7ed, #ffedd5);
    border: 1.5px solid #fed7aa;
    border-radius: 100px;
}
.badge-dot {
    width: 8px; height: 8px;
    border-radius: 50%;
    background: #f97316;
    animation: pulse-dot 1.8s ease-in-out infinite;
}
@keyframes pulse-dot {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.5; transform: scale(0.75); }
}
.badge-text {
    font-family: 'Montserrat', sans-serif;
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: #ea580c;
}

/* ─── Section title ───────────────────────────────── */
.section-title {
    font-family: 'Montserrat', sans-serif;
    font-size: clamp(2rem, 4vw, 2.75rem);
    font-weight: 900;
    color: #0f172a;
    line-height: 1.1;
    letter-spacing: -0.02em;
}
.title-underline {
    width: 64px; height: 5px;
    background: linear-gradient(90deg, #f97316, #fb923c, #fdba74);
    border-radius: 100px;
    position: relative;
}
.title-underline::after {
    content: '';
    position: absolute;
    left: 72px; top: 50%; transform: translateY(-50%);
    width: 8px; height: 5px;
    background: #f97316;
    border-radius: 100px;
}

/* ─── See all button ──────────────────────────────── */
.see-all-btn {
    font-family: 'Montserrat', sans-serif;
    display: inline-flex;
    align-items: center;
    gap: 12px;
    padding: 14px 24px;
    background: white;
    border: 2px solid #e2e8f0;
    border-radius: 16px;
    font-size: 14px;
    font-weight: 700;
    color: #1e293b;
    cursor: pointer;
    transition: all 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    letter-spacing: 0.01em;
}
.see-all-btn:hover {
    background: #f97316;
    border-color: #f97316;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(249, 115, 22, 0.35);
}
.btn-arrow-wrap {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 28px; height: 28px;
    background: #f1f5f9;
    border-radius: 8px;
    transition: all 0.25s ease;
    flex-shrink: 0;
}
.see-all-btn:hover .btn-arrow-wrap {
    background: rgba(255,255,255,0.25);
}
.btn-arrow {
    width: 16px; height: 16px;
    transition: transform 0.25s ease;
}
.see-all-btn:hover .btn-arrow {
    transform: translateX(3px);
}

/* ─── Skeleton ────────────────────────────────────── */
.skeleton-card {
    background: white;
    border-radius: 24px;
    overflow: hidden;
    height: 320px;
    border: 1.5px solid #f1f5f9;
    position: relative;
    box-shadow: 0 2px 12px rgba(0,0,0,0.04);
    display: flex;
    flex-direction: column;
}
.skeleton-shimmer {
    flex: 1;
    background: linear-gradient(110deg, #f1f5f9 30%, #e2e8f0 50%, #f1f5f9 70%);
    background-size: 200% 100%;
    animation: shimmer 1.6s linear infinite;
}
@keyframes shimmer {
    0% { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}
.skeleton-footer {
    padding: 16px 20px;
    border-top: 1px solid #f1f5f9;
}
.skeleton-line {
    height: 10px;
    background: linear-gradient(110deg, #f1f5f9 30%, #e2e8f0 50%, #f1f5f9 70%);
    background-size: 200% 100%;
    animation: shimmer 1.6s linear infinite;
    border-radius: 6px;
}

/* ─── Gallery grid ────────────────────────────────── */
.gallery-grid {
    display: grid;
    grid-template-columns: repeat(1, 1fr);
    gap: 24px;
}
@media (min-width: 640px) {
    .gallery-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}
@media (min-width: 768px) {
    .gallery-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}

/* ─── Album card ──────────────────────────────────── */
.album-card {
    position: relative;
    cursor: pointer;
    background: white;
    border-radius: 24px;
    border: 1.5px solid #f1f5f9;
    overflow: hidden;
    aspect-ratio: 4/3;
    box-shadow: 0 4px 16px rgba(0,0,0,0.06);
    transition: transform 0.35s cubic-bezier(0.34, 1.56, 0.64, 1),
                box-shadow 0.35s ease,
                border-color 0.35s ease;
    animation: card-in 0.5s ease both;
    animation-delay: var(--delay, 0ms);
}
@keyframes card-in {
    from { opacity: 0; transform: translateY(24px) scale(0.97); }
    to   { opacity: 1; transform: translateY(0) scale(1); }
}
@media (min-width: 640px) {
    .album-card { aspect-ratio: 1/1; }
}
.album-card:hover {
    transform: translateY(-6px) scale(1.01);
    box-shadow: 0 20px 48px rgba(249, 115, 22, 0.18), 0 8px 20px rgba(0,0,0,0.08);
    border-color: #fb923c;
}

/* ─── Album image ─────────────────────────────────── */
.album-img {
    width: 100%; height: 100%;
    object-fit: cover;
    transition: transform 0.7s cubic-bezier(0.25, 0.46, 0.45, 0.94);
}
.album-card:hover .album-img {
    transform: scale(1.08);
}

/* ─── Count badge ─────────────────────────────────── */
.count-badge {
    position: absolute;
    top: 14px; right: 14px;
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    background: rgba(15, 23, 42, 0.82);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    border-radius: 12px;
    border: 1px solid rgba(255,255,255,0.12);
    font-family: 'Montserrat', sans-serif;
    font-size: 12px;
    font-weight: 800;
    color: white;
    letter-spacing: 0.05em;
    transition: background 0.25s ease, transform 0.25s ease;
    z-index: 3;
}
.album-card:hover .count-badge {
    background: rgba(249, 115, 22, 0.9);
    transform: scale(1.05);
}
.count-icon {
    width: 15px; height: 15px;
    color: #fb923c;
    transition: color 0.25s ease;
}
.album-card:hover .count-icon {
    color: white;
}

/* ─── Hover overlay ───────────────────────────────── */
.album-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, rgba(15,23,42,0.92) 0%, rgba(15,23,42,0.4) 45%, transparent 80%);
    opacity: 0;
    transition: opacity 0.35s ease;
    display: flex;
    align-items: flex-end;
    z-index: 2;
}
.album-card:hover .album-overlay {
    opacity: 1;
}
@media (max-width: 639px) {
    /* On mobile, slightly show overlay to hint at interactivity */
    .album-overlay {
        opacity: 0.6;
    }
}

.overlay-content {
    padding: 24px;
    transform: translateY(8px);
    transition: transform 0.35s cubic-bezier(0.34, 1.2, 0.64, 1);
}
.album-card:hover .overlay-content {
    transform: translateY(0);
}

.overlay-category {
    display: block;
    font-family: 'Montserrat', sans-serif;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 0.14em;
    text-transform: uppercase;
    color: #fb923c;
    margin-bottom: 6px;
}
.overlay-title {
    font-family: 'Montserrat', sans-serif;
    font-size: 20px;
    font-weight: 800;
    color: white;
    line-height: 1.2;
    letter-spacing: -0.01em;
}
.overlay-cta {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-top: 12px;
    opacity: 0;
    transform: translateY(6px);
    transition: opacity 0.3s ease 0.1s, transform 0.3s ease 0.1s;
}
.album-card:hover .overlay-cta {
    opacity: 1;
    transform: translateY(0);
}
.overlay-cta-text {
    font-family: 'Montserrat', sans-serif;
    font-size: 12px;
    font-weight: 700;
    color: #94a3b8;
    letter-spacing: 0.02em;
}
.overlay-cta-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 22px; height: 22px;
    background: #f97316;
    border-radius: 6px;
    flex-shrink: 0;
}
.overlay-cta-icon svg {
    width: 12px; height: 12px;
    color: white;
}

/* Corner accent on hover */
/* .corner-accent {
    position: absolute;
    bottom: 0; left: 0;
    width: 0; height: 0;
    border-style: solid;
    border-width: 0 0 56px 56px;
    border-color: transparent transparent #f97316 transparent;
    opacity: 0;
    transition: opacity 0.3s ease;
} */
.album-card:hover .corner-accent {
    opacity: 1;
}

/* ─── Mobile label ────────────────────────────────── */
.mobile-label {
    position: absolute;
    bottom: 0; left: 0; right: 0;
    padding: 14px 16px;
    background: linear-gradient(to top, rgba(15,23,42,0.88), transparent);
    z-index: 2;
}
@media (min-width: 640px) {
    .mobile-label { display: none; }
}
.mobile-label-cat {
    display: block;
    font-family: 'Montserrat', sans-serif;
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 0.14em;
    text-transform: uppercase;
    color: #fb923c;
    margin-bottom: 2px;
}
.mobile-label-title {
    font-family: 'Montserrat', sans-serif;
    font-size: 15px;
    font-weight: 800;
    color: white;
    line-height: 1.3;
}

/* ─── Empty state ─────────────────────────────────── */
.empty-state {
    text-align: center;
    padding: 80px 24px;
    background: white;
    border-radius: 24px;
    border: 1.5px solid #f1f5f9;
    box-shadow: 0 2px 12px rgba(0,0,0,0.04);
}
.empty-icon {
    width: 56px; height: 56px;
    margin: 0 auto 16px;
    color: #cbd5e1;
}
.empty-icon svg { width: 100%; height: 100%; }
.empty-text {
    font-family: 'Montserrat', sans-serif;
    font-size: 14px;
    font-weight: 600;
    color: #94a3b8;
}
</style>