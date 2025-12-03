<template>
  <Teleport to="body">
    <Transition name="fade">
      <div v-if="isOpen" class="fixed inset-0 z-[9999] flex items-center justify-center px-4">
        <div class="fixed inset-0 bg-gray-900/75 backdrop-blur-sm" @click="close"></div>

        <div
          class="bg-white rounded-2xl shadow-xl w-full max-w-lg p-6 relative z-10 overflow-y-auto max-h-[90vh] scrollbar-hide"
        >
          <div class="flex justify-between items-center mb-6 border-b pb-4">
            <h3 class="text-xl font-bold text-gray-800">Tambah Jenis Kamar</h3>
            <button @click="close" class="text-gray-400 hover:text-gray-600 transition">
              <span class="text-2xl">&times;</span>
            </button>
          </div>

          <form @submit.prevent="submitForm" class="space-y-4">
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-1">Jenis Kasur *</label>
              <input
                v-model="form.jenis_kasur"
                type="text"
                placeholder="Contoh: King Size"
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                required
              />
            </div>

            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-1">Harga Permalam *</label>
              <div class="relative">
                <span class="absolute left-3 top-2 text-gray-500">Rp</span>
                <input
                  v-model.number="form.harga_permalam"
                  type="number"
                  placeholder="0"
                  class="w-full border border-gray-300 rounded-lg pl-10 pr-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                  required
                />
              </div>
            </div>

            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-1">Deskripsi</label>
              <textarea
                v-model="form.deskripsi"
                rows="3"
                placeholder="Deskripsi fasilitas kamar..."
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
              ></textarea>
            </div>

            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-1">Upload Gambar *</label>
              <input
                type="file"
                accept="image/*"
                @change="handleFileUpload"
                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition"
                required
              />

              <div v-if="previewImage" class="mt-3 relative group">
                <img
                  :src="previewImage"
                  class="w-full h-40 object-cover rounded-lg shadow-sm border"
                />
                <button
                  type="button"
                  @click="removeImage"
                  class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-1 shadow hover:bg-red-600 transition opacity-0 group-hover:opacity-100"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M6 18L18 6M6 6l12 12"
                    ></path>
                  </svg>
                </button>
              </div>
            </div>

            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2"
                >Fasilitas Tersedia *</label
              >

              <div class="grid grid-cols-2 gap-3 rounded-lg p-3">
                <label
                  v-for="item in fasilitasList"
                  :key="item.id_fasilitas"
                  class="flex items-center gap-2 text-sm cursor-pointer"
                >
                  <input
                    type="checkbox"
                    :value="item.id_fasilitas"
                    v-model="selectedFasilitas"
                    class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                  />
                  <span>{{ item.nama_fasilitas }}</span>
                </label>
              </div>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t mt-4">
              <button
                type="button"
                @click="close"
                class="px-5 py-2.5 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition"
              >
                Batal
              </button>
              <button
                type="submit"
                :disabled="isLoading"
                class="px-5 py-2.5 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition disabled:opacity-50 flex items-center gap-2"
              >
                <span
                  v-if="isLoading"
                  class="animate-spin h-4 w-4 border-2 border-white border-t-transparent rounded-full"
                ></span>
                {{ isLoading ? 'Menyimpan...' : 'Simpan' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </Transition>
  </Teleport>
</template>

<script setup>
import { ref, watch } from 'vue'
import axios from 'axios'

const props = defineProps({
  isOpen: { type: Boolean, default: false },
})

const emit = defineEmits(['close', 'created'])

const form = ref({
  jenis_kasur: '',
  harga_permalam: null,
  deskripsi: '',
})

const gambarFile = ref(null)
const previewImage = ref(null)
const isLoading = ref(false)

const API_BASE = 'http://127.0.0.1:8000/api'

const fasilitasList = ref([])
const selectedFasilitas = ref([])
watch(
  () => props.isOpen,
  async (val) => {
    if (val) {
      await getFasilitas()
    }
  },
)
const getFasilitas = async () => {
  try {
    const res = await axios.get(`${API_BASE}/fasilitas`)
    fasilitasList.value = res.data.data ?? []
  } catch (err) {
    console.error('Gagal load fasilitas:', err)
  }
}

const handleFileUpload = (e) => {
  const f = e.target.files && e.target.files[0]
  if (!f) return
  gambarFile.value = f
  previewImage.value = URL.createObjectURL(f)
}

const removeImage = () => {
  gambarFile.value = null
  previewImage.value = null
  // Reset file input value manually if needed via ref, but this is simple enough
}

const close = () => {
  form.value = { jenis_kasur: '', harga_permalam: null, deskripsi: '' }
  gambarFile.value = null
  previewImage.value = null
  isLoading.value = false
  emit('close')
}

const submitForm = async () => {
  if (isLoading.value) return
  isLoading.value = true
  try {
    const token = localStorage.getItem('token')
    if (!token) throw new Error('Token not found. Please login.')

    const fd = new FormData()
    fd.append('jenis_kasur', form.value.jenis_kasur)
    fd.append('harga_permalam', form.value.harga_permalam)
    fd.append('deskripsi', form.value.deskripsi ?? '')

    selectedFasilitas.value.forEach((id) => {
      fd.append('fasilitas[]', id)
    })

    if (gambarFile.value) {
      fd.append('url_gambar', gambarFile.value)
    }

    await axios.post(`${API_BASE}/jenis-kamar`, fd, {
      headers: {
        Authorization: `Bearer ${token}`,
        'Content-Type': 'multipart/form-data',
      },
    })

    emit('created')
    close()
  } catch (err) {
    console.error('create error:', err)
    alert('Gagal menambahkan data. Periksa koneksi atau token.')
  } finally {
    isLoading.value = false
  }
}
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

/* Hide scrollbar for Chrome, Safari and Opera */
.scrollbar-hide::-webkit-scrollbar {
  display: none;
}

/* Hide scrollbar for IE, Edge and Firefox */
.scrollbar-hide {
  -ms-overflow-style: none; /* IE and Edge */
  scrollbar-width: none; /* Firefox */
}
</style>
