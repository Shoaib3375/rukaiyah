<template>
  <div class="page-wrapper">
    <!-- PAGE -->
    <div class="page">

      <!-- BACK -->
      <router-link to="/patient/appointments" class="back-link">
        ← My Appointments
      </router-link>

      <div v-if="appointment" class="content-fade">
        <!-- HEADER -->
        <div class="detail-header">
          <div>
            <h1 class="detail-heading">Appointment Details</h1>
            <div v-if="appointment.id" class="detail-id">ID: #APPT-{{ appointment.id.split('-')[0].toUpperCase() }} &nbsp;·&nbsp; Booked on {{ formatDate(appointment.created_at) }}</div>
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
          <div v-if="appointment.status === 'confirmed' || appointment.status === 'pending'" class="banner-countdown">
            <small>Scheduled for</small>
            {{ formatDateTime(appointment.scheduled_at) }}
          </div>
        </div>

        <!-- TWO-COLUMN GRID -->
        <div class="detail-grid">

          <!-- LEFT COLUMN -->
          <div class="main-col">

            <!-- SESSION INFO -->
            <div class="d-card">
              <div class="d-card-head">
                <div class="d-card-title"><span class="d-card-title-icon">📋</span> Appointment</div>
              </div>
              <div class="d-card-body">
                <div class="info-grid">
                  <div class="info-item">
                    <div class="info-label">Type</div>
                    <div class="info-value type-pill">
                      <span class="type-icon">{{ getSessionTypeIcon(appointment.session_type) }}</span> {{ formatSessionType(appointment.session_type) }}
                    </div>
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
                    <div class="info-label">Status</div>
                    <div class="info-value"><span :class="['status-badge', appointment.status]" style="font-size:0.65rem;padding:4px 12px;">{{ appointment.status }}</span></div>
                  </div>
                  <div class="info-item">
                    <div class="info-label">Time</div>
                    <div class="info-value">{{ formatTimeRange(appointment.scheduled_at, appointment.duration_minutes) }}</div>
                  </div>
                  <div v-if="appointment.status === 'confirmed'" class="info-item">
                    <div class="info-label">Session Room</div>
                    <div class="info-value gold" style="font-size:0.8rem;">jetty-appt-{{ appointment.id.split('-')[0] }}</div>
                  </div>
                </div>
              </div>
            </div>

            <!-- RAQI -->
            <div class="d-card">
              <div class="d-card-head">
                <div class="d-card-title"><span class="d-card-title-icon">🕌</span> Your Raqi</div>
              </div>
              <div class="d-card-body">
                <div class="raqi-card">
                  <div class="raqi-avatar">{{ appointment.lead_raqi?.user?.full_name?.charAt(0) }}</div>
                  <div>
                    <div class="raqi-name">{{ appointment.lead_raqi?.user?.full_name }}</div>
                    <div class="raqi-spec">{{ appointment.lead_raqi?.specialization || 'General Healing' }}</div>
                    <div class="raqi-stars">
                      <template v-for="i in 5" :key="i">
                        {{ i <= Math.round(appointment.lead_raqi?.rating || 0) ? '★' : '☆' }}
                      </template>
                    </div>
                  </div>
                  <div style="margin-left:auto;text-align:right;">
                    <div class="raqi-sessions">{{ appointment.lead_raqi?.total_reviews || 0 }} reviews</div>
                    <div style="font-size:0.7rem;color:#1aab7a;margin-top:4px;">● {{ appointment.lead_raqi?.status || 'Active' }}</div>
                  </div>
                </div>
                <!-- Participants -->
                <div v-if="appointment.participants?.length" style="font-size:0.68rem;letter-spacing:0.2em;text-transform:uppercase;color:#f0b429;margin-bottom:12px;">Session Participants</div>
                <div v-for="p in appointment.participants" :key="p.id" class="participant-row">
                  <div :class="['p-av', p.role]">{{ p.raqi?.user?.full_name?.charAt(0) }}</div>
                  <div class="p-name">{{ p.raqi?.user?.full_name }}</div>
                  <span :class="['p-badge', p.role]">{{ p.role === 'lead' ? 'Lead Raqi' : 'Co-Raqi' }}</span>
                </div>
              </div>
            </div>

            <!-- SESSION ROOM / LINK -->
            <div v-if="appointment.status === 'confirmed'" class="d-card">
              <div class="d-card-head">
                <div class="d-card-title"><span class="d-card-title-icon">🔗</span> Session Access</div>
              </div>
              <div class="d-card-body">
                <div v-if="appointment.meeting_link" class="p-4 rounded-lg bg-gold/5 border border-gold/20 mb-4">
                  <p class="sec-label mb-2" style="color:var(--gold);">Meeting Link Ready</p>
                  <p class="text-sm text-cream/70 mb-4">Your Raqi has provided the session link. Click below to join when it's time.</p>
                  <a :href="appointment.meeting_link" target="_blank" class="btn btn-primary" style="text-decoration:none;">
                    🎥 Join Video Session
                  </a>
                </div>
                <div v-else class="empty-notes" style="padding:10px 0;">
                  The meeting link will appear here once your Raqi provides it.
                </div>

                <!-- Instructions -->
                <div v-if="appointment.patient_instructions" style="margin-top:20px;padding-top:20px;border-top:1px solid rgba(201,168,76,0.13);">
                  <div class="info-label" style="margin-bottom:8px;color:var(--gold);">Instructions from Raqi</div>
                  <div class="patient-note-box" style="border-left-color:#2ecc71;background:rgba(26,171,122,0.05);">
                    <div class="patient-note-text" style="color:var(--white-dim);">
                      {{ appointment.patient_instructions }}
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- YOUR NOTES -->
            <div class="d-card">
              <div class="d-card-head">
                <div class="d-card-title"><span class="d-card-title-icon">📝</span> Your Submitted Notes</div>
              </div>
              <div class="d-card-body">
                <div v-if="appointment.patient_notes" class="patient-note-box">
                  <div class="patient-note-text">
                    {{ appointment.patient_notes }}
                  </div>
                </div>
                <div v-else class="empty-notes">No notes provided.</div>
              </div>
            </div>

            <!-- SESSION NOTES -->
            <div class="d-card">
              <div class="d-card-head">
                <div class="d-card-title"><span class="d-card-title-icon">🌿</span> Session Notes</div>
                <span style="font-size:0.7rem;color:rgba(240,234,222,0.3);">Added by Raqi after session</span>
              </div>
              <div class="d-card-body">
                <div v-if="appointment.notes && appointment.notes.length" class="space-y-4">
                  <div v-for="note in appointment.notes" :key="note.id" class="note-item">
                    <span :class="['note-type-tag', note.note_type]">{{ note.note_type.replace('_', ' ') }}</span>
                    <div class="note-text">{{ note.content }}</div>
                    <div class="note-author">Added by {{ note.raqi?.user?.full_name || 'Raqi' }}</div>
                  </div>
                </div>
                <div v-else class="empty-notes">Notes will appear here once the session is completed and the Raqi adds them.</div>
              </div>
            </div>

          </div>

          <!-- RIGHT SIDEBAR -->
          <div class="sidebar-col">

            <!-- ACTIONS -->
            <div class="d-card">
              <div class="d-card-head">
                <div class="d-card-title"><span class="d-card-title-icon">⚡</span> Actions</div>
              </div>
              <div class="d-card-body">
                <div class="action-list">
                  <button v-if="appointment.status === 'confirmed'" class="btn btn-primary" @click="joinSession">
                    🎥 Join Session
                  </button>
                  <button class="btn btn-ghost" @click="addToCalendar">
                    📅 Add to Calendar
                  </button>
                  <button class="btn btn-ghost" @click="contactSupport">
                    💬 Contact Support
                  </button>
                  <button v-if="appointment.status === 'pending' || appointment.status === 'confirmed'" class="btn btn-danger" @click="cancelAppointment">
                    ✕ Cancel Appointment
                  </button>
                </div>
              </div>
            </div>

            <!-- FOLLOW-UP -->
            <div v-if="appointment.follow_up" class="d-card">
              <div class="d-card-head">
                <div class="d-card-title"><span class="d-card-title-icon">🔄</span> Follow-Up</div>
              </div>
              <div class="d-card-body">
                <div class="followup-box">
                  <div class="followup-icon">📌</div>
                  <div>
                    <div class="followup-date">{{ formatDate(appointment.follow_up.follow_up_date) }}</div>
                    <div class="followup-note">{{ appointment.follow_up.recommendation || 'Follow-up session scheduled.' }}</div>
                  </div>
                </div>
              </div>
            </div>

            <!-- QUICK INFO -->
            <div class="d-card">
              <div class="d-card-head">
                <div class="d-card-title"><span class="d-card-title-icon">ℹ️</span> Quick Info</div>
              </div>
              <div class="d-card-body" style="display:flex;flex-direction:column;gap:12px;">
                <div>
                  <div class="info-label" style="margin-bottom:4px;">Session Type</div>
                  <div style="font-size:0.85rem;color:#f0eade;">{{ formatSessionType(appointment.session_type) }}</div>
                </div>
                <div style="border-top:1px solid rgba(201,168,76,0.13);padding-top:12px;">
                  <div class="info-label" style="margin-bottom:4px;">Cancellation Policy</div>
                  <div style="font-size:0.78rem;color:rgba(240,234,222,0.3);line-height:1.7;">Free cancellation up to <span style="color:#f0eade;">2 hours</span> before the session start time.</div>
                </div>
                <div style="border-top:1px solid rgba(201,168,76,0.13);padding-top:12px;">
                  <div class="info-label" style="margin-bottom:4px;">Need Help?</div>
                  <div style="font-size:0.78rem;color:#f0b429;cursor:pointer;">support@jettyhealing.com</div>
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

      <div v-else class="error-state">
        <p>Appointment not found or you don't have permission to view it.</p>
        <router-link to="/patient/appointments" class="btn btn-primary mt-4" style="max-width:200px;margin:20px auto;">Back to Appointments</router-link>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { patientAPI } from '../../api';
