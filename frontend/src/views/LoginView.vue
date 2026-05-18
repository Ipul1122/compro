<script setup>
import { ref, onMounted, nextTick } from 'vue'
import { useRouter } from 'vue-router'
import Api from '@/api'
import axios from 'axios'

// Axios Configuration - Gunakan env variable
const apiBaseUrl = import.meta.env.VITE_API_BASE_URL || 'http://localhost:8000/api'
axios.defaults.baseURL = apiBaseUrl.replace(/\/api\/?$/, '')
axios.defaults.withCredentials = true

const router = useRouter()
const email = ref('')
const password = ref('')
const showPassword = ref(false)
const isLoading = ref(false)
const errorMessage = ref('')
const successMessage = ref('')
const activeAccount = ref('admin')
const isTransitioning = ref(false)

const accounts = {
    admin: {
        label: 'Admin',
        icon: 'shield',
        color: 'red'
    },
    director: {
        label: 'Direktur',
        icon: 'crown',
        color: 'amber'
    }
}

const switchAccount = async (role) => {
    if (activeAccount.value === role || isTransitioning.value) return

    isTransitioning.value = true

    // Add fade out effect
    const formElement = document.querySelector('form')
    if (formElement) {
        formElement.style.opacity = '0.5'
        formElement.style.transform = 'translateY(10px)'
    }

    await nextTick()

    activeAccount.value = role
    // Mengosongkan input demi keamanan (mencegah auto-fill)
    email.value = ''
    password.value = ''

    // Add fade in effect
    setTimeout(() => {
        if (formElement) {
            formElement.style.opacity = '1'
            formElement.style.transform = 'translateY(0)'
        }
        isTransitioning.value = false
    }, 150)
}

onMounted(() => {
    // Inisialisasi awal tanpa memanggil auto-fill
})

const handleLogin = async () => {
    isLoading.value = true
    errorMessage.value = ''
    successMessage.value = ''

    try {
        // 1. Get CSRF Cookie (Bisa tetap dipertahankan jika butuh proteksi ekstra)
        await axios.get('/sanctum/csrf-cookie')

        // 2. Attempt Login
        const loginEmail = email.value.includes('@') ? email.value : `${email.value}@cakrawala-internasional.co.id`
        
        const response = await axios.post('/api/login', {
            email: loginEmail,
            password: password.value
        })

        // 3. SUCCESS: Cek apakah respons success bernilai true
        if (response.data.success) {
            successMessage.value = 'Login berhasil! Mengalihkan ke dashboard...'

            // PERBAIKAN: Gunakan response.data.data.user sesuai struktur API
            sessionStorage.setItem('user', JSON.stringify(response.data.data.user))

            // TAMBAHAN WAJIB: Simpan token untuk otorisasi endpoint dashboard/admin
            sessionStorage.setItem('token', response.data.data.token)

            // Set default header axios agar otomatis mengirim token di request berikutnya
            axios.defaults.headers.common['Authorization'] = `Bearer ${response.data.data.token}`

            // Redirect ke dashboard setelah delay singkat untuk show success message
            setTimeout(() => {
                if (response.data.data.user.role === 'direktur') {
                    router.push('/direktur/dashboard')
                } else {
                    router.push('/admin/dashboard')
                }
            }, 1000)
        }

    } catch (error) {
        if (error.response && error.response.status === 401) {
            errorMessage.value = 'Email atau password yang Anda masukkan salah.'
        } else if (error.response && error.response.status === 422) {
            errorMessage.value = 'Silakan periksa format email atau persyaratan lainnya.'
        } else if (error.response && error.response.status === 429) {
            errorMessage.value = 'Terlalu banyak percobaan login. Silakan coba lagi nanti.'
        } else {
            errorMessage.value = 'Tidak dapat terhubung ke server. Silakan coba lagi nanti.'
        }
    } finally {
        isLoading.value = false
    }
}
</script>

