<template>
  <nav
    class="font-[Montserrat]"
    :class="[
      'fixed top-0 left-0 w-full z-50 transition-all duration-300',
      isScrolled ? 'bg-white shadow-lg' : 'bg-transparent',
    ]"
  >
    <div
      class="max-w-7xl mx-auto px-4 md:px-8 lg:px-20 py-4 md:py-5 flex justify-between items-center"
    >
      <!-- Logo -->
      <div class="flex items-center gap-3">
        <img
          src="@/assets/Logo Atlanz white.png"
          class="w-28 md:w-32 lg:w-40 transition-all duration-300 cursor-pointer"
          :class="isScrolled ? 'brightness-0' : ''"
          alt="Atlanz Logo"
          onclick="location.reload()"
        />
      </div>

      <!-- Mobile Menu Toggle -->
      <button
        @click="toggleMobileMenu"
        class="lg:hidden text-white p-2 focus:outline-none"
        :class="isScrolled ? 'text-gray-800' : 'text-white'"
      >
        <svg
          v-if="!showMobileMenu"
          :class="isScrolled ? 'brightness-0' : ''"
          class="w-6 h-6"
          fill="none"
          stroke="currentColor"
          viewBox="0 0 24 24"
        >
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M4 6h16M4 12h16M4 18h16" />
        </svg>
        <svg
          v-else
          class="w-6 h-6"
          :class="isScrolled ? 'brightness-0' : ''"
          fill="none"
          stroke="currentColor"
          viewBox="0 0 24 24"
        >
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>

      <!-- Desktop Navigation -->
      <ul class="hidden lg:flex items-center gap-[35px]">

        <li><a :class="linkClass" href="/" class="hover:underline-offset-6 hover:underline transition">Home</a></li>

        <li>
          <a :class="linkClass" href="#services" class="hover:underline-offset-6 hover:underline transition">
            Our Services
          </a>
        </li>

        <li>
          <a :class="linkClass" href="/rooms" class="hover:underline-offset-6 hover:underline transition">
            Rooms
          </a>
        </li>

        <!-- BOOK BUTTON (Desktop) -->
        <li v-if="isLoggedIn && idRole == 2">
          <button
            @click="openBookingModal"
            :class="linkClass"
            class="cursor-pointer hover:underline-offset-6 hover:underline transition"
          >
            Book
          </button>
        </li>

        <!-- Login (Jika belum login) -->
        <li v-if="!isLoggedIn">
          <a class="text-white font-medium py-2 px-5 rounded-3xl bg-[#c8a349] hover:opacity-80 transition" href="/login">
            Login
          </a>
        </li>

        <!-- User Icon (Jika Login) -->
        <li v-else class="relative">
          <button @click="toggleMenu" class="flex items-center cursor-pointer">
            <i class="fa-solid fa-circle-user text-3xl text-white"
              :class="isScrolled ? 'brightness-0' : ''"></i>
          </button>

          <!-- Dropdown User Menu -->
          <div
            v-if="showUserMenu"
            class="absolute right-0 mt-3 w-40 bg-white rounded-xl shadow-lg py-2 z-50"
          >
            <a :href="dashboardRoute" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Dashboard</a>

            <button
              @click="logout"
              :disabled="isLoggingOut"
              class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100 disabled:opacity-60"
            >
              {{ isLoggingOut ? "Logging out..." : "Logout" }}
            </button>
          </div>
        </li>
      </ul>

      <!-- Mobile Menu -->
      <div
        v-if="showMobileMenu"
        class="lg:hidden absolute top-full left-0 right-0 bg-white shadow-lg py-4 px-4"
      >
        <ul class="flex flex-col gap-4">

          <li><a href="/" class="block py-2 px-4 text-gray-800 hover:bg-gray-100 rounded-lg"
            @click="showMobileMenu = false">Home</a></li>

          <li><a href="#services" class="block py-2 px-4 text-gray-800 hover:bg-gray-100 rounded-lg"
            @click="showMobileMenu = false">Our Services</a></li>

          <li><a href="/rooms" class="block py-2 px-4 text-gray-800 hover:bg-gray-100 rounded-lg"
            @click="showMobileMenu = false">Rooms</a></li>

          <!-- BOOK (Mobile) -->
          <li v-if="isLoggedIn && idRole == 2">
            <button
              @click="openBookingModal"
              class="w-full text-left py-2 px-4 text-gray-800 hover:bg-gray-100 rounded-lg"
            >
              Book
            </button>
          </li>

          <!-- Login -->
          <li v-if="!isLoggedIn">
            <a href="/login"
              class="block text-center py-2 px-5 rounded-3xl bg-[#c8a349] text-white hover:opacity-80 transition"
              @click="showMobileMenu = false">
              Login
            </a>
          </li>

          <!-- User Mobile -->
          <li v-else class="border-t pt-4 mt-2">
            <a :href="dashboardRoute"
              class="block py-2 px-4 text-gray-800 hover:bg-gray-100 rounded-lg"
              @click="showMobileMenu = false">
              Dashboard
            </a>

            <button
              @click="handleLogout"
              :disabled="isLoggingOut"
              class="w-full text-left py-2 px-4 text-gray-800 hover:bg-gray-100 rounded-lg disabled:opacity-60"
            >
              {{ isLoggingOut ? "Logging out..." : "Logout" }}
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
import { ref, onMounted, onUnmounted, computed } from "vue"
import axios from "axios"
import BookingModal from "./ModalBooks.vue"
import router from "@/router"

