<template>
  <nav
    class="fixed top-0 left-0 w-full z-50 transition-all duration-300 bg-[#0b1f4f] flex justify-between items-center px-20 py-5 font-[Poppins]"
  >
    <!-- LOGO -->
    <div class="flex items-center gap-2.5">
      <img src="@/assets/Logo Atlanz white.png" alt="Atlanz Logo" class="w-[170px] h-auto" />
    </div>

    <!-- NAV LINKS -->
    <ul class="flex gap-[35px] m-0 p-0 list-none">
      <li>
        <a
          href="/"
          class="text-white font-medium text-[16px] hover:underline underline-offset-[6px] transition"
        >
          Home
        </a>
      </li>
      <li>
        <a
          href="/fasilitas"
          class="text-white font-medium text-[16px] hover:underline underline-offset-[6px] transition"
        >
          Facilities
        </a>
      </li>
      <li>
        <a
          href="/rooms"
          class="text-white font-medium text-[16px] hover:underline underline-offset-[6px] transition"
        >
          Rooms
        </a>
      </li>
      <li>
        <a
          href="#"
          class="text-white font-medium text-[16px] hover:underline underline-offset-[6px] transition"
        >
          Contact-us
        </a>
      </li>
      <li>
        <a
          href="#"
          class="text-white font-medium text-[16px] hover:underline underline-offset-[6px] transition"
        >
          Book
        </a>
      </li>
    </ul>
  </nav>
</template>

<script setup></script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap');
</style>
