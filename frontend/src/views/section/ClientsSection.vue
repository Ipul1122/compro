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
</script>

<template>
    <section id="clients" class="py-16 bg-slate-50 border-y border-slate-200 overflow-hidden">
        <div class="py-24">
            <div class="max-w-7xl mx-auto px-6">
                <div class="text-center mb-16">
                    <h2 class="text-xs font-bold text-brand uppercase tracking-[0.3em] mb-4">Trusted Partners
                    </h2>
                    <p class="text-3xl font-black text-slate-900 tracking-tighter">Collaborating with the finest
                        institutions.</p>
                </div>

                <div class="relative flex overflow-hidden">
                    <div class="flex marquee-content gap-12 items-center py-4">
                        <div v-for="(logo, index) in partnerLogos" :key="'a-' + index"
                            class="flex-shrink-0 group flex items-center justify-center grayscale opacity-50 hover:grayscale-0 hover:opacity-100 transition-all duration-500">
                            <img :src="logo" :alt="'Partner Logo ' + index"
                                class="max-h-12 w-40 object-contain transform group-hover:scale-110 transition-transform duration-300" />
                        </div>
                        <div v-for="(logo, index) in partnerLogos" :key="'b-' + index"
                            class="flex-shrink-0 group flex items-center justify-center grayscale opacity-50 hover:grayscale-0 hover:opacity-100 transition-all duration-500">
                            <img :src="logo" :alt="'Partner Logo ' + index"
                                class="max-h-12 w-40 object-contain transform group-hover:scale-110 transition-transform duration-300" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
/* The animation moves the content 50% because we doubled the logos */
@keyframes marquee {
    0% {
        transform: translateX(0);
    }

    100% {
        transform: translateX(-50%);
    }
}

.marquee-content {
    animation: marquee 30s linear infinite;
    display: flex;
    width: max-content;
    /* Important: makes container as wide as all logos */
}

/* Pause animation when user hovers */
.marquee-content:hover {
    animation-play-state: paused;
}

/* Optional: Add a fade effect to the edges */
#clients .relative::before,
#clients .relative::after {
    content: "";
    position: absolute;
    top: 0;
    width: 100px;
    height: 100%;
    z-index: 2;
}

#clients .relative::before {
    left: 0;
    background: linear-gradient(to right, #f8fafc, transparent);
    /* Matches bg-slate-50 */
}

#clients .relative::after {
    right: 0;
    background: linear-gradient(to left, #f8fafc, transparent);
}
</style>