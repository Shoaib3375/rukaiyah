<template>
  <div class="min-h-screen bg-gray-50 py-12 px-4">
    <div class="max-w-4xl mx-auto">
      <h1 class="text-3xl font-bold mb-8">Manage Availability</h1>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Add Availability Form -->
        <div class="lg:col-span-1">
          <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-bold mb-4">Add Slot</h2>
            <form @submit.prevent="handleAddSlot" class="space-y-3">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                <input
                  v-model="newSlot.date"
                  type="date"
                  required
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                />
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Start Time</label>
                <input
                  v-model="newSlot.start_time"
                  type="time"
                  required
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                />
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">End Time</label>
                <input
                  v-model="newSlot.end_time"
                  type="time"
                  required
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                />
              </div>

              <button
                :disabled="loading"
                class="w-full bg-blue-600 text-white py-2 rounded-lg font-medium hover:bg-blue-700 disabled:opacity-50"
              >
                {{ loading ? 'Adding...' : 'Add Slot' }}
              </button>
            </form>
          </div>
        </div>

        <!-- Availability List -->
        <div class="lg:col-span-2">
          <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-bold mb-4">Your Availability</h2>
            <div v-if="availability.length" class="space-y-3">
              <div
                v-for="slot in availability"
                :key="slot.id"
                class="flex justify-between items-center p-4 border rounded-lg"
              >
                <div>
                  <p class="font-medium">{{ formatDate(slot.date) }}</p>
                  <p class="text-sm text-gray-600">{{ slot.start_time }} - {{ slot.end_time }}</p>
                </div>
                <button
                  @click="deleteSlot(slot.id)"
                  class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700"
                >
                  Delete
                </button>
              </div>
            </div>
            <p v-else class="text-gray-600">No availability slots added</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue';
import { raqiAPI } from '../../api';
import { formatDate } from '../../utils';

const loading = ref(false);
const availability = ref([]);
const newSlot = reactive({
  date: '',
  start_time: '',
  end_time: ''
});

onMounted(async () => {
  try {
    const response = await raqiAPI.availability.list();
    availability.value = response.data.data;
  } catch (error) {
    console.error('Failed to load availability:', error);
  }
});

const handleAddSlot = async () => {
  loading.value = true;
  try {
    const response = await raqiAPI.availability.create(newSlot);
    availability.value.push(response.data.data);
    newSlot.date = '';
    newSlot.start_time = '';
    newSlot.end_time = '';
  } catch (error) {
    alert('Failed to add slot');
  } finally {
    loading.value = false;
  }
};

const deleteSlot = async (id) => {
  if (confirm('Delete this slot?')) {
    try {
      await raqiAPI.availability.delete(id);
      availability.value = availability.value.filter(s => s.id !== id);
    } catch (error) {
      alert('Failed to delete slot');
    }
  }
};
</script>
