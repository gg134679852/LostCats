<template>
  <router-view/>
  <FooterComponentVue />
</template>
<script>
import { Toast } from '../utils/helpers'
import FooterComponentVue from '../components/FooterComponent.vue'
export default {
  components: {
    FooterComponentVue
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
