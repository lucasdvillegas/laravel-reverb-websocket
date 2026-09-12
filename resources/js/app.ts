import { createInertiaApp } from "@inertiajs/vue3";
import { createApp, h } from "vue";
import { initializeTheme } from "@/composables/useAppearance";
import AppLayout from "@/layouts/AppLayout.vue";
import AuthLayout from "@/layouts/AuthLayout.vue";
import SettingsLayout from "@/layouts/settings/Layout.vue";
import { initializeFlashToast } from "@/lib/flashToast";
import { configureEcho } from "@laravel/echo-vue";
import { createPinia } from "pinia";

configureEcho({
    broadcaster: "reverb",
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 8080,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 8080,
    forceTLS: false,
    enabledTransports: ["ws", "wss"],
});

const appName = import.meta.env.VITE_APP_NAME || "Laravel";
const pinia = createPinia();

void createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    resolve: (name) => {
        const pages = import.meta.glob<any>("./pages/**/*.vue", {
            eager: true,
        });
        return pages[`./pages/${name}.vue`];
    },
    layout: (name) => {
        switch (true) {
            case name === "Welcome":
                return null;
            case name.startsWith("auth/"):
                return AuthLayout;
            case name.startsWith("settings/"):
                return [AppLayout, SettingsLayout];
            default:
                return AppLayout;
        }
    },
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(pinia)
            .mount(el);
    },
    progress: {
        color: "#4B5563",
    },
});

initializeTheme();
initializeFlashToast();
