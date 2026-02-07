<template>
  <div v-if="loading" class="modern-loader-overlay">
    <div class="modern-loader-spinner"></div>
  </div>
  <div v-else class="modern-add-product-container">
    <form class="modern-add-product-form" @submit.prevent="handleSubmit" enctype="multipart/form-data">
      <h2 class="modern-form-title">
        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" style="margin-right: .5em;">
          <rect x="2" y="7" width="20" height="15" rx="3.5" fill="#e0f2fe" stroke="#38bdf8" stroke-width="1.3"/>
          <path d="M6 7V5.5A2.5 2.5 0 0 1 8.5 3h7A2.5 2.5 0 0 1 18 5.5V7" stroke="#1197e6" stroke-width="1.2" />
        </svg>
        {{ id ? 'Update Product' : 'Add New Product'}}
      </h2>
      <!-- All form fields stacked in single block columns except last line -->
      <div class="modern-grid-form" style="display: flex; flex-direction: column; gap: 1.3rem;">
        <div class="modern-wide-group">
          <label for="title" class="modern-form-label">Product Title</label>
          <input 
            type="text" 
            id="title" 
            v-model="title"
            class="modern-form-input" 
            placeholder="Enter product title"
            name="title"
            autocomplete="off"
            style="display:block;width:100%;"
          />
          <div class="modern-errorMessage">
            <span v-if="errorMessage['title']" class="modern-error-message">
              <svg width="16" height="16" fill="none" viewBox="0 0 20 20" style="margin-right:0.17em;"><circle cx="10" cy="10" r="9" fill="#fee2e2"/><path stroke="#db6060" d="M10 6v4"/><circle cx="10" cy="13.25" r=".75" fill="#db6060"/></svg>
              {{ errorMessage['title'][0] }}
            </span>
          </div>
        </div>

        <div class="modern-wide-group">
          <label for="description" class="modern-form-label">Description</label>
          <textarea 
            id="description" 
            v-model="description" 
            class="modern-form-input"
            placeholder="Enter description"
            rows="3"
            name="description"
            style="display:block;width:100%;"
          ></textarea>
          <div class="modern-errorMessage">
            <span v-if="errorMessage['description']" class="modern-error-message">
              <svg width="16" height="16" fill="none" viewBox="0 0 20 20" style="margin-right:0.17em;"><circle cx="10" cy="10" r="9" fill="#fee2e2"/><path stroke="#db6060" d="M10 6v4"/><circle cx="10" cy="13.25" r=".75" fill="#db6060"/></svg>
              {{ errorMessage['description'][0] }}
            </span>
          </div>
        </div>

        <!-- Price & Image on same row as last line -->
        <div class="modern-row-group-last" style="display: flex; gap: 2.2rem; align-items: flex-start; flex-wrap: wrap;">
          <div class="modern-wide-group" style="flex: 16 1 205px; min-width: 180px; max-width: 260px;">
            <label for="price" class="modern-form-label">Price&nbsp;($)</label>
            <input 
              type="number" 
              id="price" 
              v-model="price" 
              class="modern-form-input" 
              min="0" 
              step="0.01" 
              placeholder="0.00"
              name="price"
              style="display:block;width:100%;"
            />
            <div class="modern-errorMessage">
              <span v-if="errorMessage['price']" class="modern-error-message">
                <svg width="16" height="16" fill="none" viewBox="0 0 20 20" style="margin-right:0.17em;"><circle cx="10" cy="10" r="9" fill="#fee2e2"/><path stroke="#db6060" d="M10 6v4"/><circle cx="10" cy="13.25" r=".75" fill="#db6060"/></svg>
                {{ errorMessage['price'][0] }}
              </span>
            </div>
          </div>
          <div class="modern-wide-group" style="flex: 2 1 290px; min-width: 220px;">
            <label for="image" class="modern-form-label">Image</label>
            <div class="modern-image-upload-flex modern-form-input" style="flex-direction: column;">
              <input 
                type="file" 
                id="image" 
                @change="handleImageUpload" 
                class="modern-form-input-file form-control" 
                accept=".jpg,.jpeg,.png"
                name="image" 
                style="display:block;width:100%;"
              />
              <div v-if="previewImage" class="modern-preview-image-wrapper-vertical">
                <img :src="previewImage" alt="Image Preview" class="modern-preview-image" />
              </div>
            </div>
            <div class="modern-errorMessage">
              <span v-if="errorMessage['image']" class="modern-error-message">
                <svg width="16" height="16" fill="none" viewBox="0 0 20 20" style="margin-right:0.17em;"><circle cx="10" cy="10" r="9" fill="#fee2e2"/><path stroke="#db6060" d="M10 6v4"/><circle cx="10" cy="13.25" r=".75" fill="#db6060"/></svg>
                {{ errorMessage['image'][0] }}
              </span>
            </div>
          </div>
        </div>

      </div>
      <div class="modern-form-actions">
        <button type="submit" class="modern-submit-btn modern-neon-btn">
          <span style="display:flex;align-items:center;">
            <svg v-if="id" width="20" height="20" fill="none" viewBox="0 0 24 24" style="margin-right:.39em"><path d="M17.804 8.34l-2.122-2.12a1.2 1.2 0 0 0-1.697 0l-7.01 7.008a1.2 1.2 0 0 0-.296.49l-1.193 3.412a.483.483 0 0 0 .616.615l3.412-1.192a1.2 1.2 0 0 0 .491-.297l7.009-7.01a1.2 1.2 0 0 0 0-1.697z" stroke="#fff" stroke-width="1.5"/><path d="M14.5 6.5l3 3" stroke="#fff" stroke-width="1.5"/></svg>
            <svg v-else width="20" height="20" fill="none" viewBox="0 0 24 24" style="margin-right:.39em"><path d="M12 5v14M5 12h14" stroke="#fff" stroke-width="1.8" stroke-linecap="round"/></svg>
            {{ id ? 'Update Product' : 'Create Product' }}
          </span>
        </button>
      </div>
    </form>
  </div>
