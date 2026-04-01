<template>
  <div class="profile-page">
    <div v-if="pageError" class="profile-alert profile-alert--error">{{ pageError }}</div>

    <div v-else class="profile-inner">
      <div class="profile-hero">
        <div class="hero-avatar-wrap">
          <div class="hero-avatar">{{ avatarLetter }}</div>
          <div class="avatar-ring" />
          <button type="button" class="avatar-edit" title="Edit photo" @click="toast('Photo upload coming soon.', '📷')">✎</button>
        </div>
        <div class="hero-info">
          <div class="hero-name">{{ form.full_name || 'Your name' }}</div>
          <div class="hero-email">{{ form.email || '—' }}</div>
          <div v-if="form.specialization" class="hero-sub">{{ form.specialization }}</div>
          <div class="hero-badges">
            <span class="hbadge hbadge--raqi">Raqi</span>
            <span v-if="form.is_approved" class="hbadge hbadge--approved">✓ Approved</span>
            <span v-else class="hbadge hbadge--pending">Pending approval</span>
            <span v-if="showVerifiedBadge" class="hbadge hbadge--verified">✓ Verified</span>
            <span class="hbadge hbadge--stat">{{ completedCount }} completed</span>
            <span v-if="Number(form.total_reviews) > 0" class="hbadge hbadge--reviews">★ {{ ratingDisplay }} · {{ form.total_reviews }} reviews</span>
          </div>
        </div>
        <button type="button" class="hero-save" :disabled="loading" @click="saveAll">Save Changes</button>
      </div>

      <div v-if="form.is_approved === false" class="profile-alert profile-alert--warn">
        Your account is pending admin approval. You can edit your profile below; availability, appointments, and sessions unlock after approval.
      </div>

      <div class="pcard pcard--progress">
        <div class="pcard-body pcard-body--tight">
          <div class="progress-wrap">
            <div class="progress-label">
              <span>Profile completion</span>
              <span>{{ profileProgress }}%</span>
            </div>
            <div class="progress-bar">
              <div class="progress-fill" :style="{ width: profileProgress + '%' }" />
            </div>
          </div>
          <p class="progress-hint">A complete public profile helps patients trust you when they browse Raqis.</p>
        </div>
      </div>

      <div class="tab-nav" role="tablist">
        <button type="button" class="tab-btn" :class="{ active: activeTab === 'general' }" @click="activeTab = 'general'">
          <span class="tab-icon">⚙</span> General
        </button>
        <button type="button" class="tab-btn" :class="{ active: activeTab === 'public' }" @click="activeTab = 'public'">
          <span class="tab-icon">🌐</span> Public profile
        </button>
        <button type="button" class="tab-btn" :class="{ active: activeTab === 'sessions' }" @click="activeTab = 'sessions'">
          <span class="tab-icon">📅</span> Sessions
          <span v-if="appointments.length" class="tab-badge">{{ appointments.length }}</span>
        </button>
        <button type="button" class="tab-btn" :class="{ active: activeTab === 'security' }" @click="activeTab = 'security'">
          <span class="tab-icon">🔒</span> Security
        </button>
      </div>

      <!-- General -->
      <div v-show="activeTab === 'general'" class="tab-panel">
        <div class="pcard">
          <div class="pcard-head"><div class="pcard-title">👤 Account & contact</div></div>
          <div class="pcard-body">
            <div class="form-grid">
              <div class="form-group">
                <label class="flabel">Full Name <span class="req">*</span></label>
                <input v-model="form.full_name" class="finput" type="text" placeholder="Your full name">
              </div>
              <div class="form-group">
                <label class="flabel">Email <span class="req">*</span></label>
                <input v-model="form.email" class="finput" type="email">
              </div>
              <div class="form-group span2">
                <label class="flabel">Phone</label>
                <input v-model="form.phone" class="finput" type="tel" placeholder="+880…">
              </div>
            </div>
          </div>
        </div>

        <div class="pcard">
          <div class="pcard-head"><div class="pcard-title">📋 Status</div></div>
          <div class="pcard-body">
            <div class="status-grid">
              <div class="status-item">
                <span class="status-label">Listing status</span>
                <span class="status-val">{{ statusLabel }}</span>
              </div>
              <div class="status-item">
                <span class="status-label">Admin approval</span>
                <span class="status-val">{{ form.is_approved ? 'Approved' : 'Pending' }}</span>
              </div>
              <div class="status-item">
                <span class="status-label">Upcoming sessions</span>
                <span class="status-val">{{ upcomingCount }}</span>
              </div>
              <div class="status-item">
                <span class="status-label">Completed sessions</span>
                <span class="status-val">{{ completedCount }}</span>
              </div>
            </div>
            <p class="fhint">Approval and account status are managed by Jetty Healing administrators.</p>
          </div>
        </div>

        <div class="save-row">
          <button type="button" class="btn-discard" @click="discard">Discard</button>
          <button type="button" class="btn-save" :disabled="loading" @click="saveAll">Save account</button>
        </div>
      </div>

      <!-- Public profile (what patients see) -->
      <div v-show="activeTab === 'public'" class="tab-panel">
        <div class="pcard">
          <div class="pcard-head">
            <div class="pcard-title">✨ How patients see you</div>
            <router-link v-if="form.id" :to="`/raqis/${form.id}`" class="preview-link" target="_blank" rel="noopener">Preview listing →</router-link>
          </div>
          <div class="pcard-body">
            <div class="form-grid form-grid--1">
              <div class="form-group">
                <label class="flabel">Specialization <span class="req">*</span></label>
                <input v-model="form.specialization" class="finput" type="text" placeholder="e.g. Quranic Ruqyah, Islamic counseling…">
              </div>
              <div class="form-group">
                <label class="flabel">Languages</label>
                <input v-model="form.languages" class="finput" type="text" placeholder="English, Arabic, Urdu…">
                <p class="fhint">Comma-separated. Shown on your public Raqi card.</p>
              </div>
              <div class="form-group">
                <label class="flabel">Bio</label>
                <textarea v-model="form.bio" class="ftextarea" rows="8" placeholder="Introduce yourself, your training, and how you help patients…" />
                <p class="fhint">This appears on your browse profile. Keep it professional and within Islamic guidelines.</p>
              </div>
            </div>
          </div>
        </div>

        <div class="save-row">
          <button type="button" class="btn-discard" @click="discard">Discard</button>
          <button type="button" class="btn-save" :disabled="loading" @click="saveAll">Save public profile</button>
        </div>
      </div>

      <!-- Sessions -->
      <div v-show="activeTab === 'sessions'" class="tab-panel">
        <div class="sec-head">
          <div class="sec-title">Your sessions</div>
          <div class="sec-sub">Recent appointments where you are the lead Raqi. Open a row to add notes and manage the session.</div>
        </div>

        <div v-if="!form.is_approved" class="log-empty">
          Session history will appear here after your account is approved.
        </div>
        <template v-else>
          <div class="log-filters">
            <button type="button" class="log-filter" :class="{ active: sessionFilter === 'all' }" @click="sessionFilter = 'all'">All</button>
            <button type="button" class="log-filter" :class="{ active: sessionFilter === 'upcoming' }" @click="sessionFilter = 'upcoming'">Upcoming</button>
            <button type="button" class="log-filter" :class="{ active: sessionFilter === 'completed' }" @click="sessionFilter = 'completed'">Completed</button>
            <button type="button" class="log-filter" :class="{ active: sessionFilter === 'cancelled' }" @click="sessionFilter = 'cancelled'">Cancelled / no-show</button>
          </div>
          <div v-if="apptsLoading" class="log-empty">Loading appointments…</div>
          <div v-else-if="!filteredAppointments.length" class="log-empty">No appointments in this view yet.</div>
          <div v-else class="log-timeline">
            <div v-for="apt in filteredAppointments" :key="apt.id" class="log-entry">
              <div class="log-dot" />
              <div class="log-card">
                <div class="log-card-head">
                  <div class="log-appt-info">
                    <div class="log-raqi-av" :style="patientAvatarStyle(apt)">{{ patientInitial(apt) }}</div>
                    <div>
                      <div class="log-raqi-name">{{ apt.patient?.full_name || 'Patient' }}</div>
                      <div class="log-date">{{ formatDateTime(apt.scheduled_at) }}</div>
                    </div>
                  </div>
                  <div class="log-head-right">
                    <span class="log-type-pill" :class="sessionTypeClass(apt.session_type)">{{ sessionTypeLabel(apt.session_type) }}</span>
                    <span class="apt-status" :class="'apt-status--' + statusSlug(apt.status)">{{ statusLabelApt(apt.status) }}</span>
                  </div>
                </div>
                <div class="log-card-actions">
                  <router-link :to="`/raqi/sessions/${apt.id}`" class="link-session">Open session →</router-link>
                </div>
              </div>
            </div>
          </div>
        </template>
      </div>

      <!-- Security -->
      <div v-show="activeTab === 'security'" class="tab-panel">
        <div class="pcard">
          <div class="pcard-head"><div class="pcard-title">🔑 Change password</div></div>
          <div class="pcard-body">
            <div v-if="passwordMessage" class="profile-alert" :class="passwordMessage.type === 'success' ? 'profile-alert--ok' : 'profile-alert--error'">{{ passwordMessage.text }}</div>
            <div class="form-grid form-grid--1 security-grid">
              <div class="form-group">
                <label class="flabel">Current password</label>
                <input v-model="passwordForm.current_password" class="finput" type="password" autocomplete="current-password">
              </div>
              <div class="form-group">
                <label class="flabel">New password</label>
                <input v-model="passwordForm.password" class="finput" type="password" autocomplete="new-password" placeholder="Min 8 characters">
              </div>
              <div class="form-group">
                <label class="flabel">Confirm new password</label>
                <input v-model="passwordForm.password_confirmation" class="finput" type="password" autocomplete="new-password">
              </div>
            </div>
            <div class="save-row save-row--left">
              <button type="button" class="btn-save" :disabled="passwordLoading" @click="savePassword">{{ passwordLoading ? 'Updating…' : 'Update password' }}</button>
            </div>
          </div>
        </div>

        <div class="pcard">
          <div class="pcard-head"><div class="pcard-title">📱 Two-factor authentication</div></div>
          <div class="pcard-body">
            <div class="security-row">
              <div>
                <div class="security-row-title">SMS authentication</div>
                <div class="security-row-sub">Not available yet — we will notify you when enabled.</div>
              </div>
              <button type="button" class="btn-save btn-save--muted" @click="toast('2FA coming soon.', '📱')">Coming soon</button>
            </div>
          </div>
        </div>

        <div class="danger-zone">
          <div class="dz-title">⚠ Danger zone</div>
          <div class="dz-desc">Deleting a Raqi account must be done through support so patient records and appointments stay consistent.</div>
          <button type="button" class="dz-btn" @click="toast('Please contact support to delete your account.', '⚠')">Delete my account</button>
        </div>
      </div>
    </div>

    <div class="toast" :class="{ show: toastVisible }">
      <span>{{ toastIcon }}</span>
      <span>{{ toastMsg }}</span>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted, watch } from 'vue';
