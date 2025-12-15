<template>
  <nav class="text-white py-4 px-8 flex justify-between items-center">
    <div class="flex items-center gap-2"></div>
  </nav>

  <section class="flex flex-col justify-center items-center text-center py-20 px-6">
    <div class="max-w-3xl mx-auto">
      <h1 class="text-3xl md:text-4xl font-bold text-[#1E2A3A] tracking-wide mb-4">
        ROOM & SUITES
      </h1>
      <p class="text-sm md:text-base leading-relaxed text-[#1E2A3A] text-center">
        Setiap kamar kami dirancang dengan atmosfer terang dan modern, menghadirkan kenyamanan
        optimal selama Anda menginap. Tidak hanya fokus pada fungsi, kami juga menghadirkan sentuhan
        estetika melalui desain interior yang elegan, perabotan kontemporer, serta material
        bernuansa alam yang memberikan rasa hangat dan harmonis.
      </p>
    </div>
  </section>

  <section class="bg-[#0b1f4f] py-16 flex flex-col md:flex-row items-center justify-center gap-8">
    <div class="w-full md:w-1/2">
      <img src="@/assets/image 8.png" alt="Ocean View Room" class="shadow-lg w-full object-cover" />
    </div>

    <div class="w-full md:w-1/2 text-white text-left px-6 md:px-12">
      <h2 class="text-xl md:text-3xl font-semibold mb-3">Queen Bed</h2>
      <p class="text-sm md:text-base mb-4 leading-relaxed text-gray-200">
        Kamar dengan tempat tidur ukuran Queen, cocok untuk pasangan atau tamu yang menginginkan
        ruang tidur nyaman dengan ukuran kasur lebih besar. Didesain dengan gaya modern, pencahayaan
        hangat, dan suasana yang nyaman untuk beristirahat setelah aktivitas seharian.
      </p>
      <ul class="list-disc pl-5 space-y-1 text-gray-300">
        <li>Ocean View</li>
        <li>Rp. 350.000 / Malam</li>
        <li>Queen bed</li>
      </ul>
      <!-- <button
        class="mt-6 bg-[#E3C681] text-[#1E2A3A] font-semibold px-6 py-2 rounded-md hover:bg-[#d4b65f] transition"
      >
        BOOK NOW
      </button> -->
    </div>
  </section>

  <section
    class="bg-white py-16 flex flex-col md:flex-row-reverse items-center justify-center gap-8"
  >
    <div class="w-full md:w-1/2">
      <img src="@/assets/image 9.png" alt="Beach View Room" class="shadow-lg w-full object-cover" />
    </div>

    <div class="w-full md:w-1/2 text-[#1E2A3A] text-left px-6 md:px-12">
      <h2 class="text-xl md:text-3xl font-semibold mb-3">Twin Bed</h2>
      <p class="text-sm md:text-base mb-4 leading-relaxed">
        Kamar dengan dua tempat tidur single, cocok untuk teman perjalanan, partner kerja, atau
        keluarga yang menginginkan tempat tidur terpisah. Kamar ini menawarkan kenyamanan dengan
        desain minimalis modern dan fasilitas lengkap.
      </p>
      <ul class="list-disc pl-5 space-y-1">
        <li>Ocean View</li>
        <li>Rp. 300.000 / Malam</li>
        <li>Twin bed</li>
      </ul>
      <!-- <button
        class="mt-6 bg-[#E3C681] text-[#1E2A3A] font-semibold px-6 py-2 rounded-md hover:bg-[#d4b65f] transition"
      >
        BOOK NOW
      </button> -->
    </div>
  </section>

  <section class="bg-[#0b1f4f] py-16 flex flex-col md:flex-row items-center justify-center gap-8">
    <div class="w-full md:w-1/2">
      <img
        src="@/assets/image 10.png"
        alt="Ocean View Room"
        class="shadow-lg w-full object-cover"
      />
    </div>

    <div class="w-full md:w-1/2 text-white text-left text-left px-6 md:px-12">
      <h2 class="text-xl md:text-3xl font-semibold mb-3">Double Bed</h2>
      <p class="text-sm md:text-base mb-4 leading-relaxed text-gray-200">
        Kamar dengan tempat tidur double yang menawarkan kenyamanan bagi satu atau dua tamu. Cocok
        untuk pelancong solo, business traveler, atau pasangan dengan budget hemat tetapi tetap
        menginginkan kualitas kamar yang nyaman.
      </p>
      <ul class="list-disc pl-5 space-y-1 text-gray-300">
        <li>Ocean View</li>
        <li>Rp. 250.000 / Malam</li>
        <li>Double bed</li>
      </ul>
      <!-- <button
        class="mt-6 bg-[#E3C681] text-[#1E2A3A] font-semibold px-6 py-2 rounded-md hover:bg-[#d4b65f] transition"
      >
        BOOK NOW
      </button> -->
    </div>
  </section>
</template>

<script setup></script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap');
* {
  font-family: 'Poppins', sans-serif;
}
</style>
