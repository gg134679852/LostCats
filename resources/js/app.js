require('./bootstrap');

import Vue from 'vue'
import store from './vue/store/index'
import router from './vue/router/routes'
import App from './vue/views/App'
import * as VueGoogleMaps from 'vue2-google-maps';

const swal = (window.swal = require("sweetalert2"));

Vue.use(VueGoogleMaps, {
  load: {
    key: process.env.API_KEY,
    libraries: 'places'
  }
})

const app = new Vue ({
  el:'#app',
  components:{
    App
  },
  router,
  store
}).$mount('#app');