</template>

<script setup>
import axios from 'axios'
import {ref} from 'vue'
import toastr from 'toastr'
import { useRouter } from 'vue-router';
import { useAbility } from '@casl/vue';
import { User } from '@/store';

const props = defineProps({
  id : String,
});
const userStore = User();
const router = useRouter();
const title = ref('')
const description = ref('')
const price = ref('')
const imageFile = ref(null)  // store the file object
const previewImage = ref(null) // for image preview
const errorMessage = ref([]);
const loading = ref(false);
const {can} = useAbility();

async function getEditProductDetails(productId) {
  if(can('edit','product')){
    try { 
      const res = await axios.post(`/api/product/edit/${productId}`)  ;
      if(res.data.success){
        return res.data.product;
      } 
    } catch(err){
      toastr.error(err.response.data.message, 'Error', {
          timeOut: 2000,
          progressBar: true,
          closeButton: true
        });
    }
  } else {
    router.push({name :'product'});
    toastr.error('Unauthorized','Error');
  }
}

if(props?.id){
  loading.value = true;
  getEditProductDetails(props?.id)
  .then((data) => {
    title.value = data?.title ?? '',
    description.value = data?.description ?? '',
    price.value = data?.price ?? '',
    previewImage.value = `/storage/${data?.image}` ?? ''
    loading.value = false;
  });
}

function handleImageUpload(e) {
  const file = e.target.files[0];
  if(file){
    imageFile.value = file;
    previewImage.value = URL.createObjectURL(file);
  } else {
    imageFile.value = null;
    previewImage.value = null;
  }
}

async function handleSubmit() {
  // if(can('add','product') || can('edit','product') ){
    const formData = new FormData();
    formData.append('title', title.value);
    formData.append('description', description.value);
    formData.append('price', price.value);

    // Only append image if a file was selected
    if (imageFile.value) {
      formData.append('image', imageFile.value);
    }

    try {
      let res;
      if (props?.id) {
        if(imageFile.value){
          res = await axios.post(`/api/product/update/${props.id}`, formData, {
            headers: { 'Content-Type': 'multipart/form-data' }
          });
        } else {  
          res = await axios.post(`/api/product/update/${props.id}`,formData);
        }
      } else {
        res = await axios.post('/api/product/create', formData, {
          headers: { 'Content-Type': 'multipart/form-data' }
        });
      }

      if(res.data.success){
        title.value = '';
        description.value = '';
        price.value =  '';
        imageFile.value = null;
        previewImage.value = null;
        errorMessage.value = [];
        
        // Show success toastr message
        toastr.success(res.data.message, 'Success', {
          timeOut: 2000,
          progressBar: true,
          closeButton: true
        });

        if(props?.id){
          router.push({name :'product'});
        }

      } else {
        toastr.error(res.data.message, 'Error', {
          timeOut: 2000,
          progressBar: true,
          closeButton: true
        });
      }
    } catch(err){
      if (err.response && err.response.data) {
        if(err.response.data.errors){
          errorMessage.value = err.response.data.errors;
        } else if (err.response.data.message) {
          toastr.error(err.response.data.message, 'Error', {
            timeOut: 3000,
            progressBar: true,
            closeButton: true
          });
        } else {
          console.log(err);
        }
      } else {
        console.log(err);
      }
    }
  // } else {
  //   router.push({name :'login'});
  //   userStore.loggedIn = false;
  //   toastr.error('Unauthorized','Error');
  // }
}
</script>