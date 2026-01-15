import { createRouter, createWebHistory } from "vue-router";
import AppLayout from "../layouts/AppLayout.vue";
import LoginView from "../views/LoginView.vue";
import DashboardView from "../views/DashboardView.vue";
import UsersView from "../views/UsersView.vue";
import AccessDenied from "../views/AccessDenied.vue";

const routes = [
  { path: "/login", component: LoginView },
  {
    path: "/",
    component: AppLayout,
    meta: { requiresAuth: true, title: "Dashbaord" },
    children: [{ path: "", component: DashboardView }],
  },
  {
    path: "/users",
    component: AppLayout,
    meta: { requiresAuth: true, title: "Users" },
    children: [{ path: "", component: UsersView }],
  },
  { path: "/403", component: AccessDenied },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

// Global navigation guard
router.beforeEach((to, from, next) => {
  const token = localStorage.getItem("token");
  if (to.meta.requiresAuth && !token) {
    next("/login");
  } else {
    next();
  }
});

export default router;
