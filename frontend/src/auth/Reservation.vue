<template>
  <div class="min-h-screen" style="background-color: #0E2858;">
    <div class="flex gap-8 px-20 pt-16 pb-12">
      <!-- Sidebar -->
      <div class="w-80 bg-white rounded-lg shadow-lg p-6 h-fit">
        <div class="text-gray-500 text-xs font-semibold mb-4 tracking-wide">
          YOUR PERSONAL AREA
        </div>
        
        <div class="flex items-center gap-3 mb-4 pb-4 border-b">
          <img 
            :src="profileImage" 
            alt="Profile" 
            class="w-10 h-10 rounded-full object-cover"
          />
          <span class="font-semibold text-gray-800">Salman Faris</span>
        </div>

        <div class="space-y-3">
          <div class="flex items-center gap-3 px-4 py-3 bg-blue-500 text-white rounded cursor-pointer transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            <span class="text-sm font-semibold">My Reservation</span>
          </div>

          <div class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-gray-100 rounded cursor-pointer transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            <span class="text-sm">My Profile</span>
          </div>

          <div class="border-t pt-3 mt-3">
            <div class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-gray-100 rounded cursor-pointer transition">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
              </svg>
              <span class="text-sm">New Reservation</span>
            </div>

            <div class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-gray-100 rounded cursor-pointer transition">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
              </svg>
              <span class="text-sm">Log Out</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Reservation Section -->
      <div class="flex-1 bg-white rounded-lg shadow-lg p-8">
        <div class="mb-8">
          <h1 class="text-2xl font-bold text-gray-800 mb-4">My Reservation</h1>
          <div class="flex items-center gap-3">
            <img 
              :src="profileImage" 
              alt="Profile" 
              class="w-8 h-8 rounded-full object-cover"
            />
            <div>
              <span class="text-gray-800 font-semibold block">Salman Faris</span>
              <span class="text-gray-500 text-xs">ID: 081234567892</span>
            </div>
          </div>
        </div>

        <!-- Past Reservation Section -->
        <div>
          <h2 class="text-gray-600 text-sm font-semibold mb-4 tracking-wide">Past Reservation</h2>
          
          <div class="space-y-4">
            <!-- Reservation 1 -->
            <div class="flex gap-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
              <!-- Reservation Image -->
              <div class="w-32 h-24 bg-blue-900 rounded-lg flex-shrink-0"></div>
              
              <!-- Reservation Details -->
              <div class="flex-1">
                <div class="mb-4">
                  <h3 class="text-gray-800 font-semibold mb-1">Reservation</h3>
                  <p class="text-gray-600 text-sm">2641235678565454 · Wed, 18 Oktober 2025</p>
                </div>
                
                <div class="flex gap-6 items-start">
                  <div class="flex items-start gap-2">
                    <svg class="w-5 h-5 text-gray-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <div>
                      <label class="text-gray-500 text-xs block">Check In</label>
                      <p class="text-gray-800 text-sm font-medium">25 Oktober 2025</p>
                    </div>
                  </div>
                  <div class="flex items-start gap-2">
                    <svg class="w-5 h-5 text-gray-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <div>
                      <label class="text-gray-500 text-xs block">Check Out</label>
                      <p class="text-gray-800 text-sm font-medium">25 Oktober 2025</p>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Right Side: Total Price and Button -->
              <div class="flex flex-col items-end justify-end gap-3">
                <div class="text-right">
                  <label class="text-gray-500 text-xs block mb-1">Total Price</label>
                  <p class="text-gray-800 font-semibold">Rp 1,699,999</p>
                </div>
                <button class="px-6 py-2 bg-blue-500 text-white text-sm font-medium rounded hover:bg-blue-600 transition whitespace-nowrap">
                  Show Reservation
                </button>
              </div>
            </div>

            <!-- Reservation 2 -->
            <div class="flex gap-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
              <!-- Reservation Image -->
              <div class="w-32 h-24 bg-blue-900 rounded-lg flex-shrink-0"></div>
              
              <!-- Reservation Details -->
              <div class="flex-1">
                <div class="mb-4">
                  <h3 class="text-gray-800 font-semibold mb-1">Reservation</h3>
                  <p class="text-gray-600 text-sm">1234567890123456 · Mon, 10 Oktober 2025</p>
                </div>
                
                <div class="flex gap-6 items-start">
                  <div class="flex items-start gap-2">
                    <svg class="w-5 h-5 text-gray-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <div>
                      <label class="text-gray-500 text-xs block">Check In</label>
                      <p class="text-gray-800 text-sm font-medium">12 Oktober 2025</p>
                    </div>
                  </div>
                  <div class="flex items-start gap-2">
                    <svg class="w-5 h-5 text-gray-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <div>
                      <label class="text-gray-500 text-xs block">Check Out</label>
                      <p class="text-gray-800 text-sm font-medium">15 Oktober 2025</p>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Right Side: Total Price and Button -->
              <div class="flex flex-col items-end justify-end gap-3">
                <div class="text-right">
                  <label class="text-gray-500 text-xs block mb-1">Total Price</label>
                  <p class="text-gray-800 font-semibold">Rp 2,499,999</p>
                </div>
                <button class="px-6 py-2 bg-blue-500 text-white text-sm font-medium rounded hover:bg-blue-600 transition whitespace-nowrap">
                  Show Reservation
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import poppinsImage from '@/assets/Profile.png'

export default {
  name: 'ReservationContent',
  data() {
    return {
      profileImage: poppinsImage
    }
  }
}
</script>

<style scoped>
* {
  font-family: 'Poppins', sans-serif;
}
</style>