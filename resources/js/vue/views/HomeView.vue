<template>
<div class="banner">
<h1 class="animate__animated animate__bounceIn">找到屬於你的，喵星人</h1>
</div>
<NavBarVue />
<div class="benefit-content-title">
<h2>『 變成貓奴，好處多多 』</h2>
<div class="benefit-content-title__image">
  <img data-aos="fade-up" data-aos-anchor-placement="center-bottom" src="../../../../public/img/manja-vitolic-gKXKBY-C-Dk-unsplash.webp">
</div>
</div>
<div class="benefit-content__wrap">
  <div class="benefit-content">
    <img src="../../../../public/img/silvana-carlos-RThwIDHHV90-unsplash.jpg" alt="喵星人與人類">
    <hr>
    <div class="benefit-content__text">
      <h2>緩解身心壓力和焦慮</h2>
      <br>
      <p>根據科學家表示,如果每天花15到30分鐘撫摸貓咪或是和貓咪互動,我們將會更快樂。與狗狗相比,貓咪是較低維護的寵物,意味著飼養貓的壓力較小,對於無法花大量時間陪伴的人,貓咪會是理想的寵物喔!</p>
    </div>
  </div>
   <div class="benefit-content">
    <img src="../../../../public/img/carolina-sanchez-Lh_TJTQVPjM-unsplash.jpg" alt="喵星人與人類">
    <hr>
    <div class="benefit-content__text">
      <h2>貓咪滿足陪伴的需求</h2>
      <br>
      <p>
        貓咪看似冷淡無情，其實他們可是非常有感情的喔！貓咪可以滿足我們陪伴的需求減少心靈孤單之外，貓咪也會讓人感受到類似伴侶的陪伴。</p>
    </div>
  </div>
   <div class="benefit-content">
    <img src="../../../../public/img/japheth-mast-Ga6z9QD8yvw-unsplash.jpg" alt="喵星人與人類">
    <hr>
    <div class="benefit-content__text">
      <h2>貓咪撫平我們心靈的傷痛</h2>
      <br>
      <p>失去摯愛感到令人難以置信的痛苦，但最好的應對方法是擁有一隻寵物！貓咪已被證明可以幫助人們更快地克服失去的痛苦，並且表現出較少的身體疼痛。</p>
    </div>
  </div>
</div>
<div class="catListLink__wrap">
  <div class="catListLink__content">
     <h2>以領養代替購買，開始尋找喵星人吧</h2>
 <button type="button" class="btn btn-success">開始</button>
  </div>
</div>
</template>
<script>
import NavBarVue from '../components/NavBar.vue'
import AOS from 'aos'
export default {
  components: {
    NavBarVue
  },
  mounted () {
    AOS.init()
  }
}
</script>
<style lang="scss">
  @import'../scss/HomeView.scss';
</style>
