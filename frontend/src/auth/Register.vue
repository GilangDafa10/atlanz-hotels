<template>
  <div
    class="fixed inset-0 bg-cover bg-center flex items-center justify-center"
    style="background-image: url('/src/assets/gambar hotel.jpg')"
  >
    <div class="absolute inset-0 bg-black/50"></div>

    <div
      class="relative bg-white/40 backdrop-blur-md shadow-xl rounded-2xl p-8 w-[450px] max-w-[95%]"
    >
      <h2 class="text-3xl font-bold mb-6 text-center">Welcome to ATLANZ</h2>

      <form @submit.prevent="register" class="space-y-4">
        <!-- Name -->
        <div>
          <label class="block text-sm font-medium mb-1">Name</label>
          <input
            type="text"
            v-model="name"
            placeholder="Name"
            required
            class="w-full px-4 py-2 rounded-lg bg-white focus:ring-2 focus:ring-blue-500 outline-none"
          />
        </div>

        <!-- Email -->
        <div>
          <label class="block text-sm font-medium mb-1">Email</label>
          <input
            type="email"
            v-model="email"
            placeholder="example@gmail.com"
            required
            class="w-full px-4 py-2 rounded-lg bg-white focus:ring-2 focus:ring-blue-500 outline-none"
          />
        </div>

        <!-- Phone -->
        <div>
          <label class="block text-sm font-medium mb-1">Phone No</label>
          <input
            type="tel"
            v-model="phone"
            placeholder="Phone Number"
            required
            class="w-full px-4 py-2 rounded-lg bg-white focus:ring-2 focus:ring-blue-500 outline-none"
          />
        </div>

        <!-- Password -->
        <div>
          <label class="block text-sm font-medium mb-1">Password</label>
          <div class="relative">
            <input
              :type="showPassword ? 'text' : 'password'"
              v-model="password"
              placeholder="6+ characters"
              required
              class="w-full pr-10 px-4 py-2 rounded-lg bg-white focus:ring-2 focus:ring-blue-500 outline-none"
            />
            <button
              type="button"
              @click="togglePassword"
              aria-label="Toggle password visibility"
              class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-600 p-1"
            >
              <svg
                v-if="showPassword"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                class="w-5 h-5"
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
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                class="w-5 h-5"
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

        <!-- Submit -->
        <button
          type="submit"
          :disabled="loading"
          class="w-full bg-blue-600 hover:bg-blue-700 transition text-white py-2 rounded-lg font-semibold"
        >
          {{ loading ? 'Loading...' : 'Register' }}
        </button>
      </form>

      <!-- Error -->
      <p v-if="errorMessage" class="text-red-600 text-center mt-3">
        {{ errorMessage }}
      </p>

      <!-- Success -->
      <p v-if="successMessage" class="text-green-600 text-center mt-3">
        {{ successMessage }}
      </p>

      <p class="text-center mt-4 text-sm text-gray-700">
        Have Account?
        <a href="/login" class="text-blue-600 font-semibold hover:underline">Login</a>
      </p>
    </div>
  </div>
  <SuccessCreateModal
    v-model="showSuccessModal"
    :title="'Account Created Successfully!'"
    :message="'Kode OTP akan dikirim melalui email 😄'"
    :buttonText="'ke Gmail'"
    @confirm="handleConfirm"
  />
</template>

<script setup>
import { ref } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'
import SuccessCreateModal from '@/components/SuccessCreateModal.vue'

const router = useRouter()

const name = ref('')
const email = ref('')
const phone = ref('')
const password = ref('')
const showPassword = ref(false)
const loading = ref(false)

const errorMessage = ref(null)
const successMessage = ref(null)
const showSuccessModal = ref(false)

const togglePassword = () => {
  showPassword.value = !showPassword.value
}

const handleConfirm = () => {
  // SUCCESS — redirect ke halaman OTP
  router.push({
    path: '/otp',
    query: { email: email.value },
  })
}

const register = async () => {
  loading.value = true
  errorMessage.value = null
  successMessage.value = null

  try {
    const payload = {
      name: name.value,
      email: email.value,
      password: password.value,
      no_hp: phone.value,
    }

    const res = await axios.post('http://127.0.0.1:8000/api/register', payload)

    successMessage.value = res.data.message || 'Register success!'
    showSuccessModal.value = true // modal muncul
  } catch (err) {
    errorMessage.value =
      err.response?.data?.errors || err.response?.data?.message || 'Register gagal!'
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
/* Tidak pakai CSS manual selain background-image */
</style>
