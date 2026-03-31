<template>
  <div class="page-wrapper">
    <!-- PAGE -->
    <div class="page">
      <router-link to="/raqi/appointments" class="back-link">← My Sessions</router-link>

      <div v-if="appointment" class="content-fade">
        <!-- HEADER -->
        <div class="page-header">
          <div>
            <h1 class="page-title">Session Details</h1>
            <div v-if="appointment.id && typeof appointment.id === 'string'" class="page-meta">ID: #APPT-{{ appointment.id.split('-')[0].toUpperCase() }} &nbsp;·&nbsp; Booked {{ formatDate(appointment.created_at) }}</div>
            <div v-else-if="appointment.id" class="page-meta">ID: #APPT-{{ String(appointment.id).padStart(4, '0') }} &nbsp;·&nbsp; Booked {{ formatDate(appointment.created_at) }}</div>
          </div>
          <div class="header-actions">
            <span :class="['sbadge', appointment.status]">{{ appointment.status }}</span>
          </div>
        </div>

        <!-- BANNER -->
        <div :class="['status-banner', appointment.status]">
          <div class="banner-left">
            <div class="banner-icon">{{ getStatusIcon(appointment.status) }}</div>
            <div>
              <div class="banner-title">{{ getStatusTitle(appointment.status) }}</div>
              <div class="banner-sub">{{ formatDateTime(appointment.scheduled_at) }} · {{ formatSessionType(appointment.session_type) }}</div>
            </div>
          </div>
          <div v-if="appointment.status === 'confirmed' || appointment.status === 'pending'" class="banner-timer">
            <small>Scheduled for</small>
            <strong>{{ formatDateTime(appointment.scheduled_at) }}</strong>
          </div>
        </div>

        <!-- GRID -->
        <div class="grid">

          <!-- ── LEFT COLUMN ── -->
          <div class="left-col">

            <!-- SESSION INFO -->
            <div class="dcard">
              <div class="dcard-head">
                <div class="dcard-title">📋 Appointment Info</div>
              </div>
              <div class="dcard-body">
                <div class="info-grid">
                  <div class="iitem"><div class="ilabel">Session Type</div><div class="ivalue">{{ getSessionTypeIcon(appointment.session_type) }} {{ formatSessionType(appointment.session_type) }}</div></div>
                  <div class="iitem"><div class="ilabel">Date</div><div class="ivalue">{{ formatDate(appointment.scheduled_at) }}</div></div>
                  <div class="iitem"><div class="ilabel">Time</div><div class="ivalue">{{ formatTimeRange(appointment.scheduled_at, appointment.duration_minutes) }}</div></div>
                  <div class="iitem"><div class="ilabel">Duration</div><div class="ivalue gold">{{ appointment.duration_minutes }} min</div></div>
                  <div class="iitem"><div class="ilabel">Ailment</div><div class="ivalue">{{ appointment.ailment || 'General Healing' }}</div></div>
                  <div class="iitem">
                    <div class="ilabel">Your Role</div>
                    <div class="ivalue gold capitalize">{{ getMyRole() }}</div>
                  </div>
                </div>
              </div>
            </div>

            <!-- PATIENT INFO -->
            <div class="dcard">
              <div class="dcard-head">
                <div class="dcard-title">👤 Patient</div>
              </div>
              <div class="dcard-body">
                <div class="patient-card">
                  <div class="patient-av">{{ appointment.patient?.full_name?.charAt(0) }}</div>
                  <div>
                    <div class="patient-name">{{ appointment.patient?.full_name }}</div>
                    <div class="patient-phone">{{ appointment.patient?.phone || 'No phone' }} &nbsp;·&nbsp; {{ appointment.patient?.email }}</div>
                  </div>
                </div>
                <div class="pnotes-label">Patient's submitted notes</div>
                <div class="pnotes-box">
                  <div class="pnotes-text">
                    {{ appointment.patient_notes || 'No notes provided by patient.' }}
                  </div>
                </div>
              </div>
            </div>

            <!-- JOIN SESSION LINK -->
            <div v-if="appointment.status === 'confirmed'" class="dcard">
              <div class="dcard-head">
                <div class="dcard-title">🔗 Session Link</div>
                <span style="font-size:.7rem;color:var(--white-faint);">Paste your video or meeting link</span>
              </div>
              <div class="dcard-body">
                <div class="join-section">
                  <div class="join-label">🎥 Join Session URL</div>
                  <div class="join-input-row">
                    <input
                      class="join-input"
                      v-model="meetingLinkForm"
                      type="url"
                      placeholder="https://meet.jit.si/jetty-appt-..."
                    />
                    <button class="join-btn" @click="saveMeetingLink">Save Link</button>
                  </div>
                  <div v-if="meetingLinkForm" class="join-link-preview">🔗 {{ meetingLinkForm }}</div>
                </div>

                <hr class="divider" style="margin:18px 0;">

                <!-- INSTRUCTION TO PATIENT -->
                <div class="instruction-box">
                  <div class="instruction-head">
                    <div class="instruction-icon">📨</div>
                    <div>
                      <div class="instruction-title">Instructions to Patient</div>
                      <div class="instruction-subtitle">This message will be sent to {{ appointment.patient?.full_name }} before the session</div>
                    </div>
                  </div>
                  <textarea
                    class="instruction-textarea"
                    v-model="patientInstructionsForm"
                    rows="5"
                    placeholder="e.g. Please make sure you are in a clean, quiet space. Perform wudu before we begin..."
                  ></textarea>
                  <div style="display:flex;gap:10px;margin-top:10px;flex-wrap:wrap;">
                    <button class="send-btn primary" @click="sendInstructions">📤 Send to Patient</button>
                    <button class="send-btn" @click="patientInstructionsForm = ''">Clear</button>
                  </div>
                </div>

                <!-- RAQI PRIVATE NOTES -->
                <div class="instruction-box" style="margin-top:14px;">
                  <div class="instruction-head">
                    <div class="instruction-icon">🔒</div>
                    <div>
                      <div class="instruction-title">Private Raqi Notes</div>
                      <div class="instruction-subtitle">Only visible to you and co-Raqis — not shown to patient</div>
                    </div>
                  </div>
                  <textarea
                    class="instruction-textarea"
                    v-model="privateNotesForm"
                    rows="4"
                    placeholder="Your private preparation notes, observations, recitation plan..."
                  ></textarea>
                  <button class="send-btn" style="margin-top:10px;" @click="savePrivateNotes">💾 Save Notes</button>
                </div>
              </div>
            </div>

            <!-- SESSION LOG -->
            <div class="dcard">
              <div class="dcard-head">
                <div class="dcard-title">🌿 Session Log</div>
                <span style="font-size:.7rem;color:var(--white-faint);">Ruqyah notes & observations</span>
              </div>
              <div class="dcard-body">
                <div class="note-log">
                  <div v-if="!sessionNotes.length" style="text-align:center;padding:20px 0 8px;color:var(--white-faint);font-size:.82rem;font-style:italic;">No notes yet. Add your first note below.</div>
                  <div v-for="note in sessionNotes" :key="note.id" class="note-entry">
                    <span :class="['note-tag', note.note_type]">{{ note.note_type.replace('_', ' ') }}</span>
                    <div class="note-body">{{ note.content }}</div>
                    <div class="note-meta">{{ note.raqi?.user?.full_name }} &nbsp;·&nbsp; {{ formatDateTime(note.created_at) }}</div>
                  </div>
                </div>

                <!-- Add note -->
                <div v-if="appointment.status === 'confirmed'" style="margin-top:18px;padding-top:18px;border-top:1px solid var(--border);">
                  <div style="display:grid;grid-template-columns:1fr auto;gap:10px;align-items:center;margin-bottom:10px;">
                    <div style="font-size:.65rem;letter-spacing:.2em;text-transform:uppercase;color:var(--gold);">Add Note</div>
                    <select class="note-select" v-model="newNote.note_type">
                      <option value="ruqyah_log">Ruqyah Log</option>
                      <option value="observation">Observation</option>
                      <option value="recommendation">Recommendation</option>
                    </select>
                  </div>
                  <textarea class="note-ta" v-model="newNote.content" rows="3" placeholder="Write your session note here..."></textarea>
                  <div style="margin-top:10px;display:flex;justify-content:flex-end;">
                    <button class="send-btn primary" @click="addNote" :disabled="!newNote.content">➕ Add Note</button>
                  </div>
                </div>
              </div>
            </div>

          </div>

          <!-- ── RIGHT SIDEBAR ── -->
          <div class="right-col">

            <!-- ACTIONS -->
            <div class="dcard">
              <div class="dcard-head"><div class="dcard-title">⚡ Actions</div></div>
              <div class="dcard-body">
                <div class="action-list">
                  <template v-if="appointment.status === 'pending'">
                    <button class="abtn abtn-primary" @click="acceptAppointment">✓ Accept Session</button>
                    <button class="abtn abtn-danger" @click="declineAppointment">✕ Decline Session</button>
                  </template>
                  <template v-if="appointment.status === 'confirmed'">
                    <button class="abtn abtn-primary" @click="joinSession">🎥 Join Session</button>
                    <button class="abtn abtn-teal" @click="completeAppointment">✦ Complete Session</button>
                    <button class="abtn abtn-ghost" @click="remindPatient">🔔 Remind Patient</button>
                    <button class="abtn abtn-ghost" @click="showFollowUpForm = !showFollowUpForm">📅 Schedule Follow-up</button>
                  </template>
                </div>

                <!-- Follow-up Form -->
                <div v-if="showFollowUpForm" class="mt-4 p-4 rounded-lg bg-white/[0.03] border border-white/5 content-fade">
                  <div style="font-size:.65rem;letter-spacing:.12em;text-transform:uppercase;color:var(--gold);margin-bottom:10px;">New Follow-up</div>
                  <div class="field mb-3">
                    <label style="font-size:.6rem;color:var(--white-faint);display:block;margin-bottom:4px;">DATE</label>
                    <input type="date" v-model="followUpForm.date" class="join-input w-full" style="padding:8px 12px;font-size:.75rem;" />
                  </div>
                  <div class="field mb-3">
                    <label style="font-size:.6rem;color:var(--white-faint);display:block;margin-bottom:4px;">RECOMMENDATION</label>
                    <textarea v-model="followUpForm.recommendation" rows="2" class="join-input w-full" style="padding:8px 12px;font-size:.75rem;" placeholder="Instructions…"></textarea>
                  </div>
                  <button @click="scheduleFollowUp" class="abtn abtn-primary" style="padding:8px;font-size:.7rem;">Schedule</button>
                </div>
              </div>
            </div>

            <!-- CO-RAQI ASSIGNMENT -->
            <div class="dcard">
              <div class="dcard-head">
                <div class="dcard-title">👥 Co-Raqis</div>
                <span style="font-size:.7rem;color:var(--white-faint);">Lead: {{ appointment.lead_raqi?.user?.full_name }}</span>
              </div>
              <div class="dcard-body">

                <!-- Assigned list -->
                <div class="coraqi-assigned">
                  <div v-for="p in participants" :key="p.id" class="coraqi-row">
                    <div :class="['cr-av', p.role]">{{ p.raqi?.user?.full_name?.charAt(0) }}</div>
                    <div style="flex:1;min-width:0;">
                      <div class="cr-name">{{ p.raqi?.user?.full_name }} <span v-if="p.raqi_id === appointment.lead_raqi_id" style="font-size:.65rem;color:var(--white-faint);">(Lead)</span></div>
                      <div class="cr-spec">{{ p.raqi?.specialization }}</div>
                    </div>
                    <span :class="['cr-badge', p.role, p.invite_status]">{{ p.invite_status }}</span>
                    <button v-if="appointment.status === 'confirmed' && appointment.lead_raqi_id === currentUser?.raqi_profile?.id && p.raqi_id !== appointment.lead_raqi_id" class="cr-remove" style="color:var(--white-faint);border:none;background:none;cursor:pointer;padding:4px 8px;" @click="removeCoRaqi(p.id)">✕</button>
                  </div>
                </div>

                <hr v-if="appointment.status === 'confirmed'" class="divider" style="margin:14px 0;">

                <!-- Search -->
                <template v-if="appointment.status === 'confirmed'">
                  <div style="font-size:.65rem;letter-spacing:.22em;text-transform:uppercase;color:var(--gold);margin-bottom:10px;">Invite a Co-Raqi</div>
                  <div class="raqi-search-wrap">
                    <span class="search-icon">🔍</span>
                    <input class="raqi-search" type="text" v-model="raqiSearchQuery" placeholder="Search Raqis...">
                  </div>

                  <!-- Raqi list -->
                  <div class="raqi-list">
                    <div v-for="r in filteredRaqis" :key="r.id" class="raqi-option">
                      <div class="ro-av" style="background:rgba(240,180,41,0.05);border-color:rgba(240,180,41,0.3);color:var(--gold);">{{ r.user?.full_name?.charAt(0) }}</div>
                      <div class="ro-info">
                        <div class="ro-name">{{ r.user?.full_name }}</div>
                        <div class="ro-spec">{{ r.specialization }}</div>
                        <div class="ro-rating">★ {{ r.rating }}</div>
                      </div>
                      <button class="ro-action invite" @click="inviteCoRaqi(r.id)">+ Invite</button>
                    </div>
                  </div>
                </template>
              </div>
            </div>

            <!-- QUICK INFO -->
            <div class="dcard">
              <div class="dcard-head"><div class="dcard-title">ℹ️ Quick Info</div></div>
              <div class="dcard-body">
                <div class="qi-row"><span class="qi-label">Patient</span><span class="qi-value">{{ appointment.patient?.full_name }}</span></div>
                <div class="qi-row"><span class="qi-label">Ailment</span><span class="qi-value">{{ appointment.ailment || 'General' }}</span></div>
                <div class="qi-row"><span class="qi-label">Co-Raqis</span><span class="qi-value gold">{{ participants.length - 1 }} assigned</span></div>
                <div v-if="appointment.id && typeof appointment.id === 'string'" class="qi-row"><span class="qi-label">Session ID</span><span class="qi-value" style="font-size:.72rem;">APPT-{{ appointment.id.split('-')[0].toUpperCase() }}</span></div>
                <div v-else-if="appointment.id" class="qi-row"><span class="qi-label">Session ID</span><span class="qi-value" style="font-size:.72rem;">APPT-{{ String(appointment.id).padStart(4, '0') }}</span></div>
                <div class="qi-row"><span class="qi-label">Fee</span><span class="qi-value gold">৳ 1,500</span></div>
              </div>
            </div>

          </div>
        </div>
      </div>

      <!-- SKELETON -->
      <div v-else-if="loading" class="loading-state">
        <div class="skeleton-header" style="height:60px;background:rgba(240,234,222,0.05);border-radius:8px;margin-bottom:32px;"></div>
        <div class="skeleton-banner" style="height:80px;background:rgba(240,234,222,0.05);border-radius:12px;margin-bottom:28px;"></div>
        <div class="skeleton-grid" style="display:grid;grid-template-columns:1fr 360px;gap:22px;">
          <div class="skeleton-main" style="height:600px;background:rgba(240,234,222,0.05);border-radius:12px;"></div>
          <div class="skeleton-side" style="height:400px;background:rgba(240,234,222,0.05);border-radius:12px;"></div>
        </div>
      </div>
    </div>

    <!-- TOAST -->
    <div :class="['toast', { show: toastMsg }]" id="toast">
      <span class="toast-icon">{{ toastIcon }}</span>
      <span>{{ toastMsg }}</span>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted, computed } from 'vue';
