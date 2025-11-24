<template>
  <div class="p-6">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold">Fasilitas</h1>
      <button
        @click="showCreateModal = true"
        class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition"
      >
        + Tambah Fasilitas
      </button>
    </div>

    <!-- Daftar Fasilitas dalam Baris -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
      <table class="w-full">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Fasilitas</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-for="fasilitas in facilities" :key="fasilitas.id">
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="flex items-center">
                <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center mr-3">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3-2v-4m3 4v2m3 2a6 6 0 006-6V9a6 6 0 00-6-6H9a6 6 0 00-6 6v6a6 6 0 006 6z" />
                  </svg>
                </div>
                <span>{{ fasilitas.name }}</span>
              </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">{{ fasilitas.category }}</td>
            <td class="px-6 py-4 whitespace-nowrap">
              <button
                @click="openEditModal(fasilitas)"
                class="text-blue-600 hover:text-blue-800 mr-4"
              >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
              </button>
              <button
                @click="openConfirmModal(fasilitas.id)"
                class="text-red-600 hover:text-red-800"
              >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A1 1 0 0117.133 21H6.867A1 1 0 016 19.867L4.867 7.867A1 1 0 016 6h12a1 1 0 011 1.133L19 7z" />
                </svg>
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Modal Create -->
    <CreateModal
      :is-open="showCreateModal"
      @close="showCreateModal = false"
      @create="addFacility"
    />

    <!-- Modal Edit -->
    <EditModal
      :is-open="showEditModal"
      :fasilitas="currentFacility"
      @close="showEditModal = false"
      @update="updateFacility"
    />

    <!-- Modal Konfirmasi Hapus -->
    <ConfirmModal
      :is-open="showConfirmModal"
      title="Hapus Fasilitas"
      message="Apakah kamu yakin ingin menghapus fasilitas ini? Aksi ini tidak bisa dibatalkan."
      @confirm="deleteFacility"
      @cancel="showConfirmModal = false"
    />
  </div>
</template>

<script setup>
import { ref } from 'vue'
import CreateModal from './Components/CreateModal.vue'
import EditModal from './Components/EditModal.vue'
import ConfirmModal from './Components/ConfirmModal.vue'

const facilities = ref([
  {
    id: 1,
    name: 'Bed',
    icon: 'bed',
    category: 'Kamar'
  },
  {
    id: 2,
    name: 'Shower',
    icon: 'shower',
    category: 'Kamar Mandi'
  },
  {
    id: 3,
    name: 'Perlengkapan Mandi',
    icon: 'toiletries',
    category: 'Kamar Mandi'
  },
  {
    id: 4,
    name: 'Mesin Pembuat Kopi',
    icon: 'coffee',
    category: 'Dapur'
  },
  {
    id: 5,
    name: 'Wi-Fi',
    icon: 'wifi',
    category: 'Teknologi'
  },
  {
    id: 6,
    name: 'TV',
    icon: 'tv',
    category: 'Hiburan'
  },
  {
    id: 7,
    name: 'Kulkas',
    icon: 'fridge',
    category: 'Dapur'
  },
  {
    id: 8,
    name: 'Setrika',
    icon: 'iron',
    category: 'Pakaian'
  }
])

const showCreateModal = ref(false)
const showEditModal = ref(false)
const showConfirmModal = ref(false)
const currentFacility = ref(null)
const currentIdToDelete = ref(null)

const addFacility = (newFacility) => {
  const newId = Math.max(...facilities.value.map(f => f.id), 0) + 1
  facilities.value.push({ id: newId, ...newFacility })
  showCreateModal.value = false
}

const openEditModal = (fasilitas) => {
  currentFacility.value = fasilitas
  showEditModal.value = true
}

const updateFacility = (updatedFacility) => {
  const index = facilities.value.findIndex(f => f.id === updatedFacility.id)
  if (index !== -1) {
    facilities.value[index] = updatedFacility
  }
  showEditModal.value = false
}

const openConfirmModal = (id) => {
  currentIdToDelete.value = id
  showConfirmModal.value = true
}

const deleteFacility = () => {
  facilities.value = facilities.value.filter(fasilitas => fasilitas.id !== currentIdToDelete.value)
  showConfirmModal.value = false
}
</script>