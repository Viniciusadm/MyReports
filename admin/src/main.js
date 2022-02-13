import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import Toast from "vue-toastification";
import "vue-toastification/dist/index.css";

Vue.use(Toast, {
    transition: "Vue-Toastification__bounce",
    maxToasts: 6,
    newestOnTop: true,
    hideProgressBar: true,
    timeout: 1500,
    pauseOnHover: false,
    filterBeforeCreate: (toast, toasts) => {
        if (toasts.filter(
            t => t.type === toast.type
        ).length !== 0) {
            // Returning false discards the toast
            return false;
        }
        // You can modify the toast if you want
        return toast;
    }
});

Vue.config.productionTip = false

new Vue({
    router,
    store,
    render: h => h(App)
}).$mount('#app')
