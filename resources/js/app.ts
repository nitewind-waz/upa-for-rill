import '../css/app.css';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import type { DefineComponent } from 'vue';
import { initializeTheme } from './composables/useAppearance';

import PrimeVue from 'primevue/config';
import ToastService from 'primevue/toastservice';
import Nora from '@primeuix/themes/nora';
import 'primeicons/primeicons.css'; // buat icon
import { MyTheme } from './theme-custom'

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

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
        preset: MyTheme,
        options: {
            darkModeSelector: '.my-app-dark',
        }
      },
    });
    vueApp.use(ToastService);

    vueApp.mount(el);
  },
  progress: {
    color: '#4B5563',
  },
});

initializeTheme();
