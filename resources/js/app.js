require('./bootstrap');


require('./bootstrap');

import Vue from 'vue'
import VueRouter from 'vue-router'
import router from './vue/router/routes'
import * as VueGoogleMaps from 'vue2-google-maps';

const swal = (window.swal = require("sweetalert2"));

Vue.use(VueRouter);

Vue.use(VueGoogleMaps, {
  load: {
    key: process.env.MIX_API_KEY,
    libraries: 'places'
  }
})

const app = new Vue ({
  el:'#app',
  router
});