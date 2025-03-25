import { createRouter, createWebHistory } from 'vue-router';
import Login from '../views/Login.vue';
import Admin from '../views/DashboardAdmin.vue';
import Staff from '../views/DashboardStaff.vue';
import PasswordReset from '../views/PasswordReset.vue';

const routes = [
  { path: '/', component: Login },
  { path: '/admin', component: Admin, meta: { requiresAuth: true, role: 'admin' } },
  { path: '/staff', component: Staff, meta: { requiresAuth: true, role: 'staff' } },
  { 
    path: '/password-reset', 
    component: PasswordReset,
    props: route => ({ token: route.query.token }) // Pass token as prop
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

router.beforeEach((to, from, next) => {
  const token = localStorage.getItem('token');
  const userData = localStorage.getItem('user'); // Get stored user data safely
  const user = userData ? JSON.parse(userData) : null; // Parse only if data exists

  if (to.meta.requiresAuth && !token) {
    next('/'); // Redirect to login if not authenticated
  } else if (to.meta.requiresAuth && to.meta.role) {
    if (!user || !user.role) {
      next('/'); // Redirect if user data is missing or role is undefined
    } else if (user.role !== to.meta.role) {
      next('/'); // Redirect if user role doesn't match the required role
    } else {
      next(); // Allow access
    }
  } else {
    next(); // Proceed as normal
  }
});

export default router;
