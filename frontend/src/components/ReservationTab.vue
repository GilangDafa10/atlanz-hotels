<!-- src/components/ReservationTab.vue -->
<template>
  <div v-if="!selectedBooking">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold text-gray-800">My Reservation</h1>
    </div>
    <BookingList
      :bookings="bookings"
      :loadingBooking="loadingBooking"
      @view-details="onViewDetails"
    />
  </div>
  <BookingDetail
    v-else
    :booking="selectedBooking"
    :loading-detail="loadingDetail"
    :image-getter="imageGetter"
    @back="onBack"
  />
</template>

<script setup>
import BookingList from './BookingList.vue'
import BookingDetail from './BookingDetail.vue'

const props = defineProps({
  selectedBooking: Object,
  bookings: Array,
  loadingBooking: Boolean,
  loadingDetail: Boolean,
  imageGetter: Function,
})
const emit = defineEmits(['view-details', 'back'])

const onViewDetails = (booking) => emit('view-details', booking)
const onBack = () => emit('back')
</script>
