<template>
  <div class="page">
    <div class="page-inner">
      <p class="eyebrow">Raqi</p>
      <h1 class="page-title">Availability</h1>
      <p class="page-sub">Set your weekly schedule — prayer times are automatically blocked</p>

      <!-- Prayer times banner -->
      <div v-if="prayerLoading" class="prayer-banner mb-6">
        <span class="spinner-sm"></span>
        <span class="text-xs text-cream/40">Fetching prayer times for your location…</span>
      </div>
      <div v-else-if="prayerError" class="alert-error mb-6 flex items-center gap-2">
        <span>🕌</span> {{ prayerError }}
        <button @click="fetchPrayerTimes" class="ml-auto text-xs underline opacity-60 hover:opacity-100">Retry</button>
      </div>
      <div v-else-if="prayerTimes" class="prayer-banner mb-6">
        <span class="text-gold/70 text-xs">🕌 Prayer times loaded for {{ locationName }}</span>
        <div class="flex flex-wrap gap-3 mt-2">
          <div v-for="p in displayPrayers" :key="p.name" class="prayer-pill">
            <span class="text-cream/40 text-xs">{{ p.name }}</span>
            <span class="text-cream/70 text-xs font-medium">{{ p.time }}</span>
            <span class="text-cream/25 text-xs">±15 min blocked</span>
          </div>
        </div>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">

        <!-- Form -->
        <div class="lg:col-span-2 card-gold">
          <p class="sec-label mb-4">Add Time Range</p>

          <div v-if="errors.length" class="alert-error mb-4">
            <p v-for="e in errors" :key="e">{{ e }}</p>
          </div>

          <form @submit.prevent="handleGenerate" class="space-y-4">

            <div class="field">
              <label>Day of Week</label>
              <select v-model="form.day_of_week">
                <option v-for="(d, i) in days" :key="i" :value="i">{{ d }}</option>
              </select>
            </div>

            <div class="grid grid-cols-2 gap-3">
              <div class="field">
                <label>From</label>
                <input v-model="form.from" type="time" required />
              </div>
              <div class="field">
                <label>To</label>
                <input v-model="form.to" type="time" required />
              </div>
            </div>

            <div class="field">
              <label>Slot Duration</label>
              <select v-model.number="form.interval">
                <option :value="30">30 minutes</option>
                <option :value="45">45 minutes</option>
                <option :value="60">1 hour</option>
                <option :value="90">1.5 hours</option>
                <option :value="120">2 hours</option>
              </select>
            </div>

            <!-- Preview -->
            <div v-if="preview.length" class="p-3 rounded-lg bg-white/[0.03] border border-white/5">
              <p class="sec-label mb-2">Preview — {{ availableSlots.length }} available, {{ blockedSlots.length }} blocked</p>
              <div class="flex flex-wrap gap-2">
                <div v-for="s in preview" :key="s.slot_start"
                  class="preview-slot"
                  :class="s.blocked ? 'blocked' : 'open'"
                  :title="s.blocked ? `Blocked: ${s.blockedBy} prayer (±15 min)` : ''">
                  <span>{{ s.slot_start }}</span>
                  <span v-if="s.blocked" class="block-label">🕌 {{ s.blockedBy }}</span>
                </div>
              </div>
            </div>

            <button type="submit" :disabled="saving || !availableSlots.length" class="btn-gold w-full">
              <span v-if="saving" class="spinner"></span>
              {{ saving ? 'Saving…' : `Save ${availableSlots.length} Slot${availableSlots.length !== 1 ? 's' : ''}` }}
            </button>
          </form>
        </div>

        <!-- Schedule -->
        <div class="lg:col-span-3 card">
          <p class="sec-label mb-4">Your Schedule</p>

          <div v-if="availability.length">
            <div v-for="(slots, day) in groupedByDay" :key="day" class="mb-5">
              <p class="day-label">{{ day }}</p>
              <div class="space-y-1">
                <div v-for="slot in slots" :key="slot.id"
                  class="slot-row"
                  :class="{ 'is-blocked': slot.is_blocked }">
                  <div class="flex items-center gap-2">
                    <span class="text-sm text-cream/65">{{ slot.slot_start.slice(0,5) }} – {{ slot.slot_end.slice(0,5) }}</span>
                    <span v-if="slot.is_blocked" class="badge-prayer">🕌 Prayer</span>
                  </div>
                  <button @click="deleteSlot(slot.id)" class="del-btn">✕</button>
                </div>
              </div>
            </div>
          </div>

          <div v-else class="empty-state">No availability set yet</div>
        </div>

      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue';
