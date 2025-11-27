<template>
  <div class="flex flex-col items-center justify-center h-screen bg-gray-100">
    <div class="bg-white p-8 rounded-xl shadow-lg w-[350px]">
      <h2 class="text-2xl font-bold text-center mb-4">Verify OTP</h2>

      <p class="text-center text-gray-700 mb-6">
        Enter the 6-digit OTP sent to:
        <span class="font-semibold">{{ email }}</span>
      </p>

      <input
        v-model="code"
        maxlength="6"
        class="w-full px-4 py-2 border rounded-lg mb-4 text-center text-lg tracking-widest"
        placeholder="Enter OTP"
      />

      <button
        @click="verifyOtp"
        :disabled="loading"
        class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700"
      >
        {{ loading ? 'Verifying...' : 'Verify OTP' }}
      </button>

      <p v-if="error" class="text-red-600 text-sm mt-3 text-center">{{ error }}</p>

      <div class="mt-4 text-center">
        <button @click="resendOtp" class="text-blue-600 hover:underline">Resend OTP</button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import axios from 'axios'

const route = useRoute()
const router = useRouter()

const email = route.query.email
const code = ref('')
const loading = ref(false)
const error = ref(null)

const verifyOtp = async () => {
  loading.value = true
  error.value = null

  try {
    await axios.post('http://127.0.0.1:8000/api/verify-otp', {
      email: email,
      code: code.value,
    })

    // Berhasil — redirect login
    router.push('/login')
  } catch (err) {
    error.value = err.response?.data?.message || 'Verification failed.'
  } finally {
    loading.value = false
  }
}

const resendOtp = async () => {
  error.value = null

  try {
    await axios.post('http://127.0.0.1:8000/api/resend-otp', {
      email: email,
    })

    alert('OTP has been resent to your email.')
  } catch (err) {
    error.value = err.response?.data?.message || 'Failed to resend OTP.'
  }
}
</script>
