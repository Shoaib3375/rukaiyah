<template>
  <div class="auth-page">

    <!-- Orbs -->
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>

    <!-- Stars -->
    <div class="stars" aria-hidden="true">
      <span v-for="i in 40" :key="i" class="star" :style="starStyle(i)"></span>
    </div>

    <!-- Back to home -->
    <router-link to="/" class="back-link">
      <span>☽</span> Rukaiyah
    </router-link>

    <div class="auth-card">

      <!-- Header -->
      <div class="text-center mb-8">
        <p class="eyebrow">Welcome</p>
        <h1 class="text-2xl font-light text-cream">Create your account</h1>
        <p class="text-cream/30 text-sm mt-2">Begin your healing journey</p>
      </div>

      <!-- Error -->
      <div v-if="error" class="error-box">
        {{ error }}
      </div>

      <!-- Form -->
      <form @submit.prevent="handleRegister" class="space-y-4">

        <div class="field">
          <label>Full Name</label>
          <input v-model="form.full_name" type="text" required placeholder="Ahmad Rahman" />
        </div>

        <div class="field">
          <label>Email</label>
          <input v-model="form.email" type="email" required placeholder="you@example.com" />
        </div>

        <div class="field">
          <label>Phone</label>
          <input v-model="form.phone" type="tel" required placeholder="+8801700000000" />
        </div>

        <div class="field">
          <label>I am a</label>
          <select v-model="form.role">
            <option value="patient">Patient — Seeking Healing</option>
            <option value="raqi">Raqi — Spiritual Healer</option>
          </select>
        </div>

        <div class="field">
          <label>Password</label>
          <input v-model="form.password" type="password" required placeholder="••••••••" />
        </div>

        <div class="field">
          <label>Confirm Password</label>
          <input v-model="form.password_confirmation" type="password" required placeholder="••••••••" />
        </div>

        <button type="submit" :disabled="loading" class="submit-btn">
          <span v-if="loading" class="spinner"></span>
          {{ loading ? 'Creating account…' : 'Begin Journey' }}
        </button>

      </form>

      <p class="text-center text-cream/30 text-xs mt-6">
        Already have an account?
        <router-link to="/login" class="text-gold hover:text-gold-light transition-colors">Sign in</router-link>
      </p>

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

const starStyle = (i) => ({
  left: `${(i * 137.508) % 100}%`,
  top: `${(i * 97.3) % 100}%`,
  width: `${1 + (i % 3)}px`,
  height: `${1 + (i % 3)}px`,
  animationDelay: `${(i * 0.3) % 4}s`,
  animationDuration: `${3 + (i % 4)}s`,
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
    router.push(userRole.value === 'patient' ? '/patient' : userRole.value === 'raqi' ? '/raqi' : '/admin');
  } else {
    error.value = result.error;
    loading.value = false;
  }
};
</script>

<style scoped>
.auth-page {
  min-height: 100vh;
  background: #080614;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 2rem 1rem;
  position: relative;
  overflow: hidden;
}

.back-link {
  position: fixed;
  top: 1.5rem;
  left: 2rem;
  display: flex;
  align-items: center;
  gap: 0.5rem;
  color: rgba(201,168,76,0.6);
  font-size: 0.8rem;
  letter-spacing: 0.2em;
  text-transform: uppercase;
  transition: color 0.2s;
  z-index: 10;
}
.back-link:hover { color: #c9a84c; }

/* Orbs */
.orb { position: absolute; border-radius: 50%; filter: blur(80px); pointer-events: none; }
.orb-1 { width: 500px; height: 500px; background: rgba(59,45,122,0.4); top: -150px; left: -150px; }
.orb-2 { width: 350px; height: 350px; background: rgba(201,168,76,0.07); bottom: -100px; right: -100px; }
.orb-3 { width: 250px; height: 250px; background: rgba(26,74,74,0.3); top: 50%; left: 60%; }

/* Stars */
.stars { position: absolute; inset: 0; pointer-events: none; }
.star {
  position: absolute;
  background: #f5f0e8;
  border-radius: 50%;
  opacity: 0;
  animation: twinkle var(--dur, 3s) ease-in-out infinite;
}
@keyframes twinkle { 0%,100%{opacity:0} 50%{opacity:0.5} }

/* Card */
.auth-card {
  position: relative;
  z-index: 10;
  width: 100%;
  max-width: 420px;
  background: rgba(245,240,232,0.02);
  border: 1px solid rgba(201,168,76,0.15);
  border-radius: 1.25rem;
  padding: 2.5rem 2rem;
  backdrop-filter: blur(12px);
  animation: slideUp 0.5s ease both;
}
@keyframes slideUp { from{opacity:0;transform:translateY(20px)} to{opacity:1;transform:translateY(0)} }

.eyebrow {
  color: rgba(201,168,76,0.55);
  font-size: 0.65rem;
  letter-spacing: 0.4em;
  text-transform: uppercase;
  margin-bottom: 0.5rem;
}
.text-cream { color: #f5f0e8; }
.text-gold { color: #c9a84c; }
.text-gold-light { color: #e8cc7a; }

/* Fields */
.field { display: flex; flex-direction: column; gap: 0.4rem; }
.field label {
  font-size: 0.7rem;
  letter-spacing: 0.1em;
  text-transform: uppercase;
  color: rgba(245,240,232,0.35);
}
.field input,
.field select {
  background: rgba(245,240,232,0.04);
  border: 1px solid rgba(245,240,232,0.1);
  border-radius: 0.5rem;
  padding: 0.65rem 0.9rem;
  color: #f5f0e8;
  font-size: 0.875rem;
  outline: none;
  transition: border-color 0.2s, background 0.2s;
  width: 100%;
}
.field input::placeholder { color: rgba(245,240,232,0.2); }
.field input:focus,
.field select:focus {
  border-color: rgba(201,168,76,0.45);
  background: rgba(201,168,76,0.04);
}
.field select option { background: #12102a; color: #f5f0e8; }

/* Submit */
.submit-btn {
  width: 100%;
  padding: 0.8rem;
  background: linear-gradient(135deg, #c9a84c, #b89940);
  color: #080614;
  border-radius: 0.5rem;
  font-size: 0.8rem;
  font-weight: 600;
  letter-spacing: 0.1em;
  text-transform: uppercase;
  transition: all 0.25s;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  margin-top: 0.5rem;
  box-shadow: 0 0 30px rgba(201,168,76,0.15);
}
.submit-btn:hover:not(:disabled) {
  transform: translateY(-1px);
  box-shadow: 0 0 40px rgba(201,168,76,0.3);
}
.submit-btn:disabled { opacity: 0.5; cursor: not-allowed; }

/* Spinner */
.spinner {
  width: 14px; height: 14px;
  border: 2px solid rgba(8,6,20,0.3);
  border-top-color: #080614;
  border-radius: 50%;
  animation: spin 0.7s linear infinite;
}
@keyframes spin { to { transform: rotate(360deg); } }

/* Error */
.error-box {
  background: rgba(239,68,68,0.08);
  border: 1px solid rgba(239,68,68,0.25);
  color: rgba(252,165,165,0.9);
  border-radius: 0.5rem;
  padding: 0.75rem 1rem;
  font-size: 0.8rem;
  margin-bottom: 1rem;
}
</style>
