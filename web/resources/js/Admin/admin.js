import '../bootstrap';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../../vendor/tightenco/ziggy/dist/vue.m';

import { createPinia } from "pinia";
import { Tooltip } from "bootstrap";

import ElementPlus from "element-plus";
import i18n from "@/Admin/core/plugins/i18n";
import ApiService from "@/Admin/core/services/ApiService";
import { initApexCharts } from "@/Admin/core/plugins/apexcharts";
import { initInlineSvg } from "@/Admin/core/plugins/inline-svg";
import { initVeeValidate } from "@/Admin/core/plugins/vee-validate";
import { initKtIcon } from "@/Admin/core/plugins/keenthemes";
import "@/Admin/core/plugins/prismjs";
import RootLayout from "~/layouts/RootLayout.vue";

createInertiaApp({
    title: (title) => `${title}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        const app = createApp({
            render: () => h(RootLayout, {}, {
                default: () => [h(App, props)]
            })
        })
            .use(plugin)
            .use(createPinia())
            .use(ElementPlus)
            .use(i18n)
            .use(ZiggyVue, Ziggy);

        ApiService.init(app);
        initApexCharts(app);
        initInlineSvg(app);
        initKtIcon(app);
        initVeeValidate();

        app.directive("tooltip", (el) => {
            new Tooltip(el);
        });
        app.mount(el);
        app.config.globalProperties.$route = route

        return app;
    },
    progress: {
        color: '#4B5563',
    },
});
