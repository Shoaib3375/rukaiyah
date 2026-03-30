<template>
  <div class="min-h-screen bg-gray-50 py-12 px-4">
    <div class="max-w-4xl mx-auto">
      <router-link to="/patient/raqis" class="text-blue-600 hover:underline mb-4">← Back</router-link>

      <div class="bg-white rounded-lg shadow-lg p-8">
        <div v-if="raqi">
          <div class="flex justify-between items-start mb-6">
            <div>
              <h1 class="text-3xl font-bold">{{ raqi.user.full_name }}</h1>
              <p class="text-gray-600">{{ raqi.specialization }}</p>
            </div>
            <div v-if="raqi.rating" class="text-center">
              <span class="text-3xl text-yellow-400">★</span>
              <p class="font-bold">{{ raqi.rating }}</p>
              <p class="text-sm text-gray-600">{{ raqi.reviews_count }} reviews</p>
            </div>
          </div>

          <div class="mb-6 p-4 bg-gray-50 rounded-lg">
            <h2 class="font-bold mb-2">About</h2>
            <p class="text-gray-700">{{ raqi.bio || 'No bio provided' }}</p>
          </div>

          <div class="grid grid-cols-2 gap-4 mb-6">
            <div class="p-4 bg-blue-50 rounded-lg">
              <p class="text-sm text-gray-600">Email</p>
              <p class="font-medium">{{ raqi.user.email }}</p>
            </div>
            <div class="p-4 bg-blue-50 rounded-lg">
              <p class="text-sm text-gray-600">Phone</p>
              <p class="font-medium">{{ raqi.user.phone }}</p>
            </div>
          </div>

          <div class="mb-6">
            <h2 class="font-bold mb-4">Available Slots</h2>
            <div v-if="availability.length" class="space-y-2">
              <div
                v-for="slot in availability"
                :key="slot.id"
                class="p-4 border rounded-lg flex justify-between items-center"
              >
                <div>
                  <p class="font-medium">{{ formatDate(slot.date) }} at {{ formatTime(slot.start_time) }}</p>
                  <p class="text-sm text-gray-600">Duration: {{ slot.duration_minutes }} min</p>
                </div>
                <button
                  @click="bookSlot(slot)"
                  class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700"
                >
                  Book
                </button>
              </div>
            </div>
            <p v-else class="text-gray-600">No available slots</p>
          </div>

          <router-link
            :to="{ name: 'patient-book-appointment', query: { raqi_id: raqi.id } }"
            class="inline-block bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 font-medium"
          >
            Book Appointment
          </router-link>
        </div>
        <div v-else class="text-center py-12">
          <p class="text-gray-600">Loading...</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import { patientAPI } from '../../api';
import { formatDate, formatTime } from '../../utils';

const route = useRoute();
const raqi = ref(null);
const availability = ref([]);

onMounted(async () => {
  try {
    const response = await patientAPI.raqis.get(route.params.id);
    raqi.value = response.data.data;
    availability.value = raqi.value.availability || [];
  } catch (error) {
    console.error('Failed to load Raqi:', error);
  }
});

const bookSlot = (slot) => {
  // Implement booking logic
  console.log('Book slot:', slot);
};
</script>
