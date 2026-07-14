<script setup>
import { onMounted } from 'vue'
import { useI18n } from 'vue-i18n'

const { t } = useI18n()

// Force scroll to top when page is loaded
onMounted(() => {
    window.scrollTo({
        top: 0,
        left: 0,
        behavior: 'instant'
    })
})

const itTechStack = [
    { name: 'Vue.js', icon: '/icon/it/vue-js.png', desc: 'Frontend Framework' },
    { name: 'React', icon: '/icon/it/react-js.png', desc: 'UI Component Library' },
    { name: 'Laravel', icon: '/icon/it/laravel.webp', desc: 'Secure Backend Engine' },
    { name: 'MySQL', icon: '/icon/it/mySql.png', desc: 'Relational Database' },
    { name: 'PostgreSQL', icon: '/icon/it/Postgresql_elephant.svg.webp', desc: 'Enterprise Database' }
]

const multimediaTechStack = [
    { name: 'Figma', icon: '/icon/multimedia/figma.jpeg', desc: 'Collaborative UI/UX' },
    { name: 'Adobe Photoshop', icon: '/icon/multimedia/adobe-photoshop.webp', desc: 'Creative Image Editing' },
    { name: 'Canva', icon: '/icon/multimedia/canva.png', desc: 'Design & Asset Creation' },
    { name: 'CapCut', icon: '/icon/multimedia/capcut.png', desc: 'Dynamic Video Editing' }
]
</script>

