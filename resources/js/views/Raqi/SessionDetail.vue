<template>
  <div class="page-wrapper">
    <div class="page">
      <router-link to="/raqi/appointments" class="back-link">← All Appointments</router-link>

      <div v-if="appointment" class="content-fade">
        <!-- HEADER -->
        <div class="detail-header">
          <div>
            <h1 class="detail-heading">Session Details</h1>
            <div v-if="appointment.id" class="detail-id">ID: #APPT-{{ appointment.id.split('-')[0].toUpperCase() }} &nbsp;·&nbsp; Scheduled on {{ formatDate(appointment.scheduled_at) }}</div>
          </div>
          <div class="header-badge-row">
            <span :class="['status-badge', appointment.status]">{{ appointment.status }}</span>
          </div>
        </div>

        <!-- STATUS BANNER -->
        <div :class="['status-banner', appointment.status]">
          <div class="banner-left">
            <div class="banner-icon">{{ getStatusIcon(appointment.status) }}</div>
            <div>
              <div class="banner-title">{{ getStatusTitle(appointment.status) }}</div>
              <div class="banner-sub">{{ getStatusSub(appointment.status) }}</div>
            </div>
          </div>
          <div v-if="appointment.status === 'pending'" class="banner-actions">
            <button @click="acceptAppointment" class="btn btn-primary btn-sm">✓ Accept Session</button>
            <button @click="declineAppointment" class="btn btn-danger btn-sm">✕ Decline</button>
          </div>
        </div>

        <!-- TWO-COLUMN GRID -->
        <div class="detail-grid">

          <!-- MAIN COLUMN -->
          <div class="main-col">
            
            <!-- SESSION INFO -->
            <div class="d-card">
              <div class="d-card-head">
                <div class="d-card-title"><span class="d-card-title-icon">📋</span> Appointment Info</div>
              </div>
              <div class="d-card-body">
                <div class="info-grid">
                  <div class="info-item">
                    <div class="info-label">Type</div>
                    <div class="info-value">{{ formatSessionType(appointment.session_type) }}</div>
                  </div>
                  <div class="info-item">
                    <div class="info-label">Date</div>
                    <div class="info-value">{{ formatDate(appointment.scheduled_at) }}</div>
                  </div>
                  <div class="info-item">
                    <div class="info-label">Duration</div>
                    <div class="info-value gold">{{ appointment.duration_minutes }} min</div>
                  </div>
                  <div class="info-item">
                    <div class="info-label">Time</div>
                    <div class="info-value">{{ formatTimeRange(appointment.scheduled_at, appointment.duration_minutes) }}</div>
                  </div>
                </div>
              </div>
            </div>

            <!-- PATIENT INFO -->
            <div class="d-card">
              <div class="d-card-head">
                <div class="d-card-title"><span class="d-card-title-icon">👤</span> Patient Details</div>
              </div>
              <div class="d-card-body">
                <div class="patient-profile-brief">
                  <div class="p-avatar">{{ appointment.patient?.full_name?.charAt(0) }}</div>
                  <div>
                    <div class="p-name">{{ appointment.patient?.full_name }}</div>
                    <div class="p-contact">{{ appointment.patient?.email }} &nbsp;·&nbsp; {{ appointment.patient?.phone || 'No phone' }}</div>
                  </div>
                </div>
                <div v-if="appointment.patient_notes" class="patient-note-box mt-4">
                  <div class="info-label mb-2">Patient's Notes</div>
                  <div class="patient-note-text">{{ appointment.patient_notes }}</div>
                </div>
              </div>
            </div>

            <!-- SESSION NOTES (FOR RAQI) -->
            <div class="d-card">
              <div class="d-card-head">
                <div class="d-card-title"><span class="d-card-title-icon">🌿</span> Session Logs & Notes</div>
              </div>
              <div class="d-card-body">
                <!-- Add Note Form -->
                <form v-if="appointment.status === 'confirmed'" @submit.prevent="addNote" class="note-form mb-6">
                  <div class="grid grid-cols-2 gap-4 mb-3">
                    <div class="field">
                      <label>Note Type</label>
                      <select v-model="newNote.note_type" class="form-input">
                        <option value="ruqyah_log">Ruqyah Log</option>
                        <option value="observation">Observation</option>
                        <option value="recommendation">Recommendation</option>
                        <option value="chat">Internal Chat</option>
                      </select>
                    </div>
                  </div>
                  <div class="field mb-3">
                    <label>Observation / Log Content</label>
                    <textarea v-model="newNote.content" rows="3" class="form-input" placeholder="Enter session findings…"></textarea>
                  </div>
                  <button type="submit" :disabled="!newNote.content" class="btn btn-primary btn-sm">Add Note</button>
                </form>

                <!-- Notes List -->
                <div v-if="sessionNotes.length" class="space-y-4">
                  <div v-for="note in sessionNotes" :key="note.id" class="note-item">
                    <span :class="['note-type-tag', note.note_type]">{{ note.note_type.replace('_', ' ') }}</span>
                    <div class="note-text">{{ note.content }}</div>
                    <div class="note-author">Added on {{ formatDateTime(note.created_at) }}</div>
                  </div>
                </div>
                <div v-else class="empty-notes">No session notes recorded yet.</div>
              </div>
            </div>
          </div>

          <!-- SIDEBAR -->
          <div class="sidebar-col">
            
            <!-- ACTIONS -->
            <div class="d-card">
              <div class="d-card-head">
                <div class="d-card-title"><span class="d-card-title-icon">⚡</span> Management</div>
              </div>
              <div class="d-card-body">
                <div class="action-list">
                  <button v-if="appointment.status === 'confirmed'" class="btn btn-primary" @click="joinSession">
                    🎥 Start Session
                  </button>
                  <button v-if="appointment.status === 'confirmed'" class="btn btn-success" @click="completeAppointment">
                    ✓ Complete Session
                  </button>
                  <button v-if="appointment.status === 'confirmed'" class="btn btn-ghost" @click="showFollowUpForm = !showFollowUpForm">
                    📅 Schedule Follow-up
                  </button>
                </div>

                <!-- Follow-up Form -->
                <div v-if="showFollowUpForm" class="mt-4 p-4 rounded-lg bg-white/[0.03] border border-white/5">
                  <div class="field mb-3">
                    <label>Follow-up Date</label>
                    <input type="date" v-model="followUpForm.date" class="form-input" />
                  </div>
                  <div class="field mb-3">
                    <label>Recommendation</label>
                    <textarea v-model="followUpForm.recommendation" rows="2" class="form-input" placeholder="Instructions…"></textarea>
                  </div>
                  <button @click="scheduleFollowUp" class="btn btn-gold btn-sm w-full">Schedule</button>
                </div>
              </div>
            </div>

            <!-- CO-RAQI INVITE -->
            <div v-if="appointment.status === 'confirmed'" class="d-card">
              <div class="d-card-head">
                <div class="d-card-title"><span class="d-card-title-icon">👥</span> Invite Co-Raqi</div>
              </div>
              <div class="d-card-body">
                <div class="field mb-3">
                  <select v-model="inviteForm.raqi_id" class="form-input">
                    <option value="">Select Raqi…</option>
                    <option v-for="r in availableRaqis" :key="r.id" :value="r.id">{{ r.user?.full_name }}</option>
                  </select>
                </div>
                <button @click="inviteCoRaqi" :disabled="!inviteForm.raqi_id" class="btn btn-ghost btn-sm w-full">Send Invite</button>
              </div>
            </div>

            <!-- PARTICIPANTS -->
            <div class="d-card">
              <div class="d-card-head">
                <div class="d-card-title"><span class="d-card-title-icon">👁️</span> Participants</div>
              </div>
              <div class="d-card-body">
                <div v-for="p in participants" :key="p.id" class="participant-row">
                  <div :class="['p-av', p.role]">{{ p.raqi?.user?.full_name?.charAt(0) }}</div>
                  <div class="p-name">{{ p.raqi?.user?.full_name }}</div>
                  <span :class="['p-badge', p.role]">{{ p.role }}</span>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>

      <div v-else-if="loading" class="loading-state">
        <div class="skeleton-header"></div>
        <div class="skeleton-banner"></div>
        <div class="skeleton-grid">
          <div class="skeleton-main"></div>
          <div class="skeleton-side"></div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import { raqiAPI } from '../../api';
