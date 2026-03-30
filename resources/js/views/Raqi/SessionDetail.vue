<template>
  <div class="min-h-screen bg-gray-50 py-12 px-4">
    <div class="max-w-4xl mx-auto">
      <router-link to="/raqi/appointments" class="text-blue-600 mb-4">← Back</router-link>

      <div v-if="appointment" class="space-y-6">
        <!-- Session Header -->
        <div class="bg-white rounded-lg shadow-lg p-8">
          <h1 class="text-3xl font-bold mb-4">{{ appointment.patient_profile?.user?.full_name }}</h1>
          <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div>
              <p class="text-sm text-gray-600">Status</p>
              <p :class="`font-bold ${getStatusClass(appointment.status)}`">{{ appointment.status }}</p>
            </div>
            <div>
              <p class="text-sm text-gray-600">Session Type</p>
              <p class="font-bold">{{ appointment.session_type }}</p>
            </div>
            <div>
              <p class="text-sm text-gray-600">Date & Time</p>
              <p class="font-bold">{{ formatDateTime(appointment.scheduled_at) }}</p>
            </div>
            <div>
              <p class="text-sm text-gray-600">Duration</p>
              <p class="font-bold">{{ appointment.duration_minutes }} min</p>
            </div>
          </div>
        </div>

        <!-- Actions -->
        <div v-if="appointment.status === 'pending'" class="bg-white rounded-lg shadow p-6 flex gap-4">
          <button
            @click="acceptAppointment"
            class="flex-1 bg-green-600 text-white py-3 rounded-lg hover:bg-green-700 font-bold"
          >
            ✓ Accept
          </button>
          <button
            @click="declineAppointment"
            class="flex-1 bg-red-600 text-white py-3 rounded-lg hover:bg-red-700 font-bold"
          >
            ✕ Decline
          </button>
        </div>

        <!-- Patient Notes -->
        <div v-if="appointment.patient_notes" class="bg-blue-50 rounded-lg p-6">
          <h3 class="font-bold mb-2">Patient's Notes</h3>
          <p class="text-gray-700">{{ appointment.patient_notes }}</p>
        </div>

        <!-- Session Notes -->
        <div class="bg-white rounded-lg shadow-lg p-6">
          <h3 class="text-xl font-bold mb-4">Session Notes</h3>
          <form v-if="appointment.status === 'accepted'" @submit.prevent="addNote" class="mb-6">
            <select
              v-model="newNote.note_type"
              class="w-full px-4 py-2 border rounded-lg mb-2"
            >
              <option value="ruqyah_log">Ruqyah Log</option>
              <option value="observation">Observation</option>
              <option value="recommendation">Recommendation</option>
              <option value="chat">Chat</option>
            </select>
            <textarea
              v-model="newNote.content"
              placeholder="Add a note..."
              rows="4"
              class="w-full px-4 py-2 border rounded-lg mb-2"
            ></textarea>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
              Add Note
            </button>
          </form>

          <div v-if="sessionNotes.length" class="space-y-3">
            <div v-for="note in sessionNotes" :key="note.id" class="border rounded-lg p-4 bg-gray-50">
              <p class="text-sm font-bold text-gray-600">{{ note.note_type }}</p>
              <p class="text-gray-800">{{ note.content }}</p>
            </div>
          </div>
          <p v-else class="text-gray-600">No notes yet</p>
        </div>

        <!-- Invite Co-Raqi -->
        <div v-if="appointment.status === 'accepted'" class="bg-white rounded-lg shadow-lg p-6">
          <h3 class="text-xl font-bold mb-4">Invite Co-Raqi</h3>
          <form @submit.prevent="inviteCoRaqi" class="flex gap-2">
            <select
              v-model="inviteForm.raqi_id"
              class="flex-1 px-4 py-2 border rounded-lg"
            >
              <option value="">Select a Raqi...</option>
              <option v-for="r in availableRaqis" :key="r.id" :value="r.id">
                {{ r.user.full_name }}
              </option>
            </select>
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
              Invite
            </button>
          </form>
        </div>

        <!-- Participants -->
        <div v-if="appointment.status !== 'pending'" class="bg-white rounded-lg shadow-lg p-6">
          <h3 class="text-xl font-bold mb-4">Participants</h3>
          <div v-if="participants.length" class="space-y-2">
            <div v-for="p in participants" :key="p.id" class="flex justify-between items-center p-3 bg-gray-50 rounded">
              <p class="font-medium">{{ p.raqi?.user?.full_name || p.patient?.user?.full_name }}</p>
              <span :class="`px-2 py-1 text-sm rounded font-medium ${p.status === 'accepted' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700'}`">
                {{ p.status }}
              </span>
            </div>
          </div>
        </div>

        <!-- Complete Session -->
        <div v-if="appointment.status === 'accepted'" class="flex gap-4">
          <button
            @click="completeAppointment"
            class="flex-1 bg-purple-600 text-white py-3 rounded-lg hover:bg-purple-700 font-bold"
          >
            ✓ Complete Session
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import { raqiAPI } from '../../api';
import { formatDateTime, getStatusClass } from '../../utils';

const route = useRoute();
const appointment = ref(null);
const sessionNotes = ref([]);
const participants = ref([]);
const availableRaqis = ref([]);

const newNote = reactive({ content: '', note_type: 'ruqyah_log' });
const inviteForm = reactive({ raqi_id: '' });

onMounted(async () => {
  try {
    const aptResponse = await raqiAPI.appointments.list();
    appointment.value = aptResponse.data.data.find(a => a.id === route.params.id);

    const notesResponse = await raqiAPI.notes.list(route.params.id);
    sessionNotes.value = notesResponse.data.data;

    const partsResponse = await raqiAPI.participants.list(route.params.id);
    participants.value = partsResponse.data.data;
  } catch (error) {
    console.error('Failed to load session:', error);
  }
});

const acceptAppointment = async () => {
  try {
    await raqiAPI.appointments.accept(route.params.id);
    appointment.value.status = 'accepted';
  } catch (error) {
    alert('Failed to accept appointment');
  }
};

const declineAppointment = async () => {
  try {
    await raqiAPI.appointments.decline(route.params.id);
    appointment.value.status = 'declined';
  } catch (error) {
    alert('Failed to decline appointment');
  }
};

const addNote = async () => {
  try {
    const response = await raqiAPI.notes.create(route.params.id, newNote);
    sessionNotes.value.push(response.data.data);
    newNote.content = '';
  } catch (error) {
    alert('Failed to add note');
  }
};

const inviteCoRaqi = async () => {
  try {
    await raqiAPI.participants.invite(route.params.id, { raqi_id: inviteForm.raqi_id });
    inviteForm.raqi_id = '';
  } catch (error) {
    alert('Failed to invite co-Raqi');
  }
};

const completeAppointment = async () => {
  try {
    await raqiAPI.appointments.complete(route.params.id);
    appointment.value.status = 'completed';
  } catch (error) {
    alert('Failed to complete appointment');
  }
};
</script>
