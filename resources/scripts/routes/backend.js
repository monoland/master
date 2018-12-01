import Vue from 'vue';
import Router from 'vue-router';

Vue.use(Router);

// load backend
import * as backend from '@scripts/pages/backend';

export default new Router({
    // mode: 'history',
    base: process.env.BASE_URL + '/backend',
    routes: [
        { path: '/', meta: { requiresAuth: true }, component: backend.Base, children: [
            { path: '', redirect: { name: 'dashboard' }},
            { path: 'dashboard', name: 'dashboard', component: backend.Dashboard },
            { path: 'oauth', name: 'oauth', component: backend.OAuth },
            { path: 'setting', name: 'setting', component: backend.Setting },
            { path: 'users', name: 'users', component: backend.Users },
            { path: '*', redirect: { name: 'dashboard' }},
        ]}
    ]
});