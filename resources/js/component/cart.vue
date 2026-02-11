<template>
  <div class="modern-product-listing cart-page-wrapper">
    <div class="modern-product-listing__filter"
      style="display: flex; justify-content: center; align-items: center; margin-bottom: 2.5rem;">
      <div class="moder-product-filter">
        <svg width="22" height="22" fill="none" viewBox="0 0 22 22"
          style="margin-right:.85em;opacity:0.73;margin-left: 15px;">
          <circle cx="10" cy="10" r="8" stroke="#1197e6" stroke-width="2" />
          <path d="M16 16l3.5 3.5" stroke="#1197e6" stroke-width="2" stroke-linecap="round" />
        </svg>
        <input v-model="filterText" type="text" class="modern-form-input filter-product-input"
        :placeholder="filterPlaceholder"
        ref="typedInput" style="
            flex: 1;
            padding: .85em 1em;
            border: none;
            outline: none;
            font-size: 1.1em;
            background: transparent;
            color: #134e6f;
            font-family: inherit;
            border-radius: 2em;
            box-shadow: none;
          " />
      </div>
    </div>
    <div class="modern-product-listing__grid" style="padding-bottom: 110px;">
      <!-- Added padding-bottom to prevent card content being covered by footer -->
      <div class="modern-product-card"
          v-for="products in filteredProducts" 
          :key="products.id"
          style="display: flex; flex-direction: column; height: 100%; position: relative;"
      >
        <div class="modern-product-card__img-wrapper">
          <img :src="`/storage/${products.product.image}`" :alt="products.product.title"
            class="modern-product-card__img" loading="lazy" />
        </div>
        <div class="modern-product-card__body" style="flex: 1;">
          <h3 class="modern-product-card__title">{{ products.product.title }}</h3>
          <p class="modern-product-card__desc" :title="products.product.description">
            {{
              products.product.description &&
              products.product.description.length > 80
                ? products.product.description.substring(0, 80) + '...'
                : products.product.description
            }}
          </p>
        </div>
        <!-- Card footer sits at bottom of card (NOT sticky/fixed/absolute in viewport) --> 
        <div class="modern-product-card__footer card-footer-overhaul" 
          style="margin-top: auto; background: #fff; z-index: 1; border-top: 1px solid #f2f2f2;">
          <span class="modern-product-card__price">
            {{ '$' + products.price * products.quantitty }}
          </span>
          <div class="modern-product-card__actions">
            <button @click="decreaseQuantity(products.id)" class="modern-btn modern-btn--subtract"
              aria-label="Descrease Product" :disabled="products.quantitty === 0">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="#e52727d1">
                <rect x="5" y="11" width="14" height="3" rx="1.5" fill="#fff" />
              </svg>
            </button>
            <span class="modern-btn modern-btn--quantity" title="Delete product">{{ products.quantitty }}</span>
            <button class="modern-btn modern-btn--addition" @click="increaseQuantity(products.id)"
              aria-label="Increase Product">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                <rect x="8" y="3" width="5" height="15" rx="2" fill="#fff" />
                <rect x="4" y="8" width="13" height="5" rx="2" fill="#fff" />
              </svg>
            </button>
          </div>
        </div>
      </div>
      <div v-if="!cartProduct?.CartItemCountByUser" class="modern-product-listing__empty">
        <svg width="80" height="80" viewBox="0 0 36 36">
          <circle cx="18" cy="18" r="16" fill="#e0f2fe" />
          <path
            d="M12 21.5V14.5c0-.28.22-.5.5-.5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-.5.5c-.28 0-.5-.22-.5-.5zm5 0V14.5c0-.28.22-.5.5-.5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-.5.5c-.28 0-.5-.22-.5-.5zm5 0V14.5c0-.28.22-.5.5-.5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-.5.5c-.28 0-.5-.22-.5-.5z"
            fill="#94a3b8" />
        </svg>
        <p>No products found.</p>
      </div>
    </div>
    <!-- Fixed/floating Checkout Footer, never overlaps cards -->
    <div v-if="filteredProducts.length > 0" class="cart-checkout-footer">
      <div class="cart-checkout-footer-inner">
        <div class="cart-total-amount-emoji">
          <div class="cart-total-amount-emoji-insider" style="display: flex;gap: 8px;">
            <span class="show-total-text">
              <span aria-hidden="true" style="margin-right: 2px; font-size: 1.25em;">💵</span>
              Total
            </span>
            <span class="show-total-amount">
              ${{ 
                filteredProducts.reduce((total, product) => {
                  return total + ((product.price || 0) * (product.quantitty || 0));
                }, 0)
              }}
            </span>
          </div>
          <div>
            <a style="text-decoration: none;"
              target="_blank"
              class="modern-btn modern-btn--checkout enhanced liquid-bg"
              @click="proceedToCheckout"
            > 
            <span class="liquid-effect" aria-hidden="true"></span>
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" style="vertical-align: middle; margin-right: 7px; z-index: 2; position: relative;">
              <path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zm0 2zm10-2c-1.1 0-1.99.9-1.99 2S15.9 22 17 22s2-.9 2-2-.9-2-2-2zm0 2zm1.83-3.25l1.6-7.59A1 1 0 0 0 19.45 7H6.21l-.94-2.34A1.003 1.003 0 0 0 4.3 4H2v2h1.3l3.6 8.59-1.35 2.44c-.08.15-.15.32-.15.51 0 .55.45 1 1 1h12v-2h-11.1c.04-.13.14-.25.18-.38l.93-1.69h7.43c.81 0 1.52-.52 1.77-1.25zm-2.19-1.75H8.53L7.16 9h10.09l-1.22 5z" fill="#fff"/>
            </svg>
            <span style="position: relative; z-index: 2;">Checkout</span>
          </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import toastr from 'toastr'
