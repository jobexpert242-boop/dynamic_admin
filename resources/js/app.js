import "./bootstrap";
import { createApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/vue3";
import { ZiggyVue } from "ziggy-js";

createInertiaApp({
    resolve: (name) => {
        const pages = import.meta.glob("./Pages/**/*.vue", { eager: true });
        const page = pages[`./Pages/${name}.vue`];
        return page.default;
    },
    title: (title) =>
        title ? `${title} - Comits Computer` : "Comits Computer",
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue, Ziggy)
            .mount(el);
    },
    progress: {
        color: "red",
        includeCSS: true,
        showSpinner: true,
    },
    defaults: {
        future: {
            useDialogForErrorModal: true,
        },
    },
});

// const can = (permission) => {
//   return usePage().props.auth?.user?.permissions?.includes(permission);
// };
// <button v-if="can('edit users')">Edit</button>
//  :overrides="{
//             'admin': { label: 'Admin', route: '/admin' },
//             'admin.users': { label: 'Users', route: '/admin/users' }
//             }"
// use AuthorizesRequests;
// $this->authorize('role.show');
// {{ $page.props.site.docs }}