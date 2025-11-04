<template>
  <nav class="navbar">
    <div class="logo">
      <img src="@/assets/Logo Atlanz white.png" alt="Atlanz Logo" />
    </div>
    <ul class="nav-links">
      <li><a href="#">Home</a></li>
      <li><a href="#">Facilities</a></li>
      <li><a class="active" href="#">Rooms</a></li>
      <li><a href="#">Contact-us</a></li>
      <li><a href="#">Book</a></li>
    </ul>
  </nav>
</template>

<script setup>
</script>

<style scoped>
.navbar {
  background-color: #0b1f4f;
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 25px 80px;
}

.logo {
  display: flex;
  align-items: center;
  gap: 10px;
}

.logo img {
  width: 170px;
  height: auto;
}

.logo span {
  color: white;
  font-size: 28px;
  font-weight: 600;
  letter-spacing: 2px;
}

.nav-links {
  list-style: none;
  display: flex;
  gap: 35px;
  margin: 0;
  padding: 0;
}

.nav-links a {
  color: white;
  text-decoration: none;
  font-weight: 500;
  transition: 0.3s;
  font-size: 16px;
}

.nav-links a:hover,
.nav-links a.active {
  text-decoration: underline;
  text-underline-offset: 6px;
  color: #fff;
}
</style>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap');

* {
  font-family: 'Poppins', sans-serif;
}
</style>