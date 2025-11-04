<template>
  <div class="login-page">
    <div class="overlay">
      <div class="login-container">
        <h2 class="title">Welcome to Atlanz</h2>

        <form @submit.prevent="register">
          <div class="form-group">
            <label>Name</label>
            <input type="text" v-model="name" placeholder="Name" required />
          </div>

          <div class="form-group">
            <label>Email</label>
            <input type="email" v-model="email" placeholder="example@gmail.com" required />
          </div>

          <div class="form-group">
            <label>Phone No</label>
            <input type="tel" v-model="phone" placeholder="Phone Number" required />
          </div>

          <div class="form-group">
            <label>Username</label>
            <input type="text" v-model="username" placeholder="Username" required />
          </div>

          <div class="form-group">
            <label>Password</label>
            <div class="password-input-wrapper">
              <input 
                :type="showPassword ? 'text' : 'password'" 
                v-model="password" 
                placeholder="6+ characters" 
                required 
              />
              <button 
                type="button" 
                class="toggle-password" 
                @click="showPassword = !showPassword"
              >
                👁️
              </button>
            </div>
          </div>

          <button type="submit" class="btn">Register</button>
        </form>

        <p class="footer">
          Have Account? <a href="#">Login</a>
        </p>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "Register",
  data() {
    return {
      name: "",
      email: "",
      phone: "",
      username: "",
      password: "",
      showPassword: false,
    };
  },
  methods: {
    register() {
      if (this.name && this.email && this.phone && this.username && this.password) {
        alert(`Selamat datang, ${this.name}! Akun berhasil dibuat.`);
      } else {
        alert("Isi semua field dulu ya, Odi 😄");
      }
    },
  },
};
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap');

.login-page {
  position: fixed;
  top: 0;
  left: 0;
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

.login-container {
  position: relative;
  background: rgba(244, 244, 244, 0.5);
  backdrop-filter: blur(1px);
  -webkit-backdrop-filter: blur(8px);
  padding: 2.5rem 3rem;
  border-radius: 18px;
  width: 450px;
  max-width: 95%;
  max-height: 90vh;
  overflow-y: auto;
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
  text-align: center;
  color: #000;
}

.title {
  font-size: 30px;
  font-weight: 600;
  margin-bottom: 1.5rem;
  color: #000;
  font-family: 'Poppins', sans-serif;
}

.form-group {
  text-align: left;
  margin-bottom: 1rem;
}

label {
  display: block;
  font-size: 13px;
  font-weight: 500;
  color: #000;
  margin-bottom: 6px;
  font-family: 'Poppins', sans-serif;
}

input {
  width: 100%;
  padding: 11px 14px;
  border: none;
  border-radius: 8px;
  font-size: 14px;
  outline: none;
  transition: all 0.2s;
  background: rgba(255, 255, 255, 0.95);
  box-sizing: border-box;
  color: #000;
  font-family: 'Poppins', sans-serif;
}

input::placeholder {
  color: #b0b0b8;
  font-size: 14px;
  font-family: 'Poppins', sans-serif;
}

input:focus {
  background: rgba(255, 255, 255, 1);
  box-shadow: 0 0 0 2px rgba(0, 102, 255, 0.15);
}

.password-input-wrapper {
  position: relative;
  display: flex;
  align-items: center;
}

.password-input-wrapper input {
  padding-right: 42px;
}

.toggle-password {
  position: absolute;
  right: 10px;
  background: none;
  border: none;
  cursor: pointer;
  font-size: 16px;
  padding: 5px;
  display: flex;
  align-items: center;
  justify-content: center;
  opacity: 0.5;
  transition: opacity 0.2s;
}

.toggle-password:hover {
  opacity: 0.8;
}

.btn {
  width: 100%;
  background: #0066ff;
  color: white;
  border: none;
  padding: 12px;
  border-radius: 8px;
  cursor: pointer;
  font-weight: 600;
  font-size: 15px;
  margin-top: 1rem;
  transition: all 0.2s;
  font-family: 'Poppins', sans-serif;
}

.btn:hover {
  background: #0052cc;
}

.btn:active {
  transform: scale(0.98);
}

.footer {
  margin-top: 1rem;
  font-size: 13px;
  color: #333;
  font-family: 'Poppins', sans-serif;
}

.footer a {
  color: #0066ff;
  text-decoration: none;
  font-weight: 500;
}

.footer a:hover {
  text-decoration: underline;
}


</style>