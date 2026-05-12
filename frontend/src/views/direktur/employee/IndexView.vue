<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import Api from '@/api'

import Sidebar from '@/components/direktur/Sidebar.vue'
import Navbar from '@/components/direktur/Navbar.vue'

const router = useRouter()
const isSidebarOpen = ref(false)
const user = ref({ name: '', email: '', role: '' })
const employees = ref([])
const isLoading = ref(false)
const isSubmitting = ref(false)
const errors = ref(null)
const form = ref({ name: '', email: '', password: '' })
const pagination = ref({ current_page: 1, last_page: 1, total: 0 })

const breadcrumbsData = ref([
    { label: 'Karyawan', link: '/direktur/karyawan' },
    { label: 'Index', link: null }
])

const fetchEmployees = async (page = 1) => {
    isLoading.value = true
    try {
        const response = await Api.get('/direktur/employees', { params: { page } })
        employees.value = response.data.data || []
        pagination.value = response.data.pagination || pagination.value
    } catch (error) {
        console.error('Gagal memuat daftar karyawan:', error)
        if (error.response && error.response.status === 403) {
            alert('Akses hanya untuk Direktur.')
            router.push('/direktur/dashboard')
        }
    } finally {
        isLoading.value = false
    }
}

const submitEmployee = async () => {
    errors.value = null
    isSubmitting.value = true

    try {
        const response = await Api.post('/direktur/employees', form.value)
        alert(response.data.message || 'Karyawan berhasil ditambahkan')
        form.value = { name: '', email: '', password: '' }
        fetchEmployees(1)
    } catch (error) {
        console.error('Gagal menambahkan karyawan:', error)
        console.error('Error response:', error.response)
        if (error.response && error.response.data && error.response.data.errors) {
            errors.value = error.response.data.errors
        } else if (error.response?.data?.message) {
            alert(error.response.data.message)
        } else {
            alert('Terjadi kesalahan. Silakan coba lagi.')
        }
    } finally {
        isSubmitting.value = false
    }
}

const deleteEmployee = async (id) => {
    if (!confirm('Hapus karyawan ini?')) return

    try {
        await Api.delete(`/direktur/employees/${id}`)
        alert('Karyawan berhasil dihapus')
        fetchEmployees(pagination.value.current_page)
    } catch (error) {
        console.error('Gagal menghapus karyawan:', error)
        alert('Gagal menghapus karyawan. Silakan coba lagi.')
    }
}

const handleLogout = () => {
    sessionStorage.removeItem('user')
    sessionStorage.removeItem('token')
    router.push('/view/login')
}

onMounted(() => {
    const saved = sessionStorage.getItem('user')
    if (!saved) {
        router.push('/view/login')
        return
    }

    user.value = JSON.parse(saved)
    if (user.value.role !== 'direktur') {
        alert('Hanya akun Direktur yang dapat mengakses halaman ini.')
        router.push('/direktur/dashboard')
        return
    }

    fetchEmployees()
})
</script>

