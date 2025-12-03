<template>
  <section
    class="relative h-screen w-full bg-cover bg-center"
    :style="{ backgroundImage: `url(${heroImage})` }"
  >
    <!-- Overlay -->
    <div class="absolute inset-0 bg-black/40"></div>

    <!-- Content -->
    <div
      class="px-2 relative z-10 flex flex-col items-center justify-center h-full text-white text-center"
    >
      <p class="text-lg tracking-[0.3em]">WELCOME TO</p>

      <h1 class="text-[45px] md:text-7xl font-bold tracking-wider mt-2">ATLANZ HOTEL</h1>

      <button
        @click="openBookingModal"
        class="mt-6 md:mt-8 bg-yellow-500 px-6 py-3 rounded-md font-semibold shadow-md hover:bg-yellow-600 transition"
      >
        BOOK NOW
      </button>

      <!-- Scroll -->
      <div class="absolute bottom-16 flex flex-col items-center animate-bounce">
        <p class="text-sm mb-2">Scroll</p>
        <div class="text-3xl">⌄</div>
      </div>
    </div>
  </section>
  <!-- BOOKING MODAL -->
  <BookingModal :show="showBooking" @close="showBooking = false" />
</template>

<script setup>
import heroImage from '@/assets/heroSection.png'
import BookingModal from '@/components/ModalBooks.vue'
import { ref } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const showBooking = ref(false)

const openBookingModal = () => {
  // Cek apakah user sudah login
  const isLoggedIn = localStorage.getItem('isLoggedIn')
  const userRole = localStorage.getItem('role')

  if (!isLoggedIn || isLoggedIn !== 'true') {
    // Jika belum login, arahkan ke halaman login
    alert('Silakan login terlebih dahulu untuk melakukan booking.')
    router.push('/login')
    return
  }

  // Cek apakah role user adalah 2 (bukan admin)
  if (userRole !== '2') {
    alert('Hanya user yang dapat melakukan booking. Admin tidak dapat mengakses fitur ini.')
    return
  }

  // Jika sudah login dan role = 2, buka modal
  showBooking.value = true
}
</script>
