<template>
    <main id="home" class="relative mt-3">
        <section class="max-w-7xl mx-auto px-4 pt-13 pb-32">
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

                    <p class="mt-8 text-sm md:text-lg text-slate-600 tracking-widest leading-relaxed max-w-xl">
                        {{ $t('home.description') }}
                    </p>

                    <div class="mt-5 mb-7 flex flex-col sm:flex-row gap-4">
                        <a href="#our-services"
                            class="relative inline-block px-10 py-4 rounded-xl font-bold text-white bg-gradient-to-r from-brand to-orange-500 hover:shadow-[0_10px_20px_rgba(234,68,53,0.3)] hover:scale-[1.02] active:scale-95 border border-transparent transition-all duration-300">
                            {{ $t('home.button_service') }}
                        </a>
                    </div>
                </div>

                <div
                    class="hidden lg:flex justify-center items-center relative min-h-[600px] w-full overflow-visible py-10">

                    <div
                        class="absolute w-[600px] h-[600px] bg-[radial-gradient(circle,_var(--tw-gradient-stops))] from-[#ea4435]/10 via-transparent to-transparent blur-[100px] -z-10">
                    </div>

                    <div class="relative group">

                        <div
                            class="absolute -top-6 -left-6 w-16 h-16 bg-white/60 backdrop-blur-xl rounded-full border border-white shadow-xl z-30 transition-all duration-500 group-hover:-translate-y-2 group-hover:-translate-x-1 flex items-center justify-center">
                            <div class="w-2 h-2 bg-[#ea4435] rounded-full"></div>
                        </div>

                        <div
                            class="absolute -bottom-8 -right-8 w-24 h-24 bg-gradient-to-br from-white/80 to-white/20 backdrop-blur-lg rounded-full border border-white/50 shadow-lg z-30 transition-all duration-700 group-hover:scale-110">
                        </div>

                        <div
                            class="w-[450px] h-[450px] bg-white rounded-[2.5rem] border border-slate-200/50 relative overflow-hidden flex items-center justify-center shadow-[0_40px_80px_-15px_rgba(0,0,0,0.1)] z-20">
                            <img src="/src/img/slide-gallery-17.jpg" alt="PT Cakrawala Project"
                                class="absolute inset-0 w-full h-full object-cover transition-transform duration-1000 group-hover:scale-105">
                            <div
                                class="absolute inset-0 bg-gradient-to-tr from-[#ea4435]/5 via-transparent to-transparent">
                            </div>
                        </div>

                        <div
                            class="absolute -inset-4 border border-slate-200/20 rounded-[3rem] -z-10 transition-transform duration-700 group-hover:scale-105">
                        </div>
                    </div>
                </div>

            </div>
        </section>
    </main>
</template>