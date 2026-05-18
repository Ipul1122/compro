<script setup>
import { ref, onMounted, watch, nextTick, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useI18n } from 'vue-i18n'
import { useHead } from '@unhead/vue'
import Api from '@/api'
import Navbar from '@/components/Navbar.vue'
import { getImageUrl, handleImageError } from '@/utils/imageHelper'

const route = useRoute()
const router = useRouter()
const { locale } = useI18n()

const article = ref(null)
const latestArticles = ref([])
const relatedArticles = ref([])
const isLoading = ref(true)

// =========================================================================
// SETUP SEO & META TAGS (DINAMIS & REAKTIF)
// Mengganti fungsi manual updateDocumentTitle() dengan Vue Computed
// =========================================================================

const articleTitle = computed(() => {
    if (!article.value) return 'Cakrawala'
    const title = locale.value === 'en' && article.value.title_en 
        ? article.value.title_en 
        : article.value.title
    return `${title} - Cakrawala`
})

// Gunakan short_description/excerpt artikel jika ada, agar deskripsi link bagus.
const articleDesc = computed(() => {
    if (!article.value) return 'Artikel Cakrawala'
    return locale.value === 'en' && article.value.short_description_en
        ? article.value.short_description_en
        : (article.value.short_description || article.value.excerpt || 'Baca artikel selengkapnya di Cakrawala.')
})

const articleImage = computed(() => {
    return article.value && article.value.image ? getImageUrl(article.value.image) : ''
})

const currentUrl = computed(() => {
    return typeof window !== 'undefined' ? window.location.href : ''
})

// Inject Head menggunakan @unhead/vue
useHead({
    title: articleTitle,
    meta: [
        { name: 'description', content: articleDesc },
        
        // Open Graph / Facebook / WhatsApp
        { property: 'og:type', content: 'article' },
        { property: 'og:title', content: articleTitle },
        { property: 'og:description', content: articleDesc },
        { property: 'og:image', content: articleImage },
        { property: 'og:url', content: currentUrl },
        { property: 'og:site_name', content: 'Cakrawala' },
        
        // Twitter Card
        { name: 'twitter:card', content: 'summary_large_image' },
        { name: 'twitter:title', content: articleTitle },
        { name: 'twitter:description', content: articleDesc },
        { name: 'twitter:image', content: articleImage }
    ]
})

// =========================================================================
// FETCHING DATA
// =========================================================================

const fetchDetail = async (slug) => {
    isLoading.value = true
    try {
        const response = await Api.get(`/articles/${slug}`)
        article.value = response.data.data
        
        if (article.value && article.value.category_id) {
            fetchRelated(article.value.category_id, article.value.id)
        }
        
        // Tunggu sampai DOM selesai me-render v-html
        nextTick(() => {
            normalizeHeadingIds()
        })
    } catch (error) {
        router.push({ name: 'NotFound' })
    } finally {
        isLoading.value = false
    }
}

const fetchLatest = async () => {
    try {
        const response = await Api.get('/articles?limit=3')
        latestArticles.value = response.data.data
    } catch (error) {
        console.error("Error fetching latest articles:", error)
    }
}

const fetchRelated = async (categoryId, currentArticleId) => {
    try {
        relatedArticles.value = []
        const response = await Api.get(`/articles?category_id=${categoryId}&limit=4`)
        const articles = response.data.data || []
        relatedArticles.value = articles.filter(a => a.id !== currentArticleId).slice(0, 3)
    } catch (error) {
        console.error("Error fetching related articles:", error)
    }
}

onMounted(() => {
    fetchDetail(route.params.slug)
    fetchLatest()
})

// Deteksi perubahan slug untuk navigasi antar artikel
watch(() => route.params.slug, (newSlug) => {
    if (newSlug) fetchDetail(newSlug)
})

const formatDate = (dateString) => {
    if (!dateString) return '-'
    const currentLocale = locale.value === 'en' ? 'en-US' : 'id-ID'
    return new Date(dateString).toLocaleDateString(currentLocale, {
        day: 'numeric', month: 'long', year: 'numeric'
    })
}

// =========================================================================
// TABLE OF CONTENT (TOC) HELPER FUNCTIONS
// =========================================================================

