import { createRouter, createWebHistory } from 'vue-router';
import Login from '../components/Login.vue';
import Admin from '../components/DashboardAdmin.vue';
import Staff from '../components/DashboardStaff.vue';

const routes = [
  { path: '/', component: Login },
  { path: '/admin', component: Admin, meta: { requiresAuth: true, role: 'admin' } },
  { path: '/staff', component: Staff, meta: { requiresAuth: true, role: 'staff' } }
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

router.beforeEach((to, from, next) => {
  const token = localStorage.getItem('token');
  if (to.meta.requiresAuth && !token) {
    next('/');
  } else {
    next();
  }
});

export default router;