import { useRouter } from 'vue-router';
import axios from 'axios'
import { ref, computed, onMounted } from 'vue';
import { useAbility } from '@casl/vue';
import { cartProducts,User } from '../store';
import Typed from 'typed.js';

const router = useRouter();
const cartProduct = cartProducts();
const userStore = User();
const { can } = useAbility();
let selectedProduct = [];
const operations = { operation: '' };
const filterText = ref('');
const typedInput = ref(null);
const filterPlaceholder = ref();

let typed = null;

async function decreaseQuantity(productId) {
  const firstItemObject = [cartProduct.cartDetails];
  firstItemObject.forEach(element => {
    selectedProduct = element.find(product => product.id == productId);
  });
  selectedProduct.quantitty -= 1;
  if (selectedProduct.quantitty == 0) {
    const res = await axios.post(`/api/product/cart/remove/${productId}`);
    if(res.data.success){
      const idx = cartProduct.cartDetails.findIndex((product)=>product.id == productId);
      cartProduct.cartDetails.splice(idx,1);
      cartProduct.cartItems.splice(idx,1);
    }
  } else {
    const res = await axios.post(`/api/product/cart/operation/${productId}`, selectedProduct);
  }
}

async function increaseQuantity(productId) {
  const firstItemObject = [cartProduct.cartDetails];
  firstItemObject.forEach(element => {
    selectedProduct = element.find(product => product.id == productId);
  });
  selectedProduct.quantitty += 1;
  const res = await axios.post(`/api/product/cart/operation/${productId}`, selectedProduct);
}

const filteredProducts = computed(() => {
  const term = filterText.value.trim().toLowerCase();
  if (!term) return cartProduct.cartDetails.filter((cartDetail)=>{
    return cartDetail.user_id == userStore.user.id
  });
  return cartProduct.cartDetails.filter(products => {
    return (
      (products.product.title && products.product.title.toLowerCase().includes(term)) ||
      (products.product.description && products.product.description.toLowerCase().includes(term)) || 
      (products.product.price && products.product.price.toLowerCase().includes(term))
    );
  });
});

async function proceedToCheckout() {
  try {
    const items = filteredProducts.value.map(p => ({
      price: p.price,
      quantitty: p.quantitty,
      product: p.product
    }));

    const res = await axios.post('/api/create-checkout-session', { items });
    console.log(res.data.url);
    if (res.data && res.data.url) {
      window.location = res.data.url;
    } else {
      toastr.error('Unable to start checkout.');
    }
  } catch (err) {
    console.error(err);
    toastr.error('Checkout failed.');
  }
}

onMounted(() => { 
  if (typedInput.value) {
    typed = new Typed(typedInput.value, {
      strings: [
        'Search by product descriptions, prices...',
        'Search products...'
      ],
      typeSpeed: 80,
      backSpeed: 60,
      attr: 'placeholder',
      bindInputFocusEvents: true,
      loop: true
    });
  }
})
</script>