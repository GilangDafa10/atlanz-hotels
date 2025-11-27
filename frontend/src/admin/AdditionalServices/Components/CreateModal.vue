<template>
  <div v-if="isOpen" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="bg-white p-6 rounded-lg shadow-xl w-full max-w-lg">
      <h3 class="text-xl font-semibold mb-4 text-[#0d3b66]">Tambah Layanan Tambahan Baru</h3>
      
      <form @submit.prevent="submitForm">
        <div class="mb-4">
          <label class="block text-sm font-medium text-gray-700">Nama Layanan</label>
          <input v-model="form.name" required
                 class="mt-1 block w-full border border-gray-300 rounded-md p-2 focus:ring-blue-500 focus:border-blue-500">
        </div>

        <div class="mb-4">
          <label class="block text-sm font-medium text-gray-700">Harga (Rp)</label>
          <input v-model.number="form.price" type="number" required
                 class="mt-1 block w-full border border-gray-300 rounded-md p-2 focus:ring-blue-500 focus:border-blue-500">
        </div>

        <div class="mb-4">
          <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
          <textarea v-model="form.description" rows="3"
                 class="mt-1 block w-full border border-gray-300 rounded-md p-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Upload Gambar</label>
          <input type="file" accept="image/*" @change="handleFileChange"
                 class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
          <div v-if="previewUrl" class="mt-3">
            <p class="text-xs text-gray-500 mb-1">Preview:</p>
            <img :src="previewUrl" class="h-32 w-auto object-cover rounded-lg border" />
          </div>
        </div>

        <div class="flex justify-end space-x-3 mt-6">
          <button type="button" @click="$emit('close')" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded">
            Batal
          </button>
          <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded">
            Simpan
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue';

const props = defineProps({ isOpen: Boolean });
const emit = defineEmits(['close', 'create']);

const form = ref({ name: '', price: 0, description: '' });
const selectedFile = ref(null);
const previewUrl = ref(null);

watch(() => props.isOpen, (newVal) => {
  if (newVal) {
    form.value = { name: '', price: 0, description: '' };
    selectedFile.value = null;
    previewUrl.value = null;
  }
});

const handleFileChange = (e) => {
  const file = e.target.files[0];
  if (file) {
    selectedFile.value = file;
    previewUrl.value = URL.createObjectURL(file);
  }
};

const submitForm = () => {
  const formData = new FormData();
  formData.append('nama_service', form.value.name);
  formData.append('harga_service', form.value.price);
  formData.append('deskripsi_service', form.value.description);
  if (selectedFile.value) formData.append('url_gambar', selectedFile.value);
  emit('create', formData);
};
</script>