import { formatDateTime, formatDate, unwrap } from '../../utils';

const route = useRoute();
const appointment = ref(null);
const loading = ref(true);
const sessionNotes = ref([]);
const participants = ref([]);
const availableRaqis = ref([]);
const showFollowUpForm = ref(false);

const newNote = reactive({ content: '', note_type: 'ruqyah_log' });
const inviteForm = reactive({ raqi_id: '' });
const followUpForm = reactive({ date: '', recommendation: '' });

onMounted(async () => {
  try {
    const [aptRes, notesRes, partsRes, raqisRes] = await Promise.all([
      raqiAPI.appointments.get(route.params.id),
      raqiAPI.notes.list(route.params.id),
      raqiAPI.participants.list(route.params.id),
      raqiAPI.raqis.list(),
    ]);
    appointment.value = unwrap(aptRes);
    sessionNotes.value = unwrap(notesRes);
    participants.value = unwrap(partsRes);
    availableRaqis.value = unwrap(raqisRes).data; // Paginated
  } catch (e) {
    console.error(e);
  } finally {
    loading.value = false;
  }
});

const acceptAppointment = async () => {
  try { await raqiAPI.appointments.accept(route.params.id); appointment.value.status = 'confirmed'; }
  catch (e) { alert('Failed'); }
};

