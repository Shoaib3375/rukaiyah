<template>
  <div class="page">
    <div class="page-inner">

      <!-- Header -->
      <div class="mb-8">
        <p class="eyebrow">Patient</p>
        <h1 class="page-title">Welcome back, {{ user?.full_name?.split(' ')[0] }}</h1>
        <p class="page-sub">Your spiritual healing journey continues</p>
      </div>

      <!-- Stats -->
      <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
        <div class="stat-card">
          <div class="stat-label">Upcoming</div>
          <div class="stat-value">{{ stats.upcomingCount }}</div>
        </div>
        <div class="stat-card">
          <div class="stat-label">Completed</div>
          <div class="stat-value" style="color:#a5b4fc">{{ stats.completedCount }}</div>
        </div>
        <div class="stat-card">
          <div class="stat-label">Notifications</div>
          <div class="stat-value" style="color:#fbbf24">{{ stats.unreadCount }}</div>
        </div>
      </div>

      <!-- Quick actions -->
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
        <router-link to="/patient/appointments/book" class="action-card">
          <div class="action-card-icon">📅</div>
          <div class="action-card-title">Book Appointment</div>
          <div class="action-card-sub">Find and book with an available Raqi</div>
        </router-link>
        <router-link to="/raqis" class="action-card">
          <div class="action-card-icon">👥</div>
          <div class="action-card-title">Browse Raqis</div>
          <div class="action-card-sub">View profiles and availability</div>
        </router-link>
      </div>

      <!-- Recent appointments -->
      <div class="card-gold">
        <p class="sec-label">Recent Appointments</p>
        <div v-if="loading" class="space-y-3">
          <div v-for="i in 3" :key="i" class="skeleton h-14"></div>
        </div>
        <div v-else-if="recentAppointments.length" class="space-y-2">
          <div v-for="apt in recentAppointments" :key="apt.id"
            class="flex items-center justify-between py-3 border-b border-white/5 last:border-0">
            <div>
              <p class="text-sm text-cream/80 font-medium">{{ apt.lead_raqi?.user?.full_name }}</p>
              <p class="text-xs text-cream/35 mt-0.5">{{ formatDateTime(apt.scheduled_at) }} · {{ apt.session_type }}</p>
            </div>
            <span :class="`badge badge-${apt.status}`">{{ apt.status }}</span>
          </div>
        </div>
        <div v-else class="empty-state">No recent appointments</div>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { user } from '../../store';
import { patientAPI } from '../../api';
import { formatDateTime, unwrap } from '../../utils';

const stats = ref({ upcomingCount: 0, completedCount: 0, unreadCount: 0 });
const recentAppointments = ref([]);
const loading = ref(true);

onMounted(async () => {
  try {
    const [aptRes, notifRes] = await Promise.all([
      patientAPI.appointments.list(),
      patientAPI.notifications.list()
    ]);
    const apts = unwrap(aptRes);
    const notifs = unwrap(notifRes);
    recentAppointments.value = apts.slice(0, 3);
    stats.value.upcomingCount = apts.filter(a => a.status === 'accepted').length;
    stats.value.completedCount = apts.filter(a => a.status === 'completed').length;
    stats.value.unreadCount = notifs.filter(n => !n.read_at).length;
  } catch (e) {
    console.error(e);
  } finally {
    loading.value = false;
  }
});
</script>

<style scoped>
@import '../../styles/app.css';
.text-cream { color: #f5f0e8; }
.eyebrow { color: rgba(201,168,76,0.55); font-size: 0.65rem; letter-spacing: 0.4em; text-transform: uppercase; margin-bottom: 0.5rem; }
</style>
