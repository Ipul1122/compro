import { createApp } from "vue";
import { createI18n } from 'vue-i18n';
import { createHead } from '@unhead/vue/client';
import { createPinia } from "pinia";
// import ( create)

import App from "./App.vue";
import "./style.css";
import router from "./router";
import en from './locales/en.json'
import id from './locales/id.json'
import axios from 'axios';

// Konfigurasi i18n
const i18n = createI18n({
    legacy: false,         
  globalInjection: true,
  locale: 'id', 
  fallbackLocale: 'en', 
  messages: {
    en,
    id
  }
})

// Gunakan environment variable untuk URL API
const apiBaseUrl = import.meta.env.VITE_API_BASE_URL || 'http://localhost:8000/api';
axios.defaults.baseURL = apiBaseUrl.replace(/\/api\/?$/, '');
axios.defaults.withCredentials = true; 
axios.defaults.withXSRFToken = true;


const app = createApp(App);
const head = createHead()

app.use(createPinia());
app.use(router);
app.use(head)
app.use(i18n)

app.mount("#app");
