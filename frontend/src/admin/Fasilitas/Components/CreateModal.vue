<template>
  <Teleport to="body">
    <div v-if="isOpen" class="fixed inset-0 bg-black/50 flex items-center justify-center z-[9999] transition-opacity duration-300" @click.self="close">
      <div class="bg-white rounded-lg shadow-xl max-w-lg w-full p-6 transition-transform duration-300">
        <div class="flex justify-between items-center mb-4 border-b pb-3">
          <h3 class="text-xl font-bold">Tambah Fasilitas Baru</h3>
          <button @click="close" class="text-gray-500 hover:text-gray-700 text-xl">&times;</button>
        </div>

        <form @submit.prevent="submit" class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Fasilitas *</label>
            <input
              v-model="form.nama_fasilitas"
              type="text"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              required
              :disabled="isLoading"
            />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Icon Fasilitas (Class CSS)</label>
            <input
              v-model="form.icon_fasilitas"
              type="text"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              placeholder="Contoh: fa-solid fa-wifi"
              :disabled="isLoading"
            />
          </div>

          <div class="flex justify-end gap-3 pt-4 border-t">
            <button
              type="button"
              @click="close"
              class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition"
              :disabled="isLoading"
            >
              Batal
            </button>
            <button
              type="submit"
              class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition disabled:opacity-50 flex items-center gap-2"
              :disabled="isLoading"
            >
              Tambah
            </button>
          </div>
        </form>
      </div>
    </div>
  </Teleport>
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
  nama_fasilitas: '',
  icon_fasilitas: '' // 💡 Inisialisasi field baru
})

const isLoading = ref(false) 

const submit = () => {
  isLoading.value = true
  // Emit form.value, yang kini mencakup nama_fasilitas dan icon_fasilitas
  emit('create', form.value) 
  
  // Reset form setelah kirim
  form.value.nama_fasilitas = ''
  form.value.icon_fasilitas = '' // Reset field ikon juga
  isLoading.value = false 
}

const close = () => {
  // Reset form saat menutup
  form.value.nama_fasilitas = ''
  form.value.icon_fasilitas = ''
  emit('close')
}
</script>