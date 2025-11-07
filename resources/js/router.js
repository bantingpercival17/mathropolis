import { createRouter, createWebHistory } from "vue-router";
import Intro from "./pages/Intro.vue";
import Map from "./pages/Map.vue";
import Quiz from "./pages/Quiz.vue";
import Login from "./pages/auth/Login.vue";
import Final from "./pages/Final.vue";
const childPage = [
  {
    path: "/home",
    meta: {
      auth: false,
      name: 'Home',
      user: 'Guest'
    }, component: Intro
  },
  { path: "/map", component: Map },
  { path: "/quiz/:store", component: Quiz, props: true },
  { path: "/login", component: Login },
  { path: "/final", component: Final },
  { path: '/auth/:pathMatch(.*)*', beforeEnter: () => window.location.href = '/auth/google' }
]
const routes = [
  {
    path: '/',
    redirect: '/home',
    name: 'client-layout',
    component: () => import('./pages/MainLayout.vue'),
    children: childPage
  },

];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
