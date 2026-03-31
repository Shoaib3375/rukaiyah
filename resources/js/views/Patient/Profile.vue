<template>
  <div class="profile-page">
    <div v-if="pageError" class="profile-alert profile-alert--error">{{ pageError }}</div>

    <div v-else class="profile-inner">
      <!-- Hero -->
      <div class="profile-hero">
        <div class="hero-avatar-wrap">
          <div class="hero-avatar">{{ avatarLetter }}</div>
          <div class="avatar-ring" />
          <button type="button" class="avatar-edit" title="Edit photo" @click="toast('Photo upload coming soon.', '📷')">✎</button>
        </div>
        <div class="hero-info">
          <div class="hero-name">{{ form.full_name || 'Your name' }}</div>
          <div class="hero-email">{{ form.email || '—' }}</div>
          <div class="hero-badges">
            <span class="hbadge hbadge--patient">Patient</span>
            <span v-if="showVerifiedBadge" class="hbadge hbadge--verified">✓ Verified</span>
            <span class="hbadge hbadge--sessions">{{ completedSessionsCount }} Sessions</span>
          </div>
        </div>
        <button type="button" class="hero-save" :disabled="loading" @click="saveAll">Save Changes</button>
      </div>

      <!-- Progress -->
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
          <p class="progress-hint">Complete your Ruqyah profile so Raqis can prepare the right treatment for you.</p>
        </div>
      </div>

      <!-- Tabs -->
      <div class="tab-nav" role="tablist">
        <button type="button" class="tab-btn" :class="{ active: activeTab === 'general' }" @click="activeTab = 'general'">
          <span class="tab-icon">⚙</span> General Settings
        </button>
        <button type="button" class="tab-btn" :class="{ active: activeTab === 'advanced' }" @click="activeTab = 'advanced'">
          <span class="tab-icon">🕌</span> Advanced Ruqyah Settings
        </button>
        <button type="button" class="tab-btn" :class="{ active: activeTab === 'log' }" @click="activeTab = 'log'">
          <span class="tab-icon">📜</span> Session Log
          <span v-if="sessionLogs.length" class="tab-badge">{{ sessionLogs.length }}</span>
        </button>
        <button type="button" class="tab-btn" :class="{ active: activeTab === 'security' }" @click="activeTab = 'security'">
          <span class="tab-icon">🔒</span> Security
        </button>
      </div>

      <!-- General -->
      <div v-show="activeTab === 'general'" class="tab-panel">
        <div class="pcard">
          <div class="pcard-head"><div class="pcard-title">👤 Personal Details</div></div>
          <div class="pcard-body">
            <div class="form-grid">
              <div class="form-group">
                <label class="flabel">Full Name <span class="req">*</span></label>
                <input v-model="form.full_name" class="finput" type="text" placeholder="Your full name">
              </div>
              <div class="form-group">
                <label class="flabel">Display Name</label>
                <input v-model="form.display_name" class="finput" type="text" placeholder="How Raqis will see you">
              </div>
              <div class="form-group">
                <label class="flabel">Email Address <span class="req">*</span></label>
                <input v-model="form.email" class="finput" type="email">
              </div>
              <div class="form-group">
                <label class="flabel">Phone Number</label>
                <input v-model="form.phone" class="finput" type="tel" placeholder="+880...">
              </div>
              <div class="form-group">
                <label class="flabel">Date of Birth</label>
                <input v-model="form.dob" class="finput" type="date">
              </div>
              <div class="form-group">
                <label class="flabel">Gender</label>
                <select v-model="form.gender" class="fselect">
                  <option value="">—</option>
                  <option v-for="g in genderOptions" :key="g" :value="g">{{ g }}</option>
                </select>
              </div>
              <div class="form-group span2">
                <label class="flabel">Address</label>
                <input v-model="form.address" class="finput" type="text" placeholder="Your address">
              </div>
              <div class="form-group span2">
                <label class="flabel">General health notes (optional)</label>
                <textarea v-model="form.medical_notes" class="ftextarea ftextarea--sm" rows="3" placeholder="Anything your Raqi should know about your general health…" />
              </div>
            </div>
          </div>
        </div>

        <div class="pcard">
          <div class="pcard-head"><div class="pcard-title">📞 Emergency Contact</div></div>
          <div class="pcard-body">
            <div class="form-grid">
              <div class="form-group">
                <label class="flabel">Contact Name</label>
                <input v-model="form.emergency_contact" class="finput" type="text" placeholder="e.g. Wife, Father">
              </div>
              <div class="form-group">
                <label class="flabel">Relationship</label>
                <select v-model="form.emergency_contact_relationship" class="fselect">
                  <option value="">—</option>
                  <option v-for="r in relationshipOptions" :key="r" :value="r">{{ r }}</option>
                </select>
              </div>
              <div class="form-group">
                <label class="flabel">Phone Number</label>
                <input v-model="form.emergency_contact_phone" class="finput" type="tel" placeholder="+880...">
              </div>
              <div class="form-group">
                <label class="flabel">Email (optional)</label>
                <input v-model="form.emergency_contact_email" class="finput" type="email" placeholder="emergency@email.com">
              </div>
            </div>
          </div>
        </div>

        <div class="pcard">
          <div class="pcard-head"><div class="pcard-title">🌐 Session Preferences</div></div>
          <div class="pcard-body">
            <div class="form-grid">
              <div class="form-group">
                <label class="flabel">Preferred Session Type</label>
                <select v-model="form.preferred_session_type" class="fselect">
                  <option value="">—</option>
                  <option v-for="s in sessionTypeOptions" :key="s" :value="s">{{ s }}</option>
                </select>
              </div>
              <div class="form-group">
                <label class="flabel">Preferred Language</label>
                <select v-model="form.preferred_language" class="fselect">
                  <option value="">—</option>
                  <option v-for="l in languageOptions" :key="l" :value="l">{{ l }}</option>
                </select>
              </div>
              <div class="form-group">
                <label class="flabel">Raqi Gender Preference</label>
                <select v-model="form.raqi_gender_preference" class="fselect">
                  <option v-for="o in raqiGenderOptions" :key="o" :value="o">{{ o }}</option>
                </select>
              </div>
              <div class="form-group">
                <label class="flabel">Timezone</label>
                <select v-model="form.timezone" class="fselect">
                  <option value="">—</option>
                  <option v-for="z in timezoneOptions" :key="z" :value="z">{{ z }}</option>
                </select>
              </div>
              <div class="form-group span2">
                <label class="flabel">Notification Preferences</label>
                <div class="check-grid check-grid--4">
                  <label class="check-item" :class="{ checked: form.notification_email }" @click.prevent="form.notification_email = !form.notification_email">
                    <span class="check-box">{{ form.notification_email ? '✓' : '' }}</span>
                    Email
                  </label>
                  <label class="check-item" :class="{ checked: form.notification_sms }" @click.prevent="form.notification_sms = !form.notification_sms">
                    <span class="check-box">{{ form.notification_sms ? '✓' : '' }}</span>
                    SMS
                  </label>
                  <label class="check-item" :class="{ checked: form.notification_push }" @click.prevent="form.notification_push = !form.notification_push">
                    <span class="check-box">{{ form.notification_push ? '✓' : '' }}</span>
                    Push
                  </label>
                  <label class="check-item" :class="{ checked: form.notification_reminders }" @click.prevent="form.notification_reminders = !form.notification_reminders">
                    <span class="check-box">{{ form.notification_reminders ? '✓' : '' }}</span>
                    Reminders
                  </label>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="save-row">
          <button type="button" class="btn-discard" @click="discardTab">Discard</button>
          <button type="button" class="btn-save" :disabled="loading" @click="saveAll">Save General Settings</button>
        </div>
      </div>

      <!-- Advanced -->
      <div v-show="activeTab === 'advanced'" class="tab-panel">
        <div class="pcard">
          <div class="pcard-head">
            <div class="pcard-title">🌙 Spiritual Status</div>
            <span class="pcard-hint">Used by Raqis to assess your condition</span>
          </div>
          <div class="pcard-body">
            <div class="form-grid form-grid--1">
              <div class="form-group">
                <label class="flabel">Primary Ailment / Concern <span class="req">*</span></label>
                <select v-model="form.primary_ailment" class="fselect">
                  <option value="">—</option>
                  <option v-for="a in primaryAilmentOptions" :key="a" :value="a">{{ a }}</option>
                </select>
              </div>
              <div class="form-group">
                <label class="flabel">Secondary Concerns (select all that apply)</label>
                <div class="check-grid">
                  <label
                    v-for="item in secondaryConcernOptions"
                    :key="item.key"
                    class="check-item"
                    :class="{ checked: secondaryConcernsSet.has(item.key) }"
                    @click.prevent="toggleConcern(item.key)"
                  >
                    <span class="check-box">{{ secondaryConcernsSet.has(item.key) ? '✓' : '' }}</span>
                    {{ item.label }}
                  </label>
                </div>
              </div>
              <div class="form-group duration-radios">
                <label class="flabel">How long have you been experiencing this?</label>
                <div class="radio-group">
                  <label
                    v-for="d in durationOptions"
                    :key="d"
                    class="radio-pill"
                    :class="{ selected: form.symptom_duration === d }"
                    @click.prevent="form.symptom_duration = d"
                  >
                    <input v-model="form.symptom_duration" type="radio" :value="d" class="sr-only">
                    {{ d }}
                  </label>
                </div>
              </div>
              <div class="form-group">
                <label class="flabel">Symptom Intensity</label>
                <div class="slider-wrap">
                  <span class="slider-cap">Mild</span>
                  <input v-model.number="form.symptom_intensity" class="fslider" type="range" min="1" max="10">
                  <span class="slider-cap">Severe</span>
                  <div class="slider-val">{{ form.symptom_intensity || '—' }}</div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="pcard">
          <div class="pcard-head"><div class="pcard-title">📝 Detailed Description</div></div>
          <div class="pcard-body">
            <div class="form-grid form-grid--1">
              <div class="form-group">
                <label class="flabel">Describe your symptoms in detail <span class="req">*</span></label>
                <textarea v-model="form.symptom_description" class="ftextarea" rows="5" placeholder="Describe what you experience physically, emotionally and spiritually." />
                <p class="fhint">This is shared with your assigned Raqi before the session. Be as specific as possible.</p>
              </div>
              <div class="form-group">
                <label class="flabel">When do symptoms intensify?</label>
                <div class="check-grid">
                  <label
                    v-for="item in symptomTriggerOptions"
                    :key="item.key"
                    class="check-item"
                    :class="{ checked: symptomTriggersSet.has(item.key) }"
                    @click.prevent="toggleTrigger(item.key)"
                  >
                    <span class="check-box">{{ symptomTriggersSet.has(item.key) ? '✓' : '' }}</span>
                    {{ item.label }}
                  </label>
                </div>
              </div>
              <div class="form-group">
                <label class="flabel">Do you hear voices or see shapes?</label>
                <div class="radio-group">
                  <label
                    v-for="h in hallucinationOptions"
                    :key="h"
                    class="radio-pill"
                    :class="{ selected: form.hallucinations === h }"
                    @click.prevent="form.hallucinations = h"
                  >
                    <input v-model="form.hallucinations" type="radio" :value="h" class="sr-only">
                    {{ h }}
                  </label>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="pcard">
          <div class="pcard-head"><div class="pcard-title">🏥 Medical & Religious Background</div></div>
          <div class="pcard-body">
            <div class="form-grid">
              <div class="form-group span2">
                <label class="flabel">Have you had a medical checkup?</label>
                <div class="radio-group">
                  <label
                    v-for="m in medicalCheckupOptions"
                    :key="m"
                    class="radio-pill"
                    :class="{ selected: form.medical_checkup === m }"
                    @click.prevent="form.medical_checkup = m"
                  >
                    <input v-model="form.medical_checkup" type="radio" :value="m" class="sr-only">
                    {{ m }}
                  </label>
                </div>
              </div>
              <div class="form-group span2">
                <label class="flabel">Have you had Ruqyah before?</label>
                <div class="radio-group">
                  <label
                    class="radio-pill"
                    :class="{ selected: form.previous_ruqyah === true }"
                    @click.prevent="form.previous_ruqyah = true"
                  >
                    <input v-model="form.previous_ruqyah" type="radio" :value="true" class="sr-only">
                    Yes
                  </label>
                  <label
                    class="radio-pill"
                    :class="{ selected: form.previous_ruqyah === false }"
                    @click.prevent="form.previous_ruqyah = false"
                  >
                    <input v-model="form.previous_ruqyah" type="radio" :value="false" class="sr-only">
                    No
                  </label>
                </div>
              </div>
              <div class="form-group">
                <label class="flabel">Current medications (if any)</label>
                <input v-model="form.current_medications" class="finput" type="text" placeholder="List any current medications…">
              </div>
              <div class="form-group">
                <label class="flabel">Madhhab (Islamic school of thought)</label>
                <select v-model="form.madhhab" class="fselect">
                  <option value="">—</option>
                  <option v-for="mad in madhhabOptions" :key="mad" :value="mad">{{ mad }}</option>
                </select>
              </div>
              <div class="form-group span2">
                <label class="flabel">Previous Ruqyah experience / what was done</label>
                <textarea v-model="form.previous_ruqyah_notes" class="ftextarea ftextarea--sm" rows="3" placeholder="Describe any previous Ruqyah sessions…" />
              </div>
              <div class="form-group span2">
                <label class="flabel">Additional notes for Raqi (private)</label>
                <textarea v-model="form.additional_notes" class="ftextarea ftextarea--sm" rows="3" placeholder="Any other information…" />
              </div>
            </div>
          </div>
        </div>

        <div class="pcard">
          <div class="pcard-head"><div class="pcard-title">✅ Consent & Acknowledgements</div></div>
          <div class="pcard-body">
            <div class="consent-stack">
              <label class="check-item check-item--block" :class="{ checked: form.consent_ruqyah_shariah }" @click.prevent="form.consent_ruqyah_shariah = !form.consent_ruqyah_shariah">
                <span class="check-box check-box--top">{{ form.consent_ruqyah_shariah ? '✓' : '' }}</span>
                <span class="consent-text">I confirm that I am seeking Ruqyah Shariah based on the Quran and authentic Sunnah only. I do not consent to any practices outside of Islamic guidelines.</span>
              </label>
              <label class="check-item check-item--block" :class="{ checked: form.consent_medical_disclaimer }" @click.prevent="form.consent_medical_disclaimer = !form.consent_medical_disclaimer">
                <span class="check-box check-box--top">{{ form.consent_medical_disclaimer ? '✓' : '' }}</span>
                <span class="consent-text">I understand that Ruqyah is a spiritual treatment and is not a substitute for professional medical advice.</span>
              </label>
              <label class="check-item check-item--block" :class="{ checked: form.consent_data_storage }" @click.prevent="form.consent_data_storage = !form.consent_data_storage">
                <span class="check-box check-box--top">{{ form.consent_data_storage ? '✓' : '' }}</span>
                <span class="consent-text">I consent for my session notes to be stored on Jetty's platform for continuity of care between sessions.</span>
              </label>
            </div>
          </div>
        </div>

        <div class="save-row">
          <button type="button" class="btn-discard" @click="discardTab">Discard</button>
          <button type="button" class="btn-save" :disabled="loading" @click="saveAll">Save Ruqyah Profile</button>
        </div>
      </div>

      <!-- Session log -->
      <div v-show="activeTab === 'log'" class="tab-panel">
        <div class="sec-head">
          <div class="sec-title">Your Session History</div>
          <div class="sec-sub">All Ruqyah notes and observations written by your Raqis are stored here for your reference.</div>
        </div>
        <div class="log-filters">
          <button type="button" class="log-filter" :class="{ active: logFilter === 'all' }" @click="logFilter = 'all'">All Sessions</button>
          <button type="button" class="log-filter" :class="{ active: logFilter === 'completed' }" @click="logFilter = 'completed'">Completed</button>
          <button type="button" class="log-filter" :class="{ active: logFilter === 'ruqyah_log' }" @click="logFilter = 'ruqyah_log'">Ruqyah Logs</button>
          <button type="button" class="log-filter" :class="{ active: logFilter === 'recommendation' }" @click="logFilter = 'recommendation'">Recommendations</button>
        </div>
        <div v-if="logsLoading" class="log-empty">Loading session history…</div>
        <div v-else-if="!filteredLogEntries.length" class="log-empty">No sessions with notes yet. After your Raqi adds notes, they will appear here.</div>
        <div v-else class="log-timeline">
          <div
            v-for="apt in filteredLogEntries"
            :key="apt.id"
            class="log-entry"
          >
            <div class="log-dot" />
            <div class="log-card">
              <div class="log-card-head">
                <div class="log-appt-info">
                  <div class="log-raqi-av" :style="raqiAvatarStyle(apt)">{{ raqiInitial(apt) }}</div>
                  <div>
                    <div class="log-raqi-name">{{ apt.lead_raqi?.user?.full_name || 'Raqi' }}</div>
                    <div class="log-date">{{ formatDateTime(apt.scheduled_at) }}</div>
                  </div>
                </div>
                <span class="log-type-pill" :class="sessionTypeClass(apt.session_type)">{{ sessionTypeLabel(apt.session_type) }}</span>
              </div>
              <div class="log-notes">
                <div
                  v-for="note in apt.notes || []"
                  :key="note.id"
                  v-show="logFilter === 'all' || logFilter === 'completed' || noteFilterMatch(note)"
                  class="log-note-item"
                  :class="'log-note-item--' + String(note.note_type || '').replace(/_/g, '-')"
                >
                  <div class="log-note-tag" :class="'log-note-tag--' + (note.note_type || '')">{{ noteTypeLabel(note.note_type) }}</div>
                  <div class="log-note-text">{{ note.content }}</div>
                  <div class="log-note-by">— {{ note.raqi?.user?.full_name || 'Raqi' }} · {{ formatDateTime(note.created_at) }}</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Security -->
      <div v-show="activeTab === 'security'" class="tab-panel">
        <div class="pcard">
          <div class="pcard-head"><div class="pcard-title">🔑 Change Password</div></div>
          <div class="pcard-body">
            <div v-if="passwordMessage" class="profile-alert" :class="passwordMessage.type === 'success' ? 'profile-alert--ok' : 'profile-alert--error'">{{ passwordMessage.text }}</div>
            <div class="form-grid form-grid--1 security-grid">
              <div class="form-group">
                <label class="flabel">Current Password</label>
                <input v-model="passwordForm.current_password" class="finput" type="password" autocomplete="current-password">
              </div>
              <div class="form-group">
                <label class="flabel">New Password</label>
                <input v-model="passwordForm.password" class="finput" type="password" autocomplete="new-password" placeholder="Min 8 characters">
              </div>
              <div class="form-group">
                <label class="flabel">Confirm New Password</label>
                <input v-model="passwordForm.password_confirmation" class="finput" type="password" autocomplete="new-password">
              </div>
            </div>
            <div class="save-row save-row--left">
              <button type="button" class="btn-save" :disabled="passwordLoading" @click="savePassword">{{ passwordLoading ? 'Updating…' : 'Update Password' }}</button>
            </div>
          </div>
        </div>

        <div class="pcard">
          <div class="pcard-head"><div class="pcard-title">📱 Two-Factor Authentication</div></div>
          <div class="pcard-body">
            <div class="security-row">
              <div>
                <div class="security-row-title">SMS Authentication</div>
                <div class="security-row-sub">Two-factor authentication is not enabled yet. We will notify you when this feature is available.</div>
              </div>
              <button type="button" class="btn-save btn-save--muted" @click="toast('2FA coming soon.', '📱')">Coming soon</button>
            </div>
          </div>
        </div>

        <div class="pcard">
          <div class="pcard-head"><div class="pcard-title">🖥 Active Sessions</div></div>
          <div class="pcard-body">
            <p class="fhint">Session management for other devices is not available yet. Change your password above if you suspect unauthorized access.</p>
          </div>
        </div>

        <div class="danger-zone">
          <div class="dz-title">⚠ Danger Zone</div>
          <div class="dz-desc">Permanently delete your account and all associated data including session logs, profile information and appointment history. This action cannot be undone.</div>
          <button type="button" class="dz-btn" @click="toast('Please contact support to delete your account.', '⚠')">Delete My Account</button>
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
import { patientAPI } from '../../api';
import { formatDateTime, unwrap } from '../../utils';