import { useRoute } from 'vue-router';
import { raqiAPI, authAPI } from '../../api';
import { formatDateTime, formatDate, unwrap } from '../../utils';

const route = useRoute();
const appointment = ref(null);
const loading = ref(true);
const sessionNotes = ref([]);
const participants = ref([]);
const availableRaqis = ref([]);
const raqiSearchQuery = ref('');
const currentUser = ref(null);

const toastMsg = ref('');
const toastIcon = ref('✓');
const showFollowUpForm = ref(false);

const newNote = reactive({ content: '', note_type: 'ruqyah_log' });
const followUpForm = reactive({ date: '', recommendation: '' });

// Form fields for new features (placeholders for now if not in DB)
const meetingLinkForm = ref('');
const patientInstructionsForm = ref('');
const privateNotesForm = ref('');

const toast = (msg, icon = '✓') => {
  toastMsg.value = msg;
  toastIcon.value = icon;
  setTimeout(() => { toastMsg.value = ''; }, 3000);
};

onMounted(async () => {
  try {
    const [aptRes, notesRes, partsRes, raqisRes, meRes] = await Promise.all([
      raqiAPI.appointments.get(route.params.id),
      raqiAPI.notes.list(route.params.id),
      raqiAPI.participants.list(route.params.id),
      raqiAPI.raqis.list(),
      authAPI.me()
    ]);
    appointment.value = unwrap(aptRes);
    sessionNotes.value = unwrap(notesRes);
    participants.value = unwrap(partsRes);
    availableRaqis.value = unwrap(raqisRes);
    currentUser.value = unwrap(meRes);

    // Initial form values with safety checks
    if (appointment.value) {
      meetingLinkForm.value = appointment.value.meeting_link || '';
      patientInstructionsForm.value = appointment.value.patient_instructions || '';
      privateNotesForm.value = appointment.value.raqi_notes || '';
    }
  } catch (e) {
    console.error(e);
    alert('Error Loading Page: ' + (e.response ? JSON.stringify(e.response.data) : e.message));
  } finally {
    loading.value = false;
  }
});

