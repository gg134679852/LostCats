<template>
  <NavBarVue />
  <div class="app__wrap">
    <div class="app__router-view__wrap">
      <router-view />
    </div>
    <div class="app__FooterComponent__wrap">
      <FooterComponent />
    </div>
  </div>
</template>
<script>
import FooterComponent from '../components/FooterComponent.vue'
import NavBarVue from '../components/NavBar.vue'
import { Toast } from '../utils/helpers'
export default {
  components: {
    FooterComponent,
    NavBarVue
  },
  provide () {
    return {
      Toast
    }
  }
}
</script>
<style lang="scss">
@import '../scss/all.scss';
@import '../../../css/app.scss';
@import '../scss/FooterComponent.scss'
</style>