const loading = ref(false);
const logsLoading = ref(true);
const pageError = ref(null);
const activeTab = ref('general');
const logFilter = ref('all');
const sessionLogs = ref([]);
const toastVisible = ref(false);
const toastMsg = ref('');
const toastIcon = ref('✓');
let toastTimer;

const genderOptions = ['Male', 'Female', 'Prefer not to say'];
const relationshipOptions = ['Spouse', 'Parent', 'Sibling', 'Friend', 'Other'];
const sessionTypeOptions = ['Online Video', 'Phone Call', 'In Person', 'Online Chat'];
const languageOptions = ['Bengali', 'Arabic', 'English', 'Urdu'];
const raqiGenderOptions = ['No Preference', 'Male Raqi Only', 'Female Raqi Only'];
const timezoneOptions = ['Asia/Dhaka (UTC+6)', 'Asia/Dubai (UTC+4)', 'Europe/London (UTC+0)'];
const primaryAilmentOptions = [
  'Black Magic (Sihr)', 'Evil Eye (Nazar)', 'Jinn Possession', 'Waswas (Obsessive Thoughts)',
  'Marriage Blockage', 'Spiritual Protection', 'General Healing', 'Other',
];
const secondaryConcernOptions = [
  { key: 'sleep_disturbances', label: 'Sleep disturbances / nightmares' },
  { key: 'chest_tightness', label: 'Chest tightness / heaviness' },
  { key: 'prayer_difficulty', label: 'Difficulty in prayer / Quran' },
  { key: 'headaches', label: 'Frequent headaches' },
  { key: 'body_pain', label: 'Unexplained body pain' },
  { key: 'anxiety', label: 'Anxiety / mental fog' },
  { key: 'relationship', label: 'Relationship / marriage issues' },
  { key: 'financial', label: 'Financial / career blockages' },
];
const durationOptions = ['Less than 1 month', '1–3 months', '3–6 months', '6–12 months', 'More than 1 year'];
const symptomTriggerOptions = [
  { key: 'during_prayer', label: 'During / after prayer' },
  { key: 'night', label: 'At night / before sleep' },
  { key: 'morning', label: 'In the morning' },
  { key: 'quran', label: 'When reading Quran' },
  { key: 'locations', label: 'In specific locations' },
  { key: 'people', label: 'Around certain people' },
];
const hallucinationOptions = ['Never', 'Occasionally', 'Frequently'];
const medicalCheckupOptions = ['Yes — no issues found', 'Yes — condition found', 'Not yet'];
const madhhabOptions = ["Hanafi", "Shafi'i", 'Maliki', 'Hanbali', 'Prefer not to say'];

