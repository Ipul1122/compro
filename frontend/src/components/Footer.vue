<script setup>
import { useI18n } from 'vue-i18n'

const { t } = useI18n()
</script>

<template>
    <footer id="contact" class="bg-slate-950 border-t border-slate-900 pt-20 pb-10 relative overflow-hidden">
        <div class="absolute bottom-0 right-0 w-[300px] h-[300px] bg-brand/5 blur-[120px] rounded-full -z-0">
        </div>

        <div class="max-w-7xl mx-auto px-4 relative z-10">
            <div class="grid lg:grid-cols-2 gap-16 mb-16">

                <div class="flex flex-col justify-between">
                    <div>
                        <h2 class="text-5xl md:text-7xl font-black text-white leading-none tracking-tighter">
                            {{ t('footer.now_taking') }}<br />
                            <span class="text-brand">{{ t('footer.bookings') }}</span>
                        </h2>
                        <p class="mt-6 text-slate-500 max-w-sm">
                            {{ t('footer.description') }}
                        </p>
                    </div>

                    <div class="mt-10">
                        <router-link to="/contact">
                            <button
                                class="group flex items-center gap-3 px-8 py-4 bg-white text-black rounded-full font-bold hover:bg-brand hover:text-white transition-all duration-300">
                                {{ t('footer.contact_us') }}
                                <span class="group-hover:translate-x-1 transition-transform">→</span>
                            </button>
                        </router-link>
                    </div>
                </div>

                <div class="space-y-10">
                    <div>
                        <h3 class="text-white font-bold mb-4 uppercase tracking-widest text-sm">
                            {{ t('footer.stay_connected') }}
                        </h3>
                        <p class="text-slate-400 leading-relaxed mb-6">
                            {{ t('footer.social_desc_part1') }} <span class="text-slate-200 font-semibold">PT
                                Cakrawala
                                Parama Internasional</span> {{ t('footer.social_desc_part2') }}
                        </p>

                        <div class="flex gap-4">
                            <a href="#"
                                class="w-12 h-12 rounded-full border border-slate-800 flex items-center justify-center text-slate-400 hover:border-brand hover:text-brand transition-all">
                                <span class="text-xs font-bold">FB</span>
                            </a>
                            <a href="#"
                                class="w-12 h-12 rounded-full border border-slate-800 flex items-center justify-center text-slate-400 hover:border-brand hover:text-brand transition-all">
                                <span class="text-xs font-bold">IG</span>
                            </a>
                            <a href="#"
                                class="w-12 h-12 rounded-full border border-slate-800 flex items-center justify-center text-slate-400 hover:border-brand hover:text-brand transition-all">
                                <span class="text-xs font-bold">YT</span>
                            </a>
                            <a href="#"
                                class="w-12 h-12 rounded-full border border-slate-800 flex items-center justify-center text-slate-400 hover:border-brand hover:text-brand transition-all">
                                <span class="text-xs font-bold">IN</span>
                            </a>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-white font-bold mb-4 uppercase tracking-widest text-sm">
                            {{ t('footer.headquarters') }}
                        </h3>
                        <address class="not-italic text-slate-500 text-sm leading-loose">
                            South Quarter Tower A, Lt 18<br />
                            Jl. R.A. Kartini No.Kav 8, Kec. Cilandak<br />
                            Daerah Khusus Ibukota Jakarta 12430
                        </address>
                    </div>
                </div>
            </div>

            <div class="pt-8 border-t border-slate-900 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-slate-600 text-xs uppercase tracking-widest">
                    &copy; 2026 PT Cakrawala Parama Internasional. {{ t('footer.all_rights_reserved') }}
                </p>
                <div class="flex gap-6 text-xs text-slate-600 uppercase tracking-widest">
                    <a href="#" class="hover:text-brand transition-colors">{{ t('footer.privacy_policy') }}</a>
                    <a href="#" class="hover:text-brand transition-colors">{{ t('footer.terms_of_service') }}</a>
                </div>
            </div>
        </div>
    </footer>
</template>

<style scoped></style>