<template>
  <section class="min-h-screen bg-[#0c2c67] pt-20 pb-16 sm:pt-24 sm:pb-20">
    <div class="max-w-6xl mx-auto px-4">
      <!-- Gunakan grid responsif: 1 kolom (mobile), 1 kolom (tablet), 3 kolom (desktop) -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- LEFT MAIN CARD -->
        <div class="lg:col-span-2 bg-white rounded-xl p-4 sm:p-6 shadow-md">
          <h1 class="text-lg sm:text-xl font-semibold text-[#0c2c67] mb-3">Booking Summary</h1>

          <!-- Loading -->
          <div v-if="loading" class="text-center py-8 sm:py-10 text-gray-600">
            Loading booking details...
          </div>

          <!-- Booking Data -->
          <div v-else-if="bookingData">
            <!-- Room Info -->
            <div class="mb-6">
              <div class="flex flex-col sm:flex-row gap-4">
                <!-- Gambar responsif: lebih kecil di mobile -->
                <img
                  :src="bookingData?.room?.url_gambar || '/images/default-room.jpg'"
                  class="w-full sm:w-64 h-44 object-cover rounded-lg"
                />

                <div class="text-sm flex-1">
                  <p class="font-semibold text-gray-800 text-base sm:text-lg mb-2">
                    Hotel Atlanz Indonesia
                  </p>
                  <p class="font-semibold text-gray-800 text-base sm:text-lg mb-2">
                    {{ bookingData.room.jenis_kasur }}
                  </p>
                  <p class="font-semibold">
                    {{ formatDate(route.query.check_in) }} –
                    {{ formatDate(route.query.check_out) }}
                  </p>

                  <p class="mt-3 font-semibold text-gray-700">Fasilitas:</p>
                  <ul class="mt-1">
                    <li
                      v-for="f in bookingData?.fasilitas"
                      :key="f.id_fasilitas"
                      class="flex items-start gap-2 mt-1"
                    >
                      <i
                        v-if="f.icon_fasilitas"
                        :class="f.icon_fasilitas"
                        class="w-4 h-4 flex-shrink-0 mt-0.5"
                      ></i>
                      <span>{{ f.nama_fasilitas }}</span>
                    </li>
                  </ul>
                </div>
              </div>
            </div>

            <!-- Additional Services -->
            <div class="border border-gray-300 rounded-xl px-4 sm:px-6 py-4 bg-white">
              <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
                <h2 class="text-base sm:text-lg font-semibold text-gray-800">
                  Additional Services
                </h2>
                <button
                  @click="showServiceModal = true"
                  class="flex items-center gap-1.5 sm:gap-2 bg-[#0c2c67] hover:bg-[#081e47] text-white px-3 sm:px-4 py-2 rounded-md text-xs sm:text-sm font-medium transition-transform hover:scale-[1.02]"
                >
                  <svg
                    class="w-3.5 h-3.5 sm:w-4 sm:h-4"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
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

              <div v-if="selectedServices?.length > 0">
                <div
                  v-for="srv in selectedServices"
                  :key="srv.id_service"
                  class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 p-3 sm:p-4 mb-4 border rounded-xl"
                >
                  <div class="flex gap-3 sm:gap-4 w-full sm:w-auto">
                    <img
                      :src="
                        srv.url_gambar
                          ? `http://127.0.0.1:8000/storage/${srv.url_gambar}`
                          : '/src/assets/Yoga.png'
                      "
                      class="w-full sm:w-32 h-20 sm:h-24 object-cover rounded-lg"
                    />
                    <div class="flex-1">
                      <h3 class="text-base font-semibold text-[#b7a34b] uppercase tracking-wide">
                        {{ srv.nama_service }}
                      </h3>
                      <p class="text-xs sm:text-sm text-gray-600">
                        {{ formatRupiah(srv.harga_service) }}
                      </p>
                    </div>
                  </div>
                  <button
                    @click="removeService(srv.id_service)"
                    class="text-red-600 hover:text-red-800 p-1.5 sm:p-2 self-start sm:self-auto"
                  >
                    <svg
                      class="w-4 h-4 sm:w-5 sm:h-5"
                      fill="none"
                      stroke="currentColor"
                      viewBox="0 0 24 24"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M6 18L18 6M6 6l12 12"
                      />
                    </svg>
                  </button>
                </div>
              </div>

              <p v-else class="text-gray-600 text-sm">Tidak ada layanan tambahan dipilih.</p>
            </div>
          </div>

          <!-- Error state -->
          <div v-else class="text-center text-red-600 py-10">
            Terjadi kesalahan mengambil data booking.
          </div>
        </div>

        <!-- RIGHT SUMMARY -->
        <div class="bg-white rounded-xl shadow-md p-4 sm:p-6 h-fit">
          <div class="mb-4">
            <button class="px-4 py-1 bg-gray-300 text-xs sm:text-sm rounded">Summary</button>
          </div>

          <!-- Subtotal -->
          <div class="text-xs sm:text-sm text-gray-600 mb-5">
            <div class="flex justify-between py-1">
              <p>Subtotal</p>
              <p>{{ formatRupiah(bookingData?.subtotal || 0) }}</p>
            </div>
            <div class="flex justify-between py-1">
              <p>Additional Services</p>
              <p>{{ formatRupiah(additionalServicesTotal) }}</p>
            </div>
          </div>

          <!-- Total -->
          <div class="flex justify-between font-bold text-base sm:text-lg mb-4">
            <p>TOTAL</p>
            <p class="text-[#0c2c67]">{{ formatRupiah(totalWithServices) }}</p>
          </div>

          <!-- Buttons -->
          <div class="flex gap-2 sm:gap-3">
            <button
              @click="goToPayment"
              class="w-full bg-[#b7a34b] hover:bg-[#9c8b3f] text-white py-2.5 rounded font-medium text-sm sm:text-base"
            >
              Checkout
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal tetap di luar grid, tidak perlu diubah -->
    <AdditionalServiceModal
      v-model="showServiceModal"
      :services="availableServices"
      :selected-services="selectedServices"
      :loading="loadingServices"
      @update:selected-services="updateSelectedServices"
    />
  </section>
