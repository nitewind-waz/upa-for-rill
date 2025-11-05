import '../css/app.css';

import { createApp, h, DefineComponent } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';

// PrimeVue Imports
import PrimeVue from 'primevue/config';
import ToastService from 'primevue/toastservice';
import ConfirmationService from 'primevue/confirmationservice';
import DialogService from 'primevue/dialogservice';
import Tooltip from 'primevue/tooltip';
import Ripple from 'primevue/ripple';

// PrimeVue CSS
import 'primeicons/primeicons.css';
import { MyTheme } from './theme-custom';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob<DefineComponent>('./Pages/**/*.vue')
        ),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(PrimeVue, {
                ripple: true,
                theme: MyTheme,
                inputStyle: 'outlined'
            })
            .use(ToastService)
            .use(ConfirmationService)
            .use(DialogService);

        // Register directives
        app.directive('tooltip', Tooltip);
        app.directive('ripple', Ripple);

        app.mount(el);
    },
    progress: {
        color: '#F97316',
        showSpinner: true,
    },
});