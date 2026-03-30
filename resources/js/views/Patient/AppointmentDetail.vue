<template>
  <div class="page">
    <div class="page-inner-md">
      <router-link to="/patient/appointments" class="back-link">← My Appointments</router-link>

      <div v-if="appointment" class="space-y-4">
        <!-- Header -->
        <div class="card-gold">
          <div class="flex items-start justify-between mb-4">
            <div>
              <p class="eyebrow">Appointment</p>
              <h1 class="page-title mb-0">{{ appointment.lead_raqi?.user?.full_name }}</h1>
              <p class="text-xs text-cream/35 mt-1">{{ appointment.lead_raqi?.specialization }}</p>
            </div>
            <span :class="`badge badge-${appointment.status}`">{{ appointment.status }}</span>
          </div>
          <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
            <div class="p-3 rounded-lg bg-white/[0.03] border border-white/5"><p class="sec-label">Type</p><p class="text-sm text-cream/70 capitalize">{{ appointment.session_type }}</p></div>
            <div class="p-3 rounded-lg bg-white/[0.03] border border-white/5"><p class="sec-label">Date</p><p class="text-sm text-cream/70">{{ formatDateTime(appointment.scheduled_at) }}</p></div>
            <div class="p-3 rounded-lg bg-white/[0.03] border border-white/5"><p class="sec-label">Duration</p><p class="text-sm text-cream/70">{{ appointment.duration_minutes }} min</p></div>
            <div class="p-3 rounded-lg bg-white/[0.03] border border-white/5"><p class="sec-label">Status</p><p class="text-sm text-cream/70 capitalize">{{ appointment.status }}</p></div>
          </div>
        </div>

        <!-- Patient notes -->
        <div v-if="appointment.patient_notes" class="card">
          <p class="sec-label mb-2">My Notes for Raqi</p>
          <p class="text-sm text-cream/65 leading-relaxed">{{ appointment.patient_notes }}</p>
        </div>

        <!-- Participants -->
        <div v-if="appointment.participants?.length" class="card">
          <p class="sec-label mb-3">Raqis in this Session</p>
          <div class="space-y-3">
            <div v-for="p in appointment.participants" :key="p.id" class="flex items-center justify-between py-2 border-b border-white/5 last:border-0">
              <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-gold/10 flex items-center justify-center text-gold text-xs font-bold">
                  {{ p.raqi?.user?.full_name?.charAt(0) }}
                </div>
                <div>
                  <p class="text-sm text-cream/80">{{ p.raqi?.user?.full_name }}</p>
                  <p class="text-[10px] text-gold/50 uppercase tracking-tighter">{{ p.role }} Raqi</p>
                </div>
              </div>
              <span :class="`badge badge-${p.invite_status} text-[10px]`">{{ p.invite_status }}</span>
            </div>
          </div>
        </div>

        <!-- Cancellation -->
        <div v-if="appointment.status === 'pending' || appointment.status === 'confirmed'" class="card border-red-900/20">
          <p class="sec-label text-red-400/70 mb-2">Cancel Appointment</p>
          <p class="text-xs text-cream/40 mb-4">You can cancel this appointment if it's more than 2 hours before the scheduled time.</p>
          <button @click="cancelAppointment" class="btn-danger w-full text-sm">Cancel Appointment</button>
        </div>
      </div>

      <div v-else-if="loading" class="space-y-4">
        <div class="skeleton h-48"></div>
        <div class="skeleton h-32"></div>
      </div>

      <div v-else class="card empty-state">
        <p>Appointment not found.</p>
        <router-link to="/patient/appointments" class="btn-gold mt-4">Back to Appointments</router-link>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { patientAPI } from '../../api';
import { formatDateTime, unwrap } from '../../utils';

const route = useRoute();
const router = useRouter();
const appointment = ref(null);
const loading = ref(true);

onMounted(async () => {
  try {
    const res = await patientAPI.appointments.get(route.params.id);
    appointment.value = unwrap(res);
  } catch (e) {
    console.error(e);
  } finally {
    loading.value = false;
  }
});

const cancelAppointment = async () => {
  if (!confirm('Are you sure you want to cancel this appointment?')) return;
  try {
    await patientAPI.appointments.cancel(route.params.id);
    router.push('/patient/appointments');
  } catch (e) {
    alert(e.response?.data?.message || 'Failed to cancel appointment');
  }
};
</script>

<style scoped>
@import '../../styles/app.css';
.eyebrow { color: rgba(201,168,76,0.55); font-size: 0.65rem; letter-spacing: 0.4em; text-transform: uppercase; margin-bottom: 0.5rem; }
.text-cream { color: #f5f0e8; }
.w-full { width: 100%; }
.badge-pending { background: rgba(201,168,76,0.1); color: #c9a84c; border: 1px solid rgba(201,168,76,0.2); }
.badge-confirmed { background: rgba(34,197,94,0.1); color: #4ade80; border: 1px solid rgba(34,197,94,0.2); }
.badge-cancelled { background: rgba(239,68,68,0.1); color: #f87171; border: 1px solid rgba(239,68,68,0.2); }
.badge-completed { background: rgba(59,130,246,0.1); color: #60a5fa; border: 1px solid rgba(59,130,246,0.2); }
</style>
