<script setup>
import { ref, computed, onMounted, onUnmounted, watch } from 'vue';
import { useI18n } from 'vue-i18n';
import { useRouter } from 'vue-router';
import { questions } from '../../../data/psikotesQuestions.js';

const { t, locale } = useI18n();
const router = useRouter();

// Timer settings: 20 minutes (1200 seconds)
const TOTAL_TIME = 1200;
const timer = ref(TOTAL_TIME);
let intervalId = null;

// Quiz States
const isStarted = ref(false);
const isSubmitted = ref(false);
const currentQuestionIndex = ref(0);
const userAnswers = ref({});
const flaggedQuestions = ref({});
const showSubmitModal = ref(false);
const timeSpent = ref(0);
const isSidebarOpen = ref(false);

const STORAGE_KEY = 'psikotes_quiz_progress';

// Helper to format remaining time
const formatTime = (seconds) => {
  const m = Math.floor(seconds / 60);
  const s = seconds % 60;
  return `${String(m).padStart(2, '0')}:${String(s).padStart(2, '0')}`;
};

// Timer resume helper
const resumeTimer = () => {
  if (intervalId) clearInterval(intervalId);
  intervalId = setInterval(() => {
    if (timer.value > 0) {
      timer.value--;
      timeSpent.value++;
    } else {
      autoSubmit();
    }
  }, 1000);
};

// Start Quiz logic
const startQuiz = () => {
  isStarted.value = true;
  isSubmitted.value = false;
  timer.value = TOTAL_TIME;
  userAnswers.value = {};
  flaggedQuestions.value = {};
  currentQuestionIndex.value = 0;
  timeSpent.value = 0;
  isSidebarOpen.value = false;
  saveProgress();
  resumeTimer();
};

// Navigate index
const selectQuestion = (index) => {
  currentQuestionIndex.value = index;
};

const nextQuestion = () => {
  if (currentQuestionIndex.value < questions.length - 1) {
    currentQuestionIndex.value++;
  }
};

const prevQuestion = () => {
  if (currentQuestionIndex.value > 0) {
    currentQuestionIndex.value--;
  }
};

// Select Answer logic
const selectAnswer = (option) => {
  userAnswers.value[questions[currentQuestionIndex.value].id] = option;
};

// Flag/bookmark question logic
const toggleFlag = () => {
  const qId = questions[currentQuestionIndex.value].id;
  flaggedQuestions.value[qId] = !flaggedQuestions.value[qId];
};

// Calculation stats
const totalAnswered = computed(() => Object.keys(userAnswers.value).length);
const totalUnanswered = computed(() => questions.length - totalAnswered.value);

// Check if current question is flagged
const isCurrentFlagged = computed(() => {
  const qId = questions[currentQuestionIndex.value].id;
  return !!flaggedQuestions.value[qId];
});

// Trigger submit confirmation
const triggerSubmit = () => {
  showSubmitModal.value = true;
};

const closeSubmitModal = () => {
  showSubmitModal.value = false;
};

// Submit and compute results
const submitQuiz = () => {
  if (intervalId) clearInterval(intervalId);
  isSubmitted.value = true;
  isStarted.value = false;
  showSubmitModal.value = false;
  window.scrollTo({ top: 0, behavior: 'smooth' });
};

// Auto submit on timeout
const autoSubmit = () => {
  if (intervalId) clearInterval(intervalId);
  submitQuiz();
};

// LocalStorage helpers
const saveProgress = () => {
  const data = {
    isStarted: isStarted.value,
    isSubmitted: isSubmitted.value,
    currentQuestionIndex: currentQuestionIndex.value,
    userAnswers: userAnswers.value,
    flaggedQuestions: flaggedQuestions.value,
    timer: timer.value,
    timeSpent: timeSpent.value
  };
  localStorage.setItem(STORAGE_KEY, JSON.stringify(data));
};

const loadProgress = () => {
  const saved = localStorage.getItem(STORAGE_KEY);
  if (saved) {
    try {
      const data = JSON.parse(saved);
      isStarted.value = data.isStarted ?? false;
      isSubmitted.value = data.isSubmitted ?? false;
      currentQuestionIndex.value = data.currentQuestionIndex ?? 0;
      userAnswers.value = data.userAnswers ?? {};
      flaggedQuestions.value = data.flaggedQuestions ?? {};
      timer.value = data.timer ?? TOTAL_TIME;
      timeSpent.value = data.timeSpent ?? 0;

      if (isStarted.value && !isSubmitted.value) {
        resumeTimer();
      }
    } catch (e) {
      console.error('Error loading quiz progress from localStorage:', e);
    }
  }
};

onMounted(() => {
  loadProgress();
});

onUnmounted(() => {
  if (intervalId) clearInterval(intervalId);
});

// Watch reactive state to persist changes
watch(
  [isStarted, isSubmitted, currentQuestionIndex, userAnswers, flaggedQuestions, timer, timeSpent],
  () => {
    saveProgress();
  },
  { deep: true }
);

// Scoring results
const correctAnswersCount = computed(() => {
  let count = 0;
  questions.forEach(q => {
    if (userAnswers.value[q.id] === q.correctAnswer) {
      count++;
    }
  });
  return count;
});

