<template>
  <div class="min-h-screen" style="background-color: #0e2858">
    <!-- LOADING -->
    <div
      v-if="loadingPage"
      class="w-full h-screen flex items-center justify-center text-white text-lg"
    >
      <div class="flex flex-col items-center gap-3">
        <div
          class="w-10 h-10 border-4 border-white border-t-transparent rounded-full animate-spin"
        ></div>
        <span>Loading...</span>
      </div>
    </div>

    <!-- CONTENT -->
    <div v-else class="px-4 md:px-8 lg:px-20 pt-16 md:pt-20 pb-12">
      <!-- Mobile Tab Toggle -->
      <div class="lg:hidden mb-6 mt-6">
        <button
          @click="showSidebar = !showSidebar"
          class="w-full bg-white rounded-lg shadow-lg p-4 flex items-center justify-between text-gray-800 font-medium"
        >
          <span>{{ activeTab === 'profile' ? 'My Profile' : 'My Reservation' }}</span>
          <svg
            class="w-5 h-5 transition-transform"
            :class="{ 'rotate-180': showSidebar }"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M19 9l-7 7-7-7"
            />
          </svg>
        </button>

        <!-- Dropdown -->
        <div v-if="showSidebar" class="mt-2 bg-white rounded-lg shadow-lg p-4 space-y-2">
          <div
            class="flex items-center gap-3 px-4 py-3 rounded cursor-pointer transition"
            :class="
              activeTab === 'profile' ? 'bg-blue-500 text-white' : 'text-gray-700 hover:bg-gray-100'
            "
            @click="switchTabMobile('profile')"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
              ></path>
            </svg>
            <span class="text-sm font-semibold">My Profile</span>
          </div>
          <div
            class="flex items-center gap-3 px-4 py-3 rounded cursor-pointer transition"
            :class="
              activeTab === 'reservation'
                ? 'bg-blue-500 text-white'
                : 'text-gray-700 hover:bg-gray-100'
            "
            @click="switchTabMobile('reservation')"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
              ></path>
            </svg>
            <span class="text-sm font-medium">My Reservation</span>
          </div>
        </div>
      </div>

      <div class="flex flex-col lg:flex-row gap-6 lg:gap-8">
        <!-- Sidebar -->
        <div class="hidden lg:block w-full lg:w-80 bg-white rounded-lg shadow-lg p-6 h-fit lg:my-10">
          <div class="space-y-3">
            <div
              class="flex items-center gap-3 px-4 py-3 rounded cursor-pointer transition"
              :class="
                activeTab === 'profile'
                  ? 'bg-blue-500 text-white'
                  : 'text-gray-700 hover:bg-gray-100'
              "
              @click="switchTab('profile')"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                ></path>
              </svg>
              <span class="text-sm font-semibold">My Profile</span>
            </div>

            <div
              class="flex items-center gap-3 px-4 py-3 rounded cursor-pointer transition"
              :class="
                activeTab === 'reservation'
                  ? 'bg-blue-500 text-white'
                  : 'text-gray-700 hover:bg-gray-100'
              "
              @click="switchTab('reservation')"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                ></path>
              </svg>
              <span class="text-sm font-medium">My Reservation</span>
            </div>
          </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 bg-white rounded-lg shadow-lg p-4 md:p-6 lg:p-8 lg:my-10">
          <!-- PROFILE TAB -->
          <div v-if="activeTab === 'profile'">
            <div class="mb-8 flex justify-between items-center">
              <h1 class="text-2xl font-bold text-gray-800">My Profile</h1>

              <!-- Edit Button -->
              <button
                v-if="!isEditing"
                @click="startEdit"
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition"
              >
                Edit Profile
              </button>
            </div>

            <!-- NAME -->
            <div class="mb-8">
              <h2 class="text-gray-600 text-sm font-semibold mb-4 tracking-wide">User</h2>
              <div class="flex items-start gap-4">
                <div class="flex-1">
                  <label class="text-gray-500 text-xs block mb-1">Name</label>

                  <!-- VIEW -->
                  <span v-if="!isEditing" class="text-gray-800 font-medium">{{ name }}</span>

                  <!-- EDIT -->
                  <input
                    v-else
                    v-model="formProfile.name"
                    type="text"
                    class="w-full border rounded-lg px-3 py-2 text-sm"
                  />

                  <div class="border-b border-gray-300 mt-2"></div>
                </div>
              </div>
            </div>

            <!-- EMAIL -->
            <div class="mb-8">
              <h2 class="text-gray-600 text-sm font-semibold mb-4 tracking-wide">Contacts</h2>
              <div class="space-y-4">

                <!-- EMAIL -->
                <div>
                  <label class="text-gray-500 text-xs block mb-1">Email</label>

                  <span v-if="!isEditing" class="text-gray-800 font-medium break-all">{{ email }}</span>

                  <input
                    v-else
                    v-model="formProfile.email"
                    type="email"
                    class="w-full border rounded-lg px-3 py-2 text-sm"
                  />

                  <div class="border-b border-gray-300 mt-2"></div>
                </div>

                <!-- ALAMAT -->
                <div>
                  <label class="text-gray-500 text-xs block mb-1">Alamat</label>

                  <span v-if="!isEditing" class="text-gray-800 font-medium">{{ alamat }}</span>

                  <input
                    v-else
                    v-model="formProfile.alamat"
                    type="text"
                    class="w-full border rounded-lg px-3 py-2 text-sm"
                  />

                  <div class="border-b border-gray-300 mt-2"></div>
                </div>

                <!-- NO HP -->
                <div>
                  <label class="text-gray-500 text-xs block mb-1">Mobile / Cell Phone</label>

                  <span v-if="!isEditing" class="text-gray-800 font-medium">{{ no_hp }}</span>

                  <input
                    v-else
                    v-model="formProfile.no_hp"
                    type="text"
                    class="w-full border rounded-lg px-3 py-2 text-sm"
                  />

                  <div class="border-b border-gray-300 mt-2"></div>
                </div>
              </div>
            </div>

            <!-- SAVE / CANCEL BUTTONS -->
            <div v-if="isEditing" class="flex gap-3 mt-6">
              <button
                @click="saveProfile"
                class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition"
              >
                Save Perubahan
              </button>

              <button
                @click="isEditing = false"
                class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition"
              >
                Cancel
              </button>
            </div>
          </div>

          <!-- ================= RESERVATION TAB ================= -->
