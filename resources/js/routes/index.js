import { createWebHistory, createRouter } from "vue-router";
import product from "../component/product.vue";
import Add_product from "../component/add_product.vue";
import Login from "../component/login.vue";
import Cart from "../component/cart.vue";
import { User } from "@/store";
import Register from "@/component/register.vue";

// Example of setting role and permissions for routes using meta fields
const routes = [
    {
        path: '/',
        name: 'product',
        component: product,
        meta: {
            requiresAuth: true
        }
    },
    {
        path: '/add_product',
        name: 'add_product',
        component: Add_product,
        meta: {
            requiresAuth: true
        }
    },
    {
        path: '/edit_product/:id',
        name: 'edit_product',
        component: Add_product,
        props: true,
        meta: {
            requiresAuth: true
        }
    },
    {
        path: '/login',
        name: 'login',
        component: Login,
    },
    {
        path: '/register/:register',
        name: 'register',
        component: Login,
        props : true,
    },
    {
        path: '/cart',
        name: 'cart',
        component: Cart,
        meta: {
            requiresAuth: true
        }
    }
];
const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes
});

router.beforeEach((to, from, next) => {
    const auth = User();
    if (to.meta.requiresAuth && !auth.isLoggedIn) next({ name: 'register' });
    else next();
});

export default router;