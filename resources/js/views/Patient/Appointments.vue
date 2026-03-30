<template>
  <div class="min-h-screen bg-gray-50 py-12 px-4">
    <div class="max-w-4xl mx-auto">
      <h1 class="text-3xl font-bold mb-8">My Appointments</h1>

      <!-- Filters -->
      <div class="bg-white rounded-lg shadow p-4 mb-6 flex gap-2">
        <button
          v-for="status in ['all', 'pending', 'accepted', 'completed', 'canceled']"
          :key="status"
          @click="filterStatus = status"
          :class="[
            'px-4 py-2 rounded-lg font-medium transition',
            filterStatus === status
              ? 'bg-blue-600 text-white'
              : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
          ]"
        >
          {{ status.charAt(0).toUpperCase() + status.slice(1) }}
        </button>
      </div>

      <!-- Appointments List -->
      <div v-if="filteredAppointments.length" class="space-y-4">
        <div
          v-for="apt in filteredAppointments"
          :key="apt.id"
          class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition"
        >
          <div class="flex justify-between items-start mb-4">
            <div>
              <h2 class="text-2xl font-bold">{{ apt.lead_raqi?.user?.full_name }}</h2>
              <p class="text-gray-600">{{ apt.session_type }}</p>
            </div>
            <span :class="`px-4 py-2 rounded-full text-sm font-medium ${getStatusClass(apt.status)}`">
              {{ apt.status }}
            </span>
          </div>

          <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
              <p class="text-sm text-gray-600">Scheduled</p>
              <p class="font-medium">{{ formatDateTime(apt.scheduled_at) }}</p>
            </div>
            <div>
              <p class="text-sm text-gray-600">Duration</p>
              <p class="font-medium">{{ apt.duration_minutes }} minutes</p>
            </div>
          </div>

          <div v-if="apt.patient_notes" class="mb-4 p-3 bg-gray-50 rounded">
            <p class="text-sm text-gray-600">Your Notes:</p>
            <p class="text-gray-800">{{ apt.patient_notes }}</p>
          </div>

          <div class="flex gap-2">
            <router-link
              :to="`/patient/appointments/${apt.id}`"
              class="flex-1 bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 text-center font-middle"
            >
              View Details
            </router-link>
            <button
              v-if="apt.status === 'pending'"
              @click="cancelAppointment(apt.id)"
              class="flex-1 bg-red-600 text-white py-2 rounded-lg hover:bg-red-700 font-medium"
            >
              Cancel
            </button>
          </div>
        </div>
      </div>

      <div v-else class="bg-white rounded-lg shadow p-8 text-center">
        <p class="text-gray-600 text-lg mb-4">No appointments found</p>
        <router-link to="/patient/appointments/book" class="text-blue-600 hover:underline font-medium">
          Book your first appointment
        </router-link>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { patientAPI } from '../../api';
import { formatDateTime, getStatusClass } from '../../utils';

const appointments = ref([]);
const filterStatus = ref('all');
const loading = ref(false);

const filteredAppointments = computed(() => {
  if (filterStatus.value === 'all') return appointments.value;
  return appointments.value.filter(a => a.status === filterStatus.value);
});

onMounted(async () => {
  loading.value = true;
  try {
    const response = await patientAPI.appointments.list();
    appointments.value = response.data.data;
  } catch (error) {
    console.error('Failed to load appointments:', error);
  } finally {
    loading.value = false;
  }
});

const cancelAppointment = async (id) => {
  if (confirm('Are you sure you want to cancel this appointment?')) {
    try {
      await patientAPI.appointments.cancel(id);
      appointments.value = appointments.value.filter(a => a.id !== id);
    } catch (error) {
      alert('Failed to cancel appointment');
    }
  }
};
</script>
