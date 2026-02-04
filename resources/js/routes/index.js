import { createWebHistory, createRouter } from "vue-router";
import product from "../component/product.vue";
import Add_product from "../component/add_product.vue";
import Login from "../component/login.vue";
import Cart from "../component/cart.vue";

// Example of setting role and permissions for routes using meta fields

const routes = [
    {
        path: '/', 
        name: 'product',
        component: product,
    },
    {
        path: '/add_product',
        name: 'add_product',
        component: Add_product,
    },
    {
        path :'/edit_product/:id',  
        name :'edit_product',
        component : Add_product,
        props : true,
    },
    {
        path : '/login',
        name : 'login',
        component : Login,
    },
    {   
        path:'/cart',
        name:'cart',
        component : Cart
    }
];  

const router = createRouter({ 
    history: createWebHistory(import.meta.env.BASE_URL),
    routes
});

export default router;