<template>
  <div class="bg-gray-100 min-h-screen flex flex-col items-center py-10">
    <h2 class="text-3xl font-bold mb-10 text-gray-800">Additional Services</h2>

    <div
      v-for="(service, index) in services"
      :key="index"
      class="flex items-center justify-between bg-[#0c2250] text-white w-[950px] h-[220px] rounded-2xl overflow-hidden mb-10 shadow-lg px-4"
    >
      <!-- Gambar -->
      <div class="w-[320px] h-[180px] overflow-hidden rounded-xl ml-2">
        <img
          :src="service.image"
          :alt="service.name"
          class="w-full h-full object-cover rounded-xl"
        />
      </div>

      <!-- Teks dan Tombol -->
      <div class="flex-1 px-10 flex justify-between items-center">
        <div>
          <h3 class="text-3xl italic font-semibold text-[#f6e05e] tracking-wide">
            {{ service.name }}
          </h3>
          <p class="text-2xl font-bold mt-2">{{ service.price }}</p>
        </div>

        <button
          class="bg-[#b7a34b] hover:bg-[#a2923f] text-black font-semibold px-8 py-2 rounded-md transition-transform hover:scale-105"
        >
          Pilih
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
const services = [
  {
    name: "YOGA",
    image: new URL('@/assets/Yoga.png', import.meta.url).href,
    price: "Rp. 180.000",
  },
  {
    name: "SNORKLING",
    image: new URL('@/assets/Snorkling.png', import.meta.url).href,
    price: "Rp. 9.000",
  },
  {
    name: "SPA",
    image: new URL('@/assets/Spa.png', import.meta.url).href,
    price: "Rp. 899.000",
  },
];
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap');
* {
  font-family: 'Poppins', sans-serif;
}
</style>
