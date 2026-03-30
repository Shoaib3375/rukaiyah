<template>
  <div class="min-h-screen bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center py-12 px-4">
    <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-8">
      <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-blue-600">🌙 Jetty Healing</h1>
        <p class="text-gray-600 mt-2">Create Your Account</p>
      </div>

      <form @submit.prevent="handleRegister" class="space-y-3">
        <div v-if="error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded text-sm">
          {{ error }}
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
          <input
            v-model="form.full_name"
            type="text"
            required
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            placeholder="John Doe"
          />
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
          <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
          <input
            v-model="form.phone"
            type="tel"
            required
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            placeholder="+8801700000000"
          />
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Account Type</label>
          <select
            v-model="form.role"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
          >
            <option value="patient">Patient (Seeking Healing)</option>
            <option value="raqi">Raqi (Spiritual Healer)</option>
          </select>
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

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
          <input
            v-model="form.password_confirmation"
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
          {{ loading ? 'Creating account...' : 'Register' }}
        </button>
      </form>

      <div class="mt-6 text-center">
        <p class="text-gray-600 text-sm">
          Already have an account?
          <router-link to="/login" class="text-blue-600 hover:underline font-medium">
            Login here
          </router-link>
        </p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue';
import { useRouter } from 'vue-router';
import { register, userRole } from '../../store';

const router = useRouter();
const loading = ref(false);
const error = ref('');

const form = reactive({
  full_name: '',
  email: '',
  phone: '',
  role: 'patient',
  password: '',
  password_confirmation: ''
});

const handleRegister = async () => {
  if (form.password !== form.password_confirmation) {
    error.value = 'Passwords do not match';
    return;
  }

  loading.value = true;
  error.value = '';

  const result = await register({ ...form });

  if (result.success) {
    const redirect = userRole.value === 'patient' ? '/patient' : userRole.value === 'raqi' ? '/raqi' : '/admin';
    router.push(redirect);
  } else {
    error.value = result.error;
    loading.value = false;
  }
};
</script>
