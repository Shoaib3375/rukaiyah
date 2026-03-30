<template>
  <div class="min-h-screen bg-gray-50 py-12 px-4">
    <div class="max-w-6xl mx-auto">
      <h1 class="text-3xl font-bold mb-8">My Appointments</h1>

      <div class="bg-white rounded-lg shadow p-4 mb-6 flex gap-2">
        <button
          v-for="status in ['all', 'pending', 'accepted', 'completed']"
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

      <div v-if="filteredAppointments.length" class="space-y-4">
        <router-link
          v-for="apt in filteredAppointments"
          :key="apt.id"
          :to="`/raqi/sessions/${apt.id}`"
          class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition block"
        >
          <div class="flex justify-between items-start mb-4">
            <div>
              <h2 class="text-2xl font-bold">{{ apt.patient_profile?.user?.full_name }}</h2>
              <p class="text-gray-600">{{ apt.session_type }}</p>
            </div>
            <span :class="`px-4 py-2 rounded-full text-sm font-medium ${getStatusClass(apt.status)}`">
              {{ apt.status }}
            </span>
          </div>

          <div class="grid grid-cols-2 gap-4">
            <div>
              <p class="text-sm text-gray-600">Scheduled</p>
              <p class="font-medium">{{ formatDateTime(apt.scheduled_at) }}</p>
            </div>
            <div>
              <p class="text-sm text-gray-600">Duration</p>
              <p class="font-medium">{{ apt.duration_minutes }} minutes</p>
            </div>
          </div>
        </router-link>
      </div>

      <div v-else class="bg-white rounded-lg shadow p-8 text-center">
        <p class="text-gray-600">No appointments found</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { raqiAPI } from '../../api';
import { formatDateTime, getStatusClass } from '../../utils';

const appointments = ref([]);
const filterStatus = ref('all');

const filteredAppointments = computed(() => {
  if (filterStatus.value === 'all') return appointments.value;
  return appointments.value.filter(a => a.status === filterStatus.value);
});

onMounted(async () => {
  try {
    const response = await raqiAPI.appointments.list();
    appointments.value = response.data.data;
  } catch (error) {
    console.error('Failed to load appointments:', error);
  }
});
</script>
