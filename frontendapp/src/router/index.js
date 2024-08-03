import { createRouter, createWebHistory } from 'vue-router';

const router = createRouter({
  history: createWebHistory(), // Changez cette ligne
  routes: [
    {
      path: '/login',
      name: 'Login',
      component: () => import('@/views/components/users/LoginPage.vue'),
    },
    // Ajoutez d'autres routes ici
  ],
});

export default router;