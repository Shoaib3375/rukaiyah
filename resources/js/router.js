import { createRouter, createWebHistory } from 'vue-router';
import { isAuthenticated, userRole, authReady, initAuth } from './store';

// Landing
import Landing from './views/Landing.vue';

// Auth views
import Login from './views/Auth/Login.vue';
import Register from './views/Auth/Register.vue';
import InviteAccept from './views/Auth/InviteAccept.vue';

// Patient views
import PatientDashboard from './views/Patient/Dashboard.vue';
import PatientProfile from './views/Patient/Profile.vue';
import PatientAppointments from './views/Patient/Appointments.vue';
import PatientAppointmentDetail from './views/Patient/AppointmentDetail.vue';
import PatientBookAppointment from './views/Patient/BookAppointment.vue';
import PatientBrowseRaqis from './views/Patient/BrowseRaqis.vue';
import PatientRaqiDetail from './views/Patient/RaqiDetail.vue';
import PatientNotifications from './views/Patient/Notifications.vue';

// Raqi views
import RaqiDashboard from './views/Raqi/Dashboard.vue';
import RaqiProfile from './views/Raqi/Profile.vue';
import RaqiAvailability from './views/Raqi/Availability.vue';
import RaqiAppointments from './views/Raqi/Appointments.vue';
import RaqiSessionDetail from './views/Raqi/SessionDetail.vue';
import RaqiNotifications from './views/Raqi/Notifications.vue';

// Admin views
import AdminDashboard from './views/Admin/Dashboard.vue';
import AdminUsers from './views/Admin/Users.vue';
import AdminRaqis from './views/Admin/Raqis.vue';
import AdminAppointments from './views/Admin/Appointments.vue';

const routes = [
  // Auth routes
  { path: '/login', name: 'login', component: Login, meta: { requiresGuest: true } },
  { path: '/register', name: 'register', component: Register, meta: { requiresGuest: true } },
  { path: '/invites/:token', name: 'invite', component: InviteAccept, meta: { requiresAuth: true } },

  // Public Raqi Browse Routes
  { path: '/raqis', name: 'browse-raqis', component: PatientBrowseRaqis, meta: { requiresAuth: false } },
  { path: '/raqis/:id', name: 'raqi-detail', component: PatientRaqiDetail, meta: { requiresAuth: false } },

  // Patient routes
  {
    path: '/patient',
    meta: { requiresAuth: true, role: 'patient' },
    children: [
      { path: '', name: 'patient-dashboard', component: PatientDashboard },
      { path: 'profile', name: 'patient-profile', component: PatientProfile },
      { path: 'appointments', name: 'patient-appointments', component: PatientAppointments },
      { path: 'appointments/:id', name: 'patient-appointment-detail', component: PatientAppointmentDetail },
      { path: 'appointments/book', name: 'patient-book-appointment', component: PatientBookAppointment },

      { path: 'notifications', name: 'patient-notifications', component: PatientNotifications }
    ]
  },

  // Raqi routes
  {
    path: '/raqi',
    meta: { requiresAuth: true, role: 'raqi' },
    children: [
      { path: '', name: 'raqi-dashboard', component: RaqiDashboard },
      { path: 'profile', name: 'raqi-profile', component: RaqiProfile },
      { path: 'availability', name: 'raqi-availability', component: RaqiAvailability },
      { path: 'appointments', name: 'raqi-appointments', component: RaqiAppointments },
      { path: 'sessions/:id', name: 'raqi-session-detail', component: RaqiSessionDetail },
      { path: 'notifications', name: 'raqi-notifications', component: RaqiNotifications }
    ]
  },

  // Admin routes
  {
    path: '/admin',
    meta: { requiresAuth: true, role: 'admin' },
    children: [
      { path: '', name: 'admin-dashboard', component: AdminDashboard },
      { path: 'users', name: 'admin-users', component: AdminUsers },
      { path: 'raqis', name: 'admin-raqis', component: AdminRaqis },
      { path: 'appointments', name: 'admin-appointments', component: AdminAppointments }
    ]
  },

  // Landing
  { path: '/', name: 'home', component: Landing, meta: { requiresGuest: false } }
];

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes
});

// Route guards
router.beforeEach(async (to, from, next) => {
  if (!authReady.value) {
    await initAuth();
  }

  const requiresAuth = to.meta.requiresAuth;
  const requiresGuest = to.meta.requiresGuest;
  const requiredRole = to.meta.role;

  if (requiresGuest && isAuthenticated.value) {
    next({ name: userRole.value === 'patient' ? 'patient-dashboard' : userRole.value === 'raqi' ? 'raqi-dashboard' : 'admin-dashboard' });
  } else if (requiresAuth && !isAuthenticated.value) {
    let q = { redirect: to.fullPath };
    if (to.name === 'patient-book-appointment') q.message = 'login_to_book';
    next({ name: 'login', query: q });
  } else if (requiredRole && userRole.value !== requiredRole) {
    next({ name: 'login' });
  } else {
    next();
  }
});

export default router;
