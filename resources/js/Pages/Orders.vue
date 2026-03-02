<template>
  <BModal v-model="show">
    Hello this is modal testing
  </BModal>
  <div class="modern-order-wrapper_order">
    <div class="modern-orders-container">
      <!-- Empty state for no orders -->
       {{ order[1][0] }}
      <div v-if="!order?.length" class="modern-product-listing__empty_order">
        <svg width="90" height="90" viewBox="0 0 36 36">
          <circle cx="18" cy="18" r="16" fill="#e0f2fe" />
          <path d="M12 21.5V14.5c0-.28.22-.5.5-.5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-.5.5c-.28 0-.5-.22-.5-.5zm5 0V14.5c0-.28.22-.5.5-.5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-.5.5c-.28 0-.5-.22-.5-.5zm5 0V14.5c0-.28.22-.5.5-.5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-.5.5c-.28 0-.5-.22-.5-.5z" fill="#94a3b8"/>
        </svg>
        <p class="order_not_found">No orders found.</p>
      </div>
      <!-- Orders List -->
      <div v-else class="modern-product-listing_order">
        <div v-for="(orderObj, idxOrder) in order" :key="idxOrder" class="modern-product-card_order">
          <!-- Order Header -->
          <div class="order_time_details">
            <div>
              <div class="order_date_outer">Order date: <span class="order_date_inner">{{ orderObj?.order?.order_date ? new Date(orderObj.order.order_date).toLocaleDateString() : '' }}</span></div>
              <div class="order_status_outer">Order status: <span class="order_status_inner">{{ orderObj?.order?.order_status || 'Processing' }}</span></div>
            </div>
            <div class="order_number_container" >
              <div class="order_number_outer" >Order #<span class="order_number_inner" >{{ orderObj?.order?.id || '-' }}</span></div>
            </div>
          </div>

          <div class="order_hr_line" >
          </div>
        
          <!-- Fallback for the case where orderObj itself is a product item -->
          <div class="order_details" v-if="orderObj?.product">
            <div class="order_image" >
              <img :src="orderObj?.product?.image ? `/storage/${orderObj.product.image}` : '/images/no-photo.png'" :alt="orderObj?.product?.title ?? 'Product Image'" >
            </div>
            <div class="order_info" >
              <div class="order_title" >{{ orderObj?.product?.title ?? 'Product' }}</div>
              <div class="order_description" v-if="orderObj?.product?.description">{{ orderObj.product.description }}</div>
              <div class="order_quantity_container" >
                <div>Qty: <span class="order_quantity" >{{ orderObj?.quantity ?? '1' }}</span></div>
              </div>
            </div>
            <div class="order_amount_container" >
              <div class="order_amount" ><span>{{ getSymbolFromCurrency(orderObj.order.currency) }}</span>{{ orderObj?.order?.total_amount || 0 }}</div>
              <BButton class="modern-btn_order" @click="show = true">Details</BButton>
            </div>
          </div>
          <div v-else class="order_product_not_found" >No products found in this order.</div>
        </div>
      </div>
    </div>
    <!-- Fixed Checkout Footer -->
    <div v-if="total_amount" class="cart-checkout-footer">
      <div class="cart-checkout-footer-inner">
        <div class="cart-total-amount-emoji">
          <div class="cart-total-amount-emoji-insider">
            <span class="show-total-text">
              <span aria-hidden="true" style="margin-right: 2px; font-size: 1.28em;">💵</span>
              Total
            </span>
            <span class="show-total-amount">
              <!-- Add checkout total if needed -->
              ${{total_amount}}
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
import getSymbolFromCurrency from 'currency-symbol-map';

const router = useRouter();
const userStore = User();
const { can } = useAbility();
const order = ref([]);
const show = ref(false);
const total_amount = ref(0);

async function getOrderProduct() {
  const res = await axios.get('/api/get-order-product');
  if (res.data.success) {
    order.value.push(res.data.orderItems);
    order.value = order.value[0];
    // total_amount.value = order.value
    // ?.reduce((total,sum) => total + parseFloat(sum.total || 0),0) ?? 0;
  }
}

onMounted(async () => { 
  await getOrderProduct();
});

</script>