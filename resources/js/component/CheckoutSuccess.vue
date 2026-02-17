<template>
  <div></div>
</template>

<script setup>
import { onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import toastr from 'toastr';
import axios from 'axios';

const route = useRoute();
const router = useRouter();
let sessionId = '';
onMounted(async () => {
  sessionId = route.query.session_id;
  // Show a simple toast-only success message
  if(sessionId != ''){
    const res = await axios.get(`/api/set-status-cart-item/${sessionId}`);
    toastr.success('Payment successful. Thank you!');
    router.push({ name: 'product' });
  }
});
</script>

