import './assets/main.css'
import { createApp } from 'vue'
import App from './App.vue'
import router from "./router";
import initSessionManager from './utils/sessionManager';

initSessionManager({
    idleLimitMinutes: 10,
    redirectTo: "/login",
})
createApp(App).use(router).mount('#app')