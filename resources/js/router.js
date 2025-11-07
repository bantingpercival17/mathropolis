import { createRouter, createWebHistory } from "vue-router";
import Intro from "./pages/Intro.vue";
import Map from "./pages/Map.vue";
import Quiz from "./pages/Quiz.vue";
import Login from "./pages/Login.vue";
import Final from "./pages/Final.vue";

const routes = [
  { path: "/", component: Intro },
  { path: "/map", component: Map },
  { path: "/quiz/:store", component: Quiz, props: true },
  { path: "/login", component: Login },
  { path: "/final", component: Final }
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
