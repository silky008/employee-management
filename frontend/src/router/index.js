import { createRouter, createWebHistory } from "vue-router";
import AppLayout from "../layouts/AppLayout.vue";
import LoginView from "../views/LoginView.vue";
import DashboardView from "../views/DashboardView.vue";
import UsersView from "../views/UsersView.vue";
import AccessDenied from "../views/AccessDenied.vue";
import api from "@/services/api";

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
    meta: { requiresAuth: true, requiresAdmin: true, title: "Users" },
    children: [{ path: "", component: UsersView }],
  },
  { path: "/403", component: AccessDenied },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

// Global navigation guard
router.beforeEach(async (to, from, next) => {
  const token = localStorage.getItem("token");

  // Not logged in but trying to access protected page
  if (to.meta.requiresAuth && !token) {
    next("/login");
    return;
  }

  // If admin-only route
  if (to.meta.requiresAdmin) {
    try {
      const res = await api.get("/me");
      const user = res.data;

      if (user.role.name !== "admin") {
        // Not admin → redirect to dashboard
        next("/");
        return;
      }
    } catch (error) {
      // Token invalid → logout
      localStorage.removeItem("token");
      next("/login");
      return;
    }
  }

  next();
});

export default router;
