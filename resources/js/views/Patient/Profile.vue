<template>
  <div class="page">
    <div class="page-inner-sm">
      <p class="eyebrow">Patient</p>
      <h1 class="page-title">My Profile</h1>
      <p class="page-sub">Manage your account details</p>

      <div class="card-gold">
        <div v-if="message" :class="message.type === 'success' ? 'alert-success' : 'alert-error'" class="mb-4">{{ message.text }}</div>

        <form @submit.prevent="handleSave" class="space-y-4">
          <div class="grid grid-cols-2 gap-4">
            <div class="field">
              <label>Full Name</label>
              <input v-model="profile.full_name" type="text" />
            </div>
            <div class="field">
              <label>Email</label>
              <input v-model="profile.email" type="email" />
            </div>
          </div>
          <div class="field">
            <label>Phone</label>
            <input v-model="profile.phone" type="tel" />
          </div>
          <div class="field">
            <label>Health Notes (optional)</label>
            <textarea v-model="profile.medical_notes" rows="4" placeholder="Describe your health concerns…"></textarea>
          </div>
          <button type="submit" :disabled="loading" class="btn-gold w-full">
            <span v-if="loading" class="spinner"></span>
            {{ loading ? 'Saving…' : 'Save Changes' }}
          </button>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue';
import { patientAPI } from '../../api';

const loading = ref(false);
const message = ref(null);
const profile = reactive({ full_name: '', email: '', phone: '', medical_notes: '' });

onMounted(async () => {
  try { const r = await patientAPI.profile.get(); Object.assign(profile, r.data.data); }
  catch (e) { message.value = { type: 'error', text: 'Failed to load profile' }; }
});

const handleSave = async () => {
  loading.value = true;
  try { await patientAPI.profile.update(profile); message.value = { type: 'success', text: 'Profile updated' }; }
  catch (e) { message.value = { type: 'error', text: 'Failed to update profile' }; }
  finally { loading.value = false; }
};
</script>

<style scoped>
@import '../../styles/app.css';
.eyebrow { color: rgba(201,168,76,0.55); font-size: 0.65rem; letter-spacing: 0.4em; text-transform: uppercase; margin-bottom: 0.5rem; }
.w-full { width: 100%; }
.spinner { width:14px;height:14px;border:2px solid rgba(8,6,20,0.3);border-top-color:#080614;border-radius:50%;animation:spin .7s linear infinite;display:inline-block; }
@keyframes spin{to{transform:rotate(360deg)}}
</style>
