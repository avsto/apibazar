import { createWebHistory, createRouter } from 'vue-router';

import Login from './components/Login.vue';
import Dashboard from './components/Dashboard.vue';


import Home from './components/home/Home.vue';
import Schemes from './components/setup/schemes.vue';
import Api from './components/setup/Api.vue';
import Provider from './components/setup/Provider.vue';
import ApiCommision from './components/setup/ApiCommision.vue';
import SchemeCommision from './components/setup/SchemeCommision.vue';
import Roles from './components/role/Roles.vue';

const routes = [
    { path: '/', component: Login },
    {
        path: '/dashboard',
        component: Dashboard,
        redirect: '/dashboard/home',
        children: [
            { path: 'home', component: Home },

            { path: 'setup/schemes', component: Schemes },
            { path: 'setup/schemeCommision/:option1/:option2', component: SchemeCommision },
            { path: 'setup/api', component: Api },
            { path: 'setup/provider', component: Provider },
            { path: 'setup/apicommission/:option1/:option2', component: ApiCommision },

            { path: 'usertype/roles', component: Roles },

        ],
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
