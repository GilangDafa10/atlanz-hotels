<template>
  <section class="min-h-screen bg-[#0c2c67] py-16 text-white ">
    <div class="max-w-5xl bg-gray-200 p-10 rounded-2xl my-15 mx-auto">

      <h2 class="text-2xl text-black font-semibold text-center">
        Additional Services
      </h2>
      <p class="text-center text-gray-800 mb-10">
        Pilih Additional Service Yang Anda Inginkan
      </p>

      <!-- Loading -->
      <div v-if="loading" class="text-center py-10 text-gray-200">
        Loading services...
      </div>

      <!-- Card List -->
      <div
        v-else
        class="grid md:grid-cols-2 gap-8"
      >
        <div
          v-for="service in services"
          :key="service.id_service"
          class="bg-white text-black rounded-xl shadow-lg overflow-hidden hover:scale-[1.02] transition cursor-pointer"
        >
          <!-- Image -->
          <img
            src="../assets/Spa.png"
            alt="service image"
            class="w-full h-40 object-cover"
          />

          <!-- Content -->
          <div class="p-4">
            <h3 class="text-lg font-semibold">
              {{ service.nama_service }}
            </h3>

            <p class="text-gray-600 mb-4">
              {{ formatRupiah(service.harga_service) }}
            </p>

            <button
              @click="toggleSelect(service.id_service)"
              :class="selectedServices.includes(service.id_service)
                ? 'bg-gray-500'
                : 'bg-[#b7a34b] hover:bg-[#9c8b3f]'"
              class="w-full text-white py-2 rounded-lg font-medium transition"
            >
              {{
                selectedServices.includes(service.id_service)
                  ? 'Batalkan'
                  : 'Pilih'
              }}
            </button>
          </div>
        </div>
      </div>

      <!-- Lanjut Booking -->
      <div class="flex justify-end mt-10">
        <button
          @click="goToBooking"
          class="bg-[#b7a34b] hover:bg-[#9c8b3f] text-white px-6 py-3 rounded-lg text-sm font-medium transition"
        >
          Lanjutkan Booking
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

    services.value = (res.data.data ?? res.data).map(s => ({
      ...s,
      image_url: s.image_url || "https://via.placeholder.com/600x400"
    }))

  } catch (err) {
    console.error("Gagal mengambil service:", err)
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

// Pilih service
const toggleSelect = (id) => {
  if (selectedServices.value.includes(id)) {
    selectedServices.value = selectedServices.value.filter(x => x !== id)
  } else {
    selectedServices.value.push(id)
  }
}

// Lanjut booking (FIX 422)
const goToBooking = () => {
  router.push({
    name: 'booking',
    query: {
      jenis_kamar_id: route.query.jenis_kamar_id,
      check_in: route.query.check_in,
      check_out: route.query.check_out,
      rooms: route.query.rooms,
      service_ids: JSON.stringify(selectedServices.value), // <— HARUS ARRAY
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
