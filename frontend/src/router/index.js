import { createRouter, createWebHistory } from "vue-router";

// Import pages
import Home from "../pages/Home.vue";
import Rooms from "../pages/Rooms.vue";
// import Booking from "../pages/Booking.vue";
import Booking from "../auth/BookingPage.vue";
import AdminLayout from "../layouts/AdminLayouts.vue";
import Dashboard from "../admin/Dashboard/Dashboard.vue";
import Fasilitas from "../admin/Fasilitas/Index.vue";
import JenisKamar from "../admin/JenisKamar/Index.vue";
import Kamar from "../admin/Kamar/Index.vue";
import AdditionalServices from "../admin/AdditionalServices/Index.vue";

// ✅ BARU: Import Komponen Users
import Users from "../admin/Users/Index.vue"; 

import AddService from "@/pages/AddService.vue";
import Profile from "@/pages/Profil.vue";
import Login from "@/auth/Login.vue";
import Register from "@/auth/Register.vue";
import RoomsSelection from "@/pages/RoomsSelection.vue";
import Confirmation from "@/components/Confirmation.vue";
import CreateInvoice from "@/pembayaran/CreateInvoice.vue";
import BookingPage from "@/pages/BookingPage.vue";

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
        path: "/cari-kamar",
        name: "cari-kamar",
        component: RoomsSelection,
    },
    {
        path: "/booking",
        name: "booking",
        component: BookingPage,
    },
    {
        path: "/AddService",
        name: "addservice",
        component: AddService,
    },
    {
        path: '/payment',
        name: 'midtrans-payment',
        component: CreateInvoice,
    },
    {
        path: "/Profile",
        name: "profile",
        component: Profile,
    },
    {
        path: "/Login",
        name: "login",
        component: Login,
    },
    {
        path: '/oauth/callback',
        name: 'oauth.callback',
        component: () => import('@/pages/OauthCallback.vue')
    },
    {
        path: '/otp',
        name: 'otp',
        component: () => import('@/RegisterPage/OTP.vue')
    },
    {
        path: "/Register",
        name: "register",
        component: Register,
    },
    {
        path: "/Confirmation",
        name: "confirmation",
        component: Confirmation,
    },
    {
        path: "/BookingPage",
        name: "bookingpage",
        component: BookingPage,
    },
    
    // --- ADMIN ROUTES GROUPED BY LAYOUT ---
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
    
    // ... (Fasilitas, JenisKamar, Kamar, AdditionalServices routes)

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
    {
        path: "/AdditionalServices",
        component: AdminLayout,
        children: [
        {
            path: "",
            name: "admin.AdditionalServices",
            component: AdditionalServices,
        },
        ],
    },
    
    // ✅ BARU: Route untuk Users
    {
        path: "/users",
        component: AdminLayout,
        children: [
        {
            path: "",
            name: "admin.users",
            component: Users,
        },
        ],
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;