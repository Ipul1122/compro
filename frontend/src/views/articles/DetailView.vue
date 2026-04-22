<script setup>
import { ref, onMounted, watch, nextTick } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useI18n } from 'vue-i18n'
import Api from '@/api'
import Navbar from '@/components/Navbar.vue'
import { getImageUrl, handleImageError } from '@/utils/imageHelper'

const route = useRoute()
const router = useRouter()
const { locale } = useI18n()

const article = ref(null)
const latestArticles = ref([])
const isLoading = ref(true)

// Update Judul Halaman secara dinamis
const updateDocumentTitle = () => {
    if (article.value) {
        const title = locale.value === 'en' && article.value.title_en 
            ? article.value.title_en 
            : article.value.title
        document.title = `${title} - Cakrawala`
    }
}

const fetchDetail = async (slug) => {
    isLoading.value = true
    try {
        const response = await Api.get(`/articles/${slug}`)
        article.value = response.data.data
        updateDocumentTitle()
        
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

onMounted(() => {
    fetchDetail(route.params.slug)
    fetchLatest()
})

// Deteksi perubahan slug untuk navigasi antar artikel
watch(() => route.params.slug, (newSlug) => {
    if (newSlug) fetchDetail(newSlug)
})

// Deteksi perubahan bahasa untuk update title browser
watch(locale, () => {
    updateDocumentTitle()
})

const formatDate = (dateString) => {
    if (!dateString) return '-'
    const currentLocale = locale.value === 'en' ? 'en-US' : 'id-ID'
    return new Date(dateString).toLocaleDateString(currentLocale, {
        day: 'numeric', month: 'long', year: 'numeric'
    })
}

// Fungsi helper untuk generate ID dari text (sebagai backup)
const generateIdFromText = (text) => {
    return text
        .toLowerCase()
        .replace(/\s+/g, '-')
        .replace(/[^\w\-]/g, '')
        .replace(/\-+/g, '-')
        .replace(/^-|-$/g, '')
}

// FUNGSI INTI PERBAIKAN: Suntikkan ID dari link TOC ke elemen Heading
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
    
    console.error(`
        ❌ TOC: Gagal menemukan elemen untuk "#${decodedId}"
        
        Debugging Info:
        - TOC Link ID: #${decodedId}
        - Heading count: ${headings.length}
        - Attempted strategies: ID lookup, name attr, section-N index, generated ID, keyword match
        
        💡 Possible Solutions:
        1. Re-generate TOC di admin (klik "Buat TOC" lagi)
        2. Pastikan H2/H3 dalam artikel memiliki text yang jelas
        3. Check HTML di Database apakah heading ada
    `)
}
</script>

<template>
    <div class="min-h-screen bg-slate-50 flex flex-col">
        <Navbar />

        <main class="flex-grow container mx-auto px-4 py-12 max-w-6xl">
            <div class="flex flex-col lg:flex-row gap-12">
                
                <div class="lg:w-2/3">
                    <div v-if="isLoading" class="animate-pulse space-y-6">
                        <div class="h-10 bg-slate-200 rounded w-3/4"></div>
                        <div class="h-96 bg-slate-200 rounded-3xl"></div>
                        <div class="h-64 bg-slate-200 rounded-3xl"></div>
                    </div>

                    <div v-else-if="article">
                        <h1 class="text-3xl md:text-5xl font-black text-slate-900 leading-tight my-16">
                            {{ locale === 'en' && article.title_en ? article.title_en : article.title }}
                        </h1>
                        
                        <div class="flex flex-wrap items-center justify-between gap-4 mb-8 border-b border-slate-100 pb-6">
                            <div class="flex items-center gap-4 text-[11px] font-black text-slate-400 uppercase tracking-wider">
                                <span>{{ formatDate(article.created_at) }}</span>
                                <span class="w-1.5 h-1.5 bg-slate-300 rounded-full"></span>
                                <span>{{ article.total_view || 0 }} Views</span>
                            </div>

                            <div class="flex items-center gap-3">
                                <span class="text-[10px] font-black text-slate-400 uppercase mr-2">Share:</span>
                                <div class="flex gap-2">
                                    <button class="p-2 bg-white rounded-xl border border-slate-100 text-pink-600 hover:bg-pink-50 transition-colors">IG</button>
                                    <button class="p-2 bg-white rounded-xl border border-slate-100 text-blue-600 hover:bg-blue-50 transition-colors">FB</button>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-3xl p-2 border border-slate-100 shadow-sm mb-10 overflow-hidden">
                            <img :src="getImageUrl(article.image)" @error="handleImageError" class="w-full h-auto object-cover rounded-2xl" />
                        </div>

                        <article 
                            @click="handleContentClick"
                            class="bg-white rounded-3xl border border-slate-100 shadow-sm p-8 md:p-12 text-slate-700 prose prose-slate prose-lg max-w-none prose-a:text-blue-600 prose-a:no-underline hover:prose-a:underline" 
                            v-html="locale === 'en' && article.content_en ? article.content_en : article.content">
                        </article>
                    </div>
                </div>

                <div class="lg:w-1/3">
                    <div class="sticky top-8">
                        <h2 class="text-xl font-black text-slate-900 uppercase tracking-tight mb-6">
                            {{ locale === 'en' ? 'Latest Articles' : 'Artikel Terbaru' }}
                        </h2>
                        
                        <div class="flex flex-col gap-6">
                            <router-link v-for="lat in latestArticles" :key="lat.id" :to="`/articles/${lat.slug}`" 
                                class="group flex gap-4 bg-white p-3 rounded-2xl border border-slate-100 hover:shadow-xl hover:shadow-slate-200/50 transition-all">
                                <div class="w-24 h-20 flex-shrink-0 overflow-hidden rounded-xl">
                                    <img :src="getImageUrl(lat.image)" @error="handleImageError" class="w-full h-full object-cover group-hover:scale-110 transition-transform" />
                                </div>
                                <div class="flex flex-col justify-center">
                                    <span class="text-[9px] font-black text-blue-600 uppercase mb-1">
                                        {{ locale === 'en' && lat.category?.name_en ? lat.category?.name_en : lat.category?.name }}
                                    </span>
                                    <h3 class="text-sm font-bold text-slate-900 line-clamp-2 leading-tight group-hover:text-blue-600 transition-colors">
                                        {{ locale === 'en' && lat.title_en ? lat.title_en : lat.title }}
                                    </h3>
                                </div>
                            </router-link>
                        </div>

                        <div class="mt-8 bg-blue-600 rounded-3xl p-8 text-white">
                            <h4 class="text-xl font-black mb-2">{{ locale === 'en' ? 'Need Help?' : 'Butuh Bantuan?' }}</h4>
                            <p class="text-blue-100 text-sm mb-6">{{ locale === 'en' ? 'Contact our team for a free consultation.' : 'Hubungi tim kami untuk konsultasi gratis.' }}</p>
                            <button class="w-full bg-white text-blue-600 py-3 rounded-xl font-black uppercase text-xs">
                                {{ locale === 'en' ? 'Start Now' : 'Mulai Sekarang' }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>

<style scoped>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;  
    overflow: hidden;
}

html {
    scroll-behavior: smooth;
}
</style>