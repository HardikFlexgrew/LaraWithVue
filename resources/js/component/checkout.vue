<template>
  <div class="checkout-container">
    <div class="checkout-wrapper">
        <form @submit.prevent="placeOrder" class="address-form">

            <!-- Checkout Steps -->
            <div class="checkout-steps">
                <div class="step active">
                    <div class="step-number">1</div>
                    <div class="step-label">Shipping Address</div>
                </div>
                <div class="step-divider"></div>
                    <div class="step active">
                    <div class="step-number">2</div>
                    <div class="step-label">Order Review</div>
                </div>
                <div class="step-divider"></div>
                    <div class="step active">
                    <div class="step-number">3</div>
                    <div class="step-label">Payment</div>
                </div>
            </div>
            {{}}
            <!-- Step 1: Address Form -->
            <div class="checkout-section" style="padding-top:25px">
                <h2 class="section-title">Shipping Address</h2>
                <div class="form-grid">
                    <div class="form-group full-width">
                        <label class="form-label">Full Name</label>
                        <input 
                            v-model="formData.fullName" 
                            type="text" 
                            class="form-input" 
                            placeholder="John Doe"
                            required
                        />
                    <span v-if="errors.fullName" class="error-message">{{ errors.fullName }}</span>
                    </div>

                    <div class="form-group full-width">
                        <label class="form-label">Email Address</label>
                        <input 
                            v-model="formData.email" 
                            type="email" 
                            class="form-input" 
                            placeholder="john@example.com"
                            required
                        />
                        <span v-if="errors.email" class="error-message">{{ errors.email }}</span>
                    </div>

                    <div class="form-group full-width">
                        <label class="form-label">Street Address</label>
                        <input 
                            v-model="formData.address" 
                            type="text" 
                            class="form-input" 
                            placeholder="123 Main Street"
                            required
                        />
                        <span v-if="errors.address" class="error-message">{{ errors.address }}</span>
                    </div>

                    <div class="form-group">
                        <label class="form-label">City</label>
                        <input 
                            v-model="formData.city" 
                            type="text" 
                            class="form-input" 
                            placeholder="New York"
                            required
                        />
                        <span v-if="errors.city" class="error-message">{{ errors.city }}</span>
                    </div>

                    <div class="form-group">
                        <label class="form-label">State</label>
                        <input 
                            v-model="formData.state" 
                            type="text" 
                            class="form-input" 
                            placeholder="NY"
                            required
                        />
                        <span v-if="errors.state" class="error-message">{{ errors.state }}</span>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Country</label>
                        <select v-model="formData.country" class="form-input" required>
                            <option value="">Select a country</option>
                            <option v-for="country in countries[0]" :key="country.id" :value="country.id">
                             {{ country.country_name }}
                            </option>
                        </select>
                        <span v-if="errors.country" class="error-message">{{ errors.country }}</span>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Postal Code</label>
                        <input 
                            v-model="formData.postalCode" 
                            type="text" 
                            class="form-input" 
                            placeholder="10001"
                            required
                            maxlength="6"
                            @input="formatePostalCode"
                        />
                        <span v-if="errors.postalCode" class="error-message">{{ errors.postalCode }}</span>
                    </div>
                </div>
            </div>

            <!-- Step 2: Order Review -->
            <div class="checkout-section">
                <h2 class="section-title">Order Review</h2>

                <!-- Cart Items Review -->
                <div class="review-card">
                    <h3>Order Items</h3>
                    <div class="cart-items-review">
                        <div v-for="item in filteredCartItems" :key="item.id" class="cart-item">
                        <img :src="`/storage/${item.product.image}`" :alt="item.product.title" class="item-image" />
                        <div class="item-info">
                            <p class="item-title">{{ item.product.title }}</p>
                            <p class="item-price">${{ Number(item.price).toFixed(2) }}</p>
                        </div>
                        <div class="item-quantity">
                            <p>Qty: <strong>{{ item.quantitty }}</strong></p>
                        </div>
                        <div class="item-total">
                            <p>${{ (item.price * item.quantitty).toFixed(2) }}</p>
                        </div>
                        </div>
                    </div>
                </div>

                <!-- Price Summary -->
                <div class="price-summary">
                    <div class="summary-row">
                        <span>Subtotal:</span>
                        <span>${{ subtotal.toFixed(2) }}</span>
                    </div>
                    <div class="summary-row">
                        <span>Shipping:</span>
                        <span>${{ shippingCost.toFixed(2) }}</span>
                    </div>
                    <div class="summary-row tax">
                        <span>Tax (estimated):</span>
                        <span>${{ tax.toFixed(2) }}</span>
                    </div>
                    <div class="summary-row total">
                        <span>Total:</span>
                        <span>${{ grandTotal.toFixed(2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Step 3: Payment -->
            <div class="checkout-section">
              <h2 class="section-title">Payment Method</h2>
              
              <div class="payment-methods">
                  <div class="payment-option">
                      <label class="form-label">Payment Method</label>
                      <select
                      id="country_id"
                      v-model="paymentMethod"
                      class="login-input"
                      required
                      autocomplete="country_id">
                          <option :value="paymentMethod">
                              {{ paymentMethod }}
                          </option>
                      </select>
                  </div>
              </div>

                <!-- Order Summary for Payment -->
              <div class="payment-summary">
              <h3>Order Summary</h3>
                  <div class="summary-row">
                      <span>Subtotal:</span>
                      <span>${{ subtotal.toFixed(2) }}</span>
                  </div>
                  <div class="summary-row">
                      <span>Shipping:</span>
                      <span>${{ shippingCost.toFixed(2) }}</span>
                  </div>
                  <div class="summary-row">
                      <span>Tax:</span>
                      <span>${{ tax.toFixed(2) }}</span>
                  </div>
                  <div class="summary-row total">
                      <span>Total Amount:</span>
                      <span>${{ grandTotal.toFixed(2) }}</span>
                  </div>
              </div>

              <div class="form-actions">
                <button type="button" @click="goBack" class="btn btn-secondary">
                    Back
                </button>
                <button type="button" @click="processPayment" :disabled="isProcessing" class="btn btn-primary btn-payment">
                  <span v-if="!isProcessing">
                    Pay ${{ grandTotal.toFixed(2) }}
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" style="margin-left: 0.5rem;">
                      <path d="M5 12l5 5L20 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                  </span>
                  <span v-else class="loading-spinner">Processing...</span>
                </button>
              </div>
            </div>
        </form>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useRouter,useRoute } from 'vue-router';
import axios from 'axios';
import toastr from 'toastr';
import { cartProducts, User } from '@/store';

const router = useRouter();
const route = useRoute();
const cartProduct = cartProducts();
const userStore = User();

const isProcessing = ref(false);
const countries = ref([]);
const paymentMethod = ref('card');

const formData = ref({
  fullName: '',
  email: '',
  address: '',
  city: '',
  state: '',
  country: '',
  postalCode: ''
});

const cardDetails = ref({
  number: '',
  name: '',
  expiry: '',
  cvc: ''
});

async function getCountry(){
  const res = await axios.get('/api/register/get_country');
  if(res.data.success){
    countries.value.push(res.data.countries);
  }
}

const errors = ref({});

const shippingCost = ref(0);

// Computed properties
const filteredCartItems = computed(() => {
  return cartProduct.cartDetails[0].filter(item => item.user_id === userStore.user.id);
});

const subtotal = computed(() => {
  return filteredCartItems.value.reduce((sum, item) => {
    return sum + (parseFloat(item.price) * parseFloat(item.quantitty));
  }, 0);
});

const tax = computed(() => {
  return subtotal.value * 0.08; // 8% tax
});

const grandTotal = computed(() => {
  return subtotal.value + shippingCost.value + tax.value;
});

const formatePostalCode = () => {
  let val = formData.value.postalCode.replace(/\D/g,'');
  formData.value.postalCode = val;
}

const validateForm = () => {
  errors.value = {};
  
  if (!formData.value.fullName){ errors.value.fullName = 'Full name is required';toastr.error('Full name is required')};
  if (!formData.value.email) {errors.value.email = 'Email is required';toastr.error('Email is required')};
  if (!formData.value.address) {errors.value.address = 'Address is required';toastr.error('Address is required')};
  if (!formData.value.city) {errors.value.city = 'City is required';toastr.error('City is required')};
  if (!formData.value.state) {errors.value.state = 'State is required';toastr.error('State is required')};
  if (!formData.value.country) {errors.value.country = 'Country is required';toastr.error('Country is required')};
  if (!formData.value.postalCode) {errors.value.postalCode = 'Postal code is required';toastr.error('Postal code is required')};
 
  return Object.keys(errors.value).length === 0;
};

const goBack = () => {
  router.push({ name: 'cart' });
};

const processPayment = async () => {
  let validate = validateForm();
  if(validate){
    if (!cartProduct.cartDetails[0].length) {
      toastr.error('Your cart is empty', 'Error');
      return;
    }

    isProcessing.value = true;

    try {
      let cartIds = [];
      let userIds = [];
      filteredCartItems.value.map(items => {
        cartIds.push(items.id);
      });

      // Prepare cart items for Stripe
      const items = filteredCartItems.value.map(item => ({
        cartId: cartIds,
        postal_code : formData.value.postalCode,
        quantitty: item.quantitty,
        price: item.price,
        product: item.product,
        tax_rate : 0.08
      }));

      // Create Stripe checkout session
      const response = await axios.post('/api/create-checkout-session', { items });

      if (response.data.url) {
        cartProduct.tempCart = [];
        cartProduct.tempCart.push(formData.value);
        // Redirect to Stripe checkout
        window.location.href = response.data.url; 
      }
    } catch (error) {
      console.error('Payment error:', error);
      toastr.error(error.response?.data?.error || 'Payment processing failed', 'Error');
      isProcessing.value = false;
    }
  }
};

// Pre-fill user data
const prefillUserData = () => {
  if (userStore.user) {
    formData.value.fullName = userStore.user.name || '';
    formData.value.email = userStore.user.email || '';
    formData.value.address = userStore.user.address || '';
    formData.value.city = userStore.user.city || '';
    formData.value.state = userStore.user.state || '';
    if (userStore.user.country_id) {
      formData.value.country = userStore.user.country_id;
    }
  }
};

onMounted(async () => {
  if( route.query?.session_id){
    let sessionId = route.query?.session_id;
    if(sessionId != ''){
      console.log(sessionId);

      const res = await axios.post(`/api/set-status-cart-item/${sessionId}`, {tempCart: cartProduct.tempCart[0]});

    }
  }
  if (!userStore.loggedIn) {
    router.push({ name: 'login' });
    return;
  }

  if (!filteredCartItems.value.length) {
    toastr.error('Your cart is empty', 'Warning');
    router.push({ name: 'cart' });
    return;
  }

//   fetchCountries();
  getCountry();
  prefillUserData();
});
</script>
