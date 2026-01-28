import { defineStore } from "pinia";

export const User = defineStore("user", {
    state: () => ({
        loggedIn: false,
        role : '',
        user : ''
    }),
    persist: true
});

export const cartProducts = defineStore("handleProduct",{
    state : () => ({ 
        cartItems : [], // product details which store in cart 
        cartDetails : [], // cart details with product details
    }),
    getters : {
        CartItemCountByUser : (state) => {
            const currentUser = User();
            const userId = currentUser?.user?.id;
            if (!userId) return 0;
            return state.cartDetails.filter((detail) => detail.user_id == userId).length;
        }
    },
    actions : {
        addToCart(cartInfo){
            const existCart = this.cartDetails.find((proudct) => proudct.id == cartInfo.id && proudct.price == cartInfo.price);
            if(existCart){
                existCart.quantitty += 1
            } else {
                this.cartDetails.push(cartInfo);
                this.cartItems.push(cartInfo.proudct);
            }
        }
    },
    persist : true
})