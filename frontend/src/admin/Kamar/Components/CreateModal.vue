<template>
  <Teleport to="body">
    <Transition name="fade">
      <div v-if="isOpen" class="fixed inset-0 z-[9999] flex items-center justify-center px-4">
        <div class="fixed inset-0 bg-gray-900/75 backdrop-blur-sm" @click="close"></div>

        <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg p-6 relative z-10">
          <div class="flex justify-between items-center mb-6 border-b pb-4">
            <h3 class="text-xl font-bold text-gray-800">Tambah Kamar Baru</h3>
            <button @click="close" class="text-gray-400 hover:text-gray-600 transition">
              <span class="text-2xl">&times;</span>
            </button>
          </div>

          <form @submit.prevent="submitCreate" class="space-y-4">
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-1">Nomor Kamar *</label>
              <input 
                v-model="form.nomor_kamar" 
                type="text" 
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 outline-none transition" 
                required 
              />
            </div>

            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-1">Tipe Kamar *</label>
              <select 
                v-model="form.id_jenis_kamar" 
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 outline-none transition" 
                required
                :disabled="loadingJenisKamar"
              >
                <option :value="null" disabled>
                  {{ loadingJenisKamar ? 'Memuat Tipe Kamar...' : 'Pilih Jenis Kamar' }}
                </option>
                <option 
                  v-for="jenis in jenisKamarList" 
                  :key="jenis.id_jenis_kamar" 
                  :value="jenis.id_jenis_kamar"
                >
                  {{ jenis.jenis_kasur }} (Rp {{ Number(jenis.harga_permalam).toLocaleString('id-ID') }})
                </option>
              </select>
            </div>
            
            <div class="flex justify-end gap-3 pt-4 border-t mt-4">
              <button type="button" @click="close" class="px-5 py-2.5 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition">Batal</button>
              <button type="submit" :disabled="isLoading || loadingJenisKamar" class="px-5 py-2.5 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition disabled:opacity-50 flex items-center gap-2">
                 <span v-if="isLoading" class="animate-spin h-4 w-4 border-2 border-white border-t-transparent rounded-full"></span>
                 {{ isLoading ? 'Memproses...' : 'Tambah Kamar' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </Transition>
  </Teleport>
</template>

<script setup>
import { ref, watch, onMounted } from "vue";
import axios from "axios";

const props = defineProps({
  isOpen: { type: Boolean, default: false },
});

const emit = defineEmits(["close", "create"]);

const form = ref({
  // NAMA FIELD KUNCI (sesuai KamarStoreRequest Laravel)
  nomor_kamar: "",
  id_jenis_kamar: null, 
});

const isLoading = ref(false);
const jenisKamarList = ref([]);
const loadingJenisKamar = ref(false);

const API_BASE = "http://127.0.0.1:8000/api";

// Fetch Jenis Kamar dari endpoint publik
const fetchJenisKamar = async () => {
  loadingJenisKamar.value = true;
  try {
    const res = await axios.get(`${API_BASE}/jenis-kamar`);
    jenisKamarList.value = res.data.data;
  } catch (err) {
    console.error("Gagal fetch jenis kamar:", err);
  } finally {
    loadingJenisKamar.value = false;
  }
};

onMounted(() => {
    fetchJenisKamar();
});

// Reset form dan refresh data saat modal dibuka
watch(
  () => props.isOpen,
  (newVal) => {
    if (newVal) {
        form.value.nomor_kamar = "";
        form.value.id_jenis_kamar = null;
        if (jenisKamarList.value.length === 0) {
            fetchJenisKamar();
        }
    }
  }
);

const close = () => {
  emit("close");
};

const submitCreate = () => {
  isLoading.value = true;
  
  // Emit payload dengan key yang benar ke index.vue
  const payload = {
    nomor_kamar: form.value.nomor_kamar,
    id_jenis_kamar: form.value.id_jenis_kamar,
  };

  emit("create", payload);
  
  // index.vue akan menutup modal jika sukses
  isLoading.value = false; 
};
</script>

<style scoped>
.fade-enter-active, .fade-leave-active { transition: opacity 0.3s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
/* Style untuk transisi pada Modal */
.scale-95 { transform: scale(0.95); }
.scale-100 { transform: scale(1); }
</style>