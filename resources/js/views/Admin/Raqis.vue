<template>
  <div class="page">
    <div class="page-inner">
      <p class="eyebrow">Admin</p>
      <h1 class="page-title">Raqis</h1>
      <p class="page-sub">Review and manage Raqi accounts</p>

      <div class="filter-tabs mb-6">
        <button @click="tab = 'pending'" :class="['filter-tab', tab === 'pending' && 'active']">
          Pending <span v-if="pendingRaqis.length" class="ml-1 text-gold">({{ pendingRaqis.length }})</span>
        </button>
        <button @click="tab = 'approved'" :class="['filter-tab', tab === 'approved' && 'active']">Approved</button>
      </div>

      <!-- Pending -->
      <div v-if="tab === 'pending'" class="space-y-4">
        <div v-for="raqi in pendingRaqis" :key="raqi.id" class="card-gold">
          <div class="flex items-start justify-between mb-4">
            <div>
              <p class="font-medium text-cream/90 text-base">{{ raqi.user.full_name }}</p>
              <p class="text-xs text-gold/60 mt-0.5">{{ raqi.specialization }}</p>
              <p class="text-xs text-cream/35 mt-2 leading-relaxed max-w-md">{{ raqi.bio }}</p>
            </div>
            <div class="flex gap-2 shrink-0">
              <button @click="approveRaqi(raqi.id)" class="btn-success">✓ Approve</button>
              <button @click="suspendRaqi(raqi.id)" class="btn-danger">✕ Reject</button>
            </div>
          </div>
          <div class="grid grid-cols-2 gap-3">
            <div class="p-3 rounded-lg bg-white/[0.03]"><p class="sec-label">Email</p><p class="text-sm text-cream/60">{{ raqi.user.email }}</p></div>
            <div class="p-3 rounded-lg bg-white/[0.03]"><p class="sec-label">Phone</p><p class="text-sm text-cream/60">{{ raqi.user.phone }}</p></div>
          </div>
        </div>
        <div v-if="!pendingRaqis.length" class="card empty-state">No pending Raqis</div>
      </div>

      <!-- Approved -->
      <div v-else class="card" style="padding:0;overflow:hidden">
        <table class="dark-table">
          <thead>
            <tr>
              <th>Name</th>
              <th>Email</th>
              <th>Specialization</th>
              <th>Rating</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="raqi in approvedRaqis" :key="raqi.id">
              <td style="color:rgba(245,240,232,0.85)" class="font-medium">{{ raqi.user.full_name }}</td>
              <td>{{ raqi.user.email }}</td>
              <td>{{ raqi.specialization }}</td>
              <td>{{ raqi.rating ? `★ ${raqi.rating}` : '—' }}</td>
              <td><span class="badge badge-active">Approved</span></td>
              <td><button @click="suspendRaqi(raqi.id)" class="btn-danger" style="font-size:0.7rem;padding:0.3rem 0.75rem">Suspend</button></td>
            </tr>
          </tbody>
        </table>
        <div v-if="!approvedRaqis.length" class="empty-state">No approved Raqis</div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { adminAPI } from '../../api';
import { unwrap } from '../../utils';

const tab = ref('pending');
const pendingRaqis = ref([]);
const approvedRaqis = ref([]);

onMounted(async () => {
  try {
    const [pendingRes, approvedRes] = await Promise.all([
      adminAPI.raqis.pending(),
      adminAPI.raqis.approved(),
    ]);
    pendingRaqis.value = unwrap(pendingRes);
    approvedRaqis.value = unwrap(approvedRes);
  } catch (e) { console.error(e); }
});

const approveRaqi = async (id) => {
  try {
    await adminAPI.raqis.approve(id);
    const raqi = pendingRaqis.value.find(r => r.id === id);
    if (raqi) { approvedRaqis.value.unshift({ ...raqi, is_approved: true }); }
    pendingRaqis.value = pendingRaqis.value.filter(r => r.id !== id);
  } catch (e) { alert('Failed to approve'); }
};
const suspendRaqi = async (id) => {
  try {
    await adminAPI.raqis.suspend(id);
    approvedRaqis.value = approvedRaqis.value.filter(r => r.id !== id);
    pendingRaqis.value = pendingRaqis.value.filter(r => r.id !== id);
  } catch (e) { alert('Failed to suspend'); }
};
</script>

<style scoped>
@import '../../styles/app.css';
.eyebrow { color: rgba(201,168,76,0.55); font-size: 0.65rem; letter-spacing: 0.4em; text-transform: uppercase; margin-bottom: 0.5rem; }
.text-cream { color: #f5f0e8; }
.text-gold { color: #c9a84c; }
.text-gold\/60 { color: rgba(201,168,76,0.6); }
</style>
