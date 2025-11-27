<template>
  <div class="p-6 bg-white shadow-xl rounded-xl border border-gray-100 min-h-screen">
    
    <header class="flex justify-between items-center pb-4 mb-6 border-b border-gray-200">
      <h2 class="text-3xl font-extrabold text-[#0d3b66]">
        Daftar Layanan Tambahan
      </h2>

      <button 
        @click="openCreateModal"
        class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-5 rounded-lg shadow-md transition duration-300 ease-in-out transform hover:scale-[1.02] flex items-center gap-2"
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
        </svg>
        Tambah Layanan Baru
      </button>
    </header>

    <div v-if="loading" class="text-center py-12 text-gray-500 text-lg">
      <svg class="animate-spin h-6 w-6 mr-3 inline-block text-indigo-500" viewBox="0 0 24 24"></svg>
      Memuat data...
    </div>

    <div v-else-if="errorMessage" class="bg-red-50 border-l-4 border-red-400 text-red-700 p-4 rounded mb-8 shadow-sm">
      <p class="font-bold">Error!</p>
      <p>{{ errorMessage }}</p>
    </div>

    <div v-else>
      <div v-if="services.length === 0" class="text-center py-20 text-gray-400 border-2 border-dashed border-gray-200 rounded-xl">
        <p class="text-xl font-medium mb-2">Belum ada layanan yang ditambahkan.</p>
        <p>Gunakan tombol "Tambah Layanan Baru" di atas untuk memulai.</p>
      </div>

      <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
        <div 
          v-for="service in services" 
          :key="service.id" 
          class="bg-white rounded-xl shadow-lg overflow-hidden transition-all duration-300 transform hover:shadow-2xl hover:-translate-y-1 group"
        >
          <div class="h-40 w-full overflow-hidden">
            <img 
              :src="service.imageUrl" 
              :alt="service.name" 
              class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-[1.05]"
              @error="$event.target.src = 'https://via.placeholder.com/600x400?text=No+Image'"
            />
          </div>

          <div class="p-4 flex flex-col justify-between h-auto">
            <div>
              <h3 class="text-xl font-bold text-gray-800 mb-1 leading-tight">
                {{ service.name }}
              </h3>
              
              <p class="text-base font-bold text-green-600 mb-3">
                {{ service.priceFormatted }}
              </p>
              
              <p class="text-sm text-gray-500 mb-4 line-clamp-3">
                {{ service.description }}
              </p>
            </div>

            <div class="flex justify-end space-x-4 pt-3 border-t border-gray-100">
              <button 
                @click="openEditModal(service)" 
                class="text-indigo-600 hover:text-indigo-800 font-semibold text-sm transition duration-150 ease-in-out"
              >
                Edit
              </button>

              <button 
                @click="openConfirmModal(service.id)" 
                class="text-red-600 hover:text-red-800 font-semibold text-sm transition duration-150 ease-in-out"
              >
                Hapus
              </button>
            </div>
          </div>
        </div>
      </div>
      </div>

    <CreateModal 
      :isOpen="isCreateModalOpen" 
      @close="closeModals" 
      @create="handleCreate" 
    />
    
    <EditModal 
      :isOpen="isEditModalOpen" 
      :serviceData="selectedService" 
      @close="closeModals" 
      @update="handleUpdate" 
    />
    
    <ConfirmModal 
      :isOpen="isConfirmModalOpen" 
      :serviceId="serviceToDeleteId" 
      @close="closeModals" 
      @confirm="handleDelete" 
    />
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import CreateModal from './Components/CreateModal.vue';
import EditModal from './Components/EditModal.vue';
import ConfirmModal from './Components/ConfirmModal.vue';

// STATE
const isCreateModalOpen = ref(false);
const isEditModalOpen = ref(false);
const isConfirmModalOpen = ref(false);

const selectedService = ref(null);
const serviceToDeleteId = ref(null);
const services = ref([]);
const loading = ref(false);
const errorMessage = ref('');

// CONFIG
const API_BASE = "http://127.0.0.1:8000/api";
const BASE_URL = "http://127.0.0.1:8000";

const getAuthHeader = () => {
  const token = localStorage.getItem("token");

  if (!token) {
    errorMessage.value = "Token tidak ditemukan. Silakan login kembali.";
    throw new Error("Token not found");
  }

  return {
    headers: {
      Authorization: `Bearer ${token}`,
      Accept: "application/json",
    }
  };
};

// FETCH DATA
const fetchServices = async () => {
  loading.value = true;
  errorMessage.value = "";

  try {
    const res = await axios.get(`${API_BASE}/additional-service`, getAuthHeader());

    services.value = res.data.data.map(item => {
      // Logic untuk membersihkan path gambar
      let path = item.url_gambar || '';
      path = path.replace('public/', ''); 
      if (!path.startsWith('storage/') && path) {
          path = `storage/${path}`; 
      }
      
      const imageUrl = item.url_gambar 
          ? `${BASE_URL}/${path}` 
          : 'https://via.placeholder.com/600x400?text=No+Image';

      return {
        id: item.id_service || item.id,
        name: item.nama_service,
        price: item.harga_service,
        priceFormatted: `Rp ${Number(item.harga_service).toLocaleString("id-ID")}`,
        description: item.deskripsi_service,
        imageUrl: imageUrl,
        originalData: { ...item }
      };
    });

  } catch (err) {
    console.error("Fetch Error:", err.response || err);

    if (err.message === "Token not found") {
      errorMessage.value = "Otentikasi gagal. Silakan login ulang.";
    } else {
      errorMessage.value = "Gagal mengambil data layanan.";
    }
  } finally {
    loading.value = false;
  }
};

// CREATE
const handleCreate = async (formData) => {
  try {
    await axios.post(`${API_BASE}/additional-service`, formData, getAuthHeader());
    await fetchServices();
    closeModals();
    alert("Layanan berhasil ditambahkan!");
  } catch (err) {
    console.error("Create Error:", err.response || err);
    alert("Gagal menambah layanan.");
  }
};

// UPDATE
const handleUpdate = async (payload) => {
  try {
    const url = `${API_BASE}/additional-service/${payload.id}?_method=PUT`;
    await axios.post(url, payload.data, getAuthHeader());

    await fetchServices();
    closeModals();
    alert("Layanan berhasil diperbarui!");
  } catch (err) {
    console.error("Update Error:", err.response || err);
    alert("Gagal memperbarui layanan.");
  }
};

// DELETE
const handleDelete = async () => {
  try {
    await axios.delete(`${API_BASE}/additional-service/${serviceToDeleteId.value}`, getAuthHeader());
    await fetchServices();
    closeModals();
    alert("Layanan dihapus.");
  } catch (err) {
    console.error("Delete Error:", err.response || err);
    alert("Gagal menghapus layanan.");
  }
};

// MODALS
const openCreateModal = () => (isCreateModalOpen.value = true);

const openEditModal = (service) => {
  // Mapping ulang data agar sesuai dengan prop yang diharapkan oleh EditModal
  selectedService.value = {
    id_service: service.id,
    nama_service: service.name,
    harga_service: service.price,
    deskripsi_service: service.description,
    url_gambar: service.imageUrl
  };

  isEditModalOpen.value = true;
};


const openConfirmModal = (id) => {
  serviceToDeleteId.value = id;
  isConfirmModalOpen.value = true;
};

const closeModals = () => {
  isCreateModalOpen.value = false;
  isEditModalOpen.value = false;
  isConfirmModalOpen.value = false;
  selectedService.value = null;
  serviceToDeleteId.value = null;
};

onMounted(fetchServices);
</script>