const form = reactive(emptyForm());

function emptyForm() {
  return {
    full_name: '',
    email: '',
    phone: '',
    display_name: '',
    dob: '',
    gender: '',
    address: '',
    medical_notes: '',
    emergency_contact: '',
    emergency_contact_relationship: '',
    emergency_contact_phone: '',
    emergency_contact_email: '',
    preferred_session_type: '',
    preferred_language: '',
    raqi_gender_preference: 'No Preference',
    timezone: '',
    notification_email: true,
    notification_sms: true,
    notification_push: false,
    notification_reminders: true,
    primary_ailment: '',
    secondary_concerns: [],
    symptom_duration: '',
    symptom_intensity: null,
    symptom_description: '',
    symptom_triggers: [],
    hallucinations: '',
    medical_checkup: '',
    previous_ruqyah: null,
    current_medications: '',
    madhhab: '',
    previous_ruqyah_notes: '',
    additional_notes: '',
    consent_ruqyah_shariah: false,
    consent_medical_disclaimer: false,
    consent_data_storage: false,
    is_verified: false,
    email_verified_at: null,
    completed_sessions_count: 0,
  };
}

let snapshot = null;

const secondaryConcernsSet = computed(() => new Set(form.secondary_concerns || []));
const symptomTriggersSet = computed(() => new Set(form.symptom_triggers || []));

