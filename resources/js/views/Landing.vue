<template>
  <div class="landing">

    <!-- Nav -->
    <header class="fixed top-0 left-0 right-0 z-50 flex items-center justify-between px-8 py-5 transition-all duration-500" :class="scrolled ? 'bg-void/90 backdrop-blur-md border-b border-gold/10' : ''">
      <div class="flex items-center gap-3">
        <span class="text-2xl">☽</span>
        <span class="text-gold font-semibold tracking-widest text-sm uppercase">Rukaiyah</span>
      </div>
      <div class="flex items-center gap-3">
        <router-link to="/login" class="text-cream/60 hover:text-cream text-sm tracking-wide transition-colors duration-200">
          Sign in
        </router-link>
        <router-link to="/register" class="btn-gold text-sm">
          Begin Journey
        </router-link>
      </div>
    </header>

    <!-- Hero -->
    <section class="hero-section relative flex flex-col items-center justify-center text-center px-6 min-h-screen overflow-hidden">

      <!-- Ambient orbs -->
      <div class="orb orb-1"></div>
      <div class="orb orb-2"></div>
      <div class="orb orb-3"></div>

      <!-- Stars -->
      <div class="stars" aria-hidden="true">
        <span v-for="i in 60" :key="i" class="star" :style="starStyle(i)"></span>
      </div>

      <!-- Arabic calligraphy watermark -->
      <div class="absolute inset-0 flex items-center justify-center pointer-events-none select-none opacity-[0.03]">
        <span class="text-cream font-serif" style="font-size: clamp(12rem, 40vw, 32rem); line-height:1;">بِسْمِ</span>
      </div>

      <div class="relative z-10 max-w-4xl mx-auto">
        <p class="text-gold/70 tracking-[0.4em] text-xs uppercase mb-8 animate-fade-in">Spiritual Healing Platform</p>

        <h1 class="hero-title mb-8 animate-slide-up">
          Healing through<br/>
          <span class="text-gradient">Sacred Recitation</span>
        </h1>

        <p class="text-cream/50 text-lg leading-relaxed max-w-xl mx-auto mb-14 animate-slide-up" style="animation-delay:0.1s">
          Connect with certified Raqis for Ruqyah sessions — video, voice, or in-person. Rooted in Quran and Sunnah.
        </p>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-4 animate-slide-up" style="animation-delay:0.2s">
          <router-link to="/raqis" class="btn-gold-lg">
            Find a Raqi
          </router-link>
          <router-link to="/register?role=raqi" class="btn-ghost-lg">
            Join as Raqi
          </router-link>
        </div>
      </div>

      <!-- Scroll hint -->
      <div class="absolute bottom-10 left-1/2 -translate-x-1/2 flex flex-col items-center gap-2 opacity-30 animate-bounce">
        <span class="text-cream/60 text-xs tracking-widest uppercase">Scroll</span>
        <div class="w-px h-10 bg-gradient-to-b from-gold to-transparent"></div>
      </div>
    </section>

    <!-- Divider -->
    <div class="divider-line"></div>

    <!-- How it works -->
    <section class="section-pad relative overflow-hidden">
      <div class="orb orb-4"></div>

      <div class="max-w-6xl mx-auto px-6">
        <div class="text-center mb-20">
          <p class="eyebrow">The Path</p>
          <h2 class="section-title">How it works</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-px bg-gold/10 rounded-2xl overflow-hidden">
          <div v-for="(step, i) in steps" :key="i" class="step-card group">
            <div class="step-number">{{ String(i + 1).padStart(2, '0') }}</div>
            <div class="text-3xl mb-5">{{ step.icon }}</div>
            <h3 class="text-cream font-semibold text-lg mb-3">{{ step.title }}</h3>
            <p class="text-cream/40 text-sm leading-relaxed">{{ step.desc }}</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Divider -->
    <div class="divider-line"></div>

    <!-- Session types -->
    <section class="section-pad relative">
      <div class="max-w-6xl mx-auto px-6">
        <div class="text-center mb-20">
          <p class="eyebrow">Sessions</p>
          <h2 class="section-title">Choose your format</h2>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
          <div v-for="type in sessionTypes" :key="type.label" class="type-card group">
            <div class="text-4xl mb-4 group-hover:scale-110 transition-transform duration-300">{{ type.icon }}</div>
            <div class="text-cream/80 font-medium text-sm tracking-wide">{{ type.label }}</div>
            <div class="text-cream/30 text-xs mt-1">{{ type.sub }}</div>
          </div>
        </div>
      </div>
    </section>

    <!-- Divider -->
    <div class="divider-line"></div>

    <!-- Features -->
    <section class="section-pad relative overflow-hidden">
      <div class="orb orb-5"></div>
      <div class="max-w-6xl mx-auto px-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">

          <div>
            <p class="eyebrow">Why Rukaiyah</p>
            <h2 class="section-title text-left">Built for the<br/><span class="text-gradient">soul's journey</span></h2>
            <p class="text-cream/40 mt-6 leading-relaxed">Every feature is designed with intention — from the moment you seek help to the moment you find peace.</p>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div v-for="feat in features" :key="feat.title" class="feature-card">
              <div class="text-2xl mb-3">{{ feat.icon }}</div>
              <div class="text-cream/80 font-medium text-sm mb-1">{{ feat.title }}</div>
              <div class="text-cream/35 text-xs leading-relaxed">{{ feat.desc }}</div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Divider -->
    <div class="divider-line"></div>

    <!-- Testimonials -->
    <section class="section-pad relative">
      <div class="max-w-5xl mx-auto px-6">
        <div class="text-center mb-20">
          <p class="eyebrow">Voices</p>
          <h2 class="section-title">What they say</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <div v-for="t in testimonials" :key="t.name" class="testimonial-card">
            <div class="text-gold/40 text-5xl font-serif leading-none mb-4">"</div>
            <p class="text-cream/60 text-sm leading-relaxed mb-6">{{ t.text }}</p>
            <div class="flex items-center gap-3 border-t border-gold/10 pt-4">
              <div class="w-8 h-8 rounded-full bg-gold/20 flex items-center justify-center text-gold text-xs font-bold">
                {{ t.name[0] }}
              </div>
              <div>
                <div class="text-cream/70 text-xs font-medium">{{ t.name }}</div>
                <div class="text-cream/30 text-xs">{{ t.role }}</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA -->
    <section class="section-pad relative overflow-hidden">
      <div class="orb orb-6"></div>
      <div class="max-w-3xl mx-auto px-6 text-center">
        <div class="cta-card">
          <p class="text-gold/60 tracking-[0.3em] text-xs uppercase mb-6">Begin Today</p>
          <h2 class="text-3xl md:text-5xl font-light text-cream mb-6 leading-tight">
            Your healing<br/>starts with one step
          </h2>
          <p class="text-cream/40 mb-10 text-sm leading-relaxed max-w-md mx-auto">
            Join thousands who have found clarity, peace, and spiritual strength through Ruqyah.
          </p>
          <router-link to="/register" class="btn-gold-lg">
            Create Free Account
          </router-link>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <footer class="border-t border-gold/10 py-12 px-8">
      <div class="max-w-6xl mx-auto flex flex-col md:flex-row items-center justify-between gap-6">
        <div class="flex items-center gap-3">
          <span class="text-xl">☽</span>
          <span class="text-gold/60 text-sm tracking-widest uppercase">Rukaiyah</span>
        </div>
        <p class="text-cream/20 text-xs tracking-wide">© 2026 Rukaiyah · All rights reserved</p>
        <div class="flex gap-6">
          <router-link to="/login" class="text-cream/30 hover:text-cream/60 text-xs transition-colors">Sign in</router-link>
          <router-link to="/register" class="text-cream/30 hover:text-cream/60 text-xs transition-colors">Register</router-link>
        </div>
      </div>
    </footer>

  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';

