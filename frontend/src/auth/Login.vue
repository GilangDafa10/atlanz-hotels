<template>
  <div class="login-page">
    <div class="overlay">
      <div class="login-container">
        <h2 class="title">Sign in to Atlanz</h2>

        <form @submit.prevent="login">
          <div class="form-group">
            <label>Username / Email</label>
            <input type="text" v-model="username" placeholder="Username / Email" required />
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
                @click="togglePassword"
              >
                <svg
                  v-if="showPassword"
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                  class="eye-icon"
                >
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>

                <svg
                  v-else
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                  class="eye-icon"
                >
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 3l18 18M10.58 10.58A3 3 0 0113.42 13.42M6.26 6.26A9.77 9.77 0 003 12c1.274 4.057 5.064 7 9.542 7a9.77 9.77 0 005.18-1.54M17.74 17.74A9.77 9.77 0 0021 12c-1.274-4.057-5.064-7-9.542-7a9.77 9.77 0 00-5.18 1.54" />
                </svg>
              </button>
            </div>
          </div>

          <button type="submit" class="btn" :disabled="loading">
            {{ loading ? 'Loading...' : 'Login' }}
          </button>
        </form>

        <p v-if="errorMessage" style="color:red; margin-top:10px;">
          {{ errorMessage }}
        </p>

        <p class="footer">
          No Account? <a href="#">Create Account</a>
        </p>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  name: "Login",
  data() {
    return {
      username: "",
      password: "",
      showPassword: false,
      loading: false,
      errorMessage: null,
    };
  },
  methods: {
    togglePassword() {
      this.showPassword = !this.showPassword;
    },

    async login() {
      this.loading = true;
      this.errorMessage = null;

      try {
        const payload = {
          email: this.username, // API pakai email
          password: this.password,
        };

        const res = await axios.post("http://127.0.0.1:8000/api/login", payload);

        // Simpan token
        localStorage.setItem("token", res.data.data.token);
        this.$router.push("/Confirmation");
        
      } 
      catch (err) {
        this.errorMessage = err.response?.data?.message || "Login gagal!";
      } 
      finally {
        this.loading = false;
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
  padding: 2.5rem 2.8rem;
  border-radius: 18px;
  width: 360px;
  max-width: 90%;
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
  text-align: center;
  color: #000;
}

.title {
  font-size: 30px;
  font-weight: 600;
  margin-bottom: 2rem;
  color: #000;
}

.form-group {
  text-align: left;
  margin-bottom: 1.2rem;
}

label {
  display: block;
  font-size: 13px;
  font-weight: 500;
  color: #000;
  margin-bottom: 6px;
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
}

input::placeholder {
  color: #b0b0b8;
  font-size: 14px;
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
  padding-right: 45px; 
}

.toggle-password {
  position: absolute;
  right: 12px;
  background: none;
  border: none;
  cursor: pointer;
  padding: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #555;
}

.eye-icon {
  width: 22px;
  height: 22px;
  opacity: 0.6;
  transition: opacity 0.2s ease, transform 0.2s ease;
}

.toggle-password:hover .eye-icon {
  opacity: 1;
  transform: scale(1.05);
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
  margin-top: 1.2rem;
  transition: all 0.2s;
}

.btn:hover {
  background: #0052cc;
}

.btn:active {
  transform: scale(0.98);
}

.footer {
  margin-top: 1.2rem;
  font-size: 13px;
  color: #333;
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
