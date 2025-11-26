<template>
  <Teleport to="body">
    <Transition name="fade">
      <div v-if="isOpen" class="fixed inset-0 z-[9999] flex items-center justify-center px-4">
        <div class="fixed inset-0 bg-gray-900/75 backdrop-blur-sm" @click="close"></div>

        <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg p-6 relative z-10">
          <div class="flex justify-between items-center mb-6 border-b pb-4">
            <h3 class="text-xl font-bold text-gray-800">Edit Kamar: {{ initialForm.nomor_kamar }}</h3>
            <button @click="close" class="text-gray-400 hover:text-gray-600 transition">
              <span class="text-2xl">&times;</span>
            </button>
          </div>

          <form @submit.prevent="submitUpdate" class="space-y-4">
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
              <button type="submit" :disabled="isLoading || !isFormDirty" class="px-5 py-2.5 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition disabled:opacity-50 flex items-center gap-2">
                 <span v-if="isLoading" class="animate-spin h-4 w-4 border-2 border-white border-t-transparent rounded-full"></span>
                 {{ isLoading ? 'Memperbarui...' : 'Simpan Perubahan' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </Transition>
  </Teleport>
</template>

<script setup>
import { ref, watch, onMounted, computed } from "vue";
import axios from "axios";

const props = defineProps({
  isOpen: { type: Boolean, default: false },
  // Menerima data kamar asli yang di-load dari API (originalData)
  kamar: { type: Object, default: () => ({}) }, 
});

const emit = defineEmits(["close", "update"]);

// --- STATE FORM ---
const initialForm = ref({}); // Menyimpan data awal untuk perbandingan
const form = ref({
  // NAMA FIELD KUNCI (sesuai KamarStoreRequest Laravel)
  id_kamar: null, // Penting untuk URL PUT
  nomor_kamar: "",
  id_jenis_kamar: null, 
});

const isLoading = ref(false);
const jenisKamarList = ref([]);
const loadingJenisKamar = ref(false);

const API_BASE = "http://127.0.0.1:8000/api";

// Cek apakah form sudah diubah
const isFormDirty = computed(() => {
    return (
        form.value.nomor_kamar !== initialForm.value.nomor_kamar ||
        form.value.id_jenis_kamar !== initialForm.value.id_jenis_kamar
    );
});

// 🔑 Fetch Jenis Kamar untuk Dropdown
const fetchJenisKamar = async () => {
  loadingJenisKamar.value = true;
  try {
    // Endpoint /jenis-kamar bersifat PUBLIC
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

// Sinkronisasi data saat props.kamar berubah (ketika modal dibuka)
watch(
  () => props.kamar,
  (newKamar) => {
    if (newKamar && newKamar.id_kamar) {
        // Map data props ke form state
        form.value.id_kamar = newKamar.id_kamar;
        form.value.nomor_kamar = newKamar.nomor_kamar;
        form.value.id_jenis_kamar = newKamar.id_jenis_kamar;
        
        // Simpan data awal untuk cek perubahan
        initialForm.value = { ...form.value };
    }
  },
  { immediate: true }
);

const close = () => {
  emit("close");
};

const submitUpdate = () => {
  if (!isFormDirty.value) {
    alert("Tidak ada perubahan yang dilakukan.");
    return;
  }
  
  isLoading.value = true;
  
  // 1. Buat payload yang dibutuhkan API (harus menyertakan ID untuk identifikasi)
  const payload = {
    id_kamar: form.value.id_kamar,
    nomor_kamar: form.value.nomor_kamar,
    id_jenis_kamar: form.value.id_jenis_kamar,
  };

  // 2. Emit payload ke index.vue untuk proses PUT API
  emit("update", payload);
  
  // index.vue yang akan mengatur isLoading dan close()
  isLoading.value = false; 
};
</script>

<style scoped>
.fade-enter-active, .fade-leave-active { transition: opacity 0.3s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
</style>  