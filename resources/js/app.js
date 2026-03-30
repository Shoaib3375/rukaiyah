import './bootstrap';
import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import { initializeReverb } from './reverb';

const app = createApp(App);

app.use(router);
app.mount('#app');

// Initialize Reverb for real-time events
initializeReverb();

