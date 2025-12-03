<template>
  <div
    class="group bg-white rounded-2xl shadow-sm hover:shadow-xl border border-gray-100 overflow-hidden transition-all duration-300 flex flex-col h-full"
  >
    <div class="relative h-56 overflow-hidden bg-gray-100">
      <img
        :src="imageUrl"
        :alt="room.name"
        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
        @error="useFallbackImage"
      />

      <div
        class="absolute top-3 right-3 bg-white/90 backdrop-blur px-3 py-1 rounded-full shadow-sm text-sm font-bold text-blue-600"
      >
        {{ room.price }}
      </div>
    </div>

    <div class="p-5 flex flex-col flex-grow">
      <h3 class="text-xl font-bold text-gray-800 mb-2 group-hover:text-blue-600 transition-colors">
        {{ room.name }}
      </h3>

      <p class="text-gray-500 text-sm mb-4 line-clamp-3 flex-grow">
        {{ room.description || 'Tidak ada deskripsi tersedia.' }}
      </p>

      <div class="pt-4 border-t border-gray-100 flex gap-2 mt-auto">
        <button
          @click="$emit('edit', room)"
          class="flex-1 flex items-center justify-center gap-2 px-3 py-2 text-sm font-medium text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition"
        >
          Edit
        </button>

        <button
          @click="$emit('delete', room.id)"
          class="flex-1 flex items-center justify-center gap-2 px-3 py-2 text-sm font-medium text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition"
        >
          Hapus
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  room: { type: Object, required: true },
})

// LOGIKA UTAMA DISINI
const imageUrl = computed(() => {
  // Jika ada url_gambar, gabungkan dengan domain
  if (props.room.url_gambar) {
    return `http://127.0.0.1:8000/storage/${props.room.url_gambar}`
  }
  // Jika null/kosong, pakai placeholder
  return 'https://via.placeholder.com/400x250?text=No+Image'
})

const useFallbackImage = (e) => {
  e.target.src = 'https://via.placeholder.com/400x250?text=No+Image'
}
</script>

<style scoped>
.line-clamp-3 {
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>
