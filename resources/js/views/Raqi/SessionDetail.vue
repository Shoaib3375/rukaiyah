<template>
  <div class="page">
    <div class="page-inner-md">
      <router-link to="/raqi/appointments" class="back-link">← Appointments</router-link>

      <div v-if="appointment" class="space-y-4">
        <!-- Header -->
        <div class="card-gold">
          <div class="flex items-start justify-between mb-4">
            <div>
              <p class="eyebrow">Session</p>
              <h1 class="page-title mb-0">{{ appointment.patient_profile?.user?.full_name }}</h1>
            </div>
            <span :class="`badge badge-${appointment.status}`">{{ appointment.status }}</span>
          </div>
          <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
            <div class="p-3 rounded-lg bg-white/[0.03]"><p class="sec-label">Type</p><p class="text-sm text-cream/70 capitalize">{{ appointment.session_type }}</p></div>
            <div class="p-3 rounded-lg bg-white/[0.03]"><p class="sec-label">Date</p><p class="text-sm text-cream/70">{{ formatDateTime(appointment.scheduled_at) }}</p></div>
            <div class="p-3 rounded-lg bg-white/[0.03]"><p class="sec-label">Duration</p><p class="text-sm text-cream/70">{{ appointment.duration_minutes }} min</p></div>
            <div class="p-3 rounded-lg bg-white/[0.03]"><p class="sec-label">Status</p><p class="text-sm text-cream/70 capitalize">{{ appointment.status }}</p></div>
          </div>
        </div>

        <!-- Accept / Decline -->
        <div v-if="appointment.status === 'pending'" class="flex gap-3">
          <button @click="acceptAppointment" class="btn-success flex-1">✓ Accept</button>
          <button @click="declineAppointment" class="btn-danger flex-1">✕ Decline</button>
        </div>

        <!-- Patient notes -->
        <div v-if="appointment.patient_notes" class="card">
          <p class="sec-label">Patient's Notes</p>
          <p class="text-sm text-cream/55 leading-relaxed">{{ appointment.patient_notes }}</p>
        </div>

        <!-- Session notes -->
        <div class="card">
          <p class="sec-label mb-4">Session Notes</p>
          <form v-if="appointment.status === 'accepted'" @submit.prevent="addNote" class="space-y-3 mb-6">
            <div class="field">
              <label>Note Type</label>
              <select v-model="newNote.note_type">
                <option value="ruqyah_log">Ruqyah Log</option>
                <option value="observation">Observation</option>
                <option value="recommendation">Recommendation</option>
                <option value="chat">Chat</option>
              </select>
            </div>
            <div class="field">
              <label>Content</label>
              <textarea v-model="newNote.content" rows="3" placeholder="Add a note…"></textarea>
            </div>
            <button type="submit" class="btn-gold">Add Note</button>
          </form>
          <div v-if="sessionNotes.length" class="space-y-3">
            <div v-for="note in sessionNotes" :key="note.id" class="p-3 rounded-lg bg-white/[0.03] border border-white/5">
              <p class="sec-label capitalize">{{ note.note_type.replace('_', ' ') }}</p>
              <p class="text-sm text-cream/65 leading-relaxed">{{ note.content }}</p>
            </div>
          </div>
          <div v-else class="empty-state py-4">No notes yet</div>
        </div>

        <!-- Invite co-Raqi -->
        <div v-if="appointment.status === 'accepted'" class="card">
          <p class="sec-label mb-3">Invite Co-Raqi</p>
          <form @submit.prevent="inviteCoRaqi" class="flex gap-3">
            <div class="field flex-1">
              <select v-model="inviteForm.raqi_id">
                <option value="">Select a Raqi…</option>
                <option v-for="r in availableRaqis" :key="r.id" :value="r.id">{{ r.user.full_name }}</option>
              </select>
            </div>
            <button type="submit" class="btn-gold shrink-0">Invite</button>
          </form>
        </div>

        <!-- Participants -->
        <div v-if="participants.length" class="card">
          <p class="sec-label mb-3">Participants</p>
          <div class="space-y-2">
            <div v-for="p in participants" :key="p.id" class="flex items-center justify-between py-2 border-b border-white/5 last:border-0">
              <p class="text-sm text-cream/70">{{ p.raqi?.user?.full_name }}</p>
              <span :class="`badge badge-${p.invite_status}`">{{ p.invite_status }}</span>
            </div>
          </div>
        </div>

        <!-- Complete -->
        <div v-if="appointment.status === 'accepted'">
          <button @click="completeAppointment" class="btn-gold w-full">✓ Complete Session</button>
        </div>
      </div>

      <div v-else class="space-y-3">
        <div class="skeleton h-40"></div>
        <div class="skeleton h-32"></div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import { raqiAPI } from '../../api';
import { formatDateTime, unwrap } from '../../utils';

const route = useRoute();
const appointment = ref(null);
const sessionNotes = ref([]);
const participants = ref([]);
const availableRaqis = ref([]);
const newNote = reactive({ content: '', note_type: 'ruqyah_log' });
const inviteForm = reactive({ raqi_id: '' });

onMounted(async () => {
  try {
    const [aptRes, notesRes, partsRes] = await Promise.all([
      raqiAPI.appointments.list(),
      raqiAPI.notes.list(route.params.id),
      raqiAPI.participants.list(route.params.id),
    ]);
    appointment.value = unwrap(aptRes).find(a => a.id === route.params.id);
    sessionNotes.value = unwrap(notesRes);
    participants.value = unwrap(partsRes);
  } catch (e) { console.error(e); }
});

const acceptAppointment = async () => {
  try { await raqiAPI.appointments.accept(route.params.id); appointment.value.status = 'accepted'; }
  catch (e) { alert('Failed'); }
};
const declineAppointment = async () => {
  try { await raqiAPI.appointments.decline(route.params.id); appointment.value.status = 'declined'; }
  catch (e) { alert('Failed'); }
};
const addNote = async () => {
  try { const r = await raqiAPI.notes.create(route.params.id, newNote); sessionNotes.value.push(r.data.data); newNote.content = ''; }
  catch (e) { alert('Failed to add note'); }
};
const inviteCoRaqi = async () => {
  try { await raqiAPI.participants.invite(route.params.id, { raqi_id: inviteForm.raqi_id }); inviteForm.raqi_id = ''; }
  catch (e) { alert('Failed to invite'); }
};
const completeAppointment = async () => {
  try { await raqiAPI.appointments.complete(route.params.id); appointment.value.status = 'completed'; }
  catch (e) { alert('Failed'); }
};
</script>

<style scoped>
@import '../../styles/app.css';
.eyebrow { color: rgba(201,168,76,0.55); font-size: 0.65rem; letter-spacing: 0.4em; text-transform: uppercase; margin-bottom: 0.5rem; }
.text-cream { color: #f5f0e8; }
.w-full { width: 100%; }
.flex-1 { flex: 1; }
</style>