const filteredRaqis = computed(() => {
  if (!raqiSearchQuery.value) return availableRaqis.value.slice(0, 5);
  const q = raqiSearchQuery.value.toLowerCase();
  return availableRaqis.value.filter(r => 
    r.user?.full_name?.toLowerCase().includes(q) || 
    r.specialization?.toLowerCase().includes(q)
  ).slice(0, 5);
});

const getMyRole = () => {
  if (!appointment.value || !currentUser.value) return '';
  return appointment.value.lead_raqi?.user_id === currentUser.value.id ? 'lead' : 'co-raqi';
};

const acceptAppointment = async () => {
  try { 
    await raqiAPI.appointments.accept(route.params.id); 
    appointment.value.status = 'confirmed';
    toast('Session accepted!');
  } catch (e) { toast('Failed to accept', '✕'); }
};

const declineAppointment = async () => {
  try { 
    await raqiAPI.appointments.decline(route.params.id); 
    appointment.value.status = 'cancelled';
    toast('Session declined', '✕');
  } catch (e) { toast('Failed to decline', '✕'); }
};

const addNote = async () => {
  try {
    const r = await raqiAPI.notes.create(route.params.id, newNote);
    sessionNotes.value.unshift(r.data.data);
    newNote.content = '';
    toast('Note added', '🌿');
  } catch (e) { toast('Failed to add note', '✕'); }
};

