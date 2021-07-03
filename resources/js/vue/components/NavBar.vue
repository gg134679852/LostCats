<template>
 <nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container">
    <router-link
      class="navbar-brand"
      to="/"
    >
      <i class="fas fa-cat"></i> 迷途喵星人
    </router-link>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
    </div>
    <router-link to="/login" class="btn btn-primary" v-if="!isAuthenticated">
  登陸
</router-link>
 <router-link to="/login" class="btn btn-primary p-1" v-if="isAuthenticated">
  會員頁面
</router-link>
 <button  class="btn btn-primary p-1" v-if="isAuthenticated" @click="logout">
  登出
</button>
  </div>
</nav>
</template>

<script>
import { mapState } from 'vuex'
import {apiHelper,Toast} from './../utils/helpers'
import axios from 'axios'
const getToken = () => localStorage.getItem('token')
export default {
 computed: {
    ...mapState(['currentUser', 'isAuthenticated'])
  },
  methods: {
    logout(){
      axios.get('/sanctum/csrf-cookie')
      .then(response => {
       apiHelper.get('logout',{
      headers: { Authorization: `Bearer ${getToken()}`}})
      .then(()=>{
        this.$store.commit('revokeAuthentication')
        Toast.fire({
          icon: 'success',
          title:'成功登出'
        })
      })
    })
  }
 }
}
</script>