<!-- src/admin/Dashboard/Dashboard.vue -->
<template>
  <div class="w-full">

    <!-- TITLE -->
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Dashboard</h1>

    <!-- TOP CARDS -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">

      <!-- CARD 1: Total Booking -->
      <div class="bg-blue-500 text-white p-6 rounded-2xl shadow-md flex justify-between items-center">
        <div>
          <p class="text-sm opacity-80">Total Booking</p>
          <h2 class="text-4xl font-bold mt-2">{{ dashboard.total_booking_berhasil }}</h2>
        </div>

        <div class="bg-white/20 p-3 rounded-xl">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8h18M3 16h18M7 4h10M7 20h10"/>
          </svg>
        </div>
      </div>

      <!-- CARD 2: Pemasukan -->
      <div class="bg-blue-500 text-white p-6 rounded-2xl shadow-md flex justify-between items-center">
        <div>
          <p class="text-sm opacity-80">Pemasukan</p>
          <h2 class="text-4xl font-bold mt-2">
            Rp. {{ dashboard.total_pemasukan?.toLocaleString("id-ID") }}
          </h2>
        </div>

        <div class="bg-white/20 p-3 rounded-xl">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
        </div>
      </div>

    </div>

    <!-- TABLE SECTION -->
    <div class="bg-white p-6 rounded-2xl shadow-md">

      <div class="flex justify-between items-center mb-4">
        <div>
          <h2 class="text-lg font-semibold text-gray-800">Riwayat Pesanan</h2>
          <p class="text-sm text-gray-500">Track and manage all customer orders</p>
        </div>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full text-left">
          <thead>
            <tr class="border-b text-gray-600">
              <th class="py-3 px-4">Order ID</th>
              <th class="py-3 px-4">Customer ID</th>
              <th class="py-3 px-4">Date</th>
              <th class="py-3 px-4">Total</th>
            </tr>
          </thead>

          <tbody>
            <tr
              v-for="item in dashboard.riwayat_booking"
              :key="item.id_booking"
              class="border-b hover:bg-gray-50"
            >
              <td class="py-4 px-4">#{{ item.id_booking }}</td>
              <td class="py-4 px-4">{{ item.id_user }}</td>
              <td class="py-4 px-4">{{ item.tgl_booking }}</td>
              <td class="py-4 px-4">Rp {{ item.total_harga?.toLocaleString("id-ID") }}</td>
            </tr>

            <tr v-if="dashboard.riwayat_booking.length === 0">
              <td colspan="4" class="py-4 text-center text-gray-400">No records found.</td>
            </tr>

          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";
import AdminLayout from "@/layouts/AdminLayouts.vue";

defineOptions({ layout: AdminLayout });

const dashboard = ref({
  total_booking_berhasil: 0,
  total_pemasukan: 0,
  riwayat_booking: []
});

const token = localStorage.getItem("token");

const fetchDashboard = async () => {
  try {
    const response = await axios.get("http://127.0.0.1:8000/api/dashboard", {
      headers: { Authorization: `Bearer ${token}` }
    });

    dashboard.value = response.data.data;

  } catch (error) {
    console.error("Gagal mengambil dashboard:", error);
  }
};

onMounted(() => {
  fetchDashboard();
});
</script>
