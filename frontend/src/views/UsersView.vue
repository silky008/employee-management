<template>
  <div class="p-6">
    <div class="flex justify-between items-center mb-6">
      <h2 class="text-2xl font-bold">Users</h2>
      <button
        v-if="currentUser && currentUser.role.name === 'admin'"
        @click="openCreateModal"
        class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700"
      >
        Add User
      </button>
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
            <td>
              <button
                @click="openEditModal(user)"
                class="px-2 py-1 text-white bg-yellow-500 rounded hover:bg-yellow-600"
              >
                Edit
              </button>
              <button
                @click="openDeleteModal(user)"
                class="px-2 py-1 ml-2 text-white bg-red-500 rounded hover:bg-red-600"
              >
                Delete
              </button>
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

  <!-- Create User Modal -->
  <div
    v-if="showCreate"
    class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50"
  >
    <div class="bg-white rounded-lg p-6 w-full max-w-md">
      <h3 class="text-xl font-bold mb-4">Create User</h3>

      <form @submit.prevent="createUser" class="space-y-4">
        <input
          v-model="form.name"
          placeholder="Name"
          class="w-full border p-2 rounded"
        />
        <input
          v-model="form.email"
          placeholder="Email"
          class="w-full border p-2 rounded"
        />
        <input
          v-model="form.password"
          type="password"
          placeholder="Password"
          class="w-full border p-2 rounded"
        />

        <select v-model="form.role_id" class="w-full border p-2 rounded">
          <option value="">Select Role</option>
          <option value="1">Admin</option>
          <option value="2">Manager</option>
          <option value="3">Employee</option>
        </select>

        <div class="flex justify-end space-x-2">
          <button
            type="button"
            @click="showCreate = false"
            class="px-4 py-2 bg-gray-200 rounded"
          >
            Cancel
          </button>
          <button
            type="submit"
            class="px-4 py-2 bg-blue-600 text-white rounded"
          >
            Save
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Edit User Modal -->
  <div
    v-if="showEdit"
    class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50"
  >
    <div class="bg-white rounded-lg p-6 w-full max-w-md">
      <h3 class="text-xl font-bold mb-4">Edit User</h3>

      <form @submit.prevent="updateUser" class="space-y-4">
        <input
          v-model="editForm.name"
          placeholder="Name"
          class="w-full border p-2 rounded"
        />
        <input
          v-model="editForm.email"
          placeholder="Email"
          class="w-full border p-2 rounded"
        />

        <select v-model="editForm.role_id" class="w-full border p-2 rounded">
          <option value="1">Admin</option>
          <option value="2">Manager</option>
          <option value="3">Employee</option>
        </select>

        <div class="flex justify-end space-x-2">
          <button
            type="button"
            @click="showEdit = false"
            class="px-4 py-2 bg-gray-200 rounded"
          >
            Cancel
          </button>
          <button
            type="submit"
            class="px-4 py-2 bg-blue-600 text-white rounded"
          >
            Update
          </button>
        </div>
      </form>
    </div>
  </div>
  <!-- Delete Confirmation Modal -->
  <div
    v-if="showDelete"
    class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50"
  >
    <div class="bg-white p-6 rounded w-96">
      <h2 class="text-lg font-bold mb-4 text-red-600">Confirm Delete</h2>

      <p class="mb-4">Are you sure you want to delete this user?</p>

      <div class="flex justify-end gap-2">
        <button
          @click="showDelete = false"
          class="px-4 py-2 bg-gray-300 rounded"
        >
          Cancel
        </button>

        <button
          @click="confirmDelete"
          class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700"
        >
          Delete
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import api from "@/services/api";
import { useRouter } from "vue-router";

const router = useRouter();
const currentUser = ref(null);

const users = ref({ data: [] });
const page = ref(1);
const lastPage = ref(1);

const showCreate = ref(false);
const form = ref({
  name: "",
  email: "",
  password: "",
  role_id: "",
});

const showEdit = ref(false);
const editForm = ref({
  id: null,
  name: "",
  email: "",

  role_id: "",
});

const showDelete = ref(false);
const deleteUserId = ref(null);

const openDeleteModal = (user) => {
  deleteUserId.value = user.id;
  showDelete.value = true;
};

const openCreateModal = () => {
  showCreate.value = true;
};

const openEditModal = (user) => {
  editForm.value = {
    id: user.id,
    name: user.name,
    email: user.email,

    role_id: user.role.id,
  };
  showEdit.value = true;
};
const createUser = async () => {
  try {
    await api.post("/users", form.value);
    showCreate.value = false;
    fetchUsers(); // refresh list
  } catch (error) {
    alert("Error creating user");
  }
};

const updateUser = async () => {
  try {
    await api.put(`/users/${editForm.value.id}`, {
      name: editForm.value.name,
      email: editForm.value.email,
      role_id: editForm.value.role_id,
    });

    showEdit.value = false;
    fetchUsers();
  } catch (error) {
    alert("Error updating user");
  }
};

const confirmDelete = async () => {
  try {
    await api.delete(`/users/${deleteUserId.value}`);
    showDelete.value = false;
    fetchUsers(); // reload table
  } catch (error) {
    alert(error.response?.data?.message || "Delete failed");
  }
};
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

onMounted(async () => {
  const me = await api.get("/me");
  currentUser.value = me.data;

  fetchUsers();
});
</script>
