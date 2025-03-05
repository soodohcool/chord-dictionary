import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView,
    },
    {
      path: '/selected',
      name: 'selected',
      component: HomeView,
      props: { initialSection: 'selected' }
    },
    {
      path: '/all',
      name: 'all',
      component: HomeView,
      props: { initialSection: 'all' }
    },
    {
      path: '/about',
      name: 'about',
      component: HomeView,
      props: { initialSection: 'about' }
    },
    // Catch-all route for 404
    {
      path: '/:pathMatch(.*)*',
      redirect: '/'
    }
  ],
})

export default router
