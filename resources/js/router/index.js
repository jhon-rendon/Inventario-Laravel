import { createRouter, createWebHistory } from 'vue-router'
import authRouter from '../modules/auth/router'


const routes = [
    {
        path:'/home',
        name:'home',
        component: () => import('../views/Home.vue')
    },
    {
        path: '/auth',
        ...authRouter,
      },


]


export default createRouter({
    history: createWebHistory(),
    routes
})
