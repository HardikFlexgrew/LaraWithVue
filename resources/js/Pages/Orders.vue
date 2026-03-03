<template>
  <BModal v-model="show">
    Hello this is modal testing
  </BModal>
  <div class="modern-order-wrapper_order">
    <div class="modern-orders-container">
      <!-- Empty state for no orders -->
      <div v-if="!order?.length" class="modern-product-listing__empty_order">
        <svg width="90" height="90" viewBox="0 0 36 36">
          <circle cx="18" cy="18" r="16" fill="#e0f2fe" />
          <path
            d="M12 21.5V14.5c0-.28.22-.5.5-.5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-.5.5c-.28 0-.5-.22-.5-.5zm5 0V14.5c0-.28.22-.5.5-.5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-.5.5c-.28 0-.5-.22-.5-.5zm5 0V14.5c0-.28.22-.5.5-.5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-.5.5c-.28 0-.5-.22-.5-.5z"
            fill="#94a3b8" />
        </svg>
        <p class="order_not_found">No orders found.</p>
      </div>
      <!-- Orders List -->
      <div v-else class="modern-product-listing_order">
        <div v-for="(orderObj, idxOrder) in order" :key="idxOrder" class="modern-product-card_order">
          <!-- Order Header -->
          <div class="order_time_details">
            <div>
              <div class="order_number_outer">Order #<span class="order_number_inner">{{ orderObj?.id || '-' }}</span></div>
              <div class="order_date_outer">Order date: <span class="order_date_inner">{{ orderObj?.order_date ? new Date(orderObj.order_date).toLocaleDateString() : '' }}</span></div>
            </div>
            <div class="order_number_container">
              <div class="premium-order-status-badge" :class="`status-${orderObj.order_status?.toLowerCase()}`">
                {{ orderObj?.order_status || 'Processing' }}
              </div>
            </div>
          </div>

          <div class="order_hr_line">
          </div>

          <!-- Fallback for the case where orderObj itself is a product item -->
          <div class="order_details modern-premium-order-card" v-if="orderObj?.order_items">
            <table class="premium-order-table" style="width: 100%; border-collapse: collapse;">
              <tbody v-for="orderItem in orderObj?.order_items" :key="orderItem.id" >
                <tr class="premium-order-item"
                  style="border-bottom: 1px solid #e0f2fe;">
                  <td class="premium-order-image-container" style="padding: 0.5em;">
                    <img
                      :src="orderItem.product?.image ? `/storage/${orderItem.product.image}` : '/images/no-photo.png'"
                      :alt="orderItem.product?.title ?? 'Product Image'" class="premium-order-image"
                      style="width: 48px; height: 48px; border-radius: 8px;">
                  </td>
                
                  <td class="premium-order-info" style="padding: 0.5em;">
                    <div class="premium-order-title">
                      {{ orderItem.product?.title ?? 'Product' }}
                    </div>
                    <div class="premium-order-description" v-if="orderItem?.product?.description">
                      {{ orderItem.product.description }}
                    </div>
                  </td>
                
                  <td class="premium-order-quantity" style="padding: 0.5em;">
                    <div style="font-size: 17px;">
                      <span class="premium-order-quantity-label">Qty:</span>
                      <span class="premium-order-quantity-value">
                        {{ orderItem.quantity ?? '1' }}
                      </span>
                    </div>
                    <div class="premium-order-total-amount" style="font-size: 16px;">
                      <span style="font-size: 14px;">{{ getSymbolFromCurrency(orderObj.currency) }}</span>
                      <span class="">{{ orderItem.price ?? '1' }}</span>                      
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
            <div class="premium-order-bottom">
              <div class="premium-order-total">
                <span class="show-total-text-order">
                  <span aria-hidden="true" style="margin-right: 2px;">💵</span>
                  Total 
                </span> &nbsp;
                <span class="show-total-amount-order">
                  {{getSymbolFromCurrency(orderObj.currency)}}{{ orderObj?.total_amount || 0 }}
                </span>
              </div>
            </div>
          </div>
          <div v-else class="order_product_not_found">No products found in this order.</div>
        </div>
      </div>
    </div>
    <!-- Fixed Checkout Footer -->
    <div v-if="total_value" class="cart-checkout-footer">
      <div class="cart-checkout-footer-inner">
        <div class="cart-total-amount-emoji">
          <div class="cart-total-amount-emoji-insider">
            <span class="show-total-text">
              <span aria-hidden="true" style="margin-right: 2px; font-size: 1.28em;">💵</span>
              Total
            </span>
            <span class="show-total-amount">
              <!-- Add checkout total if needed -->
              ${{ total_value }}
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
import { cartProducts, User } from '../store';
import getSymbolFromCurrency from 'currency-symbol-map';

const router = useRouter();
const userStore = User();
const { can } = useAbility();
const order = ref([]);
const show = ref(false);
const total_value = ref(0);

async function getOrderProduct() {
  const res = await axios.get('/api/get-order-product');
  if (res.data.success) {
    order.value.push(...res.data.order);
    total_value.value = order.value.reduce((total, item) => total + parseFloat(item.total_amount), 0).toFixed(2);
  }
}

onMounted(async () => {
  await getOrderProduct();
});

</script>