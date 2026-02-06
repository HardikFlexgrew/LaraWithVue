<template>
  <header class="header-modern aesthetic-shadow">
    <div class="header__container-modern">
      <div class="header__logo-modern">
        <span class="logo-icon">
          <svg width="40" height="40" viewBox="0 0 48 48" fill="none">
            <circle cx="24" cy="24" r="21" fill="#fff"/>
            <circle cx="24" cy="18" r="8" stroke="#0ea5e9" stroke-width="2.2" fill="#bae6fd"/>
            <ellipse cx="24" cy="32.5" rx="14" ry="8" stroke="#38bdf8" stroke-width="2.2" fill="#f0f9ff"/>
            <path d="M14 26c3-3 8-3 10 0 2-1 7-2 10 0" stroke="#0ea5e9" stroke-width="1.5" stroke-linecap="round"/>
          </svg>
        </span>
        <span class="logo-text-modern animate-gradient">LaraWithVue</span>
      </div>
      <nav :class="['header__nav-modern', { 'header__nav--open-modern': navOpen }]">
        <ul class="nav-list-modern">
          <li v-if="userStore.loggedIn">
            <RouterLink class="nav-link-modern nav-link-icon" :to="{name : 'product'}">
              <span class="icon">
                <svg width="20" height="20" fill="none"><path stroke="#0ea5e9" stroke-width="2" d="M4 10h12M4 14h8M4 6h10" stroke-linecap="round"/></svg>
              </span>
              Product
            </RouterLink>
          </li>
          <li v-if="userStore.loggedIn && can('add','product')">
            <RouterLink class="nav-link-modern nav-link-icon" :to="{name : 'add_product'}">
              <span class="icon">
                <svg width="19" height="19" fill="none"><circle cx="9.5" cy="9.5" r="8.5" stroke="#0ea5e9" stroke-width="2"/><path d="M9.5 6v7M6 9.5h7" stroke="#38bdf8" stroke-width="1.7" stroke-linecap="round"/></svg>
              </span>
              Add Product
            </RouterLink>
          </li>
          <li v-if="userStore.loggedIn">
            <!-- Add to cart Btn -->
            <RouterLink class="nav-link-modern nav-link-icon" :to="{name : 'cart'}">
              <span class="icon">
                <svg width="21" height="21" fill="none" viewBox="0 0 24 24">
                  <circle cx="9" cy="21" r="1.5" fill="#0ea5e9"/>
                  <circle cx="18" cy="21" r="1.5" fill="#0ea5e9"/>
                  <path d="M5.5 6H20l-1.31 7.34A2 2 0 0 1 16.73 15H7.35a2 2 0 0 1-1.97-1.67L4 4H2" stroke="#38bdf8" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
              </span>
              Cart
            </RouterLink>
          </li>
        </ul>
        <div class="header__actions--mobile-modern" v-if="!userStore.loggedIn">
          <RouterLink class="nav-link-modern" :to="{name : 'login'}">
            <button class="sign-in-btn-modern">
              <svg width="18" height="18" fill="none" style="margin-right:5px;vertical-align:middle"><path d="M13 9H5M9 5l-4 4 4 4" stroke="#fff" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/><circle cx="9" cy="9" r="8" stroke="#bae6fd" stroke-width="1"/></svg>
              Sign In
            </button>
          </RouterLink>
        </div>
      </nav>
      <div class="header__actions-modern">
        <!-- User Details and Logout Button together at right side for desktop -->
        <template v-if="userStore.loggedIn">
          <div class="modern-user-pill user-pill-glass" style="margin-right:0;">
            <span class="user-avatar">
              <svg width="30" height="30" viewBox="0 0 40 40" style="background:#fff;border-radius:50%;box-shadow:0 2px 12px #bae6fd40;" fill="none">
                <circle cx="20" cy="13.5" r="8.3" stroke="#0ea5e9" stroke-width="2" fill="#bae6fd"/>
                <ellipse cx="20" cy="28.8" rx="13.5" ry="7.2" stroke="#0ea5e9" stroke-width="2" fill="#f0f9ff"/>
              </svg>
            </span>
            <div class="user-details">
              <span class="user-email">
                <svg width="15" height="15" style="margin-right:0.23em;margin-bottom:-3px;width: 17px;height: 30px;" viewBox="0 0 24 24" fill="none"><path d="M2 8l10 7 10-7" stroke="#38bdf8" stroke-width="1.4" fill="#fff"/><rect x="2" y="6" width="20" height="12" rx="2.7" stroke="#fff" stroke-width="1.1" fill="none"/></svg>
                {{ userStore?.user?.email }}
              </span>
              <span v-if="userStore.role" class="user-role">
                {{ userStore?.role }}
              </span>
            </div>
            <button class="sign-in-btn-modern hide-on-mobile-modern nav-link-icon-logout" style="margin-left:1.1em;" @click="handleLogout()">
              <svg width="17" height="17" fill="#000" style="margin-right:5px;vertical-align:middle"><path d="M12 13.2v-2.25M12 9.5V6.75M12 9.5l1.85-2m-1.85 2L10.15 7.5" stroke="#fff" stroke-width="10" stroke-linecap="round"/><rect x="3" y="3" width="11" height="11" rx="2.5" stroke="#bae6fd" stroke-width="1"/></svg>
              Logout
            </button>
          </div>
        </template>
        <button class="menu-btn-modern" @click="toggleNav" aria-label="Open Menu">
          <svg v-if="!navOpen" width="32" height="32" viewBox="0 0 24 24"><circle cx="12" cy="12" r="11" stroke="#38bdf8" stroke-width="1.3" fill="none"/><path stroke="#0ea5e9" stroke-width="2.2" fill="none" stroke-linecap="round" stroke-linejoin="round" d="M7 9h10M7 12h10M7 15h10"/></svg>
          <svg v-else width="32" height="32" viewBox="0 0 24 24"><circle cx="12" cy="12" r="11" stroke="#38bdf8" stroke-width="1.3" fill="none"/><path stroke="#0ea5e9" stroke-width="2.2" fill="none" stroke-linecap="round" stroke-linejoin="round" d="M8 16L16 8M8 8l8 8"/></svg>
        </button>
      </div>
    </div>
  </header>
</template>

<script setup>
import { ref, onMounted, watch } from "vue";
import { RouterLink } from "vue-router";
import { User } from "@/store";
import { useRouter } from "vue-router";
import axios from "axios";
import { useAbility } from "@casl/vue";

const navOpen = ref(false);
const userStore = User();
const router = useRouter();
const { can } = useAbility();

onMounted(()=>{
  if(!userStore.loggedIn){
    router.push({name : 'login'})
  }

});

watch(
  ()=>userStore.loggedIn,
  ()=>{
    console.log(userStore.loggedIn);  
    if(!userStore.loggedIn){
      router.push({name : 'login'})
    }
  }
)

function toggleNav() {
  navOpen.value = !navOpen.value;
}

async function handleLogout(){
 const res = await userStore.logout();
 if(res.data.success){
  router.push({name : 'login'});
 }
}
</script>