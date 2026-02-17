<template>
  <div class="login-wrapper">
    <form
      class="login-form"
      :class="register ? 'login-form-register' : ''"
      :style="register ? 'max-width: 680px' : 'max-width: 400px'" 
      @submit.prevent="register ? handleRegister() : handleLogin()"
    >
      <h2 class="login-title">
        <svg width="27" height="27" viewBox="0 0 24 24" fill="none" style="vertical-align:middle; margin-right:.5em;">
          <circle cx="12" cy="7" r="5" fill="#e0f2fe" stroke="#38bdf8" stroke-width="1.4" />
          <rect x="4.5" y="14.5" width="15" height="7" rx="3.5" fill="#e0f2fe" stroke="#1197e6" stroke-width="1.2" />
        </svg>
        {{ register ? 'Register' : 'Login' }}
      </h2>
      <!-- Register form -->
      <template v-if="register">
        <div class="form-row form-row-name-email">
          <div class="form-group form-group-inline">
            <label for="name" class="login-label">Name</label>
            <input type="text" id="name" v-model="name" class="login-input" placeholder="Enter your name" required autocomplete="username" />
            <div class="login-error" v-if="errorMessage && errorMessage.name">
              <span>
                <svg width="14" height="14" fill="none" viewBox="0 0 20 20" style="margin-right:0.13em;">
                  <circle cx="10" cy="10" r="9" fill="#fee2e2" />
                  <path stroke="#db6060" d="M10 6v4" />
                  <circle cx="10" cy="13.25" r=".75" fill="#db6060" />
                </svg>
                {{ errorMessage.name[0] }}
              </span>
            </div>
          </div>
          <div class="form-group form-group-inline">
            <label for="email" class="login-label">Email Address</label>
            <input type="email" id="email" v-model="email" class="login-input" placeholder="Enter your email" required autocomplete="username" />
            <div class="login-error" v-if="errorMessage && errorMessage.email">
              <span>
                <svg width="14" height="14" fill="none" viewBox="0 0 20 20" style="margin-right:0.13em;">
                  <circle cx="10" cy="10" r="9" fill="#fee2e2" />
                  <path stroke="#db6060" d="M10 6v4" />
                  <circle cx="10" cy="13.25" r=".75" fill="#db6060" />
                </svg>
                {{ errorMessage.email[0] }}
              </span>
            </div>
          </div>
        </div>
        <div class="form-group">
          <label for="address" class="login-label">Address</label>
          <textarea
            id="address"
            v-model="address"
            class="login-input"
            placeholder="Enter your address"
            required
            autocomplete="address-line1"
            rows="2"
            style="resize:vertical"
          ></textarea>
          <div class="login-error" v-if="errorMessage && errorMessage.address">
            <span>
              <svg width="14" height="14" fill="none" viewBox="0 0 20 20" style="margin-right:0.13em;">
                <circle cx="10" cy="10" r="9" fill="#fee2e2" />
                <path stroke="#db6060" d="M10 6v4" />
                <circle cx="10" cy="13.25" r=".75" fill="#db6060" />
              </svg>
              {{ errorMessage.address[0] }}
            </span>
          </div>
        </div>
        <div class="reg-form-row">
          <div class="form-group form-group-inline">
            <label for="city" class="login-label">City</label>
            <input
              type="text"
              id="city"
              v-model="city"
              class="login-input"
              placeholder="City"
              required
              autocomplete="address-level2"
            />
            <div class="login-error" v-if="errorMessage && errorMessage.city">
              <span>
                <svg width="14" height="14" fill="none" viewBox="0 0 20 20" style="margin-right:0.13em;">
                  <circle cx="10" cy="10" r="9" fill="#fee2e2" />
                  <path stroke="#db6060" d="M10 6v4" />
                  <circle cx="10" cy="13.25" r=".75" fill="#db6060" />
                </svg>
                {{ errorMessage.city[0] }}
              </span>
            </div>
          </div>
          <div class="form-group form-group-inline">
            <label for="state" class="login-label">State</label>
            <input
              type="text"
              id="state"
              v-model="state"
              class="login-input"
              placeholder="State"
              required
              autocomplete="address-level1"
            />
            <div class="login-error" v-if="errorMessage && errorMessage.state">
              <span>
                <svg width="14" height="14" fill="none" viewBox="0 0 20 20" style="margin-right:0.13em;">
                  <circle cx="10" cy="10" r="9" fill="#fee2e2" />
                  <path stroke="#db6060" d="M10 6v4" />
                  <circle cx="10" cy="13.25" r=".75" fill="#db6060" />
                </svg>
                {{ errorMessage.state[0] }}
              </span>
            </div>
          </div>
          <div class="form-group form-group-inline">
            <label for="country" class="login-label">Country</label>
            <select
              id="country"
              v-model="country"
              class="login-input"
              required
              autocomplete="country"
            >
              <option value="" disabled>Select country</option>
              <option v-for="c in countries" :key="c" :value="c">{{ c }}</option>
            </select>
            <div class="login-error" v-if="errorMessage && errorMessage.country">
              <span>
                <svg width="14" height="14" fill="none" viewBox="0 0 20 20" style="margin-right:0.13em;">
                  <circle cx="10" cy="10" r="9" fill="#fee2e2" />
                  <path stroke="#db6060" d="M10 6v4" />
                  <circle cx="10" cy="13.25" r=".75" fill="#db6060" />
                </svg>
                {{ errorMessage.country[0] }}
              </span>
            </div>
          </div>
        </div>
        <div class="row reg-password-row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="password" class="login-label">Password</label>
              <input type="password" id="password" name="password" v-model="password" class="login-input" placeholder="Enter password"
                required autocomplete="current-password" />
              <div class="login-error" v-if="errorMessage && errorMessage.password">
                <span>
                  <svg width="14" height="14" fill="none" viewBox="0 0 20 20" style="margin-right:0.13em;">
                    <circle cx="10" cy="10" r="9" fill="#fee2e2" />
                    <path stroke="#db6060" d="M10 6v4" />
                    <circle cx="10" cy="13.25" r=".75" fill="#db6060" />
                  </svg>
                  {{ errorMessage.password[0] }}
                </span>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="password_confirmation" class="login-label">Confirm Password</label>
              <input 
                type="password" 
                @input="checkPassword"
                id="password_confirmation" 
                name="password_confirmation"
                v-model="password_confirmation"
                class="login-input" 
                placeholder="Enter confirm password"
                required 
                autocomplete="current-password" />
              <div class="login-error" v-if="errorMessage && errorMessage.password_confirmation">
                <span>
                  <svg width="14" height="14" fill="none" viewBox="0 0 20 20" style="margin-right:0.13em;">
                    <circle cx="10" cy="10" r="9" fill="#fee2e2" />
                    <path stroke="#db6060" d="M10 6v4" />
                    <circle cx="10" cy="13.25" r=".75" fill="#db6060" />
                  </svg>
                  {{ errorMessage.password_confirmation[0] }}
                </span>
              </div>
            </div>
          </div>
        </div>
      </template>
      <!-- Login form -->
      <template v-else>
        <div class="form-group">
          <label for="email" class="login-label">Email Address</label>
          <input type="email" id="email" v-model="email" class="login-input" placeholder="Enter your email" required autocomplete="username" />
          <div class="login-error" v-if="errorMessage && errorMessage.email">
            <span>
              <svg width="14" height="14" fill="none" viewBox="0 0 20 20" style="margin-right:0.13em;">
                <circle cx="10" cy="10" r="9" fill="#fee2e2" />
                <path stroke="#db6060" d="M10 6v4" />
                <circle cx="10" cy="13.25" r=".75" fill="#db6060" />
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
            placeholder="Enter password"
            required
            autocomplete="current-password"
            style="border-radius:7px"
          />
          <div class="login-error" v-if="errorMessage && errorMessage.password">
            <span>
              <svg width="14" height="14" fill="none" viewBox="0 0 20 20" style="margin-right:0.13em;">
                <circle cx="10" cy="10" r="9" fill="#fee2e2" />
                <path stroke="#db6060" d="M10 6v4" />
                <circle cx="10" cy="13.25" r=".75" fill="#db6060" />
              </svg>
              {{ errorMessage.password[0] }}
            </span>
          </div>
        </div>
      </template>
      <div class="form-actions" style="margin-top:1.6em">
        <button type="submit" class="login-btn"
          style="background: linear-gradient(90deg, #38bdf8, #818cf8); color:#fff; font-weight:600; border-radius:8px; box-shadow:0 2px 8px #93c5fd55; min-width:120px; font-size:1.15rem;">
          {{ register ? 'Register' : 'Log In' }}
        </button>
      </div>
      <div class="login-error-general" style="display:flex; align-items:center; justify-content:center; padding-top:.7em">
        <svg width="15" height="15" fill="none" viewBox="0 0 20 20" style="margin-right:0.17em;">
          <circle cx="10" cy="10" r="9" fill="#d5d8f0" />
          <path stroke="#2d83c1" d="M10 6v4" />
          <circle cx="10" cy="13.25" r=".75" fill="#db6060" />
        </svg>
        <span v-if="!register" style="color:rgb(28, 150, 230);">If you are new user,
          <RouterLink :to="{ name: 'register', params: { 'register': true } }" style="color:#1c96e6; font-weight:500">Register</RouterLink>
        </span>
        <span v-else style="color:rgb(28, 150, 230);">If you already have an account,
          <RouterLink :to="{ name: 'login' }" style="color:#1c96e6; font-weight:500">Login</RouterLink>
        </span>
      </div>
    </form>
  </div>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'
