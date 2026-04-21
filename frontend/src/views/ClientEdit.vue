<template>
  <div v-if="client" class="p-6">
    <h2 class="text-2xl font-bold mb-6">Edit Client</h2>
    <p v-if="successMessage" class="text-green-600 mb-3">
      {{ successMessage }}
    </p>
    <!-- Form -->
    <form @submit.prevent="updateClient">
      <input v-model="client.name" class="border p-2 mb-3 w-full" />

      <input
        v-model="client.program_limit"
        type="number"
        class="border p-2 mb-3 w-full"
      />
      <div v-if="client?.logo" class="mb-4">
        <p class="text-sm text-gray-500 mb-2">Current Logo:</p>

        <img
          :src="getLogo(client.logo)"
          class="w-24 h-24 rounded border object-cover"
        />
      </div>

      <div class="mb-4">
        <p class="text-sm text-gray-500 mb-2">Upload new logo (optional)</p>

        <input type="file" @change="handleFile" />
      </div>

      <button class="bg-blue-600 text-white px-4 py-2 rounded">Update</button>
    </form>
  </div>

  <div v-else class="p-6">Loading client...</div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useRoute, useRouter } from "vue-router";
import api from "@/services/api";

const route = useRoute();
const router = useRouter();

const client = ref(null);
const logoFile = ref(null);
const successMessage = ref("");

const getLogo = (logo) => `http://localhost:8000/storage/${logo}`;

const handleFile = (e) => {
  logoFile.value = e.target.files[0];
};

onMounted(async () => {
  const res = await api.get(`/clients/${route.params.id}`);
  //  console.log(res.data);
  //console.log(res.data.data);
  client.value = res.data;
});

const updateClient = async () => {
  const formData = new FormData();

  formData.append("name", client.value.name);
  formData.append("program_limit", client.value.program_limit);

  if (logoFile.value) {
    formData.append("logo", logoFile.value);
  }

  await api.post(`/clients/${client.value.id}?_method=PUT`, formData);

  // refresh data
  const res = await api.get(`/clients/${client.value.id}`);
  client.value = res.data;

  logoFile.value = null;
  successMessage.value = "Client updated successfully";
};
</script>
