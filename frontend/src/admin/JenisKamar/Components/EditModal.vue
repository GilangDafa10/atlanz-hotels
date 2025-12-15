<template>
  <Teleport to="body">
    <Transition name="fade">
      <div v-if="isOpen" class="fixed inset-0 z-[9999] flex items-center justify-center px-4">
        <div class="fixed inset-0 bg-gray-900/75 backdrop-blur-sm" @click="close"></div>

        <div
          class="bg-white rounded-2xl shadow-xl w-full max-w-lg p-6 relative z-10 overflow-y-auto max-h-[90vh] scrollbar-hide"
        >
          <div class="flex justify-between items-center mb-6 border-b pb-4">
            <h3 class="text-xl font-bold text-gray-800">Edit Jenis Kamar</h3>
            <button @click="close" class="text-gray-400 hover:text-gray-600 transition">
              <span class="text-2xl">&times;</span>
            </button>
          </div>

          <form @submit.prevent="submitEdit" class="space-y-4">
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-1">Jenis Kasur *</label>
              <input
                v-model="form.jenis_kasur"
                type="text"
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 outline-none transition"
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
                  class="w-full border border-gray-300 rounded-lg pl-10 pr-4 py-2 focus:ring-2 focus:ring-blue-500 outline-none transition"
                  required
                />
              </div>
            </div>

            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-1">Deskripsi</label>
              <textarea
                v-model="form.deskripsi"
                rows="3"
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 outline-none transition"
              ></textarea>
            </div>

            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-1">Gambar</label>

              <div class="mb-3">
                <img
                  v-if="newPreview"
                  :src="newPreview"
                  class="w-full h-40 object-cover rounded-lg border shadow-sm"
                  alt="New Preview"
                />
                <img
                  v-else-if="existingPreview"
                  :src="existingPreview"
                  class="w-full h-40 object-cover rounded-lg border shadow-sm"
                  alt="Existing"
                />
                <div
                  v-else
                  class="w-full h-40 bg-gray-100 rounded-lg flex items-center justify-center text-gray-400 border border-dashed border-gray-300"
                >
                  Tidak ada gambar
                </div>
              </div>

              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Fasilitas *</label>

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

              <input
                type="file"
                accept="image/*"
                @change="handleFileChange"
                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition"
              />
              <p class="text-xs text-gray-500 mt-1">
                *Biarkan kosong jika tidak ingin mengubah gambar.
              </p>
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
                {{ isLoading ? 'Menyimpan...' : 'Simpan Perubahan' }}
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
  room: { type: Object, default: null }, // Menerima objek 'room' dari index.vue
})

const emit = defineEmits(['close', 'updated'])

const form = ref({
  id: null,
  jenis_kasur: '',
  harga_permalam: null,
  deskripsi: '',
})

const existingPreview = ref(null)
const newFile = ref(null)
const newPreview = ref(null)
const isLoading = ref(false)
const fasilitasList = ref([])
const selectedFasilitas = ref([])

const API_BASE = 'http://127.0.0.1:8000/api'

// ✅ REVISI WATCH: Memastikan properti objek 'r' sesuai dengan yang dikirim dari index.vue
watch(
  () => props.room,
  async (r) => {
    if (!r || !r.id_jenis_kamar) {
      selectedFasilitas.value = []
      return
    }

    // isi form seperti sebelumnya
    form.value.id = r.id_jenis_kamar
    form.value.jenis_kasur = r.jenis_kasur || ''
    form.value.harga_permalam = r.harga_permalam
    form.value.deskripsi = r.deskripsi || ''

    existingPreview.value = r.url_gambar ? `${API_BASE}/${r.url_gambar}` : null

    // 🔥 Load daftar fasilitas dari API
    await loadFasilitas()

    // 🔥 Checklist fasilitas yang sudah ada pada kamar
    selectedFasilitas.value = r.fasilitas?.map((f) => f.id_fasilitas) || []
  },
  { immediate: true },
)

const loadFasilitas = async () => {
  try {
    const res = await axios.get(`${API_BASE}/fasilitas`)
    fasilitasList.value = res.data.data ?? []
  } catch (err) {
    console.error('Gagal mengambil fasilitas:', err)
  }
}

const handleFileChange = (e) => {
  const f = e.target.files && e.target.files[0]
  if (!f) return
  newFile.value = f
  newPreview.value = URL.createObjectURL(f)
}

const close = () => {
  // Membersihkan input file saat modal ditutup
  newFile.value = null
  newPreview.value = null
  emit('close')
}

const submitEdit = async () => {
  isLoading.value = true
  try {
    const token = localStorage.getItem('token')
    if (!token) throw new Error('Token not found.')

    const fd = new FormData()
    // ✅ Method Spoofing
    fd.append('_method', 'PUT')

    // Pastikan field yang dikirim sesuai dengan API backend (misal: Laravel)
    fd.append('jenis_kasur', form.value.jenis_kasur)
    fd.append('harga_permalam', form.value.harga_permalam)
    fd.append('deskripsi', form.value.deskripsi ?? '')

    if (newFile.value) {
      fd.append('url_gambar', newFile.value)
    }

    // Kirim fasilitas yang dipilih
    selectedFasilitas.value.forEach((id) => {
      fd.append('fasilitas[]', id)
    })

    // ✅ Menggunakan POST ke endpoint update
    await axios.post(`${API_BASE}/jenis-kamar/${form.value.id}`, fd, {
      headers: {
        Authorization: `Bearer ${token}`,
        // Penting untuk FormData
        'Content-Type': 'multipart/form-data',
      },
    })

    alert('Data berhasil diupdate.')
    emit('updated')
    close()
  } catch (err) {
    console.error('edit error:', err.response?.data || err.message || err)
    // Memberikan pesan error yang lebih detail jika ada dari server
    const serverMessage = err.response?.data?.message || 'Terjadi kesalahan saat menyimpan data.'
    alert(`Gagal mengupdate data: ${serverMessage}`)
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
