<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

/** * AXIOS CONFIGURATION
 * baseURL should be the root of your Laravel server.
 * Do NOT include '/api/login' here, or your other API calls will break.
 */
axios.defaults.baseURL = 'http://localhost:8000/'
axios.defaults.withCredentials = true

const router = useRouter()
const email = ref('')
const password = ref('')
const isLoading = ref(false)
const errorMessage = ref('')

const handleLogin = async () => {
    isLoading.value = true
    errorMessage.value = ''

    try {
        // 1. Get CSRF Cookie (Necessary for Laravel Sanctum stateful auth)
        // This hits: http://localhost:8000/sanctum/csrf-cookie
        await axios.get('/sanctum/csrf-cookie')

        // 2. Attempt Login
        // This hits: http://localhost:8000/api/login
        const response = await axios.post('/api/login', {
            email: email.value,
            password: password.value
        })

        // 3. On Success
        console.log('Login successful', response.data)

        // Redirect to your admin dashboard
        router.push('/admin')

    } catch (error) {
        // 4. Handle Errors
        if (error.response && error.response.status === 401) {
            errorMessage.value = 'The credentials you entered are incorrect.'
        } else if (error.response && error.response.status === 422) {
            errorMessage.value = 'Please check your email format or requirements.'
        } else {
            errorMessage.value = 'Unable to connect to the server. Please try again later.'
        }
        console.error('Login Error:', error)
    } finally {
        isLoading.value = false
    }
}
</script>

<template>
    <div class="min-h-screen bg-slate-50 flex items-center justify-center px-6 py-12">
        <div class="max-w-md w-full">
            <div class="text-center mb-10">
                <router-link to="/">
                    <img src="/src/img/webcakrawala-logo.png" alt="Logo" class="h-12 w-auto mx-auto mb-6" />
                </router-link>
                <h2 class="text-3xl font-black text-slate-900 tracking-tighter">Admin Portal</h2>
                <p class="text-slate-500 mt-2">Please enter your details to sign in.</p>
            </div>

            <div class="bg-white p-8 rounded-3xl shadow-[0_20px_50px_rgba(0,0,0,0.05)] border border-slate-100">

                <div v-if="errorMessage"
                    class="mb-6 p-4 bg-red-50 border border-red-100 text-red-600 text-sm rounded-xl font-bold">
                    {{ errorMessage }}
                </div>

                <form @submit.prevent="handleLogin" class="space-y-6">
                    <div>
                        <label for="email" class="block text-sm font-bold text-slate-700 mb-2">Email Address</label>
                        <input v-model="email" type="email" id="email" placeholder="name@company.com"
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-brand focus:ring-2 focus:ring-brand/10 outline-none transition-all"
                            required />
                    </div>

                    <div>
                        <div class="flex justify-between mb-2">
                            <label for="password" class="block text-sm font-bold text-slate-700">Password</label>
                            <a href="#" class="text-xs font-bold text-brand hover:underline">Forgot?</a>
                        </div>
                        <input v-model="password" type="password" id="password" placeholder="••••••••"
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-brand focus:ring-2 focus:ring-brand/10 outline-none transition-all"
                            required />
                    </div>

                    <button type="submit" :disabled="isLoading"
                        class="w-full bg-slate-900 text-white py-4 rounded-xl font-black hover:bg-brand transition-all shadow-lg hover:shadow-brand/30 active:scale-[0.98] disabled:opacity-70 flex justify-center items-center">
                        <span v-if="!isLoading">Sign In</span>
                        <span v-else class="flex gap-2">
                            <span class="w-2 h-2 bg-white rounded-full animate-bounce"></span>
                            <span class="w-2 h-2 bg-white rounded-full animate-bounce [animation-delay:0.2s]"></span>
                            <span class="w-2 h-2 bg-white rounded-full animate-bounce [animation-delay:0.4s]"></span>
                        </span>
                    </button>
                </form>
            </div>

            <p class="text-center mt-8 text-sm text-slate-500">
                Not an admin?
                <router-link to="/" class="font-bold text-slate-900 hover:text-brand transition-colors">
                    Back to Home
                </router-link>
            </p>
        </div>
    </div>
</template>

<style scoped>
.text-brand {
    color: #ea4435;
}

.bg-brand {
    background-color: #ea4435;
}

.focus\:border-brand:focus {
    border-color: #ea4435;
}

.focus\:ring-brand\/10:focus {
    --tw-ring-color: rgba(234, 68, 53, 0.1);
}

.hover\:shadow-brand\/30:hover {
    box-shadow: 0 10px 15px -3px rgba(234, 68, 53, 0.3);
}
</style>