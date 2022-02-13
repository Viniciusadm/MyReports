import { createApp } from "vue";
import App from "./App.vue";
import router from "./router";
import store from "./store";

import Toast from "vue-toastification";
import "vue-toastification/dist/index.css";

const options = {
    transition: "Vue-Toastification__bounce",
    maxToasts: 3,
    newestOnTop: true,
    hideProgressBar: true,
    timeout: 1500,
    pauseOnHover: false,
};

createApp(App).use(store).use(router).use(Toast, options).mount("#app");
