<template>
  <div id="app" class="min-h-screen bg-gray-50">
    <NavBar v-if="isAuthenticated" />
    <router-view />
  </div>
</template>

<script setup>
import { computed, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { isAuthenticated, initAuth } from './store';
import NavBar from './components/NavBar.vue';

const router = useRouter();

onMounted(async () => {
  await initAuth();
  // Redirect to login if not authenticated and not on auth pages
  if (!isAuthenticated.value && !['login', 'register', 'invite'].includes(router.currentRoute.value.name)) {
    router.push('/login');
  }
});
</script>

<style scoped>
#app {
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
}
</style>
