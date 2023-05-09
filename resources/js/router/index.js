import { createRouter, createWebHistory } from 'vue-router'



const routes = [
    {
        path:'/home',
        name:'example',
        component: () => import('../components/ExampleComponent.vue')
    },

]


export default createRouter({
    history: createWebHistory(),
    routes
})
