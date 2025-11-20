<template>
  <section class="bg-[#0c2c67] min-h-screen py-10">
    <div class="w-[90%] max-w-6xl mx-auto flex flex-col lg:flex-row gap-8 lg:pl-8">
      <!-- BAGIAN KIRI (3 ROOM) -->
      <div class="flex-2 flex flex-col gap-8">
        <!-- === ROOM 1 === -->
        <div
          class="bg-white rounded-lg shadow-lg p-6 transition-all duration-500 hover:shadow-2xl hover:scale-[1.01]"
        >
          <h3 class="text-[#0c2c67] text-2xl font-semibold mb-1">Rp.999.999</h3>
          <p class="text-[#0c2c67] text-sm font-semibold mb-1 uppercase">
            Wednesday, Oct 15 - Thursday, Oct 16
          </p>
          <h2 class="italic font-semibold text-[#b7a34b] text-3xl mb-4">Hotel Atlanz Indonesia</h2>

          <div class="flex gap-4 mb-4 items-start">
            <img src="@/assets/book.png" alt="Room" class="w-[50%] rounded-md shadow-sm" />
            <p class="text-[#0c2c67] text-base font-medium">Breakfast in Bed Experience</p>
          </div>

          <h4 class="text-[#0c2c67] text-base font-semibold mb-3">Fasilitas :</h4>
          <div class="flex flex-col gap-2 text-[#0c2c67] text-[15px] leading-relaxed">
            <div class="flex items-center gap-2"><Icon icon="mdi:bed" class="text-lg" /> Bed</div>
            <div class="flex items-center gap-2">
              <Icon icon="mdi:wifi" class="text-lg" /> Wi-Fi
            </div>
            <div class="flex items-center gap-2">
              <Icon icon="mdi:shower" class="text-lg" /> Shower
            </div>
            <div class="flex items-center gap-2">
              <Icon icon="mdi:television" class="text-lg" /> TV
            </div>
            <div class="flex items-center gap-2">
              <Icon icon="mdi:toothbrush-paste" class="text-lg" /> Perlengkapan Mandi
            </div>
            <div class="flex items-center gap-2">
              <Icon icon="mdi:fridge" class="text-lg" /> Kulkas
            </div>
            <div class="flex items-center gap-2">
              <Icon icon="mdi:coffee-maker" class="text-lg" /> Mesin Pembuat Kopi
            </div>
            <div class="flex items-center gap-2">
              <Icon icon="mdi:iron" class="text-lg" /> Setrika
            </div>
          </div>
        </div>

        <!-- === ROOM 2 === -->
        <div
          class="bg-white rounded-lg shadow-lg p-6 transition-all duration-500 hover:shadow-2xl hover:scale-[1.01]"
        >
          <h3 class="text-[#0c2c67] text-2xl font-semibold mb-1">Rp.1.299.999</h3>
          <p class="text-[#0c2c67] text-sm font-semibold mb-1 uppercase">
            Thursday, Oct 16 - Friday, Oct 17
          </p>
          <h2 class="italic font-semibold text-[#b7a34b] text-3xl mb-4">Deluxe Room</h2>

          <div class="flex gap-4 mb-4 items-start">
            <img src="@/assets/book.png" alt="Room" class="w-[50%] rounded-md shadow-sm" />
            <p class="text-[#0c2c67] text-base font-medium">
              Spacious room with balcony & breakfast
            </p>
          </div>

          <h4 class="text-[#0c2c67] text-base font-semibold mb-3">Fasilitas :</h4>
          <div class="flex flex-col gap-2 text-[#0c2c67] text-[15px] leading-relaxed">
            <div class="flex items-center gap-2"><Icon icon="mdi:bed" class="text-lg" /> Bed</div>
            <div class="flex items-center gap-2">
              <Icon icon="mdi:wifi" class="text-lg" /> Wi-Fi
            </div>
            <div class="flex items-center gap-2">
              <Icon icon="mdi:shower" class="text-lg" /> Shower
            </div>
            <div class="flex items-center gap-2">
              <Icon icon="mdi:television" class="text-lg" /> TV
            </div>
            <div class="flex items-center gap-2">
              <Icon icon="mdi:toothbrush-paste" class="text-lg" /> Perlengkapan Mandi
            </div>
            <div class="flex items-center gap-2">
              <Icon icon="mdi:fridge" class="text-lg" /> Kulkas
            </div>
            <div class="flex items-center gap-2">
              <Icon icon="mdi:coffee-maker" class="text-lg" /> Mesin Pembuat Kopi
            </div>
            <div class="flex items-center gap-2">
              <Icon icon="mdi:iron" class="text-lg" /> Setrika
            </div>
          </div>
        </div>

        <!-- === ROOM 3 === -->
        <div
          class="bg-white rounded-lg shadow-lg p-6 transition-all duration-500 hover:shadow-2xl hover:scale-[1.01]"
        >
          <h3 class="text-[#0c2c67] text-2xl font-semibold mb-1">Rp.1.899.999</h3>
          <p class="text-[#0c2c67] text-sm font-semibold mb-1 uppercase">
            Friday, Oct 17 - Saturday, Oct 18
          </p>
          <h2 class="italic font-semibold text-[#b7a34b] text-3xl mb-4">Suite Room</h2>

          <div class="flex gap-4 mb-4 items-start">
            <img src="@/assets/book.png" alt="Room" class="w-[50%] rounded-md shadow-sm" />
            <p class="text-[#0c2c67] text-base font-medium">
              Luxury suite with living area & ocean view
            </p>
          </div>

          <h4 class="text-[#0c2c67] text-base font-semibold mb-3">Fasilitas :</h4>
          <div class="flex flex-col gap-2 text-[#0c2c67] text-[15px] leading-relaxed">
            <div class="flex items-center gap-2"><Icon icon="mdi:bed" class="text-lg" /> Bed</div>
            <div class="flex items-center gap-2">
              <Icon icon="mdi:wifi" class="text-lg" /> Wi-Fi
            </div>
            <div class="flex items-center gap-2">
              <Icon icon="mdi:shower" class="text-lg" /> Shower
            </div>
            <div class="flex items-center gap-2">
              <Icon icon="mdi:television" class="text-lg" /> TV
            </div>
            <div class="flex items-center gap-2">
              <Icon icon="mdi:toothbrush-paste" class="text-lg" /> Perlengkapan Mandi
            </div>
            <div class="flex items-center gap-2">
              <Icon icon="mdi:fridge" class="text-lg" /> Kulkas
            </div>
            <div class="flex items-center gap-2">
              <Icon icon="mdi:coffee-maker" class="text-lg" /> Mesin Pembuat Kopi
            </div>
            <div class="flex items-center gap-2">
              <Icon icon="mdi:iron" class="text-lg" /> Setrika
            </div>
          </div>
        </div>
      </div>

      <!-- === BAGIAN KANAN (STICKY) === -->
      <div class="w-full lg:w-80 bg-white rounded-lg shadow-lg p-6 h-fit sticky top-24 self-start">
        <div class="flex gap-2 mb-5">
          <button class="bg-[#0c2c67] text-white text-sm font-semibold px-3 py-1 rounded-md">
            Summary
          </button>
          <button class="bg-gray-300 text-sm px-3 py-1 rounded-md text-gray-700">Details</button>
        </div>

        <div class="flex justify-between text-sm mb-1">
          <span class="text-gray-600">Subtotal</span>
          <span>Rp. 999.999</span>
        </div>
        <div class="flex justify-between text-sm mb-4">
          <span class="text-gray-600">Taxes & Fees</span>
          <span>Rp. 1.000</span>
        </div>

        <div class="flex justify-between border-t pt-3 mb-4">
          <span class="font-semibold text-[#0c2c67]">TOTAL</span>
          <span class="font-bold text-[#0c2c67]">Rp.1.000.000</span>
        </div>

        <div class="flex gap-3">
          <button
            class="bg-[#b7a34b] hover:bg-[#9b8a3e] text-white font-semibold py-2 w-1/2 rounded-md transition"
          >
            Agree
          </button>
          <button class="bg-gray-300 text-[#0c2c67] font-semibold py-2 w-1/2 rounded-md">
            Checkout
          </button>
        </div>
      </div>
    </div>
  </section>
</template>
