import { createRouter, createWebHistory } from "vue-router";

// Import pages
import Home from "../pages/Home.vue";
import Rooms from "../pages/Rooms.vue";
import Fasilitas from "../pages/Fasilitas.vue";
// import About from "../pages/About.vue";
// import DetailMobil from "../pages/DetailMobil.vue";

const routes = [
    {
        path: "/",
        name: "home",
        component: Home,
    },
    {
        path: "/rooms",
        name: "rooms",
        component: Rooms,
    },
    {
        path: "/rooms",
        name: "rooms",
        component: Rooms,
    },
    {
        path: "/fasilitas",
        name: "fasilitas",
        component: Fasilitas,
    },
    // {
    //     path: "/about",
    //     name: "about",
    //     component: About,
    // },
    // {
    //     path: "/mobil/:id",
    //     name: "detail-mobil",
    //     component: DetailMobil,
    // }
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