const avatarLetter = computed(() => (form.full_name || '?').trim().charAt(0).toUpperCase() || '?');

const showVerifiedBadge = computed(() => !!(form.is_verified || form.email_verified_at));

const completedSessionsCount = computed(() => form.completed_sessions_count ?? 0);

const profileProgress = computed(() => {
  const checks = [
    () => form.full_name?.trim(),
    () => form.email?.trim(),
    () => form.phone?.trim(),
    () => form.address?.trim(),
    () => form.emergency_contact?.trim(),
    () => form.display_name?.trim(),
    () => form.dob,
    () => form.gender,
    () => form.primary_ailment,
    () => (form.symptom_description || '').trim().length > 24,
    () => form.consent_ruqyah_shariah,
    () => form.consent_medical_disclaimer,
  ];
  const n = checks.filter((c) => c()).length;
  return Math.min(100, Math.round((n / checks.length) * 100));
});

const filteredLogEntries = computed(() => {
  const list = sessionLogs.value || [];
  if (logFilter.value === 'all' || logFilter.value === 'completed') {
    return list;
  }
  return list.filter((apt) => (apt.notes || []).some((note) => note.note_type === logFilter.value));
});

function noteFilterMatch(note) {
  if (logFilter.value === 'all' || logFilter.value === 'completed') return true;
  return note.note_type === logFilter.value;
}