const scrolled = ref(false);

const onScroll = () => { scrolled.value = window.scrollY > 40; };
onMounted(() => window.addEventListener('scroll', onScroll));
onUnmounted(() => window.removeEventListener('scroll', onScroll));

const starStyle = (i) => ({
  left: `${(i * 137.508) % 100}%`,
  top: `${(i * 97.3) % 100}%`,
  width: `${1 + (i % 3)}px`,
  height: `${1 + (i % 3)}px`,
  animationDelay: `${(i * 0.3) % 4}s`,
  animationDuration: `${3 + (i % 4)}s`,
});

const steps = [
  { icon: '🔍', title: 'Browse Raqis', desc: 'Explore verified, admin-approved Raqis filtered by specialization, language, and availability.' },
  { icon: '📅', title: 'Book a Session', desc: 'Choose your preferred format — video, phone, chat, or in-person — and pick a time that suits you.' },
  { icon: '✨', title: 'Begin Healing', desc: 'Connect with your Raqi in a private, secure session. Receive follow-ups and session notes.' },
];

const sessionTypes = [
  { icon: '🎥', label: 'Video', sub: 'Jitsi Meet room' },
  { icon: '💬', label: 'Chat', sub: 'Real-time messages' },
  { icon: '📞', label: 'Phone', sub: 'Voice call' },
  { icon: '🕌', label: 'In Person', sub: 'Physical meeting' },
];

