<script setup>
const partnerLogos = [
    '/img/BKKBN-LOGO.png',
    '/img/BMKG-LOGO.png',
    '/img/BNI-LOGO.png',
    '/img/DPR-LOGO.png',
    '/img/DPR-RI-LOGO.png',
    '/img/DYNO-LOGO.png',
    '/img/ITL-TRISAKTI-LOGO.png',
    '/img/KEMENTERIAN-PERINDUSTRIAN-LOGO.png',
    '/img/LOGO-KUNING.png',
    '/img/PARIWISATA-LOGO.png',
    '/img/RISTEK-LOGO.png',
    '/img/SETJEN-DPD-LOGO.png',
    '/img/STAN-LOGO.png',
    '/img/STIK-LOGO.png',
    '/img/TRISAKTI-LOGO.png',
    '/img/ULAT-UNGU-LOGO.png'
];

const testimonials = [
    {
        name: 'Budi Santoso',
        company: 'PT Maju Jaya',
        message: 'Exceptional service. We are highly satisfied with the professional and timely results delivered by the team.'
    },
    {
        name: 'Siti Aminah',
        company: 'PT Teknologi Inovasi',
        message: 'Greatly helped by the solutions provided. Communication was smooth, and the results exceeded our expectations.'
    },
    {
        name: 'Andi Wijaya',
        company: 'PT Bangun Persada',
        message: 'Undeniable quality of work. Highly recommend this service to other companies looking for a reliable partner.'
    }
];
</script>

<template>
    <section id="clients" class="py-16 bg-slate-50 border-y border-slate-200 overflow-hidden">
        <div class="py-16">
            <div class="max-w-5xl mx-auto px-6">

                <!-- CLIENT -->
                <div class="text-center mb-4">
                    <h2 class="text-[10px] font-bold text-orange-500 uppercase tracking-[0.3em] mb-2">Trusted Partners</h2>
                    <p class="text-3xl font-black text-slate-900 tracking-tighter">Collaborating with the finest institutions.</p>
                </div>
                <div class="w-9 h-[3px] bg-orange-500 rounded-full mx-auto mb-10"></div>

                <div class="relative flex overflow-hidden mb-14">
                    <div class="flex marquee-content gap-10 items-center py-3">
                        <div v-for="(logo, index) in [...partnerLogos, ...partnerLogos]" :key="'logo-' + index"
                            class="flex-shrink-0 group flex items-center justify-center grayscale opacity-45 hover:grayscale-0 hover:opacity-100 transition-all duration-500">
                            <img :src="logo" :alt="'Partner Logo ' + index"
                                class="max-h-12 w-28 object-contain transform group-hover:scale-110 transition-transform duration-300" />
                        </div>
                    </div>
                </div>

                <!-- TESTIMONIAL -->
                <div class="text-center mb-4 mt-8">
                    <h2 class="text-[10px] font-bold text-orange-500 uppercase tracking-[0.3em] mb-2">Client Testimonials</h2>
                    <p class="text-3xl font-black text-slate-900 tracking-tighter">What they say about us.</p>
                </div>
                <div class="w-9 h-[3px] bg-orange-500 rounded-full mx-auto mb-10"></div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div v-for="(testimonial, index) in testimonials" :key="'testi-' + index"
                        class="bg-white p-6 rounded-2xl border-[1.5px] border-slate-200 flex flex-col justify-between hover:-translate-y-1 hover:border-orange-500 hover:shadow-[0_8px_24px_rgba(249,115,22,0.1)] transition-all duration-300">
                        <div>
                            <svg class="w-8 h-8 text-orange-500 opacity-25 mb-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z" />
                            </svg>
                            <p class="text-slate-500 mb-6 italic leading-relaxed text-sm">"{{ testimonial.message }}"</p>
                        </div>
                        <div class="flex items-center gap-3 border-t border-slate-100 pt-4">
                            <div class="w-10 h-10 bg-orange-50 rounded-full flex items-center justify-center text-orange-500 font-bold text-base border-2 border-orange-500 flex-shrink-0">
                                {{ testimonial.name.charAt(0) }}
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-900 text-sm">{{ testimonial.name }}</h4>
                                <p class="text-xs text-orange-500 font-semibold mt-0.5">{{ testimonial.company }}</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;900&display=swap');

section { font-family: 'Montserrat', sans-serif; }

@keyframes marquee {
    0% { transform: translateX(0); }
    100% { transform: translateX(-50%); }
}

.marquee-content {
    animation: marquee 28s linear infinite;
    display: flex;
    width: max-content;
}

.marquee-content:hover {
    animation-play-state: paused;
}

#clients .relative::before,
#clients .relative::after {
    content: "";
    position: absolute;
    top: 0;
    width: 80px;
    height: 100%;
    z-index: 2;
}

#clients .relative::before {
    left: 0;
    background: linear-gradient(to right, #f8fafc, transparent);
}

#clients .relative::after {
    right: 0;
    background: linear-gradient(to left, #f8fafc, transparent);
}
</style>