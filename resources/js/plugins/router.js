import { createMemoryHistory, createRouter } from 'vue-router'

import UsersView from '../views/UsersView.vue'
import HomeView from '../views/HomeView.vue'

const routes = [
    {
        path: '/users',
        component: UsersView, // Parent component (acts as a layout)
        children: [
            {
                path: '', // Empty means default child route ("/users")
                name: 'UserList',
                component: UsersView,
            },
        ]
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
];

export default createRouter({
    history: createMemoryHistory(),
    routes,
})
