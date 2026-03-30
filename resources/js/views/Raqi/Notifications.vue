<template>
  <div class="min-h-screen bg-gray-50 py-12 px-4">
    <div class="max-w-4xl mx-auto">
      <h1 class="text-3xl font-bold mb-8">Notifications</h1>

      <div v-if="notifications.length" class="space-y-4">
        <div
          v-for="notification in notifications"
          :key="notification.id"
          class="bg-white rounded-lg shadow p-6 cursor-pointer hover:shadow-lg transition"
          @click="markAsRead(notification)"
        >
          <div class="flex justify-between items-start">
            <div class="flex-1">
              <h2 class="font-bold text-lg">{{ notification.title }}</h2>
              <p class="text-gray-700 mt-1">{{ notification.message }}</p>
              <p class="text-sm text-gray-500 mt-2">{{ formatDateTime(notification.created_at) }}</p>
            </div>
            <span
              v-if="!notification.read_at"
              class="px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800"
            >
              New
            </span>
          </div>
        </div>
      </div>

      <div v-else class="bg-white rounded-lg shadow p-8 text-center">
        <p class="text-gray-600">No notifications</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { raqiAPI } from '../../api';
import { formatDateTime } from '../../utils';

const notifications = ref([]);

onMounted(async () => {
  try {
    const response = await raqiAPI.notifications.list();
    notifications.value = response.data.data;
  } catch (error) {
    console.error('Failed to load notifications:', error);
  }
});

const markAsRead = async (notification) => {
  if (!notification.read_at) {
    try {
      await raqiAPI.notifications.markRead(notification.id);
      notification.read_at = new Date().toISOString();
    } catch (error) {
      console.error('Failed to mark notification as read:', error);
    }
  }
};
</script>
