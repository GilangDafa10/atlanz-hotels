<template>
  <section class="bg-[#0c2c67] pb-10 pt-24">
    <div class="bg-white text-black rounded-2xl shadow-xl py-5 px-8 mx-auto w-[90%] max-w-6xl">
      <h2 class="text-2xl font-semibold text-center text-[#0c2c67] mb-5">Kamar</h2>

      <div class="grid md:grid-cols-3 gap-10">
        <div
          v-for="room in displayedRooms"
          :key="room.id_jenis_kamar"
          class="bg-white border border-gray-200 rounded-xl shadow-md overflow-hidden hover:shadow-2xl transition-all duration-300 hover:scale-[1.03]"
        >
          <div class="relative">
            <img
              :src="room.url_gambar"
              :alt="room.jenis_kasur"
              class="w-full h-[220px] object-cover"
            />

            <div class="absolute bottom-0 w-full bg-[#dfe6f1] text-center py-3">
              <h3 class="text-lg font-semibold text-[#0c2c67]">{{ room.jenis_kasur }}</h3>
            </div>
          </div>

          <div class="p-5 flex flex-col items-center">
            <p class="text-gray-600 mb-2">
              {{ room.deskripsi }}
            </p>
          </div>

          <div class="p-3 flex flex-col items-center">
            <p class="text-xl font-bold text-gray-700 mb-3">
              {{ formatRupiah(room.harga_permalam) }}
            </p>

            <button
              @click="goToBooking(room)"
              class="bg-[#b7a34b] hover:bg-[#9c8b3f] text-white px-5 py-2 rounded-md text-sm font-medium transition-transform hover:scale-105"
            >
              BOOK NOW
            </button>

            <!-- <button
              @click="goToAdditionalServices(room)"
              class="bg-[#0c2c67] hover:bg-[#081e47] text-white px-5 py-2 rounded-md text-sm font-medium mt-2 transition-transform hover:scale-105"
            >
              ADDITIONAL SERVICES
            </button> -->
          </div>
        </div>
      </div>

      <div class="flex justify-center mt-12">
        <button
          @click="showAll = !showAll"
          class="bg-gray-600 hover:bg-gray-700 text-white px-8 py-2 rounded-full text-sm font-medium transition-transform hover:scale-105"
        >
          {{ showAll ? 'Show Less' : 'Show All' }}
        </button>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import axios from 'axios'
import router from '@/router'

const showAll = ref(false)
const route = useRoute()

const jenis_kamar = ref([])

onMounted(async () => {
  const res = await axios.get('http://127.0.0.1:8000/api/jenis-kamar', {
    params: {
      check_in: route.query.checkIn,
      check_out: route.query.checkOut,
    },
  })

  // Jika API Anda mengembalikan data.data
  jenis_kamar.value = res.data.data ?? res.data
  console.log(jenis_kamar.value)
})

const goToBooking = (room) => {
  router.push({
    name: 'booking', // sesuaikan dengan nama route kamu
    query: {
      jenis_kamar_id: room.id_jenis_kamar,
      check_in: route.query.checkIn,
      check_out: route.query.checkOut,
      rooms: route.query.rooms,
    },
  })
}

const goToAdditionalServices = (room) => {
  router.push({
    name: 'addservice', // sesuaikan dengan route kamu
    query: {
      jenis_kamar_id: room.id_jenis_kamar,
      check_in: route.query.checkIn,
      check_out: route.query.checkOut,
      rooms: route.query.rooms,
    },
  })
}

// Format harga ke rupiah
const formatRupiah = (value) => {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
  }).format(value)
}

const displayedRooms = computed(() =>
  showAll.value ? jenis_kamar.value : jenis_kamar.value.slice(0, 3),
)
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap');
* {
  font-family: 'Poppins', sans-serif;
}
</style>