const showBooking = ref(false)
const isLoggedIn = ref(false)
const showUserMenu = ref(false)
const isLoggingOut = ref(false)
const idRole = ref(null)
const showMobileMenu = ref(false)

// Fetch login state
onMounted(() => {
  isLoggedIn.value = localStorage.getItem("isLoggedIn") === "true"
  idRole.value = localStorage.getItem("role")
})

// Dashboard route
const dashboardRoute = computed(() => {
  if (idRole.value == 1) return "admin/dashboard"
  if (idRole.value == 2) return "/profile"
  return "/profile"
})

// Toggle menus
const toggleMenu = () => (showUserMenu.value = !showUserMenu.value)
const toggleMobileMenu = () => {
  showMobileMenu.value = !showMobileMenu.value
  if (showMobileMenu.value) showUserMenu.value = false
}

// 🔥 BOOKING LOGIC
const openBookingModal = async () => {
  try {
    const token = localStorage.getItem("token")

    if (!isLoggedIn.value || !token) {
      alert("Silakan login terlebih dahulu untuk booking.")
      return router.push("/login")
    }

    if (idRole.value != 2) {
      alert("Admin tidak dapat melakukan booking.")
      return
    }

    const res = await axios.get("http://localhost:8000/api/me", {
      headers: {
        Authorization: `Bearer ${token}`,
      },
    })

    const user = res.data.data

    if (!user.alamat) {
      alert("Lengkapi alamat Anda sebelum booking!")
      return router.push("/profile")
    }

    showBooking.value = true
    showMobileMenu.value = false
  } catch (error) {
    console.log(error)

    if (error.response?.status === 401) {
      alert("Sesi login berakhir, silakan login ulang.")
      localStorage.clear()
      return router.push("/login")
    }
  }
}

// Logout
const handleLogout = () => {
  logout()
  showMobileMenu.value = false
}

const logout = () => {
  isLoggingOut.value = true
  setTimeout(() => {
    localStorage.clear()
    isLoggedIn.value = false
    isLoggingOut.value = false
    router.push("/login")
  }, 1500)
}

// Scroll effect
const isScrolled = ref(false)
const handleScroll = () => (isScrolled.value = window.scrollY > 50)

onMounted(() => window.addEventListener("scroll", handleScroll))
onUnmounted(() => window.removeEventListener("scroll", handleScroll))

const linkClass = computed(() =>
  isScrolled.value
    ? "text-gray-800 font-medium hover:text-[#c8a349] transition"
    : "text-white font-medium hover:opacity-80 transition"
)
</script>

<style scoped>
@import url("https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap");
</style>
