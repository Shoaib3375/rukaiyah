<template>
  <div class="min-h-screen bg-gray-50 py-12 px-4">
    <div class="max-w-7xl mx-auto">
      <!-- Welcome Section -->
      <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Welcome, {{ user?.full_name }}!</h1>
        <p class="text-gray-600">Find trusted Raqis for your spiritual healing journey</p>
      </div>

      <!-- Quick Stats -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
          <h3 class="text-gray-500 text-sm font-medium">Upcoming Appointments</h3>
          <p class="text-3xl font-bold text-blue-600 mt-2">{{ stats.upcomingCount }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
          <h3 class="text-gray-500 text-sm font-medium">Completed Sessions</h3>
          <p class="text-3xl font-bold text-green-600 mt-2">{{ stats.completedCount }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
          <h3 class="text-gray-500 text-sm font-medium">Unread Notifications</h3>
          <p class="text-3xl font-bold text-orange-600 mt-2">{{ stats.unreadCount }}</p>
        </div>
      </div>

      <!-- Quick Actions -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <router-link
          to="/patient/appointments/book"
          class="bg-blue-600 text-white rounded-lg shadow-lg p-8 hover:bg-blue-700 transition"
        >
          <h2 class="text-2xl font-bold mb-2">📅 Book Appointment</h2>
          <p class="text-blue-100">Find and book with an available Raqi</p>
        </router-link>

        <router-link
          to="/patient/raqis"
          class="bg-purple-600 text-white rounded-lg shadow-lg p-8 hover:bg-purple-700 transition"
        >
          <h2 class="text-2xl font-bold mb-2">👥 Browse Raqis</h2>
          <p class="text-purple-100">View profiles and availability of Raqis</p>
        </router-link>
      </div>

      <!-- Recent Appointments -->
      <div class="bg-white rounded-lg shadow mt-8 p-6">
        <h2 class="text-2xl font-bold mb-4">Recent Appointments</h2>
        <div v-if="recentAppointments.length" class="space-y-4">
          <div
            v-for="apt in recentAppointments"
            :key="apt.id"
            class="border rounded-lg p-4 flex justify-between items-center"
          >
            <div>
              <h3 class="font-bold">{{ apt.lead_raqi?.user?.full_name }}</h3>
              <p class="text-sm text-gray-600">{{ formatDateTime(apt.scheduled_at) }}</p>
              <p class="text-sm text-gray-600">{{ apt.session_type }}</p>
            </div>
            <span :class="`px-3 py-1 rounded text-sm font-medium ${getStatusClass(apt.status)}`">
              {{ apt.status }}
            </span>
          </div>
        </div>
        <p v-else class="text-gray-600">No recent appointments</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { user } from '../../store';
import { patientAPI } from '../../api';
import { formatDateTime, getStatusClass } from '../../utils';

const stats = ref({
  upcomingCount: 0,
  completedCount: 0,
  unreadCount: 0
});

const recentAppointments = ref([]);

onMounted(async () => {
  try {
    const response = await patientAPI.appointments.list();
    const appointments = response.data.data;

    recentAppointments.value = appointments.slice(0, 3);
    stats.value.upcomingCount = appointments.filter(a => a.status === 'accepted').length;
    stats.value.completedCount = appointments.filter(a => a.status === 'completed').length;

    const notificationsResponse = await patientAPI.notifications.list();
    stats.value.unreadCount = notificationsResponse.data.data.filter(n => !n.read_at).length;
  } catch (error) {
    console.error('Failed to load dashboard:', error);
  }
});
</script>
