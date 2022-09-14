<template>
 <NavBarVue />
 <div class="aboutUs-content__banner">
  <div class="aboutUs-content__banner__title">
     <h2>關於我們</h2>
  </div>
 </div>
 <div class="aboutUs-content__warp">
  <div class="aboutUs-content__top">
    <div class="aboutUs-content__top__left">
      <img src="../../../../public/img/tong-nguyen-van-3hvmrZJTfJU-unsplash.jpg" alt="">
    </div>
    <div class="aboutUs-content__top__right">
      <h2>網站的初衷</h2>
      <hr>
      <p>根據統計動物收容所的一半以上動物都是被棄養，流浪動物棄養的問題到現在並未停止過，最近因疫情變得更加嚴重，收容所可容量的動物數量也漸漸減少，本網站的初衷是藉由網站社群呼籲社會大眾以領養代替購買，減少動物收容所的負擔</p>
    </div>
  </div>
  <hr>
  <div class="aboutUs-content__bottom">
    <div class="aboutUs-content__bottom__left">
      <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d14464.582477492957!2d121.4482077!3d24.9951674!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x6d4b927a2eb17556!2z5p2_5qmL5YuV54mp5LmL5a62!5e0!3m2!1szh-TW!2stw!4v1663136885140!5m2!1szh-TW!2stw"  allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
    <div class="aboutUs-content__bottom__right">
      <h2>聯絡我們</h2>
      <hr>
      <h3>地址：220新北市板橋區板城路28-1號</h3>
      <br>
      <h3>電話：0289662158</h3>
    </div>
  </div>
 </div>
</template>
<script>
import NavBarVue from '../components/NavBar.vue'
export default {
  components: {
    NavBarVue
  }
}
</script>
<style lang="scss">
  @import '../scss/AboutUs.scss';
</style>
