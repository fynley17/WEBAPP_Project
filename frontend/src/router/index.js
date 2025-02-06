import { createRouter, createWebHistory } from 'vue-router';
import Login from '../components/Login.vue';
// import AdminDashboard from '/../views/AdminDashboard';
// import StaffDashboard from '/../views/StaffDashboard';

const routes = [
  { path: '/', component: Login }
  // { path: '/admin', component: AdminDashboard, meta: { requiresAuth: true, role: 'admin' } },
  // { path: '/staff', component: StaffDashboard, meta: { requiresAuth: true, role: 'staff' } }
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