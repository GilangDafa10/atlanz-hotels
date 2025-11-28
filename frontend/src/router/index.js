import { createRouter, createWebHistory } from "vue-router";
import api from "@/utils/api";
// Import pages
import Home from "../pages/Home.vue";
import Rooms from "../pages/Rooms.vue";
// import Booking from "../pages/Booking.vue";
// import Booking from "../auth/BookingPage.vue";
import AdminLayout from "../layouts/AdminLayouts.vue";
import Dashboard from "../admin/Dashboard/Dashboard.vue";
import Fasilitas from "../admin/Fasilitas/Index.vue";
import JenisKamar from "../admin/JenisKamar/Index.vue";
import Kamar from "../admin/Kamar/Index.vue";
import AdditionalServices from "../admin/AdditionalServices/Index.vue";
import AddService from "@/pages/AddService.vue";
import Profile from "@/pages/Profil.vue";
import Login from "@/auth/Login.vue";
import Register from "@/auth/Register.vue";
import RoomsSelection from "@/pages/RoomsSelection.vue";
import Confirmation from "@/components/Confirmation.vue";
import CreateInvoice from "@/pembayaran/CreateInvoice.vue";
import BookingPage from "@/pages/BookingPage.vue";
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
        path: "/cari-kamar",
        name: "cari-kamar",
        meta: { requiresAuth: true },
        component: RoomsSelection,
    },
    {
        path: "/booking",
        name: "booking",
        meta: { requiresAuth: true },
        component: BookingPage,
    },
    {
        path: "/AddService",
        name: "addservice",
        meta: { requiresAuth: true },
        component: AddService,
    },
    {
        path: '/payment',
        name: 'midtrans-payment',
        meta: { requiresAuth: true },
        component: CreateInvoice,
    },
    {
        path: "/Profile",
        name: "profile",
        meta: { requiresAuth: true },
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
    // {
    //     path: "/BookingPage",
    //     name: "bookingpage",
    //     meta: { requiresAuth: true },
    //     component: BookingPage,
    // },
    {
        path: "/dashboard",
        component: AdminLayout,
        meta: { requiresAdmin: true },
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
        meta: { requiresAdmin: true },
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
        meta: { requiresAdmin: true },
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
        meta: { requiresAdmin: true },
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
        meta: { requiresAdmin: true },
        children: [
            {
                path: "",
                name: "admin.AdditionalServices",
                component: AdditionalServices,
            },
        ],
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});


let user = null

router.beforeEach(async (to, from, next) => {
    const token = localStorage.getItem('token')

    // 1️⃣ Jika halaman butuh login tapi tidak ada token → langsung ke login
    if (to.meta.requiresAuth && !token) {
        return next('/login')
    }

    // 2️⃣ Kalau ada token tapi user belum dimuat → panggil /me dulu
    if (token && !user) {
        try {
            const res = await api.get('/me')
            user = res.data.data
        } catch (e) {
            localStorage.clear()
            return next('/login')
        }
    }

    // 3️⃣ Pastikan admin page sesuai role
    if (to.meta.requiresAdmin && user?.id_role !== 1) {
        return next('/')
    }

    // 4️⃣ Kalau sudah login → tidak boleh masuk login
    if (to.name === 'login' && token) {
        return next('/')
    }

    next()
})

export default router;
