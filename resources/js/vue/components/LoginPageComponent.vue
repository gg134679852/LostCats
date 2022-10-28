<template>
  <loading-icon :active="isLoading" />
    <div class="formBody">
    <div class="formBody__login" v-if="formType === 'login'">
      <div class="formBody__login__form">
       <form class="row justify-content-center" @submit.stop.prevent="login">
      <div class="col-md-6">
        <div class="mb-2">
          <label for="inputEmail" class="form-label">電子郵件</label>
          <input type="email" id="inputEmail" class="form-control" required autofocus
            v-model="loginData.email" />
        </div>
        <div class="mb-2">
          <label for="inputPassword" class="form-label">密碼</label>
          <input type="password" id="inputPassword" class="form-control" required
            v-model="loginData.password" />
        </div>
        <div class="text-center mt-4">
          <button class="btn btn-lg btn-secondary btn-block" type="submit">登入</button>
        </div>
      </div>
    </form>
    </div>
    <div class="formBody__login__bottom">
       <h2>還不是會員？</h2>
       <a class="formBody__login__button" href="#"  @click.stop.prevent="switcher('singUp')">註冊</a>
    </div>
    </div>
    <div class="formBody__singUp" v-else-if="formType === 'singUp'">
        <div class="formBody__singUp__form">
       <form class="row justify-content-center" @submit.stop.prevent="singUp">
      <div class="col-md-6">
          <div class="mb-2">
          <label for="inputName" class="form-label">名稱</label>
          <input type="text" id="inputName" class="form-control"  required autofocus
            v-model="singUpData.name" />
          </div>
        <div class="mb-2">
          <label for="inputEmail" class="form-label">電子郵件</label>
          <input type="email" id="inputEmail" class="form-control"  required autofocus
            v-model="singUpData.email" />
        </div>
        <div class="mb-2">
          <label for="inputPassword" class="form-label">密碼</label>
          <input type="password" id="inputPassword" class="form-control"  required
            v-model="singUpData.password" />
        </div>
         <div class="mb-2">
          <label for="inputPassword" class="form-label">確認密碼</label>
          <input type="password" id="inputPassword" class="form-control"  required
            v-model="singUpData.password_confirmation" />
        </div>
        <div class="text-center mt-4">
          <button class="btn btn-lg btn-secondary btn-block" type="submit">註冊</button>
        </div>
      </div>
    </form>
    </div>
    <div class="formBody__singUp__bottom">
       <h2>已經是會員？</h2>
       <a class="formBody__singUp__button" href="#"  @click.stop.prevent="switcher('login')">登入</a>
    </div>
     </div>
    </div>
</template>
<script>
export default {
  data () {
    return {
      loginData: {
        email: '',
        password: ''
      },
      singUpData: {
        name: '',
        email: '',
        password: '',
        password_confirmation: ''
      },
      isLoading: false,
      formType: 'login'
    }
  },
  inject: ['Toast'],
  methods: {
    login () {
      this.isLoading = true
      this.$axiosHelper.post('user/login', {
        email: this.loginData.email,
        password: this.loginData.password
      })
        .then((obj) => {
          this.Toast.fire({
            icon: obj.data.icon,
            title: obj.data.message
          })
          if (obj.data.icon === 'success') {
            this.$store.dispatch('setUser', obj.data)
            this.$store.state.currentUser.isAdmin ? (this.$router.push('/admin/dashboard/datalist')) : (this.$router.push('/'))
          }
          this.isLoading = false
        })
    },
    singUp () {
      this.isLoading = true
      this.$axiosHelper.post('user/singup', {
        name: this.singUpData.name,
        email: this.singUpData.email,
        password: this.singUpData.password,
        password_confirmation: this.singUpData.password_confirmation
      })
        .then((obj) => {
          this.Toast.fire({
            icon: 'success',
            title: '成功註冊'
          })
          this.formType = 'login'
          this.isLoading = false
        })
        .catch(err => {
          if (err.response.data.errors) {
            const errorMessage = err.response.data.errors
            const objectKey = Object.keys(errorMessage)
            objectKey.forEach((key) => {
              return this.Toast.fire({
                icon: 'warning',
                title: `${errorMessage[key]}`
              })
            })
            this.isLoading = false
          }
        })
    },
    switcher (type) {
      switch (type) {
        case 'singUp':{
          this.formType = 'singUp'
          break
        }
        case 'login':{
          this.formType = 'login'
          break
        }
      }
    }
  }
}
</script>
<style lang="scss">
  @import '../scss/LoginPageComponent.scss';
</style>