import { formatDateTime, formatDate, formatTime, unwrap } from '../../utils';

const route = useRoute();
const router = useRouter();
const appointment = ref(null);
const loading = ref(true);

onMounted(async () => {
  try {
    const res = await patientAPI.appointments.get(route.params.id);
    appointment.value = unwrap(res);
  } catch (e) {
    console.error('Error fetching appointment:', e);
  } finally {
    loading.value = false;
  }
});

const getStatusIcon = (status) => {
  const icons = {
    confirmed: '✅',
    pending: '⏳',
    completed: '✦',
    cancelled: '✕',
    no_show: '❗'
  };
  return icons[status] || '📅';
};

const getStatusTitle = (status) => {
  const titles = {
    confirmed: 'Your session is confirmed',
    pending: 'Awaiting confirmation',
    completed: 'Session completed',
    cancelled: 'Appointment cancelled',
    no_show: 'Appointment missed'
  };
  return titles[status] || 'Appointment Details';
};

const getStatusSub = (status) => {
  if (!appointment.value) return '';
  const name = appointment.value.lead_raqi?.user?.full_name || 'your Raqi';
  
  const subs = {
    confirmed: `${formatDateTime(appointment.value.scheduled_at)} — ${name} is expecting you`,
    pending: `The request has been sent to ${name}. We'll notify you once it's confirmed.`,
    completed: `This session was held on ${formatDate(appointment.value.scheduled_at)}.`,
    cancelled: `This appointment has been cancelled and is no longer active.`,
    no_show: `The appointment time has passed without a session.`
  };
  return subs[status] || '';
};

