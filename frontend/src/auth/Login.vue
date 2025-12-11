<template>
  <div
    class="fixed inset-0 bg-cover bg-center"
    style="background-image: url('/src/assets/gambar hotel.jpg')"
  >
    <div class="absolute inset-0 bg-black/50 flex items-center justify-center">
      <div class="backdrop-blur-md bg-white/50 shadow-xl p-8 rounded-2xl w-80 md:w-96 text-center">
        <h2 class="text-3xl font-semibold text-black mb-6">Sign in to ATLANZ</h2>

        <form @submit.prevent="login" class="space-y-4">

          <!-- EMAIL -->
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

          <!-- PASSWORD -->
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
                <!-- ikon -->
                <svg v-if="showPassword" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                <svg v-else xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 3l18 18M10.58 10.58A3 3 0 0113.42 13.42M6.26 6.26A9.77 9.77 0 003 12c1.274 4.057 5.064 7 9.542 7a9.77 9.77 0 005.18-1.54M17.74 17.74A9.77 9.77 0 0021 12c-1.274-4.057-5.064-7-9.542-7a9.77 9.77 0 00-5.18 1.54" />
                </svg>
              </button>
            </div>
          </div>

<<<<<<< Updated upstream
          <!-- Button -->
          <button
            :disabled="loading"
            class="w-full mt-1 bg-blue-600 text-white py-2 rounded-lg text-sm font-semibold hover:bg-blue-700 active:scale-95 transition"
          >
            {{ loading ? 'Loading...' : 'Login' }}
          </button>
=======
          <!-- 🟦 RECAPTCHA BOX -->
          <div class="mt-2 flex justify-center">
            <div ref="recaptchaBox" class="g-recaptcha"></div>
          </div>

          <!-- BUTTON LOGIN -->
          <button
            :disabled="loading"
            class="w-full mt-1 bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 active:scale-95 transition"
          >
            {{ loading ? 'Loading...' : 'Login' }}
          </button>

          <!-- GOOGLE -->
          <button
            type="button"
            @click="loginWithGoogle"
            class="w-full mt-2 bg-red-600 text-white py-2 rounded-lg hover:bg-red-700 transition"
          >
            <i class="fa-brands fa-google mr-1"></i>
            Login with Google
          </button>

          <!-- GITHUB -->
          <button
            type="button"
            @click="loginWithGithub"
            class="w-full mt-2 bg-gray-900 text-white py-2 rounded-lg hover:bg-black transition"
          >
            <i class="fa-brands fa-github mr-1"></i>
            Login with GitHub
          </button>

>>>>>>> Stashed changes
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

  <SuccessLoginModal v-model="showSuccessModal" />
</template>

<script setup>
import { ref } from 'vue'
import axios from 'axios'
<<<<<<< Updated upstream
=======
import api from '@/utils/api'
>>>>>>> Stashed changes
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

/* ---------------------------
  RECAPTCHA
----------------------------*/
const siteKey = import.meta.env.VITE_CAPTCHA_KEY || ''
const recaptchaToken = ref(null)
const recaptchaBox = ref(null)
let grecaptchaWidgetId = null

// safe loader: jika grecaptcha belum ada, coba lagi sampai 2 detik
const loadRecaptcha = () => {
  const tryRender = () => {
    if (window.grecaptcha && typeof window.grecaptcha.render === 'function') {
      // render sekali
      grecaptchaWidgetId = window.grecaptcha.render(recaptchaBox.value, {
        sitekey: siteKey,
        callback: (token) => {
          recaptchaToken.value = token
        },
        'expired-callback': () => {
          recaptchaToken.value = null
        }
      })
      return true
    }
    return false
  }

  if (!tryRender()) {
    const interval = setInterval(() => {
      if (tryRender()) {
        clearInterval(interval)
      }
    }, 300)
    // auto-stop after 3s
    setTimeout(() => clearInterval(interval), 3000)
  }
}

onMounted(() => {
  loadRecaptcha()
})

/* ---------------------------
  LOGIN (kirim recaptcha_token)
----------------------------*/
const login = async () => {
  loading.value = true
  errorMessage.value = null

  // pastikan token tersedia
  if (!recaptchaToken.value) {
    errorMessage.value = 'Please verify reCAPTCHA!'
    loading.value = false
    return
  }

  try {
    const payload = {
      email: username.value,
      password: password.value,
      recaptcha_token: recaptchaToken.value, // KIRIM dengan nama 'recaptcha_token'
    }

    const res = await axios.post('http://127.0.0.1:8000/api/login', payload)

    localStorage.setItem('token', res.data.data.token)
    localStorage.setItem('isLoggedIn', 'true')

<<<<<<< Updated upstream
    // 1️⃣ Tampilkan modal sukses login
    showSuccessModal.value = true

    // 2️⃣ Setelah 3 detik → redirect ke /
    setTimeout(() => {
      router.push('/')
    }, 3000)
=======
    // ambil role
    const me = await api.get('/me')
    const role = me.data.data.id_role
    localStorage.setItem('role', role)

    showSuccessModal.value = true

    // bersihkan captcha agar user bisa login lain kali (opsional)
    try {
      if (window.grecaptcha && grecaptchaWidgetId !== null) {
        window.grecaptcha.reset(grecaptchaWidgetId)
        recaptchaToken.value = null
        // render ulang widget (optional, but some browsers need)
        loadRecaptcha()
      }
    } catch (e) {
      // ignore
    }

    setTimeout(() => {
      router.push(role == 1 ? '/admin/dashboard' : '/')
    }, 1000)
>>>>>>> Stashed changes
  } catch (err) {
    // tampilkan pesan error server jika ada
    errorMessage.value = err.response?.data?.message || err.message || 'Login gagal!'
  } finally {
    loading.value = false
  }
}
<<<<<<< Updated upstream
=======

/* ---------------------------
  Social logins
----------------------------*/
const loginWithGoogle = async () => {
  const res = await axios.get('http://127.0.0.1:8000/api/auth/google/redirect')
  window.location.href = res.data.url
}

const loginWithGithub = async () => {
  const res = await axios.get('http://127.0.0.1:8000/api/auth/github/redirect')
  window.location.href = res.data.url
}

/* ---------------------------
  handling social login callback token in route
----------------------------*/
onMounted(async () => {
  const token = router.currentRoute.value.query.token

  if (token) {
    localStorage.setItem('token', token)
    localStorage.setItem('isLoggedIn', 'true')
    localStorage.setItem('lastActivity', Date.now())

    const me = await api.get('/me')
    const role = me.data.data.id_role
    localStorage.setItem('role', role)

    router.replace(role == 1 ? '/dashboard' : '/')
  }
})
>>>>>>> Stashed changes
</script>
