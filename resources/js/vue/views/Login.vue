<template>
 <div class="container">
   <form class="text-center" @submit.prevent.stop="login">
<div class="input-group mb-3">
  <span class="input-group-text" id="basic-addon1">電子郵件</span>
  <input type="email" class="form-control" aria-describedby="basic-addon1"
  v-model="loginData.email"
  >
</div>
<div class="input-group mb-3">
  <span class="input-group-text" id="basic-addon1">密碼</span>
  <input type="password" class="form-control"
  v-model="loginData.password"
  aria-describedby="basic-addon1">
</div>
<button type="submit" class="btn btn-primary" :disabled="isProcessing">登陸</button>
<router-link class="btn btn-primary" to="/singup">註冊</router-link>
</form>
</div>
</template>
<script>
import axios from 'axios'
import {apiHelper,Toast} from './../utils/helpers'
export default {
  data(){
    return({
      loginData:{
         email:'',
         password:''
       },
       isProcessing: false
    })
  },
  methods:{
     login(){
      this.isProcessing = true
      axios.get('/sanctum/csrf-cookie')
      .then(response => {
      apiHelper.post('login',{
         email: this.loginData.email,
         password:this.loginData.password
      })
      .then((obj)=>{
         this.$store.commit('setUser',obj.data.userData)
         this.$router.push('/')
         localStorage.setItem('token',obj.data.token)
        Toast.fire({
          icon: 'success',
          title:obj.data.message
        })
      })
    .catch(error => {
     if(error.response){
       const input = ['password','email']
       input.forEach((input)=>{
         Toast.fire({
          icon: 'warning',
          title:error.response.data.errors[input][0]
        })
       })
     }
     this.isProcessing = false
     })
    })
  }
 }
}
</script>