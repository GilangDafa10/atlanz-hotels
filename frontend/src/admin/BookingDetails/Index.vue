<template>
  <div class="p-4 md:p-6 bg-gray-50 min-h-screen">
    <!-- Header -->
    <div
      class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 md:mb-6 gap-3"
    >
      <div>
        <h1 class="text-xl md:text-2xl font-semibold text-gray-900">Booking Details</h1>
        <p class="text-xs md:text-sm text-gray-600 mt-1">Manage all hotel bookings</p>
      </div>
    </div>

    <!-- Error -->
    <div v-if="error" class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
      {{ error }}
    </div>

    <!-- Loading -->
    <div
      v-if="loading"
      class="mb-6 p-4 bg-blue-100 border border-blue-400 text-blue-700 rounded-lg text-center"
    >
      Loading bookings...
    </div>

    <!-- Search -->
    <div class="flex gap-4 mb-4 md:mb-6 items-center">
      <div class="flex-1 flex items-center bg-white border border-gray-300 rounded-lg px-3 py-2">
        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
          />
        </svg>
        <input
          type="text"
          placeholder="Search bookings..."
          v-model="searchQuery"
          @input="filterBookings"
          class="border-none outline-none flex-1 text-sm ml-2"
        />
      </div>
    </div>

    <!-- Desktop Table View (hidden on mobile) -->
    <div class="hidden lg:block bg-white rounded-lg shadow-sm overflow-hidden">
      <div>
        <table class="w-full">
          <thead>
            <tr class="bg-gray-50 border-b-2 border-gray-200">
              <th class="px-4 xl:px-6 py-4 text-left text-xs font-semibold whitespace-nowrap">
                Booking ID
              </th>
              <th class="px-4 xl:px-6 py-4 text-left text-xs font-semibold whitespace-nowrap">
                Customer
              </th>
              <th class="px-4 xl:px-6 py-4 text-left text-xs font-semibold whitespace-nowrap">
                Check In
              </th>
              <th class="px-4 xl:px-6 py-4 text-left text-xs font-semibold whitespace-nowrap">
                Check Out
              </th>
              <th class="px-4 xl:px-6 py-4 text-left text-xs font-semibold whitespace-nowrap">
                Rooms
              </th>
              <th class="px-4 xl:px-6 py-4 text-left text-xs font-semibold whitespace-nowrap">
                Amount
              </th>
              <th class="px-4 xl:px-6 py-4 text-left text-xs font-semibold whitespace-nowrap">
                Status
              </th>
              <th class="px-4 xl:px-6 py-4 text-left text-xs font-semibold whitespace-nowrap">
                Detail
              </th>
            </tr>
          </thead>

          <tbody class="divide-y divide-gray-200">
            <tr v-if="filteredBookings.length === 0">
              <td colspan="8" class="px-6 py-12 text-center text-gray-500">No bookings found</td>
            </tr>

            <tr
              v-for="booking in filteredBookings"
              :key="booking.bookingId"
              class="hover:bg-gray-50"
            >
              <td class="px-4 xl:px-6 py-4 whitespace-nowrap">{{ booking.bookingId }}</td>
              <td class="px-4 xl:px-6 py-4">{{ booking.guestName }}</td>
              <td class="px-4 xl:px-6 py-4 whitespace-nowrap">
                {{ formatDate(booking.checkIn) }}
              </td>
              <td class="px-4 xl:px-6 py-4 whitespace-nowrap">
                {{ formatDate(booking.checkOut) }}
              </td>
              <td class="px-4 xl:px-6 py-4">{{ booking.rooms }}</td>
              <td class="px-4 xl:px-6 py-4 font-semibold whitespace-nowrap">
                Rp {{ formatCurrency(booking.amount) }}
              </td>

              <td class="px-4 xl:px-6 py-4">
                <span
                  :class="[
                    'px-3 py-1 rounded-full text-xs font-semibold inline-block',
                    booking.status === 'Confirmed'
                      ? 'bg-blue-100 text-blue-700'
                      : booking.status === 'Completed'
                        ? 'bg-green-100 text-green-700'
                        : booking.status === 'Pending'
                          ? 'bg-yellow-100 text-yellow-700'
                          : booking.status === 'Cancelled'
                            ? 'bg-red-100 text-red-700'
                            : 'bg-gray-100 text-gray-700',
                  ]"
                >
                  {{ booking.status }}
                </span>
              </td>

              <td class="px-4 xl:px-6 py-4">
                <button
                  @click="openDetail(booking)"
                  class="px-3 py-1 bg-indigo-100 text-indigo-700 rounded-full text-xs hover:bg-indigo-200 whitespace-nowrap"
                >
                  Detail
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Mobile Card View (hidden on desktop) -->
    <div class="lg:hidden space-y-4">
      <div
        v-if="filteredBookings.length === 0"
        class="bg-white rounded-lg p-6 text-center text-gray-500"
      >
        No bookings found
      </div>

      <div
        v-for="booking in filteredBookings"
        :key="booking.bookingId"
        class="bg-white rounded-lg shadow-sm p-4 space-y-3"
      >
        <div class="flex justify-between items-start">
          <div>
            <p class="font-semibold text-gray-900">{{ booking.bookingId }}</p>
            <p class="text-sm text-gray-600">{{ booking.guestName }}</p>
          </div>
          <span
            :class="[
              'px-3 py-1 rounded-full text-xs font-semibold',
              booking.status === 'Confirmed'
                ? 'bg-blue-100 text-blue-700'
                : booking.status === 'Completed'
                  ? 'bg-green-100 text-green-700'
                  : booking.status === 'Pending'
                    ? 'bg-yellow-100 text-yellow-700'
                    : booking.status === 'Cancelled'
                      ? 'bg-red-100 text-red-700'
                      : 'bg-gray-100 text-gray-700',
            ]"
          >
            {{ booking.status }}
          </span>
        </div>

        <div class="grid grid-cols-2 gap-2 text-sm">
          <div>
            <p class="text-gray-500 text-xs">Check In</p>
            <p class="font-medium">{{ formatDate(booking.checkIn) }}</p>
          </div>
          <div>
            <p class="text-gray-500 text-xs">Check Out</p>
            <p class="font-medium">{{ formatDate(booking.checkOut) }}</p>
          </div>
          <div>
            <p class="text-gray-500 text-xs">Rooms</p>
            <p class="font-medium">{{ booking.rooms }}</p>
          </div>
          <div>
            <p class="text-gray-500 text-xs">Amount</p>
            <p class="font-semibold">Rp {{ formatCurrency(booking.amount) }}</p>
          </div>
        </div>

        <button
          @click="openDetail(booking)"
          class="w-full px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700 transition"
        >
          View Detail
        </button>
      </div>
    </div>

    <!-- Modal Detail -->
    <div
      v-if="showDetailModal"
      class="fixed inset-0 bg-black/50 flex justify-center items-center z-50 p-4"
      @click.self="showDetailModal = false"
    >
      <div
        class="bg-white p-4 md:p-6 rounded-lg shadow-lg w-full max-w-md md:max-w-lg max-h-[90vh] overflow-y-auto"
      >
        <div class="flex justify-between items-center mb-4">
          <h2 class="text-lg md:text-xl font-semibold">Booking Detail</h2>
          <button @click="showDetailModal = false" class="text-gray-500 hover:text-gray-700 p-1">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M6 18L18 6M6 6l12 12"
              />
            </svg>
          </button>
        </div>

        <div class="space-y-2 text-sm">
          <div class="grid grid-cols-2 gap-2">
            <p class="text-gray-600">ID</p>
            <p class="font-medium">{{ selectedBooking.bookingId }}</p>

            <p class="text-gray-600">Guest</p>
            <p class="font-medium">{{ selectedBooking.guestName }}</p>

            <p class="text-gray-600">Check In</p>
            <p class="font-medium">{{ formatDate(selectedBooking.checkIn) }}</p>

            <p class="text-gray-600">Check Out</p>
            <p class="font-medium">{{ formatDate(selectedBooking.checkOut) }}</p>

            <p class="text-gray-600">Total Amount</p>
            <p class="font-semibold text-indigo-600">
              Rp {{ formatCurrency(selectedBooking.amount) }}
            </p>
          </div>
        </div>

        <!-- KAMAR -->
        <h3 class="mt-4 mb-2 font-semibold text-sm md:text-base">Kamar</h3>
        <ul v-if="selectedBooking.kamars.length" class="space-y-3">
          <li
            v-for="(kamar, i) in selectedBooking.kamars"
            :key="i"
            class="border rounded-lg p-3 text-sm"
          >
            <div class="space-y-1">
              <p>
                <span class="text-gray-600">Nomor:</span>
                <span class="font-medium">{{ kamar.nomor_kamar }}</span>
              </p>
              <p>
                <span class="text-gray-600">Kasur:</span>
                <span class="font-medium">{{ kamar.jenis_kamar.jenis_kasur }}</span>
              </p>
              <p>
                <span class="text-gray-600">Harga/Malam:</span>
                <span class="font-medium"
                  >Rp {{ formatCurrency(kamar.jenis_kamar.harga_permalam) }}</span
                >
              </p>
              <p class="text-gray-700 text-xs">{{ kamar.jenis_kamar.deskripsi }}</p>

              <div class="mt-2 pt-2 border-t">
                <p class="text-gray-600 font-medium mb-1">Fasilitas:</p>
                <ul class="space-y-1">
                  <li
                    v-for="f in kamar.fasilitas"
                    :key="f.id_fasilitas"
                    class="flex items-center gap-2 text-xs"
                  >
                    <i :class="f.icon_fasilitas" class="text-indigo-600"></i>
                    <span>{{ f.nama_fasilitas }}</span>
                  </li>
                </ul>
              </div>
            </div>
          </li>
        </ul>

        <!-- ADDITIONAL SERVICES -->
        <h3 class="mt-4 mb-2 font-semibold text-sm md:text-base">Layanan Tambahan</h3>
        <div v-if="selectedBooking.additional_services.length" class="space-y-2">
          <div
            v-for="srv in selectedBooking.additional_services"
            :key="srv.id"
            class="flex justify-between items-center text-sm border-b pb-2"
          >
            <span>{{ srv.nama }}</span>
            <span class="font-medium">Rp {{ formatCurrency(srv.harga) }}</span>
          </div>
        </div>
        <p v-else class="text-gray-500 text-sm">Tidak ada layanan tambahan</p>

        <!-- PEMBAYARAN -->
        <h3 class="mt-4 mb-2 font-semibold text-sm md:text-base">Pembayaran</h3>
        <div v-if="selectedBooking.pembayaran" class="bg-gray-50 rounded-lg p-3 space-y-1 text-sm">
          <div class="flex justify-between">
            <span class="text-gray-600">Status</span>
            <span class="font-medium">{{ selectedBooking.pembayaran.status_pembayaran }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-gray-600">Metode</span>
            <span class="font-medium">{{ selectedBooking.pembayaran.metode }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-gray-600">Tanggal Bayar</span>
            <span class="font-medium">{{ selectedBooking.pembayaran.tanggal_bayar }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-gray-600">ID Transaksi</span>
            <span class="font-medium text-xs break-all">{{
              selectedBooking.pembayaran.id_transaksi
            }}</span>
          </div>
        </div>
        <p v-else class="text-gray-500 text-sm">Belum ada pembayaran</p>

        <button
          @click="showDetailModal = false"
          class="mt-5 px-4 py-2 bg-gray-700 text-white rounded hover:bg-gray-800 w-full"
        >
          Close
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const bookings = ref([])
const filteredBookings = ref([])
const searchQuery = ref('')

const loading = ref(false)
const error = ref('')

const showDetailModal = ref(false)
const selectedBooking = ref({})

const API_URL = 'http://127.0.0.1:8000/api'
const token = localStorage.getItem('auth_token') || localStorage.getItem('token')

const getAuthHeader = () => ({
  headers: {
    ...(token && { Authorization: `Bearer ${token}` }),
    Accept: 'application/json',
  },
})

const formatCurrency = (amount) =>
  new Intl.NumberFormat('id-ID', {
    minimumFractionDigits: 0,
  }).format(amount)

const formatDate = (date) =>
  new Date(date).toLocaleDateString('id-ID', {
    day: '2-digit',
    month: 'short',
    year: 'numeric',
  })

const mapStatus = (s) => {
  const map = {
    pending: 'Pending',
    confirmed: 'Confirmed',
    completed: 'Completed',
    cancelled: 'Cancelled',
  }
  return map[s?.toLowerCase()] || 'Pending'
}

const mapBookingData = (data) => {
  return data.map((b) => ({
    bookingId: `#${b.id_booking}`,
    guestName: b.nama_tamu || `User ${b.id_user}`,
    checkIn: b.tgl_checkin,
    checkOut: b.tgl_checkout,
    rooms: b.kamars?.length || 0,
    amount: b.total_harga || 0,
    status: mapStatus(b.status_booking),
  }))
}

const fetchBookings = async () => {
  loading.value = true
  error.value = ''
  try {
    const res = await axios.get(`${API_URL}/booking`, getAuthHeader())
    bookings.value = mapBookingData(res.data?.data?.bookings || [])
    filteredBookings.value = bookings.value
  } catch (e) {
    error.value = 'Failed to load bookings.'
  } finally {
    loading.value = false
  }
}

const fetchBookingDetail = async (id) => {
  loading.value = true
  error.value = ''

  try {
    const res = await axios.get(`${API_URL}/booking/${id}`, getAuthHeader())
    const b = res.data?.data?.booking

    selectedBooking.value = {
      bookingId: b.id_booking,
      guestName: `User ${b.id_user}`,
      checkIn: b.tgl_checkin,
      checkOut: b.tgl_checkout,
      amount: b.total_harga,
      status: mapStatus(b.status_booking),

      kamars: b.kamars || [],
      additional_services: b.additional_services || [],
      pembayaran: b.pembayaran,
    }

    showDetailModal.value = true
  } catch (e) {
    error.value = 'Gagal mengambil detail booking.'
  } finally {
    loading.value = false
  }
}

const openDetail = (booking) => {
  const id = booking.bookingId.replace('#', '')
  fetchBookingDetail(id)
}

const filterBookings = () => {
  const q = searchQuery.value.toLowerCase()
  filteredBookings.value = bookings.value.filter(
    (b) => b.bookingId.toLowerCase().includes(q) || b.guestName.toLowerCase().includes(q),
  )
}

onMounted(fetchBookings)
</script>
