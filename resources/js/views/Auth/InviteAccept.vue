<template>
  <div class="min-h-screen bg-blue-50 flex items-center justify-center py-12 px-4">
    <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-8">
      <h1 class="text-2xl font-bold text-center mb-6">Co-Raqi Invitation</h1>

      <div v-if="loading" class="text-center">
        <p class="text-gray-600">Processing invitation...</p>
      </div>

      <div v-else-if="error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
        {{ error }}
      </div>

      <div v-else-if="success" class="text-center">
        <div class="mb-4 text-4xl">✓</div>
        <p class="text-green-600 font-medium mb-4">{{ success }}</p>
        <router-link to="/raqi" class="text-blue-600 hover:underline">
          Go to Dashboard
        </router-link>
      </div>

      <div v-else class="space-y-4">
        <p class="text-gray-600">You have been invited to join a healing session.</p>
        <div class="flex gap-3">
          <button
            @click="handleAccept"
            class="flex-1 bg-green-600 text-white py-2 rounded-lg font-medium hover:bg-green-700"
          >
            Accept
          </button>
          <button
            @click="handleDecline"
            class="flex-1 bg-red-600 text-white py-2 rounded-lg font-medium hover:bg-red-700"
          >
            Decline
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import { inviteAPI } from '../../api';

const router = useRouter();
const route = useRoute();
const loading = ref(false);
const error = ref('');
const success = ref('');

const handleAccept = async () => {
  loading.value = true;
  try {
    await inviteAPI.accept(route.params.token);
    success.value = 'Invitation accepted! You can now join the session.';
    setTimeout(() => router.push('/raqi'), 2000);
  } catch (err) {
    error.value = err.response?.data?.message || 'Failed to accept invitation';
    loading.value = false;
  }
};

const handleDecline = async () => {
  loading.value = true;
  try {
    await inviteAPI.decline(route.params.token);
    success.value = 'Invitation declined.';
    setTimeout(() => router.push('/raqi'), 2000);
  } catch (err) {
    error.value = err.response?.data?.message || 'Failed to decline invitation';
    loading.value = false;
  }
};
</script>
