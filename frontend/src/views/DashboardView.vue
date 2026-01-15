<template>
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
