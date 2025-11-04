<template>
  <div>
    <Navbar />
    <main class="page-content">
      <LoginPage />
      <RegisterPage />
      <Confirmation />
      <Rooms/>
      <AddService/>
      <BookingSelection/>
      <Booking/>
      <Home/>
      <PaymentSucces/>
      <PaymentFailed/>
    </main>

    <Footer />
  </div>
</template>

<script setup>
import Navbar from './components/Navbar.vue'
import Footer from './components/Footer.vue'
// import PaymentSucces from './auth/PaymentSucces.vue';
import PaymentFailed from './auth/PaymentFailed.vue';
// import Home from './page/Home.vue';
// import BookingSelection from './auth/BookingSelection.vue';
// import Booking from './auth/Booking.vue';
// import Rooms from './auth/Rooms.vue';
// import AddService from './auth/AddService.vue';
// import LoginPage from './auth/Login.vue'
// import RegisterPage from './auth/Register.vue'
// import Confirmation from './auth/Confirmation.vue'


</script>