import { raqiAPI } from '../../api';
import { formatDateTime, unwrap } from '../../utils';

const loading = ref(false);
const pageError = ref(null);
const activeTab = ref('general');
const sessionFilter = ref('all');
const appointments = ref([]);
const apptsLoading = ref(false);
const appointmentsFetchAttempted = ref(false);
const toastVisible = ref(false);
const toastMsg = ref('');
const toastIcon = ref('✓');
let toastTimer;

const form = reactive(emptyForm());
let snapshot = null;

function emptyForm() {
  return {
    id: '',
    full_name: '',
    email: '',
    phone: '',
    bio: '',
    specialization: '',
    languages: '',
    status: '',
    is_approved: null,
    rating: 0,
    total_reviews: 0,
    is_verified: false,
    email_verified_at: null,
    completed_sessions_count: 0,
    upcoming_sessions_count: 0,
  };
}

const avatarLetter = computed(() => (form.full_name || '?').trim().charAt(0).toUpperCase() || '?');
const showVerifiedBadge = computed(() => !!(form.is_verified || form.email_verified_at));
const completedCount = computed(() => form.completed_sessions_count ?? 0);
const upcomingCount = computed(() => form.upcoming_sessions_count ?? 0);

const ratingDisplay = computed(() => {
  const r = Number(form.rating);
  return Number.isFinite(r) ? r.toFixed(1) : '—';
});

