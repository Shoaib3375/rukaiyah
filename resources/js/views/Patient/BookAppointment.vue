<template>
  <div class="min-h-screen bg-gray-50 py-12 px-4">
    <div class="max-w-2xl mx-auto">
      <div class="bg-white rounded-lg shadow-lg p-8">
        <h1 class="text-3xl font-bold mb-2">Book An Appointment</h1>
        <p class="text-gray-600 mb-6">Schedule a healing session with your chosen Raqi</p>

        <form @submit.prevent="handleSubmit" class="space-y-4">
          <div v-if="error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            {{ error }}
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Select Raqi</label>
            <select
              v-model="form.lead_raqi_id"
              required
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
            >
              <option value="">Choose a Raqi...</option>
              <option v-for="raqi in raqis" :key="raqi.id" :value="raqi.id">
                {{ raqi.user.full_name }}
              </option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Session Type</label>
            <select
              v-model="form.session_type"
              required
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
            >
              <option value="">Choose session type...</option>
              <option value="video">Video Call</option>
              <option value="chat">Text Chat</option>
              <option value="phone">Phone Call</option>
              <option value="in_person">In Person</option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Scheduled Date & Time</label>
            <input
              v-model="form.scheduled_at"
              type="datetime-local"
              required
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
            />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Duration (minutes)</label>
            <input
              v-model.number="form.duration_minutes"
              type="number"
              min="30"
              max="240"
              required
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
            />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
            <textarea
              v-model="form.patient_notes"
              rows="4"
              placeholder="Describe what you're seeking help with..."
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
            ></textarea>
          </div>

          <button
            :disabled="loading"
            class="w-full bg-blue-600 text-white py-2 rounded-lg font-medium hover:bg-blue-700 disabled:opacity-50"
          >
            {{ loading ? 'Booking...' : 'Book Appointment' }}
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

const router = useRouter();
const loading = ref(false);
const error = ref('');
const raqis = ref([]);

const form = reactive({
  lead_raqi_id: '',
  session_type: '',
  scheduled_at: '',
  duration_minutes: 60,
  patient_notes: ''
});

onMounted(async () => {
  try {
    const response = await patientAPI.raqis.list();
    raqis.value = response.data.data;
  } catch (err) {
    error.value = 'Failed to load Raqis';
  }
});

const handleSubmit = async () => {
  loading.value = true;
  error.value = '';
  try {
    await patientAPI.appointments.create(form);
    router.push('/patient/appointments');
  } catch (err) {
    error.value = err.response?.data?.message || 'Failed to book appointment';
    loading.value = false;
  }
};
</script>
