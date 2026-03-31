<template>
  <nav class="bg-gray-950 shadow-xl border-b border-yellow-600 border-opacity-20 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between h-16 items-center">
        <!-- Logo -->
        <div class="flex items-center py-2">
          <router-link to="/" class="text-2xl font-bold text-yellow-600 hover:text-yellow-500 transition-colors">
            🌙 Jetty Healing
          </router-link>
        </div>

        <!-- Navigation Links -->
        <div class="flex items-center space-x-2">
          <!-- Patient Links -->
          <template v-if="userRole === 'patient'">
            <router-link
              to="/patient"
              class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200"
              :class="isActive('/patient') ? 'bg-yellow-600 bg-opacity-20 text-yellow-500 shadow-md' : 'text-gray-300 hover:text-gray-100 hover:bg-gray-800'"
            >
              Dashboard
            </router-link>
            <router-link
              to="/patient/appointments"
              class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200"
              :class="isActive('/patient/appointments') ? 'bg-yellow-600 bg-opacity-20 text-yellow-500 shadow-md' : 'text-gray-300 hover:text-gray-100 hover:bg-gray-800'"
            >
              Appointments
            </router-link>
            <router-link
              to="/raqis"
              class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200"
              :class="isActive('/patient/raqis') ? 'bg-yellow-600 bg-opacity-20 text-yellow-500 shadow-md' : 'text-gray-300 hover:text-gray-100 hover:bg-gray-800'"
            >
              Browse Raqis
            </router-link>
            <router-link
              to="/patient/profile"
              class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200"
              :class="isActive('/patient/profile') ? 'bg-yellow-600 bg-opacity-20 text-yellow-500 shadow-md' : 'text-gray-300 hover:text-gray-100 hover:bg-gray-800'"
            >
              Profile
            </router-link>
          </template>

          <!-- Raqi Links -->
          <template v-else-if="userRole === 'raqi'">
            <router-link
              to="/raqi"
              class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200"
              :class="isActive('/raqi') ? 'bg-yellow-600 bg-opacity-20 text-yellow-500 shadow-md' : 'text-gray-300 hover:text-gray-100 hover:bg-gray-800'"
            >
              Dashboard
            </router-link>
            <router-link
              to="/raqi/appointments"
              class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200"
              :class="isActive('/raqi/appointments') ? 'bg-yellow-600 bg-opacity-20 text-yellow-500 shadow-md' : 'text-gray-300 hover:text-gray-100 hover:bg-gray-800'"
            >
              Appointments
            </router-link>
            <router-link
              to="/raqi/availability"
              class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200"
              :class="isActive('/raqi/availability') ? 'bg-yellow-600 bg-opacity-20 text-yellow-500 shadow-md' : 'text-gray-300 hover:text-gray-100 hover:bg-gray-800'"
            >
              Availability
            </router-link>
            <router-link
              to="/raqi/profile"
              class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200"
              :class="isActive('/raqi/profile') ? 'bg-yellow-600 bg-opacity-20 text-yellow-500 shadow-md' : 'text-gray-300 hover:text-gray-100 hover:bg-gray-800'"
            >
              Profile
            </router-link>
          </template>

          <!-- Admin Links -->
          <template v-else-if="userRole === 'admin'">
            <router-link
              to="/admin"
              class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200"
              :class="isActive('/admin') ? 'bg-yellow-600 bg-opacity-20 text-yellow-500 shadow-md' : 'text-gray-300 hover:text-gray-100 hover:bg-gray-800'"
            >
              Dashboard
            </router-link>
            <router-link
              to="/admin/users"
              class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200"
              :class="isActive('/admin/users') ? 'bg-yellow-600 bg-opacity-20 text-yellow-500 shadow-md' : 'text-gray-300 hover:text-gray-100 hover:bg-gray-800'"
            >
              Users
            </router-link>
            <router-link
              to="/admin/raqis"
              class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200"
              :class="isActive('/admin/raqis') ? 'bg-yellow-600 bg-opacity-20 text-yellow-500 shadow-md' : 'text-gray-300 hover:text-gray-100 hover:bg-gray-800'"
            >
              Raqis
            </router-link>
            <router-link
              to="/admin/appointments"
              class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200"
              :class="isActive('/admin/appointments') ? 'bg-yellow-600 bg-opacity-20 text-yellow-500 shadow-md' : 'text-gray-300 hover:text-gray-100 hover:bg-gray-800'"
            >
              Appointments
            </router-link>
          </template>

          <!-- Notifications -->
          <router-link
            :to="`/${userRole}/notifications`"
            class="px-4 py-2 rounded-lg text-sm font-medium relative text-gray-300 hover:text-gray-100 hover:bg-gray-800 transition-all duration-200"
          >
            🔔
            <span v-if="unreadCount" class="absolute top-0 right-0 bg-red-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-bold shadow-lg">
              {{ unreadCount }}
            </span>
          </router-link>

          <!-- User Menu -->
          <div class="relative ml-4">
            <button
              @click="showUserMenu = !showUserMenu"
              class="px-4 py-2 rounded-lg text-sm font-medium text-gray-300 hover:text-gray-100 hover:bg-gray-800 transition-all duration-200"
            >
              {{ userName }}
            </button>
            <div v-if="showUserMenu" class="absolute right-0 mt-2 w-48 bg-gray-900 rounded-lg shadow-2xl border border-yellow-600 border-opacity-30 py-1 z-50">
              <router-link to="/profile" class="block px-4 py-2 text-sm text-gray-300 hover:bg-yellow-600 hover:bg-opacity-20 hover:text-yellow-400 transition-colors duration-200">
                Profile
              </router-link>
              <button
                @click="handleLogout"
                class="block w-full text-left px-4 py-2 text-sm text-gray-300 hover:bg-red-600 hover:bg-opacity-20 hover:text-red-400 transition-colors duration-200 border-t border-gray-700"
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