import { raqiAPI } from '../../api';
import { unwrap } from '../../utils';

const days = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
const PRAYER_NAMES = ['Fajr','Dhuhr','Asr','Maghrib','Isha'];
const BUFFER = 15; // minutes

const loading   = ref(false);
const saving    = ref(false);
const availability = ref([]);
const errors    = ref([]);
const prayerLoading = ref(false);
const prayerError   = ref('');
const prayerTimes   = ref(null); // { Fajr: '05:12', Dhuhr: '12:30', ... }
const locationName  = ref('');

const form = reactive({ day_of_week: 1, from: '09:00', to: '17:00', interval: 60 });

// ── Helpers ──────────────────────────────────────────────
const toMin = (hhmm) => {
  const [h, m] = hhmm.slice(0, 5).split(':').map(Number);
  return h * 60 + m;
};
const toHHMM = (min) =>
  `${String(Math.floor(min / 60)).padStart(2,'0')}:${String(min % 60).padStart(2,'0')}`;

// Returns the prayer name if a slot overlaps its ±15 min window, else null
const blockedByPrayer = (slotStartMin, slotEndMin) => {
  if (!prayerTimes.value) return null;
  for (const name of PRAYER_NAMES) {
    const pt = prayerTimes.value[name];
    if (!pt) continue;
    const pm = toMin(pt);
    if (slotStartMin < pm + BUFFER && slotEndMin > pm - BUFFER) return name;
  }
  return null;
};

// ── Prayer times via Aladhan API ─────────────────────────
const fetchPrayerTimes = async () => {
  prayerLoading.value = true;
  prayerError.value = '';
  try {
    const pos = await new Promise((res, rej) =>
      navigator.geolocation.getCurrentPosition(res, rej, { timeout: 8000 })
    );
    const { latitude: lat, longitude: lng } = pos.coords;

    // Reverse geocode for display name
    try {
      const geo = await fetch(`https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json`);
      const geoData = await geo.json();
      locationName.value = geoData.address?.city || geoData.address?.town || geoData.address?.state || 'your location';
    } catch { locationName.value = 'your location'; }

    // Fetch prayer times
    const today = new Date();
    const dd = String(today.getDate()).padStart(2,'0');
    const mm = String(today.getMonth() + 1).padStart(2,'0');
    const yyyy = today.getFullYear();
    const res = await fetch(
      `https://api.aladhan.com/v1/timings/${dd}-${mm}-${yyyy}?latitude=${lat}&longitude=${lng}&method=2`
    );
    const data = await res.json();
    if (data.code !== 200) throw new Error('Prayer API error');

    const t = data.data.timings;
    prayerTimes.value = {
      Fajr:    t.Fajr.slice(0,5),
      Dhuhr:   t.Dhuhr.slice(0,5),
      Asr:     t.Asr.slice(0,5),
      Maghrib: t.Maghrib.slice(0,5),
      Isha:    t.Isha.slice(0,5),
    };
  } catch (e) {
    if (e.code === 1) prayerError.value = 'Location access denied — prayer blocking disabled';
    else prayerError.value = 'Could not fetch prayer times — slots will not be blocked';
  } finally {
    prayerLoading.value = false;
  }
};

// ── Computed ─────────────────────────────────────────────
const displayPrayers = computed(() =>
  prayerTimes.value
    ? PRAYER_NAMES.map(n => ({ name: n, time: prayerTimes.value[n] }))
    : []
);

const preview = computed(() => {
  if (!form.from || !form.to) return [];
  const slots = [];
  let cur = toMin(form.from);
  const end = toMin(form.to);
  while (cur + form.interval <= end) {
    const slotEnd = cur + form.interval;
    const blocked = blockedByPrayer(cur, slotEnd);
    slots.push({
      slot_start: toHHMM(cur),
      slot_end:   toHHMM(slotEnd),
      blocked:    !!blocked,
      blockedBy:  blocked,
    });
    cur += form.interval;
  }
  return slots;
});

const availableSlots = computed(() => preview.value.filter(s => !s.blocked));
const blockedSlots   = computed(() => preview.value.filter(s => s.blocked));

const groupedByDay = computed(() => {
  const groups = {};
  for (const slot of availability.value) {
    const name = days[slot.day_of_week];
    if (!groups[name]) groups[name] = [];
    groups[name].push(slot);
  }
  for (const d in groups)
    groups[d].sort((a, b) => a.slot_start.localeCompare(b.slot_start));
  return groups;
});

