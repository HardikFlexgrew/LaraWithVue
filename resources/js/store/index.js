import axios from "axios";
import { defineStore } from "pinia";

export const User = defineStore("user", {
    state: () => ({
        loggedIn: false,
        role : '',
        user : ''
    }),
    getters : {
        isLoggedIn : (state)=>{
            return !!state.user;
        }
    },
    actions : {
        setUser(response){
            this.user = response ?? null;
        },

        async logout(){
            try { 
                const res = await axios.post('/api/logout', {
                    credentials : 'include',
                    headers : {Accept:'application/json'},
                });
                this.user = null;
                this.role = null
                this.loggedIn = false;
                return res;
            } catch { 
                return 'Logout failed';
            }
        }
    },
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
            return state.cartDetails.map((cartDetail) => {
                // If cartDetail is an array, filter; otherwise, return matching object in array
                if (Array.isArray(cartDetail)) {
                  return cartDetail.filter(cartItem => cartItem.user_id == currentUser?.user?.id);
                } else if (cartDetail && typeof cartDetail === 'object') {
                  // If it's a single object, wrap in array if user matches
                  return cartDetail.user_id == currentUser?.user?.id ? [cartDetail] : [];
                } else {
                  return [];
                }
              });
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