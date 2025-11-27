<template>
  <section class="min-h-screen bg-[#0c2c67] py-30">
    <div class="max-w-6xl mx-auto rounded-xl">
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- LEFT MAIN CARD -->
        <div class="lg:col-span-2 bg-white rounded-xl p-6 shadow-md">
          <h1 class="text-xl font-semibold text-[#0c2c67] mb-3">Booking Summary</h1>

          <!-- Loading -->
          <div v-if="loading" class="text-center py-10 text-gray-600">
            Loading booking details...
          </div>

          <!-- Booking Data -->
          <div v-else-if="bookingData">
            <!-- Room Info -->
            <div class="mb-6">
              <div class="flex gap-4">
                <img
                  :src="bookingData.kamars[0].jenis_kamar.url_gambar || '/images/default-room.jpg'"
                  class="w-64 h-44 object-cover rounded-lg"
                  @error="handleImageError"
                />

                <div class="text-sm">
                  <p class="font-semibold text-gray-800 text-lg mb-2">Hotel Atlanz Indonesia</p>

                  <p class="font-semibold">
                    {{ formatDate(bookingData.tgl_checkin) }} -
                    {{ formatDate(bookingData.tgl_checkout) }}
                  </p>

                  <p class="mt-3 font-semibold text-gray-700">Fasilitas :</p>
                  <ul class="text-gray-700 text-sm space-y-1">
                    <li
                      v-for="f in bookingData.kamars[0].fasilitas"
                      :key="f.id_fasilitas"
                      class="flex items-center gap-2"
                    >
                      <i :class="f.icon_fasilitas"></i>
                      {{ f.nama_fasilitas }}
                    </li>
                  </ul>
                </div>
              </div>
            </div>

            <!-- Additional Services -->
            <div class="border border-gray-300 rounded-xl px-6 py-4 mb-6 bg-white">
              <div class="flex justify-between items-center mb-3">
                <h2 class="text-lg font-semibold text-gray-800">Additional Services</h2>
                <button
                  @click="goToAdditionalServices"
                  class="flex items-center gap-2 bg-[#0c2c67] hover:bg-[#081e47] text-white px-4 py-2 rounded-md text-sm font-medium mt-2 transition-transform hover:scale-105"
                >
                  <svg class="w- h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                    />
                  </svg>
                  Add Service
                </button>
              </div>

              <div v-if="bookingData.additional_services.length > 0">
                <div
                  v-for="srv in bookingData.additional_services"
                  :key="srv.id"
                  class="flex items-center gap-5 border rounded-xl p-4 mb-4"
                >
                  <img
                    :src="
                      srv.url_gambar
                        ? `http://127.0.0.1:8000/storage/${srv.url_gambar}`
                        : '/src/assets/Yoga.png'
                    "
                    class="w-40 h-24 object-cover rounded-lg"
                  />
                  <div>
                    <h3 class="text-lg text-[#b7a34b] font-semibold uppercase tracking-wide">
                      {{ srv.nama }}
                    </h3>
                  </div>
                </div>
              </div>

              <p v-else class="text-gray-600">Tidak ada layanan tambahan dipilih.</p>
            </div>
          </div>

          <!-- Error state -->
          <div v-else class="text-center text-red-600 py-10">
            Terjadi kesalahan mengambil data booking.
          </div>
        </div>

        <!-- RIGHT SUMMARY -->
        <div class="bg-white rounded-xl shadow-md p-6 h-fit">
          <div class="flex gap-2 mb-3">
            <button class="px-7 py-1 bg-gray-300 text-sm rounded">Summary</button>
          </div>

          <!-- Subtotal -->
          <div class="text-xs text-gray-600 mb-6">
            <div class="flex justify-between">
              <p>Subtotal</p>
              <p>{{ formatRupiah(bookingData?.total_harga - 1000 || 0) }}</p>
            </div>
            <div class="flex justify-between">
              <p>Taxes & Fees</p>
              <p>{{ formatRupiah(1000) }}</p>
            </div>
          </div>

          <!-- Total -->
          <div class="flex justify-between font-bold text-lg mb-4">
            <p>TOTAL</p>
            <p class="text-[#0c2c67]">
              {{ formatRupiah(bookingData?.total_harga || 0) }}
            </p>
          </div>

          <!-- Buttons -->
          <div class="flex gap-3">
            <button @click="goToPayment" class="flex-1 bg-gray-300 text-gray-700 py-2 rounded">
              Checkout
            </button>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import axios from 'axios'

const route = useRoute()
const router = useRouter()

const loading = ref(false)
const bookingData = ref(null)

// Format date
const formatDate = (dateString) =>
  new Date(dateString).toLocaleDateString('id-ID', {
    day: 'numeric',
    month: 'long',
    year: 'numeric',
  })

// Format rupiah
const formatRupiah = (value) =>
  new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
  }).format(value)

// Handle image fallback
const handleImageError = (e) => (e.target.src = '/images/default-room.jpg')

// 👉 BUTTON CREATE BOOKING + REDIRECT PAYMENT
const goToPayment = async () => {
  try {
    loading.value = true

    const serviceArray = route.query.service_ids ? JSON.parse(route.query.service_ids) : []

    const payload = {
      jenis_kamar_id: Number(route.query.jenis_kamar_id),
      tgl_checkin: route.query.check_in,
      tgl_checkout: route.query.check_out,
      jumlah: Number(route.query.rooms),
      service_ids: serviceArray,
    }

    const res = await axios.post('http://127.0.0.1:8000/api/booking', payload, {
      headers: {
        Authorization: `Bearer ${localStorage.getItem('token')}`,
      },
    })

    bookingData.value = res.data.data.booking

    // Redirect ke pembayaran
    router.push({
      name: 'midtrans-payment',
      query: { id_booking: bookingData.value.id_booking },
    })
  } catch (err) {
    console.error('Booking error:', err.response?.data || err)
    alert('Booking gagal dibuat. Periksa data Anda.')
  } finally {
    loading.value = false
  }
}

// Tidak ada lagi API call disini
onMounted(async () => {
  try {
    loading.value = true

    const serviceArray = route.query.service_ids ? JSON.parse(route.query.service_ids) : []

    const res = await axios.get('http://127.0.0.1:8000/api/booking', {
      params: {
        jenis_kamar_id: route.query.jenis_kamar_id,
        tgl_checkin: route.query.check_in,
        tgl_checkout: route.query.check_out,
        jumlah: route.query.rooms,
        service_ids: serviceArray,
      },
    })

    bookingData.value = res.data.data
  } catch (err) {
    console.error('Preview booking error:', err.response?.data || err)
  } finally {
    loading.value = false
  }
})
</script>

<style>
* {
  font-family: 'Poppins', sans-serif;
}
</style>
