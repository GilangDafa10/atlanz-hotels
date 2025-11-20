<template>
  <div class="min-h-screen flex items-center justify-center bg-[#0c2c67]">
    <div class="flex flex-col items-center">
      <!-- Logo loading -->
      <img
        src="../assets/A for Atlanz.png"
        class="w-40 h-40 animate-pulse opacity-90"
        alt="Loading Logo"
      />

      <!-- Text -->
      <p class="text-white mt-6 text-lg animate-bounce">Mengarahkan ke halaman pembayaran...</p>
    </div>
  </div>
</template>

<script setup>
import { onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'

const route = useRoute()
const router = useRouter()

onMounted(() => {
  const qrisUrl = route.query.qris_url

  if (!qrisUrl) {
    console.error('qris_url tidak ditemukan!')
    return
  }

  // Delay 1.5 detik untuk animasi loading
  setTimeout(() => {
    window.location.href = qrisUrl
  }, 1500)
})
</script>

<style>
/* Optional: animasi lembut */
</style>
