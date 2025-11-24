<template>
  <div class="p-6">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold">Daftar Kamar</h1>
      <button
        @click="showCreateModal = true"
        class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition"
      >
        + Tambah Kamar
      </button>
    </div>

    <!-- Tabel -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
      <table class="w-full">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Kamar</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe Kamar</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-for="kamar in kamarList" :key="kamar.id">
            <td class="px-6 py-4 whitespace-nowrap">{{ kamar.number }}</td>
            <td class="px-6 py-4 whitespace-nowrap">{{ kamar.type }}</td>
            <td class="px-6 py-4 whitespace-nowrap">
              <button
                @click="openEditModal(kamar)"
                class="text-blue-600 hover:text-blue-800 mr-4"
              >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
              </button>
              <button
                @click="openConfirmModal(kamar.id)"
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
      @create="addKamar"
    />

    <!-- Modal Edit -->
    <EditModal
      :is-open="showEditModal"
      :kamar="currentKamar"
      @close="showEditModal = false"
      @update="updateKamar"
    />

    <!-- Modal Konfirmasi Hapus -->
    <ConfirmModal
      :is-open="showConfirmModal"
      title="Hapus Kamar"
      message="Apakah kamu yakin ingin menghapus kamar ini? Aksi ini tidak bisa dibatalkan."
      @confirm="deleteKamar"
      @cancel="showConfirmModal = false"
    />
  </div>
</template>

<script setup>
import { ref } from 'vue'
import CreateModal from './Components/CreateModal.vue'
import EditModal from './Components/EditModal.vue'
import ConfirmModal from './Components/ConfirmModal.vue'

const kamarList = ref([
  {
    id: 1,
    number: '101',
    type: 'Twin Bed',
    status: 'tersedia',
    floor: 1,
    facilities: ['AC', 'Wi-Fi', 'TV']
  },
  {
    id: 2,
    number: '102',
    type: 'Double Bed',
    status: 'dipesan',
    floor: 1,
    facilities: ['AC', 'Wi-Fi', 'TV', 'Kulkas']
  },
  {
    id: 3,
    number: '103',
    type: 'King Bed',
    status: 'tersedia',
    floor: 1,
    facilities: ['AC', 'Wi-Fi', 'TV', 'Kulkas', 'Ruang Tamu']
  },
  {
    id: 4,
    number: '201',
    type: 'Twin Bed',
    status: 'dibersihkan',
    floor: 2,
    facilities: ['AC', 'Wi-Fi', 'TV']
  },
  {
    id: 5,
    number: '202',
    type: 'Double Bed',
    status: 'tersedia',
    floor: 2,
    facilities: ['AC', 'Wi-Fi', 'TV', 'Kulkas']
  },
  {
    id: 6,
    number: '203',
    type: 'King Bed',
    status: 'dipesan',
    floor: 2,
    facilities: ['AC', 'Wi-Fi', 'TV', 'Kulkas', 'Ruang Tamu']
  },
])

const showCreateModal = ref(false)
const showEditModal = ref(false)
const showConfirmModal = ref(false)
const currentKamar = ref(null)
const currentIdToDelete = ref(null)

const addKamar = (newKamar) => {
  const newId = Math.max(...kamarList.value.map(k => k.id), 0) + 1
  kamarList.value.push({ id: newId, ...newKamar })
  showCreateModal.value = false
}

const openEditModal = (kamar) => {
  currentKamar.value = kamar
  showEditModal.value = true
}

const updateKamar = (updatedKamar) => {
  const index = kamarList.value.findIndex(k => k.id === updatedKamar.id)
  if (index !== -1) {
    kamarList.value[index] = updatedKamar
  }
  showEditModal.value = false
}

const openConfirmModal = (id) => {
  currentIdToDelete.value = id
  showConfirmModal.value = true
}

const deleteKamar = () => {
  kamarList.value = kamarList.value.filter(kamar => kamar.id !== currentIdToDelete.value)
  showConfirmModal.value = false
}
</script>