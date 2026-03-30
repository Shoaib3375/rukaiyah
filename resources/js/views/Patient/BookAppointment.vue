<template>
  <div class="page">
    <div class="page-inner-sm">
      <router-link to="/patient/appointments" class="back-link">← Appointments</router-link>
      <p class="eyebrow">Patient</p>
      <h1 class="page-title">Book a Session</h1>
      <p class="page-sub">Schedule a healing session with your chosen Raqi</p>

      <div class="card-gold">
        <div v-if="error" class="alert-error mb-4">{{ error }}</div>

        <form @submit.prevent="handleSubmit" class="space-y-4">
          <div class="field">
            <label>Select Raqi</label>
            <select v-model="form.lead_raqi_id" required>
              <option value="">Choose a Raqi…</option>
              <option v-for="r in raqis" :key="r.id" :value="r.id">{{ r.user.full_name }}</option>
            </select>
          </div>
          <div class="field">
            <label>Session Type</label>
            <select v-model="form.session_type" required>
              <option value="">Choose type…</option>
              <option value="video">🎥 Video Call</option>
              <option value="chat">💬 Text Chat</option>
              <option value="phone">📞 Phone Call</option>
              <option value="in_person">🕌 In Person</option>
            </select>
          </div>
          <div class="field">
            <label>Date & Time</label>
            <input v-model="form.scheduled_at" type="datetime-local" required />
          </div>
          <div class="field">
            <label>Duration (minutes)</label>
            <input v-model.number="form.duration_minutes" type="number" min="30" max="240" required />
          </div>
          <div class="field">
            <label>Notes</label>
            <textarea v-model="form.patient_notes" rows="4" placeholder="Describe what you're seeking help with…"></textarea>
          </div>
          <button type="submit" :disabled="loading" class="btn-gold w-full">
            <span v-if="loading" class="spinner"></span>
            {{ loading ? 'Booking…' : 'Book Appointment' }}
          </button>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { patientAPI } from '../../api';
import { unwrap } from '../../utils';

const router = useRouter();
const loading = ref(false);
const error = ref('');
const raqis = ref([]);

const form = reactive({ lead_raqi_id: '', session_type: '', scheduled_at: '', duration_minutes: 60, patient_notes: '' });

onMounted(async () => {
  try { const r = await patientAPI.raqis.list(); raqis.value = unwrap(r); }
  catch (e) { error.value = 'Failed to load Raqis'; }
});

const handleSubmit = async () => {
  loading.value = true; error.value = '';
  try { await patientAPI.appointments.create(form); router.push('/patient/appointments'); }
  catch (e) { error.value = e.response?.data?.message || 'Failed to book'; loading.value = false; }
};
</script>

<style scoped>
@import '../../styles/app.css';
.eyebrow { color: rgba(201,168,76,0.55); font-size: 0.65rem; letter-spacing: 0.4em; text-transform: uppercase; margin-bottom: 0.5rem; }
.w-full { width: 100%; }
.spinner { width:14px;height:14px;border:2px solid rgba(8,6,20,0.3);border-top-color:#080614;border-radius:50%;animation:spin .7s linear infinite;display:inline-block; }
@keyframes spin{to{transform:rotate(360deg)}}
</style>
