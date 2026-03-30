<template>
  <div class="min-h-screen bg-gray-50 py-12 px-4">
    <div class="max-w-7xl mx-auto">
      <h1 class="text-3xl font-bold mb-8">All Appointments</h1>

      <!-- Filters -->
      <div class="bg-white rounded-lg shadow p-4 mb-6 flex gap-2">
        <input
          v-model="searchQuery"
          type="text"
          placeholder="Search appointments..."
          class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
        />
        <select
          v-model="statusFilter"
          class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
        >
          <option value="">All Status</option>
          <option value="pending">Pending</option>
          <option value="accepted">Accepted</option>
          <option value="completed">Completed</option>
        </select>
      </div>

      <!-- Appointments Table -->
      <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full text-sm">
          <thead class="bg-gray-100 border-b">
            <tr>
              <th class="px-6 py-3 text-left font-bold">Patient</th>
              <th class="px-6 py-3 text-left font-bold">Raqi</th>
              <th class="px-6 py-3 text-left font-bold">Date & Time</th>
              <th class="px-6 py-3 text-left font-bold">Type</th>
              <th class="px-6 py-3 text-left font-bold">Status</th>
              <th class="px-6 py-3 text-left font-bold">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y">
            <tr v-for="apt in filteredAppointments" :key="apt.id" class="hover:bg-gray-50">
              <td class="px-6 py-4 font-medium">{{ apt.patient_profile?.user?.full_name }}</td>
              <td class="px-6 py-4">{{ apt.lead_raqi?.user?.full_name }}</td>
              <td class="px-6 py-4">{{ formatDateTime(apt.scheduled_at) }}</td>
              <td class="px-6 py-4">{{ apt.session_type }}</td>
              <td class="px-6 py-4">
                <span :class="`px-3 py-1 rounded-full text-xs font-medium ${getStatusClass(apt.status)}`">
                  {{ apt.status }}
                </span>
              </td>
              <td class="px-6 py-4 flex gap-2">
                <button
                  @click="viewDetail(apt.id)"
                  class="text-blue-600 hover:underline"
                >
                  View
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div v-if="filteredAppointments.length === 0" class="bg-white rounded-lg shadow p-8 text-center mt-4">
        <p class="text-gray-600">No appointments found</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { adminAPI } from '../../api';
import { formatDateTime, getStatusClass } from '../../utils';

const router = useRouter();
const appointments = ref([]);
const searchQuery = ref('');
const statusFilter = ref('');

const filteredAppointments = computed(() => {
  return appointments.value.filter(apt =>
    (apt.patient_profile?.user?.full_name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
     apt.lead_raqi?.user?.full_name.toLowerCase().includes(searchQuery.value.toLowerCase())) &&
    (!statusFilter.value || apt.status === statusFilter.value)
  );
});

onMounted(async () => {
  try {
    const response = await adminAPI.appointments.list({});
    appointments.value = response.data.data;
  } catch (error) {
    console.error('Failed to load appointments:', error);
  }
});

const viewDetail = (id) => {
  // Navigate to detail view or modal
  console.log('View appointment:', id);
};
</script>
