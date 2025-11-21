<template>
  <div class="min-h-screen" style="background-color: #0E2858;">
    <!-- LOADING -->
    <div 
      v-if="loadingPage" 
      class="w-full h-screen flex items-center justify-center text-white text-lg"
    >
      <div class="flex flex-col items-center gap-3">
        <div class="w-10 h-10 border-4 border-white border-t-transparent rounded-full animate-spin"></div>
        <span>Loading...</span>
      </div>
    </div>

    <!-- CONTENT -->
    <div v-else class="flex gap-8 px-6 md:px-20 pt-16 pb-12">

      <!-- SIDEBAR (tidak diubah) -->
      <div class="w-80 bg-white rounded-lg shadow-lg p-6 h-fit my-10">
        <div class="space-y-3">
          <!-- TAB : PROFILE -->
          <div 
            class="flex items-center gap-3 px-4 py-3 rounded cursor-pointer transition"
            :class="activeTab === 'profile' ? 'bg-blue-500 text-white' : 'text-gray-700 hover:bg-gray-100'"
            @click="switchTab('profile')"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            <span class="text-sm font-semibold">My Profile</span>
          </div>
          <!-- TAB : RESERVATION -->
          <div 
            class="flex items-center gap-3 px-4 py-3 rounded cursor-pointer transition"
            :class="activeTab === 'reservation' ? 'bg-blue-500 text-white' : 'text-gray-700 hover:bg-gray-100'"
            @click="switchTab('reservation')"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            <span class="text-sm font-medium">My Reservation</span>
          </div>

    
        </div>
      </div>

      <!-- RIGHT SIDE CONTENT -->
      <div class="flex-1 bg-white rounded-lg shadow-lg p-6 md:p-8 my-10">

        <!-- PROFILE TAB -->
        <div v-if="activeTab === 'profile'">
          <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-800 mb-2">My Profile</h1>
          </div>

          <!-- User -->
          <div class="mb-8">
            <h2 class="text-gray-600 text-sm font-semibold mb-4 tracking-wide">User</h2>
            <div class="flex items-center gap-4">
              <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
              </svg>
              <div class="flex-1">
                <label class="text-gray-500 text-xs block mb-1">Name</label>
                <span class="text-gray-800 font-medium">{{ name }}</span>
                <div class="border-b border-gray-300 mt-2"></div>
              </div>
            </div>
          </div>

          <!-- Contacts -->
          <div>
            <h2 class="text-gray-600 text-sm font-semibold mb-4 tracking-wide">Contacts</h2>
            <div class="space-y-4">
              <div class="flex items-center gap-4">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
                <div class="flex-1">
                  <label class="text-gray-500 text-xs block mb-1">Email</label>
                  <span class="text-gray-800 font-medium">{{ email }}</span>
                  <div class="border-b border-gray-300 mt-2"></div>
                </div>
              </div>
              <div class="flex items-center gap-4">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                </svg>
                <div class="flex-1">
                  <label class="text-gray-500 text-xs block mb-1">Mobile or Cell Phone</label>
                  <span class="text-gray-800 font-medium">{{ no_hp }}</span>
                  <div class="border-b border-gray-300 mt-2"></div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- RESERVATION TAB -->
        <div v-else-if="activeTab === 'reservation'">
          <div v-if="!selectedBooking">
            <div class="flex justify-between items-center mb-6">
              <h1 class="text-2xl font-bold text-gray-800">My Reservation</h1>
            </div>

            <div v-if="loadingBooking" class="text-gray-500 py-4">
              Loading booking history...
            </div>

            <div v-else-if="bookings.length === 0" class="text-gray-500 py-4">
              You don’t have any bookings.
            </div>

            <!-- LIST BOOKING (Info Dasar Saja) -->
            <div v-else class="space-y-5">
              <div 
                v-for="b in bookings" 
                :key="b.id_booking"
                class="border border-gray-200 rounded-xl p-5 hover:shadow-md transition"
              >
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                  <div>
                    <h2 class="text-lg font-bold text-gray-800">Booking #{{ b.id_booking }}</h2>
                    <p class="text-sm text-gray-600 mt-1">
                      {{ b.tgl_checkin }} → {{ b.tgl_checkout }} • {{ b.total_malam }} night(s)
                    </p>
                    <p class="text-sm text-gray-700 mt-1 font-medium">
                      Total: Rp {{ b.total_harga.toLocaleString() }}
                    </p>
                  </div>

                  <div class="flex items-center gap-3">
                    <span 
                      class="px-3 py-1 text-xs rounded-full font-medium"
                      :class="{
                        'bg-yellow-100 text-yellow-700': b.status_booking === 'pending',
                        'bg-green-100 text-green-700': b.status_booking === 'selesai',
                        'bg-red-100 text-red-700': b.status_booking === 'batal'
                      }"
                    >
                      {{ b.status_booking.charAt(0).toUpperCase() + b.status_booking.slice(1) }}
                    </span>
                    <button 
                      @click="viewBookingDetails(b)"
                      class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg transition"
                    >
                      View Details
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- DETAIL BOOKING -->
          <div v-else>
            <div class="flex justify-between items-center mb-6">
              <h1 class="text-2xl font-bold text-gray-800">Booking Detail</h1>
              <button 
                @click="selectedBooking = null"
                class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg text-sm font-medium transition"
              >
                ← Back
              </button>
            </div>

            <div class="bg-gray-50 rounded-lg p-6 mb-6">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                  <p class="text-sm text-gray-600">Booking ID</p>
                  <p class="font-medium">#{{ selectedBooking.id_booking }}</p>
                </div>
                <div>
                  <p class="text-sm text-gray-600">Status</p>
                  <span 
                    class="inline-block px-3 py-1 text-xs rounded-full font-medium mt-1"
                    :class="{
                      'bg-yellow-100 text-yellow-700': selectedBooking.status_booking === 'pending',
                      'bg-green-100 text-green-700': selectedBooking.status_booking === 'selesai',
                      'bg-red-100 text-red-700': selectedBooking.status_booking === 'batal'
                    }"
                  >
                    {{ selectedBooking.status_booking.charAt(0).toUpperCase() + selectedBooking.status_booking.slice(1) }}
                  </span>
                </div>
                <div>
                  <p class="text-sm text-gray-600">Check-in</p>
                  <p class="font-medium">{{ selectedBooking.tgl_checkin }}</p>
                </div>
                <div>
                  <p class="text-sm text-gray-600">Check-out</p>
                  <p class="font-medium">{{ selectedBooking.tgl_checkout }}</p>
                </div>
                <div>
                  <p class="text-sm text-gray-600">Duration</p>
                  <p class="font-medium">{{ selectedBooking.total_malam }} night(s)</p>
                </div>
                <div>
                  <p class="text-sm text-gray-600">Total Price</p>
                  <p class="font-medium">Rp {{ selectedBooking.total_harga.toLocaleString() }}</p>
                </div>
              </div>
            </div>

            <!-- Kamar yang Dipesan -->
            <div>
              <h2 class="text-lg font-semibold text-gray-800 mb-4">Rooms Booked</h2>
              <div class="space-y-4">
                <div 
                  v-for="k in selectedBooking.kamars"
                  :key="k.id_kamar"
                  class="flex items-start gap-4 p-4 bg-white border rounded-lg"
                >
                  <div class="w-20 h-20 bg-gray-200 rounded-md overflow-hidden flex-shrink-0">
                  <img 
                    :src="k.jenis_kamar?.url_gambar ?? k.url_gambar ?? '/images/default-room.jpg'"
                    class="w-full h-full object-cover"
                    alt="Room {{ k.nomor_kamar }}"
                  >
                  </div>
                  <div class="flex-1">
                    <p class="font-semibold text-gray-800">Room {{ k.nomor_kamar }}</p>
                    <p class="text-sm text-gray-600">{{ k.jenis_kamar.jenis_kasur }}</p>
                    <p class="text-sm text-gray-700 font-medium mt-1">
                      Rp {{ k.jenis_kamar.harga_permalam.toLocaleString() }} / night
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import axios from "axios";
import { ref, onMounted } from "vue";