function toggleConcern(key) {
  const arr = form.secondary_concerns;
  const i = arr.indexOf(key);
  if (i >= 0) arr.splice(i, 1);
  else arr.push(key);
}

function toggleTrigger(key) {
  const arr = form.symptom_triggers;
  const i = arr.indexOf(key);
  if (i >= 0) arr.splice(i, 1);
  else arr.push(key);
}

function applyProfilePayload(data) {
  Object.assign(form, emptyForm());
  if (!data) return;
  const bool = (v) => v === true || v === 1 || v === '1';
  form.full_name = data.full_name || '';
  form.email = data.email || '';
  form.phone = data.phone || '';
  form.display_name = data.display_name || '';
  form.dob = data.dob ? String(data.dob).slice(0, 10) : '';
  form.gender = data.gender || '';
  form.address = data.address || '';
  form.medical_notes = data.medical_notes || '';
  form.emergency_contact = data.emergency_contact || '';
  form.emergency_contact_relationship = data.emergency_contact_relationship || '';
  form.emergency_contact_phone = data.emergency_contact_phone || '';
  form.emergency_contact_email = data.emergency_contact_email || '';
  form.preferred_session_type = data.preferred_session_type || '';
  form.preferred_language = data.preferred_language || '';
  form.raqi_gender_preference = data.raqi_gender_preference || 'No Preference';
  form.timezone = data.timezone || '';
  form.notification_email = bool(data.notification_email);
  form.notification_sms = bool(data.notification_sms);
  form.notification_push = bool(data.notification_push);
  form.notification_reminders = bool(data.notification_reminders);
  form.primary_ailment = data.primary_ailment || '';
  form.secondary_concerns = Array.isArray(data.secondary_concerns) ? [...data.secondary_concerns] : [];
  form.symptom_duration = data.symptom_duration || '';
  form.symptom_intensity = data.symptom_intensity != null ? Number(data.symptom_intensity) : null;
  form.symptom_description = data.symptom_description || '';
  form.symptom_triggers = Array.isArray(data.symptom_triggers) ? [...data.symptom_triggers] : [];
  form.hallucinations = data.hallucinations || '';
  form.medical_checkup = data.medical_checkup || '';
  form.previous_ruqyah = data.previous_ruqyah === null || data.previous_ruqyah === undefined ? null : bool(data.previous_ruqyah);
  form.current_medications = data.current_medications || '';
  form.madhhab = data.madhhab || '';
  form.previous_ruqyah_notes = data.previous_ruqyah_notes || '';
  form.additional_notes = data.additional_notes || '';
  form.consent_ruqyah_shariah = bool(data.consent_ruqyah_shariah);
  form.consent_medical_disclaimer = bool(data.consent_medical_disclaimer);
  form.consent_data_storage = bool(data.consent_data_storage);
  form.is_verified = bool(data.is_verified);
  form.email_verified_at = data.email_verified_at || null;
  form.completed_sessions_count = data.completed_sessions_count ?? 0;
}

function buildUpdatePayload() {
  return {
    full_name: form.full_name,
    email: form.email,
    phone: form.phone || null,
    address: form.address || null,
    medical_notes: form.medical_notes || null,
    emergency_contact: form.emergency_contact || null,
    emergency_contact_relationship: form.emergency_contact_relationship || null,
    emergency_contact_phone: form.emergency_contact_phone || null,
    emergency_contact_email: form.emergency_contact_email || null,
    display_name: form.display_name || null,
    dob: form.dob || null,
    gender: form.gender || null,
    preferred_session_type: form.preferred_session_type || null,
    preferred_language: form.preferred_language || null,
    raqi_gender_preference: form.raqi_gender_preference || null,
    timezone: form.timezone || null,
    notification_email: form.notification_email,
    notification_sms: form.notification_sms,
    notification_push: form.notification_push,
    notification_reminders: form.notification_reminders,
    primary_ailment: form.primary_ailment || null,
    secondary_concerns: form.secondary_concerns,
    symptom_duration: form.symptom_duration || null,
    symptom_intensity: form.symptom_intensity,
    symptom_description: form.symptom_description || null,
    symptom_triggers: form.symptom_triggers,
    hallucinations: form.hallucinations || null,
    medical_checkup: form.medical_checkup || null,
    previous_ruqyah: form.previous_ruqyah,
    current_medications: form.current_medications || null,
    madhhab: form.madhhab || null,
    previous_ruqyah_notes: form.previous_ruqyah_notes || null,
    additional_notes: form.additional_notes || null,
    consent_ruqyah_shariah: form.consent_ruqyah_shariah,
    consent_medical_disclaimer: form.consent_medical_disclaimer,
    consent_data_storage: form.consent_data_storage,
  };
}

