import { createApp } from "vue";
import App from "./App.vue";
import router from "./router";
import axios from "axios";
import 'bootstrap/dist/css/bootstrap.min.css'
import 'bootstrap' // JS components like dropdown, modal

axios.defaults.baseURL = "http://localhost:8001/api"; // Laravel API base
axios.defaults.withCredentials = true;

const app = createApp(App);
app.config.globalProperties.$axios = axios;
app.use(router);
app.mount("#app");
