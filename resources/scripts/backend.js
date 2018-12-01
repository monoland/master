import Vue from 'vue';
import Vuetify from 'vuetify';
import { Misc } from './misc';

Vue.config.productionTip = false;
Vue.use(Vuetify);
Vue.use(Misc);

import * as Parts from './parts';
Object.keys(Parts).forEach((part) => {
    Vue.component(Parts[part].name, Parts[part]);
});

import Apps from './pages/Apps.vue';
import router from './routes/backend';

new Vue({
    router,
    data:() => ({
        // backend menu
        backend: {
            menus: [
                { type: 'item', icon: 'dashboard', text: 'Dashboard', to: { name: 'dashboard' }},
                { type: 'divider', class: 'my-2' },
                { type: 'subheader', text: 'Authentication' },
                { type: 'item', icon: 'event_seat', text: 'Apps Management', to: { name: 'oauth' }},
                { type: 'item', icon: 'supervisor_account', text: 'User Management', to: { name: 'users' }},
                { type: 'subheader', text: 'Setting' },
                { type: 'item', icon: 'settings', text: 'User Setting', to: { name: 'setting' }},
                // { type: 'group', icon: 'account_circle', text: 'Mastering', items: [
                //     { type: 'item', icon: 'people_outline', text: 'Agency', to: { name: 'agency' }},
                //     { type: 'item', icon: 'people_outline', text: 'Category', to: { name: 'category' }},
                //     { type: 'item', icon: 'people_outline', text: 'Database', to: { name: 'database' }},
                //     { type: 'item', icon: 'people_outline', text: 'Laguage', to: { name: 'language' }},
                // ]},
                // { type: 'item', icon: 'dashboard', text: 'Application', to: { name: 'application' }},
            ]
        },

        message: { type: null, text: null, show: false }
    }),
    render: h => h(Apps)
}).$mount('#monoland');