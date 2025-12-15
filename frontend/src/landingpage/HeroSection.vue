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

      <h1 class="text-[45px] md:text-7xl font-bold tracking-wider mt-2">
        ATLANZ HOTEL
      </h1>

      <button
        @click="openBookingModal"
        class="mt-6 md:mt-8 bg-yellow-500 px-6 py-3 rounded-md font-semibold shadow-md hover:bg-yellow-600 transition"
      >
        BOOK NOW
      </button>

      <!-- Scroll Indicator -->
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
import axios from 'axios'

const router = useRouter()
const showBooking = ref(false)

const openBookingModal = async () => {
  try {
    // ==================================================
    // 1️⃣ CEK LOGIN
    // ==================================================
    const isLoggedIn = localStorage.getItem('isLoggedIn')
    const userRole = localStorage.getItem('role')
    const token = localStorage.getItem('token')

    if (!isLoggedIn || isLoggedIn !== 'true' || !token) {
      alert('Silakan login terlebih dahulu untuk melakukan booking.')
      return router.push('/login')
    }

    // ==================================================
    // 2️⃣ CEK ROLE USER (hanya user role = 2)
    // ==================================================
    if (userRole !== '2') {
      alert('Hanya user yang dapat melakukan booking. Admin tidak dapat mengakses fitur ini.')
      return
    }

    // ==================================================
    // 3️⃣ CEK ALAMAT LEWAT API /api/me
    // ==================================================
    const response = await axios.get('http://localhost:8000/api/me', {
      headers: {
        Authorization: `Bearer ${token}`
      }
    })

    const user = response.data.data

    // Jika alamat kosong → redirect ke profil
    if (!user.alamat || user.alamat === '' || user.alamat === null) {
      alert('Lengkapi alamat pada profil sebelum melakukan booking!')
      return router.push('/profile')
    }

    // ==================================================
    // 4️⃣ JIKA SEMUA VALID → buka modal booking
    // ==================================================
    showBooking.value = true

  } catch (error) {
    console.error(error)

    // Jika token expired / unauthorized
    if (error.response && error.response.status === 401) {
      alert('Sesi login sudah berakhir. Silakan login kembali.')
      localStorage.clear()
      return router.push('/login')
    }

    alert('Terjadi kesalahan. Silakan coba lagi.')
  }
}
</script>

<style scoped>
/* Tambahan styling bila diperlukan */
</style>
