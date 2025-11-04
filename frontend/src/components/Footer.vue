<template>
  <footer class="bg-[#0b1f4f] text-white py-6 w-full">

    <div class="flex flex-col md:flex-row items-center justify-between border-b-2 border-gray-400 pb-4 px-8 w-full">

      <!-- Logo -->
      <div class="flex flex-col items-center md:items-start">
        <h2 class="text-4xl font-bold tracking-wide">ATLANZ</h2>
        <span class="block text-sm text-gray-300 tracking-[5px]">HOTELS</span>
      </div>

      <!-- Navigation -->
      <ul
        class="flex flex-wrap justify-center gap-6 text-sm font-medium my-4 md:my-0"
      >
        <li><a href="#" class="hover:underline underline-offset-4">Home</a></li>
        <li><a href="#" class="hover:underline underline-offset-4">Book</a></li>
        <li><a href="#" class="hover:underline underline-offset-4">Rooms</a></li>
        <li><a href="#" class="hover:underline underline-offset-4">Facilities</a></li>
        <li><a href="#" class="hover:underline underline-offset-4">Contact-us</a></li>
      </ul>

      <!-- Social Icons -->
      <div class="flex gap-4 text-lg">
        <font-awesome-icon :icon="['fab', 'facebook-f']" class="hover:text-[#c8a349]" />
        <font-awesome-icon :icon="['fab', 'twitter']" class="hover:text-[#c8a349]" />
        <font-awesome-icon :icon="['fab', 'instagram']" class="hover:text-[#c8a349]" />
      </div>
    </div>

    <!-- Bagian Bawah -->
    <div class="text-center text-gray-400 text-xs mt-3">
      © 2025 Atlanz Hotel. All Right Reserved
    </div>
  </footer>
</template>

<script setup>
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { library } from "@fortawesome/fontawesome-svg-core";
import { faFacebookF, faTwitter, faInstagram } from "@fortawesome/free-brands-svg-icons";

library.add(faFacebookF, faTwitter, faInstagram);
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap');

* {
  font-family: 'Poppins', sans-serif;
}
</style>
