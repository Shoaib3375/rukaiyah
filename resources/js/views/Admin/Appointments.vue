<template>
  <div class="page">
    <div class="page-inner">
      <p class="eyebrow">Admin</p>
      <h1 class="page-title">Appointments</h1>
      <p class="page-sub">All platform sessions at a glance</p>

      <div class="flex gap-3 mb-6">
        <div class="field flex-1" style="max-width:24rem">
          <input v-model="searchQuery" type="text" placeholder="Search by patient or Raqi…" />
        </div>
        <div class="field" style="width:10rem">
          <select v-model="statusFilter">
            <option value="">All Status</option>
            <option value="pending">Pending</option>
            <option value="accepted">Accepted</option>
            <option value="completed">Completed</option>
            <option value="canceled">Canceled</option>
          </select>
        </div>
      </div>

      <div class="card" style="padding:0;overflow:hidden">
        <table class="dark-table">
          <thead>
            <tr>
              <th>Patient</th>
              <th>Raqi</th>
              <th>Date & Time</th>
              <th>Type</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="apt in filteredAppointments" :key="apt.id">
              <td style="color:rgba(245,240,232,0.85)" class="font-medium">{{ apt.patient_profile?.user?.full_name }}</td>
              <td>{{ apt.lead_raqi?.user?.full_name }}</td>
              <td>{{ formatDateTime(apt.scheduled_at) }}</td>
              <td class="capitalize">{{ apt.session_type }}</td>
              <td><span :class="`badge badge-${apt.status}`">{{ apt.status }}</span></td>
            </tr>
          </tbody>
        </table>
        <div v-if="!filteredAppointments.length" class="empty-state">No appointments found</div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { adminAPI } from '../../api';
import { formatDateTime, unwrap } from '../../utils';

const appointments = ref([]);
const searchQuery = ref('');
const statusFilter = ref('');

const filteredAppointments = computed(() =>
  appointments.value.filter(apt =>
    (apt.patient_profile?.user?.full_name?.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
     apt.lead_raqi?.user?.full_name?.toLowerCase().includes(searchQuery.value.toLowerCase())) &&
    (!statusFilter.value || apt.status === statusFilter.value)
  )
);

onMounted(async () => {
  try { const r = await adminAPI.appointments.list({}); appointments.value = unwrap(r); }
  catch (e) { console.error(e); }
});
</script>

<style scoped>
@import '../../styles/app.css';
.eyebrow { color: rgba(201,168,76,0.55); font-size: 0.65rem; letter-spacing: 0.4em; text-transform: uppercase; margin-bottom: 0.5rem; }
.flex-1 { flex: 1; }
</style>
