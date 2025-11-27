<template>
  <Teleport to="body">
    <div v-if="isOpen" class="fixed inset-0 bg-black/50 flex items-center justify-center z-[9999]" @click.self="close">
      <div class="bg-white rounded-lg shadow-xl max-w-lg w-full p-6">
        <div class="flex justify-between items-center mb-4 border-b pb-3">
          <h3 class="text-xl font-bold">Tambah Pengguna Baru</h3>
          <button @click="close" class="text-gray-500 hover:text-gray-700 text-xl">&times;</button>
        </div>

        <form @submit.prevent="submit" class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nama *</label>
            <input v-model="form.name" type="text" required class="w-full px-3 py-2 border rounded-md" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
            <input v-model="form.email" type="email" required class="w-full px-3 py-2 border rounded-md" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Password *</label>
            <input v-model="form.password" type="password" required class="w-full px-3 py-2 border rounded-md" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Role *</label>
            <select v-model="form.role" required class="w-full px-3 py-2 border rounded-md">
                <option value="customer">Customer</option>
                <option value="admin">Admin</option>
            </select>
          </div>
          
          <div v-if="errorMsg" class="text-red-600 text-sm font-semibold">{{ errorMsg }}</div>

          <div class="flex justify-end gap-3 pt-4 border-t">
            <button type="button" @click="close" class="px-4 py-2 border rounded-md">Batal</button>
            <button type="submit" :disabled="isLoading" class="px-4 py-2 bg-blue-600 text-white rounded-md disabled:opacity-50">
                {{ isLoading ? 'Menyimpan...' : 'Simpan' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </Teleport>
</template>

<script setup>
import { ref } from 'vue'
import axios from 'axios'

const props = defineProps({
  isOpen: Boolean,
})

const emit = defineEmits(['close', 'created'])

const form = ref({
  name: '',
  email: '',
  password: '',
  role: 'customer', // Default role
})

const isLoading = ref(false)
const errorMsg = ref('')
const API_URL = 'http://127.0.0.1:8000/api/users'

const getAuthHeader = () => {
    const token = localStorage.getItem('token') 
    if (!token) throw new Error("Token not found.") 
    return { headers: { Authorization: `Bearer ${token}` } }
}

const submit = async () => {
  isLoading.value = true
  errorMsg.value = ''
  try {
    await axios.post(API_URL, form.value, getAuthHeader())
    
    // Reset form
    form.value = { name: '', email: '', password: '', role: 'customer' }
    
    emit('created')
  } catch (error) {
    console.error('Create user error:', error.response?.data || error)
    errorMsg.value = error.response?.data?.message || 'Gagal menambahkan pengguna.'
  } finally {
    isLoading.value = false
  }
}

const close = () => {
    form.value = { name: '', email: '', password: '', role: 'customer' }
    errorMsg.value = ''
    emit('close')
}
</script>