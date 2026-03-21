<template>
  <div class="modern-product-listing">
    <!-- Filter/Search Bar -->
    <div class="modern-product-listing__filter" v-if="!editCheck"
      style="display: flex; justify-content: center; align-items: center; margin-bottom: 2.5rem;">
      <div class="moder-product-filter">
        <svg width="22" height="22" fill="none" viewBox="0 0 22 22"
          style="margin-right:.85em;opacity:0.73;margin-left: 15px;">
          <circle cx="10" cy="10" r="8" stroke="#1197e6" stroke-width="2" />
          <path d="M16 16l3.5 3.5" stroke="#1197e6" stroke-width="2" stroke-linecap="round" />
        </svg>
        <input
          v-model="filterText"
          id="searchFilterInput"
          type="text"
          class="modern-form-input filter-product-input"
          :placeholder="filterPlaceholder"
          ref="typedInput"
          style="
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
          "
        />
      </div>
    </div>
    <div class="modern-product-listing__grid">
      <EditProduct :id="editId" v-if="editCheck" @edited="editedProduct"></EditProduct>
      <div class="modern-product-card" v-else v-for="product in filteredProducts" :key="product.id">
        <div class="modern-product-card__img-wrapper" v-for="image in product.images" :key="image.id">
          <img :src="`/storage/${image.path}`" :alt="product.title" class="modern-product-card__img"
            loading="lazy" />
        </div>
        <div class="modern-product-card__body">
          <h3 class="modern-product-card__title" style="width: 100%;display: flex; align-items: center; justify-content: space-between; gap: 1.15em;">
            <span style="display: flex; align-items: center; gap: 0.65em;">
              {{ product.title }}
            </span>
            <span v-if="userStore.role == 'Admin'" style="font-size: 0.93em; color: #0891b2; background: #e0f2fe; padding: 0.21em 0.68em; border-radius: 6px; font-weight: 500; margin-left: 0.3em;">
              Stock: {{ product.stock }}
            </span>
          </h3>
          <p class="modern-product-card__desc" :title="product.description" style="margin-bottom: 0;">
            {{ product.description.length > 80 ? product.description.substring(0, 80) + '...' : product.description }}
          </p>
        </div>
        <div class="modern-product-card__footer">
          <span class="modern-product-card__price">${{ product.price }}</span>
          <div class="modern-product-card__actions">
            <button @click="editProduct(product.id)" class="modern-btn modern-btn--edit" title="edit product"
              aria-label="Edit" v-if="can('edit', 'product')">  
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                <path
                  d="M15.232 5.232l3.536 3.536-10.036 10.036-3.536-3.536L15.232 5.232zM3 21h7.5M17 7l-1-1c-1.172-1.172-3.072-1.172-4.244 0l-1 1c-1.172 1.172-1.172 3.072 0 4.244l1 1c1.172 1.172 3.072 1.172 4.244 0l1-1c1.172-1.172 1.172-3.072 0-4.244z"
                  stroke="#e0f2fe" stroke-width="2"></path>
              </svg>
            </button>

            <button @click="deleteProduct(product.id)" class="modern-btn modern-btn--delete" title="Delete product"
              aria-label="Delete" v-if="can('delete', 'product')">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                <path d="M3 6h18M8 6V4a2 2 0 012-2h4a2 2 0 012 2v2m2 0V20a2 2 0 01-2 2H6a2 2 0 01-2-2V6h16z"
                  stroke="#fee2e2" stroke-width="2"></path>
              </svg>
            </button>
            <button @click="addToCart(product.id)" class="modern-btn modern-btn--cart" title="Add to cart"
              aria-label="Add to cart" :disabled="addCartDisabled">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                <path
                  d="M5 6h14l-1.5 9h-11L5 6zm0 0L4 2H2m2 4l1.5 9m4.5 3a1 1 0 110 2 1 1 0 010-2zm6 0a1 1 0 110 2 1 1 0 010-2z"
                  stroke="#38bdf8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M11 12h2m-1-1v2" stroke="#38bdf8" stroke-width="2" stroke-linecap="round" />
              </svg>
            </button>
          </div>
        </div>
      </div>
      <div v-if="!filteredProducts.length" class="modern-product-listing__empty">
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
import { onMounted, ref,computed, onUpdated } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios'
import { useAbility } from '@casl/vue';
import { cartProducts } from '@/store';
import Typed from 'typed.js';
import EditProduct from './add_product.vue';
import { User } from '@/store';

const router = useRouter();
const products = ref([]);
const filterText = ref('');
const cartProduct = cartProducts();
const { can } = useAbility();
const userStore = User();
const editCheck = ref(false);
const editId = ref(0);
const typedInput = ref(null);
const filterPlaceholder = ref();
let typed = null;
const addCartDisabled = ref(false);

async function getProduct() {
  const res = await axios.get('/api/product/show');
  if(res){
    products.value = res.data.products;
  }
}

const filteredProducts = computed(() => {
  const term = filterText.value.trim().toLowerCase();
  if (!term) return products.value;
  return products.value.filter(product => {
    return (
      (product.title && product.title.toLowerCase().includes(term)) ||
      (product.description && product.description.toLowerCase().includes(term)) || 
      (product.price && product.price.toLowerCase().includes(term))
    );
  });
});
 
function editedProduct(check){
  if(check){
    editCheck.value = false;    
    getProduct();
  }
}

async function editProduct(productId) {
  if (can('edit', 'product')) {
    editCheck.value = true;
    editId.value = productId;
  } else {
    toastr.error("Unauthorized", 'Error');
  }
}

async function addToCart(productId) {
  try {
    const res = await axios.post(`/api/product/cart/${productId}`);
    if (res.data.success) {
      cartProduct.addToCart(res.data.cart_details);
      toastr.success(res.data.message, 'Success');
    } else {
      toastr.error(res.data.message,'Oops!');
      addCartDisabled.value = true;
    }
  } catch (err) {
    console.log(err);
  }
}

async function deleteProduct(productId) {
  
  if (can('delete', 'product')) {
    try {
      const res = await axios.post(`/api/product/delete/${productId}`);
      if (res.data.success) {
        toastr.success(res.data.message, 'Success', {
          timeOut: 3000,
          progressBar: true,
          closeButton: true
        });
        const idx = products.value.findIndex((product) => product.id == productId);
        products.value.splice(idx,1);
      }
    } catch (err) {
      toastr.error(err.response.data.message, 'Error', {
        timeOut: 3000,
        progressBar: true,
        closeButton: true
      });
    }
  } else {
    toastr.error("Unauthorized", 'Error');
  }
} 

onUpdated((typed)=>{
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

onMounted(async () => {
  await getProduct();
});

</script>