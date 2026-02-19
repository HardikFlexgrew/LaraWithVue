import { createWebHistory, createRouter } from "vue-router";
import product from "../component/product.vue";
import Add_product from "../component/add_product.vue";
import Login from "../component/login.vue";
import Cart from "../component/cart.vue";
import Checkout from "../component/checkout.vue";
import CheckoutSuccess from "../component/CheckoutSuccess.vue";
import { User } from "@/store";
import { useAbility } from "@casl/vue";
import { rule } from "postcss";

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
            requiresAuth: true,
            permissions : {
                action : 'add',
                subject : 'product'
            }
        }
    },
    {
        path: '/edit_product/:id',
        name: 'edit_product',
        component: Add_product,
        props: true,
        meta: {
            requiresAuth: true,
            permissions : {
                action : 'edit',
                subject : 'product'
            }
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
    },
    {
        path: '/checkout',
        name: 'checkout',
        component: Checkout,
        meta: {
            requiresAuth: true
        }
    }
    ,
    {
        path: '/checkout/success',
        name: 'checkout_success',
        component: CheckoutSuccess,
    }
];
const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes
});

router.beforeEach((to, from, next) => {
    const {can} = useAbility();
    const auth = User();
    if (to.meta.requiresAuth && !auth.isLoggedIn) return next({ name: 'register', params : {'register' : true}  });
    else {
        if(to.meta.requiresAuth && to.meta.permissions){
            if(!can(to.meta.permissions.action,to.meta.permissions.subject)){
                return next({name : 'login'});
            }
        }
        return next();
    }

});

export default router;