const inviteCoRaqi = async (raqiId) => {
  try {
    await raqiAPI.participants.invite(route.params.id, { raqi_id: raqiId });
    const res = await raqiAPI.participants.list(route.params.id);
    participants.value = unwrap(res);
    toast('Invite sent', '👥');
  } catch (e) { toast('Failed to invite', '✕'); }
};

const removeCoRaqi = async (participantId) => {
  if (!confirm('Remove this co-Raqi from the session?')) return;
  try {
    await raqiAPI.participants.remove(route.params.id, participantId);
    participants.value = participants.value.filter(p => p.id !== participantId);
    toast('Co-Raqi removed', '✕');
  } catch (e) { toast('Failed to remove co-Raqi', '✕'); }
};

const scheduleFollowUp = async () => {
  try {
    await raqiAPI.followup.create(route.params.id, {
      follow_up_date: followUpForm.date,
      recommendation: followUpForm.recommendation
    });
    toast('Follow-up scheduled', '📅');
    showFollowUpForm.value = false;
  } catch (e) { toast('Failed to schedule', '✕'); }
};

const completeAppointment = async () => {
  if (!confirm('Mark session as completed?')) return;
  try { 
    await raqiAPI.appointments.complete(route.params.id); 
    appointment.value.status = 'completed';
    toast('Session completed!', '✦');
  } catch (e) { toast('Failed to complete', '✕'); }
};

