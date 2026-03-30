<template>
  <div class="min-h-screen bg-gray-50 py-12 px-4">
    <div class="max-w-7xl mx-auto">
      <h1 class="text-3xl font-bold mb-8">Manage Users</h1>

      <!-- Search & Filter -->
      <div class="bg-white rounded-lg shadow p-4 mb-6 flex gap-2">
        <input
          v-model="searchQuery"
          type="text"
          placeholder="Search users..."
          class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
        />
        <select
          v-model="roleFilter"
          class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
        >
          <option value="">All Roles</option>
          <option value="patient">Patient</option>
          <option value="raqi">Raqi</option>
          <option value="admin">Admin</option>
        </select>
      </div>

      <!-- Users Table -->
      <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full">
          <thead class="bg-gray-100 border-b">
            <tr>
              <th class="px-6 py-3 text-left font-bold">Name</th>
              <th class="px-6 py-3 text-left font-bold">Email</th>
              <th class="px-6 py-3 text-left font-bold">Role</th>
              <th class="px-6 py-3 text-left font-bold">Phone</th>
              <th class="px-6 py-3 text-left font-bold">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y">
            <tr v-for="user in filteredUsers" :key="user.id" class="hover:bg-gray-50">
              <td class="px-6 py-4 font-medium">{{ user.full_name }}</td>
              <td class="px-6 py-4">{{ user.email }}</td>
              <td class="px-6 py-4">
                <span :class="`px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800`">
                  {{ user.role }}
                </span>
              </td>
              <td class="px-6 py-4">{{ user.phone }}</td>
              <td class="px-6 py-4 flex gap-2">
                <button
                  @click="toggleUserStatus(user.id)"
                  :class="user.is_active ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700'"
                  class="text-white px-3 py-1 rounded text-sm"
                >
                  {{ user.is_active ? 'Deactivate' : 'Activate' }}
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

const users = ref([]);
const searchQuery = ref('');
const roleFilter = ref('');

const filteredUsers = computed(() => {
  return users.value.filter(user =>
    (user.full_name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
     user.email.toLowerCase().includes(searchQuery.value.toLowerCase())) &&
    (!roleFilter.value || user.role === roleFilter.value)
  );
});

onMounted(async () => {
  try {
    const response = await adminAPI.users.list({});
    users.value = response.data.data;
  } catch (error) {
    console.error('Failed to load users:', error);
  }
});

const toggleUserStatus = async (userId) => {
  try {
    const user = users.value.find(u => u.id === userId);
    await adminAPI.users.activate(userId, { is_active: !user.is_active });
    user.is_active = !user.is_active;
  } catch (error) {
    alert('Failed to update user status');
  }
};
</script>
