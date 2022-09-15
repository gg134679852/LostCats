<template>
 <NavBarVue />
 <div class="aboutUs-content__banner">
  <div class="aboutUs-content__banner__title">
     <h2>關於我們</h2>
  </div>
 </div>
 <div class="aboutUs-content__wrap">
  <div class="aboutUs-content">
    <div class="aboutUs-content__left">
      <img src="../../../../public/img/tong-nguyen-van-3hvmrZJTfJU-unsplash.jpg" alt="">
    </div>
    <div class="aboutUs-content__right">
      <h2>網站的初衷</h2>
      <hr>
      <p>根據統計動物收容所的一半以上動物都是被棄養，流浪動物棄養的問題到現在並未停止過，最近因疫情變得更加嚴重，收容所可容量的動物數量也漸漸減少，本網站的初衷是藉由網站社群呼籲社會大眾以領養代替購買，減少動物收容所的負擔</p>
    </div>
  </div>
 </div>
 <div class="aboutUs-content__banner__bottom">
  <div class="aboutUs-content__banner__bottom__title">
     <h2>請以領養代替購買</h2>
     <br>
     <h2>給喵星人溫暖的家</h2>
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
