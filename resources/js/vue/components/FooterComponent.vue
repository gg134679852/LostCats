<template>
 <footer>
  <div class="footer__wrap">
    <div class="footer__links">
       <ul>
     <li>
      <a aria-current="page" href="#">關於我們</a>
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
    <div class="footer__shelterInfo">
      <h2>地址：220新北市板橋區板城路28-1號</h2>
      <h2>電話：0289662158</h2>
      <h2>營業時間：上午10:00 - 下午4:00</h2>
    </div>
    <div class="footer__socialLinks">
        <ul>
     <li>
      <a href="#"><i class="fab fa-facebook-square"></i></a>
    </li>
    <li>
     <a href="#">
     <i class="fab fa-twitter-square"></i>
    </a>
    </li>
    <li>
     <a aria-current="page" href="#">
      <i class="fab fa-instagram-square"></i>
    </a>
    </li>
     <li>
     <a aria-current="page" href="#"><i class="fab fa-line"></i></a>
    </li>
  </ul>
    </div>
     <h2>僅供練習用，無商業用途</h2>
  </div>
</footer>
</template>
<style lang="scss">
@import'../scss/FooterComponent.scss';
</style>
