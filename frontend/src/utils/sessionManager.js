// sessionManager.js

import axios from "axios"

export default function initSessionManager({
    idleLimitMinutes = 10,
    redirectTo = "/login",
} = {}) {

    const IDLE_LIMIT = idleLimitMinutes * 60 * 1000
    const CHECK_INTERVAL = 5000 // 5 detik

    // -----------------------------
    // 🔹 Update aktivitas user
    // -----------------------------
    const updateActivity = () => {
        localStorage.setItem("lastActivity", Date.now())
    }

    // Tangkap aktivitas user
    const registerActivityListeners = () => {
        window.onload = updateActivity
        window.onmousemove = updateActivity
        window.onkeydown = updateActivity
        window.onclick = updateActivity
        window.onscroll = updateActivity
    }

    // -----------------------------
    // 🔹 Cek apakah user idle
    // -----------------------------
    const startIdleChecker = () => {
        setInterval(() => {
            const last = localStorage.getItem("lastActivity")
            const now = Date.now()

            if (last && now - last > IDLE_LIMIT) {
                logout()
            }
        }, CHECK_INTERVAL)
    }

    // -----------------------------
    // 🔹 Logout function
    // -----------------------------
    const logout = () => {
        localStorage.removeItem("token")
        localStorage.removeItem("isLoggedIn")
        localStorage.removeItem("lastActivity")

        window.location.href = redirectTo
    }

    // -----------------------------
    // 🔹 Axios Interceptor (401 → logout otomatis)
    // -----------------------------
    axios.interceptors.response.use(
        res => res,
        err => {
            if (err.response?.status === 401) {
                logout()
            }
            return Promise.reject(err)
        }
    )

    // -----------------------------
    // 🔹 Aktifkan session manager
    // -----------------------------
    registerActivityListeners()
    startIdleChecker()
}
