import { createMemoryHistory, createRouter } from 'vue-router'

import UsersView from '../views/UsersView.vue'
import HomeView from '../views/HomeView.vue'

const routes = [
    {
        path: '/users',
        name: 'Users',
        component: UsersView,
    },
    {
        path: '/',
        redirect: '/users', // Default route
    },
    {
        path: '/home',
        name: 'Home',
        component: HomeView,
    },
    {
        path: '/users/create',
        name: 'CreateUser',
        component: () => import('../views/CreateUserView.vue'),
    }
];

export default createRouter({
    history: createMemoryHistory(),
    routes,
})
