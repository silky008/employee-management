<template>
  <div class="p-6">
    <h2 class="text-2xl font-bold mb-6">Clients</h2>

    <!-- Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div
        v-for="client in clients"
        :key="client.id"
        class="bg-white shadow-md rounded-xl p-5 hover:shadow-lg transition"
      >
        <!-- Logo -->
        <div class="flex items-center gap-4">
          <img
            :src="getLogo(client.logo)"
            class="w-14 h-14 rounded-full object-cover border"
          />

          <!-- Name -->
          <div>
            <h3 class="text-lg font-semibold">
              {{ client.name }}
            </h3>

            <!-- Program limit -->
            <p class="text-sm text-gray-500">
              Program Limit: {{ client.program_limit }}
            </p>
          </div>
        </div>

        <!-- Footer actions (future use) -->
        <div class="mt-4 flex justify-end gap-2">
          <router-link
            :to="`/clients/view/${client.id}`"
            class="bg-green-500 text-white px-2 py-1 rounded"
          >
            View
          </router-link>

          <router-link
            :to="`/clients/edit/${client.id}`"
            class="bg-yellow-500 text-white px-2 py-1 rounded"
          >
            Edit
          </router-link>
          <button
            @click="deleteClient(client.id)"
            class="bg-red-500 text-white px-2 py-1 rounded"
          >
            Delete
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
<script setup>
import { ref, onMounted } from "vue";
import api from "@/services/api";

const clients = ref([]);

const fetchClients = async () => {
  try {
    const res = await api.get("/clients");
    clients.value = res.data.data; // because of pagination
  } catch (e) {
    console.error(e);
  }
};

const getLogo = (logo) => {
  return logo
    ? `http://localhost:8000/storage/${logo}`
    : "https://via.placeholder.com/100";
};

const deleteClient = async (id) => {
  if (!confirm("Are you sure you want to delete this client?")) return;

  await api.delete(`/clients/${id}`);
  fetchClients();
};

onMounted(() => {
  fetchClients();
});
</script>
