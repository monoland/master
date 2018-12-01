import Vue from 'vue';
import Router from 'vue-router';

Vue.use(Router);

// authentication
import * as account from '@scripts/pages/account';

export default new Router({
    // mode: 'history',
    base: process.env.BASE_URL,
    routes: [
        { path: '/', component: account.Base, children: [
            { path: '', redirect: { name: 'login' }},
            { path: 'login', name: 'login', component: account.Login },
            { path: '*', redirect: { name: 'login' }},
        ]}
    ]
});