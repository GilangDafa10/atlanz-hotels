<template>
  <div v-if="modelValue" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-xl p-6 w-80 text-center animate-fadeIn">
      <img src="@/assets/check_circle.png" alt="Success" class="mx-auto mb-4" />
      <h3 class="text-xl font-semibold mb-2">{{ title }}</h3>

      <p class="text-gray-600 mb-4">
        {{ message }}
      </p>

      <button
        @click="close"
        class="px-4 py-2 bg-blue-600 text-white rounded-lg w-full hover:bg-blue-700"
      >
        {{ buttonText }}
      </button>
    </div>
  </div>
</template>

<script setup>
import { defineProps, defineEmits } from 'vue'

const emit = defineEmits(['update:modelValue', 'confirm'])

const props = defineProps({
  modelValue: Boolean,
  title: {
    type: String,
    default: 'Success!',
  },
  message: {
    type: String,
    default: 'Berhasil!',
  },
  buttonText: {
    type: String,
    default: 'OK',
  },
})

const close = () => {
  emit('update:modelValue', false)
  emit('confirm') // event ketika klik OK
}
</script>

<style scoped>
@keyframes fadeIn {
  from {
    opacity: 0;
    transform: scale(0.95);
  }
  to {
    opacity: 1;
    transform: scale(1);
  }
}

.animate-fadeIn {
  animation: fadeIn 0.2s ease-out;
}
</style>
