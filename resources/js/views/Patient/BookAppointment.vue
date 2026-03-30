<template>
  <div class="page">
    <div class="page-inner">
      <router-link to="/patient/appointments" class="back-link">← Appointments</router-link>
      <p class="eyebrow">Patient</p>
      <h1 class="page-title">Book a Session</h1>
      <p class="page-sub">Schedule a healing session with your chosen Raqi</p>

      <div class="grid grid-cols-2 gap-6 mt-8">
        <!-- Left: Form -->
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
              <label>Select Date</label>
              <input v-model="selectedDate" type="date" @change="loadSlotsForDate" required />
            </div>

            <div class="field">
              <label>Duration (minutes)</label>
              <select v-model.number="form.duration_minutes">
                <option value="30">30 minutes</option>
                <option value="60">1 hour</option>
                <option value="90">1.5 hours</option>
                <option value="120">2 hours</option>
              </select>
            </div>

            <div class="field">
              <label>Notes</label>
              <textarea v-model="form.patient_notes" rows="4" placeholder="Describe what you're seeking help with…"></textarea>
            </div>

            <button type="submit" :disabled="loading || !form.scheduled_at" class="btn-gold w-full">
              <span v-if="loading" class="spinner"></span>
              {{ loading ? 'Booking…' : 'Book Appointment' }}
            </button>
          </form>
        </div>

        <!-- Right: Available Slots -->
        <div class="card-gold">
          <p class="sec-label mb-4">Available Slots {{ selectedDate ? '(' + availableSlots.length + ')' : '' }}</p>
          <div v-if="!selectedDate" class="text-center text-gray-400 py-8">
            Select a date to see available slots
          </div>
          <div v-else-if="loadingSlots" class="text-center py-8">
            <div class="spinner mx-auto mb-2"></div>
            Loading slots...
          </div>
          <div v-else-if="availableSlots.length === 0" class="alert-warning">
            No available slots for this date. Try another date.
          </div>
          <div v-else class="space-y-2 max-h-96 overflow-y-auto">
            <button 
              v-for="slot in availableSlots" 
              :key="slot.start"
              @click="form.scheduled_at = slot.start"
              :class="['slot-button', { 'active': form.scheduled_at === slot.start }]"
            >
              {{ slot.display }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { patientAPI } from '../../api';
import { unwrap } from '../../utils';

const router = useRouter();
const loading = ref(false);
const loadingSlots = ref(false);
const error = ref('');
const raqis = ref([]);
const availableSlots = ref([]);
const selectedDate = ref('');

const form = reactive({ 
  lead_raqi_id: '', 
  session_type: '', 
  scheduled_at: '', 
  duration_minutes: 60, 
  patient_notes: '' 
});

onMounted(async () => {
  try { const r = await patientAPI.raqis.list(); raqis.value = unwrap(r); }
  catch (e) { error.value = 'Failed to load Raqis'; }
});

const loadSlotsForDate = async () => {
  if (!form.lead_raqi_id || !selectedDate.value) {
    availableSlots.value = [];
    form.scheduled_at = '';
    return;
  }

  loadingSlots.value = true;
  error.value = '';
  form.scheduled_at = '';

  try {
    const response = await patientAPI.raqis.getAvailableSlots(form.lead_raqi_id, { 
      date: selectedDate.value,
      duration_minutes: form.duration_minutes 
    });
    
    // Response structure: { success: true, data: { slots: [...] } }
    const responseData = response.data || response;
    const slotsData = responseData.data || responseData;
    const slots = slotsData.slots || [];
    
    availableSlots.value = slots;
    if (slots.length === 0) {
      error.value = 'No available slots for this date';
    }
  } catch (e) {
    error.value = e.response?.data?.message || 'Failed to load slots';
    availableSlots.value = [];
  } finally {
    loadingSlots.value = false;
  }
};

const handleSubmit = async () => {
  loading.value = true;
  error.value = '';
  try { 
    await patientAPI.appointments.create(form); 
    router.push('/patient/appointments'); 
  }
  catch (e) { 
    error.value = e.response?.data?.message || 'Failed to book'; 
    loading.value = false; 
  }
};
</script>

<style scoped>
@import '../../styles/app.css';
.eyebrow { color: rgba(201,168,76,0.55); font-size: 0.65rem; letter-spacing: 0.4em; text-transform: uppercase; margin-bottom: 0.5rem; }
.grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; }
.gap-6 { gap: 1.5rem; }
.mt-8 { margin-top: 2rem; }
.w-full { width: 100%; }
.mx-auto { margin-left: auto; margin-right: auto; }
.mb-2 { margin-bottom: 0.5rem; }
.mb-4 { margin-bottom: 1rem; }
.py-8 { padding-top: 2rem; padding-bottom: 2rem; }
.text-center { text-align: center; }
.text-gray-400 { color: rgba(201,168,76,0.4); }
.sec-label { font-size: 0.95rem; font-weight: 600; color: rgba(201,168,76,1); }
.date-group { margin-bottom: 1.5rem; }
.date-label { font-size: 0.85rem; font-weight: 600; color: rgba(201,168,76,0.7); margin-bottom: 0.5rem; padding-bottom: 0.5rem; border-bottom: 1px solid rgba(201,168,76,0.2); }
.slot-button { 
  display: block;
  width: 100%;
  padding: 0.75rem 1rem;
  border: 2px solid rgba(201,168,76,0.3);
  background: transparent;
  color: rgba(201,168,76,0.8);
  border-radius: 0.5rem;
  cursor: pointer;
  font-size: 0.9rem;
  transition: all 0.2s;
  text-align: left;
}
.slot-button:hover { border-color: rgba(201,168,76,0.6); background: rgba(201,168,76,0.05); }
.slot-button.active { background: rgba(201,168,76,0.2); border-color: rgba(201,168,76,1); color: rgba(201,168,76,1); font-weight: 600; }
.max-h-96 { max-height: 24rem; }
.overflow-y-auto { overflow-y: auto; }
.space-y-2 > * + * { margin-top: 0.5rem; }
.space-y-4 > * + * { margin-top: 1rem; }
.spinner { width:14px; height:14px; border:2px solid rgba(8,6,20,0.3); border-top-color:#080614; border-radius:50%; animation:spin .7s linear infinite; display:inline-block; }
@keyframes spin{to{transform:rotate(360deg)}}
.alert-warning { background: rgba(201,168,76,0.1); border: 1px solid rgba(201,168,76,0.3); border-radius: 0.5rem; padding: 1rem; color: rgba(201,168,76,0.8); font-size: 0.9rem; }
.alert-error { background: rgba(255,0,0,0.1); border: 1px solid rgba(255,0,0,0.3); border-radius: 0.5rem; padding: 1rem; color: rgba(255,100,100,1); font-size: 0.9rem; }

@media (max-width: 1024px) {
  .grid { grid-template-columns: 1fr; }
}
</style>
