<template>
  <nav
    :class="[
      'fixed top-0 left-0 w-full z-50 transition-all duration-300',
      isScrolled ? 'bg-white shadow-lg' : 'bg-transparent',
    ]"
  >
    <div class="max-w-7xl mx-auto px-20 py-5 flex justify-between items-center">
      <!-- Logo -->
      <div class="flex items-center gap-3">
        <img
          src="@/assets/Logo Atlanz white.png"
          class="w-40 transition-all duration-300 cursor-pointer"
          :class="isScrolled ? 'brightness-0' : ''"
          alt="Atlanz Logo"
          onclick="location.reload()"
        />
      </div>

      <!-- Navigation Links -->
      <ul class="flex gap-10">
        <li>
          <a
            :class="linkClass"
            class="text-lg hover:underline-offset-6 hover:underline transition"
            href="/"
            >Home</a
          >
        </li>
        <li>
          <a
            :class="linkClass"
            class="text-lg hover:underline-offset-6 hover:underline transition"
            href="/fasilitas"
            >Facilities</a
          >
        </li>
        <li>
          <a
            :class="linkClass"
            class="text-lg hover:underline-offset-6 hover:underline transition"
            href="/rooms"
            >Rooms</a
          >
        </li>
        <li>
          <a
            :class="linkClass"
            class="text-lg hover:underline-offset-6 hover:underline transition"
            href="#"
            >Contact-us</a
          >
        </li>
        <li>
          <a
            :class="linkClass"
            class="text-lg hover:underline-offset-6 hover:underline transition"
            href="#"
          >
            Book
          </a>
        </li>
      </ul>
    </div>
  </nav>
</template>

<script setup>
import { ref, onMounted, onUnmounted, computed } from 'vue'

const isScrolled = ref(false)

const handleScroll = () => {
  isScrolled.value = window.scrollY > 50
}

onMounted(() => window.addEventListener('scroll', handleScroll))
onUnmounted(() => window.removeEventListener('scroll', handleScroll))

const linkClass = computed(() =>
  isScrolled.value
    ? 'text-gray-800 font-medium hover:text-[#0b1f4f] transition'
    : 'text-white font-medium hover:opacity-80 transition',
)
</script>
