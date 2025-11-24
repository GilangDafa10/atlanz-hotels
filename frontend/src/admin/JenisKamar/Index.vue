  <template>
    <div class="p-6">
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Jenis Kamar</h1>
        <button
          @click="showCreateModal = true"
          class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition"
        >
          + Tambah Jenis Kamar
        </button>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <RoomCard
          v-for="room in rooms"
          :key="room.id"
          :room="room"
          @edit="openEditModal(room)"
          @delete="openConfirmModal(room.id)"
        />
      </div>

      <!-- Modal Create -->
      <CreateModal
        :is-open="showCreateModal"
        @close="showCreateModal = false"
        @create="addRoom"
      />

      <!-- Modal Edit -->
      <EditModal
        :is-open="showEditModal"
        :room="currentRoom"
        @close="showEditModal = false"
        @update="updateRoom"
      />

      <!-- Modal Konfirmasi Hapus -->
      <ConfirmModal
        :is-open="showConfirmModal"
        title="Hapus Jenis Kamar"
        message="Apakah kamu yakin ingin menghapus jenis kamar ini? Aksi ini tidak bisa dibatalkan."
        @confirm="deleteRoom"
        @cancel="showConfirmModal = false"
      />
    </div>
  </template>

  <script setup>
  import { ref } from 'vue'
  import RoomCard from './Components/RoomCard.vue'
  import CreateModal from './Components/CreateModal.vue'
  import EditModal from './Components/EditModal.vue'
  import ConfirmModal from './Components/ConfirmModal.vue'

  const rooms = ref([
    {
      id: 1,
      name: 'Twin Bed',
      price: 'Rp.999.999',
      description: 'Kamar dengan 2 tempat tidur single yang nyaman',
      image: new URL('@/assets/image8.png', import.meta.url).href
    },
    {
      id: 2,
      name: 'Double Bed',
      price: 'Rp.999.999',
      description: 'Kamar dengan 1 tempat tidur double yang luas',
      image: new URL('@/assets/image9.png', import.meta.url).href
    },
    {
      id: 3,
      name: 'King Bed',
      price: 'Rp.999.999',
      description: 'Kamar suite mewah dengan tempat tidur king size',
      image: new URL('@/assets/image10.png', import.meta.url).href
    }
  ])

  const showCreateModal = ref(false)
  const showEditModal = ref(false)
  const showConfirmModal = ref(false)
  const currentRoom = ref(null)
  const currentIdToDelete = ref(null)

  const addRoom = (newRoom) => {
    const newId = Math.max(...rooms.value.map(r => r.id), 0) + 1
    rooms.value.push({ id: newId, ...newRoom })
    showCreateModal.value = false
  }

  const openEditModal = (room) => {
    currentRoom.value = room
    showEditModal.value = true
  }

  const updateRoom = (updatedRoom) => {
    const index = rooms.value.findIndex(r => r.id === updatedRoom.id)
    if (index !== -1) {
      rooms.value[index] = updatedRoom
    }
    showEditModal.value = false
  }

  const openConfirmModal = (id) => {
    currentIdToDelete.value = id
    showConfirmModal.value = true
  }

  const deleteRoom = () => {
    rooms.value = rooms.value.filter(room => room.id !== currentIdToDelete.value)
    showConfirmModal.value = false
  }
  </script>