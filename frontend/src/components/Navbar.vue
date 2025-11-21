<template>
  <nav
    class="fixed top-0 left-0 w-full z-50 transition-all duration-300 bg-[#0b1f4f] flex justify-between items-center px-20 py-5 font-[Montserrat]"
  >
    <!-- LOGO -->
    <div class="flex items-center gap-2.5">
      <img src="@/assets/Logo Atlanz white.png" alt="Atlanz Logo" class="w-[170px] h-auto" />
    </div>

    <!-- NAV LINKS -->
    <ul class="flex gap-[35px] m-0 p-0 list-none">
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

      <li v-if="isLoggedIn">
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
          <a href="/profile" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Dashboard</a>
          <button
            @click="logout"
            class="cursor-pointer block text-left w-full px-4 py-2 text-gray-700 hover:bg-gray-100"
          >
            Logout
          </button>
        </div>
      </li>
    </ul>
  </nav>

  <!-- BOOKING MODAL -->
  <BookingModal :show="showBooking" @close="showBooking = false" />
</template>

<script setup>
import { ref, onMounted } from 'vue'
import BookingModal from './ModalBooks.vue'

const showBooking = ref(false)
const isLoggedIn = ref(false)
const showUserMenu = ref(false)

onMounted(() => {
  isLoggedIn.value = localStorage.getItem('isLoggedIn') === 'true'
})

const toggleMenu = () => {
  showUserMenu.value = !showUserMenu.value
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
