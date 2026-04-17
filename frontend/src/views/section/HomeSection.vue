<template>
    <main id="home" class="relative mt-3">
        <section class="max-w-7xl mx-auto px-4 pt-24 pb-32">
            <div class="grid lg:grid-cols-2 gap-12 items-center">

                <div class="text-left relative z-10">
                    <h1 class="relative z-10 flex flex-col gap-2">
                        <span
                            class="text-brand font-black uppercase tracking-[0.4em] text-[10px] md:text-xs mb-2 flex items-center gap-3">
                            <span class="w-8 h-px bg-brand/50"></span>
                            {{ $t('home.subtitle') }}
                        </span>

                        <span class="text-4xl md:text-6xl font-bold tracking-tight text-slate-900 leading-tight">
                            PT <span class="font-light italic text-slate-500">Cakrawala</span>
                            <br />
                            <span
                                class="bg-gradient-to-r from-slate-900 via-slate-800 to-brand bg-clip-text text-transparent">
                                Parama Internasional
                            </span>
                        </span>

                        <div class="mt-4 w-20 h-1 bg-gradient-to-r from-brand to-transparent rounded-full"></div>
                    </h1>

                    <p
                        class="mt-8 text-sm md:text-lg text-slate-600 uppercase tracking-widest leading-relaxed max-w-xl">
                        {{ $t('home.description') }}
                    </p>

                    <div class="mt-5 mb-7 flex flex-col sm:flex-row gap-4">
                        <a href="#our-services"
                            class="relative inline-block px-10 py-4 rounded-xl font-bold text-white bg-gradient-to-r from-brand to-orange-500 hover:shadow-[0_10px_20px_rgba(234,68,53,0.3)] hover:scale-[1.02] active:scale-95 border border-transparent transition-all duration-300">
                            {{ $t('home.button_service') }}
                        </a>
                    </div>
                </div>

                <div class="hidden lg:flex justify-center relative">
                    <div
                        class="w-full aspect-square max-w-lg bg-slate-50 backdrop-blur-sm rounded-3xl border border-slate-200 relative overflow-hidden flex items-center justify-center group shadow-2xl">

                        <img src="/src/img/slide-gallery-17.jpg" alt="PT Cakrawala Project"
                            class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">

                        <div
                            class="absolute inset-0 bg-gradient-to-tr from-brand/20 via-transparent to-transparent opacity-60 group-hover:opacity-100 transition-opacity z-10">
                        </div>

                        <div
                            class="absolute inset-0 flex items-center justify-center z-20 opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                        </div>
                    </div>

                    <div
                        class="absolute -top-10 -right-10 w-72 h-72 bg-brand/10 blur-[100px] rounded-full animate-pulse -z-10">
                    </div>
                </div>

            </div>
        </section>
    </main>
</template>