<!-- src/views/MyAccount.vue -->
<template>
  <div class="min-h-screen" style="background-color: #0e2858">
    <!-- LOADING (inline) -->
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
              />
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
              />
            </svg>
            <span class="text-sm font-medium">My Reservation</span>
          </div>
        </div>
      </div>

      <div class="flex flex-col lg:flex-row gap-6 lg:gap-8">
        <!-- SIDEBAR -->
        <div
          class="hidden lg:block w-full lg:w-80 bg-white rounded-lg shadow-lg p-6 h-fit lg:my-10"
        >
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
                />
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
                />
              </svg>
              <span class="text-sm font-medium">My Reservation</span>
            </div>
          </div>
        </div>

        <!-- CONTENT AREA -->
        <div class="flex-1 bg-white rounded-lg shadow-lg p-4 md:p-6 lg:p-8 lg:my-10">
          <ProfileTab
            v-if="activeTab === 'profile'"
            :name="name"
            :email="email"
            :alamat="alamat"
            :no_hp="no_hp"
            :is-editing="isEditing"
            :form-profile="formProfile"
            @start-edit="startEdit"
            @save="(data) => saveProfile(data)"
            @cancel="cancelEdit"
          />
          <ReservationTab
            v-else-if="activeTab === 'reservation'"
            :selected-booking="selectedBooking"
            :bookings="bookings"
            :loading-booking="loadingBooking"
            :loading-detail="loadingDetail"
            :image-getter="getImageUrl"
            @view-details="viewBookingDetails"
            @back="selectedBooking = null"
          />
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import ProfileTab from '@/components/ProfileTab.vue'
import ReservationTab from '@/components/ReservationTab.vue'

// === FUNGSI DAN STATE UTAMA TETAP DI SINI ===
const activeTab = ref('profile')
const selectedBooking = ref(null)
const showSidebar = ref(false)

const userId = ref(null) // tambahkan ini
const name = ref('')
const email = ref('')
const no_hp = ref('')

const bookings = ref([])
const loadingPage = ref(true)
const loadingBooking = ref(false)
const loadingDetail = ref(false)

// 🔽 Fungsi getImageUrl tetap di dalam file ini
const getImageUrl = (urlGambar) => {
  if (!urlGambar) return '/images/default-room.jpg'
  return `http://localhost:8000/storage/${urlGambar}`
}

// --- Methods ---
const switchTab = async (tab) => {
  activeTab.value = tab
  selectedBooking.value = null
  if (tab === 'reservation') getBookings()
}

const switchTabMobile = async (tab) => {
  activeTab.value = tab
  selectedBooking.value = null
  showSidebar.value = false
  if (tab === 'reservation') getBookings()
}

// Tambahkan state baru
const alamat = ref('')
const isEditing = ref(false)
const formProfile = ref({
  name: '',
  email: '',
  alamat: '',
  no_hp: '',
})

// Perbarui getProfile agar juga ambil alamat
const getProfile = async () => {
  try {
    const token = localStorage.getItem('token')
    const res = await axios.get('http://localhost:8000/api/me', {
      headers: { Authorization: `Bearer ${token}` },
    })
    const user = res.data.data
    userId.value = user.id
    name.value = user.name
    email.value = user.email
    no_hp.value = user.no_hp
    alamat.value = user.alamat || '' // pastikan field ini ada di API

    // Sync form awal
    formProfile.value = {
      name: user.name,
      email: user.email,
      alamat: user.alamat || '',
      no_hp: user.no_hp,
    }
  } catch (e) {
    console.error(e)
  }
}

// Methods untuk edit
const startEdit = () => {
  isEditing.value = true
  // formProfile sudah diisi di getProfile
}

const cancelEdit = () => {
  isEditing.value = false
  // Opsional: reset form ke nilai awal
  formProfile.value = {
    name: name.value,
    email: email.value,
    alamat: alamat.value,
    no_hp: no_hp.value,
  }
}

const saveProfile = async (updatedData) => {
  if (!userId.value) {
    alert('User ID not found. Please reload the page.')
    return
  }

  try {
    const token = localStorage.getItem('token')
    console.log('Sending update:', updatedData) // Debug log

    const response = await axios.put(
      `http://localhost:8000/api/profile/${userId.value}`,
      updatedData,
      {
        headers: {
          Authorization: `Bearer ${token}`,
          Accept: 'application/json',
          'Content-Type': 'application/json',
        },
      },
    )

    console.log('Update response:', response.data) // Debug log

    // Jika sukses, update data lokal dengan data yang baru
    name.value = updatedData.name
    email.value = updatedData.email
    alamat.value = updatedData.alamat
    no_hp.value = updatedData.no_hp

    // Update formProfile juga
    formProfile.value = { ...updatedData }

    isEditing.value = false
    alert('Profile updated successfully!')
  } catch (error) {
    console.error('Update error:', error)
    console.error('Error response:', error.response?.data) // Debug log
    let message = 'Failed to update profile.'
    if (error.response?.data?.message) {
      message = error.response.data.message
    } else if (error.response?.data?.errors) {
      // Jika Laravel mengembalikan validation errors
      const errors = error.response.data.errors
      message = Object.values(errors).flat().join('\n')
    }
    alert(message)
  }
}

const getBookings = async () => {
  loadingBooking.value = true
  try {
    const token = localStorage.getItem('token')
    const res = await axios.get('http://localhost:8000/api/booking', {
      headers: { Authorization: `Bearer ${token}` },
    })
    const data = res.data.data
    if (data.bookings) {
      bookings.value = data.bookings
    } else if (Array.isArray(data)) {
      bookings.value = data
    } else if (data.booking) {
      bookings.value = [data.booking]
    } else {
      bookings.value = []
    }
  } catch (e) {
    console.error(e)
    bookings.value = []
  }
  loadingBooking.value = false
}

const viewBookingDetails = async (booking) => {
  loadingDetail.value = true
  try {
    const token = localStorage.getItem('token')
    const res = await axios.get(`http://localhost:8000/api/booking/${booking.id_booking}`, {
      headers: { Authorization: `Bearer ${token}` },
    })
    const detailData = res.data.data.booking || res.data.data
    selectedBooking.value = detailData
  } catch (e) {
    console.error('Error fetching booking detail:', e)
    alert('Failed to load booking details')
  }
  loadingDetail.value = false
}

onMounted(async () => {
  await getProfile()
  loadingPage.value = false
})
</script>
