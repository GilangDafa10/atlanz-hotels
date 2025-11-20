<template>
  <div
    class="fixed inset-0 bg-cover bg-center"
    style="background-image: url('/src/assets/gambar hotel.jpg')"
  >
    <!-- Overlay -->
    <div class="absolute inset-0 bg-black/50 flex items-center justify-center">
      <!-- Login Card -->
      <div class="backdrop-blur-md bg-white/50 shadow-xl p-8 rounded-2xl w-80 md:w-96 text-center">
        <h2 class="text-3xl font-semibold text-black mb-6">Sign in to ATLANZ</h2>

        <!-- Form -->
        <form @submit.prevent="login" class="space-y-4">
          <!-- Username -->
          <div class="text-left">
            <label class="text-sm font-medium text-black mb-1 block"> Username / Email </label>
            <input
              v-model="username"
              type="text"
              required
              class="w-full px-4 py-2 rounded-lg bg-white/90 focus:bg-white border border-gray-200 focus:ring-2 focus:ring-blue-500 outline-none"
              placeholder="Username / Email"
            />
          </div>

          <!-- Password -->
          <div class="text-left">
            <label class="text-sm font-medium text-black mb-1 block"> Password </label>

            <div class="relative flex items-center">
              <input
                :type="showPassword ? 'text' : 'password'"
                v-model="password"
                required
                placeholder="6+ characters"
                class="w-full px-4 py-2 pr-12 rounded-lg bg-white/90 focus:bg-white border border-gray-200 focus:ring-2 focus:ring-blue-500 outline-none"
              />

              <button
                type="button"
                @click="togglePassword"
                class="absolute right-3 text-gray-600 hover:text-black transition"
              >
                <svg
                  v-if="showPassword"
                  xmlns="http://www.w3.org/2000/svg"
                  class="w-6 h-6"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="1.8"
                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                  />
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="1.8"
                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                  />
                </svg>

                <svg
                  v-else
                  xmlns="http://www.w3.org/2000/svg"
                  class="w-6 h-6"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="1.8"
                    d="M3 3l18 18M10.58 10.58A3 3 0 0113.42 13.42M6.26 6.26A9.77 9.77 0 003 12c1.274 4.057 5.064 7 9.542 7a9.77 9.77 0 005.18-1.54M17.74 17.74A9.77 9.77 0 0021 12c-1.274-4.057-5.064-7-9.542-7a9.77 9.77 0 00-5.18 1.54"
                  />
                </svg>
              </button>
            </div>
          </div>

          <!-- Button -->
          <button
            :disabled="loading"
            class="w-full mt-1 bg-blue-600 text-white py-2 rounded-lg text-sm font-semibold hover:bg-blue-700 active:scale-95 transition"
          >
            {{ loading ? 'Loading...' : 'Login' }}
          </button>
        </form>

        <p v-if="errorMessage" class="text-red-600 text-sm mt-3">
          {{ errorMessage }}
        </p>

        <p class="mt-4 text-gray-700 text-sm">
          No Account?
          <a href="/register" class="text-blue-600 font-medium hover:underline"> Create Account </a>
        </p>
      </div>
    </div>
  </div>

  <!-- SUKSES LOGIN MODAL -->
  <SuccessLoginModal v-model="showSuccessModal" />
</template>

<script setup>
import { ref } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'
import SuccessLoginModal from '@/components/SuccessLoginModal.vue'

const router = useRouter()

const username = ref('')
const password = ref('')
const showPassword = ref(false)
const loading = ref(false)
const errorMessage = ref(null)

const showSuccessModal = ref(false)

const togglePassword = () => {
  showPassword.value = !showPassword.value
}

const login = async () => {
  loading.value = true
  errorMessage.value = null

  try {
    const payload = {
      email: username.value,
      password: password.value,
    }

    const res = await axios.post('http://127.0.0.1:8000/api/login', payload)

    localStorage.setItem('token', res.data.data.token)
    localStorage.setItem('isLoggedIn', 'true')

    // 1️⃣ Tampilkan modal sukses login
    showSuccessModal.value = true

    // 2️⃣ Setelah 3 detik → redirect ke /
    setTimeout(() => {
      router.push('/')
    }, 3000)
  } catch (err) {
    errorMessage.value = err.response?.data?.message || 'Login gagal!'
  } finally {
    loading.value = false
  }
}
</script>
