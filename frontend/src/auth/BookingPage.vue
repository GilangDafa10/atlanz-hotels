<template>
  <section class="bg-gray-100 min-h-screen py-10">
    <div class="max-w-4xl mx-auto bg-white shadow-lg rounded-xl p-8">
      <h1 class="text-2xl font-bold text-[#0c2c67] mb-6">Booking Summary</h1>

      <!-- Jika loading -->
      <div v-if="loading" class="text-center text-gray-600 py-10">Loading booking details...</div>

      <!-- Jika berhasil mengambil data -->
      <div v-else-if="bookingData" class="space-y-8">
        <!-- Informasi booking -->
        <div class="border-b pb-4">
          <h2 class="text-xl font-semibold text-gray-800 mb-3">Informasi Booking</h2>

          <p>
            <span class="font-semibold">Check-in:</span> {{ formatDate(bookingData.tgl_checkin) }}
          </p>
          <p>
            <span class="font-semibold">Check-out:</span> {{ formatDate(bookingData.tgl_checkout) }}
          </p>
          <p><span class="font-semibold">Total Malam:</span> {{ bookingData.total_malam }}</p>
          <p>
            <span class="font-semibold">Status Booking:</span>
            <span
              class="px-3 py-1 rounded text-white"
              :class="bookingData.status_booking === 'pending' ? 'bg-yellow-500' : 'bg-green-600'"
            >
              {{ bookingData.status_booking }}
            </span>
          </p>
        </div>

        <!-- Detail kamar -->
        <div class="border-b pb-4">
          <h2 class="text-xl font-semibold text-gray-800 mb-3">Detail Kamar</h2>

          <div
            v-for="k in bookingData.kamars"
            :key="k.id_kamar"
            class="flex gap-4 border rounded-lg p-4 shadow-sm"
          >
            <img :src="k.jenis_kamar.url_gambar" class="w-32 h-24 object-cover rounded-lg" />

            <div>
              <p class="text-lg font-semibold">{{ k.jenis_kamar.jenis_kasur }}</p>
              <p class="text-gray-600">No. Kamar: {{ k.nomor_kamar }}</p>
              <p class="text-gray-800 font-medium mt-1">
                Harga / malam:
                {{ formatRupiah(k.jenis_kamar.harga_permalam) }}
              </p>
            </div>
          </div>
        </div>

        <!-- Additional Services -->
        <div class="border-b pb-4" v-if="bookingData.additional_services.length > 0">
          <h2 class="text-xl font-semibold text-gray-800 mb-3">Additional Services</h2>

          <div
            v-for="srv in bookingData.additional_services"
            :key="srv.id"
            class="flex justify-between py-2"
          >
            <p>{{ srv.nama }}</p>
            <p>{{ formatRupiah(srv.harga) }}</p>
          </div>
        </div>

        <div v-else class="border-b pb-4">
          <h2 class="text-xl font-semibold text-gray-800 mb-3">Additional Services</h2>
          <p class="text-gray-600">Tidak ada layanan tambahan dipilih.</p>
        </div>

        <!-- Total harga -->
        <div class="pt-4">
          <h2 class="text-xl font-semibold text-gray-800 mb-3">Total Pembayaran</h2>
          <p class="text-2xl font-bold text-[#0c2c67]">
            {{ formatRupiah(bookingData.total_harga) }}
          </p>
        </div>

        <!-- Tombol aksi -->
        <div class="flex justify-end gap-3 mt-6">
          <button @click="goHome" class="px-5 py-2 border rounded-md hover:bg-gray-100">
            Kembali
          </button>

          <button
            @click="goToPayment"
            class="px-5 py-2 bg-[#b7a34b] hover:bg-[#9c8b3f] text-white rounded-md"
          >
            Lanjut Pembayaran
          </button>
        </div>
      </div>

      <!-- Jika error -->
      <div v-else class="text-center text-red-600 py-10">
        Terjadi kesalahan mengambil data booking.
      </div>
    </div>
  </section>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import axios from 'axios'
import { useRoute, useRouter } from 'vue-router'

const route = useRoute()
const router = useRouter()

const bookingData = ref(null)
const loading = ref(true)

// Format tanggal
const formatDate = (dateString) =>
  new Date(dateString).toLocaleDateString('id-ID', {
    day: 'numeric',
    month: 'long',
    year: 'numeric',
  })

// Format rupiah
const formatRupiah = (value) =>
  new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(value)

onMounted(async () => {
  try {
    const res = await axios.post(
      'http://127.0.0.1:8000/api/booking',
      {
        jenis_kamar_id: route.query.jenis_kamar_id,
        tgl_checkin: route.query.check_in,
        tgl_checkout: route.query.check_out,
        jumlah: route.query.rooms,
        service_ids: route.query.service_ids ? JSON.parse(route.query.service_ids) : [],
      },
      {
        headers: {
          Authorization: `Bearer ${localStorage.getItem('token')}`,
        },
      },
    )

    bookingData.value = res.data.data.booking
    console.log('Booking berhasil:', bookingData.value)
  } catch (err) {
    console.error('Booking error:', err.response?.data || err)
  } finally {
    loading.value = false
  }
})

// Navigasi
const goHome = () => router.push('/')
const goToPayment = () =>
  router.push({
    name: 'midtrans-payment',
    query: {
      id_booking: bookingData.value.id_booking,
    },
  })
</script>

<style scoped>
* {
  font-family: 'Poppins', sans-serif;
}
</style>
