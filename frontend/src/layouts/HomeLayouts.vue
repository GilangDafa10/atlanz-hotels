<template>
  <div>
    <HomeNavbar />

    <main>
      <slot />
    </main>

    <Footer />
  </div>
</template>

<script setup>
import HomeNavbar from '../components/HomeNavbar.vue'
import Footer from '../components/Footer.vue'
</script>
