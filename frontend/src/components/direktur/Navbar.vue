<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { useRouter } from 'vue-router'
import Api from '@/api'

const props = defineProps({
    user: {
        type: Object,
        default: () => ({ name: 'Direktur', email: '', role: 'direktur' })
    },
    notificationCount: {
        type: Number,
        default: null
    },
    notifications: {
        type: Array,
        default: () => null
    },
    breadcrumbs: {
        type: Array,
        default: () => [{ label: 'Overview', link: null }]
    }
})

const emit = defineEmits(['toggle-sidebar', 'logout'])

const router = useRouter()
const isProfileOpen = ref(false)
const isNotifOpen = ref(false)
const localNotifications = ref([])
const localUnreadCount = ref(0)
let notifInterval = null

const displayedNotifications = computed(() => {
    if (localNotifications.value.length > 0) return localNotifications.value
    return Array.isArray(props.notifications) ? props.notifications : []
})

const displayedNotificationCount = computed(() => {
    if (localUnreadCount.value > 0) return localUnreadCount.value
    if (typeof props.notificationCount === 'number') return props.notificationCount
    return localUnreadCount.value
})

const fetchLocalNotifications = async () => {
    try {
        const r = await Api.get('/direktur/notifications', { params: { limit: 8 } })
        localNotifications.value = r.data?.data || []
        localUnreadCount.value = r.data?.meta?.unread_count || 0
    } catch (error) {
        console.error('Notif fetch error:', error)
    }
}

const markAllAsRead = async () => {
    try {
        await Api.post('/direktur/notifications/read')
        localUnreadCount.value = 0
        localNotifications.value = localNotifications.value.map((item) => ({ ...item, is_read: true }))
    } catch (error) {
        console.error('Mark read error:', error)
    }
}

const openNotification = async (item) => {
    const targetUrl = item?.url || '/direktur/articles?status=pending'
    try {
        if (!item?.is_read && item?.id) {
            await Api.post('/direktur/notifications/read', { ids: [item.id] })
            localNotifications.value = localNotifications.value.map((n) =>
                n.id === item.id ? { ...n, is_read: true } : n
            )
            localUnreadCount.value = Math.max(0, localUnreadCount.value - 1)
        }
    } catch (error) {
        console.error('Open notif error:', error)
    } finally {
        isNotifOpen.value = false
        router.push(targetUrl)
    }
}

onMounted(() => {
    fetchLocalNotifications()
    notifInterval = setInterval(fetchLocalNotifications, 5000)
})

onUnmounted(() => {
    clearInterval(notifInterval)
})

const handleLogout = async () => {
    try {
        await Api.post('/logout')
    } catch (error) {
        console.error('Logout error:', error)
    } finally {
        sessionStorage.removeItem('token')
        sessionStorage.removeItem('user')
        router.push({ name: 'login' })
        emit('logout')
    }
}
</script>

