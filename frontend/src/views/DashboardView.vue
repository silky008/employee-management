<template>
  <!-- Stats Cards -->
  <div class="grid grid-cols-3 gap-4">
    <div class="p-4 bg-white shadow rounded">
      <h3 class="text-gray-500">Total Users</h3>
      <p class="text-2xl font-bold">{{ stats.total_users }}</p>
    </div>
  </div>

  <!-- Chart -->
  <div class="bg-white p-4 rounded shadow">
    <h3 class="mb-4 font-semibold">Users by Role</h3>
    <canvas id="roleChart"></canvas>
  </div>

  <!-- Recent Activity -->
  <div class="bg-white p-4 rounded shadow">
    <h3 class="mb-4 font-semibold">Recent Activity</h3>

    <ul>
      <li v-for="log in stats.recent_logs" :key="log.id" class="border-b py-2">
        <strong>{{ log.actor.name }}</strong>
        {{ log.action }}
        user #{{ log.target_id }}
      </li>
    </ul>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import api from "@/services/api";
import Chart from "chart.js/auto";

const stats = ref({
  total_users: 0,
  users_by_role: [],
  recent_logs: [],
});

let chartInstance = null;

const fetchStats = async () => {
  const res = await api.get("/dashboard-stats");
  stats.value = res.data;

  renderChart();
};

const renderChart = () => {
  const ctx = document.getElementById("roleChart");

  const labels = stats.value.users_by_role.map((r) => r.role);
  const data = stats.value.users_by_role.map((r) => r.count);

  if (chartInstance) {
    chartInstance.destroy();
  }

  chartInstance = new Chart(ctx, {
    type: "bar",
    data: {
      labels,
      datasets: [
        {
          label: "Users",
          data,
        },
      ],
    },
  });
};

onMounted(() => {
  fetchStats();
});
</script>