// ── Lifecycle ─────────────────────────────────────────────
onMounted(async () => {
  try { const r = await raqiAPI.availability.list(); availability.value = unwrap(r); }
  catch (e) { console.error(e); }
  fetchPrayerTimes();
});

// ── Actions ───────────────────────────────────────────────
const handleGenerate = async () => {
  errors.value = [];
  if (!availableSlots.value.length) { errors.value = ['No available slots — all are blocked by prayer times']; return; }

  saving.value = true;
  const created = [];

  for (const slot of availableSlots.value) {
    try {
      const r = await raqiAPI.availability.create({
        day_of_week: form.day_of_week,
        slot_start:  slot.slot_start,
        slot_end:    slot.slot_end,
      });
      created.push(r.data.data);
    } catch (e) {
      const status = e.response?.status;
      if (status === 422) {
        const errs = e.response?.data?.errors;
        const first = errs ? Object.values(errs)[0]?.[0] : e.response?.data?.message;
        errors.value = [first || 'Validation failed'];
        saving.value = false;
        return;
      }
      // 409/unique duplicate — skip silently
    }
  }

  availability.value.push(...created);
  saving.value = false;
};

const deleteSlot = async (id) => {
  try {
    await raqiAPI.availability.delete(id);
    availability.value = availability.value.filter(s => s.id !== id);
  } catch (e) { alert('Failed to remove slot'); }
};
</script>

<style scoped>
@import '../../styles/app.css';
.eyebrow { color: rgba(201,168,76,0.55); font-size: 0.65rem; letter-spacing: 0.4em; text-transform: uppercase; margin-bottom: 0.5rem; }
.text-cream { color: #f5f0e8; }
.text-gold\/70 { color: rgba(201,168,76,0.7); }
.w-full { width: 100%; }

/* Prayer banner */
.prayer-banner {
  background: rgba(201,168,76,0.04);
  border: 1px solid rgba(201,168,76,0.15);
  border-radius: 0.75rem;
  padding: 1rem 1.25rem;
}
.prayer-pill {
  display: flex;
  flex-direction: column;
  gap: 0.1rem;
  background: rgba(245,240,232,0.03);
  border: 1px solid rgba(245,240,232,0.07);
  border-radius: 0.5rem;
  padding: 0.4rem 0.75rem;
  min-width: 5rem;
}

/* Preview slots */
.preview-slot {
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 0.3rem 0.6rem;
  border-radius: 0.4rem;
  font-size: 0.72rem;
  border: 1px solid;
  transition: all 0.15s;
}
.preview-slot.open {
  background: rgba(34,197,94,0.07);
  border-color: rgba(34,197,94,0.2);
  color: rgba(134,239,172,0.8);
}
.preview-slot.blocked {
  background: rgba(201,168,76,0.06);
  border-color: rgba(201,168,76,0.18);
  color: rgba(201,168,76,0.5);
  opacity: 0.7;
}
.block-label { font-size: 0.6rem; color: rgba(201,168,76,0.5); margin-top: 0.1rem; }

/* Schedule rows */
.day-label { font-size: 0.7rem; font-weight: 500; color: rgba(201,168,76,0.65); margin-bottom: 0.4rem; letter-spacing: 0.05em; }
.slot-row {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0.45rem 0.75rem;
  border-radius: 0.5rem;
  background: rgba(245,240,232,0.02);
  border: 1px solid rgba(245,240,232,0.05);
  transition: border-color 0.2s;
}
.slot-row:hover { border-color: rgba(201,168,76,0.15); }
.slot-row.is-blocked {
  background: rgba(201,168,76,0.04);
  border-color: rgba(201,168,76,0.12);
}
.badge-prayer {
  font-size: 0.6rem;
  padding: 0.1rem 0.4rem;
  border-radius: 2rem;
  background: rgba(201,168,76,0.1);
  border: 1px solid rgba(201,168,76,0.2);
  color: rgba(201,168,76,0.7);
}
.del-btn { font-size: 0.7rem; color: rgba(239,68,68,0.35); transition: color 0.2s; background: none; border: none; cursor: pointer; }
.del-btn:hover { color: rgba(239,68,68,0.8); }

/* Spinner */
.spinner { width:14px;height:14px;border:2px solid rgba(8,6,20,0.3);border-top-color:#080614;border-radius:50%;animation:spin .7s linear infinite;display:inline-block; }
.spinner-sm { width:10px;height:10px;border:1.5px solid rgba(245,240,232,0.1);border-top-color:rgba(245,240,232,0.4);border-radius:50%;animation:spin .7s linear infinite;display:inline-block; }
@keyframes spin{to{transform:rotate(360deg)}}
</style>
