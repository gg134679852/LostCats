require('./bootstrap');

require('alpinejs');

require('./bootstrap');

import Vue from 'vue'
import VueRouter from 'vue-router'
import router from './vue/router/routes'

const swal = (window.swal = require("sweetalert2"));

Vue.use(VueRouter);

const app = new Vue ({
  el:'#app',
  router
});