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
        cartProduct.tempCart.push(formData.value);
        cartProduct.tempCart.push(cardDetails.value);
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
      const res = await axios.get(`/api/set-status-cart-item/${sessionId}`);
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

<style scoped>
.checkout-container {
  min-height: 100vh;
  background: linear-gradient(135deg, #f0fdfa 0%, #e0f2fe 100%);
  padding: 2rem 1rem;
}

.checkout-wrapper {
  max-width: 1000px;
  margin: 0 auto;
  background: #fff;
  border-radius: 16px;
  overflow: hidden;
  box-shadow: 0px 1px 20px 1px #e7f1f4;
}

/* Checkout Steps */
.checkout-steps {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 2.5rem 2rem;
  background: linear-gradient(115deg, #f8f8f8 0%, #ecf7ff 100%);
  color: #2f90c5;
}

.step {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 0.5rem;
  opacity: 0.5;
  transition: opacity 0.3s ease;
}

.step.active,
.step.completed {
  opacity: 1;
}

.step-number {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background: rgba(255, 255, 255, 0.3);
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: bold;
  font-size: 1.1rem;
  border: 2px solid rgba(255, 255, 255, 0.5);
  transition: all 0.3s ease;
}

.step.active .step-number {
  background: #fff;
  color: #0ea5e9;
  border-color: #fff;
}

.step.completed .step-number {
  background: #4ade80;
  border-color: #22c55e;
  color: #fff;
}

.step.completed .step-number::after {
  content: '✓';
  position: absolute;
  font-size: 1.2rem;
}

.step-label {
  font-size: 0.9rem;
  font-weight: 500;
  text-align: center;
  white-space: nowrap;
}

.step-divider {
  flex: 1;
  height: 2px;
  background: rgba(255, 255, 255, 0.3);
  margin: 0 1rem;
  align-self: flex-start;
  margin-top: 20px;
}

/* Checkout Section */
.checkout-section {
  padding: 0.1rem 2rem;
  animation: fadeIn 0.3s ease;
  /* background: linear-gradient(115deg, #f0fdfa 0%, #e0f2fe 100%); */
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.section-title {
  font-size: 1.8rem;
  color: #0ea5e9;
  margin-bottom: 2rem;
  font-weight: 600;
}

/* Form Styles */
.address-form,
.card-form {
  margin-bottom: 2rem;
}

.form-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 1.5rem;
  margin-bottom: 1.5rem;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.form-group.full-width {
  grid-column: 1 / -1;
}

.form-label {
  font-size: 0.95rem;
  font-weight: 600;
  color: #0873ad;
  display: flex;
  align-items: center;
}

.form-input,
.form-select {
  padding: 0.9rem 1.1rem;
  border: 2px solid #d1f0fc;
  border-radius: 8px;
  font-size: 1rem;
  font-family: inherit;
  background: #f9fafb;
  transition: all 0.3s ease;
  color: #1e293b;
}

.form-input:focus,
.form-select:focus {
  outline: none;
  border-color: #0ea5e9;
  background: #fff;
  box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.1);
}

.error-message {
  font-size: 0.85rem;
  color: #ef4444;
  display: flex;
  align-items: center;
  gap: 0.3rem;
}

/* Card Form Specific */
.card-input-wrapper {
  display: grid;
  gap: 2rem;
  margin-bottom: 2rem;
}

.card-preview {
  display: flex;
  justify-content: center;
}

.card-mock {
  width: 100%;
  max-width: 380px;
  background: linear-gradient(135deg, #0ea5e9 0%, #0873ad 100%);
  border-radius: 12px;
  padding: 2rem;
  color: #fff;
  box-shadow: 0 10px 30px rgba(14, 165, 233, 0.3);
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  min-height: 200px;
}

.card-number {
  font-size: 1.4rem;
  font-weight: 500;
  letter-spacing: 2px;
  margin-bottom: 2rem;
  font-family: 'Courier New', monospace;
}

.card-bottom {
  display: flex;
  justify-content: space-between;
  align-items: flex-end;
}

.card-label {
  font-size: 0.75rem;
  opacity: 0.8;
  text-transform: uppercase;
  display: block;
  margin-bottom: 0.3rem;
}

.card-name,
.card-expiry {
  font-size: 1rem;
  font-weight: 500;
}

.form-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1.5rem;
}

/* Payment Methods */
.payment-methods {
  display: flex;
  gap: 1.5rem;
  margin-bottom: 2.5rem;
}

.payment-option {
  flex: 1;
  min-width: 200px;
}

.payment-radio {
  display: none;
}

.payment-label {
  display: flex;
  align-items: center;
  gap: 1rem;
  padding: 1.2rem;
  border: 2px solid #d1f0fc;
  border-radius: 10px;
  cursor: pointer;
  transition: all 0.3s ease;
  background: #f9fafb;
  font-weight: 500;
  color: #0873ad;
}

.payment-radio:checked + .payment-label {
  border-color: #0ea5e9;
  background: linear-gradient(92deg, #d1f0fc 0%, #e0f2fe 100%);
  color: #0873ad;
}

.payment-label svg {
  width: 24px;
  height: 24px;
  stroke: currentColor;
}

.payment-text {
  font-size: 1rem;
}

/* Summaries */
.review-card {
  background: linear-gradient(92deg, #f0fdfa 0%, #f7fbfc 100%);
  border: 2px solid #d1f0fc;
  border-radius: 12px;
  padding: 1.5rem;
  margin-bottom: 1.5rem;
}

.review-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1rem;
}

.review-header h3 {
  color: #0873ad;
  font-size: 1.1rem;
  margin: 0;
}

.edit-btn {
  background: transparent;
  border: none;
  color: #0ea5e9;
  cursor: pointer;
  text-decoration: underline;
  font-size: 0.9rem;
  font-weight: 500;
  transition: color 0.2s ease;
}

.edit-btn:hover {
  color: #0873ad;
}

.review-content p {
  margin: 0.4rem 0;
  color: #475569;
  font-size: 0.95rem;
}

.review-content p strong {
  color: #0873ad;
}

/* Cart Items Review */
.cart-items-review {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.cart-item {
  display: grid;
  grid-template-columns: 60px 1fr auto auto;
  gap: 1rem;
  align-items: center;
  padding: 1rem;
  background: #fff;
  border-radius: 8px;
  border: 1px solid #e3f3fb;
}

.item-image {
  width: 60px;
  height: 60px;
  object-fit: cover;
  border-radius: 6px;
}

.item-info {
  display: flex;
  flex-direction: column;
  gap: 0.3rem;
}

.item-title {
  margin: 0;
  color: #1e293b;
  font-weight: 500;
  font-size: 0.95rem;
}

.item-price {
  margin: 0;
  color: #0ea5e9;
  font-weight: 600;
  font-size: 0.9rem;
}

.item-quantity p,
.item-total p {
  margin: 0;
  color: #475569;
  font-size: 0.9rem;
}

.item-total p {
  color: #0873ad;
  font-weight: 600;
  font-size: 1rem;
}

/* Price Summary */
.price-summary,
.payment-summary {
  background: linear-gradient(92deg, #f0fdfa 0%, #f7fbfc 100%);
  border: 2px solid #d1f0fc;
  border-radius: 12px;
  padding: 1.5rem;
  margin-bottom: 2rem;
}

.payment-summary {
  display: none;
}

.checkout-section:last-of-type .payment-summary {
  display: block;
}

.summary-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0.8rem 0;
  border-bottom: 1px solid #d1f0fc;
  color: #475569;
  font-weight: 500;
}

.summary-row:last-child {
  border-bottom: none;
}

.summary-row.tax {
  color: #7c3aed;
}

.summary-row.total {
  background: linear-gradient(92deg, #d1f0fc 0%, #d0effc 100%);
  margin: 0.5rem -1.5rem -1.5rem -1.5rem;
  padding: 1rem 1.5rem;
  border-radius: 0 0 12px 12px;
  color: #0873ad;
  font-size: 1.1rem;
  border: none;
}

.summary-row.total span:last-child {
  font-weight: 700;
  color: #0ea5e9;
}

.summary-row span:last-child {
  font-weight: 600;
  color: #0ea5e9;
}

/* Form Actions */
.form-actions {
  display: flex;
  gap: 1rem;
  justify-content: flex-end;
  padding-top: 2rem;
  border-top: 2px solid #e3f3fb;
}

/* Buttons */
.btn {
  padding: 0.95rem 2rem;
  border: none;
  border-radius: 8px;
  font-size: 1rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  font-family: inherit;
}

.btn-primary {
  background: linear-gradient(92deg, #0ea5e9 55%, #1197e6 100%);
  color: #fff;
  box-shadow: 0 4px 12px rgba(14, 165, 233, 0.3);
}

.btn-primary:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 6px 16px rgba(14, 165, 233, 0.4);
}

.btn-primary:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.btn-secondary {
  background: transparent;
  color: #0ea5e9;
  border: 2px solid #0ea5e9;
}

.btn-secondary:hover {
  background: rgba(14, 165, 233, 0.05);
  border-color: #0873ad;
  color: #0873ad;
}

.btn-payment {
  min-width: 200px;
  font-size: 1.05rem;
}

.loading-spinner {
  display: inline-block;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  from {
    opacity: 0.6;
  }
  to {
    opacity: 1;
  }
}

/* Responsive */
@media (max-width: 768px) {
  .checkout-steps {
    padding: 1.5rem 1rem;
    gap: 0.5rem;
  }

  .step-label {
    font-size: 0.75rem;
  }

  .step-divisor {
    margin: 0 0.5rem;
  }

  .checkout-section {
    padding: 1.5rem 1rem;
  }

  .section-title {
    font-size: 1.4rem;
    margin-bottom: 1.5rem;
  }

  .form-grid {
    grid-template-columns: 1fr;
    gap: 1rem;
  }

  .form-row {
    grid-template-columns: 1fr;
  }

  .cart-item {
    grid-template-columns: 50px 1fr;
    gap: 0.8rem;
  }

  .item-quantity,
  .item-total {
    grid-column: 1 / -1;
    font-size: 0.85rem;
  }

  .form-actions {
    flex-direction: column-reverse;
    gap: 0.8rem;
  }

  .btn {
    width: 100%;
  }

  .payment-methods {
    flex-direction: column;
  }
}
</style>
