<template>
  <div>
    <div class="mb-8 flex justify-between items-center">
      <h1 class="text-2xl font-bold text-gray-800">My Profile</h1>

      <!-- Edit Button -->
      <button
        v-if="!isEditing"
        @click="$emit('start-edit')"
        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition"
      >
        Edit Profile
      </button>
    </div>

    <!-- User Section -->
    <div class="mb-8">
      <h2 class="text-gray-600 text-sm font-semibold mb-3 tracking-wide">User</h2>
      <div class="flex items-start gap-4">
        <div class="flex-1">
          <label class="text-gray-500 text-xs flex flex-row items-start gap-2 mb-1"
            ><i class="fa-solid fa-user"></i> Name</label
          >

          <span v-if="!isEditing" class="text-gray-800 font-medium">{{ name }}</span>

          <input
            v-else
            v-model="localForm.name"
            type="text"
            class="w-full border rounded-lg px-3 py-2 text-sm"
          />

          <div class="border-b border-gray-300 mt-2"></div>
        </div>
      </div>
    </div>

    <!-- Contacts Section -->
    <div class="mb-8">
      <h2 class="text-gray-600 text-sm font-semibold mb-3 tracking-wide">Contacts</h2>
      <div class="space-y-4">
        <!-- Email -->
        <div>
          <label class="text-gray-500 text-xs flex flex-row items-start gap-2 mb-1"
            ><i class="fa-solid fa-message"></i> Email</label
          >
          <span v-if="!isEditing" class="text-gray-800 font-medium break-all">{{ email }}</span>
          <input
            v-else
            v-model="localForm.email"
            type="email"
            class="w-full border rounded-lg px-3 py-2 text-sm"
          />
          <div class="border-b border-gray-300 mt-2"></div>
        </div>

        <!-- Alamat -->
        <div>
          <label class="text-gray-500 text-xs flex flex-row items-start gap-2 mb-1"
            ><i class="fa-solid fa-location-dot"></i> Alamat</label
          >
          <span v-if="!isEditing" class="text-gray-800 font-medium">{{ alamat }}</span>
          <input
            v-else
            v-model="localForm.alamat"
            type="text"
            class="w-full border rounded-lg px-3 py-2 text-sm"
          />
          <div class="border-b border-gray-300 mt-2"></div>
        </div>

        <!-- No HP -->
        <div>
          <label class="text-gray-500 text-xs flex flex-row items-start gap-2 mb-1"
            ><i class="fa-solid fa-phone"></i> Mobile / Cell Phone</label
          >
          <span v-if="!isEditing" class="text-gray-800 font-medium">{{ no_hp }}</span>
          <input
            v-else
            v-model="localForm.no_hp"
            type="text"
            class="w-full border rounded-lg px-3 py-2 text-sm"
          />
          <div class="border-b border-gray-300 mt-2"></div>
        </div>
      </div>
    </div>

    <!-- Save / Cancel Buttons -->
    <div v-if="isEditing" class="flex gap-3 mt-6">
      <button
        @click="handleSave"
        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition"
      >
        Save Perubahan
      </button>
      <button
        @click="$emit('cancel')"
        class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition"
      >
        Cancel
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue'

const props = defineProps({
  name: String,
  email: String,
  alamat: String,
  no_hp: String,
  isEditing: Boolean,
  formProfile: Object, // initial form data from parent
})

// Gunakan local copy agar tidak langsung mutate props
// Inisialisasi dengan default value jika props.formProfile undefined
const localForm = ref({
  name: props.formProfile?.name || props.name || '',
  email: props.formProfile?.email || props.email || '',
  alamat: props.formProfile?.alamat || props.alamat || '',
  no_hp: props.formProfile?.no_hp || props.no_hp || '',
})

// Sync localForm jika formProfile berubah dari luar (misal setelah reset)
watch(
  () => props.formProfile,
  (newVal) => {
    localForm.value = { ...newVal }
  },
  { deep: true, immediate: true },
)

const emit = defineEmits(['start-edit', 'save', 'cancel'])

const handleSave = () => {
  console.log('ProfileTab - localForm before save:', localForm.value)
  emit('save', localForm.value)
}
</script>
