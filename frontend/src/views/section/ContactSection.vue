<script setup>
import { ref } from 'vue'
import { useI18n } from 'vue-i18n'

const { t } = useI18n()

const form = ref({
    nama: '',
    email: '',
    pesan: ''
})

const sendToWhatsApp = () => {
    const waNumber = '6285284201428'
    const message = t('contact_section.whatsapp_greeting', {
        name: form.value.nama,
        email: form.value.email,
        message: form.value.pesan
    })
    
    window.open(`https://wa.me/${waNumber}?text=${message}`, '_blank')
}
</script>

<template>
    <section id="contact" class="contact-section py-28 font-montserrat relative overflow-hidden">

        <div class="blob blob-1"></div>
        <div class="blob blob-2"></div>
        <div class="blob blob-3"></div>

        <div class="grid-overlay"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

            <div class="text-center mb-20">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full badge-pill mb-6">
                    <span class="dot-pulse"></span>
                    <span class="text-xs font-black uppercase tracking-[0.2em] text-orange-600">
                        {{ t('contact_section.badge') }}
                    </span>
                </div>
                <h2 class="text-4xl md:text-6xl font-black text-slate-900 mb-5 tracking-tight leading-none">
                    <span class="block text-orange-500 relative inline-block mt-1">
                        {{ t('contact_section.title') }}
                        <svg class="underline-svg" viewBox="0 0 300 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M2 9C60 3 120 1 180 5C220 8 260 10 298 8" stroke="#f97316" stroke-width="3.5" stroke-linecap="round"/>
                        </svg>
                    </span>
                </h2>
                <p class="text-slate-500 max-w-xl mx-auto font-medium text-base leading-relaxed">
                    {{ t('contact_section.description') }}
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-stretch">

                <div class="lg:col-span-7 h-[420px] lg:h-auto min-h-[480px]">
                    <div class="map-card w-full h-full rounded-[2rem] overflow-hidden relative group">
                        <div class="corner-accent top-4 left-4"></div>
                        <div class="corner-accent bottom-4 right-4 rotate-180"></div>

                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3331.261420632077!2d106.81342307413082!3d-6.194213560682781!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f75e2156f8c5%3A0x1e2ee1b85d99116a!2sMALL%20THAMRIN%20CITY!5e1!3m2!1sid!2sid!4v1776912747213!5m2!1sid!2sid"
                            width="100%" height="100%"
                            style="border:0; filter: saturate(1.1) contrast(1.05);"
                            allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                        </iframe>

                        <div class="location-badge absolute bottom-6 left-6 right-6">
                            <div class="flex items-center gap-3">
                                <div class="loc-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-black text-slate-900">{{ t('contact_section.company_name') }}</p>
                                    <p class="text-xs text-slate-500 mt-0.5">{{ t('contact_section.address') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-5">
                    <div class="form-card rounded-[2rem] p-8 md:p-10 h-full flex flex-col justify-center">

                        <div class="mb-8">
                            <h3 class="text-xl font-black text-slate-900 mb-1">{{ t('contact_section.form_title') }}</h3>
                            <p class="text-xs text-slate-400 font-medium">{{ t('contact_section.form_subtitle') }}</p>
                        </div>

                        <form @submit.prevent="sendToWhatsApp" class="space-y-5">

                            <div class="input-group">
                                <label class="input-label">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/>
                                    </svg>
                                    {{ t('contact_section.form.name_label') }}
                                </label>
                                <div class="input-wrapper">
                                    <input
                                        v-model="form.nama"
                                        type="text"
                                        required
                                        :placeholder="t('contact_section.form.name_placeholder')"
                                        class="styled-input"
                                    />
                                    <div class="input-line"></div>
                                </div>
                            </div>

                            <div class="input-group">
                                <label class="input-label">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                                    </svg>
                                    {{ t('contact_section.form.email_label') }}
                                </label>
                                <div class="input-wrapper">
                                    <input
                                        v-model="form.email"
                                        type="email"
                                        required
                                        :placeholder="t('contact_section.form.email_placeholder')"
                                        class="styled-input"
                                    />
                                    <div class="input-line"></div>
                                </div>
                            </div>

                            <div class="input-group">
                                <label class="input-label">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"/>
                                    </svg>
                                    {{ t('contact_section.form.message_label') }}
                                </label>
                                <div class="input-wrapper">
                                    <textarea
                                        v-model="form.pesan"
                                        required
                                        rows="4"
                                        :placeholder="t('contact_section.form.message_placeholder')"
                                        class="styled-input resize-none"
                                    ></textarea>
                                    <div class="input-line"></div>
                                </div>
                            </div>

                            <button type="submit" class="wa-button group">
                                <span class="wa-button-bg"></span>
                                <span class="wa-button-content">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                    </svg>
                                    <span class="font-black tracking-wide">{{ t('contact_section.form.submit') }}</span>
                                    <span class="arrow-icon">→</span>
                                </span>
                            </button>

                        </form>

                        <div class="mt-8 pt-7 border-t border-slate-100">
                            <div class="flex items-center gap-4">
                                <div class="phone-icon-wrap">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">{{ t('contact_section.info.title') }}</p>
                                    <p class="text-sm font-black text-slate-800 mt-0.5">0852-8420-1428</p>
                                </div>
                                <div class="available-tag ml-auto">
                                    <span class="dot-green"></span>
                                    <span class="text-[10px] font-bold text-green-700">{{ t('contact_section.info.status') }}</span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
/* Seluruh blok CSS/Style tidak berubah, dibiarkan seperti file aslinya */
@import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&display=swap');

/* ─── Base ─── */
.contact-section {
    background: #fff8f5;
    font-family: 'Montserrat', sans-serif;
}
.font-montserrat { font-family: 'Montserrat', sans-serif; }

/* ─── Blobs ─── */
.blob {
    position: absolute;
    border-radius: 50%;
    filter: blur(80px);
    pointer-events: none;
    z-index: 0;
    animation: blobFloat 8s ease-in-out infinite;
}
.blob-1 {
    width: 500px; height: 500px;
    background: rgba(249, 115, 22, 0.12);
    top: -100px; right: -100px;
    animation-delay: 0s;
}
.blob-2 {
    width: 350px; height: 350px;
    background: rgba(249, 115, 22, 0.07);
    bottom: 0; left: -80px;
    animation-delay: -3s;
}
.blob-3 {
    width: 200px; height: 200px;
    background: rgba(249, 115, 22, 0.1);
    top: 40%; left: 40%;
    animation-delay: -5s;
}
@keyframes blobFloat {
    0%, 100% { transform: translate(0, 0) scale(1); }
    50% { transform: translate(15px, -20px) scale(1.04); }
}

/* ─── Grid overlay ─── */
.grid-overlay {
    position: absolute; inset: 0; z-index: 0;
    background-image:
        linear-gradient(rgba(249,115,22,0.04) 1px, transparent 1px),
        linear-gradient(90deg, rgba(249,115,22,0.04) 1px, transparent 1px);
    background-size: 40px 40px;
}

/* ─── Badge ─── */
.badge-pill {
    background: rgba(249,115,22,0.08);
    border: 1px solid rgba(249,115,22,0.2);
}
.dot-pulse {
    width: 7px; height: 7px;
    background: #f97316;
    border-radius: 50%;
    display: inline-block;
    animation: pulse 1.5s ease-in-out infinite;
}
@keyframes pulse {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.5; transform: scale(0.75); }
}

/* ─── Underline SVG ─── */
.underline-svg {
    position: absolute;
    bottom: -10px; left: 0;
    width: 100%; height: 12px;
}

/* ─── Map card ─── */
.map-card {
    border: 1.5px solid rgba(249,115,22,0.15);
    box-shadow: 0 30px 60px -12px rgba(249,115,22,0.15), 0 0 0 1px rgba(255,255,255,0.8) inset;
    transition: box-shadow 0.4s ease, transform 0.4s ease;
}
.map-card:hover {
    box-shadow: 0 40px 80px -12px rgba(249,115,22,0.25), 0 0 0 1px rgba(255,255,255,0.8) inset;
    transform: translateY(-3px);
}
.map-card iframe { width: 100%; height: 100%; display: block; }

.corner-accent {
    position: absolute;
    width: 24px; height: 24px;
    border-top: 2.5px solid #f97316;
    border-left: 2.5px solid #f97316;
    border-radius: 4px 0 0 0;
    z-index: 10;
}

/* ─── Location badge ─── */
.location-badge {
    background: rgba(255,255,255,0.92);
    backdrop-filter: blur(16px);
    border: 1px solid rgba(249,115,22,0.15);
    border-radius: 1rem;
    padding: 14px 18px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.08);
}
.loc-icon {
    width: 34px; height: 34px;
    background: #f97316;
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
    box-shadow: 0 4px 12px rgba(249,115,22,0.4);
}

