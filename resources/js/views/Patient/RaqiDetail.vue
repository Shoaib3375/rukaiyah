<template>
  <div class="page">
    <div class="page-inner-md">
      <router-link to="/raqis" class="back-link">← Browse Raqis</router-link>

      <div v-if="raqi">
        <div class="card-gold mb-4">
          <div class="flex items-start justify-between mb-4">
            <div class="flex items-center gap-4">
              <div class="w-14 h-14 rounded-full bg-gold/10 border border-gold/20 flex items-center justify-center text-gold text-xl font-semibold">
                {{ raqi.user.full_name[0] }}
              </div>
              <div>
                <h1 class="page-title mb-0">{{ raqi.user.full_name }}</h1>
                <p class="text-xs text-gold/60 mt-0.5">{{ raqi.specialization }}</p>
              </div>
            </div>
            <div class="text-right">
              <div class="flex justify-end gap-0.5 text-gold text-sm mb-1">
                <span v-for="i in 5" :key="i">{{ i <= Math.round(Number(raqi.rating) || 0) ? '★' : '☆' }}</span>
              </div>
              <div v-if="Number(raqi.total_reviews) > 0" class="text-gold text-lg leading-tight">{{ Number(raqi.rating).toFixed(1) }}</div>
              <div class="text-xs text-cream/30">
                {{ Number(raqi.total_reviews) > 0 ? `${raqi.total_reviews} review${Number(raqi.total_reviews) === 1 ? '' : 's'}` : 'No reviews yet' }}
              </div>
            </div>
          </div>

          <div class="divider"></div>

          <p class="sec-label">About</p>
          <p class="text-sm text-cream/55 leading-relaxed mb-4">{{ raqi.bio || 'No bio provided.' }}</p>

          <div v-if="raqi.languages" class="mb-4">
            <p class="sec-label">Languages</p>
            <p class="text-sm text-cream/55">{{ formatLanguages(raqi.languages) }}</p>
          </div>

          <div class="grid grid-cols-2 gap-3">
            <div class="p-3 rounded-lg bg-white/[0.03]">
              <p class="sec-label">Email</p>
              <p class="text-sm text-cream/65">{{ raqi.user.email }}</p>
            </div>
            <div class="p-3 rounded-lg bg-white/[0.03]">
              <p class="sec-label">Phone</p>
              <p class="text-sm text-cream/65">{{ raqi.user.phone }}</p>
            </div>
          </div>
        </div>

        <div v-if="reviewsList.length" class="card mb-4">
          <p class="sec-label mb-3">Patient reviews</p>
          <div class="space-y-4">
            <div v-for="rev in reviewsList" :key="rev.id" class="review-row">
              <div class="flex items-start justify-between gap-3 mb-2">
                <span class="text-sm text-cream/80 font-medium">{{ reviewerLabel(rev) }}</span>
                <div class="flex gap-0.5 text-gold text-xs shrink-0">
                  <span v-for="i in 5" :key="i">{{ i <= (rev.rating || 0) ? '★' : '☆' }}</span>
                </div>
              </div>
              <p v-if="rev.comment" class="text-sm text-cream/50 leading-relaxed italic">{{ rev.comment }}</p>
              <p v-else class="text-xs text-cream/25 italic">Rated {{ rev.rating }} stars</p>
              <p class="text-xs text-cream/20 mt-2">{{ formatReviewDate(rev.created_at) }}</p>
            </div>
          </div>
        </div>

        <div class="card mb-4">
          <p class="sec-label">Available Slots</p>
          <div v-if="availability.length" class="space-y-2">
            <div v-for="slot in availability" :key="slot.id"
              class="flex items-center justify-between py-3 border-b border-white/5 last:border-0">
              <div>
                <p class="text-sm text-cream/75">{{ slot.slot_start }} – {{ slot.slot_end }}</p>
                <p class="text-xs text-cream/30 capitalize">Day {{ slot.day_of_week }}</p>
              </div>
              <span class="badge badge-active">Open</span>
            </div>
          </div>
          <div v-else class="empty-state py-6">No availability slots listed</div>
        </div>

        <router-link :to="{ name: 'patient-book-appointment', query: { raqi_id: raqi.id } }" class="btn-gold w-full">
          Book Appointment
        </router-link>
      </div>

      <div v-else class="space-y-3">
        <div class="skeleton h-40"></div>
        <div class="skeleton h-32"></div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import { patientAPI } from '../../api';

const route = useRoute();
const raqi = ref(null);
const availability = ref([]);

const reviewsList = computed(() => raqi.value?.reviews || []);

function formatLanguages(raw) {
  if (!raw) return '';
  if (typeof raw === 'string' && raw.trim().startsWith('[')) {
    try {
      const p = JSON.parse(raw);
      return Array.isArray(p) ? p.join(', ') : raw;
    } catch {
      return raw;
    }
  }
  return raw;
}

function reviewerLabel(rev) {
  const name = rev.patient?.full_name?.trim();
  if (!name) return 'Verified patient';
  const parts = name.split(/\s+/);
  if (parts.length === 1) return parts[0];
  return `${parts[0]} ${parts[parts.length - 1].charAt(0)}.`;
}

function formatReviewDate(iso) {
  if (!iso) return '';
  return new Date(iso).toLocaleDateString(undefined, { year: 'numeric', month: 'short', day: 'numeric' });
}

onMounted(async () => {
  try {
    const r = await patientAPI.raqis.get(route.params.id);
    raqi.value = r.data.data;
    availability.value = raqi.value.availabilities || raqi.value.availability || [];
  } catch (e) { console.error(e); }
});
</script>

<style scoped>
@import '../../styles/app.css';
.text-cream { color: #f5f0e8; }
.text-gold { color: #c9a84c; }
.text-gold\/60 { color: rgba(201,168,76,0.6); }
.bg-gold\/10 { background: rgba(201,168,76,0.1); }
.border-gold\/20 { border-color: rgba(201,168,76,0.2); }
.w-full { width: 100%; }
.review-row {
  padding-bottom: 1rem;
  border-bottom: 1px solid rgba(255,255,255,0.06);
}
.review-row:last-child { border-bottom: none; padding-bottom: 0; }
</style>
