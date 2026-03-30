<template>
  <div class="page">
    <div class="page-inner-md">
      <p class="eyebrow">Patient</p>
      <h1 class="page-title">My Appointments</h1>
      <p class="page-sub">Track and manage your healing sessions</p>

      <div class="filter-tabs mb-6">
        <button v-for="s in statuses" :key="s" @click="filterStatus = s"
          :class="['filter-tab', filterStatus === s && 'active']">
          {{ s === 'all' ? 'All' : s.charAt(0).toUpperCase() + s.slice(1) }}
        </button>
      </div>

      <div v-if="loading" class="space-y-3">
        <div v-for="i in 4" :key="i" class="skeleton h-24"></div>
      </div>

      <div v-else-if="filteredAppointments.length" class="space-y-3">
        <div v-for="apt in filteredAppointments" :key="apt.id" class="card">
          <div class="flex items-start justify-between mb-3">
            <div>
              <p class="font-medium text-cream/90">{{ apt.lead_raqi?.user?.full_name }}</p>
              <p class="text-xs text-cream/35 mt-0.5 capitalize">{{ apt.session_type }}</p>
            </div>
            <span :class="`badge badge-${apt.status}`">{{ apt.status }}</span>
          </div>
          <div class="grid grid-cols-2 gap-3 mb-4">
            <div>
              <p class="sec-label">Scheduled</p>
              <p class="text-sm text-cream/70">{{ formatDateTime(apt.scheduled_at) }}</p>
            </div>
            <div>
              <p class="sec-label">Duration</p>
              <p class="text-sm text-cream/70">{{ apt.duration_minutes }} min</p>
            </div>
          </div>
          <div v-if="apt.patient_notes" class="mb-4 p-3 rounded-lg bg-white/[0.03] text-xs text-cream/45 leading-relaxed">
            {{ apt.patient_notes }}
          </div>
          <div class="flex gap-2">
            <router-link :to="`/patient/appointments/${apt.id}`" class="btn-ghost text-xs">View Details</router-link>
            <button v-if="apt.status === 'pending'" @click="cancelAppointment(apt.id)" class="btn-danger text-xs">Cancel</button>
          </div>
        </div>
      </div>

      <div v-else class="card empty-state">
        No appointments found.
        <router-link to="/patient/appointments/book" class="block mt-2 text-gold text-sm hover:text-gold-light">Book your first session →</router-link>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { patientAPI } from '../../api';
import { formatDateTime, unwrap } from '../../utils';

const appointments = ref([]);
const filterStatus = ref('all');
const loading = ref(true);
const statuses = ['all', 'pending', 'accepted', 'completed', 'canceled'];

const filteredAppointments = computed(() =>
  filterStatus.value === 'all' ? appointments.value
    : appointments.value.filter(a => a.status === filterStatus.value)
);

onMounted(async () => {
  try {
    const res = await patientAPI.appointments.list();
    appointments.value = unwrap(res);
  } catch (e) { console.error(e); } finally { loading.value = false; }
});

const cancelAppointment = async (id) => {
  if (!confirm('Cancel this appointment?')) return;
  try {
    await patientAPI.appointments.cancel(id);
    appointments.value = appointments.value.filter(a => a.id !== id);
  } catch (e) { alert('Failed to cancel'); }
};
</script>

<style scoped>
@import '../../styles/app.css';
.text-cream { color: #f5f0e8; }
.text-gold { color: #c9a84c; }
.text-gold-light { color: #e8cc7a; }
.eyebrow { color: rgba(201,168,76,0.55); font-size: 0.65rem; letter-spacing: 0.4em; text-transform: uppercase; margin-bottom: 0.5rem; }
</style>
