<template>
  <section
    class="min-h-screen bg-[#0b1e45] flex items-center justify-center px-4"
  >
    <div
  class="bg-white rounded-2xl shadow-xl p-15 flex flex-col items-center text-center w-[800px] animate-fade-in"
>

     
      <img
        src="@/assets/X circle.png"
        alt="Success"
        class="h-20 w-20 mb-5"
      />


      <img
        src="@/assets/Logo Atlanz 2.png"
        alt="Atlanz Logo"
        class="h-8 mb-5"
      />


      <h1 class="text-4xl font-semibold mb-6 text-[#071851]">
        Payment Failed
      </h1>


<router-link
  to="/"
  class="bg-[#0066ff] hover:bg-[#0052cc] text-white font-normal text-2xl py-3 px-20 rounded-lg transition-all hover:shadow-lg hover:-translate-y-1"
>
  Home
</router-link>

    </div>
  </section>
</template>

<script setup></script>

<style scoped>
@import url("https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap");

* {
  font-family: "Poppins", sans-serif;
}

@keyframes fade-in {
  from {
    opacity: 0;
    transform: translateY(15px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-fade-in {
  animation: fade-in 0.8s ease-in-out;
}
</style>