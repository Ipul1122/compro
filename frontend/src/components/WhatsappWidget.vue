<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { useI18n } from 'vue-i18n';

const { locale } = useI18n();

const isOpen = ref(false);
const showNotification = ref(true);
const isScrolled = ref(false);

const toggleWidget = () => {
  isOpen.value = !isOpen.value;
  if (isOpen.value) {
    showNotification.value = false;
  }
};

const checkScroll = () => {
  isScrolled.value = window.scrollY > 402;
};

onMounted(() => {
  window.addEventListener('scroll', checkScroll);
});

onUnmounted(() => {
  window.removeEventListener('scroll', checkScroll);
});

// Data Whatsapp per Divisi
const whatsappData = computed(() => {
  const isEn = locale.value === 'en';
  return [
    {
      id: 'assessment',
      title: isEn ? 'Assessment & Psychotest Center' : 'Asesmen & Pusat Psikotes',
      subtitle: isEn ? 'PIC: Psychological & HC Services' : 'PIC: Layanan Psikologi & SDM',
      number: '6285284201428',
      message: isEn 
        ? 'Hello CPI, I am interested in inquiring about your Psychotest & Competency Assessment services.'
        : 'Halo CPI, saya tertarik dan ingin berkonsultasi mengenai layanan Psikotes & Asesmen Kompetensi.',
      icon: '🧠'
    },
    {
      id: 'interior',
      title: isEn ? 'Interior Design & Renovation' : 'Desain Interior & Renovasi',
      subtitle: isEn ? 'PIC: Interior Design Sales' : 'PIC: Sales Desain Interior',
      number: '6285284201428',
      message: isEn
        ? 'Hello CPI, I would like to consult about your Office Interior Design & Renovation services.'
        : 'Halo CPI, saya ingin berkonsultasi mengenai jasa Desain Interior Kantor & Renovasi.',
      icon: '🛠️'
    },
    {
      id: 'lms',
      title: isEn ? 'LMS & Digital Learning' : 'LMS & E-Learning Platform',
      subtitle: isEn ? 'PIC: Learning Management System Demo' : 'PIC: Demo Sistem LMS',
      number: '6285284201428',
      message: isEn
        ? 'Hello CPI, I would like to request a demo and details of your Learning Management System.'
        : 'Halo CPI, saya ingin meminta demo dan informasi lengkap mengenai platform Learning Management System.',
      icon: '💻'
    },
    {
      id: 'general',
      title: isEn ? 'General & Business Relations' : 'Umum & Hubungan Kerja Sama',
      subtitle: isEn ? 'PIC: General Inquiry & Partnerships' : 'PIC: Informasi Umum & Kemitraan',
      number: '6285284201428',
      message: isEn
        ? 'Hello CPI, I would like to get in touch for general consultation / business partnerships.'
        : 'Halo CPI, saya ingin berkonsultasi untuk kerja sama bisnis / informasi umum.',
      icon: '📞'
    }
  ];
});

const getWhatsappLink = (number, text) => {
  return `https://wa.me/${number}?text=${encodeURIComponent(text)}`;
};
</script>

