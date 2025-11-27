<template>
  <Teleport to="body">
    <div v-if="isOpen" class="fixed inset-0 bg-black/50 flex items-center justify-center z-[9999]" @click.self="cancel">
      <div class="bg-white rounded-lg shadow-xl max-w-sm w-full p-6 transition-transform duration-300">
        <div class="text-center">
          <svg class="mx-auto h-12 w-12 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.3 16c-.77 1.333.192 3 1.732 3z" />
          </svg>
          <h3 class="mt-2 text-lg leading-6 font-medium text-gray-900">{{ title }}</h3>
          <div class="mt-2">
            <p class="text-sm text-gray-500">{{ message }}</p>
          </div>
        </div>
        
        <div class="mt-5 sm:mt-6 sm:flex sm:flex-row-reverse gap-3">
          <button
            type="button"
            @click="confirmAction"
            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm"
          >
            Ya, Hapus
          </button>
          <button
            type="button"
            @click="cancel"
            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm"
          >
            Batal
          </button>
        </div>
      </div>
    </div>
  </Teleport>
</template>

<script setup>
const props = defineProps({
  isOpen: {
    type: Boolean,
    default: false
  },
  title: {
    type: String,
    default: "Konfirmasi Aksi"
  },
  message: {
    type: String,
    default: "Apakah Anda yakin ingin melanjutkan?"
  }
})

const emit = defineEmits(['confirm', 'cancel'])

const confirmAction = () => {
  emit('confirm')
}

const cancel = () => {
  emit('cancel')
}
</script>