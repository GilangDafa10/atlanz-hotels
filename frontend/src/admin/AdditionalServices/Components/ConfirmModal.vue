<template>
  <div v-if="isOpen" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="bg-white p-6 rounded-lg shadow-xl w-full max-w-sm">
      <h3 class="text-xl font-semibold mb-4 text-red-600">Konfirmasi Penghapusan</h3>
      
      <p class="text-gray-700 mb-6">Anda yakin ingin menghapus Layanan Tambahan ID: **{{ serviceId }}**? Tindakan ini tidak dapat dibatalkan.</p>

      <div class="flex justify-end space-x-3">
        <button type="button" @click="$emit('close')"
                :disabled="isLoading" 
                class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded transition duration-200 disabled:opacity-50">
          Batal
        </button>
        <button type="button" @click="handleConfirm"
                :disabled="isLoading" 
                class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded transition duration-200 flex items-center disabled:opacity-50">
          <span v-if="isLoading">Menghapus...</span>
          <span v-else>Ya, Hapus</span>
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue';

const props = defineProps({
    isOpen: Boolean,
    serviceId: [Number, String] 
});

const emit = defineEmits(['close', 'confirm']); 

const isLoading = ref(false); 

watch(() => props.isOpen, (newVal) => {
    if (!newVal) {
        isLoading.value = false;
    }
});

const handleConfirm = () => {
    isLoading.value = true;
    emit('confirm');
};
</script>