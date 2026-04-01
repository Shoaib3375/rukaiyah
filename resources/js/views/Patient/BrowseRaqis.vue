<template>
  <div class="page">
    <div class="page-inner">
      <p class="eyebrow">Directory</p>
      <h1 class="page-title">Browse Raqis</h1>
      <p class="page-sub">Find a certified Raqi for your healing journey</p>

      <div class="field mb-6" style="max-width:28rem">
        <input v-model="searchQuery" type="text" placeholder="Search by name or specialization…" />
      </div>

      <div v-if="loading" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        <div v-for="i in 6" :key="i" class="skeleton h-40"></div>
      </div>

      <div v-else-if="filteredRaqis.length" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        <router-link v-for="raqi in filteredRaqis" :key="raqi.id" :to="`/raqis/${raqi.id}`" class="card raqi-card">
          <div class="flex items-start justify-between mb-3">
            <div class="w-10 h-10 rounded-full bg-gold/10 border border-gold/20 flex items-center justify-center text-gold font-semibold text-sm">
              {{ raqi.user.full_name[0] }}
            </div>
            <span class="badge badge-active">Active</span>
          </div>
          <p class="font-medium text-cream/90 mb-0.5">{{ raqi.user.full_name }}</p>
          <p class="text-xs text-gold/60 mb-3">{{ raqi.specialization }}</p>
          <p v-if="raqi.bio" class="text-xs text-cream/35 leading-relaxed line-clamp-2 mb-3">{{ raqi.bio }}</p>
          <div class="flex items-center justify-between mt-auto pt-3 border-t border-white/5">
            <div class="flex flex-col gap-1">
              <div class="flex items-center gap-0.5 text-gold text-xs leading-none" :title="ratingSummary(raqi)">
                <span v-for="i in 5" :key="i">{{ i <= Math.round(Number(raqi.rating) || 0) ? '★' : '☆' }}</span>
              </div>
              <div v-if="Number(raqi.total_reviews) > 0" class="text-xs text-cream/50">
                <span class="text-cream/70 font-medium">{{ Number(raqi.rating).toFixed(1) }}</span>
                <span class="text-cream/25"> · {{ raqi.total_reviews }} review{{ Number(raqi.total_reviews) === 1 ? '' : 's' }}</span>
              </div>
              <span v-else class="text-xs text-cream/25">No reviews yet</span>
            </div>
            <span class="text-xs text-gold/50 shrink-0">View →</span>
          </div>
        </router-link>
      </div>

      <div v-else class="card empty-state">No Raqis found matching your search</div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { patientAPI } from '../../api';
import { unwrap } from '../../utils';

const raqis = ref([]);
const searchQuery = ref('');
const loading = ref(true);

const filteredRaqis = computed(() =>
  raqis.value.filter(r =>
    r.user.full_name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
    r.specialization?.toLowerCase().includes(searchQuery.value.toLowerCase())
  )
);

function ratingSummary(raqi) {
  const n = Number(raqi.total_reviews) || 0;
  if (!n) return 'No reviews yet';
  return `${Number(raqi.rating).toFixed(1)} average from ${n} review${n === 1 ? '' : 's'}`;
}

onMounted(async () => {
  try { const r = await patientAPI.raqis.list(); raqis.value = unwrap(r); }
  catch (e) { console.error(e); } finally { loading.value = false; }
});
</script>

<style scoped>
@import '../../styles/app.css';
.eyebrow { color: rgba(201,168,76,0.55); font-size: 0.65rem; letter-spacing: 0.4em; text-transform: uppercase; margin-bottom: 0.5rem; }
.text-cream { color: #f5f0e8; }
.text-gold { color: #c9a84c; }
.bg-gold\/10 { background: rgba(201,168,76,0.1); }
.border-gold\/20 { border-color: rgba(201,168,76,0.2); }
.text-gold\/60 { color: rgba(201,168,76,0.6); }
.text-gold\/50 { color: rgba(201,168,76,0.5); }
.raqi-card { display: flex; flex-direction: column; transition: all 0.25s; }
.raqi-card:hover { border-color: rgba(201,168,76,0.3); transform: translateY(-2px); }
.line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
</style>