<template>
    <main class="relative bg-[#FAF9F6] text-slate-800 font-['Quicksand'] z-0 overflow-x-hidden pt-20">
        
        <!-- Background accents -->
        <div class="absolute top-20 left-0 w-full h-[600px] bg-gradient-to-b from-red-50/40 via-transparent to-transparent -z-10 pointer-events-none"></div>

        <!-- HERO SECTION -->
        <section class="relative pt-24 pb-20 md:pt-36 md:pb-28">
            <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 text-center relative z-10">
                
                <!-- Badge -->
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-red-50 border border-red-100 text-red-600 text-xs font-bold uppercase tracking-wider mb-8 shadow-sm font-['Montserrat']">
                    <span class="flex h-2 w-2 relative">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-red-600"></span>
                    </span>
                    {{ t('service_view.it_multimedia.badge') }}
                </div>

                <!-- Main Title -->
                <h1 class="text-4xl sm:text-5xl lg:text-7xl font-black tracking-tight leading-[1.1] mb-8 uppercase text-slate-900 font-['Montserrat']">
                    {{ t('service_view.it_multimedia.title') }} <br />
                    <span class="text-red-600">
                        {{ t('service_view.it_multimedia.title_highlight') }}
                    </span>
                </h1>

                <!-- Description -->
                <p class="text-lg md:text-xl text-slate-600 max-w-3xl mx-auto leading-relaxed mb-12 font-medium">
                    {{ t('service_view.it_multimedia.desc') }}
                </p>

                <!-- Navigation buttons -->
                <div class="flex flex-wrap justify-center gap-4">
                    <a href="#website-perisai" class="px-8 py-4 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl shadow-lg shadow-red-600/20 transition-all duration-300 hover:-translate-y-0.5 active:scale-95 font-['Montserrat'] text-sm tracking-wide">
                        Website Perisai
                    </a>
                    <a href="#multimedia-content" class="px-8 py-4 bg-white hover:bg-slate-50 text-slate-800 font-bold rounded-xl border border-slate-200 transition-all duration-300 hover:-translate-y-0.5 active:scale-95 shadow-sm font-['Montserrat'] text-sm tracking-wide">
                        Multimedia Content
                    </a>
                </div>
            </div>
        </section>

        <!-- SECTION 1: WEBSITE PERISAI -->
        <section id="website-perisai" class="py-20 md:py-28 relative border-t border-slate-100 bg-white">
            <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12">
                <div class="grid lg:grid-cols-[1.1fr,1fr] gap-16 lg:gap-24 items-center">
                    
                    <!-- Left: Content Card -->
                    <div class="space-y-8">
                        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-md bg-red-50 border border-red-100 text-red-600 text-xs font-black uppercase tracking-wider font-['Montserrat']">
                            Legislative System
                        </div>
                        <h2 class="text-3xl md:text-4xl lg:text-5xl font-extrabold tracking-tight leading-tight text-slate-900 uppercase font-['Montserrat']">
                            {{ t('service_view.it_multimedia.services.website_perisai.title') }}
                        </h2>
                        <p class="text-base md:text-lg text-slate-600 leading-relaxed font-medium">
                            {{ t('service_view.it_multimedia.services.website_perisai.desc') }}
                        </p>
                        
                        <!-- List of key features -->
                        <div class="grid sm:grid-cols-2 gap-4 pt-4 border-t border-slate-100">
                            <div class="flex items-start gap-3">
                                <span class="text-red-600 font-bold">✔</span>
                                <span class="text-sm font-bold text-slate-700">{{ t('service_view.it_multimedia.services.website_perisai.feat1') }}</span>
                            </div>
                            <div class="flex items-start gap-3">
                                <span class="text-red-600 font-bold">✔</span>
                                <span class="text-sm font-bold text-slate-700">{{ t('service_view.it_multimedia.services.website_perisai.feat2') }}</span>
                            </div>
                            <div class="flex items-start gap-3">
                                <span class="text-red-600 font-bold">✔</span>
                                <span class="text-sm font-bold text-slate-700">{{ t('service_view.it_multimedia.services.website_perisai.feat3') }}</span>
                            </div>
                            <div class="flex items-start gap-3">
                                <span class="text-red-600 font-bold">✔</span>
                                <span class="text-sm font-bold text-slate-700">{{ t('service_view.it_multimedia.services.website_perisai.feat4') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- TECHNOLOGY & TOOLS SECTION -->
        <section class="py-20 md:py-28 relative border-t border-slate-100 bg-white">
            <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12">
                
                <header class="text-center max-w-3xl mx-auto mb-16">
                    <h2 class="text-3xl md:text-5xl font-black uppercase tracking-tight text-slate-900 mb-6 font-['Montserrat']">
                        {{ t('service_view.it_multimedia.tech_stack.title') }}
                    </h2>
                    <p class="text-slate-600 font-medium text-base md:text-lg leading-relaxed">
                        {{ t('service_view.it_multimedia.tech_stack.subtitle') }}
                    </p>
                </header>

                <div class="grid lg:grid-cols-2 gap-12 max-w-6xl mx-auto">
                    
                    <!-- Sub-category: IT Development -->
                    <div class="bg-[#FAF9F6] rounded-3xl border border-slate-200 p-8 md:p-10 shadow-sm relative overflow-hidden group">
                        
                        <div class="flex items-center gap-3 mb-8">
                            <span class="text-red-600 font-bold text-xl font-mono">/01</span>
                            <h3 class="text-slate-900 font-extrabold text-lg tracking-tight uppercase font-['Montserrat']">IT & Web Systems</h3>
                        </div>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div v-for="tech in itTechStack" :key="tech.name" class="flex items-center gap-4 bg-white p-4 rounded-2xl border border-slate-200 transition-all duration-300 hover:translate-y-[-2px] hover:shadow-md">
                                <div class="w-12 h-12 flex-shrink-0 rounded-xl bg-white border border-slate-100 flex items-center justify-center p-2.5 overflow-hidden shadow-sm">
                                    <img :src="tech.icon" :alt="tech.name" class="w-full h-full object-contain" />
                                </div>
                                <div class="min-w-0">
                                    <h4 class="font-extrabold text-sm text-slate-800 truncate font-['Montserrat']">{{ tech.name }}</h4>
                                    <p class="text-[10px] text-slate-500 font-semibold truncate mt-0.5">{{ tech.desc }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sub-category: Multimedia & Design -->
                    <div class="bg-[#FAF9F6] rounded-3xl border border-slate-200 p-8 md:p-10 shadow-sm relative overflow-hidden group">

                        <div class="flex items-center gap-3 mb-8">
                            <span class="text-red-600 font-bold text-xl font-mono">/02</span>
                            <h3 class="text-slate-900 font-extrabold text-lg tracking-tight uppercase font-['Montserrat']">Multimedia Tools</h3>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div v-for="tech in multimediaTechStack" :key="tech.name" class="flex items-center gap-4 bg-white p-4 rounded-2xl border border-slate-200 transition-all duration-300 hover:translate-y-[-2px] hover:shadow-md">
                                <div class="w-12 h-12 flex-shrink-0 rounded-xl bg-white border border-slate-100 flex items-center justify-center p-2.5 overflow-hidden shadow-sm">
                                    <img :src="tech.icon" :alt="tech.name" class="w-full h-full object-contain" />
                                </div>
                                <div class="min-w-0">
                                    <h4 class="font-extrabold text-sm text-slate-800 truncate font-['Montserrat']">{{ tech.name }}</h4>
                                    <p class="text-[10px] text-slate-500 font-semibold truncate mt-0.5">{{ tech.desc }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </section>

        <!-- CALL TO ACTION (CTA) -->
        <section class="py-24 md:py-32 relative border-t border-slate-100 bg-[#FAF9F6]">
            
            <div class="max-w-4xl mx-auto px-6 text-center space-y-8">
                <h2 class="text-3xl md:text-5xl font-black uppercase tracking-tight text-slate-900 leading-tight font-['Montserrat']">
                    {{ t('service_view.it_multimedia.cta.title') }}
                </h2>
                <p class="text-base md:text-lg text-slate-600 max-w-2xl mx-auto font-medium leading-relaxed">
                    {{ t('service_view.it_multimedia.cta.desc') }}
                </p>
                <div class="pt-4">
                    <a href="https://wa.me/62811192534" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-3 bg-red-600 hover:bg-red-700 text-white px-8 py-4 rounded-xl font-bold text-sm shadow-lg shadow-red-600/20 transition-all duration-300 hover:-translate-y-1 active:scale-95 font-['Montserrat'] tracking-wide">
                        <span>💬 {{ t('service_view.it_multimedia.cta.button') }}</span>
                    </a>
                </div>
            </div>
        </section>

    </main>
</template>

<style scoped>
/* Transisi and custom fonts fallback */
.font-montserrat {
    font-family: 'Montserrat', sans-serif;
}
.font-quicksand {
    font-family: 'Quicksand', sans-serif;
}
</style>
