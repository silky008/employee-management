<template>
  <div class="p-6">
    <div class="flex justify-between items-center mb-6">
      <h2 class="text-2xl font-bold">Users</h2>
    </div>

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
      <table class="w-full text-sm text-left text-gray-500">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
          <tr>
            <th class="px-6 py-3">Name</th>
            <th class="px-6 py-3">Email</th>
            <th class="px-6 py-3">Role</th>
          </tr>
        </thead>

        <tbody>
          <tr
            v-for="user in users.data"
            :key="user.id"
            class="bg-white border-b hover:bg-gray-50"
          >
            <td class="px-6 py-4 font-medium text-gray-900">
              {{ user.name }}
            </td>
            <td class="px-6 py-4">
              {{ user.email }}
            </td>
            <td class="px-6 py-4">
              {{ user.role.name }}
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div class="flex justify-between items-center mt-6">
      <button
        @click="changePage(page - 1)"
        :disabled="page === 1"
        class="px-4 py-2 text-sm bg-gray-200 rounded disabled:opacity-50"
      >
        Previous
      </button>

      <span class="text-sm"> Page {{ page }} of {{ lastPage }} </span>

      <button
        @click="changePage(page + 1)"
        :disabled="page === lastPage"
        class="px-4 py-2 text-sm bg-gray-200 rounded disabled:opacity-50"
      >
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
