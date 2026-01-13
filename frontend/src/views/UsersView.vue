<template>
  <div>
    <h2>Users</h2>
    <table>
      <tr v-for="user in users.data" :key="user.id">
        <td>{{ user.name }}</td>
        <td>{{ user.email }}</td>
        <td>{{ user.role.name }}</td>
      </tr>
    </table>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import api from "@/services/api";
import { useRouter } from "vue-router";

const router = useRouter();

const users = ref({ data: [] });

onMounted(async () => {
  try {
    const res = await api.get("/users");
    users.value = res.data;
  } catch (error) {
    if (error.response && error.response.status === 403) {
      router.push("/403");
    }
  }
});
</script>
