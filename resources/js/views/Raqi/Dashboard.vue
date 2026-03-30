<template>
  <div class="min-h-screen bg-gray-50 py-12 px-4">
    <div class="max-w-7xl mx-auto">
      <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Welcome, {{ user?.full_name }}!</h1>
        <p class="text-gray-600">Manage your healing sessions and availability</p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
          <h3 class="text-gray-500 text-sm font-medium">Pending Requests</h3>
          <p class="text-3xl font-bold text-yellow-600 mt-2">{{ stats.pendingCount }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
          <h3 class="text-gray-500 text-sm font-medium">Active Sessions</h3>
          <p class="text-3xl font-bold text-green-600 mt-2">{{ stats.activeCount }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
          <h3 class="text-gray-500 text-sm font-medium">Total Completed</h3>
          <p class="text-3xl font-bold text-blue-600 mt-2">{{ stats.completedCount }}</p>
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <router-link
          to="/raqi/availability"
          class="bg-purple-600 text-white rounded-lg shadow-lg p-8 hover:bg-purple-700 transition"
        >
          <h2 class="text-2xl font-bold mb-2">📅 Manage Availability</h2>
          <p class="text-purple-100">Set your available time slots</p>
        </router-link>

        <router-link
          to="/raqi/appointments"
          class="bg-blue-600 text-white rounded-lg shadow-lg p-8 hover:bg-blue-700 transition"
        >
          <h2 class="text-2xl font-bold mb-2">👥 Appointments</h2>
          <p class="text-blue-100">View and manage sessions</p>
        </router-link>
      </div>

      <div class="bg-white rounded-lg shadow mt-8 p-6">
        <h2 class="text-2xl font-bold mb-4">Pending Requests</h2>
        <div v-if="pendingAppointments.length" class="space-y-4">
          <div
            v-for="apt in pendingAppointments"
            :key="apt.id"
            class="border rounded-lg p-4 flex justify-between items-center"
          >
            <div>
              <h3 class="font-bold">{{ apt.patient_profile?.user?.full_name }}</h3>
              <p class="text-sm text-gray-600">{{ formatDateTime(apt.scheduled_at) }}</p>
              <p class="text-sm text-gray-600">{{ apt.session_type }}</p>
            </div>
            <div class="flex gap-2">
              <button
                @click="acceptAppointment(apt.id)"
                class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700"
              >
                Accept
              </button>
              <button
                @click="declineAppointment(apt.id)"
                class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700"
              >
                Decline
              </button>
            </div>
          </div>
        </div>
        <p v-else class="text-gray-600">No pending requests</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { user } from '../../store';
import { raqiAPI } from '../../api';
import { formatDateTime } from '../../utils';

const stats = ref({
  pendingCount: 0,
  activeCount: 0,
  completedCount: 0
});

const pendingAppointments = ref([]);

onMounted(async () => {
  try {
    const response = await raqiAPI.appointments.list();
    const appointments = response.data.data;

    pendingAppointments.value = appointments.filter(a => a.status === 'pending').slice(0, 3);
    stats.value.pendingCount = appointments.filter(a => a.status === 'pending').length;
    stats.value.activeCount = appointments.filter(a => a.status === 'accepted').length;
    stats.value.completedCount = appointments.filter(a => a.status === 'completed').length;
  } catch (error) {
    console.error('Failed to load dashboard:', error);
  }
});

const acceptAppointment = async (id) => {
  try {
    await raqiAPI.appointments.accept(id);
    pendingAppointments.value = pendingAppointments.value.filter(a => a.id !== id);
    stats.value.pendingCount--;
    stats.value.activeCount++;
  } catch (error) {
    alert('Failed to accept appointment');
  }
};

const declineAppointment = async (id) => {
  try {
    await raqiAPI.appointments.decline(id);
    pendingAppointments.value = pendingAppointments.value.filter(a => a.id !== id);
    stats.value.pendingCount--;
  } catch (error) {
    alert('Failed to decline appointment');
  }
};
</script>