const activeTab = ref("profile");
const selectedBooking = ref(null); // untuk menampilkan detail

const name = ref("");
const email = ref("");
const no_hp = ref("");

const bookings = ref([]);
const loadingPage = ref(true);
const loadingBooking = ref(false);

const switchTab = async (tab) => {
  activeTab.value = tab;
  selectedBooking.value = null; // reset saat ganti tab

  if (tab === "reservation") {
    getBookings();
  }
};

const getProfile = async () => {
  try {
    const token = localStorage.getItem("token");
    const res = await axios.get("http://localhost:8000/api/me", {
      headers: { Authorization: `Bearer ${token}` }
    });
    name.value = res.data.data.name;
    email.value = res.data.data.email;
    no_hp.value = res.data.data.no_hp;
  } catch (e) {
    console.error(e);
  }
};

const getBookings = async () => {
  loadingBooking.value = true;
  try {
    const token = localStorage.getItem("token");
    const res = await axios.get("http://localhost:8000/api/booking", {
      headers: { Authorization: `Bearer ${token}` }
    });
    bookings.value = res.data.data.bookings;
  } catch (e) {
    console.error(e);
  }
  loadingBooking.value = false;
};

const viewBookingDetails = (booking) => {
  selectedBooking.value = booking;
};

onMounted(async () => {
  await getProfile();
  loadingPage.value = false;
});
</script>