const getSessionTypeIcon = (type) => {
  const icons = {
    video: '🎥',
    chat: '💬',
    phone: '📞',
    in_person: '🕌'
  };
  return icons[type] || '📅';
};

const formatSessionType = (type) => {
  const types = {
    video: 'Online Video',
    chat: 'Live Chat',
    phone: 'Phone Session',
    in_person: 'In-Person'
  };
  return types[type] || type;
};

const formatTimeRange = (start, duration) => {
  if (!start) return '';
  const startTime = new Date(start);
  const endTime = new Date(startTime.getTime() + duration * 60000);
  
  const formatOptions = { hour: 'numeric', minute: '2-digit', hour12: true };
  return `${startTime.toLocaleTimeString([], formatOptions)} — ${endTime.toLocaleTimeString([], formatOptions)}`;
};

const cancelAppointment = async () => {
  if (!confirm('Are you sure you want to cancel this appointment?\nCancellation is only allowed more than 2 hours before the session.')) return;
  
  try {
    await patientAPI.appointments.cancel(route.params.id);
    // Refresh data
    const res = await patientAPI.appointments.get(route.params.id);
    appointment.value = unwrap(res);
  } catch (e) {
    alert(e.response?.data?.message || 'Failed to cancel appointment');
  }
};

const joinSession = () => {
  if (appointment.value && appointment.value.meeting_link) {
    window.open(appointment.value.meeting_link, '_blank');
  } else {
    alert('Meeting link is not available yet.');
  }
};

