<template>
  <div class="min-h-screen bg-gray-50 py-12 px-4">
    <div class="max-w-4xl mx-auto">
      <h1 class="text-3xl font-bold mb-8">My Profile</h1>

      <form @submit.prevent="handleSave" class="bg-white rounded-lg shadow-lg p-8 space-y-4">
        <div v-if="message" :class="`p-4 rounded mb-4 ${message.type === 'success' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'}`">
          {{ message.text }}
        </div>

        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
            <input v-model="profile.full_name" type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input v-model="profile.email" type="email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" />
          </div>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
          <input v-model="profile.phone" type="tel" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" />
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Specialization</label>
          <input v-model="profile.specialization" type="text" placeholder="e.g., Islamic Ruqyah..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" />
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Bio</label>
          <textarea v-model="profile.bio" rows="4" placeholder="Tell patients about yourself..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"></textarea>
        </div>

        <button :disabled="loading" class="w-full bg-blue-600 text-white py-2 rounded-lg font-medium hover:bg-blue-700 disabled:opacity-50">
          {{ loading ? 'Saving...' : 'Save Changes' }}
        </button>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue';
import { raqiAPI } from '../../api';

const loading = ref(false);
const message = ref(null);
const profile = reactive({
  full_name: '',
  email: '',
  phone: '',
  specialization: '',
  bio: ''
});

onMounted(async () => {
  try {
    const response = await raqiAPI.profile.get();
    Object.assign(profile, response.data.data);
  } catch (error) {
    message.value = { type: 'error', text: 'Failed to load profile' };
  }
});

const handleSave = async () => {
  loading.value = true;
  try {
    await raqiAPI.profile.update(profile);
    message.value = { type: 'success', text: 'Profile updated successfully' };
  } catch (error) {
    message.value = { type: 'error', text: 'Failed to update profile' };
  } finally {
    loading.value = false;
  }
};
</script>
