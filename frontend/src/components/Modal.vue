<template>
  <div class="booking-container">
    <!-- Header with Logo -->
    <div class="header">
      <div class="logo-section">
        <!-- Ganti src dengan path logo Anda -->
        <img src="@/assets/logo.png" alt="Logo" class="logo" />
      </div>
    </div>

    <!-- Booking Section -->
    <div class="booking-section">
      <div class="form-wrapper">
        
        <!-- Check-in -->
        <div class="form-group">
          <label>Check-in (DD/MM/YYYY)</label>
          <input 
            v-model="checkIn" 
            type="date" 
            class="form-input"
            placeholder="DD/MM/YYYY"
          />
        </div>

        <!-- Check-out -->
        <div class="form-group">
          <label>Check-out (DD/MM/YYYY)</label>
          <input 
            v-model="checkOut" 
            type="date" 
            class="form-input"
            placeholder="DD/MM/YYYY"
          />
        </div>

        <!-- Rooms -->
        <div class="form-group">
          <label>Rooms</label>
          <select v-model="rooms" class="form-select">
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
            <option value="6">6</option>
            <option value="7">7</option>
            <option value="8">8</option>
            <option value="9">9</option>
            <option value="10">Max. 10</option>
          </select>
        </div>

        <!-- Person per room -->
        <div class="form-group">
          <label>Person per room</label>
          <select v-model="persons" class="form-select">
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">Min. 4</option>
            <option value="5">5</option>
          </select>
        </div>
      </div>

      <!-- Book Button -->
      <button @click="handleBooking" class="btn-book">BOOK NOW</button>
    </div>
  </div>
</template>

<script>
export default {
  name: 'HotelBooking',
  data() {
    return {
      checkIn: '',
      checkOut: '',
      rooms: '10',
      persons: '4'
    }
  },
  methods: {
    handleBooking() {
      if (this.checkIn && this.checkOut) {
        console.log({
          checkIn: this.checkIn,
          checkOut: this.checkOut,
          rooms: this.rooms,
          persons: this.persons
        });
        alert('Booking berhasil! Silakan lanjutkan ke halaman pembayaran.');
      } else {
        alert('Mohon isi semua field terlebih dahulu');
      }
    }
  }
}
</script>

<style scoped>
* {
  font-family: 'Poppins', sans-serif;
}

.booking-container {
  width: 100%;
  background-color: #f5f5f5;
}

/* Header */
.header {
  padding: 40px 20px;
  text-align: center;
  background-color: #f5f5f5;
}

.logo-section {
  display: flex;
  justify-content: center;
  align-items: center;
}

.logo {
  height: 60px;
  object-fit: contain;
}

/* Booking Section */
.booking-section {
  background: linear-gradient(135deg, #1a3a5c 0%, #2a5a8c 100%);
  padding: 40px 20px;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 30px;
}

.form-wrapper {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 20px;
  width: 100%;
  max-width: 900px;
}

.form-group {
  display: flex;
  flex-direction: column;
}

.form-group label {
  color: #ffffff;
  font-size: 12px;
  font-weight: 500;
  margin-bottom: 8px;
  letter-spacing: 0.5px;
  text-transform: uppercase;
}

.form-input,
.form-select {
  padding: 12px 15px;
  border: 1px solid #ddd;
  border-radius: 4px;
  font-size: 14px;
  font-weight: 400;
  background-color: #ffffff;
  color: #333;
  font-family: 'Poppins', sans-serif;
  transition: all 0.3s ease;
}

.form-input:focus,
.form-select:focus {
  outline: none;
  border-color: #d4a574;
  box-shadow: 0 0 5px rgba(212, 165, 116, 0.3);
}

/* Button */
.btn-book {
  background-color: #d4a574;
  color: #ffffff;
  padding: 14px 40px;
  border: none;
  border-radius: 4px;
  font-size: 14px;
  font-weight: 600;
  letter-spacing: 1px;
  cursor: pointer;
  text-transform: uppercase;
  transition: all 0.3s ease;
  font-family: 'Poppins', sans-serif;
}

.btn-book:hover {
  background-color: #c49464;
  transform: translateY(-2px);
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
}

.btn-book:active {
  transform: translateY(0);
}

/* Responsive */
@media (max-width: 768px) {
  .form-wrapper {
    grid-template-columns: 1fr;
  }

  .booking-section {
    padding: 30px 15px;
  }

  .btn-book {
    width: 100%;
  }
}
</style>