<template>
  <div class="p-6">
    <h2 class="text-2xl font-bold mb-4">Audit Logs</h2>
    <div class="flex gap-4 mb-4">
      <!-- Search -->
      <input
        v-model="search"
        type="text"
        placeholder="Search by actor name"
        class="border p-2 rounded w-64"
      />

      <!-- Action Filter -->
      <select v-model="actionFilter" class="border p-2 rounded">
        <option value="">All Actions</option>
        <option value="created">Created</option>
        <option value="updated">Updated</option>
        <option value="deleted">Deleted</option>
      </select>
    </div>
    <div class="overflow-x-auto shadow-md sm:rounded-lg">
      <table class="w-full text-sm text-left text-gray-500">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
          <tr>
            <th class="px-6 py-3">Actor</th>
            <th class="px-6 py-3">Action</th>
            <th class="px-6 py-3">Target</th>
            <th class="px-6 py-3">Changes</th>
            <th class="px-6 py-3">Time</th>
          </tr>
        </thead>

        <tbody>
          <tr
            v-for="log in logs.data"
            :key="log.id"
            class="bg-white border-b hover:bg-gray-50"
          >
            <td class="px-6 py-4">{{ log.actor.name }}</td>
            <td class="px-6 py-4">
              <span
                :class="{
                  'text-green-600': log.action === 'created',
                  'text-blue-600': log.action === 'updated',
                  'text-red-600': log.action === 'deleted',
                }"
              >
                {{ log.action }}
              </span>
            </td>
            <td class="px-6 py-4">
              {{ log.target_type }} #{{ log.target_id }}
            </td>
            <td class="px-6 py-4">
              <pre class="text-xs">{{
                JSON.stringify(log.changes, null, 2)
              }}</pre>
            </td>
            <td class="px-6 py-4">
              {{ new Date(log.created_at).toLocaleString() }}
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div class="flex justify-center mt-6 gap-2">
      <button
        :disabled="currentPage === 1"
        @click="fetchLogs(currentPage - 1)"
        class="px-3 py-1 border rounded disabled:opacity-50"
      >
        Prev
      </button>

      <span class="px-3 py-1">Page {{ currentPage }} of {{ lastPage }}</span>

      <button
        :disabled="currentPage === lastPage"
        @click="fetchLogs(currentPage + 1)"
        class="px-3 py-1 border rounded disabled:opacity-50"
      >
        Next
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from "vue";
import api from "@/services/api";

const logs = ref({ data: [] });
const search = ref("");
const actionFilter = ref("");
const currentPage = ref(1);
const lastPage = ref(1);
let debounceTimer = null;

const fetchLogs = async (page = 1) => {
  try {
    logs.value = { data: [] };
    const res = await api.get("/audit-logs", {
      params: { page, search: search.value, action: actionFilter.value },
    });
    logs.value = res.data;
    currentPage.value = res.data.meta.current_page;
    lastPage.value = res.data.meta.last_page;
  } catch (error) {
    console.error(error);
  }
};

watch([search, actionFilter], () => {
  clearTimeout(debounceTimer);

  debounceTimer = setTimeout(() => {
    fetchLogs(1);
  }, 500);
});

onMounted(() => {
  fetchLogs();
});
</script>
