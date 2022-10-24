<template>
 <nav class="navbar__wrap">
<div class="navbar__web-name" @click.prevent="linkClick('/')">
  <h3 class="navbar-brand__title">
    <i class="fas fa-cat"></i> 迷途喵星人
  </h3>
</div>
 <div class="navbar__mobile" :class="{open:isOpen}" @click="burgerButtonClick">
    <div class="navbar__mobile__burger-button"></div>
  </div>
<div class="navbar__linkList__desktop">
  <ul>
     <li>
      <a aria-current="page" href="#" @click.prevent="linkClick('/aboutUs')">關於我們</a>
    </li>
    <li>
     <a aria-current="page" href="#" @click.prevent="linkClick('/catList')">喵星人列表</a>
    </li>
    <li>
     <a href="#" v-if="this.$store.state.isAuthenticated" @click.prevent="linkClick('/userPage')">會員頁面</a>
     <a href="#" v-else @click.prevent="linkClick('/login')">登入</a>
    </li>
     <li v-if="this.$store.state.isAuthenticated">
     <a href="#" @click.prevent="logout">登出</a>
    </li>
  </ul>
</div>
<div class="navbar__linkList__mobile" :class="{open:isOpen}">
  <ul class="navbar-nav">
   <li>
      <a class="nav-link" aria-current="page" href="#" @click.prevent="linkClick('/aboutUs')">關於我們</a>
    </li>
    <li>
     <a aria-current="page" href="#" @click.prevent="linkClick('/catList')">喵星人列表</a>
    </li>
    <li>
     <a href="#" v-if="this.$store.state.isAuthenticated" @click.prevent="linkClick('/userPage')">會員頁面</a>
     <a href="#" v-else @click.prevent="linkClick('/login')">登入</a>
    </li>
     <li v-if="this.$store.state.isAuthenticated">
     <a href="#" @click.prevent="logout">登出</a>
    </li>
  </ul>
</div>
</nav>
</template>
<script>
export default {
  inject: ['Toast'],
  data () {
    return ({
      isOpen: false
    })
  },
  methods: {
    burgerButtonClick () {
      this.isOpen === false ? this.isOpen = true : this.isOpen = false
    },
    linkClick (url) {
      this.$router.push(url)
    },
    logout () {
      this.$axiosHelper
        .post('user/logout')
        .then(() => {
          this.$store.commit('revokeAuthentication')
          this.Toast.fire({
            icon: 'success',
            title: '成功登出'
          })
          this.$router.push('/')
        })
    }
  }
}
</script>
<style lang="scss">
@import '../scss/NavBar.scss'
</style>
