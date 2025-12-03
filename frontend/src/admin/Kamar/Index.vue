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

    <div v-if="loading" class="text-center py-10">
      <span class="text-gray-500">Memuat data kamar...</span>
    </div>

    <div v-else-if="errorMessage" class="bg-red-100 text-red-700 p-4 rounded mb-4 font-semibold">
      Gagal memuat data! {{ errorMessage }}
    </div>

    <div v-else class="bg-white rounded-lg shadow overflow-hidden">
      <table class="w-full">
        <thead class="bg-gray-50">
          <tr>
            <th
              class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
            >
              No. Kamar
            </th>
            <th
              class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
            >
              Tipe Kamar
            </th>
            <th
              class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
            >
              Aksi
            </th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-if="kamarList.length === 0 && !loading">
            <td colspan="3" class="px-6 py-4 text-center text-gray-500">Belum ada data kamar.</td>
          </tr>
          <tr v-for="kamar in kamarList" :key="kamar.id">
            <td class="px-6 py-4 whitespace-nowrap font-medium">{{ kamar.number }}</td>
            <td class="px-6 py-4 whitespace-nowrap">{{ kamar.type }}</td>
            <td class="px-6 py-4 whitespace-nowrap">
              <button @click="openEditModal(kamar)" class="text-blue-600 hover:text-blue-800 mr-4">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  class="h-4 w-4 inline"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                  />
                </svg>
              </button>
              <button @click="openConfirmModal(kamar.id)" class="text-red-600 hover:text-red-800">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  class="h-4 w-4 inline"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M19 7l-.867 12.142A1 1 0 0117.133 21H6.867A1 1 0 016 19.867L4.867 7.867A1 1 0 016 6h12a1 1 0 011 1.133L19 7z"
                  />
                </svg>
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <CreateModal :is-open="showCreateModal" @close="showCreateModal = false" @create="addKamar" />

    <EditModal
      :is-open="showEditModal"
      :kamar="currentKamar"
      @close="showEditModal = false"
      @update="updateKamar"
    />

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
import { ref, onMounted } from 'vue'
import axios from 'axios'
import CreateModal from './Components/CreateModal.vue'
import EditModal from './Components/EditModal.vue'
import ConfirmModal from './Components/ConfirmModal.vue'

// --- State ---
const kamarList = ref([])
const loading = ref(false)
const errorMessage = ref('')

const showCreateModal = ref(false)
const showEditModal = ref(false)
const showConfirmModal = ref(false)
const currentKamar = ref(null)
const currentIdToDelete = ref(null)
// Tambahkan di bagian state
const jenisKamarList = ref([])
const loadingJenisKamar = ref(false)

// --- Konfigurasi API ---
const API_URL = 'http://127.0.0.1:8000/api/kamar'

// Helper untuk header Auth
const getAuthHeader = () => {
  const token = localStorage.getItem('token')

  if (!token) {
    errorMessage.value = 'Autentikasi diperlukan. Token tidak ditemukan.'
    throw new Error('Token not found.')
  }

  return {
    headers: {
      Authorization: `Bearer ${token}`,
      Accept: 'application/json',
    },
  }
}