const declineAppointment = async () => {
  try { await raqiAPI.appointments.decline(route.params.id); appointment.value.status = 'cancelled'; }
  catch (e) { alert('Failed'); }
};

const addNote = async () => {
  try {
    const r = await raqiAPI.notes.create(route.params.id, newNote);
    sessionNotes.value.unshift(r.data.data);
    newNote.content = '';
  } catch (e) { alert('Failed to add note'); }
};

const inviteCoRaqi = async () => {
  try {
    await raqiAPI.participants.invite(route.params.id, { raqi_id: inviteForm.raqi_id });
    const res = await raqiAPI.participants.list(route.params.id);
    participants.value = unwrap(res);
    inviteForm.raqi_id = '';
    alert('Invite sent');
  } catch (e) { alert('Failed to invite'); }
};

const scheduleFollowUp = async () => {
  try {
    await raqiAPI.followup.create(route.params.id, {
      follow_up_date: followUpForm.date,
      recommendation: followUpForm.recommendation
    });
    alert('Follow-up scheduled');
    showFollowUpForm.value = false;
  } catch (e) { alert('Failed to schedule follow-up'); }
};

const completeAppointment = async () => {
  if (!confirm('Mark session as completed?')) return;
  try { await raqiAPI.appointments.complete(route.params.id); appointment.value.status = 'completed'; }
  catch (e) { alert('Failed'); }
};

const joinSession = () => alert('Joining session...');

const getStatusIcon = (s) => ({ confirmed: '✅', pending: '⏳', completed: '✦', cancelled: '✕' }[s] || '📅');
const getStatusTitle = (s) => ({ confirmed: 'Session Confirmed', pending: 'Pending Request', completed: 'Session Completed', cancelled: 'Cancelled' }[s] || s);
const getStatusSub = (s) => ({ confirmed: 'This session is ready to start.', pending: 'Please review and accept or decline this request.', completed: 'This session has been archived.', cancelled: 'This session was cancelled.' }[s] || '');

const formatSessionType = (type) => ({ video: 'Online Video', chat: 'Live Chat', phone: 'Phone Session', in_person: 'In-Person' }[type] || type);

const formatTimeRange = (start, duration) => {
  if (!start) return '';
  const startTime = new Date(start);
  const endTime = new Date(startTime.getTime() + duration * 60000);
  const opt = { hour: 'numeric', minute: '2-digit', hour12: true };
  return `${startTime.toLocaleTimeString([], opt)} — ${endTime.toLocaleTimeString([], opt)}`;
};
</script>

