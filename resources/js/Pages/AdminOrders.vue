<template>
  <div class="admin-orders-modern-wrapper">
    <div class="admin-orders-header">
      
      <input
        type="text"
        v-model="filterText"
        ref="typedInput"
        :placeholder="filterPlaceholder"
        class="admin-orders-search"
      />
      <div class="admin-orders-controls">
        <select v-model="payment_status" class="admin-orders-sort">
          <option value="">All Status</option>
          <option value="succeeded">Succeeded</option>
          <option value="pending">Pending</option>
        </select>
        <select v-model="filterStatus" class="admin-orders-filter">
          <option value="">All Status</option>
          <option value="completed">Completed</option>
          <option value="pending">Pending</option>
        </select>
      </div>
    </div>
    <div class="admin-orders-table-container">
      <table class="admin-orders-modern-table">
        <thead>
          <tr>
            <th>#Order ID</th>
            <th>Customer</th>
            <th>Purchased</th>
            <th>Total order</th>
            <th>Payment</th>
            <th>Payment status</th>
            <th>Order status</th>
            <th>Date</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="order in filteredOrders" :key="order.id">
            <!-- {{ order }} -->
            <td class="order-id">#{{ order.id }}</td>
            <td class="customer-cell">
              <img v-if="order.user?.avatar" :src="order.user.avatar" class="customer-avatar" />
              <span class="customer-name">{{ order.users?.name || '-' }}</span>
            </td>
            <td>
                <div class="order-products-images">
                  <template v-if="Array.isArray(order.order_items) && order.order_items.length">
                    <img
                      v-for="(item, idx) in order.order_items"
                      :key="item.id || idx"
                      :src="`/storage/${item.product.image}`"
                      class="order-product-thumb"
                      :alt="item.name"
                      style="height: 34px; width: 34px; object-fit: cover; margin-right: 4px; border-radius: 4px; border: 1px solid #eee;"
                    />
                  </template>
                  <template v-else>
                    <span style="color: #aaa;">No products</span>
                  </template>
                </div>
            </td>
            <td>${{ order.total_amount }}</td>
            <td>{{ order.payment_details.payment_method || '-' }}</td>
            <td>
              <b-badge style="font-size: 16px;margin-left: 15px;" pill :variant="order.payment_status === 'succeeded' ? 'success' : 'warning'">
                {{ order.payment_status === "succeeded" ? 'Succeeded' : 'Pending' }}
              </b-badge>
            </td>
            <td>
              <span :class="['status-badge', 'status-' + (order.order_status?.toLowerCase() || 'processing')]">
                {{ order.order_status || 'Processing' }}
              </span>
            </td>
            <td>{{ formatDate(order.order_date) }}</td>
          </tr>
        </tbody>
      </table>
      <div v-if="!filteredOrders.length" class="admin-orders-empty">
        <svg width="90" height="90" viewBox="0 0 36 36">
          <circle cx="18" cy="18" r="16" fill="#e0f2fe" />
          <path d="M12 21.5V14.5c0-.28.22-.5.5-.5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-.5.5c-.28 0-.5-.22-.5-.5zm5 0V14.5c0-.28.22-.5.5-.5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-.5.5c-.28 0-.5-.22-.5-.5zm5 0V14.5c0-.28.22-.5.5-.5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-.5.5c-.28 0-.5-.22-.5-.5z" fill="#94a3b8" />
        </svg>
        <p>No orders found.</p>
      </div>
    </div>
    <div v-if="userOrders.length" class="admin-user-orders-modal">
      <div class="admin-user-orders-modal-content">
        <h3>User Orders</h3>
        <button class="close-modal-btn" @click="userOrders = []">&times;</button>
        <ul>
          <li v-for="order in userOrders" :key="order.id">
            Order #{{ order.id }} - {{ order.order_status }} - ${{ order.total_amount }}
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import Typed from 'typed.js';
import axios from 'axios';

const orders = ref([]);
const userOrders = ref([]);
const filterText = ref('');
const payment_status = ref('');
const filterStatus = ref('');
const filterPlaceholder = ref('');
const typedInput = ref(null);
let typed = null;

async function fetchOrders() {
  const res = await axios.get('/api/admin/orders');
  orders.value = res.data.orders || [];
}

function formatDate(date) {
  if (!date) return '-';
  const d = new Date(date);
  return `${d.toLocaleDateString()} ${d.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', hour12 : true })}`;
}

const filteredOrders = computed(() => {
  let search = filterText.value.toLowerCase().trim();
  return orders.value.filter((item) => {
    const matchSearch = !search || (
      item.order_status?.includes(search) ||
      item.payment_status?.includes(search) ||
      String(item.id)?.includes(search) ||
      item.payment_details?.payment_method?.includes(search) ||
      item.order_date?.includes(search) ||
      item.users?.name?.includes(search) ||
      String(item.total_amount)?.includes(search)
    );

    const matchOrderStatus = !filterStatus.value || item.order_status === filterStatus.value;
    const matchPaymentStatus = !payment_status.value || item.payment_status === payment_status.value;

    return matchSearch && matchOrderStatus && matchPaymentStatus;
  });
});

onMounted(async() => {
  await fetchOrders();
  if (typedInput.value) {
    typed = new Typed(typedInput.value, {
      strings: [
        'Search by order ID, prices, customers...',
        'Search orders...'
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