import toastr from 'toastr'
import { User } from '../store'
import { apiRequest } from '@/services/api'

const props = defineProps({
  register: String,
});

function reset() {
  email.value = '';
  password.value = '';
  address.value = '';
  state.value = '';
  city.value = '';
  country.value = '';
}

// static list of countries for select options - customize as required
const countries = [
  'United States', 'Canada', 'India', 'United Kingdom', 'Australia',
  'Germany', 'France', 'Brazil', 'Japan', 'China',
  // ...add more countries as needed
];

const router = useRouter()
const userStore = User();
const name = ref('');
const email = ref('');
const password = ref('');
const password_confirmation = ref('');
const address = ref('');
const state = ref('');
const city = ref('');
const country = ref('');
const errorMessage = ref({})
const generalError = ref('')

if (userStore.loggedIn) {
  router.push({ name: 'product' });
}

async function getCountry(){
  const res = await axios.get(`/api/login/get_country`);
  console.log(res);
}

onMounted(async ()=>{
  getCountry();
});

function checkPassword(e) {
  const pasCheck = password.value.includes(e.target.value);
  if (pasCheck) {
    e.target.style.borderColor = 'green';
  } else {
    e.target.style.borderColor = 'red';
  }
}

async function handleRegister() {
  if(password_confirmation.value != password.value){
    toastr.error('Password can\'t matches');
    return;
  } else {
    try{
      const formData = new FormData();
      formData.append('name', name.value);
      formData.append('email', email.value);
      formData.append('password', password.value);
      formData.append('password_confirmation', password_confirmation.value);
      formData.append('address', address.value);
      formData.append('state', state.value);
      formData.append('city', city.value);
      formData.append('country', country.value);

      const res = await axios.post('/api/register', formData);

      if(res.data.success){
        toastr.success(res.data.message);
        userStore.loggedIn = true;
        if(res.data.user) userStore.setUser(res.data.user);
        if(res.data.role) userStore.role = res.data.role;
        await router.push({name:'product'});
      }
    } catch(err){
      toastr.error(err.response?.data?.message ?? 'Registration failed');
      // Optionally, you can show validation error details from backend:
      if (err.response && err.response.data && err.response.data.errors) {
        errorMessage.value = err.response.data.errors;
      }
    }
  }
}

async function handleLogin() {
  try {
    const res = await apiRequest('/api/login', {
      email: email.value,
      password: password.value
    });

    if (res.data.success) {
      userStore.loggedIn = true;
      userStore.role = res.data.role;
      userStore.user = res.data.user;
      toastr.success(res.data.message);
      router.push({ name: 'product' });
      userStore.setUser(res.data.user);
      reset()
    }
  } catch (error) {
    router.push({ name: 'login' });
    toastr.error(error.response?.data?.message ?? 'Login failed');
    reset()
  }
}
</script>