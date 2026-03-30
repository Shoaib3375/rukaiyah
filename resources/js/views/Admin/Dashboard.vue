<template>
  <div class="min-h-screen bg-gray-50 py-12 px-4">
    <div class="max-w-7xl mx-auto">
      <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Admin Dashboard</h1>
        <p class="text-gray-600">Manage users, Raqis, and platform analytics</p>
      </div>

      <!-- Stats Grid -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
          <h3 class="text-gray-500 text-sm font-medium">Total Users</h3>
          <p class="text-3xl font-bold text-blue-600 mt-2">{{ stats.totalUsers }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
          <h3 class="text-gray-500 text-sm font-medium">Active Raqis</h3>
          <p class="text-3xl font-bold text-green-600 mt-2">{{ stats.activeRaqis }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
          <h3 class="text-gray-500 text-sm font-medium">Pending Approvals</h3>
          <p class="text-3xl font-bold text-yellow-600 mt-2">{{ stats.pendingRaqis }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
          <h3 class="text-gray-500 text-sm font-medium">Total Appointments</h3>
          <p class="text-3xl font-bold text-purple-600 mt-2">{{ stats.totalAppointments }}</p>
        </div>
      </div>

      <!-- Quick Links -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <router-link
          to="/admin/raqis"
          class="bg-yellow-600 text-white rounded-lg shadow-lg p-8 hover:bg-yellow-700 transition"
        >
          <h2 class="text-2xl font-bold mb-2">⭐ Pending Approvals</h2>
          <p class="text-yellow-100">Review and approve new Raqis</p>
        </router-link>

        <router-link
          to="/admin/users"
          class="bg-blue-600 text-white rounded-lg shadow-lg p-8 hover:bg-blue-700 transition"
        >
          <h2 class="text-2xl font-bold mb-2">👥 Users</h2>
          <p class="text-blue-100">Manage user accounts</p>
        </router-link>

        <router-link
          to="/admin/appointments"
          class="bg-purple-600 text-white rounded-lg shadow-lg p-8 hover:bg-purple-700 transition"
        >
          <h2 class="text-2xl font-bold mb-2">📅 Appointments</h2>
          <p class="text-purple-100">View all appointments</p>
        </router-link>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { adminAPI } from '../../api';

const stats = ref({
  totalUsers: 0,
  activeRaqis: 0,
  pendingRaqis: 0,
  totalAppointments: 0
});

onMounted(async () => {
  try {
    const response = await adminAPI.stats.index();
    stats.value = response.data.data;
  } catch (error) {
    console.error('Failed to load stats:', error);
  }
});
</script>
