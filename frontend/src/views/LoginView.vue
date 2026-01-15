<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-50">
    <div class="w-full max-w-md bg-white rounded-lg shadow p-6">
      <h2 class="text-2xl font-bold mb-6 text-center">Sign in</h2>

      <form @submit.prevent="login" class="space-y-4">
        <div>
          <label class="block mb-1 text-sm font-medium">Email</label>
          <input
            type="email"
            v-model="email"
            required
            class="w-full border rounded-lg p-2"
          />
        </div>

        <div>
          <label class="block mb-1 text-sm font-medium">Password</label>
          <input
            type="password"
            v-model="password"
            required
            class="w-full border rounded-lg p-2"
          />
        </div>

        <button
          type="submit"
          class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700"
        >
          Login
        </button>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";
import axios from "axios";
import { useRouter } from "vue-router";

const router = useRouter();

const email = ref("");
const password = ref("");

const login = async () => {
  try {
    const response = await axios.post(
      "http://127.0.0.1:8000/api/login",
      { email: email.value, password: password.value },
      { headers: { Accept: "application/json" } }
    );
    localStorage.setItem("token", response.data.token);
    alert("Login successful");
    //This is the important line
    router.push("/");
  } catch (error) {
    console.error(error.response?.data || error.message);
    alert("Login failed");
  }
};
</script>