function saveSnapshot() {
  snapshot = JSON.parse(JSON.stringify(form));
}

function discardTab() {
  if (snapshot) applyProfilePayload(snapshot);
  toast('Changes discarded.', '↩');
}

async function saveAll() {
  loading.value = true;
  try {
    const res = await patientAPI.profile.update(buildUpdatePayload());
    const data = res.data?.data;
    applyProfilePayload(data);
    saveSnapshot();
    toast(res.data?.message || 'Profile saved successfully!', '✓');
  } catch (e) {
    const msg = e.response?.data?.message || 'Failed to save profile.';
    toast(msg, '✕');
  } finally {
    loading.value = false;
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
    const res = await patientAPI.profile.updatePassword({ ...passwordForm });
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

const SESSION_TYPE_MAP = {
  video: 'Online Video',
  phone: 'Phone Call',
  chat: 'Online Chat',
  in_person: 'In Person',
};

function sessionTypeLabel(raw) {
  if (!raw) return 'Session';
  const k = typeof raw === 'object' && raw?.value != null ? raw.value : raw;
  return SESSION_TYPE_MAP[k] || String(k).replace(/_/g, ' ');
}

function sessionTypeClass(raw) {
  const k = typeof raw === 'object' && raw?.value != null ? raw.value : raw;
  return 'log-type-pill--' + String(k || '').replace(/_/g, '-');
}

function noteTypeLabel(type) {
  const map = {
    ruqyah_log: 'Ruqyah Log',
    observation: 'Observation',
    recommendation: 'Recommendation',
    chat: 'Chat',
  };
  return map[type] || String(type || '').replace(/_/g, ' ');
}

function raqiInitial(apt) {
  const name = apt.lead_raqi?.user?.full_name || 'R';
  return name.trim().charAt(0).toUpperCase();
}

function raqiAvatarStyle(apt) {
  const palette = [
    ['rgba(124,92,191,.15)', 'rgba(124,92,191,.3)', '#a78bfa'],
    ['rgba(41,128,185,.15)', 'rgba(41,128,185,.3)', '#5dade2'],
    ['rgba(26,171,122,.12)', 'rgba(26,171,122,.3)', '#2ecc71'],
  ];
  const idx = (apt.id || '').charCodeAt(0) % palette.length;
  const [bg, border, color] = palette[idx];
  return { background: bg, borderColor: border, color };
}

onMounted(async () => {
  pageError.value = null;
  try {
    const [profileRes, logsRes] = await Promise.all([
      patientAPI.profile.get(),
      patientAPI.profile.sessionLogs(),
    ]);
    applyProfilePayload(profileRes.data?.data);
    saveSnapshot();
    sessionLogs.value = unwrap(logsRes) || [];
  } catch (e) {
    pageError.value = e.response?.data?.message || 'Failed to load profile.';
  } finally {
    logsLoading.value = false;
  }
});

watch(activeTab, (tab) => {
  if (tab === 'log' && !sessionLogs.value.length && !logsLoading.value) {
    logsLoading.value = true;
    patientAPI.profile.sessionLogs()
      .then((r) => { sessionLogs.value = unwrap(r) || []; })
      .catch(() => {})
      .finally(() => { logsLoading.value = false; });
  }
});
</script>

<style scoped>
.sr-only { position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px; overflow: hidden; clip: rect(0,0,0,0); border: 0; }

.profile-page {
  --void: #08060f;
  --deep: #0d0b1c;
  --card: #121024;
  --card2: #161330;
  --card3: #1b1840;
  --border: rgba(201,168,76,0.12);
  --border-mid: rgba(201,168,76,0.26);
  --gold: #f0b429;
  --gold-light: #ffd166;
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

.profile-hero {
  display: flex; align-items: center; gap: 28px; margin-bottom: 36px; flex-wrap: wrap;
}
.hero-avatar-wrap { position: relative; flex-shrink: 0; }
.hero-avatar {
  width: 84px; height: 84px; border-radius: 50%;
  background: linear-gradient(135deg, rgba(139,92,246,.25), rgba(240,180,41,.18));
  border: 2px solid var(--border-mid);
  display: flex; align-items: center; justify-content: center;
  font-family: var(--font-d); font-size: 2rem; color: var(--gold);
}
.avatar-ring {
  position: absolute; inset: -5px; border-radius: 50%;
  border: 1px solid rgba(240,180,41,.2); pointer-events: none;
}
.avatar-edit {
  position: absolute; bottom: 2px; right: 2px;
  width: 26px; height: 26px; border-radius: 50%;
  background: var(--gold); color: #1a1200;
  border: 2px solid var(--void);
  display: flex; align-items: center; justify-content: center;
  font-size: .65rem; cursor: pointer;
}
.hero-info { flex: 1; min-width: 200px; }
.hero-name { font-family: var(--font-d); font-size: 1.5rem; letter-spacing: .07em; margin-bottom: 4px; }
.hero-email { font-size: .82rem; color: var(--white-faint); margin-bottom: 10px; }
.hero-badges { display: flex; gap: 8px; flex-wrap: wrap; }
.hbadge {
  font-size: .65rem; letter-spacing: .16em; text-transform: uppercase;
  padding: 4px 12px; border-radius: 20px;
}
.hbadge--patient { background: rgba(26,171,122,0.12); color: #2ecc71; border: 1px solid rgba(26,171,122,.25); }
.hbadge--verified { background: var(--gold-dim); color: var(--gold); border: 1px solid rgba(240,180,41,.25); }
.hbadge--sessions { background: rgba(139,92,246,0.12); color: #a78bfa; border: 1px solid rgba(139,92,246,.25); }

.hero-save {
  margin-left: auto; font-size: .78rem; letter-spacing: .14em; text-transform: uppercase;
  background: var(--gold); color: #1a1200;
  border: none; padding: 11px 26px; border-radius: 8px;
  cursor: pointer; font-weight: 500;
}
.hero-save:disabled { opacity: 0.5; cursor: not-allowed; }

.pcard { background: var(--card); border: 1px solid var(--border); border-radius: 12px; margin-bottom: 24px; overflow: hidden; }
.pcard--progress { margin-bottom: 28px; }
.pcard-head { padding: 15px 22px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap; }
.pcard-title { font-size: .67rem; letter-spacing: .26em; text-transform: uppercase; color: var(--gold); }
.pcard-hint { font-size: .7rem; color: var(--white-faint); }
.pcard-body { padding: 24px; }
.pcard-body--tight { padding: 18px 22px; }

.progress-label { display: flex; justify-content: space-between; font-size: .68rem; color: var(--white-faint); margin-bottom: 6px; }
.progress-bar { height: 4px; background: var(--card3); border-radius: 2px; overflow: hidden; }
.progress-fill { height: 100%; background: var(--gold); border-radius: 2px; transition: width .4s ease; }
.progress-hint { font-size: .72rem; color: var(--white-faint); margin-top: 8px; line-height: 1.5; }

.tab-nav { display: flex; flex-wrap: wrap; gap: 0; border-bottom: 1px solid var(--border); margin-bottom: 32px; }
.tab-btn {
  display: flex; align-items: center; gap: 8px;
  font-size: .82rem; padding: 12px 22px;
  color: var(--white-faint); background: none;
  border: none; border-bottom: 2px solid transparent;
  cursor: pointer; letter-spacing: .04em; margin-bottom: -1px;
}
.tab-btn:hover { color: var(--white-dim); }
.tab-btn.active { color: var(--gold); border-bottom-color: var(--gold); }
.tab-badge { font-size: .6rem; background: var(--gold-dim); color: var(--gold); border-radius: 20px; padding: 1px 7px; }

.form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 18px; }
.form-grid--1 { grid-template-columns: 1fr; }
.form-group { display: flex; flex-direction: column; gap: 7px; }
.form-group.span2 { grid-column: span 2; }
.flabel { font-size: .67rem; letter-spacing: .18em; text-transform: uppercase; color: var(--white-faint); }
.req { color: #e74c3c; margin-left: 2px; }
.finput, .fselect, .ftextarea {
  background: var(--card2); border: 1px solid var(--border);
  border-radius: 8px; padding: 11px 14px; font-size: .85rem; color: var(--white); outline: none; width: 100%;
}
.finput:focus, .fselect:focus, .ftextarea:focus { border-color: var(--border-mid); box-shadow: 0 0 0 3px rgba(240,180,41,.08); }
.ftextarea { resize: vertical; min-height: 90px; font-family: var(--font-b); font-size: .95rem; line-height: 1.7; }
.ftextarea--sm { min-height: 72px; }
.fhint { font-size: .68rem; color: var(--white-faint); line-height: 1.5; margin-top: 4px; }

.radio-group { display:  flex; gap: 10px; flex-wrap: wrap; }
.radio-pill {
  display: flex; align-items: center; gap: 7px;
  padding: 8px 16px; border-radius: 8px;
  border: 1px solid var(--border); background: var(--card2);
  cursor: pointer; font-size: .8rem; color: var(--white-dim); user-select: none;
}
.radio-pill.selected { border-color: var(--gold); background: var(--gold-dim); color: var(--gold); }

.check-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
.check-grid--4 { grid-template-columns: repeat(4, 1fr); }
.check-item {
  display: flex; align-items: center; gap: 10px;
  padding: 10px 14px; border-radius: 8px;
  border: 1px solid var(--border); background: var(--card2);
  cursor: pointer; font-size: .82rem; color: var(--white-dim); user-select: none;
}
.check-item.checked { border-color: var(--gold); background: var(--gold-dim); color: var(--white); }
.check-item--block { align-items: flex-start; padding: 14px; }
.check-box {
  width: 16px; height: 16px; border-radius: 4px;
  border: 1px solid var(--border-mid); flex-shrink: 0;
  display: flex; align-items: center; justify-content: center;
  font-size: .7rem; color: #1a1200;
}
.check-box--top { margin-top: 2px; }
.check-item.checked .check-box { background: var(--gold); border-color: var(--gold); }
.consent-text { line-height: 1.6; font-size: .82rem; }
.consent-stack { display: flex; flex-direction: column; gap: 12px; }

.slider-wrap { display: flex; align-items: center; gap: 14px; flex-wrap: wrap; }
.slider-cap { font-size: .72rem; color: var(--white-faint); }
.fslider { flex: 1; min-width: 120px; accent-color: var(--gold); height: 4px; }
.slider-val { font-family: var(--font-d); font-size: .9rem; color: var(--gold); min-width: 24px; text-align: center; }

.save-row { display: flex; justify-content: flex-end; gap: 12px; margin-top: 24px; padding-top: 24px; border-top: 1px solid var(--border); flex-wrap: wrap; }
.save-row--left { justify-content: flex-start; }
.btn-save {
  font-size: .78rem; letter-spacing: .14em; text-transform: uppercase;
  padding: 11px 28px; border-radius: 8px; border: none;
  background: var(--gold); color: #1a1200; cursor: pointer; font-weight: 500;
}
.btn-save:disabled { opacity: 0.5; cursor: not-allowed; }
.btn-save--muted { opacity: 0.85; }
.btn-discard {
  font-size: .78rem; letter-spacing: .12em; text-transform: uppercase;
  padding: 11px 22px; border-radius: 8px;
  border: 1px solid var(--border-mid); background: none; color: var(--white-faint); cursor: pointer;
}

.sec-head { margin-bottom: 22px; }
.sec-title { font-family: var(--font-d); font-size: 1.05rem; letter-spacing: .08em; margin-bottom: 4px; }
.sec-sub { font-size: .78rem; color: var(--white-faint); line-height: 1.6; }

.log-filters { display: flex; gap: 8px; margin-bottom: 20px; flex-wrap: wrap; }
.log-filter {
  font-size: .72rem; letter-spacing: .1em; text-transform: uppercase;
  padding: 6px 16px; border-radius: 20px;
  border: 1px solid var(--border); background: none; color: var(--white-faint); cursor: pointer;
}
.log-filter.active { background: var(--gold-dim); border-color: var(--gold); color: var(--gold); }

.log-timeline { position: relative; padding-left: 28px; }
.log-timeline::before {
  content: ''; position: absolute; left: 7px; top: 8px; bottom: 8px; width: 1px;
  background: linear-gradient(to bottom, var(--gold), rgba(240,180,41,0) 90%); opacity: .25;
}
.log-entry { position: relative; margin-bottom: 22px; }
.log-dot {
  position: absolute; left: -24px; top: 14px;
  width: 10px; height: 10px; border-radius: 50%;
  border: 1px solid var(--gold); background: var(--void);
}
.log-card { background: var(--card); border: 1px solid var(--border); border-radius: 10px; overflow: hidden; }
.log-card-head {
  padding: 12px 18px; border-bottom: 1px solid var(--border);
  display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap;
}
.log-appt-info { display: flex; align-items: center; gap: 10px; flex: 1; min-width: 0; }
.log-raqi-av {
  width: 32px; height: 32px; border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  font-family: var(--font-d); font-size: .78rem; flex-shrink: 0; border: 1px solid;
}
.log-raqi-name { font-family: var(--font-d); font-size: .82rem; letter-spacing: .05em; }
.log-date { font-size: .7rem; color: var(--white-faint); margin-top: 1px; }

.log-type-pill--video, .log-type-pill--phone, .log-type-pill--in-person, .log-type-pill--chat {
  font-size: .62rem; letter-spacing: .12em; text-transform: uppercase;
  padding: 3px 10px; border-radius: 20px; white-space: nowrap;
}
.log-type-pill--video { background: rgba(59,130,246,.12); color: #60a5fa; }
.log-type-pill--phone { background: rgba(26,171,122,0.12); color: #2ecc71; }
.log-type-pill--in-person { background: rgba(240,180,41,0.12); color: var(--gold); }
.log-type-pill--chat { background: rgba(139,92,246,0.12); color: #a78bfa; }

.log-notes { padding: 14px 18px; display: flex; flex-direction: column; gap: 12px; }
.log-note-item {
  padding: 10px 14px; background: var(--card2); border-radius: 8px; border-left: 2px solid var(--border-mid);
}
.log-note-item--ruqyah-log { border-left-color: var(--gold); }
.log-note-item--observation { border-left-color: var(--teal); }
.log-note-item--recommendation { border-left-color: var(--purple); }
.log-note-item--chat { border-left-color: rgba(96,165,250,0.8); }

.log-note-tag { font-size: .62rem; letter-spacing: .16em; text-transform: uppercase; margin-bottom: 5px; }
.log-note-tag--ruqyah_log { color: var(--gold); }
.log-note-tag--observation { color: #2ecc71; }
.log-note-tag--recommendation { color: #a78bfa; }
.log-note-tag--chat { color: #60a5fa; }

.log-note-text { font-family: var(--font-b); font-size: .95rem; font-style: italic; color: var(--white-dim); line-height: 1.75; }
.log-note-by { font-size: .68rem; color: var(--white-faint); margin-top: 4px; }

.log-empty { text-align: center; padding: 48px 20px; color: var(--white-faint); font-size: .85rem; font-style: italic; }

.security-grid { max-width: 480px; }
.security-row { display: flex; align-items: center; justify-content: space-between; gap: 20px; flex-wrap: wrap; }
.security-row-title { font-size: .88rem; margin-bottom: 4px; }
.security-row-sub { font-size: .75rem; color: var(--white-faint); max-width: 36rem; }

.danger-zone {
  background: rgba(192,57,43,0.12); border: 1px solid rgba(192,57,43,.3);
  border-radius: 12px; padding: 22px 24px; margin-top: 8px;
}
.dz-title { font-family: var(--font-d); font-size: .95rem; letter-spacing: .06em; color: #e74c3c; margin-bottom: 8px; }
.dz-desc { font-size: .8rem; color: var(--white-faint); line-height: 1.7; margin-bottom: 16px; }
.dz-btn {
  font-size: .75rem; letter-spacing: .14em; text-transform: uppercase;
  padding: 10px 22px; border-radius: 8px;
  border: 1px solid rgba(192,57,43,.5); background: none; color: #e74c3c; cursor: pointer;
}

.toast {
  position: fixed; bottom: 28px; right: 28px; background: var(--card2);
  border: 1px solid var(--border-mid); border-left: 3px solid var(--gold);
  border-radius: 10px; padding: 14px 20px; font-size: .82rem; color: var(--white-dim); z-index: 999;
  transform: translateY(20px); opacity: 0; transition: all .3s ease; pointer-events: none;
  display: flex; align-items: center; gap: 10px; max-width: 320px;
}
.toast.show { transform: translateY(0); opacity: 1; }

.duration-radios .radio-group { flex-direction: column; align-items: flex-start; }

@media (max-width: 760px) {
  .profile-inner { padding: 20px 16px; }
  .form-grid { grid-template-columns: 1fr; }
  .form-group.span2 { grid-column: span 1; }
  .check-grid--4 { grid-template-columns: 1fr 1fr; }
}
</style>
