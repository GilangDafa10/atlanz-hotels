<!-- src/components/BookingDetail.vue -->
<template>
  <div>
    <div v-if="loadingDetail" class="text-center py-8">
      <div
        class="inline-block w-8 h-8 border-4 border-blue-600 border-t-transparent rounded-full animate-spin"
      ></div>
      <p class="text-gray-600 mt-2">Loading booking details...</p>
    </div>

    <div v-else>
      <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-6">
        <h1 class="text-xl md:text-2xl font-bold text-gray-800">Booking Detail</h1>
        <button
          @click="$emit('back')"
          class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg text-sm font-medium transition"
        >
          ← Back
        </button>
      </div>

      <div class="bg-gray-50 rounded-lg p-4 md:p-6 mb-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 md:gap-6 mb-6">
          <div>
            <p class="text-sm text-gray-600">Booking ID</p>
            <p class="font-medium">#{{ booking.id_booking }}</p>
          </div>
          <div>
            <p class="text-sm text-gray-600">Status</p>
            <span
              class="inline-block px-3 py-1 text-xs rounded-full font-medium mt-1"
              :class="{
                'bg-yellow-100 text-yellow-700': booking.status_booking === 'pending',
                'bg-green-100 text-green-700': booking.status_booking === 'berhasil',
                'bg-red-100 text-red-700': booking.status_booking === 'batal',
              }"
            >
              {{ booking.status_booking.charAt(0).toUpperCase() + booking.status_booking.slice(1) }}
            </span>
          </div>
          <div>
            <p class="text-sm text-gray-600">Check-in</p>
            <p class="font-medium">{{ booking.tgl_checkin }}</p>
          </div>
          <div>
            <p class="text-sm text-gray-600">Check-out</p>
            <p class="font-medium">{{ booking.tgl_checkout }}</p>
          </div>
          <div>
            <p class="text-sm text-gray-600">Duration</p>
            <p class="font-medium">{{ booking.total_malam }} night(s)</p>
          </div>
          <div>
            <p class="text-sm text-gray-600">Total Price</p>
            <p class="font-medium">Rp {{ booking.total_harga.toLocaleString() }}</p>
          </div>
        </div>
      </div>

      <div class="mb-6">
        <h2 class="text-base md:text-lg font-semibold text-gray-800 mb-4">Rooms Booked</h2>
        <div class="space-y-4">
          <div
            v-for="k in booking.kamars"
            :key="k.id_kamar"
            class="bg-white border rounded-lg overflow-hidden"
          >
            <div class="flex flex-col sm:flex-row items-start gap-4 p-4">
              <div
                class="w-full sm:w-32 h-48 sm:h-32 bg-gray-200 rounded-md overflow-hidden flex-shrink-0"
              >
                <img
                  :src="imageGetter(k.jenis_kamar?.url_gambar ?? k.url_gambar)"
                  class="w-full h-full object-cover"
                  :alt="`Room ${k.nomor_kamar}`"
                />
              </div>
              <div class="flex-1 w-full">
                <p class="font-semibold text-gray-800 text-lg">Room {{ k.nomor_kamar }}</p>
                <p class="text-sm text-gray-600 mt-1">{{ k.jenis_kamar.jenis_kasur }}</p>
                <p class="text-sm text-gray-700 font-medium mt-2">
                  Rp {{ k.jenis_kamar.harga_permalam.toLocaleString() }} / night
                </p>

                <p class="text-sm font-medium text-gray-700 mt-2">Room Facilities:</p>
                <div v-if="k.fasilitas && k.fasilitas.length > 0" class="mt-2 flex flex-wrap gap-2">
                  <div v-for="f in k.fasilitas" :key="f.id_fasilitas">
                    <i
                      :class="f.icon_fasilitas"
                      class="text-[#0e2858] w-4 h-4 flex-shrink-0 mt-0.5"
                    ></i>
                    <span class="px-2 py-1 bg-blue-50 text-[#0e2858] text-xs rounded-full">{{
                      f.nama_fasilitas
                    }}</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div v-if="booking.additional_services?.length" class="mb-6">
        <h2 class="text-base md:text-lg font-semibold text-gray-800 mb-4">Additional Services</h2>
        <div class="bg-white border rounded-lg p-4">
          <div class="space-y-3">
            <div
              v-for="service in booking.additional_services"
              :key="service.id_additional_service || service.id_service"
              class="flex flex-col sm:flex-row items-start gap-4 pb-3 border-b last:border-b-0 last:pb-0"
            >
              <div
                v-if="service.url_gambar"
                class="w-full sm:w-24 h-20 sm:h-16 bg-gray-200 rounded-md overflow-hidden flex-shrink-0"
              >
                <img
                  :src="
                    service.url_gambar.startsWith('http')
                      ? service.url_gambar
                      : `http://127.0.0.1:8000/storage/${service.url_gambar}`
                  "
                  class="w-full h-full object-cover"
                  :alt="service.nama_layanan || service.nama_service"
                />
              </div>
              <div class="flex-1">
                <p class="font-medium text-gray-800">{{ service.nama || service.nama_service }}</p>
                <p class="text-sm text-gray-600 mt-1">
                  {{ service.deskripsi_layanan || service.deskripsi_service }}
                </p>
              </div>
              <p class="text-sm font-semibold text-gray-700 sm:ml-4 whitespace-nowrap">
                Rp {{ (service.harga || service.harga_service || 0).toLocaleString() }}
              </p>
            </div>
          </div>
        </div>
      </div>

      <div v-if="booking.pembayaran" class="mb-6">
        <h2 class="text-base md:text-lg font-semibold text-gray-800 mb-4">Payment Information</h2>
        <div class="bg-white border rounded-lg p-4">
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <p class="text-sm text-gray-600">Payment Method</p>
              <p class="font-medium">{{ booking.pembayaran.metode_pembayaran }}</p>
            </div>
            <div>
              <p class="text-sm text-gray-600">Payment Status</p>
              <span
                class="inline-block px-3 py-1 text-xs rounded-full font-medium mt-1"
                :class="{
                  'bg-yellow-100 text-yellow-700':
                    booking.pembayaran.status_pembayaran === 'pending',
                  'bg-green-100 text-green-700': booking.pembayaran.status_pembayaran === 'paid',
                  'bg-red-100 text-red-700': booking.pembayaran.status_pembayaran === 'failed',
                }"
              >
                {{
                  booking.pembayaran.status_pembayaran.charAt(0).toUpperCase() +
                  booking.pembayaran.status_pembayaran.slice(1)
                }}
              </span>
            </div>
            <div>
              <p class="text-sm text-gray-600">Total Amount</p>
              <p class="font-medium text-lg text-green-600">
                Rp {{ booking.pembayaran.jumlah_dibayar.toLocaleString() }}
              </p>
            </div>
            <div v-if="booking.pembayaran.tanggal_pembayaran">
              <p class="text-sm text-gray-600">Payment Date</p>
              <p class="font-medium">{{ booking.pembayaran.tanggal_pembayaran }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  booking: Object,
  loadingDetail: Boolean,
  imageGetter: Function, // ← menerima fungsi dari parent
})
defineEmits(['back'])
</script>
