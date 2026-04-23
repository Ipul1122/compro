<script setup>
import { useI18n } from 'vue-i18n'

const { t } = useI18n()
</script>

<template>
    <footer id="contact" class="bg-slate-950 border-t border-slate-900 pt-16 pb-8 relative overflow-hidden">
        <div class="absolute bottom-0 right-0 w-[300px] h-[300px] bg-brand/5 blur-[120px] rounded-full -z-0"></div>

        <div class="max-w-7xl mx-auto px-4 relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-16">
                
                <div class="flex flex-col space-y-6">
                    <img src="/webcakrawala-logo.png" alt="PT Cakrawala Parama Internasional" class="h-14 object-contain w-fit" />
                    <p class="text-slate-400 text-sm leading-relaxed">
                        {{ t('footer.about_desc') }}
                    </p>
                </div>

                <div>
                    <h3 class="text-white font-bold mb-6 uppercase tracking-widest text-sm">{{ t('footer.nav_title') }}</h3>
                    <ul class="space-y-3 text-slate-400 text-sm font-medium">
                        <li>
                            <router-link to="/" class="hover:text-brand hover:translate-x-1 inline-block transition-all duration-300">{{ t('footer.nav_home') }}</router-link>
                        </li>
                        <li>
                            <router-link to="/about" class="hover:text-brand hover:translate-x-1 inline-block transition-all duration-300">{{ t('footer.nav_about') }}</router-link>
                        </li>
                        <li>
                            <router-link to="/service" class="hover:text-brand hover:translate-x-1 inline-block transition-all duration-300">{{ t('footer.nav_services') }}</router-link>
                        </li>
                        <li>
                            <router-link to="/project" class="hover:text-brand hover:translate-x-1 inline-block transition-all duration-300">{{ t('footer.nav_projects') }}</router-link>
                        </li>
                        <li>
                            <router-link to="/contact" class="hover:text-brand hover:translate-x-1 inline-block transition-all duration-300">{{ t('footer.nav_contact') }}</router-link>
                        </li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-white font-bold mb-6 uppercase tracking-widest text-sm">{{ t('footer.stay_connected') }}</h3>
                    <p class="text-slate-400 text-sm leading-relaxed mb-6">
                        {{ t('footer.social_desc') }}
                    </p>
                    <div class="flex gap-4">
                        <a href="#" class="w-10 h-10 rounded-full border border-slate-800 flex items-center justify-center text-slate-400 hover:bg-brand hover:border-brand hover:text-white transition-all duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-full border border-slate-800 flex items-center justify-center text-slate-400 hover:bg-brand hover:border-brand hover:text-white transition-all duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-full border border-slate-800 flex items-center justify-center text-slate-400 hover:bg-brand hover:border-brand hover:text-white transition-all duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path><rect x="2" y="9" width="4" height="12"></rect><circle cx="4" cy="4" r="2"></circle></svg>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-full border border-slate-800 flex items-center justify-center text-slate-400 hover:bg-brand hover:border-brand hover:text-white transition-all duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33 2.78 2.78 0 0 0 1.94 2C5.12 19.5 12 19.5 12 19.5s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.33 29 29 0 0 0-.46-5.33z"></path><polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02"></polygon></svg>
                        </a>
                    </div>
                </div>

                <div class="flex flex-col space-y-4">
                    <h3 class="text-white font-bold mb-2 uppercase tracking-widest text-sm">{{ t('footer.headquarters') }}</h3>
                    <address class="not-italic text-slate-400 text-sm leading-relaxed">
                        Thamrin City, Jl. Kebon Kacang Raya Lantai 2 Blok C9a No.5 Kb. Melati, Kecamatan Tanah Abang<br>
                        Kota Jakarta Pusat, Daerah Khusus Ibukota Jakarta 10230
                    </address>
                    
                    <div class="w-full h-36 mt-2 rounded-lg overflow-hidden border border-slate-800 shadow-lg">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3331.261420632077!2d106.81342307413082!3d-6.194213560682781!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f75e2156f8c5%3A0x1e2ee1b85d99116a!2sMALL%20THAMRIN%20CITY!5e1!3m2!1sid!2sid!4v1776919312048!5m2!1sid!2sid" width="400" height="300" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
            </div>

            <div class="pt-8 border-t border-slate-900 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-slate-500 text-xs font-medium tracking-wide">
                    {{ t('footer.copyright') }}
                </p>
                <div class="flex gap-6 text-xs text-slate-500 font-medium tracking-wide">
                    <a href="#" class="hover:text-brand transition-colors">{{ t('footer.privacy_policy') }}</a>
                    <a href="#" class="hover:text-brand transition-colors">{{ t('footer.terms_of_service') }}</a>
                </div>
            </div>
        </div>
    </footer>
</template>

<style scoped></style>