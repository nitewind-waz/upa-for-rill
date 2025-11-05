import '../css/app.css';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import type { DefineComponent } from 'vue';
// import { initializeTheme } from './composables/useAppearance';

import PrimeVue from 'primevue/config';
import ToastService from 'primevue/toastservice';
import Aura from '@primeuix/themes/aura';
// import Nora from '@primevue/themes/nora'; // Gunakan ini jika ingin theme Nora
import 'primeicons/primeicons.css';
// import { MyTheme } from './theme-custom'; // Uncomment jika sudah buat custom theme

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
  title: (title) => (title ? `${title} - ${appName}` : appName),
  resolve: (name) =>
    resolvePageComponent(
      `./Pages/${name}.vue`, // Huruf besar P untuk Pages (sesuai struktur Laravel)
      import.meta.glob<DefineComponent>('./Pages/**/*.vue'),
    ),
  setup({ el, App, props, plugin }) {
    const vueApp = createApp({ render: () => h(App, props) });

    vueApp.use(plugin);
    vueApp.use(PrimeVue, {
      theme: {
        preset: Aura, // Gunakan Aura (bukan MyTheme atau Nora yang error)
        options: {
          prefix: 'p',
          darkModeSelector: '.my-app-dark',
          cssLayer: false
        }
      },
      ripple: true,
      inputStyle: 'outlined'
    });
    vueApp.use(ToastService);

    vueApp.mount(el);
  },
  progress: {
    color: '#F97316', // Orange untuk UPA Bahasa
    showSpinner: true,
  },
});

// Uncomment jika sudah ada composable initializeTheme
// initializeTheme();