<style scoped>
.page-wrapper { background: #0a0814; min-height: 100vh; color: #f0eade; font-family: 'DM Sans', sans-serif; }
.page { max-width: 1000px; margin: 0 auto; padding: 32px 40px 80px; }
.back-link { display: inline-flex; align-items: center; gap: 7px; font-size: 0.8rem; color: rgba(240,234,222,0.3); text-decoration: none; letter-spacing: 0.06em; margin-bottom: 28px; }
.detail-header { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 32px; gap: 20px; }
.detail-heading { font-family: 'Cinzel', serif; font-size: 1.55rem; font-weight: 400; letter-spacing: 0.07em; color: #f0eade; margin-bottom: 5px; }
.detail-id { font-size: 0.72rem; letter-spacing: 0.18em; color: rgba(240,234,222,0.3); text-transform: uppercase; }
.status-badge { font-size: 0.72rem; letter-spacing: 0.14em; text-transform: uppercase; padding: 6px 16px; border-radius: 30px; font-weight: 500; border: 1px solid transparent; }
.status-badge.confirmed { background: rgba(26,171,122,0.14); color: #2ecc71; border-color: rgba(26,171,122,0.25); }
.status-badge.pending   { background: rgba(224,154,32,0.14); color: #f0b429; border-color: rgba(240,180,41,0.25); }
.status-badge.completed { background: rgba(139,92,246,0.14); color: #a78bfa; border-color: rgba(139,92,246,0.25); }
.status-badge.cancelled { background: rgba(192,57,43,0.14); color: #e74c3c; border-color: rgba(192,57,43,0.25); }

.status-banner { border-radius: 12px; padding: 22px 28px; display: flex; align-items: center; justify-content: space-between; margin-bottom: 28px; border: 1px solid transparent; }
.status-banner.confirmed { background: rgba(26,171,122,0.14); border-color: rgba(26,171,122,0.2); }
.status-banner.pending   { background: rgba(240,180,41,0.10); border-color: rgba(240,180,41,0.2); }
.status-banner.completed { background: rgba(139,92,246,0.14); border-color: rgba(139,92,246,0.2); }
.status-banner.cancelled { background: rgba(192,57,43,0.14); border-color: rgba(192,57,43,0.2); }
.banner-left { display: flex; align-items: center; gap: 16px; }
.banner-icon { font-size: 1.8rem; }
.banner-title { font-family: 'Cinzel', serif; font-size: 1rem; letter-spacing: 0.06em; color: #f0eade; margin-bottom: 3px; }
.banner-sub { font-size: 0.78rem; color: rgba(240,234,222,0.3); }
.banner-actions { display: flex; gap: 10px; }

.detail-grid { display: grid; grid-template-columns: 1fr 340px; gap: 20px; align-items: start; }
.d-card { background: #141228; border: 1px solid rgba(201,168,76,0.13); border-radius: 12px; overflow: hidden; margin-bottom: 20px; }
.d-card-head { padding: 16px 22px; border-bottom: 1px solid rgba(201,168,76,0.13); display: flex; align-items: center; justify-content: space-between; }
.d-card-title { font-size: 0.68rem; letter-spacing: 0.28em; text-transform: uppercase; color: #f0b429; display: flex; align-items: center; gap: 8px; }
.d-card-title-icon { font-size: 0.9rem; opacity: 0.8; }
.d-card-body { padding: 22px; }

.info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
.info-item { background: #181535; border: 1px solid rgba(201,168,76,0.13); border-radius: 8px; padding: 14px 16px; }
.info-label { font-size: 0.67rem; letter-spacing: 0.18em; text-transform: uppercase; color: rgba(240,234,222,0.3); margin-bottom: 7px; }
.info-value { font-size: 0.9rem; color: #f0eade; font-weight: 500; }
.info-value.gold { color: #f0b429; }

.patient-profile-brief { display: flex; align-items: center; gap: 14px; }
.p-avatar { width: 44px; height: 44px; border-radius: 50%; background: rgba(139,92,246,0.1); border: 1px solid rgba(139,92,246,0.3); display: flex; align-items: center; justify-content: center; font-family: 'Cinzel', serif; color: #a78bfa; }
.p-name { font-family: 'Cinzel', serif; font-size: 1rem; color: #f0eade; }
.p-contact { font-size: 0.75rem; color: rgba(240,234,222,0.3); margin-top: 2px; }

.patient-note-box { background: #181535; border-left: 3px solid #f0b429; padding: 12px 16px; border-radius: 0 8px 8px 0; }
.patient-note-text { font-family: 'Cormorant Garamond', serif; font-size: 0.95rem; font-style: italic; color: rgba(240,234,222,0.6); line-height: 1.6; }

.form-input { width: 100%; background: rgba(240,234,222,0.03); border: 1px solid rgba(201,168,76,0.13); border-radius: 8px; color: #f0eade; padding: 10px 12px; font-size: 0.85rem; }
.form-input:focus { outline: none; border-color: #f0b429; }
.field label { display: block; font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.1em; color: rgba(240,234,222,0.3); margin-bottom: 6px; }

.note-item { padding-bottom: 14px; border-bottom: 1px solid rgba(201,168,76,0.1); }
.note-item:last-child { border-bottom: none; }
.note-type-tag { font-size: 0.6rem; text-transform: uppercase; letter-spacing: 0.1em; padding: 2px 8px; border-radius: 4px; background: rgba(240,180,41,0.1); color: #f0b429; margin-bottom: 8px; display: inline-block; }
.note-text { font-size: 0.9rem; color: rgba(240,234,222,0.7); line-height: 1.6; }
.note-author { font-size: 0.65rem; color: rgba(240,234,222,0.2); margin-top: 4px; }

.action-list { display: flex; flex-direction: column; gap: 10px; }
.btn { width: 100%; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.1em; padding: 10px 16px; border-radius: 8px; border: 1px solid; cursor: pointer; transition: 0.2s; }
.btn-primary { background: #f0b429; color: #0a0814; border-color: #f0b429; font-weight: 600; }
.btn-success { background: rgba(26,171,122,0.1); color: #2ecc71; border-color: rgba(26,171,122,0.3); }
.btn-danger { background: rgba(192,57,43,0.1); color: #e74c3c; border-color: rgba(192,57,43,0.3); }
.btn-ghost { background: transparent; color: rgba(240,234,222,0.6); border-color: rgba(201,168,76,0.2); }
.btn:hover:not(:disabled) { transform: translateY(-1px); opacity: 0.9; }
.btn-sm { padding: 6px 12px; font-size: 0.65rem; width: auto; }

.participant-row { display: flex; align-items: center; gap: 10px; padding: 8px 0; border-bottom: 1px solid rgba(201,168,76,0.1); }
.participant-row:last-child { border-bottom: none; }
.p-av { width: 30px; height: 30px; border-radius: 50%; border: 1px solid; display: flex; align-items: center; justify-content: center; font-family: 'Cinzel', serif; font-size: 0.75rem; }
.p-av.lead { background: rgba(240,180,41,0.05); border-color: rgba(240,180,41,0.3); color: #f0b429; }
.p-av.co_raqi { background: rgba(139,92,246,0.05); border-color: rgba(139,92,246,0.3); color: #a78bfa; }
.p-badge { font-size: 0.55rem; text-transform: uppercase; letter-spacing: 0.1em; padding: 2px 6px; border-radius: 4px; margin-left: auto; }
.p-badge.lead { background: rgba(240,180,41,0.1); color: #f0b429; }
.p-badge.co_raqi { background: rgba(139,92,246,0.1); color: #a78bfa; }

.empty-notes { text-align: center; padding: 20px; color: rgba(240,234,222,0.2); font-style: italic; font-size: 0.8rem; }
.loading-state { padding: 40px 0; opacity: 0.5; }

@media (max-width: 850px) { .detail-grid { grid-template-columns: 1fr; } .detail-header { flex-direction: column; align-items: flex-start; } }
</style>
