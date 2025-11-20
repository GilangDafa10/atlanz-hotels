<template>
  <section class="bg-[#0c2c67] min-h-screen py-20 text-white">
    <div class="bg-white text-black rounded-2xl shadow-xl py-10 px-8 mx-auto w-[90%] max-w-4xl">
      <h2 class="text-2xl font-semibold text-center text-[#0c2c67] mb-8">Additional Services</h2>

      <!-- Loading State -->
      <div v-if="loading" class="text-center text-gray-600 py-10">Loading services...</div>

      <!-- Services List -->
      <div v-else>
        <div
          v-for="service in services"
          :key="service.id_service"
          class="flex justify-between items-center border-b py-4"
        >
          <div>
            <p class="font-semibold text-gray-800">{{ service.nama_service }}</p>
            <p class="text-sm text-gray-600">
              {{ formatRupiah(service.harga_service) }}
            </p>
          </div>

          <input
            type="checkbox"
            class="w-5 h-5"
            :value="service.id_service"
            v-model="selectedServices"
          />
        </div>
      </div>

      <!-- Button Next -->
      <div class="flex justify-end mt-6">
        <button
          @click="goToBooking"
          class="bg-[#b7a34b] hover:bg-[#9c8b3f] text-white px-6 py-2 rounded-md text-sm font-medium transition-transform hover:scale-105"
        >
          Lanjut Booking
        </button>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import axios from 'axios'

const route = useRoute()
const router = useRouter()

const services = ref([])
const selectedServices = ref([])
const loading = ref(true)

// Ambil data services dari API
onMounted(async () => {
  try {
    const res = await axios.get('http://127.0.0.1:8000/api/additional-service')
    services.value = res.data.data ?? res.data
  } catch (err) {
    console.error('Gagal mengambil service:', err)
  } finally {
    loading.value = false
  }
})

// Format rupiah
const formatRupiah = (value) => {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
  }).format(value)
}

// menuju halaman booking dengan service yang dipilih
const goToBooking = () => {
  router.push({
    name: 'booking',
    query: {
      jenis_kamar_id: route.query.jenis_kamar_id,
      check_in: route.query.check_in,
      check_out: route.query.check_out,
      rooms: route.query.rooms,
      service_ids: JSON.stringify(selectedServices.value), // kirim sebagai array
    },
  })
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap');
* {
  font-family: 'Poppins', sans-serif;
}
</style>