const scorePercentage = computed(() => {
  return Math.round((correctAnswersCount.value / questions.length) * 100);
});

// Category Performance breakdown
const categoryPerformance = computed(() => {
  const categories = {
    verbal: { correct: 0, total: 10, label: 'Penalaran Verbal', labelEn: 'Verbal Reasoning' },
    numerical: { correct: 0, total: 10, label: 'Penalaran Numerik', labelEn: 'Numerical Reasoning' },
    logical: { correct: 0, total: 10, label: 'Penalaran Logika', labelEn: 'Logical Reasoning' }
  };

  questions.forEach(q => {
    if (userAnswers.value[q.id] === q.correctAnswer) {
      categories[q.category].correct++;
    }
  });

  return Object.keys(categories).map(key => ({
    key,
    label: locale.value === 'id' ? categories[key].label : categories[key].labelEn,
    correct: categories[key].correct,
    total: categories[key].total,
    percentage: Math.round((categories[key].correct / categories[key].total) * 100)
  }));
});

// Strongest Category Recommendation
const recommendation = computed(() => {
  const performance = categoryPerformance.value;
  let highest = performance[0];
  
  performance.forEach(cat => {
    if (cat.percentage > highest.percentage) {
      highest = cat;
    }
  });

  if (highest.key === 'verbal') {
    return {
      title: locale.value === 'id' ? 'Komunikasi & Analisis Bahasa' : 'Communication & Linguistic Analysis',
      desc: locale.value === 'id' 
        ? 'Anda memiliki potensi luar biasa dalam memahami konsep berbasis kata, merumuskan ide, serta menganalisis informasi verbal secara kritis. Karir yang sangat sesuai mencakup bidang Hubungan Masyarakat (PR), Penasihat Hukum, Konsultan SDM, Spesialis Komunikasi, atau Strategis Konten.' 
        : 'You demonstrate high capability in processing word-based concepts, formulating ideas, and critically analyzing verbal data. Recommended paths include Public Relations (PR), Legal Adviser, HR Consultant, Communication Specialist, or Content Strategist.'
    };
  } else if (highest.key === 'numerical') {
    return {
      title: locale.value === 'id' ? 'Kuantitatif & Analisis Data' : 'Quantitative & Data Analysis',
      desc: locale.value === 'id' 
        ? 'Anda unggul dalam pemecahan masalah berbasis angka, perhitungan terstruktur, dan analisis logis berbasis kuantitatif. Anda sangat cocok untuk karir di bidang Analisis Keuangan, Aktuaria, Data Scientist, Rekayasa Perangkat Lunak, atau Manajemen Risiko.' 
        : 'You excel in numerical reasoning, structured logical computational models, and quantitative data processing. Recommended careers include Financial Analyst, Actuary, Data Scientist, Software Engineer, or Risk Manager.'
    };
  } else {
    return {
      title: locale.value === 'id' ? 'Abstraksi & Logika Strategis' : 'Abstraction & Strategic Logic',
      desc: locale.value === 'id' 
        ? 'Kekuatan terbesar Anda terletak pada kemampuan deduksi logis, analisis pola, serta pemecahan masalah kompleks yang membutuhkan pemikiran strategis. Anda sangat direkomendasikan untuk posisi Manajer Proyek, Analis Sistem, Peneliti, Konsultan Strategi Bisnis, atau Perencana Operasional.' 
        : 'Your core strength lies in deductive logic, abstract pattern reasoning, and solving complex architectural problems. Recommended career fields include Project Manager, Systems Analyst, Researcher, Business Strategy Consultant, or Operations Strategist.'
    };
  }
});

// Format time spent to string
const formattedTimeSpent = computed(() => {
  const m = Math.floor(timeSpent.value / 60);
  const s = timeSpent.value % 60;
  return locale.value === 'id' 
    ? `${m} menit ${s} detik` 
    : `${m} min ${s} sec`;
});

// Category helper translation function
const getCategoryLabel = (category) => {
  if (category === 'verbal') {
    return locale.value === 'id' ? 'Penalaran Verbal' : 'Verbal Reasoning';
  } else if (category === 'numerical') {
    return locale.value === 'id' ? 'Penalaran Numerik' : 'Numerical Reasoning';
  } else {
    return locale.value === 'id' ? 'Penalaran Logika' : 'Logical Reasoning';
  }
};

// Track selected question item for detailed review
const activeReviewIndex = ref(0);

const mobNavRef = ref(null);
watch(currentQuestionIndex, (newIdx) => {
  if (mobNavRef.value) {
    const activeEl = mobNavRef.value.children[newIdx];
    if (activeEl) {
      mobNavRef.value.scrollTo({
        left: activeEl.offsetLeft - mobNavRef.value.clientWidth / 2 + activeEl.clientWidth / 2,
        behavior: 'smooth'
      });
    }
  }
});
</script>