const statusLabel = computed(() => {
  const s = form.status;
  const v = typeof s === 'object' && s?.value != null ? s.value : s;
  if (!v) return '—';
  return String(v).charAt(0).toUpperCase() + String(v).slice(1);
});

const profileProgress = computed(() => {
  const checks = [
    () => form.full_name?.trim(),
    () => form.email?.trim(),
    () => form.phone?.trim(),
    () => form.specialization?.trim(),
    () => (form.bio || '').trim().length > 40,
    () => (form.languages || '').trim().length > 2,
  ];
  const n = checks.filter((c) => c()).length;
  return Math.min(100, Math.round((n / checks.length) * 100));
});

function normalizeLanguages(raw) {
  if (raw == null || raw === '') return '';
  if (typeof raw === 'string' && raw.trim().startsWith('[')) {
    try {
      const p = JSON.parse(raw);
      return Array.isArray(p) ? p.join(', ') : raw;
    } catch {
      return raw;
    }
  }
  return String(raw);
}

function applyPayload(data) {
  Object.assign(form, emptyForm());
  if (!data) return;
  const bool = (v) => v === true || v === 1 || v === '1';
  form.id = data.id || '';
  form.full_name = data.full_name || '';
  form.email = data.email || '';
  form.phone = data.phone || '';
  form.bio = data.bio || '';
  form.specialization = data.specialization || '';
  form.languages = normalizeLanguages(data.languages);
  form.status = data.status ?? '';
  form.is_approved = data.is_approved === null || data.is_approved === undefined ? null : bool(data.is_approved);
  form.rating = data.rating ?? 0;
  form.total_reviews = data.total_reviews ?? 0;
  form.is_verified = bool(data.is_verified);
  form.email_verified_at = data.email_verified_at || null;
  form.completed_sessions_count = data.completed_sessions_count ?? 0;
  form.upcoming_sessions_count = data.upcoming_sessions_count ?? 0;
}

