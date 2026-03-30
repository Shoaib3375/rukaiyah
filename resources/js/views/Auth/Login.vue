<template>
  <div class="min-h-screen bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center py-12 px-4">
    <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-8">
      <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-blue-600">🌙 Jetty Healing</h1>
        <p class="text-gray-600 mt-2">Spiritual Healing Platform</p>
      </div>

      <form @submit.prevent="handleLogin" class="space-y-4">
        <div v-if="error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
          {{ error }}
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
          <input
            v-model="form.email"
            type="email"
            required
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            placeholder="you@example.com"
          />
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
          <input
            v-model="form.password"
            type="password"
            required
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            placeholder="••••••••"
          />
        </div>

        <button
          :disabled="loading"
          class="w-full bg-blue-600 text-white py-2 rounded-lg font-medium hover:bg-blue-700 disabled:opacity-50"
        >
          {{ loading ? 'Logging in...' : 'Login' }}
        </button>
      </form>

      <div class="mt-6 text-center">
        <p class="text-gray-600 text-sm">
          Don't have an account?
          <router-link to="/register" class="text-blue-600 hover:underline font-medium">
            Register here
          </router-link>
        </p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import { login, userRole } from '../../store';

const router = useRouter();
const route = useRoute();
const loading = ref(false);
const error = ref('');

const form = reactive({
  email: '',
  password: ''
});

const handleLogin = async () => {
  loading.value = true;
  error.value = '';

  const result = await login(form.email, form.password);

  if (result.success) {
    const redirect = route.query.redirect || (
      userRole.value === 'patient' ? '/patient' :
      userRole.value === 'raqi' ? '/raqi' :
      '/admin'
    );
    router.push(redirect);
  } else {
    error.value = result.error;
    loading.value = false;
  }
};
</script>
