<template>
  <div class="page">
    <div class="page-inner">
      <p class="eyebrow">Admin</p>
      <h1 class="page-title">Dashboard</h1>
      <p class="page-sub">Platform overview and management</p>

      <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8">
        <div class="stat-card"><div class="stat-label">Total Users</div><div class="stat-value">{{ stats.totalUsers }}</div></div>
        <div class="stat-card"><div class="stat-label">Active Raqis</div><div class="stat-value" style="color:#4ade80">{{ stats.activeRaqis }}</div></div>
        <div class="stat-card"><div class="stat-label">Pending Approvals</div><div class="stat-value" style="color:#fbbf24">{{ stats.pendingRaqis }}</div></div>
        <div class="stat-card"><div class="stat-label">Appointments</div><div class="stat-value" style="color:#a5b4fc">{{ stats.totalAppointments }}</div></div>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <router-link to="/admin/raqis" class="action-card">
          <div class="action-card-icon">⭐</div>
          <div class="action-card-title">Pending Approvals</div>
          <div class="action-card-sub">Review and approve new Raqis</div>
        </router-link>
        <router-link to="/admin/users" class="action-card">
          <div class="action-card-icon">👥</div>
          <div class="action-card-title">Users</div>
          <div class="action-card-sub">Manage all user accounts</div>
        </router-link>
        <router-link to="/admin/appointments" class="action-card">
          <div class="action-card-icon">📅</div>
          <div class="action-card-title">Appointments</div>
          <div class="action-card-sub">View all platform sessions</div>
        </router-link>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { adminAPI } from '../../api';

const stats = ref({ totalUsers: 0, activeRaqis: 0, pendingRaqis: 0, totalAppointments: 0 });

onMounted(async () => {
  try { const r = await adminAPI.stats.index(); stats.value = r.data.data; }
  catch (e) { console.error(e); }
});
</script>

<style scoped>
@import '../../styles/app.css';
.eyebrow { color: rgba(201,168,76,0.55); font-size: 0.65rem; letter-spacing: 0.4em; text-transform: uppercase; margin-bottom: 0.5rem; }
</style>
