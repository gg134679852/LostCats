<template>
  <div class="banner">
    <h1 class="animate__animated animate__bounceIn">找到屬於你的，喵星人</h1>
  </div>
  <div class="benefit-content-title">
    <h2 data-aos="fade-down" data-aos-once="true">『 變成貓奴，好處多多 』</h2>
  </div>
  <div class="benefit-content__wrap">
    <ul class="benefit-content">
      <li>
        <div class="benefit-content__image">
          <img src="../../../../public/img/silvana-carlos-RThwIDHHV90-unsplash.jpg" alt="喵星人與人類">
        </div>
        <div class="benefit-content__text">
          <div class="benefit-content__text__wrap">
            <h2>緩解身心壓力和焦慮</h2>
            <hr>
            <p>根據科學家表示,如果每天花15到30分鐘撫摸貓咪或是和貓咪互動,我們將會更快樂。與狗狗相比,貓咪是較低維護的寵物,意味著飼養貓的壓力較小,對於無法花大量時間陪伴的人,貓咪會是理想的寵物喔!</p>
          </div>
        </div>
      </li>
      <li>
        <div class="benefit-content__image">
          <img src="../../../../public/img/carolina-sanchez-Lh_TJTQVPjM-unsplash.jpg" alt="喵星人與人類">
        </div>
        <div class="benefit-content__text">
          <div class="benefit-content__text__wrap">
            <h2>貓咪滿足陪伴的需求</h2>
            <hr>
            <p>
              貓咪看似冷淡無情，其實他們可是非常有感情的喔！貓咪可以滿足我們陪伴的需求減少心靈孤單之外，貓咪也會讓人感受到類似伴侶的陪伴。</p>
          </div>
        </div>
      </li>
      <li>
        <div class="benefit-content__image">
          <img src="../../../../public/img/japheth-mast-Ga6z9QD8yvw-unsplash.jpg" alt="喵星人與人類">
        </div>
        <div class="benefit-content__text">
          <div class="benefit-content__text__wrap">
            <h2>貓咪撫平我們心靈的傷痛</h2>
            <hr>
            <p>失去摯愛感到令人難以置信的痛苦，但最好的應對方法是擁有一隻寵物！貓咪已被證明可以幫助人們更快地克服失去的痛苦，並且表現出較少的身體疼痛。</p>
          </div>
        </div>
      </li>
    </ul>
  </div>
  <div class="userComment__title">
    <h2>使用者感想</h2>
  </div>
  <div class="userComment__warp">
    <ul class="userComment">
      <li>
        <div class="userComment__image">
          <img src="../../../../public/img/avatar.png">
        </div>
        <div class="userComment__comment">
          <div class="userComment__comment__warp">
            <h2>Jack</h2>
            <hr>
            <p>謝謝這個平台，可以讓我經由捐獻來幫助台灣各地的貓星人，支持以收養代替購買</p>
          </div>
        </div>
      </li>
      <li>
        <div class="userComment__image">
          <img src="../../../../public/img/avatar.png">
        </div>
        <div class="userComment__comment">
          <div class="userComment__comment__warp">
            <h2>Ken</h2>
            <hr>
            <p>謝謝這個網站，讓我找到命中注定的喵星人，變成貓奴感覺真好！</p>
          </div>
        </div>
      </li>
      <li>
        <div class="userComment__image">
          <img src="../../../../public/img/avatar.png">
        </div>
        <div class="userComment__comment">
          <div class="userComment__comment__warp">
            <h2>Jerry</h2>
            <hr>
            <p>多虧了收藏功能，可以讓我追蹤想收養的的貓咪的狀態，搬家之後有了空間，終於可以變成貓奴了</p>
          </div>
        </div>
      </li>
    </ul>
  </div>
  <div class="singUpLink__wrap">
    <div class="singUpLink__content">
      <h2>註冊會員</h2>
      <h2>收藏喜歡的喵星人</h2>
      <button type="button" class="btn btn-success" @click="linkClick('/login')">註冊</button>
    </div>
  </div>
</template>
<script>
import AOS from 'aos'
export default {
  mounted () {
    AOS.init()
  },
  methods: {
    linkClick (url) {
      this.$router.push(url)
    }
  }
}
</script>
<style lang="scss">
@import '../scss/LandingPageComponent.scss';
</style>
