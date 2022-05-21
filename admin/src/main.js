import { createApp } from "vue";
import App from "./App.vue";
import router from "./router";
import store from "./store";

import Toast from "vue-toastification";
import "vue-toastification/dist/index.css";

import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';


const options = {
    transition: "Vue-Toastification__bounce",
    maxToasts: 3,
    newestOnTop: true,
    hideProgressBar: true,
    timeout: 1500,
    pauseOnHover: false,
};

createApp(App)
    .use(store)
    .use(router)
    .use(Toast, options)
    .use(VueSweetalert2)
    .mount("#app");