<template>
    <div class="min-h-screen flex items-center justify-center p-4 transition-all duration-500 bg-gradient-to-br from-slate-50 via-white to-slate-100">
        <!-- Background Pattern -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-red-50 rounded-full blur-3xl opacity-30"></div>
            <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-blue-50 rounded-full blur-3xl opacity-20"></div>
        </div>

        <div class="relative w-full max-w-md">
            <!-- Login Card -->
            <div class="rounded-2xl shadow-xl border p-8 backdrop-blur-sm transition-all duration-300 bg-white/80 border-slate-200/50">

                <!-- Error Message -->
                <div v-if="errorMessage"
                    class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 text-sm rounded-xl font-medium">
                    {{ errorMessage }}
                </div>

                <!-- Success Message -->
                <div v-if="successMessage"
                    class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 text-sm rounded-xl font-medium">
                    {{ successMessage }}
                </div>

                <!-- Role Switcher -->
                <div class="mb-8">
                    <div class="flex rounded-xl p-1 transition-all duration-300 bg-slate-100 role-switcher">
                        <button
                            type="button"
                            v-for="(account, role) in accounts"
                            :key="role"
                            @click="switchAccount(role)"
                            :class="[
                                'flex-1 py-3 px-4 rounded-lg text-sm font-semibold transition-all duration-200 flex items-center justify-center gap-2',
                                activeAccount === role
                                    ? 'bg-red-500 text-white shadow-sm'
                                    : 'text-slate-600 hover:text-slate-900 hover:bg-white/50'
                            ]"
                        >
                            <!-- Admin Icon -->
                            <svg v-if="role === 'admin'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                            <!-- Director Icon (Crown) -->
                            <svg v-else class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                            </svg>
                            {{ account.label }}
                        </button>
                    </div>
                </div>

                <!-- Login Form -->
                <form @submit.prevent="handleLogin" class="space-y-6">
                    <div>
                        <label for="email" class="block text-sm font-semibold mb-2 text-slate-700">
                            Username
                        </label>
                        <input
                            id="email"
                            name="email"
                            v-model="email"
                            type="text"
                            class="w-full px-4 py-3 rounded-xl border shadow-sm bg-white text-slate-900 placeholder-slate-400 outline-none transition-all duration-200 border-slate-200 focus:border-red-500 focus:ring-2 focus:ring-red-500/10"
                            placeholder="Masukkan email tanpa @"
                            required
                        />
                        <p class="mt-2 text-sm text-slate-500">
                            @cakrawala-internasional.co.id
                        </p>
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label for="password" class="block text-sm font-semibold text-slate-700">
                                Password
                            </label>
                            <!-- <a href="#" class="text-xs font-medium transition-colors text-red-500 hover:text-red-600">
                                Forgot?
                            </a> -->
                        </div>
                        <div class="relative">
                            <input
                                id="password"
                                name="password"
                                v-model="password"
                                :type="showPassword ? 'text' : 'password'"
                                class="w-full px-4 py-3 pr-12 rounded-xl border bg-white text-slate-900 placeholder-slate-400 outline-none transition-all duration-200 border-slate-200 focus:border-red-500 focus:ring-2 focus:ring-red-500/10"
                                placeholder="Masukkan Password"
                                required
                            />
                            <button 
                                type="button" 
                                @click="showPassword = !showPassword"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 focus:outline-none transition-colors"
                            >
                                <!-- Eye Open Icon -->
                                <svg v-if="!showPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <!-- Eye Closed Icon -->
                                <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <button
                        type="submit"
                        :disabled="isLoading"
                        class="w-full py-4 rounded-xl font-semibold transition-all duration-200 shadow-lg flex items-center justify-center group disabled:shadow-none bg-slate-900 hover:bg-slate-800 disabled:bg-slate-400 text-white hover:shadow-xl"
                    >
                        <span v-if="!isLoading" class="group-hover:scale-105 transition-transform duration-200">
                            Sign In
                        </span>
                        <span v-else class="flex items-center gap-2">
                            <svg class="animate-spin h-4 w-4" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Signing in...
                        </span>
                    </button>
                </form>

                <!-- Footer Text -->
                <p class="mt-6 text-xs text-center text-slate-500">
                    Secure enterprise login portal
                </p>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Form transition effects */
form {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Role switcher hover effects */
.flex.bg-slate-100 > button {
    position: relative;
    overflow: hidden;
}

.flex.bg-slate-100 > button::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.5s;
}

.flex.bg-slate-100 > button:hover::before {
    left: 100%;
}

/* Enhanced focus states */
input:focus,
button:focus {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

/* Loading button animation */
@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.7;
    }
}

button:disabled {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

/* Mobile optimizations */
@media (max-width: 640px) {
    .min-h-screen {
        padding: 1rem;
    }

    /* Pastikan input username full width di mobile karena domain suffix dipindah ke bawah */
    #email {
        border-radius: 0.75rem !important;
    }

    /* Role switcher tetap horizontal di mobile */
    .role-switcher {
        flex-direction: row;
    }
}
</style>