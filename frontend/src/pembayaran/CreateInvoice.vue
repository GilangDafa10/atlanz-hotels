<template>
  <div class="min-h-screen flex items-center justify-center bg-[#0c2c67]">
    <div class="flex flex-col items-center">
      <!-- Logo loading -->
      <!-- <img
        src="/mnt/data/0e4a13fb-c668-440c-b850-0ee695c42b0f.png"
        class="w-40 h-40 animate-pulse opacity-90"
        alt="Loading Logo"
      /> -->

      <!-- Text -->
      <p class="text-white mt-6 text-lg animate-bounce">Mengarahkan ke halaman pembayaran...</p>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import axios from 'axios'

const route = useRoute()
const router = useRouter()

const invoice = ref({})

onMounted(() => {
  fetchInvoice()
})

const fetchInvoice = async () => {
  try {
    const res = await axios.post(
      `https://unstalled-amelie-nonmonastically.ngrok-free.dev/api/booking/${route.query.id_booking}/invoice`,
      {},
      {
        headers: {
          Accept: 'application/json',
          'Content-Type': 'application/json',
          Authorization: `Bearer ${localStorage.getItem('token')}`,
        },
      },
    )

    invoice.value = res.data.data
    console.log('Invoice response:', invoice.value)

    // 🔥 FIX: Ambil qris_url dari API response
    const qrisUrl = invoice.value.qris_url

    if (!qrisUrl) {
      console.error('qris_url tidak ditemukan di response API!')
      return
    }

    // Delay untuk animasi loading
    setTimeout(() => {
      window.location.href = qrisUrl
    }, 1500)
  } catch (error) {
    console.error('Gagal load invoice:', error)
  }
}
</script>
