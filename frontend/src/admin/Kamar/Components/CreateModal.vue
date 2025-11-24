<template>
  <div v-if="isOpen" class="fixed inset-0 bg-black/50  flex items-center justify-center z-50 transition-opacity duration-300" :class="{ 'opacity-0': !isOpen, 'opacity-100': isOpen }">
    <div class="bg-white rounded-lg shadow-xl max-w-lg w-full p-6 transition-transform duration-300" :class="{ 'scale-95': !isOpen, 'scale-100': isOpen }">
      <!-- Header -->
      <div class="flex justify-between items-center mb-4">
        <h3 class="text-xl font-bold">Tambah Kamar</h3>
        <button @click="close" class="text-gray-500 hover:text-gray-700 text-xl">&times;</button>
      </div>

      <!-- Body -->
      <form @submit.prevent="submit" class="space-y-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Kamar *</label>
          <input
            v-model="form.number"
            type="text"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            required
          />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Tipe Kamar *</label>
          <select
            v-model="form.typeId"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            required
          >
            <option value="">Pilih tipe kamar</option>
            <option value="Twin Bed">Twin Bed</option>
            <option value="Double Bed">Double Bed</option>
            <option value="King Bed">King Bed</option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Status *</label>
          <select
            v-model="form.status"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            required
          >
            <option value="tersedia">Tersedia</option>
            <option value="dipesan">Dipesan</option>
            <option value="dibersihkan">Dibersihkan</option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Lantai *</label>
          <input
            v-model="form.floor"
            type="number"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            required
          />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Fasilitas</label>
          <input
            v-model="form.facilities"
            type="text"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            placeholder="AC, Wi-Fi, TV, dll (pisahkan dengan koma)"
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
            + Tambah Kamar
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const props = defineProps({
  isOpen: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['close', 'create'])

const form = ref({
  number: '',
  typeId: '',
  status: 'tersedia',
  floor: 1,
  facilities: ''
})

const submit = () => {
  // Konversi facilities dari string ke array
  const facilitiesArray = form.value.facilities ? form.value.facilities.split(',').map(f => f.trim()) : []
  emit('create', { ...form.value, facilities: facilitiesArray })
  close()
}

const close = () => {
  emit('close')
}
</script>