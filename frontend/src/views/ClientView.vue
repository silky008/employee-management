<template>
  <div v-if="client" class="bg-white p-6 rounded shadow">
    <img :src="imageUrl(client.logo)" class="h-32 mb-4" />

    <h2 class="text-2xl font-bold">{{ client.name }}</h2>

    <p class="mt-2">Programs Allowed: {{ client.program_limit }}</p>

    <router-link
      :to="`/clients/edit/${client.id}`"
      class="bg-blue-600 text-white px-4 py-2 rounded mt-4 inline-block"
    >
      Edit Client
    </router-link>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import api from "@/services/api";
import { useRoute } from "vue-router";

const route = useRoute();
const client = ref(null);

const fetchClient = async () => {
  const res = await api.get(`/clients/${route.params.id}`);
  client.value = res.data;
};

const imageUrl = (path) => `http://127.0.0.1:8000/storage/${path}`;

onMounted(fetchClient);
</script>
