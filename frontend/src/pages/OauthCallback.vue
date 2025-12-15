<script setup>
import { useRoute, useRouter } from 'vue-router'

const route = useRoute()
const router = useRouter()

const token = route.query.token

if (token) {
  // Simpan token
  localStorage.setItem('token', token)
  localStorage.setItem('isLoggedIn', 'true')

  // Redirect ke dashboard
  router.push('/')
} else {
  router.push('/login')
}
</script>

<template>
  <div class="flex justify-center items-center h-screen text-xl">Logging in...</div>
</template>
