<template>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">管理者後台</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="#">貓咪列表</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">捐款紀錄</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">使用者列表</a>
          </li>
           <li class="nav-item">
            <a class="nav-link" href="#" @click.prevent="logout">登出</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</template>
<script>
export default {
  inject: ['Toast'],
  methods: {
    logout () {
      this.$axiosHelper.post('/logout')
        .then(() => {
          this.$store.dispatch('revokeAuthentication')
          this.$router.push('/login')
          this.Toast.fire({
            icon: 'success',
            title: '成功登出'
          })
        })
        .catch((err) => {
          this.Toast.fire({
            icon: 'error',
            title: '發生錯誤，請查看開發者工具'
          })
          console.log(err)
          this.isLoading = false
        })
    }
  }
}
</script>
