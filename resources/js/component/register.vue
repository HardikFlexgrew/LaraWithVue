<template>
<div class="login-wrapper">
  <form class="login-form" @submit.prevent="handleLogin">
    <h2 class="login-title">
        <svg width="27" height="27" viewBox="0 0 24 24" fill="none" style="vertical-align:middle; margin-right:.5em;">
            <circle cx="12" cy="7" r="5" fill="#e0f2fe" stroke="#38bdf8" stroke-width="1.4"/>
            <rect x="4.5" y="14.5" width="15" height="7" rx="3.5" fill="#e0f2fe" stroke="#1197e6" stroke-width="1.2"/>
        </svg>
        Login
    </h2>
    <div class="form-group">
      <label for="email" class="login-label">Email Address</label>
      <input
        type="email"
        id="email"
        v-model="email"
        class="login-input"
        placeholder="Enter your email"
        required
        autocomplete="username"
      />
      <div class="login-error" v-if="errorMessage && errorMessage.email">
        <span>
          <svg width="14" height="14" fill="none" viewBox="0 0 20 20" style="margin-right:0.13em;">
            <circle cx="10" cy="10" r="9" fill="#fee2e2"/><path stroke="#db6060" d="M10 6v4"/><circle cx="10" cy="13.25" r=".75" fill="#db6060"/>
          </svg>
          {{ errorMessage.email[0] }}
        </span>
      </div>
    </div>
    <div class="form-group">
      <label for="password" class="login-label">Password</label>
      <input
        type="password"
        id="password"
        v-model="password"
        class="login-input"
        placeholder="Enter your password"
        required
        autocomplete="current-password"
      />
      <div class="login-error" v-if="errorMessage && errorMessage.password">
        <span>
          <svg width="14" height="14" fill="none" viewBox="0 0 20 20" style="margin-right:0.13em;">
            <circle cx="10" cy="10" r="9" fill="#fee2e2"/><path stroke="#db6060" d="M10 6v4"/><circle cx="10" cy="13.25" r=".75" fill="#db6060"/>
          </svg>
          {{ errorMessage.password[0] }}
        </span>
      </div>
    </div>
    <div class="form-actions">
      <button type="submit" class="login-btn">Log In</button>
    </div>
    <div v-if="generalError" class="login-error-general">
      <svg width="15" height="15" fill="none" viewBox="0 0 20 20" style="margin-right:0.17em;">
        <circle cx="10" cy="10" r="9" fill="#fee2e2"/><path stroke="#db6060" d="M10 6v4"/><circle cx="10" cy="13.25" r=".75" fill="#db6060"/>
      </svg>
      {{ generalError }}
    </div>
  </form>
</div>
</template>

<script setup>
  import { ref } from 'vue'
  import axios from 'axios'
  import { useRouter } from 'vue-router'  
  import toastr from 'toastr'
  import { User } from '../store'
  import { apiRequest } from '@/services/api'

  function reset(){
    email.value = '';
    password.value = '';
  }

  const router = useRouter()
  const userStore = User();
  const email = ref('')
  const password = ref('')
  const errorMessage = ref({})
  const generalError = ref('')
  
  if(userStore.loggedIn){
    router.push({name : 'product'});
  }

  async function handleLogin() {
    try {      
      const res = await apiRequest('/api/login', {
        email: email.value,
        password: password.value
      });

      if(res.data.success){
        userStore.loggedIn = true;  
        userStore.role = res.data.role;
        userStore.user = res.data.user;
        toastr.success(res.data.message);
        router.push({ name: 'product'});
        userStore.setUser(res.data.user);
        reset()
      }
    } catch (error) {
        router.push({ name: 'login' });
        toastr.error(error.response.data.message);
        reset()
    }
  }
</script>