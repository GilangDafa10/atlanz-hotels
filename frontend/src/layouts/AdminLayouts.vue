<template>
  <div class="flex h-screen bg-[#f3f7fc] overflow-hidden relative">
    <!-- Mobile Overlay -->
    <div
      v-if="showSidebar"
      class="fixed inset-0 bg-black/50 z-40 lg:hidden"
      @click="showSidebar = false"
    ></div>

    <!-- Sidebar -->
    <aside
      class="w-60 bg-[#0c2c67] text-white px-5 py-6 flex flex-col shrink-0 h-full overflow-y-auto fixed lg:sticky top-0 z-50 lg:z-auto transition-transform duration-300"
      :class="{
        '-translate-x-full lg:translate-x-0': !showSidebar,
        'translate-x-0': showSidebar,
      }"
    >
      <div class="flex items-center justify-between mb-10">
        <h1 class="text-4xl font-bold text-white tracking-wide ml-3">ATLANZ</h1>
        <button
          @click="showSidebar = false"
          class="lg:hidden text-white p-2 hover:bg-white/10 rounded"
          aria-label="Close menu"
        >
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M6 18L18 6M6 6l12 12"
            />
          </svg>
        </button>
      </div>

      <nav class="space-y-2">
        <RouterLink
          v-for="item in menu"
          :key="item.name"
          :to="item.to"
          @click="closeSidebarOnMobile"
          class="flex items-center box-content gap-3 px-4 py-3 w-full rounded-l-xl rounded-r-none font-medium transition text-sm mr-[-1.25rem]"
          :class="{
            'bg-[#f3f7fc] text-[#0d3b66]': $route.path.startsWith(item.to),
            'hover:bg-[#f3f7fc] hover:text-[#0d3b66]': !$route.path.startsWith(item.to),
          }"
        >
          <component :is="item.icon" class="w-5 h-5" />
          {{ item.name }}
        </RouterLink>
      </nav>
    </aside>

    <div class="flex-1 flex flex-col overflow-y-auto w-full lg:w-auto">
      <header
        class="bg-white px-4 md:px-6 lg:px-10 py-4 border-b flex justify-between lg:justify-end items-center gap-3 sticky top-0 z-30 shadow-sm"
      >
        <!-- Hamburger Button (Mobile Only) -->
        <button
          @click="showSidebar = !showSidebar"
          class="lg:hidden text-gray-700 p-2 hover:bg-gray-100 rounded"
          aria-label="Toggle menu"
        >
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M4 6h16M4 12h16M4 18h16"
            />
          </svg>
        </button>

        <div class="flex items-center gap-3">
          <!-- Avatar Button -->
          <div
            class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold cursor-pointer profile-btn"
            @click="toggleMenu"
          >
            {{ user?.name?.charAt(0).toUpperCase() || '?' }}
          </div>

          <!-- Name -->
          <p class="font-semibold cursor-pointer hidden sm:block" @click="toggleMenu">
            {{ user?.name }}
          </p>
        </div>

        <!-- Dropdown Menu -->
        <div
          v-if="showMenu"
          class="animate-fade profile-menu absolute top-16 right-2 bg-white shadow-xl border border-gray-300 rounded-lg animate-fade"
        >
          <button
            class="cursor-pointer w-full text-left py-2 px-3 hover:bg-gray-100 rounded-md text-sm"
            @click="router.push('/')"
          >
            Home
          </button>

          <button
            class="cursor-pointer w-full text-left py-2 px-3 hover:bg-gray-100 rounded-md text-sm text-red-600"
            @click="logout"
          >
            Logout
          </button>
        </div>
      </header>

      <main class="p-4 md:p-6 lg:p-8 flex-1">
        <RouterView />
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'
// Pastikan icon Users diimpor
import {
  Home,
  BedDouble,
  Users,
  NotebookPen,
  HelpCircle,
  Settings,
  Package,
  Utensils,
} from 'lucide-vue-next'

const router = useRouter()
const showMenu = ref(false)
const showSidebar = ref(false)

const toggleMenu = () => {
  showMenu.value = !showMenu.value
}

const closeSidebarOnMobile = () => {
  if (window.innerWidth < 1024) {
    showSidebar.value = false
  }
}

const closeMenu = (event) => {
  // Cek kalau klik di luar menu dropdown
  if (!event.target.closest('.profile-menu') && !event.target.closest('.profile-btn')) {
    showMenu.value = false
  }
}

onMounted(() => {
  document.addEventListener('click', closeMenu)
})

onBeforeUnmount(() => {
  document.removeEventListener('click', closeMenu)
})

const logout = async () => {
  try {
    await axios.post(
      'http://localhost:8000/api/logout',
      {},
      {
        headers: {
          Authorization: `Bearer ${localStorage.getItem('token')}`,
        },
      },
    )

    localStorage.removeItem('token')
    localStorage.removeItem('isLoggedIn')

    router.push('/login')
  } catch (err) {
    console.error('Logout gagal:', err)
  }
}

const user = ref(null)
onMounted(async () => {
  try {
    const token = localStorage.getItem('token')

    const res = await axios.get('http://localhost:8000/api/me', {
      headers: {
        Authorization: `Bearer ${token}`,
      },
    })

    user.value = res.data.data
  } catch (err) {
    console.error('Gagal fetch user:', err)
  }
})

const menu = [
  { name: 'Dashboard', to: { name: 'admin.dashboard' }, icon: Home },
  { name: 'Jenis Kamar', to: { name: 'admin.room.types' }, icon: BedDouble },
  { name: 'Kamar', to: { name: 'admin.rooms' }, icon: BedDouble },
  { name: 'Fasilitas', to: { name: 'admin.facilities' }, icon: NotebookPen },
  { name: 'Additional Services', to: { name: 'admin.services' }, icon: Package },
  { name: 'Users', to: { name: 'admin.users' }, icon: Users },
  { name: 'Booking Details', to: { name: 'admin.booking.details' }, icon: NotebookPen },
  { name: 'Help', to: '/help', icon: HelpCircle }, // tetap pakai path jika tidak di bawah admin
]
</script>

<style scoped>
.animate-fade {
  animation: fadeIn 0.15s ease-out;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(-6px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>