function buildUpdatePayload() {
  return {
    full_name: form.full_name,
    email: form.email,
    phone: form.phone || null,
    bio: form.bio || null,
    specialization: form.specialization || null,
    languages: form.languages?.trim() || null,
  };
}

function saveSnapshot() {
  snapshot = JSON.parse(JSON.stringify(form));
}

function discard() {
  if (snapshot) applyPayload(snapshot);
  toast('Changes discarded.', '↩');
}

async function saveAll() {
  loading.value = true;
  try {
    const res = await raqiAPI.profile.update(buildUpdatePayload());
    applyPayload(res.data?.data);
    saveSnapshot();
    toast(res.data?.message || 'Profile saved.', '✓');
  } catch (e) {
    toast(e.response?.data?.message || 'Failed to save.', '✕');
  } finally {
    loading.value = false;
  }
}

const filteredAppointments = computed(() => {
  const list = appointments.value || [];
  if (sessionFilter.value === 'all') return list;
  if (sessionFilter.value === 'upcoming') {
    return list.filter((a) => ['pending', 'confirmed'].includes(statusSlug(a.status)));
  }
  if (sessionFilter.value === 'completed') {
    return list.filter((a) => statusSlug(a.status) === 'completed');
  }
  if (sessionFilter.value === 'cancelled') {
    return list.filter((a) => ['cancelled', 'no_show'].includes(statusSlug(a.status)));
  }
  return list;
});

function statusSlug(raw) {
  const v = typeof raw === 'object' && raw?.value != null ? raw.value : raw;
  return String(v || '').toLowerCase();
}

function statusLabelApt(raw) {
  const s = statusSlug(raw);
  const map = {
    pending: 'Pending',
    confirmed: 'Confirmed',
    completed: 'Completed',
    cancelled: 'Cancelled',
    no_show: 'No show',
  };
  return map[s] || s || '—';
}

const SESSION_TYPE_MAP = {
  video: 'Online Video',
  phone: 'Phone Call',
  chat: 'Online Chat',
  in_person: 'In Person',
};

