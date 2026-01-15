<template>
  <div>
    <h2>Users</h2>
    <table border="1" cellpadding="1">
      <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Role</th>
      </tr>
      <tr v-for="user in users.data" :key="user.id">
        <td>{{ user.name }}</td>
        <td>{{ user.email }}</td>
        <td>{{ user.role.name }}</td>
      </tr>
    </table>
    <!--Pagination-->
    <div style="margin-top: 20px">
      <button :disabled="page === 1" @click="changePage(page - 1)">Pre</button>
      <span style="margin: 0 10px"> Page {{ page }} of {{ lastPage }} </span>
      <button :disabled="page === lastPage" @click="changePage(page + 1)">
        Next
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import api from "@/services/api";
import { useRouter } from "vue-router";

const router = useRouter();

const users = ref({ data: [] });
const page = ref(1);
const lastPage = ref(1);

const fetchUsers = async () => {
  try {
    const res = await api.get(`/users?page=${page.value}`);
    users.value = res.data;
    lastPage.value = res.data.meta.last_page;
  } catch (error) {
    if (error.response && error.response.status === 403) {
      router.push("/403");
    }
  }
};

const changePage = (newPage) => {
  page.value = newPage;
  fetchUsers();
};

onMounted(fetchUsers);
</script>
