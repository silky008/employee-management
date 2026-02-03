<template>
  <div class="p-6">
    <h2 class="text-2xl font-bold mb-4">Audit Logs</h2>

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
            <td class="px-6 py-4">{{ log.action }}</td>
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
import { ref, onMounted } from "vue";
import api from "@/services/api";

const logs = ref({ data: [] });
const currentPage = ref(1);
const lastPage = ref(1);

const fetchLogs = async (page = 1) => {
  try {
    const res = await api.get("/audit-logs", { params: { page } });
    logs.value = res.data;
    currentPage.value = res.data.meta.current_page;
    lastPage.value = res.data.meta.last_page;
  } catch (error) {
    console.error(error);
  }
};

onMounted(() => {
  fetchLogs();
});
</script>
