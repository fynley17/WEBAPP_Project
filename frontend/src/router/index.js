import { createRouter, createWebHistory } from 'vue-router';
import Login from '@/views/Login.vue';
import AdminDashboard from '@/views/AdminDashboard';
import StaffDashboard from '@/views/StaffDashboard';

const routes = [
    { path: '/', component: Login },
    { path: '/admin', component: AdminDashboard, meta: { requiresAuth: true, role: 'admin' } },
    { path: '/staff', component: StaffDashboard, meta: { requiresAuth: true, role: 'staff' } }
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

export default router;