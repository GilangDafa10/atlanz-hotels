<!-- src/admin/Dashboard/Dashboard.vue -->
<template>
  <div class="w-full">

    <!-- TITLE -->
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Dashboard</h1>

    <!-- TOP CARDS -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">

      <!-- CARD 1: Total Booking -->
      <div
        class="bg-blue-500 text-white p-6 rounded-2xl shadow-md flex justify-between items-center"
      >
        <div>
          <p class="text-sm opacity-80">Total Booking</p>
          <h2 class="text-4xl font-bold mt-2">120</h2>
        </div>

        <!-- ICON -->
        <div class="bg-white/20 p-3 rounded-xl">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8h18M3 16h18M7 4h10M7 20h10" />
          </svg>
        </div>
      </div>

      <!-- CARD 2: Pemasukan -->
      <div
        class="bg-blue-500 text-white p-6 rounded-2xl shadow-md flex justify-between items-center"
      >
        <div>
          <p class="text-sm opacity-80">Pemasukan</p>
          <h2 class="text-4xl font-bold mt-2">Rp. 700.000</h2>
        </div>

        <!-- ICON -->
        <div class="bg-white/20 p-3 rounded-xl">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
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
        <button class="text-blue-600 font-medium text-sm flex items-center gap-1 hover:text-blue-700">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
          </svg>
          View All
        </button>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full text-left">
          <thead>
            <tr class="border-b text-gray-600">
              <th class="py-3 px-4">Order ID</th>
              <th class="py-3 px-4">Customer</th>
              <th class="py-3 px-4">Date</th>
              <th class="py-3 px-4">Total</th>
              <th class="py-3 px-4"></th> <!-- For the ... icon -->
            </tr>
          </thead>

          <tbody>
            <tr class="border-b hover:bg-gray-50">
              <td class="py-4 px-4">#12345</td>
              <td class="py-4 px-4 flex items-center gap-2">
                <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-xs font-medium text-gray-600">SC</div>
                Sophia Clark
              </td>
              <td class="py-4 px-4">2024-01-15</td>
              <td class="py-4 px-4">$50</td>
              <td class="py-4 px-4">
                <button class="text-gray-400 hover:text-gray-600">...</button>
              </td>
            </tr>

            <tr class="border-b hover:bg-gray-50">
              <td class="py-4 px-4">#12346</td>
              <td class="py-4 px-4 flex items-center gap-2">
                <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-xs font-medium text-gray-600">EM</div>
                Ethan Miller
              </td>
              <td class="py-4 px-4">2024-01-18</td>
              <td class="py-4 px-4">$35</td>
              <td class="py-4 px-4">
                <button class="text-gray-400 hover:text-gray-600">...</button>
              </td>
            </tr>

            <tr class="border-b hover:bg-gray-50">
              <td class="py-4 px-4">#12347</td>
              <td class="py-4 px-4 flex items-center gap-2">
                <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-xs font-medium text-gray-600">OD</div>
                Olivia Davis
              </td>
              <td class="py-4 px-4">2024-01-17</td>
              <td class="py-4 px-4">$75</td>
              <td class="py-4 px-4">
                <button class="text-gray-400 hover:text-gray-600">...</button>
              </td>
            </tr>

            <tr class="border-b hover:bg-gray-50">
              <td class="py-4 px-4">#12348</td>
              <td class="py-4 px-4 flex items-center gap-2">
                <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-xs font-medium text-gray-600">LW</div>
                Liam Wilson
              </td>
              <td class="py-4 px-4">2024-01-18</td>
              <td class="py-4 px-4">$20</td>
              <td class="py-4 px-4">
                <button class="text-gray-400 hover:text-gray-600">...</button>
              </td>
            </tr>

            <tr class="border-b hover:bg-gray-50">
              <td class="py-4 px-4">#12349</td>
              <td class="py-4 px-4 flex items-center gap-2">
                <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-xs font-medium text-gray-600">AM</div>
                Ava Martinez
              </td>
              <td class="py-4 px-4">2024-01-19</td>
              <td class="py-4 px-4">$60</td>
              <td class="py-4 px-4">
                <button class="text-gray-400 hover:text-gray-600">...</button>
              </td>
            </tr>

            <tr class="border-b hover:bg-gray-50">
              <td class="py-4 px-4">#12350</td>
              <td class="py-4 px-4 flex items-center gap-2">
                <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-xs font-medium text-gray-600">NA</div>
                Noah Anderson
              </td>
              <td class="py-4 px-4">2024-01-20</td>
              <td class="py-4 px-4">$90</td>
              <td class="py-4 px-4">
                <button class="text-gray-400 hover:text-gray-600">...</button>
              </td>
            </tr>

            <tr class="hover:bg-gray-50">
              <td class="py-4 px-4">#12351</td>
              <td class="py-4 px-4 flex items-center gap-2">
                <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-xs font-medium text-gray-600">ET</div>
                Emma Thompson
              </td>
              <td class="py-4 px-4">2024-01-21</td>
              <td class="py-4 px-4">$45</td>
              <td class="py-4 px-4">
                <button class="text-gray-400 hover:text-gray-600">...</button>
              </td>
            </tr>

          </tbody>
        </table>
      </div>

    </div>

  </div>
</template>

<script setup>
// Import the AdminLayout to be used for this page
import AdminLayout from '@/layouts/AdminLayouts.vue';

// Define the layout for this component
defineOptions({
  layout: AdminLayout,
});
</script>

<style scoped>
/* No additional scoped styles needed; everything is handled by Tailwind */
</style>