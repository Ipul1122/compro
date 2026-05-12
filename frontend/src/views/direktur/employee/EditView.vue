<script setup>
import { ref, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import Api from '@/api'

import Sidebar from '@/components/direktur/Sidebar.vue'
import Navbar from '@/components/direktur/Navbar.vue'

const router = useRouter()
const route = useRoute()
const isSidebarOpen = ref(false)
const user = ref({ name: '', email: '', role: '' })
const employee = ref({ name: '', email: '', role: 'employee' })
const isLoading = ref(true)
const isSubmitting = ref(false)
const errors = ref(null)

const breadcrumbsData = ref([
    { label: 'Karyawan', link: '/direktur/karyawan' },
    { label: 'Edit', link: null }
])

const fetchEmployee = async () => {
    try {
        const response = await Api.get(`/direktur/employees/${route.params.id}`)
        employee.value = response.data.data
    } catch (error) {
        console.error('Gagal memuat data karyawan:', error)
        alert('Karyawan tidak ditemukan atau tidak dapat diakses.')
        router.push('/direktur/karyawan')
    } finally {
        isLoading.value = false
    }
}

const submitUpdate = async () => {
    isSubmitting.value = true
    errors.value = null

    try {
        const payload = {
            name: employee.value.name,
            email: employee.value.email
        }

        if (employee.value.password) {
            payload.password = employee.value.password
        }

        const response = await Api.put(`/direktur/employees/${route.params.id}`, payload)
        alert(response.data.message || 'Data karyawan berhasil diperbarui')
        router.push('/direktur/karyawan')
    } catch (error) {
        console.error('Gagal memperbarui karyawan:', error)
        if (error.response?.data?.errors) {
            errors.value = error.response.data.errors
        } else {
            alert('Terjadi kesalahan saat menyimpan data karyawan.')
        }
    } finally {
        isSubmitting.value = false
        employee.value.password = ''
    }
}

const handleLogout = () => {
    sessionStorage.removeItem('user')
    sessionStorage.removeItem('token')
    router.push('/view/login')
}

onMounted(() => {
    const savedUser = sessionStorage.getItem('user')
    if (!savedUser) {
        router.push('/view/login')
        return
    }

    user.value = JSON.parse(savedUser)
    if (user.value.role !== 'direktur') {
        alert('Hanya Direktur yang dapat mengakses halaman ini.')
        router.push('/direktur/dashboard')
        return
    }

    fetchEmployee()
})
</script>

<template>
    <div class="flex min-h-screen bg-slate-50 relative overflow-x-hidden">
        <Sidebar v-model:is-open="isSidebarOpen" @logout="handleLogout" />

        <div class="flex-1 flex flex-col min-w-0">
            <Navbar :user="user" :breadcrumbs="breadcrumbsData" @toggle-sidebar="isSidebarOpen = !isSidebarOpen" @logout="handleLogout" />

            <main class="p-4 md:p-8">
                <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6">
                    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 mb-6">
                        <div>
                            <h3 class="text-2xl font-black text-slate-900">Edit Data Karyawan</h3>
                            <p class="text-sm text-slate-500 mt-2">Perbarui nama, email, atau password karyawan.</p>
                        </div>
                        <button @click="router.push('/direktur/karyawan')" class="bg-slate-100 text-slate-700 px-5 py-3 rounded-2xl font-semibold hover:bg-slate-200 transition-colors">
                            Kembali ke List
                        </button>
                    </div>

                    <div v-if="isLoading" class="p-8 text-center text-slate-500">Memuat data karyawan...</div>

                    <div v-else class="space-y-5">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Lengkap</label>
                            <input v-model="employee.name" type="text" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-slate-400 outline-none" placeholder="Nama karyawan" />
                            <p v-if="errors?.name" class="text-xs text-red-600 mt-1">{{ errors.name?.[0] }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Email</label>
                            <input v-model="employee.email" type="email" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-slate-400 outline-none" placeholder="email@domain.com" />
                            <p v-if="errors?.email" class="text-xs text-red-600 mt-1">{{ errors.email?.[0] }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Password Baru <span class="text-slate-400 text-xs">(opsional)</span></label>
                            <input v-model="employee.password" type="password" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-slate-400 outline-none" placeholder="Kosongkan jika tidak ingin mengubah" />
                            <p v-if="errors?.password" class="text-xs text-red-600 mt-1">{{ errors.password?.[0] }}</p>
                        </div>

                        <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                            <button @click.prevent="submitUpdate" :disabled="isSubmitting" class="w-full sm:w-auto bg-slate-900 text-white rounded-2xl px-6 py-3 font-bold hover:bg-slate-800 disabled:opacity-50 transition-colors">
                                {{ isSubmitting ? 'Menyimpan...' : 'Simpan Perubahan' }}
                            </button>
                            <button @click="router.push('/direktur/karyawan')" class="w-full sm:w-auto border border-slate-200 rounded-2xl px-6 py-3 text-slate-600 hover:bg-slate-100 transition-colors">
                                Batal
                            </button>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</template>

<style scoped>
button, a { cursor: pointer; }
</style>
