<template>
  <div class="page">
    <div class="page-inner">
      <p class="eyebrow">Raqi</p>
      <h1 class="page-title">Welcome back, {{ user?.full_name?.split(' ')[0] }}</h1>
      <p class="page-sub">Manage your healing sessions and availability</p>

      <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
        <div class="stat-card">
          <div class="stat-label">Pending Requests</div>
          <div class="stat-value" style="color:#fbbf24">{{ stats.pendingCount }}</div>
        </div>
        <div class="stat-card">
          <div class="stat-label">Active Sessions</div>
          <div class="stat-value" style="color:#4ade80">{{ stats.activeCount }}</div>
        </div>
        <div class="stat-card">
          <div class="stat-label">Completed</div>
          <div class="stat-value" style="color:#a5b4fc">{{ stats.completedCount }}</div>
        </div>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
        <router-link to="/raqi/availability" class="action-card">
          <div class="action-card-icon">📅</div>
          <div class="action-card-title">Manage Availability</div>
          <div class="action-card-sub">Set your available time slots</div>
        </router-link>
        <router-link to="/raqi/appointments" class="action-card">
          <div class="action-card-icon">👥</div>
          <div class="action-card-title">Appointments</div>
          <div class="action-card-sub">View and manage sessions</div>
        </router-link>
      </div>

      <div class="card-gold">
        <p class="sec-label">Pending Requests</p>
        <div v-if="pendingAppointments.length" class="space-y-3">
          <div v-for="apt in pendingAppointments" :key="apt.id"
            class="flex items-center justify-between py-3 border-b border-white/5 last:border-0">
            <div>
              <p class="text-sm text-cream/85 font-medium">{{ apt.patient_profile?.user?.full_name }}</p>
              <p class="text-xs text-cream/35 mt-0.5">{{ formatDateTime(apt.scheduled_at) }} · {{ apt.session_type }}</p>
            </div>
            <div class="flex gap-2">
              <button @click="acceptAppointment(apt.id)" class="btn-success">Accept</button>
              <button @click="declineAppointment(apt.id)" class="btn-danger">Decline</button>
            </div>
          </div>
        </div>
        <div v-else class="empty-state">No pending requests</div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { user } from '../../store';
import { raqiAPI } from '../../api';
import { formatDateTime, unwrap } from '../../utils';

const stats = ref({ pendingCount: 0, activeCount: 0, completedCount: 0 });
const pendingAppointments = ref([]);

onMounted(async () => {
  try {
    const r = await raqiAPI.appointments.list();
    const apts = unwrap(r);
    pendingAppointments.value = apts.filter(a => a.status === 'pending').slice(0, 5);
    stats.value.pendingCount = apts.filter(a => a.status === 'pending').length;
    stats.value.activeCount = apts.filter(a => a.status === 'accepted').length;
    stats.value.completedCount = apts.filter(a => a.status === 'completed').length;
  } catch (e) { console.error(e); }
});

const acceptAppointment = async (id) => {
  try { await raqiAPI.appointments.accept(id); pendingAppointments.value = pendingAppointments.value.filter(a => a.id !== id); stats.value.pendingCount--; stats.value.activeCount++; }
  catch (e) { alert('Failed to accept'); }
};
const declineAppointment = async (id) => {
  try { await raqiAPI.appointments.decline(id); pendingAppointments.value = pendingAppointments.value.filter(a => a.id !== id); stats.value.pendingCount--; }
  catch (e) { alert('Failed to decline'); }
};
</script>

<style scoped>
@import '../../styles/app.css';
.eyebrow { color: rgba(201,168,76,0.55); font-size: 0.65rem; letter-spacing: 0.4em; text-transform: uppercase; margin-bottom: 0.5rem; }
.text-cream { color: #f5f0e8; }
</style>
