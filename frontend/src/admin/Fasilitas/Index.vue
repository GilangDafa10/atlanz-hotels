<template>
  <div class="p-6">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold">Daftar Fasilitas</h1>
      <button
        @click="showCreateModal = true"
        class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition"
      >
        + Tambah Fasilitas
      </button>
    </div>

    <div v-if="loading" class="text-center py-10">
      <span class="text-gray-500">Memuat data fasilitas...</span>
    </div>

    <div v-else-if="errorMessage" class="bg-red-100 text-red-700 p-4 rounded mb-4 font-semibold">
      Gagal memuat data! {{ errorMessage }}
    </div>

    <div v-else class="bg-white rounded-lg shadow overflow-hidden">
      <table class="w-full">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Fasilitas</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ikon</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-if="fasilitasList.length === 0">
            <td colspan="4" class="px-6 py-4 text-center text-gray-500">
              Belum ada data fasilitas.
            </td>
          </tr>
          <tr v-for="fasilitas in fasilitasList" :key="fasilitas.id_fasilitas">
            <td class="px-6 py-4 whitespace-nowrap">{{ fasilitas.id_fasilitas }}</td>
            <td class="px-6 py-4 whitespace-nowrap font-medium">{{ fasilitas.nama_fasilitas }}</td>
            
            <td class="px-6 py-4 whitespace-nowrap text-lg">
                <i :class="fasilitas.icon_fasilitas || 'fa-solid fa-circle-question'" 
                   :title="fasilitas.icon_fasilitas ? fasilitas.icon_fasilitas : 'Tidak Ada Ikon'">
                </i>
            </td>
            
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
                @click="openConfirmModal(fasilitas.id_fasilitas)"
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
    
    <CreateModal
      :is-open="showCreateModal"
      @close="showCreateModal = false"
      @create="addFasilitas"
      
    />

    <EditModal
      :is-open="showEditModal"
      :fasilitas="currentFasilitas"
      @close="showEditModal = false"
      @update="updateFasilitas"
    />

    <ConfirmModal
      :is-open="showConfirmModal"
      title="Hapus Fasilitas"
      message="Apakah kamu yakin ingin menghapus fasilitas ini? Aksi ini tidak bisa dibatalkan."
      @confirm="deleteFasilitas"
      @cancel="showConfirmModal = false"
    />
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
// Pastikan path ke komponen modal ini benar di proyek Anda
import CreateModal from './Components/CreateModal.vue'
import EditModal from './Components/EditModal.vue'
import ConfirmModal from './Components/ConfirmModal.vue'

// --- State ---
const fasilitasList = ref([]) 
const loading = ref(false)
const errorMessage = ref('')

const showCreateModal = ref(false)
const showEditModal = ref(false)
const showConfirmModal = ref(false)
const currentFasilitas = ref(null) 
const currentIdToDelete = ref(null)

// --- Konfigurasi API ---
const API_URL = 'http://127.0.0.1:8000/api/fasilitas'

// Helper untuk header Auth
const getAuthHeader = (required = false) => {
  const token = localStorage.getItem('token') 
  
  if (required && !token) {
    errorMessage.value = 'Aksi ini memerlukan autentikasi. Token tidak ditemukan.'
    throw new Error("Token not found.") 
  }

  return {
    headers: {
      ...(token && { Authorization: `Bearer ${token}` }), 
      'Accept': 'application/json'
    }
  }
}

// --- CRUD Functions ---

// READ (GET)
const fetchFasilitas = async () => {
  loading.value = true
  errorMessage.value = '' 
  try {
    const response = await axios.get(API_URL, getAuthHeader(false)) 
    // Pastikan response.data.data adalah array sesuai struktur API yang dipelajari
    fasilitasList.value = response.data.data 
  } catch (error) {
    console.error('Error fetching fasilitas:', error)
    if (error.response && error.response.status === 404) {
        fasilitasList.value = [] 
        errorMessage.value = error.response.data.message || 'Belum ada data fasilitas.'
    } else {
        errorMessage.value = 'Gagal mengambil data fasilitas. Cek koneksi Anda/API.'
    }
  } finally {
    loading.value = false
  }
}

// CREATE (POST)
const addFasilitas = async (newFasilitasPayload) => {
  try {
    await axios.post(API_URL, newFasilitasPayload, getAuthHeader(true))
    await fetchFasilitas() 
    showCreateModal.value = false 
    alert('Fasilitas berhasil ditambahkan!')
  } catch (error) {
    let msg = 'Gagal menambahkan fasilitas.';
    console.error('Gagal tambah fasilitas:', error);
    alert(msg); 
  }
}

// UPDATE (PUT)
const openEditModal = (fasilitas) => {
  // Penting: Copy objek agar perubahan di modal tidak langsung memengaruhi data tabel
  currentFasilitas.value = { ...fasilitas } 
  showEditModal.value = true
}

const updateFasilitas = async (updatedPayload) => {
  try {
    // Dapatkan ID dari payload
    const { id_fasilitas, ...dataToUpdate } = updatedPayload;

    const url = `${API_URL}/${id_fasilitas}`
    // Kirim hanya data yang diizinkan (nama_fasilitas, icon_fasilitas)
    await axios.put(url, dataToUpdate, getAuthHeader(true))

    await fetchFasilitas()
    showEditModal.value = false
    alert('Fasilitas berhasil diperbarui!')
  } catch (error) {
    let msg = 'Gagal memperbarui fasilitas.';
    console.error('Gagal update fasilitas:', error)
    alert(msg)
  }
}

// DELETE
const openConfirmModal = (id) => {
  currentIdToDelete.value = id
  showConfirmModal.value = true
}

const deleteFasilitas = async () => {
  try {
    const url = `${API_URL}/${currentIdToDelete.value}`
    await axios.delete(url, getAuthHeader(true))
    
    // Optimistic UI Update: hapus dari list tanpa fetch ulang
    fasilitasList.value = fasilitasList.value.filter(f => f.id_fasilitas !== currentIdToDelete.value)
    
    showConfirmModal.value = false
    alert('Fasilitas berhasil dihapus!')
    
    if (fasilitasList.value.length === 0) {
        await fetchFasilitas(); // Fetch ulang jika tabel kosong
    }
  } catch (error) {
    console.error('Gagal hapus fasilitas:', error)
    alert('Gagal menghapus fasilitas. Pastikan Anda memiliki hak akses Admin.')
  }
}

onMounted(() => {
  fetchFasilitas()
})
</script>