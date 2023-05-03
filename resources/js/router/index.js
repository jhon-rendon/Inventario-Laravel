import { createRouter, createWebHistory } from 'vue-router'

import example from '../components/ExampleComponent.vue'
import contactos from '../components/Contactos.vue'
import index from '../components/index.vue'

const routes = [
    {
        path:'/home',
        name:'example',
        component: example,
    },
    {
        path:'/otra',
        name:'index',
        component: index
    },
    {
        path:'/contactos',
        name:'contactos',
        component: contactos
    }
]


export default createRouter({
    history: createWebHistory(),
    routes
})
