<template>
  <div class="flex h-screen bg-[#f3f7fc] overflow-hidden">
    
    <aside class="w-60 bg-[#0c2c67] text-white px-5 py-6 flex flex-col flex-shrink-0 sticky top-0 h-full overflow-y-auto">
      <h1 class="text-4xl font-bold text-white mb-10 tracking-wide ml-3">ATLANZ</h1>

      <nav class="space-y-2">
        <RouterLink
          v-for="item in menu"
          :key="item.name"
          :to="item.to"
          class="flex items-center box-content gap-3 px-4 py-3 w-full rounded-l-xl rounded-r-none font-medium transition text-sm mr-[-1.25rem]"
          :class="{
            'bg-[#f3f7fc] text-[#0d3b66]': $route.path.startsWith(item.to),
            'hover:bg-[#f3f7fc] hover:text-[#0d3b66]': !$route.path.startsWith(item.to)
          }"
        >
          <component :is="item.icon" class="w-5 h-5" />
          {{ item.name }}
        </RouterLink>
      </nav>
    </aside>

    <div class="flex-1 flex flex-col overflow-y-auto">
      
      <header class="bg-white px-8 py-4 border-b flex justify-end items-center gap-3 sticky top-0 z-10 shadow-sm">
        <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold">
          S
        </div>
        <p class="font-semibold">Salman Faris</p>
      </header>

      <main class="p-8 flex-1">
        <RouterView />
      </main>
    </div>
  </div>
</template>

<script setup>
// Tambahkan Package dan Utensils (atau icon lain yang relevan)
import { Home, BedDouble, Users, NotebookPen, HelpCircle, Settings, Package, Utensils } from "lucide-vue-next"; 

const menu = [
  { name: "Dashboard", to: "/dashboard", icon: Home },
  { name: "Jenis Kamar", to: "/JenisKamar", icon: BedDouble },
  { name: "Kamar", to: "/Kamar", icon: BedDouble },
  { name: "Fasilitas", to: "/Fasilitas", icon: NotebookPen },
  // Tambahkan item baru di sini
  { name: "Additional Services", to: "/AdditionalServices", icon: Package }, 
  { name: "Users", to: "/users", icon: Users },
  { name: "Booking Details", to: "/booking-details", icon: NotebookPen },
  { name: "Help", to: "/help", icon: HelpCircle },
];
</script>