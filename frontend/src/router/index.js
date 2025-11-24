import { createRouter, createWebHistory } from "vue-router";

import AdminLayout from "../layouts/AdminLayouts.vue";
import Dashboard from "../admin/Dashboard/Dashboard.vue";
import Fasilitas from "../admin/Fasilitas/Index.vue";
import JenisKamar from "../admin/JenisKamar/Index.vue";
import Kamar from "../admin/Kamar/Index.vue";


const routes = [
  {
    path: "/dashboard",
    component: AdminLayout,
    children: [
      {
        path: "",
        name: "admin.dashboard",
        component: Dashboard,
      },
    ],
  },

  {
    path: "/Fasilitas",
    component: AdminLayout,
    children: [
      {
        path: "",
        name: "admin.Fasilitas",
        component: Fasilitas,
      },
    ],
  },

  {
    path: "/JenisKamar",
    component: AdminLayout,
    children: [
      {
        path: "",
        name: "admin.JenisKamar",
        component: JenisKamar,
      },
    ],
  },
  {
    path: "/Kamar",
    component: AdminLayout,
    children: [
      {
        path: "",
        name: "admin.Kamar",
        component: Kamar,
      },
    ],
  },
  
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;