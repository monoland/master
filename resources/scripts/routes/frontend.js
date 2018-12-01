import Vue from 'vue';
import Router from 'vue-router';

Vue.use(Router);

// load frontend
import * as frontend from '@scripts/pages/frontend';

export default new Router({
    // mode: 'history',
    base: process.env.BASE_URL,
    routes: [
        { path: '/', component: frontend.Base, children: [
            { path: '', name: 'welcome', component: frontend.Welcome },
            { path: 'email/verify', name: 'verify', component: frontend.Verify }
        ]}
    ]
});