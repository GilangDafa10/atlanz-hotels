<template>
  <div class="confirmation-page">
    <div class="overlay">
      <div class="confirmation-container">
        <img src="@/assets/check_circle.png" alt="Success" class="check-icon" />

        <h1>Account Created Successful</h1>
        <p>Please Check Your Email</p>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Confirmation',

  mounted() {
    // AUTO REDIRECT 3 DETIK
    setTimeout(() => {
      this.$router.push('/')
    }, 3000)
  },
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap');

/* style lainnya tetap kamu lanjutin */

.confirmation-page {
  position: fixed;
  inset: 0;
  width: 100%;
  height: 100vh;
  background: url('@/assets/gambar hotel.jpg') no-repeat center center/cover;
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
  background: rgba(0, 0, 0, 0.5);
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
  margin-bottom: 1.5rem;
}

/* BUTTON LOGIN STYLE */
.login-btn {
  background: #003dfc;
  color: white;
  border: none;
  padding: 12px 32px;
  font-size: 1.05rem;
  font-weight: 600;
  border-radius: 12px;
  cursor: pointer;
  transition: 0.25s ease;
  width: 200px;
}

.login-btn:hover {
  background: #004ccc;
  transform: translateY(-2px);
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

  .check-icon {
    width: 65px;
    height: 65px;
  }

  h1 {
    font-size: 1.4rem;
  }
}
</style>