<template>
    <div class="flex min-h-screen bg-slate-50 relative overflow-x-hidden">
        <Sidebar v-model:is-open="isSidebarOpen" @logout="handleLogout" />

        <div class="flex-1 flex flex-col min-w-0">
            <Navbar :user="user" :breadcrumbs="breadcrumbsData" @toggle-sidebar="isSidebarOpen = !isSidebarOpen" @logout="handleLogout" />

            <main class="p-4 md:p-8">
                <div class="flex flex-col lg:flex-row justify-between gap-4 mb-8">
                    <div>
                        <h3 class="text-2xl font-black text-slate-900">Manajemen Karyawan</h3>
                        <p class="text-sm text-slate-500 mt-2">Tambah, hapus, dan lihat data karyawan.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 xl:grid-cols-[1.4fr_0.6fr] gap-6">
                    <section class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 overflow-hidden">
                        <div class="flex items-center justify-between mb-6 gap-4">
                            <div>
                                <h4 class="font-bold text-slate-900">Daftar Karyawan</h4>
                                <p class="text-xs text-slate-500 mt-1">Kelola akun karyawan yang dibuat oleh Direktur.</p>
                            </div>
                            <span class="inline-flex items-center gap-2 rounded-full bg-slate-100 px-4 py-2 text-xs font-semibold text-slate-600">
                                Total: {{ pagination.total }}
                            </span>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm text-slate-600">
                                <thead class="bg-slate-50 border-t border-b border-slate-100">
                                    <tr>
                                        <th class="px-4 py-3 font-semibold uppercase tracking-wider text-slate-400">Nama</th>
                                        <th class="px-4 py-3 font-semibold uppercase tracking-wider text-slate-400">Email</th>
                                        <th class="px-4 py-3 font-semibold uppercase tracking-wider text-slate-400">Role</th>
                                        <th class="px-4 py-3 text-right font-semibold uppercase tracking-wider text-slate-400">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    <tr v-if="isLoading">
                                        <td colspan="4" class="p-8 text-center text-slate-500">Memuat data karyawan...</td>
                                    </tr>
                                    <tr v-for="employee in employees" :key="employee.id" class="hover:bg-slate-50">
                                        <td class="px-4 py-4 font-semibold text-slate-800">{{ employee.name }}</td>
                                        <td class="px-4 py-4">{{ employee.email }}</td>
                                        <td class="px-4 py-4 uppercase text-xs text-slate-500">{{ employee.role }}</td>
                                        <td class="px-4 py-4 text-right">
                                            <div class="inline-flex gap-2 justify-end">
                                                <button @click="router.push(`/direktur/karyawan/edit/${employee.id}`)" class="inline-flex items-center gap-2 text-slate-700 hover:bg-slate-100 border border-slate-200 rounded-xl px-3 py-2 text-xs font-bold transition-all">
                                                    Edit
                                                </button>
                                                <button @click="deleteEmployee(employee.id)" class="inline-flex items-center gap-2 text-red-600 hover:bg-red-50 border border-red-100 rounded-xl px-3 py-2 text-xs font-bold transition-all">
                                                    Hapus
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr v-if="!isLoading && employees.length === 0">
                                        <td colspan="4" class="p-8 text-center text-slate-400">Belum ada karyawan yang ditambahkan.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </section>

                    <section class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6">
                        <h4 class="font-bold text-slate-900 mb-4">Tambah Karyawan Baru</h4>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Lengkap</label>
                                <input v-model="form.name" type="text" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 placeholder-slate-400 focus:border-slate-400 focus:ring-2 focus:ring-slate-100 outline-none" placeholder="Nama karyawan" />
                                <p v-if="errors?.name" class="text-xs text-red-600 mt-1">{{ errors.name?.[0] }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Email</label>
                                <input v-model="form.email" type="email" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 placeholder-slate-400 focus:border-slate-400 focus:ring-2 focus:ring-slate-100 outline-none" placeholder="email@domain.com" />
                                <p v-if="errors?.email" class="text-xs text-red-600 mt-1">{{ errors.email?.[0] }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Password</label>
                                <input v-model="form.password" type="password" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 placeholder-slate-400 focus:border-slate-400 focus:ring-2 focus:ring-slate-100 outline-none" placeholder="Minimal 8 karakter" />
                                <p v-if="errors?.password" class="text-xs text-red-600 mt-1">{{ errors.password?.[0] }}</p>
                            </div>

                            <button @click.prevent="submitEmployee" :disabled="isSubmitting" class="w-full bg-slate-900 text-white rounded-2xl py-3 font-bold hover:bg-slate-800 transition-colors disabled:opacity-50">
                                {{ isSubmitting ? 'Menyimpan...' : 'Tambah Karyawan' }}
                            </button>
                        </div>
                    </section>
                </div>
            </main>
        </div>
    </div>
</template>

<style scoped>
button, a { cursor: pointer; }
</style>