// --- READ (GET) ---
const fetchKamar = async () => {
  loading.value = true
  errorMessage.value = ''
  try {
    const response = await axios.get(API_URL, getAuthHeader())

    const rawData = response.data.data
    console.log('fetchKamar - Raw data from API:', rawData)

    kamarList.value = rawData.map((item) => {
      const mapped = {
        id: item.id_kamar,
        number: item.nomor_kamar,
        type: item.jenis_kamar ? item.jenis_kamar.jenis_kasur : '-',
        originalData: {
          ...item,
          // Pastikan id_jenis_kamar ada, baik dari root atau dari nested object
          id_jenis_kamar: item.id_jenis_kamar || item.jenis_kamar?.id_jenis_kamar,
        },
      }
      console.log('fetchKamar - Mapped item for', item.nomor_kamar, ':', mapped)
      return mapped
    })
  } catch (error) {
    console.error('Error fetching data:', error)
    if (error.message === 'Token not found.') {
      // Error sudah ditangani di getAuthHeader
    } else if (error.response && error.response.status === 404) {
      kamarList.value = []
      errorMessage.value = 'Belum ada data kamar.'
    } else if ((error.response && error.response.status === 401) || error.response.status === 403) {
      errorMessage.value = 'Sesi habis atau Token tidak valid/Tidak memiliki hak akses Admin.'
    } else {
      errorMessage.value = 'Gagal mengambil data kamar.'
    }
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  fetchKamar()
})
const fetchJenisKamar = async () => {
  loadingJenisKamar.value = true
  try {
    const res = await axios.get('http://127.0.0.1:8000/api/jenis-kamar')
    jenisKamarList.value = res.data.data
  } catch (err) {
    console.error('Gagal fetch jenis kamar:', err)
  } finally {
    loadingJenisKamar.value = false
  }
}
onMounted(() => {
  fetchKamar()
  fetchJenisKamar() // ✅ Tambahkan ini
})

// --- CREATE (POST) ---
const addKamar = async (newKamarPayload) => {
  try {
    await axios.post(API_URL, newKamarPayload, getAuthHeader())

    // Sukses: Refresh data dan tutup modal
    await fetchKamar()
    showCreateModal.value = false
  } catch (error) {
    let errorMessage = 'Gagal menambahkan kamar.'

    if (error.response) {
      if (error.response.status === 422) {
        const validationErrors = error.response.data.errors
        const firstField = Object.keys(validationErrors)[0]
        errorMessage = `Input tidak valid: ${validationErrors[firstField][0]}`
      } else if (error.response.status === 401 || error.response.status === 403) {
        errorMessage = 'Autentikasi gagal. Cek token atau pastikan Anda Admin.'
      } else {
        errorMessage = `Terjadi kesalahan server (${error.response.status}).`
      }
    } else if (error.message === 'Token not found.') {
      errorMessage = 'Token tidak ditemukan. Silakan login kembali.'
    }

    console.error('Gagal tambah kamar:', error)
    alert(errorMessage)
  }
}

// --- PREPARE EDIT (Update Kamar) ---
const openEditModal = (kamarMapped) => {
  console.log('openEditModal - kamarMapped:', kamarMapped)
  console.log('openEditModal - originalData:', kamarMapped.originalData)
  console.log('openEditModal - id_jenis_kamar:', kamarMapped.originalData?.id_jenis_kamar)
  currentKamar.value = kamarMapped.originalData
  showEditModal.value = true
}

const updateKamar = async (updatedPayload) => {
  try {
    const url = `${API_URL}/${updatedPayload.id_kamar}`

    console.log('Update payload:', updatedPayload)
    const response = await axios.put(url, updatedPayload, getAuthHeader())
    console.log('Update response:', response.data)

    // Refresh data dari server untuk memastikan sinkronisasi
    await fetchKamar()

    showEditModal.value = false
    alert('Kamar berhasil diperbarui!')
  } catch (error) {
    console.error('Gagal update kamar:', error)

    let errorMessage = 'Gagal memperbarui kamar.'
    if (error.response?.status === 422) {
      const validationErrors = error.response.data.errors
      const firstField = Object.keys(validationErrors)[0]
      errorMessage = `Input tidak valid: ${validationErrors[firstField][0]}`
    } else if (error.response?.status === 401 || error.response?.status === 403) {
      errorMessage = 'Autentikasi gagal. Cek token atau pastikan Anda Admin.'
    }

    alert(errorMessage)
  }
}

// --- DELETE ---
const openConfirmModal = (id) => {
  currentIdToDelete.value = id
  showConfirmModal.value = true
}

const deleteKamar = async () => {
  try {
    const url = `${API_URL}/${currentIdToDelete.value}`
    await axios.delete(url, getAuthHeader())

    await fetchKamar() // Refresh list setelah delete
    showConfirmModal.value = false
  } catch (error) {
    console.error('Gagal hapus kamar:', error)
    alert('Gagal menghapus kamar. Cek token Anda.')
  }
}
</script>
