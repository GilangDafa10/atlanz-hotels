<template>
  <div class="confirmation-page">
    <div class="overlay">
      <div class="confirmation-container">
        <!-- Logo di atas -->
        <!-- <img src="@/assets/Logo Atlanz 2.png" alt="Atlanz Logo" class="logo" /> -->

        <!-- Centang di tengah -->
        <img src="@/assets/check_circle.png" alt="Success" class="check-icon" />

        <!-- Tulisan -->
        <h1>Account Created Successful</h1>
        <p>Please Check Your Email</p>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "Confirmation",
};
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap');

.confirmation-page {
  position: fixed;
  inset: 0;
  width: 100%;
  height: 100vh;
  background: url("@/assets/gambar hotel.jpg") no-repeat center center/cover;
  display: flex;
  justify-content: center;
  align-items: center;
  font-family: 'Poppins', sans-serif;
}

.overlay {
  position: absolute;
  top: 0;
  left: 0;
  height: 100%;
  width: 100%;
  background: rgba(0, 0, 0, 0.50);
  display: flex;
  justify-content: center;
  align-items: center;
}


.confirmation-container {
  background: rgba(255, 255, 255, 0.7);
  backdrop-filter: blur(1px);
  -webkit-backdrop-filter: blur(8px);
  border-radius: 25px;
  padding: 3rem 4rem;
  text-align: center;
  box-shadow: 0 8px 35px rgba(0, 0, 0, 0.25);
  color: #333;
  width: 85%;
  max-width: 650px;
  animation: fadeIn 1s ease;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
}


.logo {
  width: 190px;
  margin-bottom: 1.8rem;
  display: block;
}


.check-icon {
  width: 85px;
  height: 85px;
  object-fit: contain;
  margin-bottom: 1.8rem;
}


h1 {
  font-size: 1.9rem;
  color: #0066ff;
  margin-bottom: 0.6rem;
  font-weight: 600;
}


p {
  font-size: 1rem;
  color: #1f2937;
  opacity: 0.9;
}


@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(12px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}


@media (max-width: 480px) {
  .confirmation-container {
    padding: 1.8rem 2rem;
    width: 90%;
  }

  .logo {
    width: 130px;
    margin-bottom: 1.2rem;
  }

  .check-icon {
    width: 65px;
    height: 65px;
    margin-bottom: 1.2rem;
  }

  h1 {
    font-size: 1.4rem;
  }
}
</style>
