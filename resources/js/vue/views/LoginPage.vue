<template>
  <div class="container mt-5">
    <loading-icon :active="isLoading" />
    <form class="row justify-content-center" @submit.stop.prevent="signIn">
      <div class="col-md-6">
        <h1 class="h3 mb-3 font-weight-normal">請先登入</h1>
        <div class="mb-2">
          <label for="inputEmail" class="sr-only">Email address</label>
          <input type="email" id="inputEmail" class="form-control" placeholder="Email address" required autofocus
            v-model="loginData.email" />
        </div>
        <div class="mb-2">
          <label for="inputPassword" class="sr-only">Password</label>
          <input type="password" id="inputPassword" class="form-control" placeholder="Password" required
            v-model="loginData.password" />
        </div>

        <div class="text-end mt-4">
          <button class="btn btn-lg btn-primary btn-block" type="submit" @click.stop.prevent="login">登入</button>
        </div>
      </div>
    </form>
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
      isLoading: false
    }
  },
  inject: ['Toast'],
  methods: {
    login () {
      this.isLoading = true
      this.$axiosHelper.post('login', {
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
            this.$store.state.currentUser.isAdmin ? (this.$router.push('/admin/dashboard/catlist')) : (this.$router.push('/'))
          }
          this.isLoading = false
        })
    }
  }
}
</script>
