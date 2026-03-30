<template>
  <div class="min-h-screen bg-gray-50 py-12 px-4">
    <div class="max-w-7xl mx-auto">
      <h1 class="text-3xl font-bold mb-8">Manage Raqis</h1>

      <!-- Tabs -->
      <div class="bg-white rounded-lg shadow p-4 mb-6 flex gap-2">
        <button
          @click="tab = 'pending'"
          :class="[
            'px-4 py-2 rounded-lg font-medium transition',
            tab === 'pending'
              ? 'bg-blue-600 text-white'
              : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
          ]"
        >
          Pending ({{ pendingRaqis.length }})
        </button>
        <button
          @click="tab = 'approved'"
          :class="[
            'px-4 py-2 rounded-lg font-medium transition',
            tab === 'approved'
              ? 'bg-blue-600 text-white'
              : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
          ]"
        >
          Approved
        </button>
      </div>

      <!-- Pending Raqis -->
      <div v-if="tab === 'pending'" class="space-y-4">
        <div v-for="raqi in pendingRaqis" :key="raqi.id" class="bg-white rounded-lg shadow-lg p-6">
          <div class="flex justify-between items-start mb-4">
            <div>
              <h2 class="text-2xl font-bold">{{ raqi.user.full_name }}</h2>
              <p class="text-gray-600">{{ raqi.specialization }}</p>
              <p class="text-sm text-gray-600 mt-2">{{ raqi.bio }}</p>
            </div>
            <div class="flex gap-2">
              <button
                @click="approveRaqi(raqi.id)"
                class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 font-medium"
              >
                ✓ Approve
              </button>
              <button
                @click="suspendRaqi(raqi.id)"
                class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 font-medium"
              >
                ✕ Reject
              </button>
            </div>
          </div>

          <div class="grid grid-cols-2 gap-4">
            <div class="p-3 bg-gray-50 rounded">
              <p class="text-sm text-gray-600">Email</p>
              <p class="font-medium">{{ raqi.user.email }}</p>
            </div>
            <div class="p-3 bg-gray-50 rounded">
              <p class="text-sm text-gray-600">Phone</p>
              <p class="font-medium">{{ raqi.user.phone }}</p>
            </div>
          </div>
        </div>

        <div v-if="pendingRaqis.length === 0" class="bg-white rounded-lg shadow p-8 text-center">
          <p class="text-gray-600">No pending Raqis</p>
        </div>
      </div>

      <!-- Approved Raqis -->
      <div v-else class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full">
          <thead class="bg-gray-100 border-b">
            <tr>
              <th class="px-6 py-3 text-left font-bold">Name</th>
              <th class="px-6 py-3 text-left font-bold">Email</th>
              <th class="px-6 py-3 text-left font-bold">Status</th>
              <th class="px-6 py-3 text-left font-bold">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y">
            <tr v-for="raqi in approvedRaqis" :key="raqi.id" class="hover:bg-gray-50">
              <td class="px-6 py-4 font-medium">{{ raqi.user.full_name }}</td>
              <td class="px-6 py-4">{{ raqi.user.email }}</td>
              <td class="px-6 py-4">
                <span class="px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                  Approved
                </span>
              </td>
              <td class="px-6 py-4">
                <button
                  @click="suspendRaqi(raqi.id)"
                  class="bg-red-600 text-white px-3 py-1 rounded text-sm hover:bg-red-700"
                >
                  Suspend
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { adminAPI } from '../../api';

const tab = ref('pending');
const allRaqis = ref([]);

const pendingRaqis = computed(() => allRaqis.value.filter(r => r.status === 'pending'));
const approvedRaqis = computed(() => allRaqis.value.filter(r => r.status === 'approved'));

onMounted(async () => {
  try {
    const response = await adminAPI.raqis.pending();
    allRaqis.value = response.data.data;
    // Also fetch approved raqis
    // TODO: Add endpoint for approved raqis
  } catch (error) {
    console.error('Failed to load Raqis:', error);
  }
});

const approveRaqi = async (raqiId) => {
  try {
    await adminAPI.raqis.approve(raqiId);
    const raqi = allRaqis.value.find(r => r.id === raqiId);
    if (raqi) raqi.status = 'approved';
  } catch (error) {
    alert('Failed to approve Raqi');
  }
};

const suspendRaqi = async (raqiId) => {
  try {
    await adminAPI.raqis.suspend(raqiId);
    allRaqis.value = allRaqis.value.filter(r => r.id !== raqiId);
  } catch (error) {
    alert('Failed to suspend Raqi');
  }
};
</script>
