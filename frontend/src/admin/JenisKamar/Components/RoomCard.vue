<template>
  <div class="bg-white rounded-xl shadow-md overflow-hidden">
    <!-- Atur container gambar dengan height tetap -->
    <div class="h-48">
  <img
    :src="room.image"
    :alt="room.name"
    class="w-full h-full object-cover object-center"
    @error="useFallbackImage"
  />
</div>
    <div class="p-4">
      <h3 class="font-bold text-lg mb-2">{{ room.name }}</h3>
      <p class="text-gray-600 text-sm mb-4">{{ room.description }}</p>
      <p class="text-blue-600 font-semibold mb-4">{{ room.price }}</p>
      <div class="flex gap-2">
        <button
          @click="$emit('edit', room)"
          class="flex items-center gap-1 text-blue-600 hover:text-blue-800 text-sm"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
          </svg>
          Edit
        </button>
        <button
          @click="$emit('delete', room.id)"
          class="flex items-center gap-1 text-red-600 hover:text-red-800 text-sm"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A1 1 0 0117.133 21H6.867A1 1 0 016 19.867L4.867 7.867A1 1 0 016 6h12a1 1 0 011 1.133L19 7z" />
          </svg>
          Hapus
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { defineProps, defineEmits } from 'vue'

const props = defineProps({
  room: {
    type: Object,
    required: true
  }
})

const emit = defineEmits(['edit', 'delete'])

// Fallback jika gambar error
const useFallbackImage = (event) => {
  event.target.src = 'https://via.placeholder.com/300x200?text=No+Image'
}
</script>