<template>
    <div
        v-if="isProfileOpen || isNotifOpen"
        @click="isProfileOpen = false; isNotifOpen = false"
        class="fixed inset-0 z-[100] bg-transparent"
    ></div>

    <nav class="h-20 bg-white border-b border-slate-100 px-4 md:px-8 flex items-center justify-between sticky top-0 z-[50]">
        <div class="flex items-center gap-4 min-w-0">
            <button @click="$emit('toggle-sidebar')" class="lg:hidden p-2 text-slate-600 hover:bg-slate-50 rounded-lg cursor-pointer shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <nav class="flex items-center text-sm md:text-base font-bold overflow-hidden">
                <div v-for="(item, index) in breadcrumbs" :key="index" class="flex items-center whitespace-nowrap">
                    <span v-if="index > 0" class="mx-2 text-slate-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </span>
                    <span
                        :class="index === breadcrumbs.length - 1 ? 'text-slate-900' : 'text-slate-400 font-medium'"
                        class="uppercase tracking-tight truncate max-w-[100px] md:max-w-none"
                    >
                        {{ item.label }}
                    </span>
                </div>
            </nav>
        </div>

        <div class="relative z-[110] flex items-center gap-2">
            <div class="relative">
                <button
                    @click="isNotifOpen = !isNotifOpen; isProfileOpen = false; fetchLocalNotifications(); if (isNotifOpen) markAllAsRead()"
                    class="relative h-10 w-10 rounded-xl border border-slate-200 bg-white text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-colors flex items-center justify-center"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V4a2 2 0 10-4 0v1.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    <span
                        v-if="displayedNotificationCount > 0"
                        class="absolute -top-1 -right-1 min-w-5 h-5 px-1 rounded-full bg-red-500 text-white text-[10px] font-bold flex items-center justify-center"
                    >
                        {{ displayedNotificationCount > 99 ? '99+' : displayedNotificationCount }}
                    </span>
                </button>

                <transition name="pop">
                    <div v-if="isNotifOpen" class="absolute right-0 mt-3 w-80 bg-white rounded-2xl shadow-[0_20px_50px_rgba(0,0,0,0.15)] border border-slate-100 overflow-hidden z-[120]">
                        <div class="px-4 py-3 border-b border-slate-100">
                            <p class="text-sm font-bold text-slate-900">Notifikasi Direktur</p>
                            <p class="text-[11px] text-slate-400 mt-1">Aktivitas terbaru artikel.</p>
                        </div>
                        <div class="max-h-80 overflow-y-auto divide-y divide-slate-100">
                            <button
                                v-for="item in displayedNotifications"
                                :key="item.id"
                                type="button"
                                class="w-full px-4 py-3 text-left hover:bg-slate-50 transition-colors cursor-pointer"
                                :class="!item.is_read ? 'bg-amber-50/40' : ''"
                                @click.stop="openNotification(item)"
                            >
                                <p class="text-xs text-slate-700 leading-5">{{ item.message }}</p>
                                <p class="text-[11px] text-slate-400 mt-1">{{ item.created_at_human }}</p>
                            </button>
                            <div v-if="!displayedNotifications.length" class="px-4 py-6 text-center text-xs text-slate-400">
                                Belum ada aktivitas artikel.
                            </div>
                        </div>
                    </div>
                </transition>
            </div>

            <button @click="isProfileOpen = !isProfileOpen; isNotifOpen = false" class="flex items-center gap-3 md:gap-4 hover:bg-slate-50 p-1.5 rounded-2xl transition-all cursor-pointer outline-none shrink-0">
                <div class="text-right hidden sm:block">
                    <p class="text-sm font-black text-slate-900 leading-none">{{ user?.name }}</p>
                    <p class="text-[10px] text-slate-400 mt-1 uppercase tracking-widest font-bold">{{ user?.role === 'direktur' ? 'Direktur' : user?.role === 'admin' ? 'Admin' : user?.role }}</p>
                </div>
                <img :src="`https://ui-avatars.com/api/?name=${user?.name}&background=0f172a&color=fff&bold=true`" class="h-10 w-10 rounded-full border-2 border-white shadow-sm shrink-0" />
            </button>

            <transition name="pop">
                <div v-if="isProfileOpen" class="absolute right-0 mt-3 w-60 bg-white rounded-2xl shadow-[0_20px_50px_rgba(0,0,0,0.15)] border border-slate-100 py-2 z-[120]">
                    <div class="px-4 py-3 border-b border-slate-50 mb-1">
                        <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest">Active Email</p>
                        <p class="text-sm font-bold text-slate-900 truncate">{{ user.email }}</p>
                    </div>
                </div>
            </transition>
        </div>
    </nav>
</template>

<style scoped>
.pop-enter-active, .pop-leave-active { transition: all 0.2s ease-out; }
.pop-enter-from, .pop-leave-to { transform: translateY(-10px) scale(0.95); opacity: 0; }
</style>
