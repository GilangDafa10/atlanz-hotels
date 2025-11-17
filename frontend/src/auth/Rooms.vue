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
        Each of our bright, light-flooded rooms come with everything you could possibly need for a
        comfortable stay. And yes, comfort isn't our only objective, we also value good design,
        sleek contemporary furnishing complemented by the rich tones of nature's palette as visible
        from our rooms' sea-view windows and terraces.
      </p>
    </div>
  </section>

  <section class="bg-[#0b1f4f] py-16 flex flex-col md:flex-row items-center justify-center gap-8">
    <div class="w-full md:w-1/2">
      <img src="@/assets/image 8.png" alt="Ocean View Room" class="shadow-lg w-full object-cover" />
    </div>

    <div class="w-full md:w-1/2 text-white text-left px-6 md:px-12">
      <h2 class="text-xl md:text-3xl font-semibold mb-3">Ocean View Room</h2>
      <p class="text-sm md:text-base mb-4 leading-relaxed text-gray-200">
        Each of our bright, light-flooded rooms come with everything you could possibly need for a
        comfortable stay.
      </p>
      <ul class="list-disc pl-5 space-y-1 text-gray-300">
        <li>Ocean View</li>
        <li>25 - 35 m² room</li>
        <li>Reguler bed</li>
      </ul>
      <button
        class="mt-6 bg-[#E3C681] text-[#1E2A3A] font-semibold px-6 py-2 rounded-md hover:bg-[#d4b65f] transition"
      >
        BOOK NOW
      </button>
    </div>
  </section>

  <section
    class="bg-white py-16 flex flex-col md:flex-row-reverse items-center justify-center gap-8"
  >
    <div class="w-full md:w-1/2">
      <img src="@/assets/image 9.png" alt="Beach View Room" class="shadow-lg w-full object-cover" />
    </div>

    <div class="w-full md:w-1/2 text-[#1E2A3A] text-left px-6 md:px-12">
      <h2 class="text-xl md:text-3xl font-semibold mb-3">Beach View Room</h2>
      <p class="text-sm md:text-base mb-4 leading-relaxed">
        Each of our bright, light-flooded rooms come with everything you could possibly need for a
        comfortable stay.
      </p>
      <ul class="list-disc pl-5 space-y-1">
        <li>Ocean View</li>
        <li>25 - 35 m² room</li>
        <li>Twin bed</li>
      </ul>
      <button
        class="mt-6 bg-[#E3C681] text-[#1E2A3A] font-semibold px-6 py-2 rounded-md hover:bg-[#d4b65f] transition"
      >
        BOOK NOW
      </button>
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
      <h2 class="text-xl md:text-3xl font-semibold mb-3">Deluxe Ocean View Room</h2>
      <p class="text-sm md:text-base mb-4 leading-relaxed text-gray-200">
        Each of our bright, light-flooded rooms come with everything you could possibly need for a
        comfortable stay.
      </p>
      <ul class="list-disc pl-5 space-y-1 text-gray-300">
        <li>Ocean View</li>
        <li>40 - 55 m² room </li>
        <li>King Size bed</li>
      </ul>
      <button
        class="mt-6 bg-[#E3C681] text-[#1E2A3A] font-semibold px-6 py-2 rounded-md hover:bg-[#d4b65f] transition"
      >
        BOOK NOW
      </button>
    </div>
  </section>

  <section
    class="bg-white py-16 flex flex-col md:flex-row-reverse items-center justify-center gap-8"
  >
    <div class="w-full md:w-1/2">
      <img
        src="@/assets/image 11.png"
        alt="Beach View Room"
        class="shadow-lg w-full object-cover"
      />
    </div>

    <div class="w-full md:w-1/2 text-[#1E2A3A] text-left px-6 md:px-12">
      <h2 class="text-xl md:text-3xl font-semibold mb-3">Superior Ocean View Room</h2>
      <p class="text-sm md:text-base mb-4 leading-relaxed">
        Each of our bright, light-flooded rooms come with everything you could possibly need for a
        comfortable stay.
      </p>
      <ul class="list-disc pl-5 space-y-1">
        <li>Ocean View</li>
        <li>60 - 70 m² room</li>
        <li>King Size</li>
        <li>Living Room</li>
      </ul>
      <button
        class="mt-6 bg-[#E3C681] text-[#1E2A3A] font-semibold px-6 py-2 rounded-md hover:bg-[#d4b65f] transition"
      >
        BOOK NOW
      </button>
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
