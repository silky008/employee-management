<template>
  <div class="p-6">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-3xl font-bold">Dashboard</h1>

      <button
        @click="logout"
        class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700"
      >
        Logout
      </button>
    </div>

    <div v-if="user" class="bg-white shadow rounded-lg p-6 mb-6">
      <p class="text-lg">
        Welcome,
        <span class="font-semibold">{{ user.name }}</span>
      </p>
      <p class="text-sm text-gray-600">Role: {{ user.role.name }}</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-xl font-semibold mb-2">Users</h2>
        <p class="text-sm text-gray-600 mb-4">
          Manage application users and roles.
        </p>

        <router-link
          to="/users"
          class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
        >
          Manage Users
        </router-link>
      </div>

      <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-xl font-semibold mb-2">Profile</h2>
        <p class="text-sm text-gray-600 mb-4">
          View and update your profile information.
        </p>

        <button class="bg-gray-200 px-4 py-2 rounded" disabled>
          Coming Soon
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import api from "@/services/api";
import { useRouter } from "vue-router";

const user = ref(null);
const router = useRouter();

onMounted(async () => {
  const res = await api.get("/me");
  user.value = res.data;
});

const logout = async () => {
  await api.post("/logout");
  localStorage.removeItem("token");
  router.push("/login");
};
</script>
