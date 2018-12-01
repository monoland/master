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
import router from './routes/account';

new Vue({
    router,
    data:() => ({
        message: { type: null, text: null, show: false }
    }),
    render: h => h(Apps)
}).$mount('#monoland');