const saveMeetingLink = async () => {
  try {
    await raqiAPI.appointments.update(route.params.id, { meeting_link: meetingLinkForm.value });
    toast('Meeting link saved', '🔗');
  } catch (e) { toast('Failed to save link', '✕'); }
};

const sendInstructions = async () => {
  try {
    await raqiAPI.appointments.update(route.params.id, { patient_instructions: patientInstructionsForm.value });
    toast('Instructions sent to patient', '📨');
  } catch (e) { toast('Failed to send', '✕'); }
};

const savePrivateNotes = async () => {
  try {
    await raqiAPI.appointments.update(route.params.id, { raqi_notes: privateNotesForm.value });
    toast('Private notes saved', '🔒');
  } catch (e) { toast('Failed to save', '✕'); }
};

const remindPatient = () => toast('Reminder sent to patient', '🔔');
const joinSession = () => {
  if (meetingLinkForm.value) window.open(meetingLinkForm.value, '_blank');
  else alert('No meeting link set yet.');
};

const getStatusIcon = (s) => ({ confirmed: '✅', pending: '⏳', completed: '✦', cancelled: '✕' }[s] || '📅');
const getStatusTitle = (s) => ({ confirmed: 'Session Confirmed', pending: 'Pending Request', completed: 'Session Completed', cancelled: 'Cancelled' }[s] || s);

const formatSessionType = (type) => ({ video: 'Online Video', chat: 'Live Chat', phone: 'Phone Session', in_person: 'In-Person' }[type] || type);
const getSessionTypeIcon = (type) => ({ video: '🎥', chat: '💬', phone: '📞', in_person: '🕌' }[type] || '📅');

const formatTimeRange = (start, duration) => {
  if (!start) return '';
  const startTime = new Date(start);
  const endTime = new Date(startTime.getTime() + duration * 60000);
  const opt = { hour: 'numeric', minute: '2-digit', hour12: true };
  return `${startTime.toLocaleTimeString([], opt)} — ${endTime.toLocaleTimeString([], opt)}`;
};
</script>

<script>
// Non-setup script for static data or extra styles if needed
</script>

<style scoped>
*, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

.page-wrapper {
  --void:        #08060f;
  --deep:        #0d0b1c;
  --card:        #121024;
  --card2:       #16132e;
  --card3:       #1a1638;
  --border:      rgba(201,168,76,0.13);
  --border-mid:  rgba(201,168,76,0.28);
  --border-hi:   rgba(201,168,76,0.5);
  --gold:        #f0b429;
  --gold-light:  #ffd166;
  --gold-dim:    rgba(240,180,41,0.10);
  --white:       #f0eade;
  --white-dim:   rgba(240,234,222,0.62);
  --white-faint: rgba(240,234,222,0.30);
  --teal:        #1aab7a;
  --teal-dim:    rgba(26,171,122,0.13);
  --amber-dim:   rgba(240,180,41,0.13);
  --red:         #c0392b;
  --red-dim:     rgba(192,57,43,0.13);
  --purple:      #8b5cf6;
  --purple-dim:  rgba(139,92,246,0.13);
  --blue:        #3b82f6;
  --blue-dim:    rgba(59,130,246,0.13);
  --font-d:  'Cinzel', serif;
  --font-b:  'Cormorant Garamond', serif;
  --font-ui: 'DM Sans', sans-serif;

  background: var(--void);
  color: var(--white);
  font-family: var(--font-ui);
  min-height: 100vh;
}

.page { max-width: 1060px; margin: 0 auto; padding: 32px 40px 80px; }

.back-link { display: inline-flex; align-items: center; gap: 7px; font-size: 0.8rem; color: var(--white-faint); text-decoration: none; letter-spacing: 0.06em; margin-bottom: 26px; transition: color .2s; border: none; background: none; cursor: pointer; padding: 0; }
.back-link:hover { color: var(--gold); }