const features = [
  { icon: '🛡️', title: 'Verified Raqis', desc: 'Every Raqi is manually reviewed and approved by our admin team.' },
  { icon: '👥', title: 'Multi-Raqi Sessions', desc: 'Lead Raqis can invite co-Raqis to collaborate on complex cases.' },
  { icon: '📝', title: 'Session Notes', desc: 'Structured logs — observations, Ruqyah recitations, recommendations.' },
  { icon: '🔔', title: 'Real-time Updates', desc: 'Live notifications and session events via WebSocket.' },
  { icon: '🔒', title: 'Private & Secure', desc: 'JWT authentication. Your data and sessions stay private.' },
  { icon: '⭐', title: 'Honest Reviews', desc: 'Post-session reviews help the community find the best Raqis.' },
];

const testimonials = [
  { name: 'Aisha M.', role: 'Patient', text: 'I had been struggling for years. After just three sessions with my Raqi, I felt a weight lift from my chest. This platform made it so easy to find the right person.' },
  { name: 'Ibrahim K.', role: 'Raqi', text: 'The multi-Raqi feature is incredible. I can now collaborate with senior Raqis on difficult cases. The session notes keep everything organized.' },
  { name: 'Fatima R.', role: 'Patient', text: 'The video sessions feel intimate and safe. My Raqi sends follow-up notes after every session. I finally feel heard and guided.' },
];
</script>

<style scoped>
.landing {
  background: #080614;
  color: #f5f0e8;
  min-height: 100vh;
}

