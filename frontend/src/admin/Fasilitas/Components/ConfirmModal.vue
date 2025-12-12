<template>
  <div 
    v-if="isOpen" 
    class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 transition-opacity duration-300 backdrop-blur-sm" 
    :class="{ 'opacity-0': !isOpen, 'opacity-100': isOpen }"
>
    <div class="bg-white rounded-lg shadow-xl max-w-lg w-full p-6 transition-transform duration-300" :class="{ 'scale-95': !isOpen, 'scale-100': isOpen }">
      <!-- Header -->
      <div class="flex justify-between items-center mb-4">
        <h3 class="text-xl font-bold">Konfirmasi Hapus</h3>
        <button @click="cancel" class="text-gray-500 hover:text-gray-700 text-xl">&times;</button>
      </div>

      <!-- Body -->
      <p class="text-gray-600 mb-6">{{ message }}</p>

      <!-- Footer -->
      <div class="flex justify-end gap-3">
        <button
          type="button"
          @click="cancel"
          class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition"
        >
          Batal
        </button>
        <button
          type="button"
          @click="confirm"
          class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition"
        >
          Hapus
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { defineProps, defineEmits } from 'vue'

const props = defineProps({
  isOpen: {
    type: Boolean,
    default: false
  },
  title: {
    type: String,
    default: 'Konfirmasi'
  },
  message: {
    type: String,
    default: 'Apakah kamu yakin ingin menghapus item ini? Aksi ini tidak bisa dibatalkan.'
  }
})

const emit = defineEmits(['confirm', 'cancel'])

const confirm = () => {
  emit('confirm')
}

const cancel = () => {
  emit('cancel')
}
</script>