<template>
  <div 
    :class="[
      'fixed right-6 sm:right-8 z-[99] font-montserrat transition-all duration-500 ease-out-back',
      isScrolled ? 'bottom-24 sm:bottom-28' : 'bottom-6 sm:bottom-8'
    ]"
  >
    
    <!-- Widget Chat Card -->
    <transition name="slide-fade">
      <div 
        v-if="isOpen" 
        class="absolute bottom-16 sm:bottom-20 right-0 w-[290px] sm:w-[350px] max-w-[90vw] bg-white rounded-2xl sm:rounded-3xl shadow-2xl border border-slate-100 overflow-hidden"
      >
        <!-- Header Widget -->
        <div class="bg-gradient-to-r from-slate-900 via-slate-800 to-slate-950 p-4 sm:p-6 text-white relative">
          <!-- Close Button -->
          <button 
            @click="toggleWidget" 
            class="absolute top-3 right-3 sm:top-4 sm:right-4 text-white/70 hover:text-white hover:bg-white/10 w-7 h-7 sm:w-8 sm:h-8 rounded-full flex items-center justify-center transition-colors cursor-pointer"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
          
          <div class="flex items-center gap-2.5 sm:gap-3">
            <!-- Avatar / Icon CPI -->
            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-white rounded-xl sm:rounded-2xl p-1.5 sm:p-2 flex items-center justify-center shadow-lg border border-slate-700">
              <img src="/webcakrawala-logo.png" alt="CPI Logo" class="object-contain w-full h-full" />
            </div>
            <div>
              <h3 class="font-extrabold text-[12px] sm:text-sm tracking-tight leading-none uppercase text-red-500">CPI Group</h3>
              <p class="text-[10px] sm:text-[11px] text-slate-300 font-medium mt-0.5">PT Cakrawala Parama Internasional</p>
              
              <!-- Status Online -->
              <div class="flex items-center gap-1 mt-1">
                <span class="relative flex h-1.5 w-1.5">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-emerald-500"></span>
                </span>
                <span class="text-[9px] font-bold text-emerald-400 tracking-wider uppercase">Online</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Body Widget -->
        <div class="p-4 sm:p-5 max-h-[240px] sm:max-h-[360px] overflow-y-auto bg-slate-50/50">
          <p class="text-[10px] sm:text-[11px] text-slate-500 font-semibold mb-3 sm:mb-4 leading-relaxed">
            {{ locale === 'en' 
              ? 'How can we help you? Select a service division below to start a WhatsApp chat:' 
              : 'Ada yang bisa kami bantu? Silakan pilih divisi layanan di bawah ini untuk memulai chat WhatsApp:' }}
          </p>

          <!-- List Divisi -->
          <div class="flex flex-col gap-2 sm:gap-3">
            <a 
              v-for="division in whatsappData" 
              :key="division.id"
              :href="getWhatsappLink(division.number, division.message)"
              target="_blank"
              class="flex items-center gap-2.5 sm:gap-3.5 bg-white p-2.5 sm:p-3.5 rounded-xl sm:rounded-2xl border border-slate-100 hover:border-red-200 hover:shadow-md transition-all duration-300 group"
            >
              <!-- Icon Container -->
              <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-lg sm:rounded-xl bg-slate-50 group-hover:bg-red-50 flex items-center justify-center text-base sm:text-lg transition-colors shadow-sm">
                {{ division.icon }}
              </div>
              
              <!-- Info -->
              <div class="flex-1 min-w-0">
                <h4 class="text-[11px] sm:text-xs font-bold text-slate-800 group-hover:text-red-500 transition-colors leading-snug">
                  {{ division.title }}
                </h4>
                <p class="text-[9px] sm:text-[10px] text-slate-400 font-semibold mt-0.5 truncate">
                  {{ division.subtitle }}
                </p>
              </div>

              <!-- Arrow Icon -->
              <div class="text-slate-300 group-hover:text-red-500 group-hover:translate-x-0.5 transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                </svg>
              </div>
            </a>
          </div>
        </div>

        <!-- Footer Widget -->
        <div class="border-t border-slate-100 p-2.5 sm:p-3 bg-white text-center">
          <p class="text-[8px] sm:text-[9px] font-bold text-slate-400 uppercase tracking-widest leading-none">
            {{ locale === 'en' ? 'Replies in a few minutes' : 'Membalas dalam beberapa menit' }}
          </p>
        </div>
      </div>
    </transition>

    <!-- Floating Circular Button -->
    <button 
      @click="toggleWidget"
      :class="[
        'w-12 h-12 sm:w-14 sm:h-14 bg-emerald-500 hover:bg-emerald-600 text-white rounded-full flex items-center justify-center shadow-2xl transition-all duration-500 cursor-pointer group relative',
        isOpen ? 'rotate-90 scale-90 bg-slate-900 hover:bg-slate-800' : 'hover:-translate-y-1 hover:scale-105 active:scale-95'
      ]"
      :title="locale === 'en' ? 'Contact Us on WhatsApp' : 'Hubungi kami di WhatsApp'"
    >
      <!-- Notification Badge -->
      <span 
        v-if="showNotification && !isOpen" 
        class="absolute -top-0.5 -right-0.5 flex h-3.5 w-3.5 sm:h-4 sm:w-4 items-center justify-center rounded-full bg-red-500 text-[8px] sm:text-[9px] font-black text-white shadow-md animate-bounce"
      >
        1
      </span>

      <!-- Inner Pulsing Ring -->
      <span 
        v-if="!isOpen"
        class="absolute inset-0 rounded-full bg-emerald-500 animate-ping opacity-25 group-hover:opacity-0 transition-opacity"
      ></span>

      <!-- WhatsApp SVG Icon -->
      <svg 
        v-if="!isOpen"
        xmlns="http://www.w3.org/2000/svg" 
        class="w-6 h-6 sm:w-7 sm:h-7" 
        fill="currentColor" 
        viewBox="0 0 24 24"
      >
        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
      </svg>
      
      <!-- Close Icon when Widget is Open -->
      <svg 
        v-else 
        xmlns="http://www.w3.org/2000/svg" 
        class="w-5 h-5 sm:w-6 sm:h-6 text-white" 
        fill="none" 
        viewBox="0 0 24 24" 
        stroke="currentColor" 
        stroke-width="2.5"
      >
        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
      </svg>
    </button>

  </div>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&display=swap');

.font-montserrat {
  font-family: 'Montserrat', sans-serif;
}

/* Animations for Slide-Fade */
.slide-fade-enter-active {
  transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
}
.slide-fade-leave-active {
  transition: all 0.3s cubic-bezier(0.7, 0, 0.84, 0);
}
.slide-fade-enter-from,
.slide-fade-leave-to {
  transform: translateY(25px) scale(0.95);
  opacity: 0;
}

/* Custom Scrollbar for list */
::-webkit-scrollbar {
  width: 5px;
}
::-webkit-scrollbar-track {
  background: transparent;
}
::-webkit-scrollbar-thumb {
  background: #cbd5e1;
  border-radius: 99px;
}
::-webkit-scrollbar-thumb:hover {
  background: #94a3b8;
}
</style>
