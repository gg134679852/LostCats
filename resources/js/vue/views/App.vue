<template>
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
import { Toast } from '../utils/helpers'
import FooterComponent from '../components/FooterComponent.vue'
export default {
  components: {
    FooterComponent
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