<div v-else-if="activeTab === 'reservation'">
  
  <!-- LOADING LIST -->
  <div v-if="loadingBooking" class="text-center py-10 text-gray-500">
    Loading reservations...
  </div>

  <!-- JIKA BELUM ADA BOOKING -->
  <div v-else-if="bookings.length === 0" class="text-center py-10 text-gray-600">
    <p class="text-lg font-medium">Belum ada booking.</p>
  </div>

  <!-- LIST BOOKING -->
  <div v-else class="space-y-4">
    <div
      v-for="b in bookings"
      :key="b.id_booking"
      class="border rounded-lg p-4 shadow hover:shadow-md transition cursor-pointer"
      @click="viewBookingDetails(b)"
    >
      <h3 class="text-lg font-semibold text-gray-800">
        Booking #{{ b.id_booking }}
      </h3>
      <p class="text-sm text-gray-600">Check-in: {{ b.tgl_checkin }}</p>
      <p class="text-sm text-gray-600">Check-out: {{ b.tgl_checkout }}</p>
      <p class="text-sm text-gray-600">Status: {{ b.status_booking }}</p>
    </div>
  </div>

  <!-- DETAIL BOOKING -->
  <div v-if="selectedBooking" class="mt-6 p-6 border rounded-xl shadow-lg bg-gray-50">
    <h2 class="text-xl font-bold mb-4">Detail Booking</h2>

    <p><strong>ID Booking:</strong> {{ selectedBooking.id_booking }}</p>
    <p><strong>Total Malam:</strong> {{ selectedBooking.total_malam }}</p>
    <p><strong>Total Harga:</strong> Rp {{ selectedBooking.total_harga }}</p>
    <p><strong>Status:</strong> {{ selectedBooking.status_booking }}</p>

    <hr class="my-4" />

    <h3 class="font-semibold text-lg mb-2">Detail Kamar</h3>
    <p v-if="selectedBooking.kamar">
      <strong>Nama Kamar:</strong> {{ selectedBooking.kamar.nama_kamar }}
      <br />
      <strong>Harga Kamar:</strong> {{ selectedBooking.kamar.harga }}
    </p>

    <hr class="my-4" />

    <h3 class="font-semibold text-lg mb-2">Pembayaran</h3>
    <p>
      <strong>Metode:</strong> {{ selectedBooking.pembayaran?.metode }}
      <br />
      <strong>Status:</strong> {{ selectedBooking.pembayaran?.status }}
    </p>

    <button
      @click="selectedBooking = null"
      class="mt-6 px-4 py-2 bg-gray-300 text-gray-900 rounded-lg hover:bg-gray-400 transition"
    >
      Tutup Detail
    </button>
  </div>

