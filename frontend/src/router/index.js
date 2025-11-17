import { createRouter, createWebHistory } from "vue-router";

// Import pages
import Home from "../pages/Home.vue";
import Rooms from "../pages/Rooms.vue";
import Booking from "../pages/Booking.vue";
import AddService from "@/pages/AddService.vue";
import Profile from "@/pages/Profil.vue";
import Reservation from "@/pages/Reservation.vue";
import Login from "@/pages/Login.vue";
import Register from "@/pages/Register.vue";
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
        path: "/booking",
        name: "booking",
        component: Booking,
    },
    {
        path: "/AddService",
        name: "addservice",
        component: AddService,
    },
    {
        path: "/Profile",
        name: "profile",
        component: Profile,
    },
    {
        path: "/Reservation",
        name: "reservation",
        component: Reservation, 
    },
    {
        path: "/Login",
        name: "login",
        component: Login,
    },
    {
        path: "/Register",
        name: "register",
        component: Register,
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
