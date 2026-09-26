import '../css/app.css';
import { createApp, h } from 'vue';
import { createInertiaApp, Link } from '@inertiajs/vue3';
import axios from 'axios';

window.axios = axios;
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

const pages = import.meta.glob('./Pages/**/*.vue', { eager: true });

createInertiaApp({
    resolve: (name) => {
        const page = pages[`./Pages/${name}.vue`];

        if (!page?.default) {
            throw new Error(`Inertia page component not found: ${name}`);
        }

        return page.default;
    },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .component('Link', Link)
            .mount(el);
    },
});
