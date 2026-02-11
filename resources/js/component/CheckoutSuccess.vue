<template>
  <div></div>
</template>

<script setup>
import { onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import toastr from 'toastr';

const route = useRoute();
const router = useRouter();

onMounted(() => {
  const sessionId = route.query.session_id;
  // Show a simple toast-only success message
  toastr.success('Payment successful. Thank you!');
  // Redirect back to product listing after a short delay
  setTimeout(() => router.push({ name: 'product' }), 1200);
});
</script>

