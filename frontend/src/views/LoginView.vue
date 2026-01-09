<template>
  <div>
    <h2>Login</h2>

    <input v-model="email" placeholder="Email" />
    <input v-model="password" type="password" placeholder="Password" />

    <button type="button" @click="login">Login</button>
  </div>
</template>

<script setup>
import { ref } from "vue";
import axios from "axios";
import { useRouter } from "vue-router";

const router = useRouter();

const email = ref("");
const password = ref("");

const login = async () => {
  try {
    const response = await axios.post(
      "http://127.0.0.1:8000/api/login",
      { email: email.value, password: password.value },
      { headers: { Accept: "application/json" } }
    );
    localStorage.setItem("token", response.data.token);
    alert("Login successful");
    //This is the important line
    router.push("/");
  } catch (error) {
    console.error(error.response?.data || error.message);
    alert("Login failed");
  }
};
</script>