// Fungsi helper untuk generate ID dari text (sebagai backup)
const generateIdFromText = (text) => {
    return text
        .toLowerCase()
        .replace(/\s+/g, '-')
        .replace(/[^\w\-]/g, '')
        .replace(/\-+/g, '-')
        .replace(/^-|-$/g, '')
}

// Suntikkan ID dari link TOC ke elemen Heading
const normalizeHeadingIds = () => {
    const articleElement = document.querySelector('article')
    if (!articleElement) return
    
    // Ambil semua heading (H2, H3, H4) dalam urutan kemunculan
    const headings = Array.from(articleElement.querySelectorAll('h2, h3, h4'))
    if (headings.length === 0) return
    
    // Ambil TOC container (biasanya div pertama dalam article dengan class "bg-slate-50")
    const tocContainer = articleElement.querySelector('div.bg-slate-50')
    if (!tocContainer) {
        // Jika TOC tidak ada, at least normalize heading dengan generated ID dari text
        headings.forEach((heading, idx) => {
            if (!heading.id || heading.id.trim() === '') {
                heading.id = generateIdFromText(heading.textContent) || `h-${idx}`
            }
        })
        return
    }
    
    // Ambil semua link TOC yang memiliki href dengan #
    const tocLinks = Array.from(tocContainer.querySelectorAll('a[href^="#"]'))
    
    if (tocLinks.length === 0) return
    
    // Strategy: Mapping TOC Links ke Headings berdasarkan urutan + text matching
    const usedHeadings = new Set()
    
    tocLinks.forEach((link) => {
        const expectedId = link.getAttribute('href').substring(1) // ambil "section-0"
        const linkText = link.textContent.trim()
        const linkTextLower = linkText.toLowerCase()
        
        // Cari heading yang belum di-pair dan text-nya match dengan TOC link
        let matchedHeading = null
        
        // Priority 1: Exact text match (case-insensitive)
        matchedHeading = headings.find((h, idx) => {
            if (usedHeadings.has(idx)) return false
            return h.textContent.trim().toLowerCase() === linkTextLower
        })
        
        // Priority 2: Heading text contains TOC link text (partial match)
        if (!matchedHeading) {
            matchedHeading = headings.find((h, idx) => {
                if (usedHeadings.has(idx)) return false
                return h.textContent.trim().toLowerCase().includes(linkTextLower)
            })
        }
        
        // Priority 3: TOC link text contains heading text
        if (!matchedHeading) {
            matchedHeading = headings.find((h, idx) => {
                if (usedHeadings.has(idx)) return false
                return linkTextLower.includes(h.textContent.trim().toLowerCase())
            })
        }
        
        // Priority 4: Jika masih tidak ketemu, gunakan heading terdekat yang belum di-pair
        if (!matchedHeading) {
            matchedHeading = headings.find((h, idx) => !usedHeadings.has(idx))
        }
        
        // Inject expected ID ke heading yang match
        if (matchedHeading) {
            const headingIndex = headings.indexOf(matchedHeading)
            usedHeadings.add(headingIndex)
            matchedHeading.id = expectedId
            console.debug(`✓ TOC Link "${linkText}" → ID: #${expectedId}`)
        } else {
            console.warn(`✗ Tidak ada heading yang cocok untuk TOC: "${linkText}" (expected ID: #${expectedId})`)
        }
    })
    
    // Fallback: Jika ada heading yang belum di-pair, buatkan generated ID
    headings.forEach((heading, idx) => {
        if (!usedHeadings.has(idx) && (!heading.id || heading.id.trim() === '')) {
            const generatedId = generateIdFromText(heading.textContent) || `h-${idx}`
            heading.id = generatedId
            console.debug(`✓ Heading fallback: "${heading.textContent.substring(0, 30)}" → ID: #${generatedId}`)
        }
    })
}

