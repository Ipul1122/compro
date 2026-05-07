<template>
    <main id="home" class="relative mt-3 overflow-hidden">

        <!-- Grid Background -->
        <div class="absolute inset-0 -z-20">
            <div class="absolute inset-0"
                style="background-image: linear-gradient(rgba(234,68,53,0.06) 1px, transparent 1px), linear-gradient(90deg, rgba(234,68,53,0.06) 1px, transparent 1px); background-size: 48px 48px;">
            </div>
            <div class="absolute inset-0 bg-gradient-to-br from-white via-white/95 to-red-50/60"></div>
        </div>

        <!-- Glowing blobs -->
        <div class="absolute top-0 right-0 w-[700px] h-[700px] -z-10 pointer-events-none">
            <div class="absolute top-[-100px] right-[-150px] w-[500px] h-[500px] rounded-full bg-red-500/10 blur-[120px]"></div>
        </div>
        <div class="absolute bottom-0 left-0 w-[400px] h-[400px] -z-10 pointer-events-none">
            <div class="absolute bottom-[-80px] left-[-80px] w-[300px] h-[300px] rounded-full bg-red-400/8 blur-[100px]"></div>
        </div>

        <!-- Decorative corner accent -->
        <div class="absolute top-0 left-0 w-64 h-64 -z-10 pointer-events-none overflow-hidden">
            <div class="absolute -top-16 -left-16 w-64 h-64 border border-red-500/10 rounded-full"></div>
            <div class="absolute -top-8 -left-8 w-40 h-40 border border-red-500/15 rounded-full"></div>
        </div>

        <section class="max-w-7xl mx-auto px-4 pt-16 pb-32">
            <div class="grid lg:grid-cols-2 gap-16 items-center">

                <!-- Left Content -->
                <div class="text-left relative z-10">

                    <!-- Badge -->
                    <div class="inline-flex items-center gap-3 mb-6">
                        <span class="relative flex items-center gap-2 px-4 py-1.5 rounded-full border border-red-500/20 bg-red-500/5 backdrop-blur-sm">
                            <span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse"></span>
                            <span class="text-red-600 font-black uppercase tracking-[0.35em] text-[9px] md:text-[10px]">
                                {{ $t('home.subtitle') }}
                            </span>
                        </span>
                    </div>

                    <!-- Heading -->
                    <h1 class="font-montserrat flex flex-col gap-1">
                        <span class="text-3xl md:text-5xl font-bold tracking-tight text-slate-900 leading-tight">
                            PT <span class="bg-gradient-to-r from-red-600 via-red-500 to-amber-400 bg-clip-text text-transparent">
                            Cakrawala</span>
                        </span>
                        <span class="text-3xl md:text-5xl font-bold tracking-tight leading-tight relative">
                            <span class="relative inline-block">
                                <span class="bg-gradient-to-r from-red-600 via-red-500 to-amber-400 bg-clip-text text-transparent">
                                    Parama
                                </span>
                                <!-- Underline accent -->
                                <span class="absolute -bottom-1 left-0 w-full h-[3px] bg-gradient-to-r from-red-500 to-amber-400 rounded-full opacity-60"></span>
                            </span>
                            <span class="text-slate-800 ml-3">Internasional</span>
                        </span>
                    </h1>

                    <!-- Divider line -->
                    <div class="mt-6 flex items-center gap-3">
                        <div class="w-12 h-[2px] bg-red-500 rounded-full"></div>
                        <div class="w-3 h-[2px] bg-red-300 rounded-full"></div>
                        <div class="w-1.5 h-[2px] bg-red-200 rounded-full"></div>
                    </div>

                    <!-- Description -->
                    <p class="font-quicksand mt-7 text-sm md:text-base text-slate-500 tracking-wide leading-relaxed max-w-xl">
                        {{ $t('home.description') }}
                    </p>

                    <!-- Stats strip -->
                    <div class="mt-8 flex items-center gap-6">
                        <div class="flex flex-col">
                            <span class="text-2xl font-black text-slate-900">20<span class="text-red-500">+</span></span>
                            <span class="text-[10px] uppercase tracking-widest text-slate-400">Years</span>
                        </div>
                        <div class="w-px h-10 bg-slate-200"></div>
                        <div class="flex flex-col">
                            <span class="text-2xl font-black text-slate-900">500<span class="text-red-500">+</span></span>
                            <span class="text-[10px] uppercase tracking-widest text-slate-400">Projects</span>
                        </div>
                        <div class="w-px h-10 bg-slate-200"></div>
                        <div class="flex flex-col">
                            <span class="text-2xl font-black text-slate-900">100<span class="text-red-500">+</span></span>
                            <span class="text-[10px] uppercase tracking-widest text-slate-400">Clients</span>
                        </div>
                    </div>

                    <!-- CTA Button -->
                    <div class="mt-8 flex flex-col sm:flex-row gap-4">
                        <a href="#our-services"
                            class="group relative inline-flex items-center gap-3 px-10 py-4 rounded-xl font-bold text-white overflow-hidden transition-all duration-300 hover:shadow-[0_16px_40px_rgba(234,68,53,0.35)] hover:scale-[1.02] active:scale-95">
                            <!-- Button BG -->
                            <span class="absolute inset-0 bg-gradient-to-r from-red-600 via-red-500 to-amber-500 transition-all duration-300"></span>
                            <span class="absolute inset-0 bg-gradient-to-r from-red-500 to-red-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                            <!-- Grid texture on button -->
                            <span class="absolute inset-0 opacity-[0.07]"
                                style="background-image: linear-gradient(white 1px, transparent 1px), linear-gradient(90deg, white 1px, transparent 1px); background-size: 12px 12px;"></span>
                            <span class="relative z-10">{{ $t('home.button_service') }}</span>
                            <svg class="relative z-10 w-4 h-4 transition-transform duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Right: Image Panel -->
                <div class="hidden lg:flex justify-center items-center relative min-h-[600px] w-full overflow-visible py-10">

                    <!-- Background glow -->
                    <div class="absolute w-[580px] h-[580px] bg-[radial-gradient(circle,_var(--tw-gradient-stops))] from-red-500/12 via-red-500/4 to-transparent blur-[80px] -z-10"></div>

                    <!-- Outer grid decorative frame -->
                    <div class="absolute inset-0 flex items-center justify-center pointer-events-none -z-5">
                        <div class="w-[500px] h-[500px] border border-red-500/8 rounded-[3rem] rotate-3"></div>
                        <div class="absolute w-[480px] h-[480px] border border-red-500/5 rounded-[3rem] -rotate-2"></div>
                    </div>

                    <div class="relative group">

                        <!-- Floating top-left orb -->
                        <div class="absolute -top-8 -left-8 w-16 h-16 bg-white/80 backdrop-blur-xl rounded-2xl border border-red-100 shadow-xl z-30 transition-all duration-500 group-hover:-translate-y-3 group-hover:-translate-x-1 flex items-center justify-center rotate-12 group-hover:rotate-6">
                            <div class="w-6 h-6 rounded-lg bg-gradient-to-br from-red-500 to-amber-400 flex items-center justify-center">
                                <div class="w-2 h-2 bg-white rounded-sm"></div>
                            </div>
                        </div>

                        <!-- Floating bottom-right orb -->
                        <div class="absolute -bottom-6 -right-6 w-20 h-20 bg-gradient-to-br from-red-500/15 to-amber-400/10 backdrop-blur-lg rounded-2xl border border-red-200/40 shadow-lg z-30 transition-all duration-700 group-hover:scale-110 group-hover:translate-y-2 -rotate-6">
                            <div class="absolute top-3 left-3 w-2 h-2 rounded-full bg-red-400/60"></div>
                            <div class="absolute bottom-3 right-3 w-1.5 h-1.5 rounded-full bg-amber-400/60"></div>
                        </div>

                        <!-- Floating top-right small dot -->
                        <div class="absolute -top-3 right-12 w-8 h-8 rounded-full bg-red-500/10 border border-red-300/30 z-30 transition-all duration-700 group-hover:-translate-y-2 group-hover:translate-x-1 backdrop-blur-sm"></div>

                        <!-- Image card -->
                        <div class="w-[440px] h-[460px] bg-white rounded-[2.5rem] border border-slate-100 relative overflow-hidden flex items-center justify-center shadow-[0_40px_80px_-15px_rgba(0,0,0,0.12),0_0_0_1px_rgba(234,68,53,0.06)] z-20 transition-all duration-700 group-hover:shadow-[0_60px_100px_-15px_rgba(234,68,53,0.18)]">

                            <img src="/src/img/phising.jpg" alt="PT Cakrawala Project"
                                class="absolute inset-0 w-full h-full object-cover transition-transform duration-1000 group-hover:scale-105">

                            <!-- Overlay gradient -->
                            <div class="absolute inset-0 bg-gradient-to-tr from-red-600/10 via-transparent to-amber-400/5"></div>

                            <!-- Bottom info bar -->
                            <div class="absolute bottom-0 left-0 right-0 p-5 bg-gradient-to-t from-black/40 to-transparent z-10">
                                <div class="flex items-center gap-2">
                                    <div class="w-2 h-2 rounded-full bg-green-600 animate-pulse"></div>
                                    <span class="text-white/80 text-xs font-medium tracking-widest uppercase">Active Projects</span>
                                </div>
                            </div>

                            <!-- Grid overlay subtle -->
                            <div class="absolute inset-0 opacity-[0.03]"
                                style="background-image: linear-gradient(white 1px, transparent 1px), linear-gradient(90deg, white 1px, transparent 1px); background-size: 24px 24px;">
                            </div>
                        </div>

                        <!-- Outer ring -->
                        <div class="absolute -inset-6 border border-red-500/8 rounded-[3.5rem] -z-10 transition-transform duration-700 group-hover:scale-105"></div>
                        <div class="absolute -inset-12 border border-red-500/4 rounded-[4rem] -z-10 transition-transform duration-700 group-hover:scale-102"></div>
                    </div>
                </div>

            </div>
        </section>
    </main>
</template>