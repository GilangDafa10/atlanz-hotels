<template>
  <div class="p-6">
    <h1 class="text-2xl font-bold mb-6">Manajemen Pengguna (Users)</h1>

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
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-if="userList.length === 0">
            <td colspan="4" class="px-6 py-4 text-center text-gray-500">
              Belum ada data pengguna.
            </td>
          </tr>
          <tr v-for="user in userList" :key="user.id">
            <td class="px-6 py-4 whitespace-nowrap">{{ user.id }}</td>
            <td class="px-6 py-4 whitespace-nowrap font-medium">{{ user.name }}</td>
            <td class="px-6 py-4 whitespace-nowrap">{{ user.email }}</td>
            <td class="px-6 py-4 whitespace-nowrap">
              <select 
                v-model="user.id_role" 
                @change="updateRole(user)"
                :disabled="isUpdatingRole === user.id"
                class="w-full px-2 py-1 border rounded-md text-sm focus:ring-2 focus:ring-blue-500 cursor-pointer"
                :class="{'opacity-50 cursor-not-allowed': isUpdatingRole === user.id}"
              >
                <option :value="1">Admin</option>
                <option :value="2">User</option>
              </select>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import Swal from 'sweetalert2' // Import SweetAlert2

const userList = ref([])
const loading = ref(false)
const errorMessage = ref('')
const isUpdatingRole = ref(null)

const API_URL = 'http://127.0.0.1:8000/api/users'

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

const fetchUsers = async () => {
  loading.value = true
  errorMessage.value = ''
  try {
    const response = await axios.get(API_URL, getAuthHeader(true))
    // Pastikan mapping data sesuai respons API
    userList.value = response.data.data
  } catch (error) {
    console.error('Error fetching users:', error)
    errorMessage.value = error.response?.data?.message || 'Gagal mengambil data pengguna.'
  } finally {
    loading.value = false
  }
}

const updateRole = async (user) => {
  // Kunci select option agar user tidak klik berulang kali
  isUpdatingRole.value = user.id
  
  // Simpan role lama untuk berjaga-jaga jika gagal (Rollback logic)
  // Jika user memilih 1 (Admin), role sebelumnya pasti bukan 1 (misal 2), dan sebaliknya.
  const previousRole = user.id_role === 1 ? 2 : 1; 

  try {
    const url = `${API_URL}/${user.id}`
    
    // Payload sesuai request body: { "id_role": "1" }
    const payload = { 
      id_role: String(user.id_role) 
    }
    
    await axios.put(url, payload, getAuthHeader())
    
    // Tentukan label untuk pesan sukses
    const roleName = user.id_role === 1 ? 'Admin' : 'User';

    // --- SWEETALERT SUKSES ---
    Swal.fire({
      icon: 'success',
      title: 'Berhasil!',
      text: `Role ${user.name} berhasil diubah menjadi ${roleName}`,
      timer: 2000,        // Alert menutup otomatis dalam 2 detik
      showConfirmButton: false,
      toast: true,        // Opsi toast: Tampil kecil di pojok (opsional, hapus jika ingin alert tengah)
      position: 'top-end' // Posisi di pojok kanan atas
    });
    
  } catch (error) {
    console.error('Update role error:', error)
    
    // --- ROLLBACK: Kembalikan tampilan ke nilai lama ---
    user.id_role = previousRole 
    
    // --- SWEETALERT ERROR ---
    Swal.fire({
      icon: 'error',
      title: 'Gagal',
      text: error.response?.data?.message || 'Terjadi kesalahan saat memperbarui role.',
    });
  } finally {
    // Buka kunci select option
    isUpdatingRole.value = null
  }
}

onMounted(() => {
  fetchUsers()
})
</script>