<template>
  <Teleport to="body">
    <div v-if="isOpen" class="fixed inset-0 bg-black/50 flex items-center justify-center z-[9999] transition-opacity duration-300" @click.self="close">
      <div class="bg-white rounded-lg shadow-xl max-w-lg w-full p-6 transition-transform duration-300">
        <div class="flex justify-between items-center mb-4 border-b pb-3">
          <h3 class="text-xl font-bold">Edit Fasilitas: {{ form.nama_fasilitas }}</h3>
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
              placeholder="Masukkan nama fasilitas..."
            />
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Icon Fasilitas (Class CSS)</label>
            <input
              v-model="form.icon_fasilitas"
              type="text"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              placeholder="Contoh: fa-solid fa-wifi"
            />
          </div>

          <div class="flex justify-end gap-3 pt-4 border-t">
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
  </Teleport>
</template>

<script setup>
import { ref, watch } from 'vue'

const props = defineProps({
  isOpen: {
    type: Boolean,
    default: false
  },
  // Menerima objek fasilitas saat ini dari index.vue
  fasilitas: { 
    type: Object,
    default: null
  }
})

const emit = defineEmits(['close', 'update'])

const form = ref({
  id_fasilitas: null,
  nama_fasilitas: '',
  icon_fasilitas: '',
})

// 🔄 Watcher: Otomatis mengisi form ketika data 'fasilitas' berubah/diterima
watch(() => props.fasilitas, (newFasilitas) => {
  if (newFasilitas) {
    form.value = {
      id_fasilitas: newFasilitas.id_fasilitas,
      nama_fasilitas: newFasilitas.nama_fasilitas,
      // Pastikan data icon juga diambil
      icon_fasilitas: newFasilitas.icon_fasilitas || '', 
    }
  }
}, { immediate: true })

const submit = () => {
  // Emit form.value yang mencakup id_fasilitas, nama_fasilitas, dan icon_fasilitas
  emit('update', form.value) 
}

const close = () => {
  emit('close')
}
</script>