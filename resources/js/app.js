require('./bootstrap');

import { createApp } from 'vue'
import App from './vue/views/App.vue'
import router from './vue/router'
import store from './vue/store'
import VueAxios from 'vue-axios'
import Loading from 'vue3-loading-overlay'
import 'vue3-loading-overlay/dist/vue3-loading-overlay.css'
import { axiosHelper } from './vue/utils/helpers'
require('./bootstrap')

const app = createApp(App)
app.use(VueAxios, { $axiosHelper: axiosHelper })
app.use(store)
app.use(router)
app.component('loading-icon', Loading)
app.mount('#app')