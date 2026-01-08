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

const router = useRouter();
const user = ref(null);

const fetchUser = async () => {
  try {
    const token = localStorage.getItem("token");
    const response = await axios.get("http://127.0.0.1:8000/api/me", {
      headers: { Authorization: `Bearer ${token}`, Accept: "application/json" },
    });
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
