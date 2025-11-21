<template>
  <section class="min-h-screen bg-[#0c2c67] py-12">
    <div class="max-w-6xl mx-auto rounded-xl ">

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
                  <p class="font-semibold text-gray-800 text-lg mb-2">
                    Hotel Atlanz Indonesia
                  </p>

                  <p class="font-semibold">
                    {{ formatDate(bookingData.tgl_checkin) }} -
                    {{ formatDate(bookingData.tgl_checkout) }}
                  </p>

                  <p class="mt-3 font-semibold text-gray-700">Fasilitas :</p>
                  <ul class="text-gray-700 text-sm">
                    <li class="flex items-center gap-2"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg> Bed</li>
                    <li class="flex items-center gap-2"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 117.778 0M12 20h.01m-7.048-1.414a3 3 0 115.698 0L12 20l1.369-1.414a3 3 0 115.698 0L20 20H4z" /></svg> Wi-Fi</li>
                    <li class="flex items-center gap-2"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9l9 9 9-9" /></svg> Shower</li>
                    <li class="flex items-center gap-2"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2" /></svg> TV</li>
                    <li class="flex items-center gap-2"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6M9 8h6m-6 8h6M5 4h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V6a2 2 0 012-2z" /></svg> Perlengkapan Mandi</li>
                    <li class="flex items-center gap-2"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg> Kulkas</li>
                    <li class="flex items-center gap-2"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.333-.83a2 2 0 00-1.512.14l-1.533 1.022a2 2 0 01-1.022.296l-1.533-.511a2 2 0 00-1.512.14l-1.533 1.022a2 2 0 01-1.022.296l-1.533-.511a2 2 0 00-1.512.14l-1.533 1.022a2 2 0 01-1.022.296l-1.533-.511a2 2 0 00-1.022 1.732l1.533 2.555a2 2 0 01.14 1.512l-1.022 1.533a2 2 0 00.296 1.022l1.533 1.022a2 2 0 01.14 1.512l-1.022 1.533a2 2 0 00.296 1.022l1.533 1.022a2 2 0 01.14 1.512l-1.022 1.533a2 2 0 00.296 1.022l1.533 1.022a2 2 0 01.14 1.512l-1.022 1.533a2 2 0 00.296 1.022l1.533 1.022a2 2 0 01.14 1.512l-1.022 1.533a2 2 0 00.296 1.022l1.533 1.022" /></svg> Mesin Kopi</li>
                    <li class="flex items-center gap-2"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg> Setrika</li>
                  </ul>
                </div>
              </div>
            </div>

            <!-- Additional Services -->
            <div class="border border-gray-300 rounded-xl px-6 py-4 mb-6 bg-white">

              <div class="flex justify-between items-center mb-3">
                <h2 class="text-lg font-semibold text-gray-800">Additional Services</h2>
                <button
                  @click="goToAddService"
                  class="px-3 py-1 bg-[#0c2c67] text-white text-xs rounded flex items-center gap-1"
                >
                  <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
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
                    :src="srv.url_gambar ? `http://127.0.0.1:8000/storage/${srv.url_gambar}` : '/src/assets/Yoga.png'"
                    class="w-40 h-24 object-cover rounded-lg"
                  />
                  <div>
                    <h3 class="text-lg text-[#b7a34b] font-semibold uppercase tracking-wide">
                      {{ srv.nama }}
                    </h3>
                  </div>
                </div>
              </div>

              <p v-else class="text-gray-600">
                Tidak ada layanan tambahan dipilih.
              </p>

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
            <button class="px-4 py-1 bg-gray-300 text-sm rounded border">
              Summary
            </button>
            <button class="px-4 py-1 bg-gray-200 text-sm rounded border">
              Details
            </button>
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
            <button
              class="flex-1 bg-[#b7a34b] text-white py-2 rounded hover:bg-[#9c8b3f] transition"
            >
              Agree
            </button>

            <button
              @click="goToPayment"
              class="flex-1 bg-gray-300 text-gray-700 py-2 rounded"
            >
              Checkout
            </button>
          </div>
        </div>

      </div>

    </div>
  </section>
</template>

<script setup>
import { onMounted, ref } from "vue";
import { useRoute, useRouter } from "vue-router";
import axios from "axios";

const route = useRoute();
const router = useRouter();

const loading = ref(true);
const bookingData = ref(null);

// Format date
const formatDate = (dateString) =>
  new Date(dateString).toLocaleDateString("id-ID", {
    day: "numeric",
    month: "long",
    year: "numeric",
  });

// Format rupiah
const formatRupiah = (value) =>
  new Intl.NumberFormat("id-ID", {
    style: "currency",
    currency: "IDR",
  }).format(value);

// Handle image error
const handleImageError = (e) => {
  e.target.src = '/images/default-room.jpg';
};

// NAVIGATE TO ADD SERVICE
const goToAddService = () => {
  if (!bookingData.value?.id_booking) return;
  router.push({
    name: 'add-service', // pastikan route ini ada di router Anda
    query: {
      id_booking: bookingData.value.id_booking,
      jenis_kamar_id: route.query.jenis_kamar_id,
      check_in: route.query.check_in,
      check_out: route.query.check_out,
      rooms: route.query.rooms,
    }
  });
};

// NAVIGATE PAYMENT
const goToPayment = () => {
  if (!bookingData.value?.id_booking) return;
  router.push({
    name: "midtrans-payment",
    query: {
      id_booking: bookingData.value.id_booking,
    },
  });
};

// GET BOOKING DATA
onMounted(async () => {
  try {
    const serviceArray =
      route.query.service_ids ? JSON.parse(route.query.service_ids) : [];

    const payload = {
      jenis_kamar_id: Number(route.query.jenis_kamar_id),
      tgl_checkin: route.query.check_in,
      tgl_checkout: route.query.check_out,
      jumlah: Number(route.query.rooms),
      service_ids: serviceArray,
    };

    const res = await axios.post("http://127.0.0.1:8000/api/booking", payload, {
      headers: {
        Authorization: `Bearer ${localStorage.getItem("token")}`,
      },
    });

    bookingData.value = res.data.data.booking;
  } catch (err) {
    console.error("Booking error:", err.response?.data || err);
  } finally {
    loading.value = false;
  }
});
</script>

<style scoped>
* {
  font-family: "Poppins", sans-serif;
}
</style>