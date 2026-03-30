<template>
  <div class="min-h-screen bg-gray-50 py-12 px-4">
    <div class="max-w-6xl mx-auto">
      <h1 class="text-3xl font-bold mb-8">Browse Available Raqis</h1>

      <!-- Search & Filter -->
      <div class="bg-white rounded-lg shadow p-4 mb-6">
        <input
          v-model="searchQuery"
          type="text"
          placeholder="Search by name or specialty..."
          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
        />
      </div>

      <!-- Raqis Grid -->
      <div v-if="filteredRaqis.length" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <router-link
          v-for="raqi in filteredRaqis"
          :key="raqi.id"
          :to="`/patient/raqis/${raqi.id}`"
          class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition cursor-pointer"
        >
          <div class="mb-4">
            <h2 class="text-xl font-bold">{{ raqi.user.full_name }}</h2>
            <p class="text-sm text-gray-600">{{ raqi.specialization }}</p>
          </div>

          <div v-if="raqi.bio" class="mb-4 text-gray-700 text-sm line-clamp-3">
            {{ raqi.bio }}
          </div>

          <div class="flex items-center justify-between">
            <div v-if="raqi.rating" class="flex items-center">
              <span class="text-yellow-400">★</span>
              <span class="font-medium ml-1">{{ raqi.rating }}</span>
              <span class="text-gray-600 text-sm ml-1">({{ raqi.reviews_count }})</span>
            </div>
            <span class="px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
              Available
            </span>
          </div>
        </router-link>
      </div>

      <div v-else class="bg-white rounded-lg shadow p-8 text-center">
        <p class="text-gray-600">No Raqis found matching your search</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { patientAPI } from '../../api';

const raqis = ref([]);
const searchQuery = ref('');
const loading = ref(false);

const filteredRaqis = computed(() => {
  return raqis.value.filter(raqi =>
    raqi.user.full_name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
    raqi.specialization?.toLowerCase().includes(searchQuery.value.toLowerCase())
  );
});

onMounted(async () => {
  loading.value = true;
  try {
    const response = await patientAPI.raqis.list();
    raqis.value = response.data.data;
  } catch (error) {
    console.error('Failed to load Raqis:', error);
  } finally {
    loading.value = false;
  }
});
</script>
