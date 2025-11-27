<template>
  <div class="p-6 bg-gray-50 min-h-screen">

    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
      <div>
        <h1 class="text-2xl font-semibold text-gray-900">Booking Details</h1>
        <p class="text-sm text-gray-600 mt-1">Manage all hotel bookings</p>
      </div>
    </div>

    <!-- Error -->
    <div
      v-if="error"
      class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg"
    >
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
    <div class="flex gap-4 mb-6 items-center">
      <div
        class="flex-1 flex items-center bg-white border border-gray-300 rounded-lg px-3 py-2"
      >
        <input
          type="text"
          placeholder="Search bookings..."
          v-model="searchQuery"
          @input="filterBookings"
          class="border-none outline-none flex-1 text-sm ml-2"
        />
      </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
      <table class="w-full">
        <thead>
          <tr class="bg-gray-50 border-b-2 border-gray-200">
            <th class="px-6 py-4 text-left text-xs font-semibold">Booking ID</th>
            <th class="px-6 py-4 text-left text-xs font-semibold">Customer</th>
            <th class="px-6 py-4 text-left text-xs font-semibold">Check In</th>
            <th class="px-6 py-4 text-left text-xs font-semibold">Check Out</th>
            <th class="px-6 py-4 text-left text-xs font-semibold">Rooms</th>
            <th class="px-6 py-4 text-left text-xs font-semibold">Amount</th>
            <th class="px-6 py-4 text-left text-xs font-semibold">Status</th>
            <th class="px-6 py-4 text-left text-xs font-semibold">Detail</th>
          </tr>
        </thead>

        <tbody class="divide-y divide-gray-200">
          <tr v-if="filteredBookings.length === 0">
            <td
              colspan="8"
              class="px-6 py-12 text-center text-gray-500"
            >
              No bookings found
            </td>
          </tr>

          <tr
            v-for="booking in filteredBookings"
            :key="booking.bookingId"
            class="hover:bg-gray-50"
          >
            <td class="px-6 py-4">{{ booking.bookingId }}</td>
            <td class="px-6 py-4">{{ booking.guestName }}</td>
            <td class="px-6 py-4">
              {{ formatDate(booking.checkIn) }}
            </td>
            <td class="px-6 py-4">
              {{ formatDate(booking.checkOut) }}
            </td>
            <td class="px-6 py-4">{{ booking.rooms }}</td>
            <td class="px-6 py-4 font-semibold">
              Rp {{ formatCurrency(booking.amount) }}
            </td>

            <td class="px-6 py-4">
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
                    : 'bg-gray-100 text-gray-700'
                ]"
              >
                {{ booking.status }}
              </span>
            </td>

            <td class="px-6 py-4">
              <button
                @click="openDetail(booking)"
                class="px-3 py-1 bg-indigo-100 text-indigo-700 rounded-full text-xs
                hover:bg-indigo-200"
              >
                Detail
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Modal Detail -->
    <div
      v-if="showDetailModal"
      class="fixed inset-0 bg-black/50 flex justify-center items-center z-50"
    >
      <div
        class="bg-white p-6 rounded-lg shadow-lg w-[450px] max-h-[85vh] overflow-y-auto"
      >
        <h2 class="text-xl font-semibold mb-4">Booking Detail</h2>

        <p><strong>ID:</strong> {{ selectedBooking.bookingId }}</p>
        <p><strong>Guest:</strong> {{ selectedBooking.guestName }}</p>
        <p><strong>Check In:</strong> {{ formatDate(selectedBooking.checkIn) }}</p>
        <p><strong>Check Out:</strong> {{ formatDate(selectedBooking.checkOut) }}</p>
        <p>
          <strong>Total Amount:</strong>
          Rp {{ formatCurrency(selectedBooking.amount) }}
        </p>

        <!-- KAMAR -->
        <h3 class="mt-4 mb-2 font-semibold">Kamar</h3>
        <ul v-if="selectedBooking.kamars.length" class="ml-4">
          <li
            v-for="(kamar, i) in selectedBooking.kamars"
            :key="i"
            class="mb-4"
          >
            <strong>Nomor:</strong> {{ kamar.nomor_kamar }} <br />
            <strong>Kasur:</strong> {{ kamar.jenis_kamar.jenis_kasur }} <br />
            <strong>Harga/Malam:</strong>
            Rp {{ formatCurrency(kamar.jenis_kamar.harga_permalam) }} <br />
            <strong>Deskripsi:</strong> {{ kamar.jenis_kamar.deskripsi }}

            <div class="mt-2">
              <strong>Fasilitas:</strong>
              <ul class="ml-6 list-disc">
                <li
                  v-for="f in kamar.fasilitas"
                  :key="f.id_fasilitas"
                  class="flex items-center gap-2"
                >
                  <!-- ICON FASILITAS -->
                  <i :class="f.icon_fasilitas"></i>

                  <!-- NAMA FASILITAS -->
                  {{ f.nama_fasilitas }}
                </li>
              </ul>
            </div>


            <hr class="my-3" />
          </li>
        </ul>

        <!-- ADDITIONAL SERVICES -->
        <h3 class="mt-4 mb-2 font-semibold">Layanan Tambahan</h3>
        <ul
          v-if="selectedBooking.additional_services.length"
          class="ml-4 list-disc"
        >
          <li
            v-for="srv in selectedBooking.additional_services"
            :key="srv.id"
          >
            {{ srv.nama }} – Rp {{ formatCurrency(srv.harga) }}
          </li>
        </ul>

        <!-- PEMBAYARAN -->
        <h3 class="mt-4 mb-2 font-semibold">Pembayaran</h3>
        <div v-if="selectedBooking.pembayaran">
          <p><strong>Status:</strong> {{ selectedBooking.pembayaran.status_pembayaran }}</p>
          <p><strong>Metode:</strong> {{ selectedBooking.pembayaran.metode }}</p>
          <p><strong>Tanggal Bayar:</strong> {{ selectedBooking.pembayaran.tanggal_bayar }}</p>
          <p><strong>ID Transaksi:</strong> {{ selectedBooking.pembayaran.id_transaksi }}</p>
        </div>
        <p v-else class="text-gray-500">Belum ada pembayaran</p>

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
import { ref, onMounted } from "vue";
import axios from "axios";

