<template>
  <section class="bg-[#0c2c67] py-10"> 
    <div class="bg-white text-black rounded-2xl shadow-xl py-5 px-8 mx-auto w-[90%] max-w-6xl">
      <h2 class="text-2xl font-semibold text-center text-[#0c2c67] mb-5">Kamar</h2>

      <div class="grid md:grid-cols-3 gap-10">
        <div
          v-for="(room, index) in displayedRooms"
          :key="index"
          class="bg-white border border-gray-200 rounded-xl shadow-md overflow-hidden hover:shadow-2xl transition-all duration-300 hover:scale-[1.03]"
        >
          <div class="relative">
            <img
              :src="room.image"
              :alt="room.name"
              class="w-full h-[220px] object-cover"
            />

            <div class="absolute bottom-0 w-full bg-[#dfe6f1] text-center py-3">
              <h3 class="text-lg font-semibold text-[#0c2c67]">{{ room.name }}</h3>
            </div>
          </div>

          <div class="p-5 flex flex-col items-center">
            <p class="text-xl font-bold text-gray-700 mb-3">{{ room.price }}</p>
            <button
              class="bg-[#b7a34b] hover:bg-[#9c8b3f] text-white px-5 py-2 rounded-md text-sm font-medium transition-transform hover:scale-105"
            >
              BOOK NOW
            </button>
          </div>
        </div>
      </div>

      <div class="flex justify-center mt-12">
        <button
          @click="showAll = !showAll"
          class="bg-gray-600 hover:bg-gray-700 text-white px-8 py-2 rounded-full text-sm font-medium transition-transform hover:scale-105"
        >
          {{ showAll ? 'Show Less' : 'Show All' }}
        </button>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, computed } from 'vue';

const showAll = ref(false);

const rooms = [
  {
    name: "Twin Bed",
    image: new URL("@/assets/book.png", import.meta.url).href,
    price: "Rp. 999.999",
  },
  {
    name: "Double Bed",
    image: new URL("@/assets/book.png", import.meta.url).href,
    price: "Rp. 1.299.999",
  },
  {
    name: "King Bed",
    image: new URL("@/assets/book.png", import.meta.url).href,
    price: "Rp. 1.799.999",
  },
  {
    name: "Single Bed",
    image: new URL("@/assets/book.png", import.meta.url).href,
    price: "Rp. 899.999",
  },
  {
    name: "Family Bed",
    image: new URL("@/assets/book.png", import.meta.url).href,
    price: "Rp. 2.199.999",
  },
  {
    name: "Luxury Bed",
    image: new URL("@/assets/book.png", import.meta.url).href,
    price: "Rp. 2.799.999",
  },
];

const displayedRooms = computed(() => (showAll.value ? rooms : rooms.slice(0, 3)));
</script>

<style scoped>
@import url("https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap");
* {
  font-family: "Poppins", sans-serif;
}
</style>
