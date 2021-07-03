<template>
<div class="container">
  <form class="text-center" @submit.prevent.stop="singup">
  <div class="input-group mb-3">
  <span class="input-group-text" id="basic-addon1">名字</span>
  <input type="text" class="form-control" aria-describedby="basic-addon1"
  v-model="singUpData.name"
  >
</div>
<div class="input-group mb-3">
  <span class="input-group-text" id="basic-addon1">電子郵件</span>
  <input type="text" class="form-control"  aria-describedby="basic-addon1"
  v-model="singUpData.email"
  >
</div>
<div class="input-group mb-3">
  <span class="input-group-text" id="basic-addon1">密碼</span>
  <input type="text" class="form-control"  aria-describedby="basic-addon1"
   v-model="singUpData.password"
  >
</div>
<div class="input-group mb-3">
  <span class="input-group-text" id="basic-addon1">確認密碼</span>
  <input type="text" class="form-control" aria-describedby="basic-addon1"
  v-model="singUpData.password_confirmation"
  >
</div>
<button type="submit" class="btn btn-primary" :disabled="isProcessing" >送出</button>
</form>
</div>
</template>
<script>
import axios from 'axios'
import {apiHelper,Toast} from './../utils/helpers'

export default {
  data(){
    return({
      singUpData:{
        name:'',
        email:'',
        password:'',
        password_confirmation:''
      },
      isProcessing: false
    })
  },
 methods: {
   singup(){
     this.isProcessing = true
     axios.get('/sanctum/csrf-cookie').then(response => {
       apiHelper.post('singup',{
       name:this.singUpData.name,
         email: this.singUpData.email,
         password:this.singUpData.password,
         password_confirmation:this.singUpData.password_confirmation
      })
      .then((obj)=>{
        Toast.fire({
          icon: 'success',
          title:'成功註冊'
        })
        this.$router.push('/')
      })
       .catch(error => {
     if(error.response){
       const input = ['password','email','name']
       input.forEach((input)=>{
         this.isProcessing = false
         Toast.fire({
          icon: 'warning',
          title:error.response.data.errors[input][0]
        })
       })
     }
 })  
   })
  }
 }
}
</script>