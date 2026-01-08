<template>
  <div>
    <h2>Login</h2>

    <input v-model="email" placeholder="Email" />
    <input v-model="password" type="password" placeholder="Password" />

    <button @click="login">Login</button>
  </div>
</template>

<script setup>
import { ref } from "vue";
import axios from "axios";

const email = ref("");
const password = ref("");

const login = async () => {
  const response = await axios.post("http://127.0.0.1:8000/api/login", {
    email: email.value,
    password: password.value,
  });

  localStorage.setItem("token", response.data.token);
  alert("Login successful");
};
</script>
