<template>
  <div class="modern-product-listing cart-page-wrapper">
    <div class="modern-product-listing__grid" style="padding-bottom: 110px;">
      <!-- Added padding-bottom to prevent card content being covered by footer -->
        <div class="modern-product-listing__empty_order">
            <svg width="80" height="80" viewBox="0 0 36 36">
            <circle cx="18" cy="18" r="16" fill="#e0f2fe" />
            <path
                d="M12 21.5V14.5c0-.28.22-.5.5-.5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-.5.5c-.28 0-.5-.22-.5-.5zm5 0V14.5c0-.28.22-.5.5-.5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-.5.5c-.28 0-.5-.22-.5-.5zm5 0V14.5c0-.28.22-.5.5-.5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-.5.5c-.28 0-.5-.22-.5-.5z"
                fill="#94a3b8" />
            </svg>
            <p>No products found.</p>
        </div>
        <div class="modern-product-card"
            style="display: flex; flex-direction: column; height: 100%; position: relative;"
        >
            <div class="modern-product-card__img-wrapper">
                <img 
                    class="modern-product-card__img" loading="lazy" />
            </div>
            <div class="modern-product-card__body" style="flex: 1;">
                <h3 class="modern-product-card__title">{{  }}</h3>
                <p class="modern-product-card__desc">
                    {{
                    
                    }}
                </p>
            </div>
            <!-- Card footer sits at bottom of card (NOT sticky/fixed/absolute in viewport) --> 
            <div class="modern-product-card__footer card-footer-overhaul" 
                style="margin-top: auto; background: #fff; z-index: 1; border-top: 1px solid #f2f2f2;">
                <span class="modern-product-card__price">
                    {{  }}
                </span>
            </div>
        </div>
    </div>
    <!-- Fixed/floating Checkout Footer, never overlaps cards -->
    <div class="cart-checkout-footer">
        <div class="cart-checkout-footer-inner">
            <div class="cart-total-amount-emoji">
                <div class="cart-total-amount-emoji-insider" style="display: flex;gap: 8px;">
                    <span class="show-total-text">
                    <span aria-hidden="true" style="margin-right: 2px; font-size: 1.25em;">💵</span>
                        Total
                    </span>
                    <span class="show-total-amount">
                    
                    </span>
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
const userStore = User();
const { can } = useAbility();
let selectedProduct = [];
const operations = { operation: '' };
const filterText = ref('');
const typedInput = ref(null);
const filterPlaceholder = ref();
let typed = null;

async function getOrderProduct() {
  const res = await axios.get('/api/get-order-product');
  if(res.data.success){
    res.data.products.map((e)=>{
    });
  }
}

onMounted(async () => { 
  getOrderProduct();
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
});


</script>