import { createRouter, createWebHistory } from "vue-router";
import AppLayout from "../layouts/AppLayout.vue";
import AuditLogsView from "../views/AuditLogsView.vue";
import LoginView from "../views/LoginView.vue";
import DashboardView from "../views/DashboardView.vue";
import UsersView from "../views/UsersView.vue";
import AccessDenied from "../views/AccessDenied.vue";
import ClientsView from "../views/ClientsView.vue";
import api from "@/services/api";
import ClientEdit from "../views/ClientEdit.vue";
import ClientView from "../views/ClientView.vue";

const routes = [
  // Login page
  { path: "/login", name: "Login", component: LoginView },

  // Protected routes under AppLayout
  {
    path: "/",
    component: AppLayout,
    meta: { requiresAuth: true },
    children: [
      {
        path: "",
        name: "Dashboard",
        component: DashboardView,
        meta: { title: "Dashboard" },
      },
      {
        path: "users",
        name: "Users",
        component: UsersView,
        meta: { title: "Manage Users", requiresAdmin: true },
      },
      {
        path: "clients",
        name: "ClientsList",
        component: ClientsView,
        meta: { title: "Manage Clients", requiresAdmin: true },
      },
      {
        path: "clients/view/:id",
        name: "ClientView",
        component: ClientView,
        meta: { title: "View Client", requiresAdmin: true },
      },
      {
        path: "clients/edit/:id",
        name: "ClientEdit",
        component: ClientEdit,
        meta: { title: "Edit Client", requiresAdmin: true },
      },

      {
        path: "audit-logs",
        name: "AuditLogs",
        component: AuditLogsView,
        meta: { title: "Audit Logs", requiresAdmin: true },
      },
    ],
  },

  // 403 Access Denied page
  { path: "/403", name: "AccessDenied", component: AccessDenied },

  // Fallback route
  { path: "/:catchAll(.*)", redirect: "/" },
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