function sessionTypeLabel(raw) {
  const k = typeof raw === 'object' && raw?.value != null ? raw.value : raw;
  return SESSION_TYPE_MAP[k] || String(k || '').replace(/_/g, ' ');
}

function sessionTypeClass(raw) {
  const k = typeof raw === 'object' && raw?.value != null ? raw.value : raw;
  return 'log-type-pill--' + String(k || '').replace(/_/g, '-');
}

function patientInitial(apt) {
  const n = apt.patient?.full_name || 'P';
  return n.trim().charAt(0).toUpperCase();
}

function patientAvatarStyle(apt) {
  const palette = [
    ['rgba(26,171,122,.12)', 'rgba(26,171,122,.3)', '#2ecc71'],
    ['rgba(124,92,191,.15)', 'rgba(124,92,191,.3)', '#a78bfa'],
    ['rgba(240,180,41,.12)', 'rgba(240,180,41,.35)', '#f0b429'],
  ];
  const idx = (apt.id || '').charCodeAt(0) % palette.length;
  const [bg, border, color] = palette[idx];
  return { background: bg, borderColor: border, color };
}

async function loadAppointments() {
  if (!form.is_approved) {
    appointmentsFetchAttempted.value = true;
    return;
  }
  apptsLoading.value = true;
  try {
    const res = await raqiAPI.appointments.list();
    const payload = unwrap(res);
    appointments.value = Array.isArray(payload) ? payload : (payload?.data || []);
  } catch {
    appointments.value = [];
  } finally {
    apptsLoading.value = false;
    appointmentsFetchAttempted.value = true;
  }
}

const passwordForm = reactive({
  current_password: '',
  password: '',
  password_confirmation: '',
});
const passwordLoading = ref(false);
const passwordMessage = ref(null);

async function savePassword() {
  passwordMessage.value = null;
  passwordLoading.value = true;
  try {
    const res = await raqiAPI.profile.updatePassword({ ...passwordForm });
    passwordForm.current_password = '';
    passwordForm.password = '';
    passwordForm.password_confirmation = '';
    passwordMessage.value = { type: 'success', text: res.data?.message || 'Password updated.' };
    toast(passwordMessage.value.text, '🔑');
  } catch (e) {
    const body = e.response?.data;
    const msg = body?.message || 'Could not update password.';
    const errs = body?.errors || body?.data;
    const detail = errs && typeof errs === 'object' ? Object.values(errs).flat().join(' ') : '';
    passwordMessage.value = { type: 'error', text: detail || msg };
  } finally {
    passwordLoading.value = false;
  }
}

function toast(msg, icon = '✓') {
  toastMsg.value = msg;
  toastIcon.value = icon;
  toastVisible.value = true;
  clearTimeout(toastTimer);
  toastTimer = setTimeout(() => { toastVisible.value = false; }, 3200);
}

onMounted(async () => {
  pageError.value = null;
  try {
    const res = await raqiAPI.profile.get();
    applyPayload(res.data?.data);
    saveSnapshot();
    await loadAppointments();
  } catch (e) {
    pageError.value = e.response?.data?.message || 'Failed to load profile.';
  }
});

watch(activeTab, (tab) => {
  if (tab === 'sessions' && form.is_approved && !appointmentsFetchAttempted.value && !apptsLoading.value) {
    loadAppointments();
  }
});
</script>

<style scoped>
.profile-page {
  --void: #08060f;
  --card: #121024;
  --card2: #161330;
  --card3: #1b1840;
  --border: rgba(201,168,76,0.12);
  --border-mid: rgba(201,168,76,0.26);
  --gold: #f0b429;
  --gold-dim: rgba(240,180,41,0.09);
  --white: #f0eade;
  --white-dim: rgba(240,234,222,0.60);
  --white-faint: rgba(240,234,222,0.28);
  --teal: #1aab7a;
  --purple: #8b5cf6;
  --font-d: 'Times New Roman', 'Cinzel', serif;
  --font-b: Georgia, 'Cormorant Garamond', serif;
  --font-ui: system-ui, 'DM Sans', sans-serif;
  min-height: 100vh;
  background: var(--void);
  color: var(--white);
  font-family: var(--font-ui);
  padding-bottom: 5rem;
}

