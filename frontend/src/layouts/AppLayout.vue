<template>
  <div class="flex min-h-screen bg-gray-50">
    <!-- Sidebar -->
    <aside class="w-64 bg-white shadow p-6">
      <h2 class="text-xl font-bold mb-6">My App</h2>

      <nav class="space-y-2">
        <router-link to="/" class="block px-3 py-2 rounded hover:bg-gray-200">
          Dashboard
        </router-link>

        <router-link
          v-if="user && user.role.name === 'admin'"
          to="/users"
          class="block px-3 py-2 rounded hover:bg-gray-200"
        >
          Manage Users
        </router-link>
      </nav>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 p-6">
      <!-- Navbar -->
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">{{ pageTitle }}</h1>
        <div class="flex items-center space-x-4">
          <span v-if="user" class="text-gray-700">{{ user.name }}</span>
          <button
            @click="logout"
            class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700"
          >
            Logout
          </button>
        </div>
      </div>

      <!-- Page content injected here -->
      <router-view />
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from "vue";
import api from "@/services/api";
import { useRouter, useRoute } from "vue-router";

const router = useRouter();
const route = useRoute();
const user = ref(null);

const pageTitle = computed(() => {
  return route.meta.title || "Page";
});

onMounted(async () => {
  try {
    const res = await api.get("/me");
    user.value = res.data;
  } catch {
    router.push("/login");
  }
});

const logout = async () => {
  await api.post("/logout");
  localStorage.removeItem("token");
  router.push("/login");
};
</script>
