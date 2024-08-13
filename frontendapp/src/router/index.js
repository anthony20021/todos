import { createRouter, createWebHistory } from 'vue-router';

const router = createRouter({
  history: createWebHistory(),
  routes: [
    {
        path: '/',
        name: 'HomePage',
        component: () => import('@/views/components/HomePage.vue'),
    },
    {
      path: '/login',
      name: 'Login',
      component: () => import('@/views/components/users/LoginPage.vue'),
    },
    {
        path: '/register',
        name: 'Register',
        component: () => import('@/views/components/users/RegisterPage.vue'),
    },
    {
        path: '/dashboard',
        name: 'Dashboard',
        component: () => import('@/views/components/dashboard/dashboard.vue'),
    },
    {
        path: '/myAccount',
        name: 'MyAccount',
        component: () => import('@/views/components/users/MyAccount.vue'),
    },
  ],

});
export default router;