</div>


        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import axios from "axios"
import { ref, onMounted } from "vue"

const activeTab = ref("profile")
const selectedBooking = ref(null)
const showSidebar = ref(false)

const userId = ref(null)

const name = ref("")
const email = ref("")
const alamat = ref("")
const no_hp = ref("")

const bookings = ref([])
const loadingPage = ref(true)
const loadingDetail = ref(false)
const loadingBooking = ref(false)

const isEditing = ref(false)

const formProfile = ref({
  name: "",
  email: "",
  alamat: "",
  no_hp: "",
})

const startEdit = () => {
  formProfile.value = {
    name: name.value,
    email: email.value,
    alamat: alamat.value,
    no_hp: no_hp.value,
  }
  isEditing.value = true
}

const saveProfile = async () => {
  try {
    const token = localStorage.getItem("token")

    await axios.put(
      `http://127.0.0.1:8000/api/profile/${userId.value}`,
      formProfile.value,
      {
        headers: { Authorization: `Bearer ${token}` },
      }
    )

    // update UI
    name.value = formProfile.value.name
    email.value = formProfile.value.email
    alamat.value = formProfile.value.alamat
    no_hp.value = formProfile.value.no_hp

    isEditing.value = false
    alert("Profile updated successfully!")
  } catch (err) {
    console.error(err)
    alert("Failed to update profile")
  }
}

const switchTab = async (tab) => {
  activeTab.value = tab
  selectedBooking.value = null
  if (tab === "reservation") getBookings()
}

const switchTabMobile = async (tab) => {
  activeTab.value = tab
  selectedBooking.value = null
  showSidebar.value = false
  if (tab === "reservation") getBookings()
}

const getProfile = async () => {
  try {
    const token = localStorage.getItem("token")
    const res = await axios.get("http://127.0.0.1:8000/api/me", {
      headers: { Authorization: `Bearer ${token}` },
    })

    const u = res.data.data
    userId.value = u.id
    
    name.value = u.name
    email.value = u.email
    alamat.value = u.alamat
    no_hp.value = u.no_hp
  } catch (e) {
    console.error(e)
  }
}

const getBookings = async () => {
  loadingBooking.value = true
  try {
    const token = localStorage.getItem('token')
    const res = await axios.get('http://localhost:8000/api/booking', {
      headers: { Authorization: `Bearer ${token}` },
    })

    let d = res.data.data

    console.log("HASIL API BOOKING =", d) // 🔥 WAJIB UNTUK DEBUG

    if (!d) {
      bookings.value = []
    }
    // Jika API mengirim { data: { bookings: [...] } }
    else if (Array.isArray(d.bookings)) {
      bookings.value = d.bookings
    }
    // Jika API mengirim { data: { booking: [...] } }
    else if (Array.isArray(d.booking)) {
      bookings.value = d.booking
    }
    // Jika API mengirim { data: [ ... ] }
    else if (Array.isArray(d)) {
      bookings.value = d
    }
    // Jika hanya 1 booking object
    else if (d.booking) {
      bookings.value = [d.booking]
    }
    else {
      bookings.value = []
    }

  } catch (e) {
    console.error("Error get bookings:", e)
    bookings.value = []
  }
  loadingBooking.value = false
}

const viewBookingDetails = async (booking) => {
  loadingDetail.value = true
  try {
    const token = localStorage.getItem("token")
    const res = await axios.get(
      `http://127.0.0.1:8000/api/booking/${booking.id_booking}`,
      {
        headers: { Authorization: `Bearer ${token}` },
      }
    )

    const detail = res.data.data.booking || res.data.data
    selectedBooking.value = detail
  } catch (e) {
    console.error("Error detail booking:", e)
  }
  loadingDetail.value = false
}

const getImageUrl = (urlGambar) => {
  if (!urlGambar) return "/images/default-room.jpg"
  return `http://127.0.0.1:8000/storage/${urlGambar}`
}


onMounted(async () => {
  await getProfile()
  loadingPage.value = false
})


</script>
