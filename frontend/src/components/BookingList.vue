<!-- src/components/BookingList.vue -->
<template>
  <div v-if="loadingBooking" class="text-gray-500 py-4">Loading booking history...</div>
  <div v-else-if="bookings.length === 0" class="text-gray-500 py-4">
    You don’t have any bookings.
  </div>
  <div v-else class="space-y-5">
    <div
      v-for="b in bookings"
      :key="b.id_booking"
      class="border border-gray-200 rounded-xl p-4 md:p-5 hover:shadow-md transition"
    >
      <div class="flex flex-col gap-4">
        <div class="flex-1">
          <h2 class="text-base md:text-lg font-bold text-gray-800">Booking #{{ b.id_booking }}</h2>
          <p class="text-xs md:text-sm text-gray-600 mt-1">
            {{ b.tgl_checkin }} → {{ b.tgl_checkout }} • {{ b.total_malam }} night(s)
          </p>
          <p class="text-xs md:text-sm text-gray-700 mt-1 font-medium">
            Total: Rp {{ b.total_harga.toLocaleString() }}
          </p>
        </div>

        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3">
          <span
            class="px-3 py-1 text-xs rounded-full font-medium inline-block"
            :class="{
              'bg-yellow-100 text-yellow-700': b.status_booking === 'pending',
              'bg-green-100 text-green-700': b.status_booking === 'berhasil',
              'bg-red-100 text-red-700': b.status_booking === 'batal',
            }"
          >
            {{ b.status_booking.charAt(0).toUpperCase() + b.status_booking.slice(1) }}
          </span>
          <button
            @click="$emit('view-details', b)"
            class="w-full sm:w-auto px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg transition"
          >
            View Details
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  bookings: Array,
  loadingBooking: Boolean,
})
defineEmits(['view-details'])
</script>