const addToCalendar = () => {
  alert('Adding to calendar...');
};

const contactSupport = () => {
  alert('Contacting support...');
};
</script>

<style scoped>
.page-wrapper {
  background: #0a0814;
  min-height: 100vh;
  color: #f0eade;
  font-family: 'DM Sans', sans-serif;
}

.page {
  max-width: 1000px;
  margin: 0 auto;
  padding: 32px 40px 80px;
}

.back-link {
  display: inline-flex;
  align-items: center;
  gap: 7px;
  font-size: 0.8rem;
  color: rgba(240,234,222,0.3);
  text-decoration: none;
  letter-spacing: 0.06em;
  margin-bottom: 28px;
  transition: color 0.2s;
}
.back-link:hover { color: #f0b429; }

.detail-header {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  margin-bottom: 32px;
  gap: 20px;
}
.detail-heading {
  font-family: 'Cinzel', serif;
  font-size: 1.55rem;
  font-weight: 400;
  letter-spacing: 0.07em;
  color: #f0eade;
  margin-bottom: 5px;
}
.detail-id {
  font-size: 0.72rem;
  letter-spacing: 0.18em;
  color: rgba(240,234,222,0.3);
  text-transform: uppercase;
}

.status-badge {
  font-size: 0.72rem;
  letter-spacing: 0.14em;
  text-transform: uppercase;
  padding: 6px 16px;
  border-radius: 30px;
  font-weight: 500;
  white-space: nowrap;
  border: 1px solid transparent;
}
.status-badge.confirmed { background: rgba(26,171,122,0.14); color: #2ecc71; border-color: rgba(26,171,122,0.25); }
.status-badge.pending   { background: rgba(224,154,32,0.14); color: #f0b429; border-color: rgba(240,180,41,0.25); }
.status-badge.completed { background: rgba(139,92,246,0.14); color: #a78bfa; border-color: rgba(139,92,246,0.25); }
.status-badge.cancelled { background: rgba(192,57,43,0.14); color: #e74c3c; border-color: rgba(192,57,43,0.25); }

.status-banner {
  border-radius: 12px;
  padding: 22px 28px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 28px;
  border: 1px solid transparent;
}
.status-banner.confirmed { background: rgba(26,171,122,0.14); border-color: rgba(26,171,122,0.2); }
.status-banner.pending   { background: rgba(240,180,41,0.10); border-color: rgba(240,180,41,0.2); }
.status-banner.completed { background: rgba(139,92,246,0.14); border-color: rgba(139,92,246,0.2); }
.status-banner.cancelled { background: rgba(192,57,43,0.14); border-color: rgba(192,57,43,0.2); }

.banner-left { display: flex; align-items: center; gap: 16px; }
.banner-icon { font-size: 1.8rem; }
.banner-title {
  font-family: 'Cinzel', serif;
  font-size: 1rem;
  letter-spacing: 0.06em;
  color: #f0eade;
  margin-bottom: 3px;
}
.banner-sub { font-size: 0.78rem; color: rgba(240,234,222,0.3); }
.banner-countdown {
  font-family: 'Cinzel', serif;
  font-size: 1rem;
  color: #f0b429;
  letter-spacing: 0.05em;
  text-align: right;
}
.banner-countdown small { 
  font-family: 'DM Sans', sans-serif; 
  font-size: 0.68rem; 
  color: rgba(240,234,222,0.3); 
  display: block; 
  letter-spacing: 0.12em; 
  text-transform: uppercase; 
  margin-bottom: 2px; 
}

.detail-grid {
  display: grid;
  grid-template-columns: 1fr 340px;
  gap: 20px;
  align-items: start;
}

.d-card {
  background: #141228;
  border: 1px solid rgba(201,168,76,0.13);
  border-radius: 12px;
  overflow: hidden;
  margin-bottom: 20px;
}

.d-card-head {
  padding: 16px 22px;
  border-bottom: 1px solid rgba(201,168,76,0.13);
  display: flex;
  align-items: center;
  justify-content: space-between;
}
.d-card-title {
  font-size: 0.68rem;
  letter-spacing: 0.28em;
  text-transform: uppercase;
  color: #f0b429;
  display: flex;
  align-items: center;
  gap: 8px;
}
.d-card-title-icon { font-size: 0.9rem; opacity: 0.8; }
.d-card-body { padding: 22px; }

.info-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 14px;
}
.info-item {
  background: #181535;
  border: 1px solid rgba(201,168,76,0.13);
  border-radius: 8px;
  padding: 14px 16px;
  transition: border-color 0.2s;
}
.info-item:hover { border-color: rgba(201,168,76,0.28); }
.info-label {
  font-size: 0.67rem;
  letter-spacing: 0.18em;
  text-transform: uppercase;
  color: rgba(240,234,222,0.3);
  margin-bottom: 7px;
}
.info-value {
  font-size: 0.9rem;
  color: #f0eade;
  font-weight: 500;
}
.info-value.gold { color: #f0b429; }
.info-value.type-pill {
  display: inline-flex;
  align-items: center;
  gap: 6px;
}

.raqi-card {
  display: flex;
  align-items: center;
  gap: 16px;
  padding: 4px 0 16px;
  border-bottom: 1px solid rgba(201,168,76,0.13);
  margin-bottom: 16px;
}
.raqi-avatar {
  width: 56px; height: 56px;
  border-radius: 50%;
  background: rgba(139,92,246,0.14);
  border: 2px solid rgba(139,92,246,0.3);
  display: flex;
  align-items: center;
  justify-content: center;
  font-family: 'Cinzel', serif;
  font-size: 1.3rem;
  color: #a78bfa;
  flex-shrink: 0;
}
.raqi-name {
  font-family: 'Cinzel', serif;
  font-size: 1.05rem;
  letter-spacing: 0.06em;
  color: #f0eade;
  margin-bottom: 3px;
}
.raqi-spec { font-size: 0.75rem; color: rgba(240,234,222,0.3); margin-bottom: 5px; }
.raqi-stars { font-size: 0.82rem; color: #f0b429; letter-spacing: 2px; }
.raqi-sessions { font-size: 0.7rem; color: rgba(240,234,222,0.3); }

.participant-row {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 10px 0;
  border-bottom: 1px solid rgba(201,168,76,0.13);
}
.participant-row:last-child { border-bottom: none; }
.p-av {
  width: 34px; height: 34px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-family: 'Cinzel', serif;
  font-size: 0.8rem;
  flex-shrink: 0;
  border: 1px solid;
}
.p-av.lead    { background: rgba(240,180,41,0.10); border-color: rgba(240,180,41,0.3); color: #f0b429; }
.p-av.co_raqi { background: rgba(139,92,246,0.14); border-color: rgba(139,92,246,0.3); color: #a78bfa; }
.p-name { font-size: 0.83rem; color: rgba(240,234,222,0.60); flex: 1; }
.p-badge {
  font-size: 0.63rem;
  letter-spacing: 0.14em;
  text-transform: uppercase;
  padding: 3px 10px;
  border-radius: 20px;
}
.p-badge.lead    { background: rgba(240,180,41,0.10); color: #f0b429; }
.p-badge.co_raqi { background: rgba(139,92,246,0.14); color: #a78bfa; }

.patient-note-box {
  background: #181535;
  border: 1px solid rgba(201,168,76,0.13);
  border-left: 3px solid #f0b429;
  border-radius: 0 8px 8px 0;
  padding: 16px 18px;
}
.patient-note-text {
  font-family: 'Cormorant Garamond', serif;
  font-size: 1rem;
  font-style: italic;
  color: rgba(240,234,222,0.60);
  line-height: 1.8;
}

.note-item {
  padding: 14px 0;
  border-bottom: 1px solid rgba(201,168,76,0.13);
}
.note-item:last-child { border-bottom: none; }
.note-type-tag {
  display: inline-block;
  font-size: 0.63rem;
  letter-spacing: 0.16em;
  text-transform: uppercase;
  padding: 3px 10px;
  border-radius: 20px;
  margin-bottom: 8px;
  background: rgba(240,180,41,0.10);
  color: #f0b429;
}
.note-type-tag.observation    { background: rgba(26,171,122,0.14); color: #2ecc71; }
.note-type-tag.recommendation { background: rgba(139,92,246,0.14); color: #a78bfa; }
.note-text {
  font-family: 'Cormorant Garamond', serif;
  font-size: 1rem;
  font-style: italic;
  color: rgba(240,234,222,0.60);
  line-height: 1.75;
  margin-bottom: 6px;
}
.note-author { font-size: 0.7rem; color: rgba(240,234,222,0.3); }

.action-list { display: flex; flex-direction: column; gap: 10px; }
.btn {
  width: 100%;
  font-family: 'DM Sans', sans-serif;
  font-size: 0.8rem;
  letter-spacing: 0.14em;
  text-transform: uppercase;
  padding: 12px 20px;
  border-radius: 8px;
  border: 1px solid;
  cursor: pointer;
  transition: all 0.2s;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
}
.btn-primary {
  background: #f0b429;
  color: #1a1200;
  border-color: #f0b429;
  font-weight: 500;
}
.btn-primary:hover { background: #ffd166; border-color: #ffd166; transform: translateY(-1px); }
.btn-ghost {
  background: none;
  color: rgba(240,234,222,0.60);
  border-color: rgba(201,168,76,0.28);
}
.btn-ghost:hover { color: #f0eade; border-color: #f0b429; background: rgba(240,180,41,0.10); }
.btn-danger {
  background: none;
  color: #e74c3c;
  border-color: rgba(192,57,43,0.3);
}
.btn-danger:hover { background: rgba(192,57,43,0.14); border-color: #e74c3c; }

.followup-box {
  background: #181535;
  border: 1px solid rgba(26,171,122,0.25);
  border-radius: 8px;
  padding: 14px 16px;
  display: flex;
  align-items: flex-start;
  gap: 12px;
}
.followup-icon { font-size: 1.3rem; margin-top: 1px; }
.followup-date {
  font-family: 'Cinzel', serif;
  font-size: 0.88rem;
  color: #2ecc71;
  margin-bottom: 3px;
}
.followup-note { font-size: 0.75rem; color: rgba(240,234,222,0.3); line-height: 1.6; }

.empty-notes {
  text-align: center;
  padding: 24px 0 8px;
  color: rgba(240,234,222,0.3);
  font-size: 0.82rem;
  font-style: italic;
}

/* Skeleton Loaders */
.loading-state { padding: 20px 0; }
.skeleton-header { height: 60px; background: rgba(240,234,222,0.05); border-radius: 8px; margin-bottom: 32px; }
.skeleton-banner { height: 80px; background: rgba(240,234,222,0.05); border-radius: 12px; margin-bottom: 28px; }
.skeleton-grid { display: grid; grid-template-columns: 1fr 340px; gap: 20px; }
.skeleton-main { height: 600px; background: rgba(240,234,222,0.05); border-radius: 12px; }
.skeleton-side { height: 400px; background: rgba(240,234,222,0.05); border-radius: 12px; }

@media (max-width: 800px) {
  .page { padding: 20px 16px 60px; }
  .detail-grid { grid-template-columns: 1fr; }
  .detail-header { flex-direction: column; align-items: flex-start; }
  .header-badge-row { margin-top: 10px; }
}
</style>
