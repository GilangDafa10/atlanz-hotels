<template>
  <nav
    class="font-[Montserrat]"
    :class="[
      'fixed top-0 left-0 w-full z-50 transition-all duration-300',
      isScrolled ? 'bg-white shadow-lg' : 'bg-transparent',
    ]"
  >
    <div class="max-w-7xl mx-auto px-20 py-5 flex justify-between items-center">
      <!-- Logo -->
      <div class="flex items-center gap-3">
        <img
          src="@/assets/Logo Atlanz white.png"
          class="w-40 transition-all duration-300 cursor-pointer"
          :class="isScrolled ? 'brightness-0' : ''"
          alt="Atlanz Logo"
          onclick="location.reload()"
        />
      </div>

      <!-- Navigation Links -->
      <ul class="flex items-center gap-[35px]">
        <li>
          <a :class="linkClass" class="hover:underline-offset-6 hover:underline transition" href="/"
            >Home</a
          >
        </li>
        <li>
          <a
            :class="linkClass"
            class="hover:underline-offset-6 hover:underline transition"
            href="#services"
            >Our Services</a
          >
        </li>
        <li>
          <a
            :class="linkClass"
            class="hover:underline-offset-6 hover:underline transition"
            href="/rooms"
            >Rooms</a
          >
        </li>

        <li v-if="isLoggedIn && idRole == 2">
          <button
            @click="showBooking = true"
            :class="linkClass"
            class="cursor-pointer hover:underline-offset-6 hover:underline transition"
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
              :disabled="isLoggingOut"
              class="cursor-pointer block text-left w-full px-4 py-2 text-gray-700 hover:bg-gray-100 disabled:opacity-60 disabled:cursor-not-allowed"
            >
              {{ isLoggingOut ? 'Logging out...' : 'Logout' }}
            </button>
          </div>
        </li>
      </ul>
    </div>
  </nav>

  <!-- BOOKING MODAL -->
  <BookingModal :show="showBooking" @close="showBooking = false" />
</template>

<script setup>
import { ref, onMounted, onUnmounted, computed } from 'vue'
// import { useRouter } from 'vue-router'
import BookingModal from './ModalBooks.vue'
import router from '@/router'

const showBooking = ref(false)
const isLoggedIn = ref(false)
const showUserMenu = ref(false)
const isLoggingOut = ref(false)
const idRole = ref(null)

onMounted(() => {
  isLoggedIn.value = localStorage.getItem('isLoggedIn') === 'true'
  idRole.value = localStorage.getItem('role')
})

const dashboardRoute = computed(() => {
  if (idRole.value == 1) return '/dashboard'
  if (idRole.value == 2) return '/profile'
  return '/profile' // fallback
})

const toggleMenu = () => {
  showUserMenu.value = !showUserMenu.value
}

const logout = () => {
  // set processing state, simulate logout process for 2 seconds
  isLoggingOut.value = true

  setTimeout(() => {
    localStorage.removeItem('token')
    localStorage.removeItem('isLoggedIn')
    isLoggedIn.value = false
    isLoggingOut.value = false
    router.push('/login')
  }, 2000)
}

const isScrolled = ref(false)
const handleScroll = () => {
  isScrolled.value = window.scrollY > 50
}

onMounted(() => window.addEventListener('scroll', handleScroll))
onUnmounted(() => window.removeEventListener('scroll', handleScroll))

const linkClass = computed(() =>
  isScrolled.value
    ? 'text-gray-800 font-medium hover:text-[#c8a349] transition'
    : 'text-white font-medium hover:opacity-80 transition',
)
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap');
</style>
