<template>
  <nav
    class="fixed top-0 left-0 w-full z-50 transition-all duration-300 bg-[#0b1f4f] font-[Montserrat]"
  >
    <div class="flex justify-between items-center px-4 md:px-8 lg:px-20 py-4 md:py-5">
      <!-- LOGO -->
      <div class="flex items-center gap-2.5">
        <img
          src="@/assets/Logo Atlanz white.png"
          alt="Atlanz Logo"
          class="w-28 md:w-32 lg:w-[170px] h-auto"
        />
      </div>

      <!-- Mobile Menu Toggle -->
      <button
        @click="toggleMobileMenu"
        class="lg:hidden text-white p-2 focus:outline-none"
        aria-label="Toggle menu"
      >
        <svg
          v-if="!showMobileMenu"
          class="w-6 h-6"
          fill="none"
          stroke="currentColor"
          viewBox="0 0 24 24"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M4 6h16M4 12h16M4 18h16"
          />
        </svg>
        <svg v-else class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M6 18L18 6M6 6l12 12"
          />
        </svg>
      </button>

      <!-- Desktop NAV LINKS -->
      <ul class="hidden lg:flex gap-[35px] m-0 p-0 list-none">
        <li>
          <a
            href="/"
            class="text-white font-medium text-[16px] hover:underline underline-offset-[6px] transition"
          >
            Home
          </a>
        </li>
        <li>
          <a
            href="/#services"
            class="text-white font-medium text-[16px] hover:underline underline-offset-[6px] transition"
            >Our Services</a
          >
        </li>
        <li>
          <a
            href="/rooms"
            class="text-white font-medium text-[16px] hover:underline underline-offset-[6px] transition"
          >
            Rooms
          </a>
        </li>

        <li v-if="isLoggedIn && idRole == 2 && !isInBookingPage">
          <button
            @click="showBooking = true"
            class="cursor-pointer text-white font-medium text-[16px] hover:underline underline-offset-[6px] transition"
          >
            Book
          </button>
        </li>
        <!-- Jika belum login, tampilkan tombol Login -->
        <li v-if="!isLoggedIn">
          <a
            class="text-white font-medium py-2 px-5 rounded-3xl bg-[#c8a349] hover:opacity-80 transition"
            href="/login"
          >
            Login
          </a>
        </li>

        <!-- Jika sudah login, tampilkan icon user -->
        <li v-else class="relative">
          <button @click="toggleMenu" class="flex items-center cursor-pointer">
            <i
              class="fa-solid fa-circle-user text-3xl text-white"
              :class="isScrolled ? 'brightness-0' : ''"
            ></i>
          </button>

          <!-- Dropdown Menu -->
          <div
            v-if="showUserMenu"
            class="absolute right-0 mt-3 w-40 bg-white rounded-xl shadow-lg py-2 z-50"
          >
            <a :href="dashboardRoute" class="block px-4 py-2 text-gray-700 hover:bg-gray-100"
              >Dashboard</a
            >
            <button
              @click="logout"
              class="cursor-pointer block text-left w-full px-4 py-2 text-gray-700 hover:bg-gray-100"
            >
              Logout
            </button>
          </div>
        </li>
      </ul>

      <!-- Mobile Navigation Menu -->
      <div
        v-if="showMobileMenu"
        class="lg:hidden absolute top-full left-0 right-0 bg-[#0b1f4f] shadow-lg py-4 px-4 border-t border-white/10"
      >
        <ul class="flex flex-col gap-4">
          <li>
            <a
              href="/"
              class="block py-2 px-4 text-white hover:bg-white/10 rounded-lg transition"
              @click="showMobileMenu = false"
            >
              Home
            </a>
          </li>
          <li>
            <a
              href="/#services"
              class="block py-2 px-4 text-white hover:bg-white/10 rounded-lg transition"
              @click="showMobileMenu = false"
            >
              Our Services
            </a>
          </li>
          <li>
            <a
              href="/rooms"
              class="block py-2 px-4 text-white hover:bg-white/10 rounded-lg transition"
              @click="showMobileMenu = false"
            >
              Rooms
            </a>
          </li>

          <li v-if="isLoggedIn && idRole == 2 && !isInBookingPage">
            <button
              @click="openBookingModal"
              class="w-full text-left py-2 px-4 text-white hover:bg-white/10 rounded-lg transition"
            >
              Book
            </button>
          </li>

          <!-- Mobile Login Button -->
          <li v-if="!isLoggedIn">
            <a
              href="/login"
              class="block text-center py-2 px-5 rounded-3xl bg-[#c8a349] text-white hover:opacity-80 transition"
              @click="showMobileMenu = false"
            >
              Login
            </a>
          </li>

          <!-- Mobile User Menu -->
          <li v-else class="border-t border-white/10 pt-4 mt-2">
            <a
              :href="dashboardRoute"
              class="block py-2 px-4 text-white hover:bg-white/10 rounded-lg transition"
              @click="showMobileMenu = false"
            >
              Dashboard
            </a>
            <button
              @click="handleLogout"
              class="w-full text-left py-2 px-4 text-white hover:bg-white/10 rounded-lg transition"
            >
              Logout
            </button>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- BOOKING MODAL -->
  <BookingModal :show="showBooking" @close="showBooking = false" />
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { useRoute } from 'vue-router'
import BookingModal from './ModalBooks.vue'

const route = useRoute()
const showBooking = ref(false)
const isLoggedIn = ref(false)
const showUserMenu = ref(false)
const idRole = ref(null)
const showMobileMenu = ref(false)

onMounted(() => {
  isLoggedIn.value = localStorage.getItem('isLoggedIn') === 'true'
  idRole.value = localStorage.getItem('role')
})

const dashboardRoute = computed(() => {
  if (idRole.value == 1) return '/admin/dashboard'
  if (idRole.value == 2) return '/profile'
  return '/profile' // fallback
})

// Cek apakah sedang di halaman booking
const isInBookingPage = computed(() => {
  const currentPath = route.path
  return currentPath === '/booking' || currentPath === '/room-selection'
})

const toggleMenu = () => {
  showUserMenu.value = !showUserMenu.value
}

const toggleMobileMenu = () => {
  showMobileMenu.value = !showMobileMenu.value
  if (showMobileMenu.value) {
    showUserMenu.value = false
  }
}

const openBookingModal = () => {
  showBooking.value = true
  showMobileMenu.value = false
}

const handleLogout = () => {
  logout()
  showMobileMenu.value = false
}

const logout = () => {
  localStorage.removeItem('token')
  localStorage.removeItem('isLoggedIn')
  location.reload()
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap');
</style>
