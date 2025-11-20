import '../css/app.css';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import type { DefineComponent } from 'vue';

import PrimeVue from 'primevue/config';
import ToastService from 'primevue/toastservice';
import ConfirmationService from 'primevue/confirmationservice';

// 1. Pastikan import dari '@primevue/themes' (bukan primeuix kecuali kamu pakai alias khusus)
import Aura from '@primeuix/themes/aura';
import { definePreset } from '@primeuix/themes';

import 'primeicons/primeicons.css';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

// 2. Definisikan Warna Biru Konsisten disini
const MyBluePreset = definePreset(Aura, {
    semantic: {
        primary: {
            50: '#eff6ff',
            100: '#dbeafe',
            200: '#bfdbfe',
            300: '#93c5fd',
            400: '#60a5fa',
            500: '#3b82f6', // Warna Utama (Blue 500)
            600: '#2563eb',
            700: '#1d4ed8',
            800: '#1e40af',
            900: '#1e3a8a',
            950: '#172554'
        }
    }
});

createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    resolve: (name) =>
        resolvePageComponent(
            `./pages/${name}.vue`,
            import.meta.glob<DefineComponent>('./pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        const vueApp = createApp({ render: () => h(App, props) });

        vueApp.use(plugin);
        vueApp.use(PrimeVue, {
            theme: {
                // 3. Gunakan Preset Biru yang sudah dibuat
                preset: MyBluePreset, 
                options: {
                    prefix: 'p',
                    darkModeSelector: '.my-app-dark',
                    cssLayer: false // Biarkan false jika kamu setup Tailwind tanpa layer
                }
            },
            ripple: true,
            inputStyle: 'outlined'
        });
        vueApp.use(ToastService);
        vueApp.use(ConfirmationService);
        vueApp.mount(el);
    },
    progress: {
        // 4. Ubah warna progress bar Inertia supaya senada dengan tema
        color: '#3b82f6', 
        showSpinner: true,
    },
});