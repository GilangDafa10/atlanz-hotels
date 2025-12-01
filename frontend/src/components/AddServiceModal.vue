<template>
  <div
    v-if="modelValue"
    class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4"
    @click.self="closeModal"
  >
    <div class="bg-white rounded-xl p-6 max-w-4xl w-full max-h-[80vh] overflow-y-auto">
      <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-bold text-[#0c2c67]">Select Additional Services</h2>
        <button @click="closeModal" class="text-gray-500 hover:text-gray-700 text-2xl">×</button>
      </div>

      <!-- Loading services -->
      <div v-if="loading" class="text-center py-10">
        <p class="text-gray-600">Loading services...</p>
      </div>

      <!-- Services grid -->
      <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div
          v-for="service in services"
          :key="service.id_service"
          class="border rounded-lg p-4 hover:shadow-md transition cursor-pointer"
          :class="{
            'border-[#0c2c67] bg-blue-50': isServiceSelected(service.id_service),
          }"
          @click="toggleService(service)"
        >
          <div class="flex gap-4">
            <img
              :src="
                service.url_gambar
                  ? `http://127.0.0.1:8000/storage/${service.url_gambar}`
                  : '/src/assets/Yoga.png'
              "
              class="w-24 h-24 object-cover rounded-lg"
            />
            <div class="flex-1">
              <h3 class="font-semibold text-lg text-gray-800">{{ service.nama_service }}</h3>
              <p class="text-sm text-gray-600 mb-2">{{ service.deskripsi_service }}</p>
              <p class="font-bold text-[#b7a34b]">{{ formatRupiah(service.harga_service) }}</p>
            </div>
            <div class="flex items-start">
              <input
                type="checkbox"
                :checked="isServiceSelected(service.id_service)"
                class="w-5 h-5 text-[#0c2c67]"
              />
            </div>
          </div>
        </div>
      </div>

      <!-- Modal footer -->
      <div class="mt-6 flex justify-end gap-3">
        <button
          @click="closeModal"
          class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50"
        >
          Cancel
        </button>
        <button
          @click="applyServices"
          class="px-6 py-2 bg-[#0c2c67] text-white rounded-lg hover:bg-[#081e47]"
        >
          Apply ({{ localSelectedServices.length }})
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue'

const props = defineProps({
  modelValue: {
    type: Boolean,
    required: true,
  },
  services: {
    type: Array,
    default: () => [],
  },
  selectedServices: {
    type: Array,
    default: () => [],
  },
  loading: {
    type: Boolean,
    default: false,
  },
})

const emit = defineEmits(['update:modelValue', 'update:selectedServices', 'apply'])

// Local copy of selected services
const localSelectedServices = ref([...props.selectedServices])

// Watch for changes in props
watch(
  () => props.selectedServices,
  (newVal) => {
    localSelectedServices.value = [...newVal]
  },
)

// Format rupiah
const formatRupiah = (value) =>
  new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
  }).format(value)

// Check if service is selected
const isServiceSelected = (serviceId) => {
  return localSelectedServices.value.some((s) => s.id_service === serviceId)
}

// Toggle service selection
const toggleService = (service) => {
  const index = localSelectedServices.value.findIndex((s) => s.id_service === service.id_service)
  if (index > -1) {
    localSelectedServices.value.splice(index, 1)
  } else {
    localSelectedServices.value.push(service)
  }
}

// Close modal
const closeModal = () => {
  emit('update:modelValue', false)
}

// Apply services
const applyServices = () => {
  emit('update:selectedServices', localSelectedServices.value)
  emit('apply', localSelectedServices.value)
  closeModal()
}
</script>

<style scoped>
* {
  font-family: 'Poppins', sans-serif;
}
</style>
