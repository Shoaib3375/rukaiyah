<template>
  <div class="page">
    <div class="page-inner-md">
      <p class="eyebrow">Patient</p>
      <h1 class="page-title">Notifications</h1>
      <p class="page-sub">Stay updated on your sessions</p>

      <div v-if="notifications.length" class="space-y-3">
        <div v-for="n in notifications" :key="n.id"
          class="card notification-item"
          :class="{ unread: !n.read_at }"
          @click="markAsRead(n)">
          <div class="flex items-start justify-between gap-4">
            <div class="flex-1">
              <p class="text-sm font-medium text-cream/85">{{ n.title }}</p>
              <p class="text-xs text-cream/45 mt-1 leading-relaxed">{{ n.message }}</p>
              <p class="text-xs text-cream/25 mt-2">{{ formatDateTime(n.created_at) }}</p>
            </div>
            <span v-if="!n.read_at" class="badge badge-new shrink-0">New</span>
          </div>
        </div>
      </div>

      <div v-else class="card empty-state">No notifications yet</div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { patientAPI } from '../../api';
import { formatDateTime, unwrap } from '../../utils';

const notifications = ref([]);

onMounted(async () => {
  try { const r = await patientAPI.notifications.list(); notifications.value = unwrap(r); }
  catch (e) { console.error(e); }
});

const markAsRead = async (n) => {
  if (n.read_at) return;
  try { await patientAPI.notifications.markRead(n.id); n.read_at = new Date().toISOString(); }
  catch (e) { console.error(e); }
};
</script>

<style scoped>
@import '../../styles/app.css';
.eyebrow { color: rgba(201,168,76,0.55); font-size: 0.65rem; letter-spacing: 0.4em; text-transform: uppercase; margin-bottom: 0.5rem; }
.text-cream { color: #f5f0e8; }
.notification-item { cursor: pointer; transition: border-color 0.2s; }
.notification-item.unread { border-color: rgba(201,168,76,0.2); }
.notification-item:hover { border-color: rgba(201,168,76,0.3); }
</style>
