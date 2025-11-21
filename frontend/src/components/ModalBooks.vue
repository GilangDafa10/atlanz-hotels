<template>
  <!-- BACKDROP -->
  <transition name="fade">
    <div
      v-if="show"
      class="fixed inset-0 pb-72 bg-black/30 flex justify-center items-center z-[999]"
      @click.self="close"
    >
      <transition name="modal">
        <!-- CARD -->
        <div v-if="show" class="bg-[#0f2344] w-full max-w-4xl rounded-xl p-4 text-white">
          <!-- FORM GRID -->
          <div class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
            <div>
              <label class="block mb-1 text-sm">Check-in</label>
              <input
                type="date"
                v-model="checkIn"
                class="w-full px-3 py-2 rounded bg-white text-black"
              />
            </div>

            <div>
              <label class="block mb-1 text-sm">Check-out</label>
              <input
                type="date"
                v-model="checkOut"
                class="w-full px-3 py-2 rounded bg-white text-black"
              />
            </div>

            <div>
              <label class="block mb-1 text-sm">Jumlah Kamar</label>
              <select v-model="rooms" class="w-full px-3 py-2 rounded bg-white text-black">
                <option v-for="n in 10" :key="n" :value="n">{{ n }}</option>
              </select>
            </div>

            <!-- BUTTON -->
            <div>
              <button
                @click="submitBooking"
                class="w-full bg-[#c8a349] px-8 py-2 text-white font-semibold hover:opacity-80"
              >
                CARI KAMAR
              </button>
            </div>
          </div>
        </div>
      </transition>
    </div>
  </transition>
</template>

<script setup>
import { ref, watch } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()

const props = defineProps({ show: Boolean })
const emit = defineEmits(['close'])

const checkIn = ref('')
const checkOut = ref('')
const rooms = ref(1)

const close = () => emit('close')

const submitBooking = () => {
  // Redirect ke halaman rooms dengan query
  
  router.push({
    path: '/cari-kamar',
    query: {
      checkIn: checkIn.value,
      checkOut: checkOut.value,
      rooms: rooms.value,
    },
  })

  close()
}

watch(
  () => props.show,
  (isOpen) => {
    if (isOpen) {
      // Scrollbar tetap ada, tapi konten tidak bisa digerakkan
      document.body.style.overflow = 'hidden'
    } else {
      document.body.style.overflow = 'auto'
    }
  },
)
</script>

<style scoped>
/* BACKDROP FADE */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

/* MODAL FADE + SLIDE */
.modal-enter-active,
.modal-leave-active {
  transition:
    opacity 0.3s ease,
    transform 0.3s ease;
}
.modal-enter-from,
.modal-leave-to {
  opacity: 0;
  transform: translateY(-10px);
}
</style>
