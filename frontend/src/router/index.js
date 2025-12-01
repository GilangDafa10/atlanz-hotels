import { createRouter, createWebHistory } from "vue-router";
import api from "@/utils/api";

// Public pages (lazy loaded only when needed)
const Home = () => import("@/pages/Home.vue");
const Rooms = () => import("@/pages/Rooms.vue");
const Login = () => import("@/auth/Login.vue");
const Register = () => import("@/auth/Register.vue");
const Profile = () => import("@/pages/Profil.vue");
const AddService = () => import("@/pages/AddService.vue");
const RoomsSelection = () => import("@/pages/RoomsSelection.vue");
const BookingPage = () => import("@/pages/BookingPage.vue");
const CreateInvoice = () => import("@/pembayaran/CreateInvoice.vue");
const Confirmation = () => import("@/components/Confirmation.vue");

// Auth
const OauthCallback = () => import("@/pages/OauthCallback.vue");
const OTP = () => import("@/RegisterPage/OTP.vue");

// Admin pages
const AdminLayout = () => import("@/layouts/AdminLayouts.vue");
const Dashboard = () => import("@/admin/Dashboard/Dashboard.vue");
const Fasilitas = () => import("@/admin/Fasilitas/Index.vue");
const JenisKamar = () => import("@/admin/JenisKamar/Index.vue");
const Kamar = () => import("@/admin/Kamar/Index.vue");
const BookingDetails = () => import("@/admin/BookingDetails/Index.vue");
const AdditionalServices = () => import("@/admin/AdditionalServices/Index.vue");
const Users = () => import("@/admin/Users/Index.vue");

// // Not Found
// const NotFound = () => import("@/pages/NotFound.vue");

const router = createRouter({
    history: createWebHistory(),
    routes: [
        { path: "/", name: "home", component: Home },
        { path: "/rooms", name: "rooms", component: Rooms },
        { path: "/login", name: "login", component: Login },
        { path: "/register", name: "register", component: Register },

        {
            path: "/profile",
            name: "profile",
            component: Profile,
            meta: { requiresAuth: true },
        },
        {
            path: "/booking",
            name: "booking",
            component: BookingPage,
            meta: { requiresAuth: true },
        },
        {
            path: "/room-selection",
            name: "room.selection",
            component: RoomsSelection,
            meta: { requiresAuth: true },
        },
        {
            path: "/add-service",
            name: "add.service",
            component: AddService,
            meta: { requiresAuth: true },
        },
        {
            path: "/midtrans-payment",
            name: "midtrans-payment",
            component: CreateInvoice,
            meta: { requiresAuth: true },
        },
        { path: "/confirmation", name: "confirmation", component: Confirmation },

        { path: "/oauth/callback", name: "oauth.callback", component: OauthCallback },
        { path: "/otp", name: "otp", component: OTP },

        // ADMIN ROUTES (clean structure)
        {
            path: "/admin",
            component: AdminLayout,
            meta: { requiresAdmin: true },
            children: [
                { path: "dashboard", name: "admin.dashboard", component: Dashboard },
                { path: "facilities", name: "admin.facilities", component: Fasilitas },
                { path: "room-types", name: "admin.room.types", component: JenisKamar },
                { path: "rooms", name: "admin.rooms", component: Kamar },
                { path: "services", name: "admin.services", component: AdditionalServices },
                { path: "users", name: "admin.users", component: Users },
                { path: "bookings", name: "admin.booking.details", component: BookingDetails },
            ],
        },

        // // 404 fallback
        // { path: "/:pathMatch(.*)*", name: "notfound", component: NotFound },
    ],
});


// ===== 🔐 Navigation Guard =====

let cachedUser = null;

router.beforeEach(async (to, _, next) => {
    const token = localStorage.getItem("token");

    if (to.meta.requiresAuth && !token) {
        return next("/login");
    }

    if (token && !cachedUser) {
        try {
            const res = await api.get("/me");
            cachedUser = res.data.data;
        } catch {
            localStorage.clear();
            return next("/login");
        }
    }

    if (to.meta.requiresAdmin && cachedUser?.id_role !== 1) {
        return next("/");
    }

    // Prevent login when logged in
    if (to.name === "login" && token) {
        return next("/");
    }

    next();
});

export default router;