<template>
  <!-- Main wrapper (without global navbar offset) -->
  <main class="relative bg-slate-50 font-montserrat z-0 overflow-x-hidden min-h-screen pb-20" :class="isStarted && !isSubmitted ? 'pt-0 pb-0 h-screen overflow-hidden' : 'pt-12 md:pt-16'">
    <!-- Grid Background Decoration (Visible in welcome and results screens) -->
    <div v-if="!isStarted || isSubmitted" class="absolute inset-0 bg-[linear-gradient(to_right,#f1f5f9_1px,transparent_1px),linear-gradient(to_bottom,#f1f5f9_1px,transparent_1px)] bg-[size:4rem_4rem] [mask-image:radial-gradient(ellipse_80%_50%_at_50%_0%,#000_70%,transparent_100%)] -z-10 pointer-events-none"></div>

    <!-- 1. WELCOME OR SUBMITTED RESULT VIEWS (Centered bounded containers) -->
    <div v-if="!isStarted" class="max-w-6xl mx-auto px-4">
      
      <!-- HEADER TITLE -->
      <div class="mb-8 text-center md:text-left">
        <router-link :to="locale === 'id' ? '/id/assessment' : '/en/assessment'" class="inline-flex items-center gap-2 text-sm font-bold text-gray-500 hover:text-[#1a365d] transition-colors mb-4 group">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
          </svg>
          {{ $t('service_view.trial_psikotes.back_to_services') }}
        </router-link>
        <h1 class="text-3xl md:text-5xl font-black text-slate-900 tracking-tight leading-none">
          {{ $t('service_view.trial_psikotes.title') }}
        </h1>
        <p class="text-slate-500 mt-2 text-sm md:text-base font-medium">
          {{ $t('service_view.trial_psikotes.subtitle') }}
        </p>
      </div>

      <!-- INSTRUCTIONS / WELCOME SCREEN -->
      <div v-if="!isSubmitted" class="bg-white rounded-3xl border border-slate-100 shadow-xl overflow-hidden max-w-3xl mx-auto transition-all duration-300">
        <div class="bg-white border-b border-slate-100 p-6 sm:p-10 md:p-12">
          <h2 class="text-xl sm:text-2xl md:text-3xl font-black mb-3 text-[#af1719]">
            {{ $t('service_view.trial_psikotes.instructions.title') }}
          </h2>
          <div class="flex flex-wrap gap-3 mt-6">
            <span class="inline-flex items-center gap-2 bg-orange-600/10 backdrop-blur-md px-4 py-2 rounded-full text-xs md:text-sm font-bold border border-orange-500/20 text-orange-600">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              {{ $t('service_view.trial_psikotes.instructions.duration') }}
            </span>
            <span class="inline-flex items-center gap-2 bg-orange-600/10 backdrop-blur-md px-4 py-2 rounded-full text-xs md:text-sm font-bold border border-orange-500/20 text-orange-600">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
              </svg>
              {{ $t('service_view.trial_psikotes.instructions.total_questions') }}
            </span>
          </div>
        </div>

        <div class="p-6 sm:p-10 md:p-12">
          <ul class="space-y-5 text-slate-600 font-semibold text-sm sm:text-base">
            <li class="flex items-start gap-4">
              <div class="flex-shrink-0 w-6 h-6 rounded-full bg-orange-100 flex items-center justify-center text-orange-600 font-black text-xs mt-0.5">1</div>
              <p class="leading-relaxed">{{ $t('service_view.trial_psikotes.instructions.p1') }}</p>
            </li>
            <li class="flex items-start gap-4">
              <div class="flex-shrink-0 w-6 h-6 rounded-full bg-orange-100 flex items-center justify-center text-orange-600 font-black text-xs mt-0.5">2</div>
              <p class="leading-relaxed">{{ $t('service_view.trial_psikotes.instructions.p2') }}</p>
            </li>
            <li class="flex items-start gap-4">
              <div class="flex-shrink-0 w-6 h-6 rounded-full bg-orange-100 flex items-center justify-center text-orange-600 font-black text-xs mt-0.5">3</div>
              <p class="leading-relaxed">{{ $t('service_view.trial_psikotes.instructions.p3') }}</p>
            </li>
            <li class="flex items-start gap-4">
              <div class="flex-shrink-0 w-6 h-6 rounded-full bg-orange-100 flex items-center justify-center text-orange-600 font-black text-xs mt-0.5">4</div>
              <p class="leading-relaxed">{{ $t('service_view.trial_psikotes.instructions.p4') }}</p>
            </li>
          </ul>

          <div class="mt-10 text-center">
            <button @click="startQuiz" class="px-8 py-4 sm:px-10 sm:py-5 bg-slate-900 text-orange-600 rounded-full font-black text-sm sm:text-base tracking-wide hover:bg-orange-600 hover:text-white shadow-xl active:scale-95 transition-all duration-300">
              {{ $t('service_view.trial_psikotes.instructions.btn_start') }}
            </button>
          </div>
        </div>
      </div>

      <!-- DETAILED RESULTS DASHBOARD (After completion) -->
      <div v-if="isSubmitted" class="space-y-8 max-w-4xl mx-auto transition-all duration-500">
        <!-- Score Overview block -->
        <div class="bg-white rounded-3xl border border-slate-100 shadow-xl overflow-hidden grid md:grid-cols-3">
          <!-- Main score meter (white background) -->
          <div class="bg-white p-8 sm:p-10 flex flex-col justify-between items-center text-center relative border-b md:border-b-0 md:border-r border-slate-100">
            <h3 class="text-xs sm:text-sm font-black uppercase tracking-wider text-slate-400">
              {{ $t('service_view.trial_psikotes.results.score_label') }}
            </h3>

            <div class="my-6 sm:my-8 relative w-36 h-36 sm:w-40 sm:h-40 flex items-center justify-center">
              <!-- Animated radial background SVG -->
              <svg class="absolute inset-0 w-full h-full -rotate-90" viewBox="0 0 100 100">
                <circle cx="50" cy="50" r="42" fill="none" stroke="#f1f5f9" stroke-width="8"></circle>
                <circle 
                  cx="50" 
                  cy="50" 
                  r="42" 
                  fill="none" 
                  stroke="#f97316" 
                  stroke-width="8" 
                  stroke-linecap="round"
                  :stroke-dasharray="2 * Math.PI * 42"
                  :stroke-dashoffset="2 * Math.PI * 42 * (1 - scorePercentage / 100)"
                  class="transition-all duration-1000 ease-out"
                ></circle>
              </svg>
              <!-- Score label inside -->
              <div class="text-center">
                <span class="text-4xl sm:text-5xl font-black tracking-tight leading-none text-[#af1719]">{{ scorePercentage }}</span>
                <span class="text-xs block font-bold text-slate-400 mt-1">/ 100</span>
              </div>
            </div>

            <div class="space-y-0.5">
              <p class="text-[10px] sm:text-xs font-black tracking-wider uppercase text-slate-400">
                {{ $t('service_view.trial_psikotes.results.correct_answers') }}
              </p>
              <p class="text-base sm:text-lg font-bold text-[#af1719]">
                {{ correctAnswersCount }} / {{ questions.length }}
              </p>
            </div>
          </div>

          <!-- Category Performance list -->
          <div class="md:col-span-2 p-6 sm:p-8 md:p-10 flex flex-col justify-between">
            <div>
              <h2 class="text-xl sm:text-2xl font-black text-slate-800 tracking-tight leading-none mb-1.5">
                {{ $t('service_view.trial_psikotes.results.title') }}
              </h2>
              <p class="text-xs sm:text-sm font-semibold text-slate-400 mb-6 sm:mb-8">
                {{ $t('service_view.trial_psikotes.results.subtitle') }}
              </p>

              <!-- Category scores -->
              <div class="space-y-5">
                <div v-for="cat in categoryPerformance" :key="cat.key" class="space-y-1.5">
                  <div class="flex justify-between items-center text-xs sm:text-sm font-bold">
                    <span class="text-slate-700">{{ cat.label }}</span>
                    <span class="text-slate-500 font-mono">{{ cat.correct }} / {{ cat.total }} ({{ cat.percentage }}%)</span>
                  </div>
                  <div class="w-full h-2 sm:h-2.5 bg-slate-100 rounded-full overflow-hidden">
                    <div 
                      class="h-full bg-gradient-to-r from-orange-500 to-red-600 rounded-full transition-all duration-1000"
                      :style="{ width: `${cat.percentage}%` }"
                    ></div>
                  </div>
                  <p class="text-[11px] text-slate-400 font-semibold leading-normal">
                    {{ cat.key === 'verbal' ? $t('service_view.trial_psikotes.results.cat_verbal_desc') : (cat.key === 'numerical' ? $t('service_view.trial_psikotes.results.cat_numerical_desc') : $t('service_view.trial_psikotes.results.cat_logical_desc')) }}
                  </p>
                </div>
              </div>
            </div>

            <!-- Operational stats footer -->
            <div class="flex flex-wrap gap-x-6 gap-y-3 items-center mt-6 pt-5 border-t border-slate-100">
              <div class="text-[10px] sm:text-xs font-semibold text-slate-400">
                {{ $t('service_view.trial_psikotes.results.time_spent') }}:
                <span class="block text-xs sm:text-sm font-black text-slate-800 mt-0.5">{{ formattedTimeSpent }}</span>
              </div>
              <button 
                @click="startQuiz" 
                class="ml-auto px-5 py-2.5 sm:px-6 sm:py-3 bg-slate-900 hover:bg-orange-600 text-orange-600 hover:text-white rounded-xl text-xs font-black tracking-wide shadow-sm hover:shadow-md transition-all active:scale-95"
              >
                {{ $t('service_view.trial_psikotes.results.btn_retake') }}
              </button>
            </div>
          </div>
        </div>

        <!-- Career recommendation section -->
        <div class="bg-white rounded-3xl border border-slate-100 p-6 sm:p-8 shadow-md flex flex-col sm:flex-row items-start gap-4 sm:gap-5">
          <div class="w-12 h-12 rounded-2xl bg-orange-50 flex items-center justify-center text-orange-600 shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6.5 w-6.5 sm:h-7 sm:w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
          </div>
          <div class="space-y-1.5">
            <span class="text-[10px] font-black uppercase text-orange-600 tracking-wider">
              {{ $t('service_view.trial_psikotes.results.recommendation') }}
            </span>
            <h3 class="text-lg sm:text-xl font-black text-slate-800 tracking-tight leading-none text-red-600">
              {{ recommendation.title }}
            </h3>
            <p class="text-slate-600 text-xs sm:text-sm leading-relaxed font-semibold">
              {{ recommendation.desc }}
            </p>
          </div>
        </div>

        <!-- Detailed Review segment -->
        <div class="bg-white rounded-3xl border border-slate-100 p-5 sm:p-8 shadow-md">
          <h3 class="text-lg sm:text-xl font-black text-slate-800 tracking-tight leading-none mb-6 sm:mb-8 border-b border-slate-50 pb-4">
            {{ $t('service_view.trial_psikotes.results.details_title') }}
          </h3>

          <div class="grid md:grid-cols-3 gap-6 sm:gap-8 items-start">
            <!-- Left sidebar: Question numbers GRID -->
            <div class="md:col-span-1 border-b md:border-b-0 md:border-r border-slate-100 pb-6 md:pb-0 md:pr-6">
              <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-wider mb-4">
                Navigasi Review
              </h4>
              <div class="grid grid-cols-5 gap-2 max-h-[200px] md:max-h-none overflow-y-auto pr-1">
                <button
                  v-for="(q, idx) in questions"
                  :key="'review-'+q.id"
                  @click="activeReviewIndex = idx"
                  :class="[
                    'h-9 rounded-lg font-bold text-xs flex items-center justify-center transition-all',
                    activeReviewIndex === idx 
                      ? 'ring-2 ring-slate-800 ring-offset-2 scale-105 z-10 text-orange-600' 
                      : '',
                    userAnswers[q.id] === q.correctAnswer 
                      ? 'bg-emerald-500 hover:bg-emerald-600 text-slate-900 font-bold' 
                      : 'bg-rose-500 hover:bg-rose-600 text-slate-900 font-bold'
                  ]"
                >
                  {{ idx + 1 }}
                </button>
              </div>
              
              <!-- Review Legend -->
              <div class="mt-5 pt-4 border-t border-slate-100 flex gap-4 text-[9px] font-black uppercase text-slate-400">
                <div class="flex items-center gap-1.5">
                  <span class="w-2.5 h-2.5 rounded bg-emerald-500"></span>
                  <span>Benar</span>
                </div>
                <div class="flex items-center gap-1.5">
                  <span class="w-2.5 h-2.5 rounded bg-rose-500"></span>
                  <span>Salah</span>
                </div>
              </div>
            </div>

            <!-- Right details viewer: Selected question key data -->
            <div class="md:col-span-2 space-y-5 sm:space-y-6">
              <div class="flex items-center justify-between border-b border-slate-50 pb-2">
                <span class="inline-block px-3 py-1 rounded-full bg-slate-100 text-slate-700 text-[10px] font-black uppercase tracking-wider">
                  {{ getCategoryLabel(questions[activeReviewIndex].category) }}
                </span>
                <span class="text-[10px] sm:text-xs font-bold text-slate-400">
                  {{ $t('service_view.trial_psikotes.active.question') }} {{ activeReviewIndex + 1 }} dari {{ questions.length }}
                </span>
              </div>

              <h4 class="text-sm sm:text-base font-bold text-slate-800 leading-relaxed">
                {{ questions[activeReviewIndex].texts[locale].question }}
              </h4>

              <!-- Correct / Chosen visualizer -->
              <div class="grid sm:grid-cols-2 gap-4">
                <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 flex flex-col justify-between">
                  <span class="text-[9px] font-black uppercase text-slate-400 tracking-wider">
                    {{ $t('service_view.trial_psikotes.results.your_answer') }}
                  </span>
                  <div class="flex items-center gap-2.5 mt-2">
                    <span :class="[
                      'w-7.5 h-7.5 rounded-lg flex items-center justify-center font-bold text-xs shrink-0',
                      userAnswers[questions[activeReviewIndex].id] === questions[activeReviewIndex].correctAnswer ? 'bg-emerald-500 text-slate-900 font-bold' : 'bg-rose-500 text-slate-900 font-bold'
                    ]">
                      {{ userAnswers[questions[activeReviewIndex].id] || '-' }}
                    </span>
                    <span class="text-xs text-slate-700 font-bold leading-tight truncate">
                      {{ userAnswers[questions[activeReviewIndex].id] ? questions[activeReviewIndex].texts[locale].options[userAnswers[questions[activeReviewIndex].id]] : 'Tidak dijawab' }}
                    </span>
                  </div>
                </div>

                <div class="p-3.5 rounded-2xl bg-emerald-50/40 border border-emerald-100 flex flex-col justify-between">
                  <span class="text-[9px] font-black uppercase text-emerald-600 tracking-wider">
                    {{ $t('service_view.trial_psikotes.results.correct_answer') }}
                  </span>
                  <div class="flex items-center gap-2.5 mt-2">
                    <span class="w-7.5 h-7.5 rounded-lg bg-emerald-500 text-slate-900 font-bold text-xs flex items-center justify-center shrink-0">
                      {{ questions[activeReviewIndex].correctAnswer }}
                    </span>
                    <span class="text-xs text-emerald-800 font-bold leading-tight truncate">
                      {{ questions[activeReviewIndex].texts[locale].options[questions[activeReviewIndex].correctAnswer] }}
                    </span>
                  </div>
                </div>
              </div>

              <!-- Explanation block -->
              <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-1">
                <h5 class="text-[9px] font-black uppercase text-slate-400 tracking-wider">
                  {{ $t('service_view.trial_psikotes.results.explanation') }}
                </h5>
                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed font-semibold">
                  {{ questions[activeReviewIndex].texts[locale].explanation }}
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- 2. ACTIVE TEST INTERFACE (FULL-WIDTH FLUID LAYOUT MATCHING IMAGE REFERENCE) -->
    <div v-else class="flex flex-col bg-[#f7f6f5] relative text-slate-800 h-screen overflow-hidden">
      
      <!-- Top header bar spanning 100% width (aligned with default site theme) -->
      <header class="h-16 flex items-center justify-between bg-white text-slate-800 px-6 border-b border-slate-200 shrink-0 sticky top-0 z-30 shadow-sm">
        <div class="flex flex-col">
          <span class="text-[11px] sm:text-xs font-black tracking-widest text-slate-400 uppercase">COMPETENCY ASSESSMENT</span>
          <span class="text-xs text-[#af1719] font-bold mt-0.5">
            {{ totalAnswered }} of {{ questions.length }} answered
          </span>
        </div>
        
        <div class="flex items-center gap-4">
          <!-- Mobile sidebar toggle hamburger -->
          <button 
            @click="isSidebarOpen = !isSidebarOpen" 
            class="lg:hidden flex items-center gap-1.5 text-slate-700 hover:text-[#af1719] bg-slate-50 hover:bg-slate-100 border border-slate-200 px-3 py-1.5 rounded-lg text-xs font-black transition-colors"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
            <span>Questions</span>
          </button>

          <!-- Real-time Countdown Timer -->
          <div class="flex items-center gap-2 bg-slate-50 border border-slate-200 px-3.5 py-1.5 rounded-lg font-mono text-sm sm:text-base font-bold shadow-xs">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#af1719] animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="text-slate-800">{{ formatTime(timer) }}</span>
          </div>
        </div>
      </header>

      <!-- Main workspace below header -->
      <div class="flex flex-1 min-h-0 relative">
        
        <!-- Desktop Questions Sidebar (Left side) -->
        <aside class="hidden lg:flex flex-col w-72 bg-white border-r border-slate-200 shrink-0 h-full overflow-y-auto">
          <div class="px-6 pt-5 pb-3 border-b border-slate-100 bg-slate-50/50">
            <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest">
              QUESTIONS
            </h4>
          </div>
          
          <div class="flex-1 overflow-y-auto overflow-x-hidden scrollbar-thin">
            <button 
              v-for="(q, index) in questions" 
              :key="q.id" 
              @click="selectQuestion(index)"
              :class="[
                'w-full flex items-center gap-3.5 px-6 py-4 border-b border-slate-100 text-left transition-all duration-150 relative',
                currentQuestionIndex === index 
                  ? 'bg-slate-50 text-[#af1719] shadow-sm font-bold' 
                  : 'hover:bg-slate-50/50 text-slate-700'
              ]"
            >
              <!-- Square indicator matching active selection state -->
              <span :class="[
                'w-8 h-8 rounded border flex items-center justify-center font-bold text-xs shrink-0 transition-all',
                currentQuestionIndex === index
                  ? 'border-[#af1719]/30 bg-[#af1719]/10 text-[#af1719] font-black'
                  : userAnswers[q.id]
                    ? 'border-emerald-600 bg-emerald-50 text-emerald-700 font-bold'
                    : 'border-slate-200 bg-slate-50 text-slate-400'
              ]">
                {{ index + 1 }}
              </span>
              
              <!-- Translated Category name -->
              <span class="text-xs font-bold leading-tight flex-1 tracking-tight truncate">
                {{ getCategoryLabel(q.category) }}
              </span>

              <!-- Status markers -->
              <div class="flex items-center gap-1.5 shrink-0">
                <!-- Checked answered indicator -->
                <span v-if="userAnswers[q.id] && currentQuestionIndex !== index" class="w-2 h-2 rounded-full bg-emerald-600"></span>
                <!-- Flag bookmark overlay -->
                <svg v-if="flaggedQuestions[q.id]" class="h-3.5 w-3.5 text-[#af1719]" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                </svg>
              </div>

              <!-- Left active bar indicator -->
              <span v-if="currentQuestionIndex === index" class="absolute left-0 top-0 bottom-0 w-1 bg-[#af1719]"></span>
            </button>
          </div>
        </aside>

        <!-- Mobile overlay Drawer navigation (Visible when triggered via Hamburger) -->
        <div v-if="isSidebarOpen" class="lg:hidden fixed inset-0 z-40 flex">
          <!-- Drawer Backdrop overlay -->
          <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs transition-opacity duration-300" @click="isSidebarOpen = false"></div>
          
          <!-- Slide-out sidebar container -->
          <div class="relative flex flex-col w-72 max-w-xs bg-white h-full z-50 shadow-2xl animate-slide-in">
            <div class="flex items-center justify-between px-6 py-4.5 border-b border-slate-100 bg-slate-50">
              <span class="text-xs font-black text-slate-800 uppercase tracking-widest">QUESTIONS</span>
              <button @click="isSidebarOpen = false" class="text-slate-400 hover:text-slate-600 focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5.5 w-5.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>
            
            <div class="flex-1 overflow-y-auto">
              <button 
                v-for="(q, index) in questions" 
                :key="'mob-'+q.id" 
                @click="selectQuestion(index); isSidebarOpen = false"
                :class="[
                  'w-full flex items-center gap-3 px-6 py-4 border-b border-slate-100 text-left transition-colors relative',
                  currentQuestionIndex === index 
                    ? 'bg-slate-50 text-[#af1719] font-bold' 
                    : 'hover:bg-slate-50/50 text-slate-700'
                ]"
              >
                <span :class="[
                  'w-8 h-8 rounded border flex items-center justify-center font-bold text-xs shrink-0',
                  currentQuestionIndex === index
                    ? 'border-[#af1719]/30 bg-[#af1719]/10 text-[#af1719] font-black'
                    : userAnswers[q.id]
                      ? 'border-emerald-600 bg-emerald-50 text-emerald-700 font-bold'
                      : 'border-slate-200 bg-slate-50 text-slate-400'
                ]">
                  {{ index + 1 }}
                </span>
                <span class="text-xs font-bold leading-tight flex-1 truncate">
                  {{ getCategoryLabel(q.category) }}
                </span>
                
                <div class="flex items-center gap-1.5 shrink-0">
                  <span v-if="userAnswers[q.id] && currentQuestionIndex !== index" class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                  <svg v-if="flaggedQuestions[q.id]" class="h-3.5 w-3.5 text-[#af1719]" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                  </svg>
                </div>
                
                <span v-if="currentQuestionIndex === index" class="absolute left-0 top-0 bottom-0 w-1 bg-[#af1719]"></span>
              </button>
            </div>
          </div>
        </div>

        <!-- Question Content Details Container (Right side) -->
        <main class="flex-1 flex flex-col justify-between p-6 sm:p-10 md:p-14 overflow-y-auto h-full bg-[#f8f7f5]">
          
          <!-- Centralised question block -->
          <div class="max-w-3xl mx-auto w-full flex-1 flex flex-col justify-center">
            
            <!-- Category Title, Question numbers, and Bookmark Bookmark -->
            <div class="flex items-start justify-between mb-8 border-b border-slate-200/50 pb-5">
              <div>
                <!-- Category badge -->
                <span class="inline-flex px-3.5 py-1.5 rounded-lg bg-red-50 text-[#af1719] text-[10px] font-black uppercase tracking-wider">
                  {{ getCategoryLabel(questions[currentQuestionIndex].category) }}
                </span>
                
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mt-3.5">
                  QUESTION {{ currentQuestionIndex + 1 }} OF {{ questions.length }}
                </p>
              </div>

              <!-- Flag Bookmark Toggle button -->
              <button 
                @click="toggleFlag" 
                :class="[
                  'inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border text-xs font-bold transition-all active:scale-95 shadow-xs',
                  isCurrentFlagged 
                    ? 'bg-red-50 border-red-200 text-[#af1719]' 
                    : 'bg-white border-slate-200 text-slate-400 hover:text-slate-600 hover:bg-slate-50'
                ]"
              >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" :fill="isCurrentFlagged ? 'currentColor' : 'none'" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                </svg>
                <span>{{ isCurrentFlagged ? 'Flagged' : 'Flag Question' }}</span>
              </button>
            </div>

            <!-- Main Question Statement in serif styling for executive corporate design -->
            <h2 class="text-xl sm:text-2xl lg:text-3.5xl font-serif text-[#111827] leading-[1.45] font-semibold mb-8">
              {{ questions[currentQuestionIndex].texts[locale].question }}
            </h2>

            <!-- stacked options (A, B, C, D) -->
            <div class="space-y-4 my-6">
              <button 
                v-for="(text, key) in questions[currentQuestionIndex].texts[locale].options" 
                :key="key" 
                @click="selectAnswer(key)"
                :class="[
                  'w-full flex items-stretch border rounded-xl bg-white overflow-hidden text-left transition-all duration-200 shadow-sm group min-h-[56px]',
                  userAnswers[questions[currentQuestionIndex].id] === key 
                    ? 'border-[#af1719] ring-2 ring-[#af1719]/10' 
                    : 'border-slate-200 hover:border-slate-300 hover:bg-slate-50/50'
                ]"
              >
                <!-- Left index character cell -->
                <span :class="[
                  'w-12 border-r flex items-center justify-center font-bold text-sm shrink-0 transition-colors',
                  userAnswers[questions[currentQuestionIndex].id] === key
                    ? 'border-[#af1719] bg-[#af1719] text-white font-black'
                    : 'border-slate-200 bg-slate-50 text-slate-500 group-hover:bg-slate-100/70 group-hover:text-slate-700'
                ]">
                  {{ key }}
                </span>
                
                <!-- Option contents -->
                <span class="flex-1 p-4 text-xs sm:text-sm md:text-[15px] font-semibold text-slate-700 leading-relaxed">
                  {{ text }}
                </span>
              </button>
            </div>

            <!-- Footer actions (Prev, Dots, Next) -->
            <div class="flex items-center justify-between border-t border-slate-200/60 pt-6 mt-10">
              <button 
                @click="prevQuestion" 
                :disabled="currentQuestionIndex === 0"
                class="px-5 py-2.5 rounded-lg border border-slate-200 text-slate-600 font-bold text-xs bg-white hover:bg-slate-50 transition-colors disabled:opacity-40 disabled:hover:bg-white active:scale-98"
              >
                &larr; Previous
              </button>

              <!-- Dot indicators representing current question block -->
              <div class="hidden sm:flex items-center gap-1.5 max-w-[280px] overflow-hidden">
                <button 
                  v-for="(q, index) in questions" 
                  :key="'workspace-dot-'+q.id"
                  @click="selectQuestion(index)"
                  :class="[
                    'w-3 h-3 rounded-xs transition-all shrink-0',
                    currentQuestionIndex === index 
                      ? 'bg-[#af1719] scale-120' 
                      : userAnswers[q.id] 
                        ? 'bg-emerald-500' 
                        : 'bg-slate-200 hover:bg-slate-300'
                  ]"
                  :title="'Go to ' + (index + 1)"
                ></button>
              </div>

              <!-- Next/Submit CTA -->
              <button 
                v-if="currentQuestionIndex < questions.length - 1"
                @click="nextQuestion"
                class="px-6 py-2.5 bg-slate-900 hover:bg-red-700 text-white rounded-lg font-bold text-xs transition-colors shadow-sm flex items-center gap-1.5 active:scale-98"
              >
                Next &rarr;
              </button>
              <button 
                v-else
                @click="triggerSubmit"
                class="px-6 py-2.5 bg-[#af1719] hover:bg-red-700 text-white rounded-lg font-bold text-xs transition-colors shadow-sm active:scale-98"
              >
                {{ $t('service_view.trial_psikotes.active.btn_submit') }}
              </button>
            </div>

          </div>
        </main>
      </div>

    </div>

    <!-- SUBMIT CONFIRMATION MODAL -->
    <transition name="fade">
      <div v-if="showSubmitModal" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" @click="closeSubmitModal"></div>

        <!-- Card container -->
        <div class="bg-white rounded-3xl border border-slate-100 max-w-sm w-full p-6 sm:p-8 shadow-2xl relative z-50 text-center space-y-5 transition-all">
          <div class="w-14 h-14 rounded-full bg-orange-50 flex items-center justify-center text-orange-600 mx-auto">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>

          <div class="space-y-1.5">
            <h3 class="text-xl font-black text-slate-800 tracking-tight leading-none">
              {{ $t('service_view.trial_psikotes.modal.title') }}
            </h3>
            <p v-if="totalUnanswered === 0" class="text-xs sm:text-sm text-slate-500 font-semibold leading-relaxed">
              {{ $t('service_view.trial_psikotes.modal.desc_all_answered') }}
            </p>
            <p v-else class="text-xs sm:text-sm text-slate-500 font-semibold leading-relaxed">
              {{ $t('service_view.trial_psikotes.modal.desc_unanswered', { count: totalUnanswered }) }}
            </p>
          </div>

          <div class="flex flex-col gap-2 pt-2">
            <button 
              @click="submitQuiz"
              class="w-full py-3.5 bg-[#1a365d] hover:bg-[#122846] text-orange-600 rounded-2xl font-black text-xs sm:text-sm tracking-wider shadow-md active:scale-95 transition-all"
            >
              {{ $t('service_view.trial_psikotes.modal.btn_confirm') }}
            </button>
            <button 
              @click="closeSubmitModal"
              class="w-full py-3.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-2xl font-bold text-xs sm:text-sm active:scale-95 transition-all"
            >
              {{ $t('service_view.trial_psikotes.modal.btn_cancel') }}
            </button>
          </div>
        </div>
      </div>
    </transition>
  </main>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.25s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

/* Custom scrollbar layout for question side menu */
.scrollbar-thin::-webkit-scrollbar {
  width: 4px;
}
.scrollbar-thin::-webkit-scrollbar-track {
  background: transparent;
}
.scrollbar-thin::-webkit-scrollbar-thumb {
  background-color: #cbd5e1;
  border-radius: 20px;
}

/* Simple animations for slide-in drawer on mobile */
@keyframes slideIn {
  from {
    transform: translateX(-100%);
  }
  to {
    transform: translateX(0);
  }
}
.animate-slide-in {
  animation: slideIn 0.25s ease-out forwards;
}
</style>
