<template>
  <nav class="fixed top-0 left-0 w-full z-50 bg-[#0b1f4f] font-[Montserrat]">
    <div class="flex justify-between items-center px-6 lg:px-20 py-4">

      <!-- LOGO -->
      <img 
        src="@/assets/Logo Atlanz white.png" 
        class="w-32 lg:w-40 cursor-pointer" 
        @click="goHome" 
      />

      <!-- MOBILE BUTTON -->
      <button @click="toggleMobileMenu" class="lg:hidden text-white text-2xl">
        <i class="fa-solid" :class="showMobileMenu ? 'fa-xmark' : 'fa-bars'"></i>
      </button>

      <!-- DESKTOP MENU -->
      <ul class="hidden lg:flex items-center gap-10 text-white font-medium">

        <li><a href="/" class="hover:underline">Home</a></li>
        <li><a href="/#services" class="hover:underline">Our Services</a></li>
        <li><a href="/rooms" class="hover:underline">Rooms</a></li>

        <!-- BOOK -->
        <li v-if="isLoggedIn && idRole == 2 && !isInBookingPage">
          <button @click="handleBook" class="hover:underline">Book</button>
        </li>

        <!-- LOGIN -->
        <li v-if="!isLoggedIn">
          <a href="/login" class="bg-[#c8a349] text-white px-5 py-2 rounded-2xl">
            Login
          </a>
        </li>

        <!-- USER ICON -->
        <li v-else class="relative">
  <button @click="toggleUserMenu">
    <i class="fa-solid fa-circle-user text-3xl text-white"></i>
  </button>

  <div
    v-if="showUserMenu"
    class="absolute right-0 mt-5 w-44 bg-white rounded-xl shadow-xl py-3 border border-gray-200 z-50"
  >
    <a :href="dashboardRoute" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
      Dashboard
    </a>
    <button
      @click="logout"
      class="block text-left w-full px-4 py-2 text-gray-700 hover:bg-gray-100"
    >
      Logout
    </button>
  </div>
</li>

      </ul>

      <!-- MOBILE MENU -->
      <div 
        v-if="showMobileMenu" 
        class="lg:hidden absolute top-full left-0 right-0 bg-[#0b1f4f] shadow-lg py-4 px-4"
      >
        <ul class="flex flex-col gap-4 text-white">
          <li><a href="/" @click="closeMobile">Home</a></li>
          <li><a href="/#services" @click="closeMobile">Our Services</a></li>
          <li><a href="/rooms" @click="closeMobile">Rooms</a></li>

          <li v-if="isLoggedIn && idRole == 2 && !isInBookingPage">
            <button @click="handleBook" class="text-left w-full">Book</button>
          </li>

          <li v-if="!isLoggedIn">
            <a href="/login" class="bg-[#c8a349] text-center py-2 rounded-2xl" @click="closeMobile">
              Login
            </a>
          </li>

          <li v-else class="pt-3 border-t border-white/10">
            <a 
              :href="dashboardRoute" 
              class="block py-2" 
              @click="closeMobile"
            >
              Dashboard
            </a>
            <button @click="logout" class="w-full text-left py-2">Logout</button>
          </li>
        </ul>
      </div>

    </div>

    <!-- BOOKING MODAL -->
    <BookingModal :show="showBooking" @close="showBooking = false" />
  </nav>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import axios from 'axios'
import BookingModal from './ModalBooks.vue'

const router = useRouter()
const route = useRoute()

// STATE
const isLoggedIn = ref(localStorage.getItem('isLoggedIn') === 'true')
const idRole = ref(localStorage.getItem('role'))
const showUserMenu = ref(false)
const showMobileMenu = ref(false)
const showBooking = ref(false)

// DASHBOARD ROUTE
const dashboardRoute = computed(() => {
  if (idRole.value == 1) return '/admin/dashboard'
  return '/profile'
})

const isInBookingPage = computed(() => {
  return ['booking', 'room-selection'].includes(route.path.replace('/', ''))
})

// LOGIC SAMA PERSIS DENGAN HEROSECTION
const handleBook = async () => {
  try {
    const token = localStorage.getItem('token')

    if (!token) return router.push('/login')

    const res = await axios.get('http://localhost:8000/api/me', {
      headers: { Authorization: `Bearer ${token}` },
    })

    const user = res.data.data

    // CEK ALAMAT
    if (!user.alamat || user.alamat === '') {
      alert('Silakan lengkapi alamat di profil sebelum booking.')
      return router.push('/profile')
    }

    // Boleh booking → buka modal
    showBooking.value = true
    showMobileMenu.value = false

  } catch (err) {
    console.error(err)
    if (err.response?.status === 401) {
      localStorage.clear()
      router.push('/login')
    }
  }
}

const toggleUserMenu = () => (showUserMenu.value = !showUserMenu.value)
const toggleMobileMenu = () => (showMobileMenu.value = !showMobileMenu.value)
const closeMobile = () => (showMobileMenu.value = false)

const logout = () => {
  localStorage.clear()
  router.push('/login')
}

const goHome = () => router.push('/')
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap');
</style>