const bookings = ref([]);
const filteredBookings = ref([]);
const searchQuery = ref("");

const loading = ref(false);
const error = ref("");

const showDetailModal = ref(false);
const selectedBooking = ref({});

const API_URL = "http://127.0.0.1:8000/api";
const token =
  localStorage.getItem("auth_token") || localStorage.getItem("token");

const getAuthHeader = () => ({
  headers: {
    ...(token && { Authorization: `Bearer ${token}` }),
    Accept: "application/json",
  },
});

const formatCurrency = (amount) =>
  new Intl.NumberFormat("id-ID", {
    minimumFractionDigits: 0,
  }).format(amount);

const formatDate = (date) =>
  new Date(date).toLocaleDateString("id-ID", {
    day: "2-digit",
    month: "short",
    year: "numeric",
  });

const mapStatus = (s) => {
  const map = {
    pending: "Pending",
    confirmed: "Confirmed",
    completed: "Completed",
    cancelled: "Cancelled",
  };
  return map[s?.toLowerCase()] || "Pending";
};

const mapBookingData = (data) => {
  return data.map((b) => ({
    bookingId: `#${b.id_booking}`,
    guestName: b.nama_tamu || `User ${b.id_user}`,
    checkIn: b.tgl_checkin,
    checkOut: b.tgl_checkout,
    rooms: b.kamars?.length || 0,
    amount: b.total_harga || 0,
    status: mapStatus(b.status_booking),
  }));
};

const fetchBookings = async () => {
  loading.value = true;
  error.value = "";
  try {
    const res = await axios.get(`${API_URL}/booking`, getAuthHeader());
    bookings.value = mapBookingData(res.data?.data?.bookings || []);
    filteredBookings.value = bookings.value;
  } catch (e) {
    error.value = "Failed to load bookings.";
  } finally {
    loading.value = false;
  }
};

const fetchBookingDetail = async (id) => {
  loading.value = true;
  error.value = "";

  try {
    const res = await axios.get(`${API_URL}/booking/${id}`, getAuthHeader());
    const b = res.data?.data?.booking;

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
    };

    showDetailModal.value = true;
  } catch (e) {
    error.value = "Gagal mengambil detail booking.";
  } finally {
    loading.value = false;
  }
};

const openDetail = (booking) => {
  const id = booking.bookingId.replace("#", "");
  fetchBookingDetail(id);
};

const filterBookings = () => {
  const q = searchQuery.value.toLowerCase();
  filteredBookings.value = bookings.value.filter(
    (b) =>
      b.bookingId.toLowerCase().includes(q) ||
      b.guestName.toLowerCase().includes(q)
  );
};

onMounted(fetchBookings);
</script>
