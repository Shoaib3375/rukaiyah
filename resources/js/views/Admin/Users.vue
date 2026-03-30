<template>
  <div class="page">
    <div class="page-inner">
      <p class="eyebrow">Admin</p>
      <h1 class="page-title">Users</h1>
      <p class="page-sub">Manage all platform accounts</p>

      <div class="flex gap-3 mb-6">
        <div class="field flex-1" style="max-width:24rem">
          <input v-model="searchQuery" type="text" placeholder="Search by name or email…" />
        </div>
        <div class="field" style="width:10rem">
          <select v-model="roleFilter">
            <option value="">All Roles</option>
            <option value="patient">Patient</option>
            <option value="raqi">Raqi</option>
            <option value="admin">Admin</option>
          </select>
        </div>
      </div>

      <div class="card" style="padding:0;overflow:hidden">
        <table class="dark-table">
          <thead>
            <tr>
              <th>Name</th>
              <th>Email</th>
              <th>Role</th>
              <th>Phone</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="u in filteredUsers" :key="u.id">
              <td class="font-medium" style="color:rgba(245,240,232,0.85)">{{ u.full_name }}</td>
              <td>{{ u.email }}</td>
              <td><span :class="`badge badge-${u.role}`">{{ u.role }}</span></td>
              <td>{{ u.phone }}</td>
              <td><span :class="u.is_active ? 'badge badge-active' : 'badge badge-suspended'">{{ u.is_active ? 'Active' : 'Inactive' }}</span></td>
              <td>
                <button @click="toggleUserStatus(u)" :class="u.is_active ? 'btn-danger' : 'btn-success'" style="font-size:0.7rem;padding:0.3rem 0.75rem">
                  {{ u.is_active ? 'Deactivate' : 'Activate' }}
                </button>
              </td>
            </tr>
          </tbody>
        </table>
        <div v-if="!filteredUsers.length" class="empty-state">No users found</div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { adminAPI } from '../../api';
import { unwrap } from '../../utils';

const users = ref([]);
const searchQuery = ref('');
const roleFilter = ref('');

const filteredUsers = computed(() =>
  users.value.filter(u =>
    (u.full_name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
     u.email.toLowerCase().includes(searchQuery.value.toLowerCase())) &&
    (!roleFilter.value || u.role === roleFilter.value)
  )
);

onMounted(async () => {
  try { const r = await adminAPI.users.list({}); users.value = unwrap(r); }
  catch (e) { console.error(e); }
});

const toggleUserStatus = async (u) => {
  try { await adminAPI.users.activate(u.id, { is_active: !u.is_active }); u.is_active = !u.is_active; }
  catch (e) { alert('Failed to update user'); }
};
</script>

<style scoped>
@import '../../styles/app.css';
.eyebrow { color: rgba(201,168,76,0.55); font-size: 0.65rem; letter-spacing: 0.4em; text-transform: uppercase; margin-bottom: 0.5rem; }
.flex-1 { flex: 1; }
</style>
