<template>
  <nav class="bg-white shadow">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between h-16">
        <!-- Logo -->
        <div class="flex items-center">
          <router-link to="/" class="text-2xl font-bold text-blue-600">
            🌙 Jetty Healing
          </router-link>
        </div>

        <!-- Navigation Links -->
        <div class="flex items-center space-x-1">
          <!-- Patient Links -->
          <template v-if="userRole === 'patient'">
            <router-link
              to="/patient"
              class="px-3 py-2 rounded-md text-sm font-medium"
              :class="isActive('/patient') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-50'"
            >
              Dashboard
            </router-link>
            <router-link
              to="/patient/appointments"
              class="px-3 py-2 rounded-md text-sm font-medium"
              :class="isActive('/patient/appointments') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-50'"
            >
              Appointments
            </router-link>
            <router-link
              to="/patient/raqis"
              class="px-3 py-2 rounded-md text-sm font-medium"
              :class="isActive('/patient/raqis') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-50'"
            >
              Browse Raqis
            </router-link>
            <router-link
              to="/patient/profile"
              class="px-3 py-2 rounded-md text-sm font-medium"
              :class="isActive('/patient/profile') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-50'"
            >
              Profile
            </router-link>
          </template>

          <!-- Raqi Links -->
          <template v-else-if="userRole === 'raqi'">
            <router-link
              to="/raqi"
              class="px-3 py-2 rounded-md text-sm font-medium"
              :class="isActive('/raqi') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-50'"
            >
              Dashboard
            </router-link>
            <router-link
              to="/raqi/appointments"
              class="px-3 py-2 rounded-md text-sm font-medium"
              :class="isActive('/raqi/appointments') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-50'"
            >
              Appointments
            </router-link>
            <router-link
              to="/raqi/availability"
              class="px-3 py-2 rounded-md text-sm font-medium"
              :class="isActive('/raqi/availability') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-50'"
            >
              Availability
            </router-link>
            <router-link
              to="/raqi/profile"
              class="px-3 py-2 rounded-md text-sm font-medium"
              :class="isActive('/raqi/profile') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-50'"
            >
              Profile
            </router-link>
          </template>

          <!-- Admin Links -->
          <template v-else-if="userRole === 'admin'">
            <router-link
              to="/admin"
              class="px-3 py-2 rounded-md text-sm font-medium"
              :class="isActive('/admin') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-50'"
            >
              Dashboard
            </router-link>
            <router-link
              to="/admin/users"
              class="px-3 py-2 rounded-md text-sm font-medium"
              :class="isActive('/admin/users') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-50'"
            >
              Users
            </router-link>
            <router-link
              to="/admin/raqis"
              class="px-3 py-2 rounded-md text-sm font-medium"
              :class="isActive('/admin/raqis') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-50'"
            >
              Raqis
            </router-link>
            <router-link
              to="/admin/appointments"
              class="px-3 py-2 rounded-md text-sm font-medium"
              :class="isActive('/admin/appointments') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-50'"
            >
              Appointments
            </router-link>
          </template>

          <!-- Notifications -->
          <router-link
            :to="`/${userRole}/notifications`"
            class="px-3 py-2 rounded-md text-sm font-medium relative text-gray-700 hover:bg-gray-50"
          >
            🔔
            <span v-if="unreadCount" class="absolute top-1 right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
              {{ unreadCount }}
            </span>
          </router-link>

          <!-- User Menu -->
          <div class="relative ml-3">
            <button
              @click="showUserMenu = !showUserMenu"
              class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50"
            >
              {{ userName }}
            </button>
            <div v-if="showUserMenu" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
              <router-link to="/profile" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                Profile
              </router-link>
              <button
                @click="handleLogout"
                class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50"
              >
                Logout
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </nav>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import { logout, user, userRole } from '../store';

const showUserMenu = ref(false);
const router = useRouter();
const route = useRoute();
const unreadCount = ref(0);

const userName = computed(() => user.value?.full_name || 'User');

const isActive = (path) => {
  return route.path.startsWith(path);
};

const handleLogout = async () => {
  await logout();
  router.push('/login');
};
</script>
