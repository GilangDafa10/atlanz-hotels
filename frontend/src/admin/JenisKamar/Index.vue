<template>
  <div class="min-h-screen bg-gray-50 p-6 md:p-10">
    <div class="max-w-7xl mx-auto mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
      <div>
        <h1 class="text-3xl font-bold text-gray-900">Jenis Kamar</h1>
        <p class="text-gray-500 mt-1">Kelola daftar tipe kamar dan harga hotel.</p>
      </div>
      <button
        @click="showCreateModal = true"
        class="bg-blue-600 text-white px-5 py-2.5 rounded-xl shadow-lg hover:bg-blue-700 hover:shadow-xl transition flex items-center gap-2 font-medium"
      >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Tambah Jenis Kamar
      </button>
    </div>

    <div class="max-w-7xl mx-auto">
      <div v-if="loading" class="flex justify-center items-center py-20">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
      </div>

      <div v-else-if="rooms.length === 0" class="text-center py-20 bg-white rounded-2xl shadow-sm border border-dashed border-gray-300">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada data</h3>
        <p class="mt-1 text-sm text-gray-500">Mulai dengan menambahkan jenis kamar baru.</p>
      </div>

      <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <RoomCard
          v-for="room in rooms"
          :key="room.id"
          :room="room"
          @edit="openEditModal(room)"
          @delete="openConfirmModal(room.id)"
        />
      </div>
    </div>

    <CreateModal
      :is-open="showCreateModal"
      @close="showCreateModal = false"
      @created="handleCreated"
    />

    <EditModal
      :is-open="showEditModal"
      :room="currentRoom"
      @close="showEditModal = false"
      @updated="handleUpdated"
    />

    <ConfirmModal
      :is-open="showConfirmModal"
      title="Hapus Jenis Kamar"
      message="Apakah kamu yakin ingin menghapus jenis kamar ini? Data yang dihapus tidak dapat dikembalikan."
      @confirm="confirmDelete"
      @cancel="showConfirmModal = false"
    />
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";

// Perhatikan Path Import!
// Jika file berada satu folder dengan index.vue, gunakan ./
// GUNAKAN INI HANYA JIKA FILE ADA DI DALAM FOLDER "Components"
import RoomCard from "./Components/RoomCard.vue";
import CreateModal from "./Components/CreateModal.vue";
import EditModal from "./Components/EditModal.vue";
import ConfirmModal from "./Components/ConfirmModal.vue";

const rooms = ref([]);
const loading = ref(true);
const showCreateModal = ref(false);
const showEditModal = ref(false);
const showConfirmModal = ref(false);
const currentRoom = ref(null);
const currentIdToDelete = ref(null);

const API_BASE = "http://127.0.0.1:8000/api";

// Di dalam Index.vue

const fetchRooms = async () => {
  loading.value = true;
  try {
    const res = await axios.get(`${API_BASE}/jenis-kamar`);
    
    // Mapping data
    rooms.value = res.data.data.map((item) => ({
      id: item.id_jenis_kamar,
      name: item.jenis_kasur,
      price: `Rp ${Number(item.harga_permalam).toLocaleString("id-ID")}`,
      rawPrice: item.harga_permalam,
      description: item.deskripsi,
      
      // PENTING: Kirim nama file aslinya saja (misal: "gambar.jpg")
      // Jangan digabung dengan http di sini, karena RoomCard yang akan menggabungkannya
      url_gambar: item.url_gambar 
    }));
    
  } catch (e) {
    console.error("fetchRooms error:", e);
    rooms.value = [];
  } finally {
    loading.value = false;
  }
};

onMounted(fetchRooms);

const handleCreated = async () => {
  await fetchRooms();
  showCreateModal.value = false;
};

const openEditModal = (room) => {
  // ✅ REVISI: Mengubah format data agar sesuai dengan yang diharapkan oleh EditModal
  currentRoom.value = { 
    id_jenis_kamar: room.id, // ID dibutuhkan untuk URL PUT
    jenis_kasur: room.name,
    harga_permalam: room.rawPrice, // Menggunakan harga asli (angka)
    deskripsi: room.description,
    url_gambar: room.url_gambar,
  };
  showEditModal.value = true;
};

const handleUpdated = async () => {
  await fetchRooms();
  showEditModal.value = false;
};

const openConfirmModal = (id) => {
  currentIdToDelete.value = id;
  showConfirmModal.value = true;
};

const confirmDelete = async () => {
  try {
    const token = localStorage.getItem("token");
    if (!token) throw new Error("Token tidak ditemukan. Login terlebih dahulu.");

    await axios.delete(`${API_BASE}/jenis-kamar/${currentIdToDelete.value}`, {
      headers: { Authorization: `Bearer ${token}` },
    });

    await fetchRooms();
    showConfirmModal.value = false;
  } catch (e) {
    console.error("delete error:", e);
    alert("Gagal menghapus data.");
  }
};
</script>