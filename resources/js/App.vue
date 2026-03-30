<template>
  <div id="app" class="min-h-screen bg-gray-950 text-gray-100">
    <NavBar v-if="isAuthenticated && $route.name !== 'home'" />
    <router-view />
  </div>
</template>

<script setup>
import { useRouter } from 'vue-router';
import { isAuthenticated } from './store';
import NavBar from './components/NavBar.vue';

const router = useRouter();
</script>

<style>
:root {
  --gold: #c9a84c;
  --gold-light: #e8cc7a;
  --ink: #0e0b1a;
  --deep: #12102a;
  --void: #080614;
  --white: #f5f0e8;
}

#app {
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Oxygen', 'Ubuntu', 'Cantarell', 'Fira Sans', 'Droid Sans', 'Helvetica Neue', sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  background: linear-gradient(135deg, #0f172a 0%, #1a1a2e 100%);
  color: #f5f0e8;
}
</style>