.page-header { display: flex; align-items: flex-start; justify-content: space-between; gap: 20px; margin-bottom: 28px; }
.page-title { font-family: var(--font-d); font-size: 1.55rem; font-weight: 400; letter-spacing: 0.07em; color: var(--white); margin-bottom: 5px; }
.page-meta { font-size: 0.72rem; letter-spacing: 0.15em; color: var(--white-faint); text-transform: uppercase; }
.header-actions { display: flex; align-items: center; gap: 10px; flex-shrink: 0; }

.sbadge { font-size: 0.7rem; letter-spacing: 0.14em; text-transform: uppercase; padding: 6px 16px; border-radius: 30px; font-weight: 500; white-space: nowrap; }
.sbadge.confirmed { background: var(--teal-dim); color: #2ecc71; border: 1px solid rgba(26,171,122,.25); }
.sbadge.pending   { background: var(--amber-dim); color: var(--gold); border: 1px solid rgba(240,180,41,.25); }
.sbadge.completed { background: var(--purple-dim); color: #a78bfa; border: 1px solid rgba(139,92,246,.25); }
.sbadge.cancelled { background: var(--red-dim); color: #e74c3c; border: 1px solid rgba(192,57,43,.25); }

.status-banner { background: var(--teal-dim); border: 1px solid rgba(26,171,122,.22); border-radius: 12px; padding: 20px 26px; display: flex; align-items: center; justify-content: space-between; gap: 20px; margin-bottom: 28px; }
.status-banner.pending { background: var(--amber-dim); border-color: rgba(240,180,41,.22); }
.status-banner.completed { background: var(--purple-dim); border-color: rgba(139,92,246,.22); }
.status-banner.cancelled { background: var(--red-dim); border-color: rgba(192,57,43,.22); }

.banner-left { display: flex; align-items: center; gap: 14px; }
.banner-icon { font-size: 1.7rem; }
.banner-title { font-family: var(--font-d); font-size: 0.95rem; letter-spacing: .06em; color: var(--white); margin-bottom: 3px; }
.banner-sub { font-size: 0.76rem; color: var(--white-faint); }
.banner-timer { text-align: right; }
.banner-timer small { font-size: 0.65rem; letter-spacing: .18em; text-transform: uppercase; color: var(--white-faint); display: block; margin-bottom: 2px; }
.banner-timer strong { font-family: var(--font-d); font-size: 1.05rem; color: var(--gold); }

.grid { display: grid; grid-template-columns: 1fr 360px; gap: 22px; align-items: start; }

.dcard { background: var(--card); border: 1px solid var(--border); border-radius: 12px; overflow: hidden; margin-bottom: 20px; }
.dcard-head { padding: 15px 22px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; }
.dcard-title { font-size: 0.67rem; letter-spacing: .28em; text-transform: uppercase; color: var(--gold); display: flex; align-items: center; gap: 8px; }
.dcard-body { padding: 22px; }

.info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
.iitem { background: var(--card2); border: 1px solid var(--border); border-radius: 8px; padding: 13px 15px; transition: border-color .2s; }
.iitem:hover { border-color: var(--border-mid); }
.ilabel { font-size: 0.65rem; letter-spacing: .18em; text-transform: uppercase; color: var(--white-faint); margin-bottom: 6px; }
.ivalue { font-size: 0.88rem; color: var(--white); font-weight: 500; }
.ivalue.gold { color: var(--gold); }

.patient-card { display: flex; align-items: center; gap: 14px; padding-bottom: 18px; border-bottom: 1px solid var(--border); margin-bottom: 18px; }
.patient-av { width: 50px; height: 50px; border-radius: 50%; background: var(--teal-dim); border: 2px solid rgba(26,171,122,.3); display: flex; align-items: center; justify-content: center; font-family: var(--font-d); font-size: 1.2rem; color: #2ecc71; flex-shrink: 0; }
.patient-name { font-family: var(--font-d); font-size: 1rem; letter-spacing: .06em; color: var(--white); margin-bottom: 3px; }
.patient-phone { font-size: 0.75rem; color: var(--white-faint); }

.pnotes-box { background: var(--card2); border: 1px solid var(--border); border-left: 3px solid var(--gold); border-radius: 0 8px 8px 0; padding: 16px 18px; }
.pnotes-label { font-size: 0.65rem; letter-spacing: .22em; text-transform: uppercase; color: var(--gold); margin-bottom: 10px; }
.pnotes-text { font-family: var(--font-b); font-size: 1.05rem; font-style: italic; color: var(--white-dim); line-height: 1.8; }

.join-section { margin-bottom: 20px; }
.join-label { font-size: 0.65rem; letter-spacing: .22em; text-transform: uppercase; color: var(--gold); margin-bottom: 10px; display: flex; align-items: center; gap: 8px; }
.join-input-row { display: flex; gap: 10px; align-items: stretch; }
.join-input {
  flex: 1;
  background: var(--card2);
  border: 1px solid var(--border-mid);
  border-radius: 8px;
  padding: 12px 16px;
  font-family: var(--font-ui);
  font-size: 0.82rem;
  color: var(--white);
  outline: none;
  transition: border-color .2s, box-shadow .2s;
  letter-spacing: .02em;
}
.join-input::placeholder { color: var(--white-faint); }
.join-input:focus { border-color: var(--gold); box-shadow: 0 0 0 3px rgba(240,180,41,.1); }
.join-btn {
  background: var(--gold); color: #1a1200; border: none; border-radius: 8px;
  padding: 0 20px; font-size: 0.78rem; letter-spacing: .12em; text-transform: uppercase;
  font-weight: 500; cursor: pointer; white-space: nowrap; transition: background .2s, transform .2s;
  font-family: var(--font-ui);
}
.join-btn:hover { background: var(--gold-light); transform: translateY(-1px); }
.join-link-preview { margin-top: 8px; font-size: 0.72rem; color: var(--teal); letter-spacing: .03em; display: flex; align-items: center; gap: 6px; min-height: 20px; }

.instruction-box { background: var(--card2); border: 1px solid var(--border); border-radius: 10px; padding: 18px 20px; margin-bottom: 14px; }
.instruction-head { display: flex; align-items: center; gap: 10px; margin-bottom: 12px; }
.instruction-icon { font-size: 1.1rem; }
.instruction-title { font-family: var(--font-d); font-size: 0.88rem; letter-spacing: .07em; color: var(--white); }
.instruction-subtitle { font-size: 0.7rem; color: var(--white-faint); margin-top: 1px; }
.instruction-textarea {
  width: 100%; background: var(--card3); border: 1px solid var(--border);
  border-radius: 8px; padding: 14px 16px; font-family: var(--font-b);
  font-size: 1rem; font-style: italic; color: var(--white-dim); line-height: 1.8;
  outline: none; resize: vertical; min-height: 110px;
  transition: border-color .2s; letter-spacing: .02em;
}
.instruction-textarea::placeholder { color: var(--white-faint); font-style: italic; }
.instruction-textarea:focus { border-color: var(--border-mid); }
.send-btn { display: flex; align-items: center; gap: 7px; margin-top: 10px; font-family: var(--font-ui); font-size: 0.75rem; letter-spacing: .14em; text-transform: uppercase; padding: 9px 18px; border-radius: 7px; border: 1px solid var(--border-mid); background: none; color: var(--white-dim); cursor: pointer; transition: all .2s; }
.send-btn:hover { color: var(--white); border-color: var(--gold); background: var(--gold-dim); }
.send-btn.primary { background: var(--gold); color: #1a1200; border-color: var(--gold); font-weight: 500; }
.send-btn.primary:hover { background: var(--gold-light); }

.coraqi-assigned { display: flex; flex-direction: column; gap: 10px; margin-bottom: 14px; }
.coraqi-row { background: var(--card2); border: 1px solid var(--border); border-radius: 8px; padding: 12px 14px; display: flex; align-items: center; gap: 12px; }
.cr-av { width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-family: var(--font-d); font-size: 0.85rem; flex-shrink: 0; }
.cr-av.lead    { background: var(--gold-dim); border: 1px solid rgba(240,180,41,.3); color: var(--gold); }
.cr-av.co_raqi { background: var(--purple-dim); border: 1px solid rgba(139,92,246,.3); color: #a78bfa; }
.cr-name { font-size: 0.83rem; color: var(--white-dim); flex: 1; }
.cr-spec { font-size: 0.68rem; color: var(--white-faint); }
.cr-badge { font-size: 0.62rem; letter-spacing: .14em; text-transform: uppercase; padding: 3px 10px; border-radius: 20px; white-space: nowrap; }
.cr-badge.lead    { background: var(--gold-dim); color: var(--gold); }
.cr-badge.co_raqi { background: var(--purple-dim); color: #a78bfa; }
.cr-badge.pending { background: var(--amber-dim); color: var(--gold); }

.raqi-search-wrap { position: relative; margin-bottom: 14px; }
.raqi-search {
  width: 100%; background: var(--card2); border: 1px solid var(--border-mid);
  border-radius: 8px; padding: 10px 14px 10px 38px;
  font-family: var(--font-ui); font-size: 0.82rem; color: var(--white);
  outline: none; transition: border-color .2s, box-shadow .2s;
}
.raqi-search::placeholder { color: var(--white-faint); }
.raqi-search:focus { border-color: var(--gold); box-shadow: 0 0 0 3px rgba(240,180,41,.1); }
.search-icon { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); font-size: 0.9rem; opacity: .4; pointer-events: none; }

.raqi-list { display: flex; flex-direction: column; gap: 8px; max-height: 280px; overflow-y: auto; }
.raqi-list::-webkit-scrollbar { width: 3px; }
.raqi-list::-webkit-scrollbar-thumb { background: rgba(240,180,41,.2); border-radius: 2px; }

.raqi-option { background: var(--card2); border: 1px solid var(--border); border-radius: 8px; padding: 12px 14px; display: flex; align-items: center; gap: 12px; transition: border-color .2s, background .2s; }
.raqi-option:hover { border-color: var(--border-mid); background: var(--card3); }
.ro-av { width: 38px; height: 38px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-family: var(--font-d); font-size: 0.9rem; flex-shrink: 0; border: 1px solid; }
.ro-info { flex: 1; min-width: 0; }
.ro-name { font-size: 0.83rem; color: var(--white); margin-bottom: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.ro-spec { font-size: 0.68rem; color: var(--white-faint); }
.ro-rating { font-size: 0.72rem; color: var(--gold); }
.ro-action { font-size: 0.7rem; letter-spacing: .1em; text-transform: uppercase; padding: 5px 12px; border-radius: 6px; border: 1px solid; background: none; cursor: pointer; transition: all .2s; white-space: nowrap; font-family: var(--font-ui); }
.ro-action.invite { color: var(--gold); border-color: rgba(240,180,41,.3); }
.ro-action.invite:hover { background: var(--gold-dim); border-color: var(--gold); }

.action-list { display: flex; flex-direction: column; gap: 10px; }
.abtn { width: 100%; font-family: var(--font-ui); font-size: 0.78rem; letter-spacing: .14em; text-transform: uppercase; padding: 12px 18px; border-radius: 8px; border: 1px solid; cursor: pointer; transition: all .2s; display: flex; align-items: center; justify-content: center; gap: 8px; }
.abtn-primary { background: var(--gold); color: #1a1200; border-color: var(--gold); font-weight: 500; }
.abtn-primary:hover { background: var(--gold-light); transform: translateY(-1px); }
.abtn-ghost { background: none; color: var(--white-dim); border-color: var(--border-mid); }
.abtn-ghost:hover { color: var(--white); border-color: var(--gold); background: var(--gold-dim); }
.abtn-danger { background: none; color: #e74c3c; border-color: rgba(192,57,43,.3); }
.abtn-danger:hover { background: var(--red-dim); border-color: var(--red); }
.abtn-teal { background: none; color: #2ecc71; border-color: rgba(26,171,122,.3); }
.abtn-teal:hover { background: var(--teal-dim); border-color: var(--teal); }

.note-log { display: flex; flex-direction: column; gap: 0; }
.note-entry { padding: 14px 0; border-bottom: 1px solid var(--border); }
.note-entry:last-child { border-bottom: none; }
.note-tag { display: inline-block; font-size: 0.62rem; letter-spacing: .16em; text-transform: uppercase; padding: 3px 10px; border-radius: 20px; margin-bottom: 7px; }
.note-tag.ruqyah_log    { background: var(--gold-dim); color: var(--gold); }
.note-tag.observation   { background: var(--teal-dim); color: #2ecc71; }
.note-tag.recommendation{ background: var(--purple-dim); color: #a78bfa; }
.note-body { font-family: var(--font-b); font-size: 1rem; font-style: italic; color: var(--white-dim); line-height: 1.75; }
.note-meta { font-size: 0.68rem; color: var(--white-faint); margin-top: 5px; }

.note-select { background: var(--card2); border: 1px solid var(--border-mid); border-radius: 7px; padding: 9px 12px; font-family: var(--font-ui); font-size: 0.78rem; color: var(--white-dim); outline: none; cursor: pointer; }
.note-select:focus { border-color: var(--gold); }
.note-ta { width: 100%; background: var(--card2); border: 1px solid var(--border); border-radius: 8px; padding: 12px 14px; font-family: var(--font-b); font-size: 0.95rem; font-style: italic; color: var(--white-dim); outline: none; resize: none; min-height: 80px; transition: border-color .2s; margin-top: 10px; letter-spacing: .02em; }
.note-ta::placeholder { color: var(--white-faint); }
.note-ta:focus { border-color: var(--border-mid); }

.divider { border: none; border-top: 1px solid var(--border); margin: 0; }

.qi-row { padding: 12px 0; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; }
.qi-row:last-child { border-bottom: none; padding-bottom: 0; }
.qi-label { font-size: 0.68rem; letter-spacing: .12em; text-transform: uppercase; color: var(--white-faint); }
.qi-value { font-size: 0.82rem; color: var(--white); text-align: right; }
.qi-value.gold { color: var(--gold); }

.toast { position: fixed; bottom: 28px; right: 28px; background: var(--card2); border: 1px solid var(--border-mid); border-left: 3px solid var(--gold); border-radius: 10px; padding: 14px 20px; font-size: 0.82rem; color: var(--white-dim); z-index: 999; transform: translateY(20px); opacity: 0; transition: all .3s ease; pointer-events: none; display: flex; align-items: center; gap: 10px; }
.toast.show { transform: translateY(0); opacity: 1; }
.toast-icon { font-size: 1rem; }

.content-fade { animation: fadeUp .5s ease both; }
@keyframes fadeUp { from{opacity:0;transform:translateY(14px)} to{opacity:1;transform:translateY(0)} }

@media(max-width:860px){
  .grid{grid-template-columns:1fr;}
  .page{padding:20px 16px 60px;}
}
</style>
