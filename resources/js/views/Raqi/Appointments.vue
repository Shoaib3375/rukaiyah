<template>
  <div class="page">
    <div class="page-inner-md">
      <p class="eyebrow">Raqi</p>
      <h1 class="page-title">Appointments</h1>
      <p class="page-sub">View and manage your assigned sessions</p>

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
        <router-link v-for="apt in filteredAppointments" :key="apt.id"
          :to="`/raqi/sessions/${apt.id}`" class="card apt-row">
          <div class="flex items-start justify-between mb-2">
            <div>
              <p class="font-medium text-cream/90">{{ apt.patient?.full_name }}</p>
              <p class="text-xs text-cream/35 mt-0.5 capitalize">{{ apt.session_type }}</p>
            </div>
            <span :class="`badge badge-${apt.status}`">{{ apt.status }}</span>
          </div>
          <div class="grid grid-cols-2 gap-3">
            <div>
              <p class="sec-label">Scheduled</p>
              <p class="text-sm text-cream/60">{{ formatDateTime(apt.scheduled_at) }}</p>
            </div>
            <div>
              <p class="sec-label">Duration</p>
              <p class="text-sm text-cream/60">{{ apt.duration_minutes }} min</p>
            </div>
          </div>
        </router-link>
      </div>

      <div v-else class="card empty-state">No appointments found</div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { raqiAPI } from '../../api';
import { formatDateTime, unwrap } from '../../utils';

const appointments = ref([]);
const filterStatus = ref('all');
const loading = ref(true);
const statuses = ['all', 'pending', 'confirmed', 'completed'];

const filteredAppointments = computed(() =>
  filterStatus.value === 'all' ? appointments.value
    : appointments.value.filter(a => a.status === filterStatus.value)
);

onMounted(async () => {
  try { const r = await raqiAPI.appointments.list(); appointments.value = unwrap(r); }
  catch (e) { console.error(e); } finally { loading.value = false; }
});
</script>

<style scoped>
@import '../../styles/app.css';
.eyebrow { color: rgba(201,168,76,0.55); font-size: 0.65rem; letter-spacing: 0.4em; text-transform: uppercase; margin-bottom: 0.5rem; }
.text-cream { color: #f5f0e8; }
.apt-row { display: block; transition: all 0.2s; }
.apt-row:hover { border-color: rgba(201,168,76,0.25); transform: translateX(2px); }
</style>
