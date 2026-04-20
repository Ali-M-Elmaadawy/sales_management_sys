import { createRouter, createWebHistory } from 'vue-router'


import Login from '../pages/Login.vue'
import Home from '../pages/Home.vue'
import AdminLayout from '../layouts/AdminLayout.vue'
import Products from '../pages/Products.vue'
import Users from '../pages/Users.vue'
import Sales from '../pages/Sales.vue'
import Help from '../pages/Help.vue'

const routes = [

  {
    path: '/login',
    component: Login
  },

  {
    path: '/',
    component: AdminLayout,
    children: [
        { path: 'home', component: Home },
        { path: 'products', component: Products },
        { path: 'users', component: Users },
        { path: 'sales', component: Sales },
        { path: 'help', component: Help },
    ]
  }

]

const router = createRouter({
    history: createWebHistory(),
    routes,
})

router.beforeEach((to, from, next) => {
    const token = localStorage.getItem('token')

    if (!token && to.path !== '/login') {
        next('/login')
    } else if (token && to.path === '/login') {
        next('/') 
    } else {
        next()
    }
})

export default router