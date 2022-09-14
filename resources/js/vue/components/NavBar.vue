<template>
 <nav class="navbar__wrap">
<div class="navbar__web-name">
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
      <a class="nav-link" aria-current="page" href="#">關於我們</a>
    </li>
    <li>
     <a aria-current="page" href="#">喵星人列表</a>
    </li>
    <li>
     <a href="#" v-if="this.$store.state.isAuthenticated">會員頁面</a>
     <a href="#" v-else>登陸</a>
    </li>
  </ul>
</div>
<div class="navbar__linkList__mobile" :class="{open:isOpen}">
  <ul class="navbar-nav">
     <li>
      <a class="nav-link" aria-current="page" href="#">關於我們</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" aria-current="page" href="#">喵星人列表</a>
    </li>
    <li>
      <a class="nav-link" aria-current="page" href="#" v-if="this.$store.state.isAuthenticated">會員頁面</a>
      <a class="nav-link" aria-current="page" href="#" v-else>登陸頁面</a>
    </li>
  </ul>
</div>
</nav>
</template>
<script>
export default {
  data () {
    return ({
      isOpen: false
    })
  },
  methods: {
    burgerButtonClick () {
      this.isOpen === false ? this.isOpen = true : this.isOpen = false
    }
  }
}
</script>
<style lang="scss">
@import '../scss/NavBar.scss'
</style>
