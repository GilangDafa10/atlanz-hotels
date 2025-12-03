<template>
  <transition name="fade">
    <div
      v-if="isOpen"
      class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50"
    >
      <div
        class="bg-white w-full max-w-lg rounded-2xl shadow-xl p-6 animate-fadeIn relative overflow-y-auto max-h-[90vh] scrollbar-hide"
      >
        <!-- Header -->
        <div class="flex justify-between items-center mb-4">
          <h2 class="text-xl font-bold text-[#0d3b66]">Edit Layanan</h2>
          <button @click="$emit('close')" class="text-gray-500 hover:text-gray-700 transition">
            ✕
          </button>
        </div>

        <!-- Form -->
        <div class="space-y-4">
          <div>
            <label class="font-medium text-gray-700">Nama Service</label>
            <input
              class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-200 outline-none"
              v-model="form.name"
            />
          </div>

          <div>
            <label class="font-medium text-gray-700">Harga</label>
            <input
              type="number"
              class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-200 outline-none"
              v-model="form.price"
            />
          </div>

          <div>
            <label class="font-medium text-gray-700">Deskripsi</label>
            <textarea
              class="mt-1 w-full h-28 border rounded-lg px-3 py-2 focus:ring focus:ring-blue-200 outline-none"
              v-model="form.description"
            ></textarea>
          </div>

          <div>
            <label class="font-medium text-gray-700">Gambar Saat Ini</label>
            <img
              v-if="existingImageUrl"
              :src="existingImageUrl"
              class="mt-2 w-full h-40 object-cover rounded-lg border"
            />
          </div>

          <div>
            <label class="font-medium text-gray-700">Ganti Gambar</label>
            <input
              type="file"
              class="mt-1 w-full border rounded-lg px-3 py-2 bg-gray-50 cursor-pointer"
              @change="onFileChange"
            />
          </div>
        </div>

        <!-- Footer -->
        <div class="flex justify-end gap-3 mt-6">
          <button
            @click="$emit('close')"
            class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg transition"
          >
            Batal
          </button>

          <button
            @click="submitForm"
            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition"
          >
            Simpan
          </button>
        </div>
      </div>
    </div>
  </transition>
</template>

<script setup>
import { ref, watch } from 'vue'

const props = defineProps({
  isOpen: Boolean,
  serviceData: Object,
})

const emit = defineEmits(['close', 'update'])

const form = ref({
  id: null,
  name: '',
  price: '',
  description: '',
})

const existingImageUrl = ref(null)
const selectedFile = ref(null)

watch(
  () => props.serviceData,
  (data) => {
    if (data) {
      form.value = {
        id: data.id_service,
        name: data.nama_service,
        price: data.harga_service,
        description: data.deskripsi_service,
      }
      existingImageUrl.value = data.url_gambar
    }
  },
  { immediate: true },
)

const onFileChange = (e) => {
  selectedFile.value = e.target.files[0]
}

const submitForm = () => {
  const formData = new FormData()

  formData.append('_method', 'PUT')
  formData.append('nama_service', form.value.name)
  formData.append('harga_service', form.value.price)
  formData.append('deskripsi_service', form.value.description)

  if (selectedFile.value) {
    formData.append('url_gambar', selectedFile.value)
  }

  emit('update', { id: form.value.id, data: formData })
}
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.25s;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(8px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
.animate-fadeIn {
  animation: fadeIn 0.25s ease-out;
}

/* Hide scrollbar for Chrome, Safari and Opera */
.scrollbar-hide::-webkit-scrollbar {
  display: none;
}

/* Hide scrollbar for IE, Edge and Firefox */
.scrollbar-hide {
  -ms-overflow-style: none; /* IE and Edge */
  scrollbar-width: none; /* Firefox */
}
</style>
