<template>
  <div class="modern-product-listing cart-page-wrapper">
    <div class="modern-product-listing__grid" style="padding-bottom: 110px;">
      <!-- Empty state for no orders -->
      <div v-if="!order[0]?.length" class="modern-product-listing__empty_order" style="text-align:center; margin-top:60px;">
        <svg width="80" height="80" viewBox="0 0 36 36">
          <circle cx="18" cy="18" r="16" fill="#e0f2fe" />
          <path
            d="M12 21.5V14.5c0-.28.22-.5.5-.5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-.5.5c-.28 0-.5-.22-.5-.5zm5 0V14.5c0-.28.22-.5.5-.5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-.5.5c-.28 0-.5-.22-.5-.5zm5 0V14.5c0-.28.22-.5.5-.5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-.5.5c-.28 0-.5-.22-.5-.5z"
            fill="#94a3b8" />
        </svg>
        <p style="color:#7ea9c2;margin-top:16px;font-size:1.2em;">No orders found.</p>
      </div>
      <!-- Orders Grid -->
      <div
        v-for="orderItemObj in order[0]"
        :key="orderItemObj.id"
        class="order-card-redesign"
        style="margin-bottom:34px; background: #f7fbfc; border-radius: 18px; box-shadow: 0 2px 20px #bae6fd42, 0 1.5px 0 #7dd3fc1a; border: 1.5px solid #bae6fd;"
      >
        <!-- Order Top Bar -->
        <div style="display:flex; justify-content:space-between; align-items:center; padding: 18px 34px 12px 34px; border-bottom:1.5px solid #e0f2fe;">
          <div style="display:flex; align-items:center; gap:18px;">
            <span class="order-status-modern"
              :class="'status-badge--' + (orderItemObj?.order?.order_status?.toLowerCase() || '')"
              style="padding:6px 18px; background:#e0f7fa; color:#0284c7; border-radius:14px; font-weight:650;">
              <i class="fa fa-bolt" style="color:#38bdf8; margin-right:8px;"></i>
              {{ orderItemObj?.order?.order_status || "Processing" }}
            </span>
            <span style="color:#7ea9c2; font-size:1.06em; font-weight:590;">
              Order #{{ orderItemObj?.order?.id ?? '0' }}
            </span>
          </div>
          <div>
            <span style="color:#0284c7; font-weight:560; font-size:1.08em;">
              <i class="fa fa-calendar" style="margin-right:5px; color:#0ea5e9;"></i>
              {{ orderItemObj?.order?.order_date ? new Date(orderItemObj.order.order_date).toLocaleDateString() : '' }}
            </span>
          </div>
        </div>
        <!-- Order Items in 2-column row -->
        <div class="order-items-inline" style="display:flex; flex-wrap:wrap; gap:18px 24px; padding: 24px 34px; background:#fafdff;">
          <!-- Only show first 2 products/items per row -->
          <template v-if="Array.isArray(orderItemObj) && orderItemObj.length">
            <div
              v-for="(item, idx) in orderItemObj.slice(0, 2)"
              :key="item.id"
              class="inline-order-product"
              style="flex:1 1 44%; max-width:calc(50% - 12px); display:flex; background:#fff; border-radius:15px; box-shadow:0 1.5px 10px #bae6fd19; border:1px solid #e0f2fe; padding:18px 18px 16px 12px; gap:17px; min-width:220px;"
            >
            
              <!-- Product Thumb -->
              <div style="min-width:68px; max-width:68px; height:68px; border-radius:10px; overflow:hidden; background:#f0f9ff; display:flex; align-items:center; justify-content:center;">
                <img :src="`/storage/${item?.product.image}`"
                  :alt="item?.product?.name ?? 'Product Image'"
                  style="width:100%; height:100%; object-fit:cover; border-radius:9px;" />
              </div>
              <!-- Product Info -->
              <div style="flex:1; min-width:0;">
                <div style="font-weight:600; color:#0f172a; font-size:1.04em; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                  {{ item?.product?.title ?? 'Product' }}
                  <span v-if="item?.product?.sku" style="margin-left:8px; background:#e0f2fe; color:#38bdf8; border-radius:8px; font-size:0.92em; padding:3px 7px;">SKU: {{ item?.product?.sku }}</span>
                </div>
                <div v-if="item?.product?.description" style="color:#64748b; font-size:0.99em; margin-top:5px;line-height:1.6;max-width:32ch; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                  {{ item.product.description }}
                </div>
                <div style="display:flex; flex-wrap:wrap; gap:15px; margin-top:10px;">
                  <div>
                    <span style="color:#475569; font-size:0.97em;">Qty:</span>
                    <span style="color:#38bdf8; font-weight:600;">{{ item?.quantity ?? '1' }}</span>
                  </div>
                  <div>
                    <span style="color:#475569; font-size:0.97em;">Price:</span>
                    <span style="color:#0ea5e9; font-weight:700;">
                      {{ (item?.price || 0) | item?.product?.currency }}
                    </span>
                  </div>
                  <div v-if="item?.product?.discount">
                    <span style="color:#475569; font-size:0.97em;">Discount:</span>
                    <span style="color:#16a34a; font-weight:600;">
                      -{{ item.product.discount }} {{item.product.currency || '$' }}
                    </span>
                  </div>
                </div>
                <div v-if="item?.shipping_tracking" style="margin-top:5px; color:#0ea5e9; font-size:0.97em;">
                  <b>Tracking:</b> {{ item.shipping_tracking }}
                </div>
              </div>
            </div>
          </template>
          <!-- Fallback for single "orderItemObj" style API -->
          <template v-else>
            <div
              v-if="orderItemObj?.product"
              class="inline-order-product"
              style="flex:1 1 44%; max-width:calc(50% - 12px); display:flex; background:#fff; border-radius:15px; box-shadow:0 1.5px 10px #bae6fd19; border:1px solid #e0f2fe; padding:18px 18px 16px 12px; gap:17px; min-width:220px;"
            >
              <div style="min-width:68px; max-width:68px; height:68px; border-radius:10px; overflow:hidden; background:#f0f9ff; display:flex; align-items:center; justify-content:center;">
                <img :src="orderItemObj?.product?.image || '/images/no-photo.png'"
                  :alt="orderItemObj?.product?.name ?? 'Product Image'"
                  style="width:100%; height:100%; object-fit:cover; border-radius:9px;" />
              </div>
              <div style="flex:1; min-width:0;">
                <div style="font-weight:600; color:#0f172a; font-size:1.04em; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                  {{ orderItemObj?.product?.title ?? 'Product' }}
                  <span v-if="orderItemObj?.product?.sku" style="margin-left:8px; background:#e0f2fe; color:#38bdf8; border-radius:8px; font-size:0.92em; padding:3px 7px;">SKU: {{ orderItemObj?.product?.sku }}</span>
                </div>
                <div v-if="orderItemObj?.product?.description" style="color:#64748b; font-size:0.99em; margin-top:5px;line-height:1.6;max-width:32ch; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                  {{ orderItemObj.product.description }}
                </div>
                <div style="display:flex; flex-wrap:wrap; gap:15px; margin-top:10px;">
                  <div>
                    <span style="color:#475569; font-size:0.97em;">Qty:</span>
                    <span style="color:#38bdf8; font-weight:600;">{{ orderItemObj?.product?.quantity ?? '1' }}</span>
                  </div>
                  <div>
                    <span style="color:#475569; font-size:0.97em;">Price:</span>
                    <span style="color:#0ea5e9; font-weight:700;">{{ (orderItemObj?.product?.price || 0) | orderItemObj?.product?.currency }}</span>
                  </div>
                </div>
                <div v-if="orderItemObj?.shipping_tracking" style="margin-top:5px; color:#0ea5e9; font-size:0.97em;">
                  <b>Tracking:</b> {{ orderItemObj.shipping_tracking }}
                </div>
              </div>
            </div>
            <div v-else style="color:#94a3b8; text-align:center; font-size:1.1em; font-weight:540; padding:56px 0;">
              No products found in this order.
            </div>
          </template>
        </div>
        <!-- Order summary/footer -->
        <div style="border-top:1.5px solid #e0f2fe; background:rgba(236,252,255,0.78); padding:1em 32px; display:flex; align-items:flex-start; gap:36px; flex-wrap:wrap;">
          <div style="flex:1; min-width:180px;">
            <div style="font-size:1.13em; font-weight:650; color:#0ea5e9; margin-bottom:3px;">
              Total: <span style="color:#0ea5e9;">{{ (orderItemObj?.order?.total_amount | orderItemObj?.order?.currency) ?? '' }}</span>
            </div>
            <span class="payment-status-modern"
              :class="'payment-badge--' + (orderItemObj?.order?.payment_status?.toLowerCase()||'')"
              style="padding:6px 16px; border-radius:11px; background:#e0f2fe; font-weight:540; color:#38bdf8; letter-spacing:0.06em;">
              <i class="fa fa-credit-card" style="margin-right: 6px; color:#7dd3fc;"></i>
              {{ orderItemObj?.order?.payment_status || 'Unpaid' }}
            </span>
          </div>
          <div style="flex:2; min-width:240px; color:#64748b; font-size: 1.04em;">
            <div style="margin-bottom:6px;">
              <span class="address-label" style="color:#38bdf8">Ship to:</span>
              <span style="color:#0ea5e9; font-weight:500;">{{ orderItemObj?.order?.billing_address?.address ?? 'N/A' }}</span>
            </div>
            <div v-if="orderItemObj?.order?.billing_address?.email">
              <span class="address-label" style="color:#38bdf8">Email:</span>
              <span style="color:#0ea5e9;">{{ orderItemObj?.order?.billing_address?.email ?? '' }}</span>
            </div>
          </div>
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

const router = useRouter();
const userStore = User();
const { can } = useAbility();
const order = ref([]);

async function getOrderProduct() {
  const res = await axios.get('/api/get-order-product');
  if(res.data.success){ 
    order.value.push(res.data.orderItems);
  }
}

onMounted(async () => { 
  getOrderProduct();    
});
console.log(order.value);

</script>