// Fungsi untuk menangani klik TOC dan mencegah blank page
const handleContentClick = (e) => {
    const target = e.target.closest('a')
    
    if (!target) return

    const href = target.getAttribute('href')
    if (!href) return
    
    // Pengecekan aman apakah ini link target satu halaman (TOC)
    const isHashLink = href.includes('#')
    const isLocalLink = href.startsWith('#') || href.startsWith('.') || !href.includes('://')
    
    if (!isHashLink || !isLocalLink) return
    
    // Cegah aksi default yang bikin blank page
    e.preventDefault()
    e.stopPropagation()
    
    const hashIndex = href.indexOf('#')
    if (hashIndex === -1) return
    
    const hash = href.substring(hashIndex)
    const id = hash.substring(1) 
    
    if (!id.trim()) return
    
    const decodedId = decodeURIComponent(id)
    
    const scrollToElement = (element) => {
        // Offset navbar fix (-100px agar judul tidak tertutup header web)
        const yOffset = -100
        const elementPosition = element.getBoundingClientRect().top + window.scrollY
        const offsetPosition = elementPosition + yOffset
        
        setTimeout(() => {
            window.scrollTo({
                top: Math.max(0, offsetPosition),
                behavior: 'smooth'
            })
        }, 0)
        
        window.history.replaceState(null, '', hash)
        console.debug(`✓ Scrolled ke: #${decodedId}`)
    }
    
    // Strategy 1: Langsung cari by ID (berfungsi 100% jika normalizeHeadingIds sudah jalan)
    let element = document.getElementById(decodedId)
    if (element) {
        scrollToElement(element)
        return
    }
    
    // Strategy 2: Cari by name attribute
    element = document.querySelector(`[name="${decodedId}"]`)
    if (element) {
        scrollToElement(element)
        return
    }
    
    // Strategy 3: Jika ID format "section-N", cari heading ke-N dalam urutan
    if (decodedId.match(/^section-\d+$/i)) {
        const indexMatch = decodedId.match(/\d+$/)
        if (indexMatch) {
            const index = parseInt(indexMatch[0])
            const headings = document.querySelectorAll('article h2, article h3, article h4')
            if (headings[index]) {
                // Inject ID untuk callback berikutnya
                headings[index].id = decodedId
                scrollToElement(headings[index])
                return
            }
        }
    }
    
    // Strategy 4: Cari heading by generated ID dari text-nya
    const headings = document.querySelectorAll('article h2, article h3, article h4')
    for (const heading of headings) {
        const generatedId = generateIdFromText(heading.textContent)
        if (generatedId === decodedId) {
            if (!heading.id) heading.id = decodedId
            scrollToElement(heading)
            return
        }
    }
    
    // Strategy 5: Fuzzy match - cari heading yang keyword-nya match dengan ID
    const keywords = decodedId.split('-').filter(w => w.length > 2)
    if (keywords.length > 0) {
        for (const heading of headings) {
            const headingText = heading.textContent.toLowerCase()
            const matchCount = keywords.filter(kw => headingText.includes(kw)).length
            if (matchCount === keywords.length) {
                if (!heading.id) heading.id = decodedId
                scrollToElement(heading)
                return
            }
        }
    }
    
    console.error(`❌ TOC: Gagal menemukan elemen untuk "#${decodedId}"`)
}
</script>

