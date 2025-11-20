<template>
  <transition name="modal-fade">
    <div v-if="modelValue" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
      <div class="bg-white rounded-2xl p-6 w-full max-w-sm shadow-xl animate-fadeIn text-center">
        <!-- Success Icon -->
        <div class="flex justify-center mb-4">
          <div
            class="w-14 h-14 flex items-center justify-center rounded-full bg-green-100 border border-green-300"
          >
            <svg
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              viewBox="0 0 24 24"
              stroke="green"
              class="w-8 h-8"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2.5"
                d="M5 13l4 4L19 7"
              />
            </svg>
          </div>
        </div>

        <!-- Title -->
        <h2 class="text-xl font-semibold text-gray-900 mb-2">
          {{ title }}
        </h2>

        <!-- Message -->
        <p class="text-gray-600">
          {{ message }}
        </p>
      </div>
    </div>
  </transition>
</template>

<script setup>
import { watch, onMounted } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()

const props = defineProps({
  modelValue: Boolean,
  title: {
    type: String,
    default: 'Login Berhasil!',
  },
  message: {
    type: String,
    default: 'Selamat datang kembali 😄',
  },
})

const emit = defineEmits(['update:modelValue'])

// Auto close + redirect
watch(
  () => props.modelValue,
  (val) => {
    if (val) {
      setTimeout(() => {
        emit('update:modelValue', false)
        router.push('/')
      }, 2000)
    }
  },
)
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

/* Fade overlay */
.modal-fade-enter-active,
.modal-fade-leave-active {
  transition: opacity 0.2s ease;
}
.modal-fade-enter-from,
.modal-fade-leave-to {
  opacity: 0;
}
</style>
