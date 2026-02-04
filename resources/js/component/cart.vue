<template>
  <div class="modern-product-listing">
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
    <div class="modern-product-listing__grid">
      <div class="modern-product-card" v-for="products in filteredProducts" :key="products.id">
        <div class="modern-product-card__img-wrapper">
          <img :src="`/storage/${products.product.image}`" :alt="products.product.title"
            class="modern-product-card__img" loading="lazy" />
        </div>
        <div class="modern-product-card__body">
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
        <div class="modern-product-card__footer">
          <span class="modern-product-card__price">
            {{ '$' + products.price * products.quantitty }}
          </span>
          <div class="modern-product-card__actions">
            <button @click="decreaseQuantity(products.id)" class="modern-btn modern-btn--subtract"
              aria-label="Descrease Product" :disabled="products.quantitty === 0">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="#e52727d1">
                <rect x="5" y="11" width="100" height="3" r="5" fill="#fff" />
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