<template>
    <div class="min-h-screen bg-white flex flex-col font-montserrat">
        <Navbar />

        <main class="flex-grow container mx-auto px-4 sm:px-6 py-10 md:py-16 max-w-6xl">
            <div class="flex flex-col lg:flex-row gap-10 lg:gap-14">

                <!-- Main Content -->
                <div class="lg:w-2/3">
                    <!-- Loading Skeleton -->
                    <div v-if="isLoading" class="space-y-6">
                        <div class="h-8 bg-slate-100 rounded-lg w-2/3 animate-pulse"></div>
                        <div class="h-5 bg-slate-100 rounded-lg w-1/3 animate-pulse"></div>
                        <div class="h-80 bg-slate-100 rounded-2xl animate-pulse"></div>
                        <div class="h-48 bg-slate-100 rounded-2xl animate-pulse"></div>
                    </div>

                    <!-- Article -->
                    <div v-else-if="article" class="detail-fade-in mt-14">
                        <!-- Category Badge -->
                        <span v-if="article.category" class="inline-block px-4 py-1.5 bg-orange-50 text-orange-600 text-[11px] font-bold uppercase tracking-widest rounded-full mb-6">
                            {{ locale === 'en' && article.category?.name_en ? article.category.name_en : article.category?.name }}
                        </span>

                        <h1 class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-slate-900 leading-snug mb-6">
                            {{ locale === 'en' && article.title_en ? article.title_en : article.title }}
                        </h1>

                        <!-- Meta Row -->
                        <div class="flex flex-wrap items-center gap-4 mb-8 text-xs text-slate-400 font-medium">
                            <div v-if="article.author" class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                <span>{{ article.author.name }}</span>
                            </div>
                            <span v-if="article.author" class="w-1 h-1 bg-slate-300 rounded-full"></span>
                            
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span>{{ formatDate(article.created_at) }}</span>
                            </div>
                            <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <span>{{ article.total_view || 0 }} Views</span>
                            </div>
                            <!-- Social Links -->
                            <div class="ml-auto flex items-center gap-2">
                                <a href="https://www.instagram.com/cakrawala.parama?igsh=eHczc3hhYW82MWoy" target="_blank" rel="noopener noreferrer" class="share-btn text-pink-500 hover:bg-pink-50 flex items-center justify-center" style="text-decoration: none;" aria-label="Instagram">IG</a>
                                <a href="https://www.linkedin.com/in/pt-cakrawala-parama-internasional-b06182356" target="_blank" rel="noopener noreferrer" class="share-btn text-blue-500 hover:bg-blue-50 flex items-center justify-center" style="text-decoration: none;" aria-label="LinkedIn">IN</a>
                            </div>
                        </div>

                        <!-- Featured Image -->
                        <div class="rounded-2xl overflow-hidden mb-10 shadow-sm hover:shadow-lg transition-shadow duration-300">
                            <img :src="getImageUrl(article.image)" @error="handleImageError" class="w-full h-auto object-cover" />
                        </div>

                        <!-- Article Body -->
                        <article
                            @click="handleContentClick"
                            class="article-body rounded-2xl border border-slate-100 bg-white p-6 sm:p-8 md:p-10 text-slate-700 prose prose-slate prose-lg max-w-none prose-a:text-orange-600 prose-a:no-underline hover:prose-a:underline prose-img:rounded-xl"
                            v-html="locale === 'en' && article.content_en ? article.content_en : article.content">
                        </article>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="lg:w-1/3">
                    <div class="sticky top-24 space-y-8">
                        <!-- Latest Articles -->
                        <div>
                            <h2 class="text-sm font-bold text-slate-900 uppercase tracking-widest mb-5 flex items-center gap-2">
                                <span class="w-6 h-0.5 bg-orange-500 rounded-full"></span>
                                {{ locale === 'en' ? 'Latest Articles' : 'Artikel Terbaru' }}
                            </h2>

                            <div class="flex flex-col gap-4">
                                <router-link v-for="lat in latestArticles" :key="lat.id" :to="`/articles/${lat.slug}`"
                                    class="group flex gap-4 p-3 rounded-xl bg-slate-50/70 hover:bg-orange-50 border border-transparent hover:border-orange-100 transition-all duration-200">
                                    <div class="w-20 h-16 flex-shrink-0 overflow-hidden rounded-lg">
                                        <img :src="getImageUrl(lat.image)" @error="handleImageError" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" />
                                    </div>
                                    <div class="flex flex-col justify-center min-w-0">
                                        <span class="text-[10px] font-bold text-orange-500 uppercase tracking-wider mb-1">
                                            {{ locale === 'en' && lat.category?.name_en ? lat.category?.name_en : lat.category?.name }}
                                        </span>
                                        <h3 class="text-sm font-semibold text-slate-800 line-clamp-2 leading-snug group-hover:text-orange-600 transition-colors duration-200">
                                            {{ locale === 'en' && lat.title_en ? lat.title_en : lat.title }}
                                        </h3>
                                    </div>
                                </router-link>
                            </div>
                        </div>

                        <!-- CTA Card -->
                        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-orange-500 to-orange-600 p-7 text-white">
                            <div class="absolute -top-8 -right-8 w-28 h-28 bg-white/10 rounded-full"></div>
                            <div class="absolute -bottom-6 -left-6 w-20 h-20 bg-white/10 rounded-full"></div>
                            <h4 class="text-lg font-extrabold mb-2 relative z-10">{{ locale === 'en' ? 'Need Help?' : 'Butuh Bantuan?' }}</h4>
                            <p class="text-orange-100 text-sm mb-5 relative z-10">{{ locale === 'en' ? 'Contact our team for a free consultation.' : 'Hubungi tim kami untuk konsultasi gratis.' }}</p>
                            <button class="relative z-10 w-full bg-white text-orange-600 py-3 rounded-xl font-bold text-xs uppercase tracking-wider hover:bg-orange-50 hover:shadow-lg transition-all duration-200">
                                {{ locale === 'en' ? 'Start Now' : 'Mulai Sekarang' }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Articles -->
            <div v-if="relatedArticles.length > 0" class="mt-20 pt-12 border-t border-slate-100">
                <h2 class="text-xl md:text-2xl font-extrabold text-slate-900 mb-8 flex items-center gap-3">
                    <span class="w-8 h-1 bg-orange-500 rounded-full"></span>
                    {{ locale === 'en' ? 'Related Articles' : 'Artikel Terkait' }}
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                    <router-link v-for="rel in relatedArticles" :key="rel.id" :to="`/articles/${rel.slug}`"
                        class="group rounded-2xl border border-slate-100 bg-white hover:shadow-xl hover:shadow-orange-100/40 transition-all duration-300 overflow-hidden flex flex-col h-full hover:-translate-y-1">
                        <div class="w-full h-48 overflow-hidden">
                            <img :src="getImageUrl(rel.image)" @error="handleImageError" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                        </div>
                        <div class="p-5 flex flex-col flex-grow">
                            <span class="text-[10px] font-bold text-orange-500 uppercase mb-2 tracking-wider">
                                {{ locale === 'en' && rel.category?.name_en ? rel.category?.name_en : rel.category?.name }}
                            </span>
                            <h3 class="text-base font-semibold text-slate-800 line-clamp-2 leading-snug group-hover:text-orange-600 transition-colors duration-200">
                                {{ locale === 'en' && rel.title_en ? rel.title_en : rel.title }}
                            </h3>
                        </div>
                    </router-link>
                </div>
            </div>
        </main>
    </div>
</template>

<style scoped>
/* Fade-in animation */
.detail-fade-in {
    animation: fadeUp 0.5s ease-out both;
}
@keyframes fadeUp {
    from { opacity: 0; transform: translateY(16px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Share buttons */
.share-btn {
    padding: 0.35rem 0.6rem;
    font-size: 0.65rem;
    font-weight: 700;
    border-radius: 0.5rem;
    border: 1px solid #e2e8f0;
    background: white;
    cursor: pointer;
    transition: all 0.2s;
}

/* Line clamp utility */
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Smooth scrolling */
html {
    scroll-behavior: smooth;
}

/* Article body deep styles — restore heading & whitespace */
.article-body :deep(h2) {
    font-family: 'Montserrat', sans-serif;
    font-weight: 800;
    font-size: 1.5rem;
    line-height: 1.35;
    color: #0f172a;
    margin-top: 2.25rem;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid #fed7aa;
}

.article-body :deep(h3) {
    font-family: 'Montserrat', sans-serif;
    font-weight: 700;
    font-size: 1.25rem;
    line-height: 1.4;
    color: #1e293b;
    margin-top: 1.75rem;
    margin-bottom: 0.75rem;
}

.article-body :deep(h4) {
    font-family: 'Montserrat', sans-serif;
    font-weight: 700;
    font-size: 1.1rem;
    line-height: 1.4;
    color: #334155;
    margin-top: 1.5rem;
    margin-bottom: 0.5rem;
}

.article-body :deep(p) {
    margin-top: 0;
    margin-bottom: 1.25rem;
    line-height: 1.8;
}

.article-body :deep(ul),
.article-body :deep(ol) {
    margin-top: 0.5rem;
    margin-bottom: 1.25rem;
    padding-left: 1.5rem;
}

.article-body :deep(li) {
    margin-bottom: 0.4rem;
    line-height: 1.75;
}

.article-body :deep(blockquote) {
    border-left: 4px solid #ea580c;
    padding: 0.75rem 1.25rem;
    margin: 1.5rem 0;
    background: #fff7ed;
    border-radius: 0 0.5rem 0.5rem 0;
    color: #9a3412;
    font-style: italic;
}

.article-body :deep(img) {
    margin-top: 1.5rem;
    margin-bottom: 1.5rem;
}

.article-body :deep(pre) {
    margin: 1.5rem 0;
}

.article-body :deep(hr) {
    margin: 2rem 0;
    border-color: #f1f5f9;
}
</style>