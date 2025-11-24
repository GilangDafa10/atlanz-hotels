<template>
  <div v-if="isOpen" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 transition-opacity duration-300" :class="{ 'opacity-0': !isOpen, 'opacity-100': isOpen }">
    <div class="bg-white rounded-lg shadow-xl max-w-lg w-full p-6 transition-transform duration-300" :class="{ 'scale-95': !isOpen, 'scale-100': isOpen }">
      <!-- Header -->
      <div class="flex justify-between items-center mb-4">
        <h3 class="text-xl font-bold">Edit Jenis Kamar</h3>
        <button @click="close" class="text-gray-500 hover:text-gray-700 text-xl">&times;</button>
      </div>

      <!-- Body -->
      <form @submit.prevent="submit" class="space-y-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Nama Jenis Kamar *</label>
          <input
            v-model="form.name"
            type="text"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            required
          />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Harga *</label>
          <input
            v-model="form.price"
            type="text"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            required
          />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi *</label>
          <textarea
            v-model="form.description"
            rows="3"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            required
          ></textarea>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">URL Gambar</label>
          <input
            v-model="form.image"
            type="text"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            placeholder="https://..."
          />
        </div>

        <!-- Footer -->
        <div class="flex justify-end gap-3 pt-4">
          <button
            type="button"
            @click="close"
            class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition"
          >
            Batal
          </button>
          <button
            type="submit"
            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition"
          >
            Simpan Perubahan
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue'

const props = defineProps({
  isOpen: {
    type: Boolean,
    default: false
  },
  room: {
    type: Object,
    default: null
  }
})

const emit = defineEmits(['close', 'update'])

const form = ref({
  name: '',
  price: '',
  description: '',
  image: ''
})

// Isi form dengan data room saat modal dibuka
watch(() => props.room, (newRoom) => {
  if (newRoom) {
    form.value = { ...newRoom }
  }
}, { immediate: true })

const submit = () => {
  emit('update', { id: props.room.id, ...form.value })
  close()
}

const close = () => {
  emit('close')
}
</script>