</template>

<script setup>
import { onMounted, ref, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import axios from 'axios'
import AdditionalServiceModal from '@/components/AddServiceModal.vue'

const route = useRoute()
const router = useRouter()

const loading = ref(false)
const bookingData = ref(null)
const showServiceModal = ref(false)
const loadingServices = ref(false)
const availableServices = ref([])
const selectedServices = ref([])

// Format date
const formatDate = (dateString) => {
  if (!dateString) return 'Tanggal tidak tersedia'
  const date = new Date(dateString)
  if (isNaN(date.getTime())) return 'Tanggal tidak valid'
  return date.toLocaleDateString('id-ID', {
    day: 'numeric',
    month: 'long',
    year: 'numeric',
  })
}

// Format rupiah
const formatRupiah = (value) =>
  new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
  }).format(value)

// Computed: Additional services total
const additionalServicesTotal = computed(() => {
  return selectedServices.value.reduce((sum, service) => sum + (service.harga_service || 0), 0)
})

// Computed: Total with services
const totalWithServices = computed(() => {
  return (bookingData.value?.subtotal || 0) + additionalServicesTotal.value
})

// Update selected services from modal
const updateSelectedServices = (services) => {
  selectedServices.value = services
}

// Remove service
const removeService = (serviceId) => {
  selectedServices.value = selectedServices.value.filter((s) => s.id_service !== serviceId)
}

// Fetch available services
const fetchServices = async () => {
  try {
    loadingServices.value = true
    const res = await axios.get('http://127.0.0.1:8000/api/additional-service', {
      headers: {
        Authorization: `Bearer ${localStorage.getItem('token')}`,
      },
    })
    availableServices.value = res.data.data || res.data
  } catch (err) {
    console.error('Error fetching services:', err)
    alert('Gagal memuat layanan tambahan.')
  } finally {
    loadingServices.value = false
  }
}

// Create booking and redirect to payment
const goToPayment = async () => {
  try {
    loading.value = true

    const serviceArray = selectedServices.value.map((s) => s.id_service)

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

    const id_booking = res.data.data?.booking?.id_booking || res.data.data?.id_booking

    if (!id_booking) {
      throw new Error('ID booking tidak ditemukan dalam respons')
    }

    router.push({
      name: 'midtrans-payment',
      query: {
        id_booking: id_booking,
      },
    })
  } catch (err) {
    console.error('Checkout error:', err)
    alert('Booking gagal dibuat. Silakan coba lagi.')
  } finally {
    loading.value = false
  }
}

// Initialize data on mount
onMounted(async () => {
  try {
    loading.value = true

    const { jenis_kamar_id, check_in, check_out, rooms } = route.query

    if (!jenis_kamar_id) {
      alert('Data booking tidak ditemukan.')
      return router.push('/rooms')
    }

    const serviceArray = route.query.service_ids ? JSON.parse(route.query.service_ids) : []

    const roomRes = await axios.get(`http://127.0.0.1:8000/api/jenis-kamar/${jenis_kamar_id}`, {
      headers: {
        Authorization: `Bearer ${localStorage.getItem('token')}`,
      },
      params: {
        tgl_checkin: check_in,
        tgl_checkout: check_out,
        jumlah: rooms,
        service_ids: serviceArray,
      },
    })

    const roomData = roomRes.data.data

    const nights = Math.ceil((new Date(check_out) - new Date(check_in)) / (1000 * 60 * 60 * 24))
    const roomCount = Number(rooms) || 1
    const subtotal = (roomData.harga_permalam || 0) * nights * roomCount

    bookingData.value = {
      room: {
        ...roomData,
        url_gambar: roomData.url_gambar
          ? `http://127.0.0.1:8000/storage/${roomData.url_gambar}`
          : '/images/default-room.jpg',
      },
      fasilitas: roomData.fasilitas || [], // <---- FIX
      subtotal,
    }

    await fetchServices()

    if (serviceArray.length > 0) {
      selectedServices.value = availableServices.value.filter((s) =>
        serviceArray.includes(s.id_service),
      )
    }
  } catch (err) {
    console.error('Error:', err)
    alert('Gagal memuat data preview.')
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
