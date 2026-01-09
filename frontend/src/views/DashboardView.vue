<template>
  <div>
    <h1>Dashboard</h1>
    <p v-if="user">Welcome, {{ user.name }} ({{ user.role.name }})</p>
    <button @click="logout">Logout</button>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";
import { useRouter } from "vue-router";
import api from "../services/api";

const router = useRouter();
const user = ref(null);

const fetchUser = async () => {
  try {
    const token = localStorage.getItem("token");
    const response = await api.get("/me");
    user.value = response.data;
  } catch (err) {
    console.error(err);
    router.push("/login"); // redirect if not authenticated
  }
};

const logout = () => {
  localStorage.removeItem("token");
  router.push("/login");
};

onMounted(fetchUser);
</script>
