<template>
  <div class="p-6">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold">Manajemen Pengguna (Users)</h1>
      <button
        @click="showCreateModal = true"
        class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition"
      >
        + Tambah User
      </button>
    </div>

    <div v-if="loading" class="text-center py-10">
      <span class="text-gray-500">Memuat data pengguna...</span>
    </div>

    <div v-else-if="errorMessage" class="bg-red-100 text-red-700 p-4 rounded mb-4 font-semibold">
      Gagal memuat data! {{ errorMessage }}
    </div>

    <div v-else class="bg-white rounded-lg shadow overflow-hidden">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-if="userList.length === 0">
            <td colspan="5" class="px-6 py-4 text-center text-gray-500">
              Belum ada data pengguna.
            </td>
          </tr>
          <tr v-for="user in userList" :key="user.id">
            <td class="px-6 py-4 whitespace-nowrap">{{ user.id }}</td>
            <td class="px-6 py-4 whitespace-nowrap font-medium">{{ user.name }}</td>
            <td class="px-6 py-4 whitespace-nowrap">{{ user.email }}</td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span :class="{'bg-green-100 text-green-800': user.role === 'admin', 'bg-blue-100 text-blue-800': user.role === 'customer'}" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full capitalize">
                    {{ user.role }}
                </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
              <button
                @click="openEditModal(user)"
                class="text-blue-600 hover:text-blue-800 mr-4"
              >
                Edit
              </button>
              <button
                @click="openConfirmModal(user.id)"
                class="text-red-600 hover:text-red-800"
                :disabled="user.role === 'admin' && userList.filter(u => u.role === 'admin').length === 1"
              >
                Hapus
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <CreateModal
      :is-open="showCreateModal"
      @close="showCreateModal = false"
      @created="handleCreated"
    />

    <EditModal
      :is-open="showEditModal"
      :user="currentUser"
      @close="showEditModal = false"
      @updated="handleUpdated"
    />

    <ConfirmModal
      :is-open="showConfirmModal"
      title="Hapus Pengguna"
      message="Apakah kamu yakin ingin menghapus pengguna ini? Aksi ini tidak bisa dibatalkan."
      @confirm="deleteUser"
      @cancel="showConfirmModal = false"
    />
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

// Import komponen modal
import CreateModal from './Components/CreateModal.vue'
import EditModal from './Components/EditModal.vue'
import ConfirmModal from './Components/ConfirmModal.vue'

// --- State ---
const userList = ref([]) 
const loading = ref(false)
const errorMessage = ref('')

const showCreateModal = ref(false)
const showEditModal = ref(false)
const showConfirmModal = ref(false)
const currentUser = ref(null) 
const currentIdToDelete = ref(null)

// --- Konfigurasi API ---
// Asumsi API endpoint untuk User adalah /api/users
const API_URL = 'http://127.0.0.1:8000/api/users'

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
const fetchUsers = async () => {
    loading.value = true
    errorMessage.value = '' 
    try {
        const response = await axios.get(API_URL, getAuthHeader(true)) 
        // Asumsi data user ada di response.data.data
        userList.value = response.data.data
    } catch (error) {
        console.error('Error fetching users:', error)
        errorMessage.value = 'Gagal mengambil data pengguna. Cek koneksi atau token akses.'
    } finally {
        loading.value = false
    }
}

// CREATE Handlers
const handleCreated = async () => {
    await fetchUsers() 
    showCreateModal.value = false 
}

// UPDATE Handlers
const openEditModal = (user) => {
    // Copy objek agar perubahan di modal tidak langsung memengaruhi data tabel
    currentUser.value = { ...user } 
    showEditModal.value = true
}

const handleUpdated = async () => {
    await fetchUsers()
    showEditModal.value = false
}

// DELETE Handlers
const openConfirmModal = (id) => {
    currentIdToDelete.value = id
    showConfirmModal.value = true
}

const deleteUser = async () => {
    try {
        const url = `${API_URL}/${currentIdToDelete.value}`
        await axios.delete(url, getAuthHeader(true))
        
        // Refresh data
        await fetchUsers()
        
        showConfirmModal.value = false
        alert('Pengguna berhasil dihapus!')
    } catch (error) {
        console.error('Gagal hapus pengguna:', error)
        let msg = error.response?.data?.message || 'Gagal menghapus pengguna. Pastikan Anda memiliki hak akses.'
        alert(msg)
    }
}

onMounted(() => {
    fetchUsers()
})
</script>