/* ── Buttons ── */
.btn-gold {
  padding: 0.5rem 1.25rem;
  background: rgba(201,168,76,0.12);
  border: 1px solid rgba(201,168,76,0.35);
  color: #c9a84c;
  border-radius: 0.5rem;
  font-size: 0.8rem;
  letter-spacing: 0.05em;
  transition: all 0.2s;
}
.btn-gold:hover {
  background: rgba(201,168,76,0.22);
  border-color: rgba(201,168,76,0.6);
  color: #e8cc7a;
}
.btn-gold-lg {
  display: inline-block;
  padding: 0.85rem 2.5rem;
  background: linear-gradient(135deg, #c9a84c, #b89940);
  color: #080614;
  border-radius: 0.5rem;
  font-size: 0.85rem;
  font-weight: 600;
  letter-spacing: 0.08em;
  text-transform: uppercase;
  transition: all 0.25s;
  box-shadow: 0 0 30px rgba(201,168,76,0.2);
}
.btn-gold-lg:hover {
  transform: translateY(-2px);
  box-shadow: 0 0 50px rgba(201,168,76,0.35);
}
.btn-ghost-lg {
  display: inline-block;
  padding: 0.85rem 2.5rem;
  border: 1px solid rgba(245,240,232,0.15);
  color: rgba(245,240,232,0.6);
  border-radius: 0.5rem;
  font-size: 0.85rem;
  letter-spacing: 0.08em;
  text-transform: uppercase;
  transition: all 0.25s;
}
.btn-ghost-lg:hover {
  border-color: rgba(245,240,232,0.35);
  color: #f5f0e8;
  background: rgba(245,240,232,0.04);
}

/* ── Typography ── */
.text-gold { color: #c9a84c; }
.text-cream { color: #f5f0e8; }
.text-void { color: #080614; }
.text-gradient {
  background: linear-gradient(135deg, #e8cc7a 0%, #c9a84c 50%, #9d8235 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}
.eyebrow {
  color: rgba(201,168,76,0.6);
  font-size: 0.7rem;
  letter-spacing: 0.4em;
  text-transform: uppercase;
  margin-bottom: 1rem;
}
.section-title {
  font-size: clamp(1.8rem, 4vw, 3rem);
  font-weight: 300;
  color: #f5f0e8;
  line-height: 1.2;
}
.hero-title {
  font-size: clamp(2.8rem, 7vw, 6rem);
  font-weight: 300;
  line-height: 1.1;
  letter-spacing: -0.02em;
  color: #f5f0e8;
}

/* ── Layout ── */
.section-pad { padding: 7rem 0; }
.divider-line {
  height: 1px;
  background: linear-gradient(90deg, transparent, rgba(201,168,76,0.15), transparent);
  margin: 0 4rem;
}

/* ── Ambient orbs ── */
.orb {
  position: absolute;
  border-radius: 50%;
  filter: blur(80px);
  pointer-events: none;
}
.orb-1 { width: 600px; height: 600px; background: rgba(59,45,122,0.35); top: -200px; left: -200px; }
.orb-2 { width: 400px; height: 400px; background: rgba(201,168,76,0.08); top: 30%; right: -100px; }
.orb-3 { width: 300px; height: 300px; background: rgba(26,74,74,0.3); bottom: 10%; left: 20%; }
.orb-4 { width: 500px; height: 500px; background: rgba(59,45,122,0.2); top: 0; right: -150px; }
.orb-5 { width: 400px; height: 400px; background: rgba(201,168,76,0.06); bottom: 0; left: -100px; }
.orb-6 { width: 600px; height: 600px; background: rgba(59,45,122,0.25); top: 50%; left: 50%; transform: translate(-50%,-50%); }

/* ── Stars ── */
.stars { position: absolute; inset: 0; pointer-events: none; }
.star {
  position: absolute;
  background: #f5f0e8;
  border-radius: 50%;
  animation: twinkle var(--dur, 3s) ease-in-out infinite;
  opacity: 0;
}
@keyframes twinkle {
  0%, 100% { opacity: 0; }
  50% { opacity: 0.6; }
}

/* ── Cards ── */
.step-card {
  background: rgba(8,6,20,0.8);
  padding: 2.5rem;
  transition: background 0.3s;
}
.step-card:hover { background: rgba(201,168,76,0.04); }
.step-number {
  font-size: 0.65rem;
  letter-spacing: 0.3em;
  color: rgba(201,168,76,0.4);
  margin-bottom: 1.5rem;
  font-variant-numeric: tabular-nums;
}

.type-card {
  background: rgba(245,240,232,0.02);
  border: 1px solid rgba(245,240,232,0.06);
  border-radius: 1rem;
  padding: 2rem 1.5rem;
  text-align: center;
  transition: all 0.3s;
  cursor: default;
}
.type-card:hover {
  background: rgba(201,168,76,0.06);
  border-color: rgba(201,168,76,0.2);
  transform: translateY(-4px);
}

.feature-card {
  background: rgba(245,240,232,0.02);
  border: 1px solid rgba(245,240,232,0.05);
  border-radius: 0.75rem;
  padding: 1.5rem;
  transition: all 0.3s;
}
.feature-card:hover {
  background: rgba(201,168,76,0.05);
  border-color: rgba(201,168,76,0.15);
}

.testimonial-card {
  background: rgba(245,240,232,0.02);
  border: 1px solid rgba(245,240,232,0.06);
  border-radius: 1rem;
  padding: 2rem;
  transition: border-color 0.3s;
}
.testimonial-card:hover { border-color: rgba(201,168,76,0.2); }

.cta-card {
  background: rgba(245,240,232,0.02);
  border: 1px solid rgba(201,168,76,0.15);
  border-radius: 1.5rem;
  padding: 4rem 3rem;
  position: relative;
  overflow: hidden;
}
.cta-card::before {
  content: '';
  position: absolute;
  inset: 0;
  background: radial-gradient(ellipse at center, rgba(201,168,76,0.06) 0%, transparent 70%);
  pointer-events: none;
}

/* ── Animations ── */
.animate-fade-in { animation: fadeIn 0.8s ease both; }
.animate-slide-up { animation: slideUp 0.8s ease both; }
@keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
@keyframes slideUp { from { opacity: 0; transform: translateY(24px); } to { opacity: 1; transform: translateY(0); } }
</style>
