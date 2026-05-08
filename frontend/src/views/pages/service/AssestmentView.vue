<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

const assessmentImages = [
    '/img/servicesSection/assestmen-1.jpg',
    '/img/servicesSection/assestmen-2.jpeg',
    '/img/servicesSection/assestmen-3.jpeg',
    '/img/servicesSection/assestmen-4.jpeg' 
];

const currentIndex = ref(0);
let autoSlideInterval = null;

const nextImage = () => {
    currentIndex.value = (currentIndex.value + 1) % assessmentImages.length;
};

const prevImage = () => {
    currentIndex.value = (currentIndex.value - 1 + assessmentImages.length) % assessmentImages.length;
};

const goToImage = (index) => {
    currentIndex.value = index;
};

// Pause auto slide when interacting
const pauseSlide = () => {
    if (autoSlideInterval) clearInterval(autoSlideInterval);
};

const resumeSlide = () => {
    pauseSlide();
    autoSlideInterval = setInterval(nextImage, 4000);
};

onMounted(() => {
    resumeSlide();
});

onUnmounted(() => {
    pauseSlide();
});
</script>

<template>
    <main class="relative bg-white font-montserrat z-0 pt-20 md:pt-24">
        <div class="absolute inset-0 bg-[linear-gradient(to_right,#f1f5f9_1px,transparent_1px),linear-gradient(to_bottom,#f1f5f9_1px,transparent_1px)] bg-[size:4rem_4rem] [mask-image:radial-gradient(ellipse_80%_50%_at_50%_0%,#000_70%,transparent_100%)] -z-10 pointer-events-none"></div>

        <!-- SECTION ASSESSMENT  -->
        <section id="assessment" class="py-12 md:py-24 relative overflow-hidden bg-white/50 border-y border-gray-100">
            <div class="max-w-7xl mx-auto px-4">
                <div class="grid lg:grid-cols-2 gap-16 items-center">

                    <div class="order-1 lg:order-1">
                        <div class="relative group">
                            <div class="aspect-video lg:aspect-square w-full bg-gray-100 rounded-[2rem] border border-gray-200 overflow-hidden relative shadow-lg group-hover:shadow-2xl transition-all duration-500"
                                 @mouseenter="pauseSlide" 
                                 @mouseleave="resumeSlide">
                                
                                <!-- Carousel Images -->
                                <div class="relative w-full h-full">
                                    <div v-for="(img, index) in assessmentImages" :key="img">
                                        <transition name="fade">
                                            <div v-show="currentIndex === index" class="absolute inset-0 w-full h-full">
                                                <img :src="img" :alt="'Assessment ' + index" class="w-full h-full object-cover transition-transform duration-1000 hover:scale-105" />
                                                
                                                <!-- Overlay gradient -->
                                                <div class="absolute inset-0 bg-gradient-to-t from-gray-900/90 via-gray-900/30 to-transparent"></div>
                                                
                                                <!-- Text info on image -->
                                                <div class="absolute bottom-12 left-8 z-20">
                                                    <p class="text-orange-500 font-bold tracking-[0.2em] uppercase text-xs mb-3 drop-shadow-md">Process 0{{ index + 1 }}</p>
                                                    <h3 class="text-3xl md:text-4xl font-black text-white tracking-tighter drop-shadow-lg">
                                                        Assessment
                                                    </h3>
                                                </div>
                                            </div>
                                        </transition>
                                    </div>
                                </div>

                                <!-- DPR RI Logo Overlay -->
                                <div class="absolute top-6 right-6 z-30 bg-white/95 backdrop-blur-sm p-3 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.12)] border border-white/60 transition-transform duration-300 hover:scale-105">
                                    <img src="/img/DPR-RI-LOGO.png" alt="DPR RI Logo" class="h-10 md:h-14 w-auto object-contain" />
                                </div>

                                <!-- Navigation Arrows -->
                                <button @click.stop="prevImage" class="absolute left-4 top-1/2 -translate-y-1/2 z-30 w-12 h-12 flex items-center justify-center rounded-full bg-white/20 backdrop-blur-md text-white border border-white/30 hover:bg-white hover:text-orange-600 transition-all duration-300 shadow-[0_4px_20px_rgb(0,0,0,0.1)] opacity-0 group-hover:opacity-100 hover:scale-110">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                                    </svg>
                                </button>
                                <button @click.stop="nextImage" class="absolute right-4 top-1/2 -translate-y-1/2 z-30 w-12 h-12 flex items-center justify-center rounded-full bg-white/20 backdrop-blur-md text-white border border-white/30 hover:bg-white hover:text-orange-600 transition-all duration-300 shadow-[0_4px_20px_rgb(0,0,0,0.1)] opacity-0 group-hover:opacity-100 hover:scale-110">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                                    </svg>
                                </button>

                                <!-- Indicators (Dots) -->
                                <div class="absolute bottom-6 left-1/2 -translate-x-1/2 z-30 flex gap-2.5">
                                    <button v-for="(img, index) in assessmentImages" :key="'dot-'+index"
                                            @click.stop="goToImage(index)"
                                            :aria-label="'Go to slide ' + (index + 1)"
                                            :class="['h-2 rounded-full transition-all duration-500 ease-out', currentIndex === index ? 'bg-orange-500 w-8 shadow-[0_0_10px_rgba(249,115,22,0.8)]' : 'bg-white/50 w-2 hover:bg-white hover:w-4']">
                                    </button>
                                </div>
                            </div>
                            <!-- Background decoration behind the image -->
                            <div class="absolute -bottom-4 -right-4 w-full h-full border-2 border-orange-600/20 rounded-[2rem] -z-10 group-hover:translate-x-3 group-hover:translate-y-3 transition-transform duration-500"></div>
                        </div>
                    </div>

                    <div id="psikotest" class="order-2 lg:order-2">
                        <span class="inline-block py-1.5 px-4 rounded-full bg-gray-900 text-white font-bold tracking-[0.2em] uppercase text-xs mb-6 shadow-sm">
                            {{ $t('service_view.assessment.badge') }}
                        </span>

                        <h2 class="text-4xl md:text-5xl font-black leading-[1.1] text-gray-900 uppercase tracking-tight">
                            {{ $t('service_view.assessment.title') }} <br class="hidden md:block" />
                            <span class="text-orange-600">{{ $t('service_view.assessment.title_break') }}</span> 
                            <span class="text-gray-400 text-2xl md:text-3xl font-medium block mt-2 normal-case tracking-normal">
                                {{ $t('service_view.assessment.subtitle') }}
                            </span>
                        </h2>

                        <p class="mt-6 text-lg text-gray-500 leading-relaxed font-medium">
                            {{ $t('service_view.assessment.desc') }}
                        </p>

                        <ul class="mt-8 space-y-4 bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                            <li class="flex items-center gap-4 text-gray-700 font-medium">
                                <div class="flex-shrink-0 w-8 h-8 rounded-full bg-orange-50 flex items-center justify-center text-orange-600 font-bold">1</div>
                                <span>{{ $t('service_view.assessment.point1') }}</span>
                            </li>
                            <li class="flex items-center gap-4 text-gray-700 font-medium">
                                <div class="flex-shrink-0 w-8 h-8 rounded-full bg-orange-50 flex items-center justify-center text-orange-600 font-bold">2</div>
                                <span>{{ $t('service_view.assessment.point2') }}</span>
                            </li>
                        </ul>

                        <div class="mt-10">
                            <button class="group flex items-center gap-3 text-white bg-gray-900 px-8 py-4 rounded-full font-bold text-sm hover:bg-orange-600 transition-all duration-300 shadow-md hover:shadow-xl hover:-translate-y-1">
                                {{ $t('service_view.assessment.btn_cta') }}
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <!-- SECTION Psychological Asessment  -->

        <section id="psychological-assessment" class="w-full">
        <div class="bg-gradient-to-r from-[#af1719] to-[#bca281] px-8 py-16 md:px-20 text-white">
            <div class="max-w-6xl mx-auto">
            <h1 class="font-['Montserrat'] text-5xl md:text-6xl font-extrabold leading-tight mb-6 tracking-wide">
                {{ $t('service_view.psychological_assessment.title') }}<br>{{ $t('service_view.psychological_assessment.title_break') }}
            </h1>
            <p class="font-['Quicksand'] text-base md:text-[17px] max-w-4xl leading-relaxed font-medium">
                {{ $t('service_view.psychological_assessment.desc') }}
            </p>
            </div>
        </div>

        <div class="bg-white px-8 py-16 md:px-20">
            <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-10 md:gap-14">
            
            <div class="flex flex-col items-center">
                <div class="w-20 h-20 rounded-full bg-gradient-to-b from-[#ff5e4d] to-[#fc8645] p-[4px] mb-6 shadow-sm">
                <div class="w-full h-full rounded-full bg-white flex items-center justify-center p-1">
                    <div class="w-full h-full rounded-full border-2 border-[#af1719] flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="#251624" class="w-7 h-7">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 13l-7 7-7-7m14-8l-7 7-7-7" />
                    </svg>
                    </div>
                </div>
                </div>
                <div class="bg-gradient-to-r from-[#ff4b3e] to-[#ff8c42] text-white font-['Montserrat'] font-bold py-3 px-6 rounded-3xl w-full text-center mb-6 text-sm md:text-[15px] leading-tight shadow-md">
                {{ $t('service_view.psychological_assessment.online.title1') }}<br>{{ $t('service_view.psychological_assessment.online.title2') }}
                </div>
                <p class="text-justify font-['Quicksand'] text-[#333333] text-sm md:text-[15px] text-left w-full leading-relaxed font-medium">
                {{ $t('service_view.psychological_assessment.online.desc') }}
                </p>
            </div>

            <div class="flex flex-col items-center">
                <div class="w-20 h-20 rounded-full bg-gradient-to-b from-[#ff5e4d] to-[#fc8645] p-[4px] mb-6 shadow-sm">
                <div class="w-full h-full rounded-full bg-white flex items-center justify-center p-1">
                    <div class="w-full h-full rounded-full border-2 border-[#af1719] flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="#251624" class="w-7 h-7">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 13l-7 7-7-7m14-8l-7 7-7-7" />
                    </svg>
                    </div>
                </div>
                </div>
                <div class="bg-gradient-to-r from-[#ff4b3e] to-[#ff8c42] text-white font-['Montserrat'] font-bold py-3 px-6 rounded-3xl w-full text-center mb-6 text-sm md:text-[15px] leading-tight shadow-md">
                {{ $t('service_view.psychological_assessment.offline.title1') }}<br>{{ $t('service_view.psychological_assessment.offline.title2') }}
                </div>
                <p class="font-['Quicksand'] text-[#333333] text-sm md:text-[15px] text-left w-full leading-relaxed font-medium">
                {{ $t('service_view.psychological_assessment.offline.desc') }}
                </p>
            </div>

            <div class="flex flex-col items-center">
                <div class="w-20 h-20 rounded-full bg-gradient-to-b from-[#ff5e4d] to-[#fc8645] p-[4px] mb-6 shadow-sm">
                <div class="w-full h-full rounded-full bg-white flex items-center justify-center p-1">
                    <div class="w-full h-full rounded-full border-2 border-[#af1719] flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="#251624" class="w-7 h-7">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 13l-7 7-7-7m14-8l-7 7-7-7" />
                    </svg>
                    </div>
                </div>
                </div>
                <div class="bg-gradient-to-r from-[#ff4b3e] to-[#ff8c42] text-white font-['Montserrat'] font-bold py-3 px-6 rounded-3xl w-full text-center mb-6 text-sm md:text-[15px] leading-tight shadow-md">
                {{ $t('service_view.psychological_assessment.hybrid.title1') }}<br>{{ $t('service_view.psychological_assessment.hybrid.title2') }}
                </div>
                <p class="font-['Quicksand'] text-[#333333] text-sm md:text-[15px] text-left w-full leading-relaxed font-medium">
                {{ $t('service_view.psychological_assessment.hybrid.desc') }}
                </p>
            </div>
            </div>
        </div>
        </section>

        <!-- SECTION INTEGRATED TALENT ASSESTMENT DAN DEVELOPMENT -->
         <section id="integrated-talent-assessment" class="w-full">
  <div class="bg-gradient-to-r from-[#af1719] to-[#bca281] px-8 py-16 md:px-20 text-white">
    <div class="max-w-6xl mx-auto">
      <h1 class="font-['Montserrat'] text-5xl md:text-6xl font-extrabold leading-tight mb-6 tracking-wide">
        {{ $t('service_view.integrated_talent_assessment.title1') }}<br>{{ $t('service_view.integrated_talent_assessment.title2') }}<br>{{ $t('service_view.integrated_talent_assessment.title3') }}
      </h1>
      <p class="font-['Quicksand'] text-base md:text-[17px] max-w-4xl leading-relaxed font-medium">
        {{ $t('service_view.integrated_talent_assessment.desc') }}
      </p>
    </div>
  </div>

  <div class="bg-white px-4 py-16 md:px-8 overflow-hidden">
    
    <div class="hidden md:block relative max-w-5xl mx-auto">
      <div class="absolute left-1/2 transform -translate-x-1/2 w-1.5 bg-[#f16936] top-4 bottom-12 z-0"></div>

      <div class="grid grid-cols-[1fr_auto_1fr] gap-x-0 w-full relative z-10">
        
        <div class="flex flex-col items-end pr-0 pb-10">
          <div class="w-full max-w-[440px] flex flex-col items-end">
            <div class="bg-gradient-to-r from-[#ea3837] to-[#f16936] text-white font-['Montserrat'] font-bold py-3 px-6 rounded-l-full h-[52px] w-full flex items-center shadow-md text-sm lg:text-base tracking-wide">
              {{ $t('service_view.integrated_talent_assessment.step1.title') }}
            </div>
            <div class="mt-4 px-2 w-full text-left">
              <p class="font-['Quicksand'] text-[14px] text-[#333333] leading-relaxed font-medium">
                {{ $t('service_view.integrated_talent_assessment.step1.desc_start') }}<b class="font-bold">{{ $t('service_view.integrated_talent_assessment.step1.desc_bold') }}</b>{{ $t('service_view.integrated_talent_assessment.step1.desc_end') }}
              </p>
            </div>
          </div>
        </div>
        <div class="relative flex justify-center w-14">
          <div class="w-12 h-12 rounded-full bg-[#f16936] text-white font-['Montserrat'] font-bold text-2xl flex items-center justify-center relative z-20 border-[3px] border-white shadow-sm mt-0.5">
            1
          </div>
        </div>
        <div></div>

        <div></div>
        <div class="relative flex justify-center w-14">
          <div class="w-12 h-12 rounded-full bg-[#f6a042] text-white font-['Montserrat'] font-bold text-2xl flex items-center justify-center relative z-20 border-[3px] border-white shadow-sm mt-0.5">
            2
          </div>
        </div>
        <div class="flex flex-col items-start pl-0 pb-10">
          <div class="w-full max-w-[440px] flex flex-col items-start">
            <div class="bg-gradient-to-r from-[#f6a042] to-[#fcd15b] text-white font-['Montserrat'] font-bold py-3 px-6 rounded-r-full h-[52px] w-full flex items-center shadow-md text-sm lg:text-base tracking-wide">
              {{ $t('service_view.integrated_talent_assessment.step2.title') }}
            </div>
            <div class="mt-4 px-2 w-full text-left">
              <p class="font-['Quicksand'] text-[14px] text-[#333333] leading-relaxed font-medium">
                {{ $t('service_view.integrated_talent_assessment.step2.desc_start') }}<b class="font-bold">{{ $t('service_view.integrated_talent_assessment.step2.desc_bold') }}</b>{{ $t('service_view.integrated_talent_assessment.step2.desc_end') }}
              </p>
            </div>
          </div>
        </div>

        <div class="flex flex-col items-end pr-0 pb-10">
          <div class="w-full max-w-[440px] flex flex-col items-end">
            <div class="bg-gradient-to-r from-[#ea3837] to-[#f16936] text-white font-['Montserrat'] font-bold py-3 px-6 rounded-l-full h-[52px] w-full flex items-center shadow-md text-sm lg:text-base tracking-wide">
              {{ $t('service_view.integrated_talent_assessment.step3.title') }}
            </div>
            <div class="mt-4 px-2 w-full text-left">
              <p class="font-['Quicksand'] text-[14px] text-[#333333] leading-relaxed font-medium">
                {{ $t('service_view.integrated_talent_assessment.step3.desc_start') }}<b class="font-bold">{{ $t('service_view.integrated_talent_assessment.step3.desc_bold') }}</b>{{ $t('service_view.integrated_talent_assessment.step3.desc_end') }}
              </p>
            </div>
          </div>
        </div>
        <div class="relative flex justify-center w-14">
          <div class="w-12 h-12 rounded-full bg-[#f16936] text-white font-['Montserrat'] font-bold text-2xl flex items-center justify-center relative z-20 border-[3px] border-white shadow-sm mt-0.5">
            3
          </div>
        </div>
        <div></div>

        <div></div>
        <div class="relative flex justify-center w-14">
          <div class="w-12 h-12 rounded-full bg-[#f6a042] text-white font-['Montserrat'] font-bold text-2xl flex items-center justify-center relative z-20 border-[3px] border-white shadow-sm mt-0.5">
            4
          </div>
        </div>
        <div class="flex flex-col items-start pl-0 pb-10">
          <div class="w-full max-w-[440px] flex flex-col items-start">
            <div class="bg-gradient-to-r from-[#f6a042] to-[#fcd15b] text-white font-['Montserrat'] font-bold py-3 px-6 rounded-r-full h-[52px] w-full flex items-center shadow-md text-sm lg:text-base tracking-wide">
              {{ $t('service_view.integrated_talent_assessment.step4.title') }}
            </div>
            <div class="mt-4 px-2 w-full text-left">
              <p class="font-['Quicksand'] text-[14px] text-[#333333] leading-relaxed font-medium">
                {{ $t('service_view.integrated_talent_assessment.step4.desc_start') }}<b class="font-bold">{{ $t('service_view.integrated_talent_assessment.step4.desc_bold') }}</b>{{ $t('service_view.integrated_talent_assessment.step4.desc_end') }}
              </p>
            </div>
          </div>
        </div>

        <div class="flex flex-col items-end pr-0 pb-4">
          <div class="w-full max-w-[440px] flex flex-col items-end">
            <div class="bg-gradient-to-r from-[#ea3837] to-[#f16936] text-white font-['Montserrat'] font-bold py-2 px-6 rounded-l-full h-[60px] w-full flex flex-col justify-center shadow-md text-sm lg:text-base tracking-wide leading-tight">
              <span>{{ $t('service_view.integrated_talent_assessment.step5.title1') }}</span>
              <span>{{ $t('service_view.integrated_talent_assessment.step5.title2') }}</span>
            </div>
            <div class="mt-4 px-2 w-full text-left">
              <p class="font-['Quicksand'] text-[14px] text-[#333333] leading-relaxed font-medium">
                {{ $t('service_view.integrated_talent_assessment.step5.desc_start') }}<b class="font-bold">{{ $t('service_view.integrated_talent_assessment.step5.desc_bold') }}</b>{{ $t('service_view.integrated_talent_assessment.step5.desc_end') }}
              </p>
            </div>
          </div>
        </div>
        <div class="relative flex justify-center w-14">
          <div class="w-12 h-12 rounded-full bg-[#f16936] text-white font-['Montserrat'] font-bold text-2xl flex items-center justify-center relative z-20 border-[3px] border-white shadow-sm mt-1">
            5
          </div>
        </div>
        <div></div>

      </div>
    </div>

    <div class="md:hidden relative w-full max-w-md mx-auto">
      <div class="absolute left-[22px] w-1.5 bg-[#f16936] top-4 bottom-8 z-0"></div>

      <div class="flex mb-10 relative z-10">
        <div class="w-12 h-12 rounded-full bg-[#f16936] text-white font-['Montserrat'] font-bold text-xl flex items-center justify-center shrink-0 border-[3px] border-white shadow-sm mr-4 relative top-0">
          1
        </div>
        <div class="flex-1 pt-1">
          <div class="bg-gradient-to-r from-[#ea3837] to-[#f16936] text-white font-['Montserrat'] font-bold py-2.5 px-4 rounded-xl text-sm mb-3 shadow-md tracking-wide">
            {{ $t('service_view.integrated_talent_assessment.step1.title') }}
          </div>
          <p class="font-['Quicksand'] text-[14px] text-[#333333] leading-relaxed font-medium">
            {{ $t('service_view.integrated_talent_assessment.step1.desc_start') }}<b class="font-bold">{{ $t('service_view.integrated_talent_assessment.step1.desc_bold') }}</b>{{ $t('service_view.integrated_talent_assessment.step1.desc_end') }}
          </p>
        </div>
      </div>

      <div class="flex mb-10 relative z-10">
        <div class="w-12 h-12 rounded-full bg-[#f6a042] text-white font-['Montserrat'] font-bold text-xl flex items-center justify-center shrink-0 border-[3px] border-white shadow-sm mr-4 relative top-0">
          2
        </div>
        <div class="flex-1 pt-1">
          <div class="bg-gradient-to-r from-[#f6a042] to-[#fcd15b] text-white font-['Montserrat'] font-bold py-2.5 px-4 rounded-xl text-sm mb-3 shadow-md tracking-wide">
            {{ $t('service_view.integrated_talent_assessment.step2.title') }}
          </div>
          <p class="font-['Quicksand'] text-[14px] text-[#333333] leading-relaxed font-medium">
            {{ $t('service_view.integrated_talent_assessment.step2.desc_start') }}<b class="font-bold">{{ $t('service_view.integrated_talent_assessment.step2.desc_bold') }}</b>{{ $t('service_view.integrated_talent_assessment.step2.desc_end') }}
          </p>
        </div>
      </div>

      <div class="flex mb-10 relative z-10">
        <div class="w-12 h-12 rounded-full bg-[#f16936] text-white font-['Montserrat'] font-bold text-xl flex items-center justify-center shrink-0 border-[3px] border-white shadow-sm mr-4 relative top-0">
          3
        </div>
        <div class="flex-1 pt-1">
          <div class="bg-gradient-to-r from-[#ea3837] to-[#f16936] text-white font-['Montserrat'] font-bold py-2.5 px-4 rounded-xl text-sm mb-3 shadow-md tracking-wide">
            {{ $t('service_view.integrated_talent_assessment.step3.title') }}
          </div>
          <p class="font-['Quicksand'] text-[14px] text-[#333333] leading-relaxed font-medium">
            {{ $t('service_view.integrated_talent_assessment.step3.desc_start') }}<b class="font-bold">{{ $t('service_view.integrated_talent_assessment.step3.desc_bold') }}</b>{{ $t('service_view.integrated_talent_assessment.step3.desc_end') }}
          </p>
        </div>
      </div>

      <div class="flex mb-10 relative z-10">
        <div class="w-12 h-12 rounded-full bg-[#f6a042] text-white font-['Montserrat'] font-bold text-xl flex items-center justify-center shrink-0 border-[3px] border-white shadow-sm mr-4 relative top-0">
          4
        </div>
        <div class="flex-1 pt-1">
          <div class="bg-gradient-to-r from-[#f6a042] to-[#fcd15b] text-white font-['Montserrat'] font-bold py-2.5 px-4 rounded-xl text-sm mb-3 shadow-md tracking-wide">
            {{ $t('service_view.integrated_talent_assessment.step4.title') }}
          </div>
          <p class="font-['Quicksand'] text-[14px] text-[#333333] leading-relaxed font-medium">
            {{ $t('service_view.integrated_talent_assessment.step4.desc_start') }}<b class="font-bold">{{ $t('service_view.integrated_talent_assessment.step4.desc_bold') }}</b>{{ $t('service_view.integrated_talent_assessment.step4.desc_end') }}
          </p>
        </div>
      </div>

      <div class="flex relative z-10">
        <div class="w-12 h-12 rounded-full bg-[#f16936] text-white font-['Montserrat'] font-bold text-xl flex items-center justify-center shrink-0 border-[3px] border-white shadow-sm mr-4 relative top-0">
          5
        </div>
        <div class="flex-1 pt-1">
          <div class="bg-gradient-to-r from-[#ea3837] to-[#f16936] text-white font-['Montserrat'] font-bold py-2.5 px-4 rounded-xl text-sm mb-3 shadow-md tracking-wide leading-tight">
            {{ $t('service_view.integrated_talent_assessment.step5.title1') }}<br>{{ $t('service_view.integrated_talent_assessment.step5.title2') }}
          </div>
          <p class="font-['Quicksand'] text-[14px] text-[#333333] leading-relaxed font-medium">
            {{ $t('service_view.integrated_talent_assessment.step5.desc_start') }}<b class="font-bold">{{ $t('service_view.integrated_talent_assessment.step5.desc_bold') }}</b>{{ $t('service_view.integrated_talent_assessment.step5.desc_end') }}
          </p>
        </div>
      </div>

    </div>
  </div>
</section>

    </main>
</template>

<style scoped>
  @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@700;800&family=Quicksand:wght@500;600&display=swap');

.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.8s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>