.profile-inner { max-width: 980px; margin: 0 auto; padding: 36px 40px 40px; }

.profile-alert { border-radius: 8px; padding: 12px 16px; font-size: 0.85rem; margin-bottom: 16px; }
.profile-alert--error { background: rgba(192,57,43,0.12); border: 1px solid rgba(192,57,43,0.35); color: #f5a8a0; }
.profile-alert--ok { background: rgba(26,171,122,0.12); border: 1px solid rgba(26,171,122,0.35); color: #86efac; }
.profile-alert--warn { background: rgba(240,180,41,0.08); border: 1px solid rgba(240,180,41,0.28); color: #fcd34d; }

.profile-hero { display: flex; align-items: center; gap: 28px; margin-bottom: 28px; flex-wrap: wrap; }
.hero-avatar-wrap { position: relative; flex-shrink: 0; }
.hero-avatar {
  width: 84px; height: 84px; border-radius: 50%;
  background: linear-gradient(135deg, rgba(201,168,76,.2), rgba(139,92,246,.22));
  border: 2px solid var(--border-mid);
  display: flex; align-items: center; justify-content: center;
  font-family: var(--font-d); font-size: 2rem; color: var(--gold);
}
.avatar-ring { position: absolute; inset: -5px; border-radius: 50%; border: 1px solid rgba(240,180,41,.2); pointer-events: none; }
.avatar-edit {
  position: absolute; bottom: 2px; right: 2px; width: 26px; height: 26px; border-radius: 50%;
  background: var(--gold); color: #1a1200; border: 2px solid var(--void);
  display: flex; align-items: center; justify-content: center; font-size: .65rem; cursor: pointer;
}
.hero-info { flex: 1; min-width: 200px; }
.hero-name { font-family: var(--font-d); font-size: 1.5rem; letter-spacing: .07em; margin-bottom: 4px; }
.hero-email { font-size: .82rem; color: var(--white-faint); margin-bottom: 6px; }
.hero-sub { font-size: .8rem; color: var(--white-dim); margin-bottom: 10px; font-style: italic; }
.hero-badges { display: flex; gap: 8px; flex-wrap: wrap; }
.hbadge {
  font-size: .65rem; letter-spacing: .14em; text-transform: uppercase;
  padding: 4px 12px; border-radius: 20px;
}
.hbadge--raqi { background: rgba(201,168,76,0.12); color: #f0b429; border: 1px solid rgba(201,168,76,.28); }
.hbadge--approved { background: rgba(26,171,122,0.12); color: #2ecc71; border: 1px solid rgba(26,171,122,.25); }
.hbadge--pending { background: rgba(251,191,36,0.1); color: #fbbf24; border: 1px solid rgba(251,191,36,.25); }
.hbadge--verified { background: var(--gold-dim); color: var(--gold); border: 1px solid rgba(240,180,41,.25); }
.hbadge--stat { background: rgba(139,92,246,0.12); color: #a78bfa; border: 1px solid rgba(139,92,246,.25); }
.hbadge--reviews { background: rgba(240,180,41,0.08); color: #fcd34d; border: 1px solid rgba(240,180,41,.22); }

.hero-save {
  margin-left: auto; font-size: .78rem; letter-spacing: .14em; text-transform: uppercase;
  background: var(--gold); color: #1a1200; border: none; padding: 11px 26px; border-radius: 8px;
  cursor: pointer; font-weight: 500;
}
.hero-save:disabled { opacity: 0.5; cursor: not-allowed; }

.pcard { background: var(--card); border: 1px solid var(--border); border-radius: 12px; margin-bottom: 24px; overflow: hidden; }
.pcard--progress { margin-bottom: 28px; }
.pcard-head { padding: 15px 22px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap; }
.pcard-title { font-size: .67rem; letter-spacing: .26em; text-transform: uppercase; color: var(--gold); }
.pcard-body { padding: 24px; }
.pcard-body--tight { padding: 18px 22px; }

.preview-link { font-size: .78rem; color: #a78bfa; text-decoration: none; }
.preview-link:hover { text-decoration: underline; }

.progress-label { display: flex; justify-content: space-between; font-size: .68rem; color: var(--white-faint); margin-bottom: 6px; }
.progress-bar { height: 4px; background: var(--card3); border-radius: 2px; overflow: hidden; }
.progress-fill { height: 100%; background: var(--gold); transition: width .4s ease; }
.progress-hint { font-size: .72rem; color: var(--white-faint); margin-top: 8px; line-height: 1.5; }

.tab-nav { display: flex; flex-wrap: wrap; border-bottom: 1px solid var(--border); margin-bottom: 32px; }
.tab-btn {
  display: flex; align-items: center; gap: 8px; font-size: .82rem; padding: 12px 22px;
  color: var(--white-faint); background: none; border: none; border-bottom: 2px solid transparent;
  cursor: pointer; margin-bottom: -1px;
}
.tab-btn.active { color: var(--gold); border-bottom-color: var(--gold); }
.tab-badge { font-size: .6rem; background: var(--gold-dim); color: var(--gold); border-radius: 20px; padding: 1px 7px; }

.form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 18px; }
.form-grid--1 { grid-template-columns: 1fr; }
.form-group { display: flex; flex-direction: column; gap: 7px; }
.form-group.span2 { grid-column: span 2; }
.flabel { font-size: .67rem; letter-spacing: .18em; text-transform: uppercase; color: var(--white-faint); }
.req { color: #e74c3c; margin-left: 2px; }
.finput, .ftextarea {
  background: var(--card2); border: 1px solid var(--border); border-radius: 8px; padding: 11px 14px;
  font-size: .85rem; color: var(--white); outline: none; width: 100%;
}
.finput:focus, .ftextarea:focus { border-color: var(--border-mid); box-shadow: 0 0 0 3px rgba(240,180,41,.08); }
.ftextarea { resize: vertical; min-height: 120px; font-family: var(--font-b); line-height: 1.7; }
.fhint { font-size: .68rem; color: var(--white-faint); line-height: 1.5; margin-top: 4px; }

.status-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 16px; }
.status-item { background: var(--card2); border: 1px solid var(--border); border-radius: 8px; padding: 14px 16px; }
.status-label { display: block; font-size: .62rem; letter-spacing: .14em; text-transform: uppercase; color: var(--white-faint); margin-bottom: 6px; }
.status-val { font-size: .95rem; color: var(--white); }

.save-row { display: flex; justify-content: flex-end; gap: 12px; margin-top: 24px; padding-top: 24px; border-top: 1px solid var(--border); flex-wrap: wrap; }
.save-row--left { justify-content: flex-start; }
.btn-save { font-size: .78rem; letter-spacing: .14em; text-transform: uppercase; padding: 11px 28px; border-radius: 8px; border: none; background: var(--gold); color: #1a1200; cursor: pointer; font-weight: 500; }
.btn-save:disabled { opacity: 0.5; cursor: not-allowed; }
.btn-save--muted { opacity: 0.85; }
.btn-discard { font-size: .78rem; letter-spacing: .12em; text-transform: uppercase; padding: 11px 22px; border-radius: 8px; border: 1px solid var(--border-mid); background: none; color: var(--white-faint); cursor: pointer; }

.sec-head { margin-bottom: 22px; }
.sec-title { font-family: var(--font-d); font-size: 1.05rem; letter-spacing: .08em; margin-bottom: 4px; }
.sec-sub { font-size: .78rem; color: var(--white-faint); line-height: 1.6; }

.log-filters { display: flex; gap: 8px; margin-bottom: 20px; flex-wrap: wrap; }
.log-filter { font-size: .72rem; letter-spacing: .1em; text-transform: uppercase; padding: 6px 16px; border-radius: 20px; border: 1px solid var(--border); background: none; color: var(--white-faint); cursor: pointer; }
.log-filter.active { background: var(--gold-dim); border-color: var(--gold); color: var(--gold); }

.log-timeline { position: relative; padding-left: 28px; }
.log-timeline::before { content: ''; position: absolute; left: 7px; top: 8px; bottom: 8px; width: 1px; background: linear-gradient(to bottom, var(--gold), transparent 90%); opacity: .25; }
.log-entry { position: relative; margin-bottom: 22px; }
.log-dot { position: absolute; left: -24px; top: 14px; width: 10px; height: 10px; border-radius: 50%; border: 1px solid var(--gold); background: var(--void); }
.log-card { background: var(--card); border: 1px solid var(--border); border-radius: 10px; overflow: hidden; }
.log-card-head { padding: 12px 18px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap; }
.log-appt-info { display: flex; align-items: center; gap: 10px; flex: 1; min-width: 0; }
.log-raqi-av { width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-family: var(--font-d); font-size: .78rem; flex-shrink: 0; border: 1px solid; }
.log-raqi-name { font-family: var(--font-d); font-size: .82rem; letter-spacing: .05em; }
.log-date { font-size: .7rem; color: var(--white-faint); margin-top: 1px; }
.log-head-right { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }

.log-type-pill--video, .log-type-pill--phone, .log-type-pill--in-person, .log-type-pill--chat {
  font-size: .62rem; letter-spacing: .12em; text-transform: uppercase; padding: 3px 10px; border-radius: 20px; white-space: nowrap;
}
.log-type-pill--video { background: rgba(59,130,246,.12); color: #60a5fa; }
.log-type-pill--phone { background: rgba(26,171,122,0.12); color: #2ecc71; }
.log-type-pill--in-person { background: rgba(240,180,41,0.12); color: var(--gold); }
.log-type-pill--chat { background: rgba(139,92,246,0.12); color: #a78bfa; }

.apt-status { font-size: .62rem; letter-spacing: .08em; text-transform: uppercase; padding: 3px 10px; border-radius: 20px; }
.apt-status--pending { background: rgba(251,191,36,0.12); color: #fbbf24; }
.apt-status--confirmed { background: rgba(59,130,246,.12); color: #60a5fa; }
.apt-status--completed { background: rgba(139,92,246,0.12); color: #a78bfa; }
.apt-status--cancelled, .apt-status--no_show { background: rgba(192,57,43,0.12); color: #f5a8a0; }

.log-card-actions { padding: 10px 18px 14px; }
.link-session { font-size: .8rem; color: #a78bfa; text-decoration: none; }
.link-session:hover { text-decoration: underline; }

.log-empty { text-align: center; padding: 48px 20px; color: var(--white-faint); font-size: .85rem; font-style: italic; }

.security-grid { max-width: 480px; }
.security-row { display: flex; align-items: center; justify-content: space-between; gap: 20px; flex-wrap: wrap; }
.security-row-title { font-size: .88rem; margin-bottom: 4px; }
.security-row-sub { font-size: .75rem; color: var(--white-faint); max-width: 36rem; }

.danger-zone { background: rgba(192,57,43,0.12); border: 1px solid rgba(192,57,43,.3); border-radius: 12px; padding: 22px 24px; margin-top: 8px; }
.dz-title { font-family: var(--font-d); font-size: .95rem; color: #e74c3c; margin-bottom: 8px; }
.dz-desc { font-size: .8rem; color: var(--white-faint); line-height: 1.7; margin-bottom: 16px; }
.dz-btn { font-size: .75rem; letter-spacing: .14em; text-transform: uppercase; padding: 10px 22px; border-radius: 8px; border: 1px solid rgba(192,57,43,.5); background: none; color: #e74c3c; cursor: pointer; }

.toast {
  position: fixed; bottom: 28px; right: 28px; background: var(--card2); border: 1px solid var(--border-mid); border-left: 3px solid var(--gold);
  border-radius: 10px; padding: 14px 20px; font-size: .82rem; color: var(--white-dim); z-index: 999;
  transform: translateY(20px); opacity: 0; transition: all .3s ease; pointer-events: none;
  display: flex; align-items: center; gap: 10px; max-width: 320px;
}
.toast.show { transform: translateY(0); opacity: 1; }

@media (max-width: 760px) {
  .profile-inner { padding: 20px 16px; }
  .form-grid { grid-template-columns: 1fr; }
  .form-group.span2 { grid-column: span 1; }
}
</style>