/* ─── Form card ─── */
.form-card {
    background: #fff;
    border: 1.5px solid rgba(249,115,22,0.12);
    box-shadow: 0 30px 60px -12px rgba(249,115,22,0.1), 0 0 0 1px rgba(255,255,255,0.9) inset;
}

/* ─── Input group ─── */
.input-label {
    display: flex; align-items: center; gap: 6px;
    font-size: 0.65rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.15em;
    color: #64748b;
    margin-bottom: 8px;
}
.input-wrapper { position: relative; }
.styled-input {
    width: 100%;
    padding: 14px 16px;
    font-family: 'Montserrat', sans-serif;
    font-size: 0.875rem;
    font-weight: 500;
    color: #1e293b;
    background: #f8f9fc;
    border: 1.5px solid #e2e8f0;
    border-radius: 14px;
    outline: none;
    transition: border-color 0.25s ease, background 0.25s ease, box-shadow 0.25s ease;
}
.styled-input::placeholder { color: #94a3b8; font-weight: 400; }
.styled-input:focus {
    border-color: #f97316;
    background: #fff;
    box-shadow: 0 0 0 4px rgba(249,115,22,0.08);
}
.input-line {
    position: absolute;
    bottom: 0; left: 12px; right: 12px;
    height: 2px;
    background: linear-gradient(90deg, #f97316, #fb923c);
    border-radius: 1px;
    transform: scaleX(0);
    transform-origin: left;
    transition: transform 0.3s ease;
}
.input-wrapper:focus-within .input-line { transform: scaleX(1); }

/* ─── WhatsApp Button ─── */
.wa-button {
    position: relative;
    width: 100%;
    padding: 15px 24px;
    border: none; cursor: pointer;
    border-radius: 16px;
    overflow: hidden;
    transition: transform 0.2s ease, box-shadow 0.3s ease;
}
.wa-button:hover { transform: translateY(-2px); box-shadow: 0 16px 40px rgba(249,115,22,0.35); }
.wa-button:active { transform: translateY(0) scale(0.98); }

.wa-button-bg {
    position: absolute; inset: 0;
    background: linear-gradient(135deg, #f97316 0%, #ea580c 50%, #c2410c 100%);
    transition: opacity 0.3s;
}
.wa-button:hover .wa-button-bg { opacity: 0.92; }

.wa-button-content {
    position: relative; z-index: 1;
    display: flex; align-items: center; justify-content: center; gap: 10px;
    font-family: 'Montserrat', sans-serif;
    font-size: 0.875rem;
    color: #fff;
}
.arrow-icon {
    font-size: 1.1rem;
    transition: transform 0.3s ease;
}
.wa-button:hover .arrow-icon { transform: translateX(5px); }

/* ─── Phone strip ─── */
.phone-icon-wrap {
    width: 42px; height: 42px;
    border-radius: 12px;
    background: rgba(249,115,22,0.08);
    color: #f97316;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
    transition: background 0.2s;
}
.phone-icon-wrap:hover { background: rgba(249,115,22,0.15); }

.available-tag {
    display: flex; align-items: center; gap: 5px;
    padding: 5px 10px;
    background: rgba(22,163,74,0.08);
    border-radius: 99px;
    border: 1px solid rgba(22,163,74,0.2);
}
.dot-green {
    width: 6px; height: 6px;
    background: #16a34a;
    border-radius: 50%;
    animation: pulse 2s ease-in-out infinite;
}
</style>