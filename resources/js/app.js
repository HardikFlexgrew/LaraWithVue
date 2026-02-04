import './bootstrap';
import '../css/app.css';
import { createApp, watch, ref } from 'vue';
import { createPinia } from 'pinia';
import App from './App.vue';
import router from './routes';
import { createPersistedState } from 'pinia-plugin-persistedstate';
import { abilitiesPlugin } from '@casl/vue';
import ability from './services/ability';   
import { User } from './store';
import 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js';

const app = createApp(App); 
const pinia = createPinia();
app.use(router);
pinia.use(createPersistedState());
app.use(pinia);

const userStore = User();
const abilities = ref(ability(userStore.role));

watch(
    () => userStore.role,
    (newRole) => {
        const newAbilities = ability(newRole);
        abilities.value.update(newAbilities.rules);
    }
);

app.use(abilitiesPlugin, abilities.value);
app.mount("#app");  